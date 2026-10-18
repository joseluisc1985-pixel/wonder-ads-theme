"""Build posts.json from the Markdown articles in ./articles (front matter + Markdown)."""
import json, re, pathlib, markdown

ROOT = pathlib.Path(__file__).parent
CATEGORIES = [
    {"name": "Luxury Marketing", "slug": "luxury-real-estate-marketing", "description": "Playbooks for marketing luxury listings: cinematic video, social media, ads, branding and listing presentations."},
    {"name": "Market Insights", "slug": "luxury-market-insights", "description": "Luxury real estate prices, trends and market reports for realtors, sellers and buyers."},
    {"name": "Investing", "slug": "luxury-real-estate-investing", "description": "Ideas and checklists for investing in luxury real estate, from waterfront homes to pre-construction condos."},
]

def parse(path):
    text = path.read_text(encoding="utf-8")
    m = re.match(r"^---\n(.*?)\n---\n(.*)$", text, re.S)
    meta = {}
    for line in m.group(1).splitlines():
        k, v = line.split(":", 1)
        meta[k.strip()] = v.strip()
    body = m.group(2)
    html = markdown.markdown(body, extensions=["tables", "md_in_html", "sane_lists"])
    html = re.sub(r"<table>", '<div class="table-scroll"><table>', html)
    html = html.replace("</table>", "</table></div>")
    words = len(re.sub(r"<[^>]+>", " ", html).split())
    faq = []
    fm = re.search(r"<h2>Frequently asked questions</h2>(.*?)(?=<h2>|<div class=\"sources\"|<p class=\"disclaimer\"|$)", html, re.S)
    if fm:
        for q, a in re.findall(r"<h3>(.*?)</h3>\s*<p>(.*?)</p>", fm.group(1), re.S):
            faq.append({"q": re.sub(r"<[^>]+>", "", q).strip(), "a": re.sub(r"<[^>]+>", "", a).strip()})
    meta.update(html=html, words=words, faq=faq, status=meta.get("status", "draft"))
    return meta

def main():
    posts = [parse(p) for p in sorted((ROOT / "articles").glob("*.md"))]
    slugs = {p["slug"] for p in posts}
    for p in posts:
        assert len(p["seo_title"]) <= 62, (p["slug"], len(p["seo_title"]))
        assert len(p["meta"]) <= 165, (p["slug"], len(p["meta"]))
        assert p["faq"], p["slug"]
        for link in re.findall(r'href="post:([a-z0-9-]+)"', p["html"]):
            assert link in slugs, (p["slug"], link)
    (ROOT / "posts.json").write_text(json.dumps({"categories": CATEGORIES, "posts": posts}, ensure_ascii=False, indent=1), encoding="utf-8")
    for p in posts:
        print(f'{p["words"]:>5}  {p["category"]:<17} {p["slug"]}')
    print(len(posts), "posts,", sum(p["words"] for p in posts), "words")

if __name__ == "__main__":
    main()

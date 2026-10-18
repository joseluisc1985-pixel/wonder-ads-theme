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
    meta.update(html=html, words=words, faq=faq, status=meta.get("status", "publish"))
    return meta

def main():
    posts = [parse(p) for p in sorted((ROOT / "articles").glob("*.md"))]
    slugs = {p["slug"] for p in posts}
    import datetime, random
    # Spread publish dates from January 2026 to today, never before the newest report an article cites.
    rng = random.Random(2026)
    earliest = datetime.datetime(2026, 1, 6)
    latest = datetime.datetime(2026, 10, 16, 20, 0)
    source_dates = {
        "Redfin-Luxury-Spin-Off": datetime.datetime(2026, 7, 9),
        "luxury-home-prices-rise-amid-uptick": datetime.datetime(2026, 5, 28),
        "rismedia.com/2026/07/22": datetime.datetime(2026, 7, 24),
        "pgci-q2-2026": datetime.datetime(2026, 9, 28),
        "businessobserverfl.com/news/2026/sep/04": datetime.datetime(2026, 9, 7),
        "mpamag.com": datetime.datetime(2026, 7, 9),
        "nevadarealestategroup.com": datetime.datetime(2026, 9, 7),
    }
    free = []
    for p in posts:
        lo = max([earliest] + [d for k, d in source_dates.items() if k in p["html"]])
        if lo == earliest:
            free.append(p)
            continue
        when = lo + datetime.timedelta(seconds=rng.randint(0, int((latest - lo).total_seconds())))
        p["date"] = when.replace(hour=rng.randint(7, 18), minute=rng.randint(0, 59), second=0).strftime("%Y-%m-%d %H:%M:%S")
    # Evergreen posts: spread evenly across the whole period, in shuffled order, with a few days of jitter.
    rng.shuffle(free)
    step = (latest - earliest) / max(1, len(free))
    for i, p in enumerate(free):
        when = earliest + step * i + datetime.timedelta(days=rng.uniform(0, step.days * 0.8))
        p["date"] = when.replace(hour=rng.randint(7, 18), minute=rng.randint(0, 59), second=0).strftime("%Y-%m-%d %H:%M:%S")
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

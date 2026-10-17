(function () {
	'use strict';
	var WAc = window.WA || {};
	var $ = function (s, r) { return (r || document).querySelector(s); };
	var $$ = function (s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); };
	var money = function (n) { return '$' + Number(n).toLocaleString('en-US'); };

	/* Header */
	var header = $('[data-header]');
	var hero = $('[data-hero]');
	function onScroll() {
		if (!header) return;
		var limit = hero ? hero.offsetHeight - 90 : 0;
		header.classList.toggle('is-solid', !hero || window.scrollY > limit);
	}
	window.addEventListener('scroll', onScroll, { passive: true });
	onScroll();

	var toggle = $('[data-nav-toggle]');
	if (toggle) {
		toggle.addEventListener('click', function () {
			var open = document.body.classList.toggle('nav-open');
			toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
		});
		$$('[data-nav] a').forEach(function (a) {
			a.addEventListener('click', function () {
				document.body.classList.remove('nav-open');
				toggle.setAttribute('aria-expanded', 'false');
			});
		});
	}

	/* Reveal on scroll */
	var reveals = $$('.reveal');
	if ('IntersectionObserver' in window) {
		var io = new IntersectionObserver(function (entries) {
			entries.forEach(function (e) {
				if (e.isIntersecting) { e.target.classList.add('is-in'); io.unobserve(e.target); }
			});
		}, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });
		reveals.forEach(function (el) { io.observe(el); });

		// Play previews only while visible.
		var vio = new IntersectionObserver(function (entries) {
			entries.forEach(function (e) {
				var v = e.target;
				if (e.isIntersecting) { var p = v.play(); if (p && p.catch) p.catch(function () {}); } else { v.pause(); }
			});
		}, { threshold: 0.25 });
		$$('[data-preview], [data-lazy-video]').forEach(function (v) { vio.observe(v); });
	} else {
		reveals.forEach(function (el) { el.classList.add('is-in'); });
	}

	/* Hero sound */
	var heroVideo = $('[data-hero-video]');
	var soundBtn = $('[data-sound]');
	function setSound(on) {
		if (!heroVideo) return;
		heroVideo.muted = !on;
		if (on) { heroVideo.volume = 0.8; var p = heroVideo.play(); if (p && p.catch) p.catch(function () {}); }
		if (soundBtn) soundBtn.setAttribute('aria-pressed', on ? 'true' : 'false');
		if (soundBtn) soundBtn.querySelector('strong').textContent = on ? 'Sound off' : 'Sound on';
	}
	if (soundBtn) {
		soundBtn.addEventListener('click', function (e) { e.stopPropagation(); setSound(heroVideo.muted); });
	}
	if (hero && heroVideo) {
		// First tap anywhere in the hero (outside links) turns sound on.
		hero.addEventListener('click', function (e) {
			if (e.target.closest('a, button')) return;
			if (heroVideo.muted) setSound(true);
		});
	}

	/* Fullscreen example modal */
	var modal = $('[data-modal]');
	var modalVideo = $('[data-modal-video]');
	function closeModal() {
		if (!modal) return;
		modalVideo.pause();
		modalVideo.removeAttribute('src');
		modalVideo.load();
		modal.hidden = true;
		document.body.style.overflow = '';
	}
	$$('[data-play]').forEach(function (btn) {
		btn.addEventListener('click', function () {
			var src = btn.getAttribute('data-play');
			if (!src || !modal) return;
			if (heroVideo && !heroVideo.muted) setSound(false);
			modalVideo.src = src;
			modal.hidden = false;
			document.body.style.overflow = 'hidden';
			modalVideo.muted = false;
			var p = modalVideo.play(); if (p && p.catch) p.catch(function () {});
			if (window.matchMedia('(max-width: 900px)').matches && modalVideo.requestFullscreen) {
				modalVideo.requestFullscreen().catch(function () {});
			}
		});
	});
	if (modal) {
		$('[data-modal-close]').addEventListener('click', closeModal);
		modal.addEventListener('click', function (e) { if (e.target === modal) closeModal(); });
		document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !modal.hidden) closeModal(); });
	}

	/* Custom order calculator */
	var calc = $('[data-calc]');
	if (calc && WAc.pricing) {
		var P = WAc.pricing;
		var lengths = [30, 60, 90];
		var msg = $('[data-calc-msg]', calc);
		var storyToggle = calc.elements.storytelling;
		var scriptToggle = calc.elements.custom_script;
		var scriptBox = $('[data-script]', calc);

		function config() {
			var f = calc.elements;
			var formats = ['vertical'];
			$$('input[name="formats"]:checked:not([disabled])', calc).forEach(function (i) { formats.push(i.value); });
			var music = $('input[name="music"]:checked', calc);
			var style = $('input[name="story_style"]:checked', calc);
			var length = lengths[parseInt(calc.querySelector('[name="length"]').value, 10)] || 30;
			var cfg = {
				qty: parseInt(f.qty.value, 10) || 1,
				length: length,
				formats: formats,
				music: music ? music.value : 'cinematic',
				storytelling: storyToggle.checked,
				story_style: style ? style.value : 'warm',
				custom_script: !storyToggle.checked && scriptToggle.checked,
				script: scriptBox.value,
				actors: f.actors.checked,
				captions: f.captions.checked
			};
			var per = P.lengths[length];
			formats.forEach(function (x) { per += P.formats[x] || 0; });
			cfg.per_video = per;
			cfg.total = per * cfg.qty;
			return cfg;
		}

		function render() {
			// Storytelling and custom script are mutually exclusive.
			var story = storyToggle.checked;
			$('[data-story-styles]', calc).classList.toggle('is-off', !story);
			$('[data-script-toggle]', calc).classList.toggle('is-off', story);
			if (story) scriptToggle.checked = false;
			scriptBox.hidden = !scriptToggle.checked || story;

			var c = config();
			$('[data-out="qty"]', calc).textContent = c.qty + (c.qty === 1 ? ' video' : ' videos');
			$('[data-out="length"]', calc).textContent = c.length + ' seconds — ' + money(P.lengths[c.length]);
			$('[data-total]', calc).textContent = money(c.total);
			var extras = c.formats.length > 1 ? ' · ' + (c.formats.length - 1) + ' extra format' + (c.formats.length > 2 ? 's' : '') : '';
			$('[data-breakdown]', calc).textContent = c.qty + ' × ' + c.length + '-second video' + (c.qty > 1 ? 's' : '') + extras + ' · ' + money(c.per_video) + ' each';
		}

		calc.addEventListener('input', render);
		calc.addEventListener('change', render);
		render();

		calc.addEventListener('submit', function (e) {
			e.preventDefault();
			msg.textContent = '';
			msg.classList.remove('is-error');
			if (!WAc.checkout) {
				var c0 = config();
				msg.innerHTML = 'Online checkout is opening soon. Email <a href="mailto:' + WAc.email + '?subject=Custom%20order%20' + encodeURIComponent(money(c0.total)) + '">' + WAc.email + '</a> and we’ll reserve your video today.';
				return;
			}
			var btn = $('[data-checkout]', calc);
			btn.disabled = true;
			btn.textContent = 'Opening secure checkout…';
			fetch(WAc.rest + 'checkout', {
				method: 'POST',
				headers: { 'Content-Type': 'application/json', 'X-WP-Nonce': WAc.nonce },
				body: JSON.stringify(config())
			}).then(function (r) { return r.json(); }).then(function (d) {
				if (d.url) { window.location.href = d.url; return; }
				throw new Error(d.error || 'Something went wrong.');
			}).catch(function (err) {
				msg.textContent = err.message;
				msg.classList.add('is-error');
				btn.disabled = false;
				btn.textContent = 'Reserve My Cinematic Video';
			});
		});
	}

	/* Brief forms */
	var brief = $('[data-brief]');
	if (brief) {
		var max = parseInt(brief.getAttribute('data-max'), 10) || 1;
		var list = $('[data-listings]', brief);
		var addBtn = $('[data-add-listing]', brief);
		var bmsg = $('[data-brief-msg]', brief);
		var progress = $('[data-progress]', brief);
		var draft = (window.crypto && crypto.randomUUID ? crypto.randomUUID() : String(Date.now()) + Math.random()).replace(/[^a-z0-9]/gi, '').toLowerCase().slice(0, 32);
		while (draft.length < 20) draft += Math.floor(Math.random() * 10);

		function renumber() {
			$$('[data-listing]', list).forEach(function (el, i) { $('[data-listing-num]', el).textContent = i + 1; });
			if (addBtn) addBtn.hidden = $$('[data-listing]', list).length >= max;
		}
		if (addBtn) {
			addBtn.addEventListener('click', function () {
				var items = $$('[data-listing]', list);
				if (items.length >= max) return;
				var clone = items[0].cloneNode(true);
				$$('input, textarea', clone).forEach(function (i) { i.value = ''; });
				list.appendChild(clone);
				renumber();
			});
		}
		renumber();

		function upload(file, done, total) {
			var fd = new FormData();
			fd.append('file', file);
			fd.append('draft', draft);
			return fetch(WAc.rest + 'upload', { method: 'POST', headers: { 'X-WP-Nonce': WAc.nonce }, body: fd })
				.then(function (r) { return r.json(); })
				.then(function (d) {
					done.count++;
					progress.firstElementChild.style.width = Math.round(done.count / total * 100) + '%';
					if (!d.id) throw new Error((d.error || 'Upload failed') + ' (' + file.name + ')');
					return d.id;
				});
		}
		function uploadAll(files, done, total) {
			// Sequential to stay friendly with shared hosting limits.
			var ids = [];
			return files.reduce(function (p, f) {
				return p.then(function () { return upload(f, done, total).then(function (id) { ids.push(id); }); });
			}, Promise.resolve()).then(function () { return ids; });
		}

		brief.addEventListener('submit', function (e) {
			e.preventDefault();
			bmsg.textContent = '';
			bmsg.classList.remove('is-error');
			var f = brief.elements;
			if (!f.name.value.trim() || !/^\S+@\S+\.\S+$/.test(f.email.value.trim())) {
				bmsg.textContent = 'Please add your name and a valid email.';
				bmsg.classList.add('is-error');
				return;
			}
			var listingEls = $$('[data-listing]', list);
			var logoFiles = Array.prototype.slice.call(f.logo.files || []);
			var total = logoFiles.length;
			listingEls.forEach(function (el) { total += ($('input[name="photos"]', el).files || []).length; });
			if (listingEls.every(function (el) { return !($('input[name="photos"]', el).files || []).length; })) {
				bmsg.textContent = 'Please add your listing photos.';
				bmsg.classList.add('is-error');
				return;
			}
			var submit = $('button[type="submit"]', brief);
			var label = submit.textContent;
			submit.disabled = true;
			submit.textContent = 'Uploading ' + total + ' file' + (total === 1 ? '' : 's') + '…';
			progress.hidden = false;
			var done = { count: 0 };
			var payload = {
				type: brief.getAttribute('data-type'),
				draft: draft,
				pack: f.pack.value,
				ref: f.ref.value,
				session_id: f.session_id.value,
				company: f.company.value,
				name: f.name.value.trim(),
				email: f.email.value.trim(),
				phone: f.phone.value.trim(),
				brokerage: f.brokerage.value.trim(),
				website: f.website.value.trim(),
				logo: [],
				listings: []
			};
			uploadAll(logoFiles, done, total).then(function (ids) {
				payload.logo = ids;
				return listingEls.reduce(function (p, el) {
					return p.then(function () {
						var files = Array.prototype.slice.call($('input[name="photos"]', el).files || []);
						return uploadAll(files, done, total).then(function (photoIds) {
							payload.listings.push({
								photos: photoIds,
								description: $('[name="description"]', el).value,
								areas: $('[name="areas"]', el).value,
								text: $('[name="text"]', el).value,
								notes: $('[name="notes"]', el).value
							});
						});
					});
				}, Promise.resolve());
			}).then(function () {
				submit.textContent = 'Sending…';
				return fetch(WAc.rest + 'submit', {
					method: 'POST',
					headers: { 'Content-Type': 'application/json', 'X-WP-Nonce': WAc.nonce },
					body: JSON.stringify(payload)
				}).then(function (r) { return r.json(); });
			}).then(function (d) {
				if (!d.ok) throw new Error(d.error || 'Something went wrong.');
				brief.hidden = true;
				var doneEl = $('[data-brief-done]');
				doneEl.hidden = false;
				window.scrollTo({ top: 0, behavior: 'smooth' });
			}).catch(function (err) {
				bmsg.textContent = err.message;
				bmsg.classList.add('is-error');
				submit.disabled = false;
				submit.textContent = label;
				progress.hidden = true;
			});
		});
	}
})();

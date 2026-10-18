<?php
get_header();
$p     = wa_pricing();
$hero  = wa_video( 'hero_video' );
$cmpv  = wa_video( 'compare_video' );
$cmpi  = wa_opt( 'compare_photo' );
$links = array(
	'debut'     => wa_opt( 'link_debut' ),
	'signature' => wa_opt( 'link_signature' ),
	'estate'    => wa_opt( 'link_estate' ),
);
?>
<main>

	<!-- Hero -->
	<section class="hero" data-hero>
		<?php if ( $hero ) : ?>
			<video class="hero-video" data-hero-video autoplay muted loop playsinline preload="auto" <?php echo wa_opt( 'hero_poster' ) ? 'poster="' . esc_url( wa_opt( 'hero_poster' ) ) . '"' : ''; ?>>
				<source src="<?php echo esc_url( $hero ); ?>" type="video/mp4">
			</video>
		<?php endif; ?>
		<div class="hero-shade"></div>
		<div class="wrap hero-content">
			<p class="eyebrow reveal">A Boutique AI Video Agency for Luxury Realtors</p>
			<h1 class="hero-title reveal">Every home has a story.<br>We create the feeling.</h1>
			<p class="hero-sub reveal">We are a Boutique AI Video Agency for Luxury Realtors. We create cinematic reel videos that let buyers feel the lifestyle of each property before the first showing — and help you close 2x faster.</p>
			<div class="hero-ctas reveal">
				<a class="btn btn-light" href="<?php echo esc_url( home_url( '/free-video/' ) ); ?>">Create My Free Video</a>
				<a class="btn btn-ghost-light" href="#examples">Watch Examples</a>
			</div>
			<div class="hero-links reveal">
				<a href="#pricing">Explore Our Collection</a>
				<span>·</span>
				<a href="#custom-order">Build a Custom Order</a>
			</div>
		</div>
		<div class="wrap hero-foot">
			<span>Delivered in 48 hours · Crafted by filmmakers</span>
			<?php if ( $hero ) : ?>
				<button class="sound" data-sound aria-pressed="false"><span class="sound-icon" aria-hidden="true"></span><strong>Play</strong> — Hear the story.</button>
			<?php endif; ?>
		</div>
	</section>

	<!-- Value strip -->
	<section class="values">
		<div class="wrap values-grid">
			<div class="reveal"><h3>Cinematic</h3><p>Cinematic AI videos, not slideshows.</p></div>
			<div class="reveal"><h3>48 Hours</h3><p>Concept to delivery, two days max.</p></div>
			<div class="reveal"><h3>Made to Convert</h3><p>Built for ads, reels and reach.</p></div>
			<div class="reveal"><h3>Directed by Experts</h3><p>Professional filmmakers and storytellers.</p></div>
		</div>
	</section>

	<!-- Examples -->
	<section class="section" id="examples">
		<div class="wrap">
			<div class="section-head center reveal">
				<p class="eyebrow">Examples</p>
				<h2>See the feeling.</h2>
				<p class="lead">Real properties. Tap any reel to watch it full screen, with sound.</p>
			</div>
			<div class="phones">
				<?php for ( $i = 1; $i <= 3; $i++ ) : ?>
					<?php $v = wa_video( "ex{$i}_video" ); ?>
					<figure class="phone-card reveal">
						<?php if ( $v ) : ?>
							<button class="phone" data-play="<?php echo esc_url( $v ); ?>" aria-label="Play <?php echo esc_attr( wa_opt( "ex{$i}_title" ) ); ?>">
								<span class="phone-notch"></span>
								<video src="<?php echo esc_url( $v ); ?>" muted loop playsinline preload="metadata" data-preview></video>
								<span class="phone-play" aria-hidden="true"></span>
							</button>
						<?php else : ?>
							<div class="phone is-empty">
								<span class="phone-notch"></span>
								<span class="phone-empty">Reel coming soon</span>
							</div>
						<?php endif; ?>
						<figcaption>
							<strong><?php echo esc_html( wa_opt( "ex{$i}_title" ) ); ?></strong>
							<span><?php echo esc_html( wa_opt( "ex{$i}_meta" ) ); ?></span>
						</figcaption>
					</figure>
				<?php endfor; ?>
			</div>
		</div>
	</section>

	<!-- Free video -->
	<section class="section band">
		<div class="wrap narrow center reveal">
			<p class="eyebrow">Your first video is on us</p>
			<h2>See your listing as a cinematic experience. Free.</h2>
			<p class="lead">Send us your MLS photos, tell us about the home, and receive your first cinematic reel video — on the house. No credit card. No commitment.</p>
			<a class="btn" href="<?php echo esc_url( home_url( '/free-video/' ) ); ?>">Create My Free Video</a>
		</div>
	</section>

	<!-- The difference -->
	<section class="section">
		<div class="wrap split">
			<div class="reveal">
				<p class="eyebrow">The Difference</p>
				<h2>Slideshows scroll past. Cinematic listings sell.</h2>
			</div>
			<div class="reveal">
				<p class="lead">Most "AI" listing tools still produce the same pan-and-zoom slideshows agents have used for the last two decades. Wonder Ads directs each scene like a cinematographer, adding avatars for realism, not just walkthroughs. And the numbers move.</p>
			</div>
		</div>
		<div class="wrap"><div class="pillars">
			<div class="reveal"><span class="num">01</span><h3>Stand apart.</h3><p>Post short, elegant, cinematic reel videos. Your brand looks as elevated as the homes you represent.</p></div>
			<div class="reveal"><span class="num">02</span><h3>Premium content for social.</h3><p>Vertical reels crafted for Instagram, TikTok and Facebook — made to stop the scroll and get shared.</p></div>
			<div class="reveal"><span class="num">03</span><h3>Built for campaigns.</h3><p>Every cinematic reel is designed to drive results in paid ads and organic reach.</p></div>
		</div></div>
		<p class="wrap pull reveal">Visualization that sells. Before the first showing.</p>
	</section>

	<!-- What no one else does -->
	<section class="section band">
		<div class="wrap split">
			<div class="reveal">
				<p class="eyebrow">What No One Else Does</p>
				<h2>The only listing video that speaks.</h2>
				<p class="lead">Every competitor sells silent slideshows with a music bed. Wonder Ads gives every listing an emotion.</p>
			</div>
			<div class="feature reveal">
				<h3>Captions that sell on mute</h3>
				<p>Most buyers scroll with sound off. Wonder Ads burns in elegant, word-synced captions — luxury serif for premium listings, bold pop for social — timed to every syllable of the narration.</p>
			</div>
		</div>
	</section>

	<!-- MLS safe -->
	<section class="section">
		<div class="wrap">
			<div class="section-head reveal">
				<p class="eyebrow">MLS-Safe by Design</p>
				<h2>Real homes. Shown truthfully.</h2>
				<p class="lead">AI video can invent windows, finishes, even entire rooms. On a listing, that isn't a glitch — it's misrepresentation. Every Wonder Ads video stays true to your photos, and we can show you how.</p>
			</div>
			<div class="compare reveal">
				<div class="compare-item">
					<div class="compare-media">
						<?php if ( $cmpi ) : ?>
							<img src="<?php echo esc_url( $cmpi ); ?>" alt="Original MLS listing photo" loading="lazy">
						<?php else : ?>
							<span class="compare-empty">MLS photo</span>
						<?php endif; ?>
					</div>
					<span class="compare-label">Your MLS photo</span>
				</div>
				<div class="compare-item">
					<div class="compare-media">
						<?php if ( $cmpv ) : ?>
							<video src="<?php echo esc_url( $cmpv ); ?>" muted loop playsinline autoplay preload="metadata" data-lazy-video></video>
						<?php else : ?>
							<span class="compare-empty">Cinematic reel</span>
						<?php endif; ?>
					</div>
					<span class="compare-label">Our cinematic reel</span>
				</div>
			</div>
			<div class="pillars">
				<div class="reveal"><span class="num">01</span><h3>Checked against your photos</h3><p>Every scene is reviewed side by side with the photo it came from: layout, fixtures, counters, windows. Anything that doesn't match is redone before it reaches you.</p></div>
				<div class="reveal"><span class="num">02</span><h3>Nothing invented</h3><p>Kitchens, baths and detail-heavy rooms get subtle, true-to-photo motion. Virtual-staging disclosure labels in your photos always stay in.</p></div>
				<div class="reveal"><span class="num">03</span><h3>Proof you can share</h3><p>Every video comes with a scene-by-scene comparison to your original photos, ready to share with your broker or your seller.</p></div>
			</div>
		</div>
	</section>

	<!-- Proof -->
	<section class="section proof">
		<div class="wrap narrow center reveal">
			<p class="stat">2x</p>
			<h2>Close twice as fast.</h2>
			<p class="lead">In our case studies, realtors who ran cinematic videos as Facebook ad campaigns closed their listings 2x faster. Cinema holds attention. Attention creates desire. Desire moves buyers to act.</p>
			<a class="btn btn-light" href="#pricing">See the Collections</a>
		</div>
	</section>

	<!-- How it works -->
	<section class="section" id="how">
		<div class="wrap">
			<div class="section-head center reveal">
				<p class="eyebrow">How it works</p>
				<h2>Three steps. Two days.</h2>
			</div>
			<ol class="steps">
				<li class="reveal"><span class="num">01</span><h3>Choose.</h3><p>Select a collection or build your own order. Checkout takes a minute.</p></li>
				<li class="reveal"><span class="num">02</span><h3>Share.</h3><p>Send your MLS photos and a few notes on what makes the home special.</p></li>
				<li class="reveal"><span class="num">03</span><h3>Premiere.</h3><p>Your cinematic video arrives within 48 hours, ready to post, promote and close.</p></li>
			</ol>
		</div>
	</section>

	<!-- Real humans + style -->
	<section class="section band">
		<div class="wrap split">
			<div class="reveal">
				<p class="eyebrow">Real humans. Real craft.</p>
				<h2>We sell the lifestyle. You sell the property.</h2>
			</div>
			<div class="reveal">
				<p class="lead">This isn't cheap AI software spitting out generic results. Every video is directed by real filmmakers and storytellers, carefully crafted to make buyers feel the lifestyle of each luxury property.</p>
				<p>Most real estate videos are a walkthrough: room after room, wall after wall. Ours tell a story. We show the home through the eyes of the people who will live there — coffee on the terrace at sunrise, friends gathered around the kitchen island, a quiet swim as the city lights come on. Buyers stop touring a house and start picturing their life in it.</p>
				<p class="note"><strong>Made for high-end listings.</strong> Our cinematic reels are crafted for luxury properties from $1.5M and up — fully furnished and move-in ready, where every room already tells a story.</p>
			</div>
		</div>
	</section>

	<!-- The math -->
	<section class="section">
		<div class="wrap">
			<div class="section-head reveal">
				<p class="eyebrow">The Math</p>
				<h2>Videographer, slideshow app, or Wonder Ads.</h2>
			</div>
			<div class="table-wrap reveal">
				<table class="math">
					<thead><tr><th></th><th>Videographer</th><th>Slideshow apps</th><th class="hl">Wonder Ads</th></tr></thead>
					<tbody>
						<tr><th>Cost per listing</th><td>$500–1,200</td><td>$10–15</td><td class="hl">From $640</td></tr>
						<tr><th>Turnaround</th><td>3–7 days</td><td>Minutes</td><td class="hl">2 days max</td></tr>
						<tr><th>Visuals</th><td>Real cinematography</td><td>Pan &amp; zoom on photos</td><td class="hl">Cinematic AI scenes, directed by filmmakers</td></tr>
						<tr><th>Concept &amp; script</th><td>Extra charge</td><td>—</td><td class="hl">Included</td></tr>
						<tr><th>Voiceover</th><td>Extra charge</td><td>—</td><td class="hl">Included</td></tr>
						<tr><th>Music</th><td>Extra charge</td><td>Stock tracks</td><td class="hl">Original music, included</td></tr>
						<tr><th>Revisions</th><td>Cost extra</td><td>—</td><td class="hl">Two rounds included</td></tr>
						<tr><th>Shoot day</th><td>Required</td><td>None</td><td class="hl">None — just send your photos</td></tr>
					</tbody>
				</table>
			</div>
		</div>
	</section>

	<!-- Collections -->
	<section class="section band" id="pricing">
		<div class="wrap">
			<div class="section-head center reveal">
				<p class="eyebrow">The Collections</p>
				<h2>Cinematic listing reels. Collected.</h2>
				<p class="lead">20% off every collection.</p>
			</div>
			<div class="packs">
				<?php
				$taglines = array(
					'debut'     => array( 'An introduction to AI cinema.', 'Begin with Debut' ),
					'signature' => array( 'Your listings, your signature style.', 'Choose Signature' ),
					'estate'    => array( 'For portfolios that set the standard.', 'Choose Estate' ),
				);
				foreach ( $p['packs'] as $key => $pack ) :
					?>
					<article class="pack reveal <?php echo 'signature' === $key ? 'is-featured' : ''; ?>">
						<div class="pack-badges">
							<span class="badge">20% OFF</span>
							<?php if ( 'signature' === $key ) : ?><span class="badge badge-dark">Most Chosen</span><?php endif; ?>
						</div>
						<h3><?php echo esc_html( $pack['name'] ); ?></h3>
						<p class="pack-count"><?php echo (int) $pack['videos']; ?> cinematic videos</p>
						<p class="price"><s>$<?php echo esc_html( number_format( $pack['list'] ) ); ?></s> <span>$<?php echo esc_html( number_format( $pack['price'] ) ); ?></span></p>
						<p class="pack-tag"><?php echo esc_html( $taglines[ $key ][0] ); ?></p>
						<a class="btn <?php echo 'signature' === $key ? '' : 'btn-outline'; ?>" href="<?php echo esc_url( $links[ $key ] ); ?>"><?php echo esc_html( $taglines[ $key ][1] ); ?></a>
					</article>
				<?php endforeach; ?>
			</div>
			<div class="included reveal">
				<div>
					<h4>What's included</h4>
					<ul>
						<li>30-second Reels</li><li>Concept and script</li><li>Production</li><li>Original music</li>
						<li>Voiceover creative storytelling</li><li>Captions</li><li>Text-on-screen</li><li>Two rounds of revisions</li>
					</ul>
				</div>
				<div>
					<h4>Flexible</h4>
					<p>Spread your videos across different listings, or create multiple variations of the same listing.</p>
				</div>
				<div>
					<h4>Delivery</h4>
					<ul><li>30-second Reel videos</li><li>Delivered within 2 days</li></ul>
				</div>
			</div>
			<p class="help-note reveal">Questions before you choose? Our live agent is here for you at the bottom right of the page. Chat or call, anytime.</p>
		</div>
	</section>

	<!-- Custom order -->
	<section class="section" id="custom-order">
		<div class="wrap">
			<div class="section-head reveal">
				<p class="eyebrow">Custom Order</p>
				<h2>Your cinematic video, your way.</h2>
				<p class="lead">Choose length, formats and sound. Your price updates as you go.</p>
			</div>
			<form class="calc reveal" data-calc>
				<div class="calc-fields">
					<div class="field">
						<div class="field-head"><label for="calc-qty">Quantity</label><output data-out="qty">1 video</output></div>
						<input type="range" id="calc-qty" name="qty" min="1" max="<?php echo (int) $p['max_qty']; ?>" step="1" value="1">
						<div class="ticks"><span>1</span><span>5</span><span>10</span></div>
					</div>
					<div class="field">
						<div class="field-head"><label for="calc-length">Length</label><output data-out="length">30 seconds — $800</output></div>
						<input type="range" id="calc-length" name="length" min="0" max="2" step="1" value="0">
						<div class="ticks"><span>:30</span><span>:60</span><span>:90</span></div>
					</div>

					<fieldset class="field">
						<legend>Formats</legend>
						<label class="check"><input type="checkbox" name="formats" value="vertical" checked disabled><span><strong>Vertical</strong> — Reels, TikTok</span><em>Included</em></label>
						<label class="check"><input type="checkbox" name="formats" value="horizontal"><span><strong>Horizontal</strong> — YouTube, Web</span><em>+$150</em></label>
						<label class="check"><input type="checkbox" name="formats" value="square"><span><strong>Square</strong> — Instagram, Facebook</span><em>+$150</em></label>
					</fieldset>

					<fieldset class="field">
						<legend>Sound · Original music</legend>
						<p class="hint">Choose style</p>
						<div class="chips">
							<label class="chip"><input type="radio" name="music" value="cinematic" checked><span>Cinematic</span></label>
							<label class="chip"><input type="radio" name="music" value="classical"><span>Classical</span></label>
							<label class="chip"><input type="radio" name="music" value="modern"><span>Modern</span></label>
						</div>
					</fieldset>

					<fieldset class="field">
						<legend>Voiceover</legend>
						<label class="toggle"><input type="checkbox" name="storytelling" checked><span class="switch"></span><span><strong>Creative storytelling</strong><br><small>Use creative storytelling to appeal more to your clients.</small></span></label>
						<div class="chips" data-story-styles>
							<label class="chip"><input type="radio" name="story_style" value="warm" checked><span>Warm</span></label>
							<label class="chip"><input type="radio" name="story_style" value="energetic"><span>Energetic</span></label>
							<label class="chip"><input type="radio" name="story_style" value="focused"><span>Focused</span></label>
						</div>
						<label class="toggle" data-script-toggle><input type="checkbox" name="custom_script"><span class="switch"></span><span><strong>Custom voiceover script</strong><br><small>Not available with creative storytelling.</small></span></label>
						<textarea name="script" rows="4" placeholder="Write your voiceover script…" data-script hidden></textarea>
					</fieldset>

					<fieldset class="field">
						<legend>Extras</legend>
						<label class="toggle"><input type="checkbox" name="actors"><span class="switch"></span><span><strong>Actors</strong><br><small>Choose actors for a more cinematic experience. Off = walkthrough only.</small></span></label>
						<label class="toggle"><input type="checkbox" name="captions" checked><span class="switch"></span><span><strong>Captions</strong></span></label>
					</fieldset>
				</div>

				<aside class="calc-summary">
					<p class="eyebrow">Your total</p>
					<p class="total" data-total>$800</p>
					<p class="muted" data-breakdown>1 × 30-second video</p>
					<p class="muted small">Delivered within 2 days. Secure checkout by Stripe.</p>
				<p class="muted small">Questions? Ask our live agent at the bottom right, by chat or call.</p>
					<button type="submit" class="btn btn-block" data-checkout>Reserve My Cinematic Video</button>
					<p class="form-msg" data-calc-msg></p>
				</aside>
			</form>
		</div>
	</section>

	<!-- Studio -->
	<section class="section band">
		<div class="wrap split">
			<div class="reveal">
				<p class="eyebrow">The Studio</p>
				<h2>Storytellers first. Powered by AI.</h2>
			</div>
			<div class="reveal">
				<p class="lead">Wonder Ads is a boutique studio of professional filmmakers and storytellers with more than ten years of experience. We pair that eye with the latest AI models to bring the instincts of cinema — light, pace, music, emotion — to luxury real estate. Small by design, so every video gets our full attention. No cameras, no crews, no shoot days.</p>
				<p class="signature-line">We don't make listing videos. We make the moment a buyer says, <em>that's home.</em></p>
			</div>
		</div>
	</section>

	<!-- FAQ -->
	<section class="section" id="faq">
		<div class="wrap narrow">
			<div class="section-head reveal">
				<p class="eyebrow">FAQ</p>
				<h2>Questions</h2>
			</div>
			<?php
			$faq = array(
				'How can you make a cinematic video from photos?' => 'We use your listing photos as the foundation and create movement, light and atmosphere around them with the latest AI models. No shoot day, no disruption to the home.',
				'How fast will I receive my cinematic video?'     => 'Within 2 days of receiving your complete brief.',
				'What if I want changes?'                         => 'Every cinematic video includes two rounds of revisions.',
				'Which format will I receive?'                    => 'One 9:16 vertical master, ready for Reels and TikTok. Horizontal and square versions are available for an additional fee on custom orders only.',
				'Can I use the cinematic videos in paid ads?'     => 'Yes. Our music is original, and every cinematic video is designed to perform on Facebook and Instagram campaigns.',
				'Do I need to be in the video?'                   => 'No. The home is the star. Actors can be added to your custom order.',
				'Which properties is this best for?'              => 'High-end listings from $1.5M and up that are fully furnished and move-in ready. The more lived-in and styled the home, the more powerful the story.',
			);
			?>
			<div class="faq">
				<?php foreach ( $faq as $q => $a ) : ?>
					<details class="reveal"><summary><?php echo esc_html( $q ); ?></summary><p><?php echo esc_html( $a ); ?></p></details>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<!-- Closing -->
	<section class="section closing">
		<div class="wrap narrow center reveal">
			<h2>Your next listing deserves a premiere.</h2>
			<p class="lead">Two days from now, it could be playing.</p>
			<div class="hero-ctas center">
				<a class="btn btn-light" href="#pricing">Commission a Cinematic Video</a>
				<a class="btn btn-ghost-light" href="<?php echo esc_url( home_url( '/free-video/' ) ); ?>">Create My Free Video</a>
			</div>
		</div>
	</section>

</main>
<?php
get_footer();

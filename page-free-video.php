<?php get_header(); ?>
<main class="inner">
	<section class="section">
		<div class="wrap narrow">
			<div class="section-head">
				<p class="eyebrow">Your Free Cinematic Video</p>
				<h1 class="page-title">Let's create your first one.</h1>
				<p class="lead">Tell us about your listing and we'll take it from there. Your free video arrives within 2 days.</p>
			</div>
			<div class="summary-card">
				<h4>Your free video</h4>
				<dl>
					<div><dt>Length</dt><dd>30 seconds</dd></div>
					<div><dt>Quantity</dt><dd>1</dd></div>
					<div><dt>Format</dt><dd>Vertical 9:16, for Reels and TikTok</dd></div>
					<div><dt>Sound</dt><dd>Included</dd></div>
					<div><dt>Voiceover</dt><dd>Included</dd></div>
					<div><dt>Text-on-screen</dt><dd>Included</dd></div>
					<div><dt>Captions</dt><dd>Included</dd></div>
					<div class="total-row"><dt>Total</dt><dd>$0</dd></div>
				</dl>
			</div>
			<?php get_template_part( 'template-parts/brief-form', null, array( 'type' => 'free', 'listings' => 1, 'max' => 1 ) ); ?>
		</div>
	</section>
</main>
<?php get_footer(); ?>

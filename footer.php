<footer class="site-footer">
	<div class="wrap footer-grid">
		<div>
			<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">WONDER<span>ADS</span></a>
			<p class="muted">Wonder Ads — Cinematic Videos for Luxury Real Estate Brokers.</p>
		</div>
		<div class="footer-contact">
			<a href="mailto:support@wonderads.net">support@wonderads.net</a>
			<a href="tel:+14583120457">24/7 Live Support (458) 312-0457</a>
			<span>78 SW 7th St, Miami, FL 33130, United States</span>
		</div>
	</div>
	<div class="wrap"><div class="footer-base">
		<span>© <?php echo esc_html( gmdate( 'Y' ) ); ?> Wonder Ads. All rights reserved.</span>
		<span><a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">Blog</a> · <a href="<?php echo esc_url( home_url( '/privacy/' ) ); ?>">Privacy</a> · <a href="<?php echo esc_url( home_url( '/terms/' ) ); ?>">Terms</a></span>
	</div></div>
</footer>

<div class="modal" data-modal hidden>
	<button class="modal-close" data-modal-close aria-label="Close">×</button>
	<video data-modal-video playsinline controls></video>
</div>
<?php wp_footer(); ?>
</body>
</html>

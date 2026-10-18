<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
	<meta name="description" content="<?php echo esc_attr( wa_meta_description() ); ?>">
	<meta name="theme-color" content="#FFFFFF">
	<?php wp_head(); ?>
</head>
<body <?php body_class( is_front_page() ? 'is-home' : 'is-inner' ); ?>>
<?php wp_body_open(); ?>
<header class="site-header" data-header>
	<div class="wrap header-inner">
		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="Wonder Ads home">WONDER<span>ADS</span></a>
		<nav class="nav" data-nav aria-label="Main">
			<a href="<?php echo esc_url( home_url( '/#how' ) ); ?>">How it works</a>
			<a href="<?php echo esc_url( home_url( '/#examples' ) ); ?>">Examples</a>
			<a href="<?php echo esc_url( home_url( '/#pricing' ) ); ?>">Pricing</a>
			<a href="<?php echo esc_url( home_url( '/#faq' ) ); ?>">FAQ</a>
			<a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">Blog</a>
			<a class="nav-phone" href="tel:+14583120457" aria-label="Call Wonder Ads at (458) 312-0457"><svg class="nav-phone-icon" width="12" height="12" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>(458) 312-0457</a>
			<a class="btn btn-sm" href="<?php echo esc_url( home_url( '/free-video/' ) ); ?>">Create My Free Video</a>
		</nav>
		<button class="nav-toggle" data-nav-toggle aria-label="Menu" aria-expanded="false"><span></span><span></span></button>
	</div>
</header>

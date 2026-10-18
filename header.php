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
			<a class="btn btn-sm" href="<?php echo esc_url( home_url( '/free-video/' ) ); ?>">Create My Free Video</a>
		</nav>
		<button class="nav-toggle" data-nav-toggle aria-label="Menu" aria-expanded="false"><span></span><span></span></button>
	</div>
</header>

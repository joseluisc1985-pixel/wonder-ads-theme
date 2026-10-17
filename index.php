<?php
// Fallback template: send visitors to the home page experience.
if ( ! is_front_page() && ! is_page() ) {
	wp_safe_redirect( home_url( '/' ) );
	exit;
}
get_template_part( 'page' );

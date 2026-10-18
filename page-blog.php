<?php
/**
 * Blog index (slug: blog).
 */
get_header();
$paged = max( 1, get_query_var( 'paged' ) ? get_query_var( 'paged' ) : get_query_var( 'page' ) );
$q     = new WP_Query( array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 12, 'paged' => $paged ) );
get_template_part(
	'template-parts/blog-grid',
	null,
	array(
		'query'   => $q,
		'eyebrow' => 'The Blog',
		'title'   => 'Luxury real estate, told well.',
		'lead'    => 'Market insight, marketing playbooks and investment ideas for luxury realtors, brokers and investors, from the Wonder Ads studio.',
	)
);
get_footer();

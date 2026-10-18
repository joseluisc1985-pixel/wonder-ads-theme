<?php
/**
 * Journal archives: categories, tags, dates, authors.
 */
get_header();
$desc = wp_strip_all_tags( get_the_archive_description() );
get_template_part(
	'template-parts/blog-grid',
	null,
	array(
		'eyebrow' => 'The Journal',
		'title'   => wp_strip_all_tags( single_term_title( '', false ) ? single_term_title( '', false ) : get_the_archive_title() ),
		'lead'    => $desc,
	)
);
get_footer();

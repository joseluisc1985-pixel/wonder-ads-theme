<?php
get_header();
get_template_part( 'template-parts/blog-grid', null, array( 'eyebrow' => 'Search', 'title' => 'Results for “' . get_search_query() . '”', 'lead' => '' ) );
get_footer();

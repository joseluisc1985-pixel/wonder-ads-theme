<?php
/**
 * Blog listing. $args: query (WP_Query), title, lead, eyebrow.
 */
$q = isset( $args['query'] ) ? $args['query'] : $GLOBALS['wp_query'];
?>
<main class="inner journal">
	<section class="section journal-head">
		<div class="wrap">
			<p class="eyebrow"><?php echo esc_html( $args['eyebrow'] ); ?></p>
			<h1 class="page-title"><?php echo esc_html( $args['title'] ); ?></h1>
			<?php if ( ! empty( $args['lead'] ) ) : ?><p class="lead"><?php echo esc_html( $args['lead'] ); ?></p><?php endif; ?>
			<nav class="journal-cats" aria-label="Topics">
				<a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>" class="<?php echo is_page( 'blog' ) ? 'is-active' : ''; ?>">All</a>
				<?php foreach ( get_categories( array( 'hide_empty' => true, 'exclude' => array( get_option( 'default_category' ) ) ) ) as $c ) : ?>
					<a href="<?php echo esc_url( get_category_link( $c ) ); ?>" class="<?php echo is_category( $c->term_id ) ? 'is-active' : ''; ?>"><?php echo esc_html( $c->name ); ?></a>
				<?php endforeach; ?>
			</nav>
		</div>
	</section>
	<section class="section journal-list">
		<div class="wrap">
			<?php if ( $q->have_posts() ) : ?>
				<div class="post-grid">
					<?php
					while ( $q->have_posts() ) :
						$q->the_post();
						get_template_part( 'template-parts/post-card' );
					endwhile;
					?>
				</div>
				<?php
				$links = paginate_links(
					array(
						'total'     => $q->max_num_pages,
						'current'   => max( 1, get_query_var( 'paged' ) ? get_query_var( 'paged' ) : get_query_var( 'page' ) ),
						'prev_text' => '←',
						'next_text' => '→',
					)
				);
				if ( $links ) {
					echo '<nav class="journal-pages">' . wp_kses_post( $links ) . '</nav>';
				}
				wp_reset_postdata();
				?>
			<?php else : ?>
				<p class="lead">New stories are on their way. In the meantime, ask our live agent anything at the bottom right of the page.</p>
			<?php endif; ?>
			<?php get_template_part( 'template-parts/blog-cta' ); ?>
		</div>
	</section>
</main>

<?php
/**
 * Journal article.
 */
get_header();
while ( have_posts() ) :
	the_post();
	$cats = get_the_category();
	$cat  = $cats ? $cats[0] : null;
	?>
<main class="inner article-page">
	<article class="section article">
		<div class="wrap article-wrap">
			<nav class="crumbs" aria-label="Breadcrumb">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a> <span>/</span>
				<a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">Journal</a>
				<?php if ( $cat ) : ?><span>/</span> <a href="<?php echo esc_url( get_category_link( $cat ) ); ?>"><?php echo esc_html( $cat->name ); ?></a><?php endif; ?>
			</nav>
			<header class="article-head">
				<?php if ( $cat ) : ?><p class="eyebrow"><?php echo esc_html( $cat->name ); ?></p><?php endif; ?>
				<h1 class="article-title"><?php the_title(); ?></h1>
				<?php if ( has_excerpt() ) : ?><p class="lead article-dek"><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>
				<p class="article-meta">By the Wonder Ads Studio · <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'F j, Y' ) ); ?></time><?php if ( get_the_modified_date( 'Ymd' ) > get_the_date( 'Ymd' ) ) : ?> · Updated <?php echo esc_html( get_the_modified_date( 'F j, Y' ) ); ?><?php endif; ?> · <?php echo (int) wa_read_time(); ?> min read</p>
			</header>
			<?php if ( has_post_thumbnail() ) : ?><figure class="article-hero"><?php the_post_thumbnail( 'full' ); ?></figure><?php endif; ?>
			<div class="article-body prose">
				<?php the_content(); ?>
			</div>
			<?php get_template_part( 'template-parts/blog-cta' ); ?>
			<?php
			$related = new WP_Query(
				array(
					'post_type'           => 'post',
					'posts_per_page'      => 3,
					'post__not_in'        => array( get_the_ID() ),
					'category__in'        => $cat ? array( $cat->term_id ) : array(),
					'ignore_sticky_posts' => true,
				)
			);
			if ( $related->have_posts() ) :
				?>
				<section class="related">
					<h4>Keep reading</h4>
					<div class="post-grid">
						<?php
						while ( $related->have_posts() ) :
							$related->the_post();
							get_template_part( 'template-parts/post-card' );
						endwhile;
						wp_reset_postdata();
						?>
					</div>
				</section>
			<?php endif; ?>
		</div>
	</article>
</main>
	<?php
endwhile;
get_footer();

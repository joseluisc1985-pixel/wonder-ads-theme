<?php
/**
 * Blog card.
 */
$cats = get_the_category();
$cat  = $cats ? $cats[0] : null;
?>
<article class="post-card reveal">
	<a class="post-card-link" href="<?php the_permalink(); ?>">
		<?php if ( has_post_thumbnail() ) : ?>
			<span class="post-card-img"><?php the_post_thumbnail( 'large', array( 'loading' => 'lazy' ) ); ?></span>
		<?php endif; ?>
		<?php if ( $cat ) : ?><span class="post-card-cat"><?php echo esc_html( $cat->name ); ?></span><?php endif; ?>
		<h3 class="post-card-title"><?php the_title(); ?></h3>
		<p class="post-card-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 26 ) ); ?></p>
		<span class="post-card-meta"><?php echo esc_html( get_the_date( 'M j, Y' ) ); ?> · <?php echo (int) wa_read_time(); ?> min read</span>
	</a>
</article>

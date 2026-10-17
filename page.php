<?php get_header(); ?>
<main class="inner">
	<section class="section">
		<div class="wrap narrow prose">
			<?php while ( have_posts() ) : the_post(); ?>
				<h1 class="page-title"><?php the_title(); ?></h1>
				<?php if ( get_the_content() ) : ?>
					<?php the_content(); ?>
				<?php else : ?>
					<p class="lead">This page is being prepared. For any questions, write to <a href="mailto:hello@wonderads.net">hello@wonderads.net</a>.</p>
				<?php endif; ?>
			<?php endwhile; ?>
		</div>
	</section>
</main>
<?php get_footer(); ?>

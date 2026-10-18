<?php get_header(); ?>
<main class="inner">
	<section class="section">
		<div class="wrap narrow prose">
			<?php while ( have_posts() ) : the_post(); ?>
				<h1 class="page-title"><?php the_title(); ?></h1>
				<?php if ( get_the_content() ) : ?>
					<?php the_content(); ?>
				<?php else : ?>
					<p class="lead">This page is being prepared. For any questions, ask our live agent at the bottom right, by chat or call.</p>
				<?php endif; ?>
			<?php endwhile; ?>
		</div>
	</section>
</main>
<?php get_footer(); ?>

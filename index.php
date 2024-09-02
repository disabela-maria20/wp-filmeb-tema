<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<?php endwhile;
else: ?>

	</article>
	<section class="introducao-interna introducao-geral">
		<div class="container">
			
			<h1>Não</h1>
		</div>
	</section>

<?php endif; ?>

<?php get_footer(); ?>
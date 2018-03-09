<?php defined('ABSPATH') || exit; get_header() ?>

	<!-- generated by index.php (aka: archive-post.php) -->

	<main>
		<section id='article_holder' class='grid article_list'>

			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<div class='date nomargin'>
					<?= get_the_date(); ?>
				</div>
				<h2 class='blog_title'><a href='<?= get_permalink() ?>'><?php the_title() ?> &raquo;</a></h2>
			<?php endwhile; endif; ?>
		</section>

		<div class='clear navigation'>
			<?= paginate_links(); ?>
		</div><!--/navigation-->

	</main>

	<!-- /generated by index.php -->

<?php get_footer() ?>

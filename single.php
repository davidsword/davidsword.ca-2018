<?php
/**
 * Single output for POST post types.
 *
 * All other post types have their own single-*.php page
 * or their single page has been deleted.
 *
 * @package davidsword-2018
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<!-- generated by single.php -->

<main>
	<section>

		<?php
		if ( have_posts() ) :
			while ( have_posts() ) :
				the_post();

				$is_post_paged = strstr( $post->post_content, '<!--nextpage-->' );

				$paged = get_query_var( 'page', 1 );
				?>
			<article>
				<h2>
					<span>
					<?php
					the_title();
					if ( $is_post_paged && $paged > 0 ) {
						echo " (Page {$paged})";
					}
					?>
					</span>
				</h2>

				<?php if ( is_singular( 'post' ) ) : ?>
					<div class='post_meta'>
						<a href="<?php echo get_permalink( get_option( 'page_for_posts' ) ); ?>">Blog</a> &raquo;
						<div class='post_meta--date post_meta--single-date'>
							<?php echo get_the_date(); ?>
						</div>
						<div class='post_meta--tags'>
							<?php
							$terms = wp_get_post_terms( get_the_ID(), 'category' );
							foreach ( $terms as $term ) {
								$link = get_term_link( $term->term_id, 'category' );
								?>
								<a href='<?php echo $link; ?>'>#<?php echo $term->name; ?></a>
								<?php
							}
							?>
						</div>
					</div>
				<?php endif ?>

				<?php get_template_part( 'partials/inline', 'ftrimg' ); ?>

				<div class='entry'>
					<?php the_content(); ?>
				</div>

				<?php comments_template(); ?>

			</article>


			<div class='clear navigation'>
				<?php
				if ( $is_post_paged ) {
					wp_link_pages(array(
						'before' => '<p>Post Pages: &nbsp; ',
					));
				}
				next_post_link( '%link', '&laquo; Prev Post' );
				previous_post_link( '%link', 'Next Post &raquo;' );
				?>
			</div><!--/navigation-->

				<?php
			endwhile;
		endif;
		?>
	</section>

</main>

<!-- /generated by single.php -->

<?php
get_footer();

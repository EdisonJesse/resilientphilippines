<?php
/**
 * Clean posts index template.
 *
 * @package ResilientHub
 */

get_header();
?>
<main id="primary">
	<section class="rp-page-hero">
		<div class="rp-page-shell">
			<p class="rp-eyebrow"><?php esc_html_e( 'Updates and field stories', 'resilient-hub' ); ?></p>
			<h1 class="rp-page-title"><?php single_post_title(); ?></h1>
		</div>
	</section>

	<section class="rp-archive-section">
		<div class="rp-page-shell">
			<?php if ( have_posts() ) : ?>
				<div class="rp-archive-grid">
					<?php
					while ( have_posts() ) :
						the_post();
						?>
						<article id="post-<?php the_ID(); ?>" <?php post_class( 'rp-archive-card' ); ?>>
							<?php if ( has_post_thumbnail() ) : ?>
								<a class="rp-archive-card-image" href="<?php the_permalink(); ?>">
									<?php the_post_thumbnail( 'medium_large' ); ?>
								</a>
							<?php endif; ?>
							<div class="rp-archive-card-body">
								<p class="rp-resource-type"><?php echo esc_html( get_the_date() ); ?></p>
								<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
								<?php the_excerpt(); ?>
							</div>
						</article>
						<?php
					endwhile;
					?>
				</div>
				<?php the_posts_pagination(); ?>
			<?php else : ?>
				<div class="rp-empty-state">
					<h2><?php esc_html_e( 'No posts found', 'resilient-hub' ); ?></h2>
					<p><?php esc_html_e( 'Published stories and updates will appear here.', 'resilient-hub' ); ?></p>
				</div>
			<?php endif; ?>
		</div>
	</section>
</main>
<?php
get_footer();

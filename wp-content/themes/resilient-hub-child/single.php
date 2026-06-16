<?php
/**
 * Clean single post template for restored legacy stories.
 *
 * @package ResilientHub
 */

get_header();
?>
<main id="primary" class="rp-single-main">
	<?php
	while ( have_posts() ) :
		the_post();
		$categories = get_the_category();
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'rp-single-article' ); ?>>
			<header class="rp-single-hero">
				<div class="rp-page-shell">
					<?php if ( ! empty( $categories ) ) : ?>
						<p class="rp-eyebrow"><?php echo esc_html( $categories[0]->name ); ?></p>
					<?php endif; ?>
					<h1 class="rp-single-title"><?php the_title(); ?></h1>
					<div class="rp-single-meta" aria-label="<?php esc_attr_e( 'Post details', 'resilient-hub' ); ?>">
						<span><?php echo esc_html( get_the_date() ); ?></span>
						<span><?php esc_html_e( 'By', 'resilient-hub' ); ?> <?php the_author_posts_link(); ?></span>
						<?php if ( ! empty( $categories ) ) : ?>
							<span><?php the_category( ', ' ); ?></span>
						<?php endif; ?>
					</div>
				</div>
			</header>

			<?php if ( has_post_thumbnail() ) : ?>
				<figure class="rp-single-featured rp-page-shell">
					<?php the_post_thumbnail( 'large' ); ?>
					<?php
					$thumbnail_caption = wp_get_attachment_caption( get_post_thumbnail_id() );
					if ( $thumbnail_caption ) :
						?>
						<figcaption><?php echo esc_html( $thumbnail_caption ); ?></figcaption>
					<?php endif; ?>
				</figure>
			<?php endif; ?>

			<section class="rp-single-body">
				<div class="rp-page-shell">
					<div class="entry-content rp-single-content">
						<?php the_content(); ?>
					</div>

					<footer class="rp-single-footer">
						<?php
						$tags = get_the_tag_list( '', ', ' );
						if ( $tags ) :
							?>
							<p class="rp-single-tags"><strong><?php esc_html_e( 'Tagged:', 'resilient-hub' ); ?></strong> <?php echo wp_kses_post( $tags ); ?></p>
						<?php endif; ?>

						<nav class="rp-post-nav" aria-label="<?php esc_attr_e( 'More stories', 'resilient-hub' ); ?>">
							<div><?php previous_post_link( '%link', esc_html__( 'Previous story', 'resilient-hub' ) ); ?></div>
							<div><?php next_post_link( '%link', esc_html__( 'Next story', 'resilient-hub' ) ); ?></div>
						</nav>
					</footer>
				</div>
			</section>
		</article>
		<?php
	endwhile;
	?>
</main>
<?php
get_footer();

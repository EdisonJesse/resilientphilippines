<?php
/**
 * Modern site footer.
 *
 * @package ResilientHub
 */
?>
<footer class="rp-site-footer" role="contentinfo">
	<div class="rp-footer-inner">
		<div>
			<h2><?php bloginfo( 'name' ); ?></h2>
			<p><?php esc_html_e( 'A collaborative space for humanitarian learning, disaster risk reduction resources, and partner knowledge products across the Philippines.', 'resilient-hub' ); ?></p>
		</div>
		<div>
			<h3><?php esc_html_e( 'Hub', 'resilient-hub' ); ?></h3>
			<ul class="rp-footer-list">
				<li><a href="<?php echo esc_url( home_url( '/resource-hub/' ) ); ?>"><?php esc_html_e( 'Resource Hub', 'resilient-hub' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/submit-resource/' ) ); ?>"><?php esc_html_e( 'Submit Resource', 'resilient-hub' ); ?></a></li>
			</ul>
		</div>
		<div>
			<h3><?php esc_html_e( 'Connect', 'resilient-hub' ); ?></h3>
			<?php
			if ( has_nav_menu( 'rp-footer' ) ) {
				wp_nav_menu(
					array(
						'theme_location' => 'rp-footer',
						'container'      => false,
						'menu_class'     => 'rp-footer-list',
						'fallback_cb'    => false,
					)
				);
			}
			?>
		</div>
	</div>
</footer>
</div>
</div>
<?php wp_footer(); ?>
</body>
</html>

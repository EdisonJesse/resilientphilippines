<?php
/**
 * Modern site footer.
 *
 * @package ResilientHub
 */
?>
<footer class="rp-site-footer" role="contentinfo">
	<div class="rp-page-shell" style="margin-bottom: 32px;">
		<div class="rp-footer-inner">
			<div>
				<h2><?php esc_html_e( 'ACCORD Resilience Hub', 'resilient-hub' ); ?></h2>
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
				} else {
					?>
					<ul class="rp-footer-list">
						<li><a href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>"><?php esc_html_e( 'Contact Us', 'resilient-hub' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/donate/' ) ); ?>"><?php esc_html_e( 'Donate', 'resilient-hub' ); ?></a></li>
					</ul>
					<?php
				}
				?>
			</div>
		</div>
	</div>
	
	<div class="rp-footer-bottom">
		<div class="rp-page-shell" style="display: flex; justify-content: space-between; align-items: center; width: 100%; flex-wrap: wrap; gap: 16px;">
			<p>&copy; <?php echo esc_html( date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All rights reserved.', 'resilient-hub' ); ?></p>
			<div class="rp-footer-legal-links">
				<a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>"><?php esc_html_e( 'Privacy Policy', 'resilient-hub' ); ?></a>
				<a href="<?php echo esc_url( home_url( '/terms-of-service/' ) ); ?>"><?php esc_html_e( 'Terms of Service', 'resilient-hub' ); ?></a>
				<a href="<?php echo esc_url( home_url( '/cookie-policy/' ) ); ?>"><?php esc_html_e( 'Cookie Policy', 'resilient-hub' ); ?></a>
			</div>
		</div>
	</div>
</footer>

<!-- Cookie Consent Banner -->
<div id="rp-cookie-banner" class="rp-cookie-banner" style="display: none;">
	<div class="rp-cookie-banner-inner">
		<div class="rp-cookie-banner-text">
			<h4><?php esc_html_e( 'Cookie Consent & Privacy', 'resilient-hub' ); ?></h4>
			<p><?php printf(
				/* translators: %s: Privacy Policy URL */
				__( 'We use cookies to maintain your login session, secure forms, and collect usage analytics to improve our collaborative DRR platform. Read our %s.', 'resilient-hub' ),
				'<a href="' . esc_url( home_url( '/privacy-policy/' ) ) . '" target="_blank">' . __( 'Privacy Policy', 'resilient-hub' ) . '</a>'
			); ?></p>
		</div>
		<div class="rp-cookie-banner-actions">
			<button id="rp-cookie-decline" class="rp-button rp-button-secondary"><?php esc_html_e( 'Strictly Necessary Only', 'resilient-hub' ); ?></button>
			<button id="rp-cookie-accept" class="rp-button"><?php esc_html_e( 'Accept All', 'resilient-hub' ); ?></button>
		</div>
	</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	var consent = getCookie('rp_cookie_consent');
	var banner = document.getElementById('rp-cookie-banner');
	
	if (!consent && banner) {
		banner.style.display = 'block';
	}
	
	document.getElementById('rp-cookie-accept')?.addEventListener('click', function() {
		setCookie('rp_cookie_consent', 'accepted', 365);
		if (banner) banner.style.display = 'none';
	});
	
	document.getElementById('rp-cookie-decline')?.addEventListener('click', function() {
		setCookie('rp_cookie_consent', 'declined', 365);
		if (banner) banner.style.display = 'none';
	});
	
	function setCookie(name, value, days) {
		var expires = "";
		if (days) {
			var date = new Date();
			date.setTime(date.getTime() + (days*24*60*60*1000));
			expires = "; expires=" + date.toUTCString();
		}
		var secure = window.location.protocol === 'https:' ? '; Secure' : '';
		document.cookie = name + "=" + (value || "")  + expires + "; path=/; SameSite=Lax" + secure;
	}
	
	function getCookie(name) {
		var nameEQ = name + "=";
		var ca = document.cookie.split(';');
		for(var i=0; i < ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0)==' ') c = c.substring(1, c.length);
			if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
		}
		return null;
	}
});
</script>
</div>
</div>
<?php wp_footer(); ?>
</body>
</html>

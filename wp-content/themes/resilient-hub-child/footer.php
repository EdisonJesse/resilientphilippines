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
					<li><a href="<?php echo esc_url( home_url( '/opportunities/' ) ); ?>"><?php esc_html_e( 'Opportunities', 'resilient-hub' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/job-ads/' ) ); ?>"><?php esc_html_e( 'Job Ads', 'resilient-hub' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/invitations-to-bid/' ) ); ?>"><?php esc_html_e( 'Invitations to Bid', 'resilient-hub' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/tinig/' ) ); ?>"><?php esc_html_e( 'Tinig Feedback', 'resilient-hub' ); ?></a></li>
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
						<li><a href="<?php echo esc_url( home_url( '/tinig/' ) ); ?>"><?php esc_html_e( 'Tinig Feedback', 'resilient-hub' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/donate/' ) ); ?>"><?php esc_html_e( 'Donate', 'resilient-hub' ); ?></a></li>
					</ul>
					<?php
				}
				?>
				<ul class="rp-footer-list rp-footer-social-links" aria-label="<?php esc_attr_e( 'Social media', 'resilient-hub' ); ?>">
					<li><a href="https://x.com/ACCORD_inc" target="_blank" rel="noopener noreferrer" aria-label="X"><svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg></a></li>
					<li><a href="https://www.linkedin.com/in/accord-inc-588a7a146/" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn"><svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0z"/></svg></a></li>
					<li><a href="https://www.instagram.com/resilientphilippines" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.051.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg></a></li>
					<li><a href="https://www.facebook.com/accordinc" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a></li>
				</ul>
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
(function() {
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
	
	function setCookie(name, value, days) {
		var expires = "";
		if (days) {
			var date = new Date();
			date.setTime(date.getTime() + (days*24*60*60*1000));
			expires = "; expires=" + date.toUTCString();
		}
		var secure = window.location.protocol === 'https:' ? '; Secure' : '';
		document.cookie = name + "=" + (value || "")  + expires + "; path=/; SameSite=Lax" + secure;
		try {
			localStorage.setItem(name, value);
		} catch (e) {}
	}

	var consent = getCookie('rp_cookie_consent');
	try {
		if (!consent) {
			consent = localStorage.getItem('rp_cookie_consent');
		}
	} catch (e) {}

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
})();
</script>
</div>
</div>
<?php wp_footer(); ?>
</body>
</html>

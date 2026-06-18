<?php
/**
 * Child theme setup for the Resilient Humanitarian Hub.
 *
 * @package ResilientHub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function rp_child_enqueue_assets() {
	wp_dequeue_style( 'foreverwood-style' );

	wp_enqueue_style(
		'rp-google-fonts',
		'https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'foreverwood-parent',
		get_template_directory_uri() . '/style.css',
		array(),
		wp_get_theme( get_template() )->get( 'Version' )
	);

	wp_enqueue_style(
		'rp-child-style',
		get_stylesheet_uri(),
		array( 'foreverwood-parent' ),
		wp_get_theme()->get( 'Version' )
	);

	wp_enqueue_script(
		'rp-navigation',
		get_stylesheet_directory_uri() . '/assets/js/navigation.js',
		array(),
		wp_get_theme()->get( 'Version' ),
		true
	);

	wp_enqueue_script(
		'rp-pwa',
		get_stylesheet_directory_uri() . '/assets/js/pwa.js',
		array(),
		wp_get_theme()->get( 'Version' ),
		true
	);

	wp_localize_script(
		'rp-pwa',
		'rpPwa',
		array(
			'serviceWorkerUrl' => home_url( '/rp-service-worker.js' ),
		)
	);

	wp_enqueue_script(
		'rp-catalog-ajax',
		get_stylesheet_directory_uri() . '/assets/js/catalog-ajax.js',
		array(),
		wp_get_theme()->get( 'Version' ),
		true
	);

	wp_localize_script(
		'rp-catalog-ajax',
		'rp_ajax',
		array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
		)
	);

	if ( is_page( 'news-stories' ) ) {
		wp_enqueue_script(
			'rp-news-ajax',
			get_stylesheet_directory_uri() . '/assets/js/news-ajax.js',
			array(),
			wp_get_theme()->get( 'Version' ),
			true
		);
		wp_localize_script(
			'rp-news-ajax',
			'rp_ajax',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
			)
		);
	}
}
add_action( 'wp_enqueue_scripts', 'rp_child_enqueue_assets', 20 );

function rp_child_pwa_head_tags() {
	$theme_url = trailingslashit( get_stylesheet_directory_uri() );
	?>
	<link rel="manifest" href="<?php echo esc_url( home_url( '/rp-manifest.webmanifest' ) ); ?>">
	<meta name="theme-color" content="#176b52">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-title" content="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
	<meta name="apple-mobile-web-app-status-bar-style" content="default">
	<link rel="apple-touch-icon" href="<?php echo esc_url( $theme_url . 'assets/images/pwa-accord-correct-apple-touch-icon.png' ); ?>">
	<?php
}
add_action( 'wp_head', 'rp_child_pwa_head_tags', 5 );

function rp_child_is_pwa_endpoint( $filename ) {
	$request_path = isset( $_SERVER['REQUEST_URI'] ) ? wp_parse_url( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ), PHP_URL_PATH ) : '';
	$target_path  = wp_parse_url( home_url( '/' . ltrim( $filename, '/' ) ), PHP_URL_PATH );

	return untrailingslashit( (string) $request_path ) === untrailingslashit( (string) $target_path );
}

function rp_child_serve_pwa_endpoints() {
	if ( rp_child_is_pwa_endpoint( 'rp-manifest.webmanifest' ) ) {
		$theme_url = trailingslashit( get_stylesheet_directory_uri() );
		$manifest  = array(
			'id'               => home_url( '/' ),
			'name'             => __( 'ACCORD Resilience Hub', 'resilient-hub' ),
			'short_name'       => __( 'ACCORD Hub', 'resilient-hub' ),
			'description'      => __( 'Installable mobile access to ACCORD resources, stories, and situation reports.', 'resilient-hub' ),
			'start_url'        => home_url( '/' ),
			'scope'            => home_url( '/' ),
			'display'          => 'standalone',
			'display_override' => array( 'standalone', 'minimal-ui', 'browser' ),
			'background_color' => '#ffffff',
			'theme_color'      => '#176b52',
			'orientation'      => 'portrait-primary',
			'categories'       => array( 'education', 'productivity', 'news' ),
			'icons'            => array(
				array(
					'src'   => $theme_url . 'assets/images/pwa-accord-correct-192.png',
					'sizes' => '192x192',
					'type'  => 'image/png',
				),
				array(
					'src'   => $theme_url . 'assets/images/pwa-accord-correct-512.png',
					'sizes' => '512x512',
					'type'  => 'image/png',
				),
				array(
					'src'     => $theme_url . 'assets/images/pwa-accord-correct-maskable-512.png',
					'sizes'   => '512x512',
					'type'    => 'image/png',
					'purpose' => 'maskable',
				),
			),
			'shortcuts'        => array(
				array(
					'name' => __( 'Resource Hub', 'resilient-hub' ),
					'url'  => home_url( '/resource-hub/' ),
				),
				array(
					'name' => __( 'Situation Reports', 'resilient-hub' ),
					'url'  => home_url( '/sitrep-dashboard/' ),
				),
				array(
					'name' => __( 'News & Stories', 'resilient-hub' ),
					'url'  => home_url( '/news-stories/' ),
				),
			),
		);

		status_header( 200 );
		header( 'Content-Type: application/manifest+json; charset=utf-8' );
		header( 'Cache-Control: no-cache, no-store, must-revalidate' );
		echo wp_json_encode( $manifest, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT );
		exit;
	}

	if ( rp_child_is_pwa_endpoint( 'rp-service-worker.js' ) ) {
		$theme_url = trailingslashit( get_stylesheet_directory_uri() );
		$config    = array(
			'cacheName' => 'rp-pwa-' . sanitize_key( wp_get_theme()->get( 'Version' ) ),
			'homeUrl'   => home_url( '/' ),
			'origin'    => home_url(),
			'assets'    => array(
				home_url( '/' ),
				home_url( '/resource-hub/' ),
				home_url( '/news-stories/' ),
				$theme_url . 'style.css',
				$theme_url . 'assets/js/navigation.js',
				$theme_url . 'assets/images/accord-logo.png',
				$theme_url . 'assets/images/pwa-accord-correct-192.png',
				$theme_url . 'assets/images/pwa-accord-correct-512.png',
			),
		);

		status_header( 200 );
		header( 'Content-Type: application/javascript; charset=utf-8' );
		header( 'Cache-Control: no-cache, no-store, must-revalidate' );
		header( 'Service-Worker-Allowed: /' );
		?>
const RP_PWA_CONFIG = <?php echo wp_json_encode( $config, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ); ?>;

self.addEventListener("install", function (event) {
  event.waitUntil(
    caches.open(RP_PWA_CONFIG.cacheName)
      .then(function (cache) {
        return cache.addAll(RP_PWA_CONFIG.assets);
      })
      .catch(function () {})
      .then(function () {
        return self.skipWaiting();
      })
  );
});

self.addEventListener("activate", function (event) {
  event.waitUntil(
    caches.keys()
      .then(function (keys) {
        return Promise.all(keys.map(function (key) {
          if (key !== RP_PWA_CONFIG.cacheName && key.indexOf("rp-pwa-") === 0) {
            return caches.delete(key);
          }

          return Promise.resolve();
        }));
      })
      .then(function () {
        return self.clients.claim();
      })
  );
});

self.addEventListener("fetch", function (event) {
  const request = event.request;
  const url = new URL(request.url);

  if (request.method !== "GET" || url.origin !== self.location.origin) {
    return;
  }

  if (url.pathname.indexOf("/wp-admin/") === 0 || url.pathname.indexOf("admin-ajax.php") !== -1) {
    return;
  }

  if (request.mode === "navigate") {
    event.respondWith(
      fetch(request)
        .then(function (response) {
          const copy = response.clone();
          caches.open(RP_PWA_CONFIG.cacheName).then(function (cache) {
            cache.put(request, copy);
          });
          return response;
        })
        .catch(function () {
          return caches.match(request).then(function (cached) {
            return cached || caches.match(RP_PWA_CONFIG.homeUrl);
          });
        })
    );
    return;
  }

  event.respondWith(
    caches.match(request).then(function (cached) {
      const network = fetch(request).then(function (response) {
        if (response && response.ok) {
          const copy = response.clone();
          caches.open(RP_PWA_CONFIG.cacheName).then(function (cache) {
            cache.put(request, copy);
          });
        }
        return response;
      }).catch(function () {
        return cached;
      });

      return cached || network;
    })
  );
});
		<?php
		exit;
	}
}
add_action( 'template_redirect', 'rp_child_serve_pwa_endpoints', 0 );

function rp_child_register_menus() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );

	register_nav_menus(
		array(
			'main-navigation' => __( 'Primary Navigation', 'resilient-hub' ),
			'rp-footer'       => __( 'Footer Navigation', 'resilient-hub' ),
		)
	);
}
add_action( 'after_setup_theme', 'rp_child_register_menus' );

function rp_child_dequeue_legacy_assets() {
	if ( is_admin() ) {
		return;
	}

	wp_dequeue_script( 'foreverwood-flexslider' );
	wp_dequeue_script( 'foreverwood-flexslider-settings' );
	wp_dequeue_script( 'foreverwood-placeholders' );
	wp_dequeue_script( 'foreverwood-scroll-to-top' );
	wp_dequeue_script( 'foreverwood-selectnav' );
	wp_dequeue_style( 'foreverwood-google-font-default' );
}
add_action( 'wp_enqueue_scripts', 'rp_child_dequeue_legacy_assets', 100 );

function rp_child_upload_url( $relative_path ) {
	$upload_dir = wp_get_upload_dir();
	return trailingslashit( $upload_dir['baseurl'] ) . ltrim( $relative_path, '/' );
}

function rp_child_clean_archive_title( $title ) {
	if ( is_category() || is_tag() || is_tax() ) {
		$title = single_term_title( '', false );
	} elseif ( is_author() ) {
		$title = get_the_author();
	}

	return $title;
}
add_filter( 'get_the_archive_title', 'rp_child_clean_archive_title' );

/**
 * Redirect users to /resource-hub/ upon login unless a specific redirect_to parameter is present.
 */
function rp_child_login_redirect( $redirect_to, $request, $user ) {
	if ( is_wp_error( $user ) ) {
		return $redirect_to;
	}
	if ( ! empty( $request ) && strpos( $request, 'wp-admin' ) === false ) {
		return $request;
	}
	return home_url( '/resource-hub/' );
}
add_filter( 'login_redirect', 'rp_child_login_redirect', 10, 3 );

/**
 * Redirect users to the homepage upon logging out.
 */
function rp_child_logout_redirect( $redirect_to, $requested_redirect_to, $user ) {
	return home_url( '/' );
}
add_filter( 'logout_redirect', 'rp_child_logout_redirect', 10, 3 );

/**
 * Protect the submit-resource and my-contributions pages and redirect logged-out users to the custom portal entry page.
 */
function rp_child_submit_resource_auth_gate() {
	if ( ! is_user_logged_in() ) {
		if ( is_page( 'submit-resource' ) ) {
			$login_url    = home_url( '/portal-entry/' );
			$redirect_url = add_query_arg( 'redirect_to', esc_url( home_url( '/submit-resource/' ) ), $login_url );
			wp_safe_redirect( $redirect_url );
			exit;
		} elseif ( is_page( 'submit-post' ) ) {
			$login_url    = home_url( '/portal-entry/' );
			$redirect_url = add_query_arg( 'redirect_to', esc_url( home_url( '/submit-post/' ) ), $login_url );
			wp_safe_redirect( $redirect_url );
			exit;
		} elseif ( is_page( 'my-contributions' ) ) {
			$login_url    = home_url( '/portal-entry/' );
			$redirect_url = add_query_arg( 'redirect_to', esc_url( home_url( '/my-contributions/' ) ), $login_url );
			wp_safe_redirect( $redirect_url );
			exit;
		}
	} else {
		if ( is_page( 'submit-post' ) && ! current_user_can( 'publish_posts' ) && ! current_user_can( 'manage_options' ) ) {
			wp_safe_redirect( home_url( '/' ) );
			exit;
		}
	}
}
add_action( 'template_redirect', 'rp_child_submit_resource_auth_gate' );

/**
 * Automatically activate GiveWP and set up default donation form and page.
 */
function rp_child_setup_donation_platform() {
	if ( ! function_exists( 'is_plugin_active' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	if ( ! is_plugin_active( 'give/give.php' ) ) {
		activate_plugin( 'give/give.php', '', false, true );
	}

	if ( file_exists( WP_PLUGIN_DIR . '/contact-form-7/wp-contact-form-7.php' ) && ! is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {
		activate_plugin( 'contact-form-7/wp-contact-form-7.php', '', false, true );
	}

	// Resolve slug conflict: check if there's an attachment with slug 'donate'
	$donate_attachment = get_page_by_path( 'donate', OBJECT, 'attachment' );
	if ( $donate_attachment ) {
		wp_update_post( array(
			'ID'        => $donate_attachment->ID,
			'post_name' => 'donate-attachment',
		) );
	}

	// Update global GiveWP settings to PHP currency
	$give_settings = get_option( 'give_settings', array() );
	if ( ! is_array( $give_settings ) ) {
		$give_settings = array();
	}
	if ( empty( $give_settings['currency'] ) || 'PHP' !== $give_settings['currency'] ) {
		$give_settings['currency'] = 'PHP';
		update_option( 'give_settings', $give_settings );
	}

	if ( post_type_exists( 'give_forms' ) ) {
		$existing_forms = get_posts( array(
			'post_type'   => 'give_forms',
			'post_status' => 'any',
			'numberposts' => 1,
		) );

		if ( ! empty( $existing_forms ) ) {
			$form_id = $existing_forms[0]->ID;
		} else {
			$form_id = wp_insert_post( array(
				'post_title'   => __( 'Support ACCORD\'s Community Resilience Programs', 'resilient-hub' ),
				'post_content' => __( 'Your donation helps us build resilient communities, train local disaster response teams, and provide aid during emergencies.', 'resilient-hub' ),
				'post_status'  => 'publish',
				'post_type'    => 'give_forms',
			) );
		}

		if ( $form_id && ! is_wp_error( $form_id ) ) {
			// Always sync options to ensure correct levels and PHP currency/display
			update_post_meta( $form_id, '_give_price_option', 'multi' );
			update_post_meta( $form_id, '_give_payment_display', 'onpage' );
			update_post_meta( $form_id, '_give_display_style', 'buttons' );

			$levels = array(
				array(
					'_give_id' => array( 'level_id' => 1 ),
					'_give_amount' => '500',
					'_give_text' => '500 PHP',
				),
				array(
					'_give_id' => array( 'level_id' => 2 ),
					'_give_amount' => '1000',
					'_give_text' => '1,000 PHP',
					'_give_default' => 'default',
				),
				array(
					'_give_id' => array( 'level_id' => 3 ),
					'_give_amount' => '2500',
					'_give_text' => '2,500 PHP',
				),
				array(
					'_give_id' => array( 'level_id' => 4 ),
					'_give_amount' => '5000',
					'_give_text' => '5,000 PHP',
				),
			);
			update_post_meta( $form_id, '_give_donation_levels', $levels );
			update_post_meta( $form_id, '_give_custom_amount', 'enabled' );
			update_post_meta( $form_id, '_give_custom_amount_text', 'Custom Amount' );
		}

		if ( $form_id && ! is_wp_error( $form_id ) ) {
			$donate_page = get_page_by_path( 'donate', OBJECT, 'page' );
			if ( ! $donate_page ) {
				wp_insert_post( array(
					'post_type'    => 'page',
					'post_status'  => 'publish',
					'post_name'    => 'donate',
					'post_title'   => __( 'Donate', 'resilient-hub' ),
					'post_content' => '[give_form id="' . absint( $form_id ) . '"]',
				) );
				flush_rewrite_rules();
			}
		}
	}
}
add_action( 'init', 'rp_child_setup_donation_platform' );

/**
 * AJAX handler for approving pending partner resources.
 */
function rp_ajax_approve_resource_handler() {
	if ( ! is_user_logged_in() ) {
		wp_send_json_error( array( 'message' => __( 'You must be logged in.', 'resilient-hub' ) ) );
	}

	if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'publish_posts' ) && ! current_user_can( 'publish_partner_resources' ) ) {
		wp_send_json_error( array( 'message' => __( 'You do not have permission to approve resources.', 'resilient-hub' ) ) );
	}

	$post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;
	$nonce   = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

	if ( ! $post_id || ! wp_verify_nonce( $nonce, 'rp_approve_resource_' . $post_id ) ) {
		wp_send_json_error( array( 'message' => __( 'Security check failed. Please refresh and try again.', 'resilient-hub' ) ) );
	}

	$post = get_post( $post_id );
	if ( ! $post || ! in_array( $post->post_type, array( 'partner_resources', 'rp_sitrep', 'accord_library', 'post' ), true ) || 'pending' !== $post->post_status ) {
		wp_send_json_error( array( 'message' => __( 'Submission not found or is not pending review.', 'resilient-hub' ) ) );
	}

	// Update the post status to publish
	$result = wp_update_post( array(
		'ID'          => $post_id,
		'post_status' => 'publish',
	) );

	if ( is_wp_error( $result ) ) {
		wp_send_json_error( array( 'message' => $result->get_error_message() ) );
	}

	wp_send_json_success( array( 'message' => __( 'Published successfully.', 'resilient-hub' ) ) );
}
add_action( 'wp_ajax_rp_approve_resource', 'rp_ajax_approve_resource_handler' );

/**
 * AJAX handler for updating a user's role from the frontend User Management page.
 */
function rp_ajax_update_user_role_handler() {
	if ( ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( array( 'message' => __( 'You do not have permission to manage users.', 'resilient-hub' ) ) );
	}

	$user_id  = isset( $_POST['user_id'] ) ? absint( $_POST['user_id'] ) : 0;
	$new_role = isset( $_POST['new_role'] ) ? sanitize_key( $_POST['new_role'] ) : '';
	$nonce    = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

	if ( ! $user_id || ! wp_verify_nonce( $nonce, 'rp_update_role_' . $user_id ) ) {
		wp_send_json_error( array( 'message' => __( 'Security check failed. Please refresh and try again.', 'resilient-hub' ) ) );
	}

	// Prevent users from changing their own role
	if ( get_current_user_id() === $user_id ) {
		wp_send_json_error( array( 'message' => __( 'You cannot change your own role.', 'resilient-hub' ) ) );
	}

	$allowed_roles = array( 'administrator', 'editor', 'partner_contributor', 'hub_subscriber', 'subscriber' );
	if ( ! in_array( $new_role, $allowed_roles, true ) ) {
		wp_send_json_error( array( 'message' => __( 'Invalid role selected.', 'resilient-hub' ) ) );
	}

	$user = get_userdata( $user_id );
	if ( ! $user ) {
		wp_send_json_error( array( 'message' => __( 'User not found.', 'resilient-hub' ) ) );
	}

	$user->set_role( $new_role );

	$role_labels = array(
		'administrator'       => __( 'Administrator', 'resilient-hub' ),
		'editor'              => __( 'Editor', 'resilient-hub' ),
		'partner_contributor' => __( 'Partner Contributor', 'resilient-hub' ),
		'hub_subscriber'      => __( 'Hub Subscriber', 'resilient-hub' ),
		'subscriber'          => __( 'Subscriber', 'resilient-hub' ),
	);
	$label = isset( $role_labels[ $new_role ] ) ? $role_labels[ $new_role ] : $new_role;

	wp_send_json_success( array(
		'message' => sprintf(
			/* translators: 1: display name, 2: new role */
			__( '%1$s is now %2$s', 'resilient-hub' ),
			$user->display_name,
			$label
		),
	) );
}
add_action( 'wp_ajax_rp_update_user_role', 'rp_ajax_update_user_role_handler' );

/**
 * Auto-create the User Management page if it doesn't exist.
 */
function rp_child_create_user_management_page() {
	if ( get_page_by_path( 'user-management' ) ) {
		return;
	}

	$post_id = wp_insert_post( array(
		'post_type'    => 'page',
		'post_status'  => 'publish',
		'post_name'    => 'user-management',
		'post_title'   => __( 'User Management', 'resilient-hub' ),
		'post_content' => '',
	) );

	if ( $post_id && ! is_wp_error( $post_id ) ) {
		update_post_meta( $post_id, '_wp_page_template', 'template-user-management.php' );
	}
}
add_action( 'init', 'rp_child_create_user_management_page' );

/**
 * Auto-create the Analytics Dashboard page if it doesn't exist.
 */
function rp_child_create_analytics_page() {
	if ( get_page_by_path( 'analytics-dashboard' ) ) {
		return;
	}

	$post_id = wp_insert_post( array(
		'post_type'    => 'page',
		'post_status'  => 'publish',
		'post_name'    => 'analytics-dashboard',
		'post_title'   => __( 'Analytics Dashboard', 'resilient-hub' ),
		'post_content' => '',
	) );

	if ( $post_id && ! is_wp_error( $post_id ) ) {
		update_post_meta( $post_id, '_wp_page_template', 'template-analytics.php' );
	}
}
add_action( 'init', 'rp_child_create_analytics_page' );

/**
 * Auto-create the compliance policies pages if they don't exist.
 */
function rp_child_create_compliance_pages() {
	$policies = array(
		'privacy-policy' => array(
			'title'   => __( 'Privacy Policy', 'resilient-hub' ),
			'content' => '<h2>1. Data Minimisation & Scope</h2><p>We only collect the absolute minimum data required to facilitate secure portal collaboration. This includes names, email addresses, and encrypted passwords for user authentication. Furthermore, we log standard client IP addresses and user agents when users view portal content or download files for security auditing and lightweight service metrics.</p><h2>2. Consent & Opt-Out</h2><p>Consistent with GDPR guidelines, portal account registration requires affirmative consent. All Optional tracking/analytics are disabled if you toggle your tracking preferences off in the Cookie Consent Banner or the Privacy & Data Rights dashboard.</p><h2>3. Data Access & Rights</h2><p>You maintain full ownership of your data. You may download a copy of all information logged on your profile (Data Portability) or request account deletion (Right to Erasure) directly in our self-service portal.</p>',
		),
		'terms-of-service' => array(
			'title'   => __( 'Terms of Service', 'resilient-hub' ),
			'content' => '<h2>1. Agreement & Account Rules</h2><p>By creating a Resilience Hub account, you agree to safeguard your credentials and refrain from uploading malicious files. All materials submitted must correspond to humanitarian learning, disaster risk reduction resources, and partner knowledge products.</p><h2>2. Acceptable Use Policy</h2><p>User submissions containing copyright infringement, promotional advertisements, or non-DRR information will be immediately deleted, and the offending account terminated without notice.</p><h2>3. Disclaimers</h2><p>ACCORD provides this platform as a collaborative resource. We do not warrant the accuracy of user-submitted materials and exclude all liability to the maximum extent permitted under law.</p>',
		),
		'cookie-policy' => array(
			'title'   => __( 'Cookie Policy', 'resilient-hub' ),
			'content' => '<h2>1. Strictly Necessary Cookies</h2><p>We deploy session and security cookies (`wordpress_logged_in_*`, `wp-settings-*`, and security tokens) to authenticate your profile and secure form submissions. These do not track cross-site behavior and are strictly necessary for platform operation.</p><h2>2. Optional Analytics Cookies</h2><p>If you consent to optional analytics, we use a custom self-hosted tracker cookie to monitor aggregate page views and file download events to identify popular materials. You can modify these choices at any time in the Privacy Portal.</p>',
		),
		'profile' => array(
			'title'   => __( 'My Profile & Privacy', 'resilient-hub' ),
			'content' => '',
		),
	);

	foreach ( $policies as $slug => $data ) {
		if ( get_page_by_path( $slug ) ) {
			continue;
		}

		$post_id = wp_insert_post( array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_name'    => $slug,
			'post_title'   => $data['title'],
			'post_content' => $data['content'],
		) );

		if ( $post_id && ! is_wp_error( $post_id ) && 'profile' === $slug ) {
			update_post_meta( $post_id, '_wp_page_template', 'template-profile.php' );
		}
	}
}
add_action( 'init', 'rp_child_create_compliance_pages' );

/**
 * One-time migration: Rebuild the primary navigation menu to consolidate
 * overlapping items (Posts, Stories, Library, Resource Hub) into a single
 * "Resources" parent with sub-items.
 *
 * Runs once, controlled by an option flag.
 */
function rp_child_consolidate_navigation_menu() {
	if ( get_option( 'rp_nav_consolidated_v10' ) ) {
		return;
	}

	$menu_name     = 'Resilient Hub Primary';
	$menu_location = 'main-navigation';

	// Delete existing menu if it exists so we can rebuild cleanly.
	$existing_menu = wp_get_nav_menu_object( $menu_name );
	if ( $existing_menu ) {
		wp_delete_nav_menu( $existing_menu->term_id );
	}

	// Also try to clear any menu currently assigned to this location.
	$locations = get_nav_menu_locations();
	if ( ! empty( $locations[ $menu_location ] ) ) {
		$old_menu = wp_get_nav_menu_object( $locations[ $menu_location ] );
		if ( $old_menu ) {
			wp_delete_nav_menu( $old_menu->term_id );
		}
	}

	$menu_id = wp_create_nav_menu( $menu_name );
	if ( is_wp_error( $menu_id ) ) {
		return;
	}

	$position = 0;

	// ── About Us (parent) ──────────────────────────────────────────────────
	$about_parent_id = 0;
	$about_page = get_page_by_path( 'about-us' );
	if ( $about_page ) {
		$about_parent_id = wp_update_nav_menu_item( $menu_id, 0, array(
			'menu-item-title'     => __( 'About Us', 'resilient-hub' ),
			'menu-item-object'    => 'page',
			'menu-item-object-id' => $about_page->ID,
			'menu-item-type'      => 'post_type',
			'menu-item-status'    => 'publish',
			'menu-item-position'  => ++$position,
		) );
	}

	// ── About Us > Sub-items ───────────────────────────────────────────────
	if ( $about_parent_id && ! is_wp_error( $about_parent_id ) ) {
		// Who We Are (Who We Are)
		$story_page = get_page_by_path( 'about-us-who-we-are' );
		if ( $story_page ) {
			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title'     => __( 'Who We Are', 'resilient-hub' ),
				'menu-item-object'    => 'page',
				'menu-item-object-id' => $story_page->ID,
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish',
				'menu-item-position'  => ++$position,
				'menu-item-parent-id' => $about_parent_id,
			) );
		}

		// What We Do
		$what_we_do_page = get_page_by_path( 'about-us-what-we-do' );
		if ( $what_we_do_page ) {
			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title'     => __( 'What We Do', 'resilient-hub' ),
				'menu-item-object'    => 'page',
				'menu-item-object-id' => $what_we_do_page->ID,
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish',
				'menu-item-position'  => ++$position,
				'menu-item-parent-id' => $about_parent_id,
			) );
		}

		// Our Team
		$team_page = get_page_by_path( 'about-us-our-team' );
		if ( $team_page ) {
			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title'     => __( 'Our Team', 'resilient-hub' ),
				'menu-item-object'    => 'page',
				'menu-item-object-id' => $team_page->ID,
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish',
				'menu-item-position'  => ++$position,
				'menu-item-parent-id' => $about_parent_id,
			) );
		}

		// Our Partners
		$partners_page = get_page_by_path( 'about-us-partners' );
		if ( $partners_page ) {
			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title'     => __( 'Our Partners', 'resilient-hub' ),
				'menu-item-object'    => 'page',
				'menu-item-object-id' => $partners_page->ID,
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish',
				'menu-item-position'  => ++$position,
				'menu-item-parent-id' => $about_parent_id,
			) );
		}
	}

	// ── Programmes (parent) ────────────────────────────────────────────────
	$programmes_parent_id = 0;
	$programmes_page = get_page_by_path( 'programmes' );
	if ( ! $programmes_page ) {
		$programmes_page = get_page_by_path( 'programs' );
	}
	if ( $programmes_page ) {
		$programmes_parent_id = wp_update_nav_menu_item( $menu_id, 0, array(
			'menu-item-title'     => __( 'Programmes', 'resilient-hub' ),
			'menu-item-object'    => 'page',
			'menu-item-object-id' => $programmes_page->ID,
			'menu-item-type'      => 'post_type',
			'menu-item-status'    => 'publish',
			'menu-item-position'  => ++$position,
		) );
	}

	// ── Programmes > Sub-items ─────────────────────────────────────────────
	if ( $programmes_parent_id && ! is_wp_error( $programmes_parent_id ) ) {
		// Move Up
		$move_up_page = get_page_by_path( 'progs-move-up' );
		if ( $move_up_page ) {
			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title'     => __( 'Move Up', 'resilient-hub' ),
				'menu-item-object'    => 'page',
				'menu-item-object-id' => $move_up_page->ID,
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish',
				'menu-item-position'  => ++$position,
				'menu-item-parent-id' => $programmes_parent_id,
			) );
		}

		// Partners for Resilience
		$pfr_page = get_page_by_path( 'progs-partners-for-resilience' );
		if ( $pfr_page ) {
			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title'     => __( 'Partners for Resilience', 'resilient-hub' ),
				'menu-item-object'    => 'page',
				'menu-item-object-id' => $pfr_page->ID,
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish',
				'menu-item-position'  => ++$position,
				'menu-item-parent-id' => $programmes_parent_id,
			) );
		}

		// Proud of My Purok
		$pomp_page = get_page_by_path( 'prog-proud-of-my-purok' );
		if ( $pomp_page ) {
			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title'     => __( 'Proud of My Purok', 'resilient-hub' ),
				'menu-item-object'    => 'page',
				'menu-item-object-id' => $pomp_page->ID,
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish',
				'menu-item-position'  => ++$position,
				'menu-item-parent-id' => $programmes_parent_id,
			) );
		}

		// Inclusion
		$inclusion_page = get_page_by_path( 'progs-inclusion' );
		if ( $inclusion_page ) {
			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title'     => __( 'Inclusion', 'resilient-hub' ),
				'menu-item-object'    => 'page',
				'menu-item-object-id' => $inclusion_page->ID,
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish',
				'menu-item-position'  => ++$position,
				'menu-item-parent-id' => $programmes_parent_id,
			) );
		}
	}

	// ── News & Stories ────────────────────────────────────────────────────
	$news_stories_page = get_page_by_path( 'news-stories' );
	if ( $news_stories_page ) {
		wp_update_nav_menu_item( $menu_id, 0, array(
			'menu-item-title'     => __( 'News & Stories', 'resilient-hub' ),
			'menu-item-object'    => 'page',
			'menu-item-object-id' => $news_stories_page->ID,
			'menu-item-type'      => 'post_type',
			'menu-item-status'    => 'publish',
			'menu-item-position'  => ++$position,
		) );
	} else {
		wp_update_nav_menu_item( $menu_id, 0, array(
			'menu-item-title'     => __( 'News & Stories', 'resilient-hub' ),
			'menu-item-url'       => home_url( '/news-stories/' ),
			'menu-item-type'      => 'custom',
			'menu-item-status'    => 'publish',
			'menu-item-position'  => ++$position,
		) );
	}


	// ── Resources (parent) ────────────────────────────────────────────────
	$resources_parent_id = wp_update_nav_menu_item( $menu_id, 0, array(
		'menu-item-title'  => __( 'Resources', 'resilient-hub' ),
		'menu-item-url'    => home_url( '/resource-hub/' ),
		'menu-item-type'   => 'custom',
		'menu-item-status' => 'publish',
		'menu-item-position' => ++$position,
	) );

	// ── Resources > Sub-items ─────────────────────────────────────────────
	if ( ! is_wp_error( $resources_parent_id ) ) {
		// Partner Resources archive
		wp_update_nav_menu_item( $menu_id, 0, array(
			'menu-item-title'     => __( 'Partner Resources', 'resilient-hub' ),
			'menu-item-url'       => home_url( '/partner-resources/' ),
			'menu-item-type'      => 'custom',
			'menu-item-status'    => 'publish',
			'menu-item-position'  => ++$position,
			'menu-item-parent-id' => $resources_parent_id,
		) );

		// Situation Reports
		wp_update_nav_menu_item( $menu_id, 0, array(
			'menu-item-title'     => __( 'Situation Reports', 'resilient-hub' ),
			'menu-item-url'       => home_url( '/sitrep-dashboard/' ),
			'menu-item-type'      => 'custom',
			'menu-item-status'    => 'publish',
			'menu-item-position'  => ++$position,
			'menu-item-parent-id' => $resources_parent_id,
		) );

		// Submit a Resource
		$submit_page = get_page_by_path( 'submit-resource' );
		if ( $submit_page ) {
			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title'     => __( 'Submit a Resource', 'resilient-hub' ),
				'menu-item-object'    => 'page',
				'menu-item-object-id' => $submit_page->ID,
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish',
				'menu-item-position'  => ++$position,
				'menu-item-parent-id' => $resources_parent_id,
			) );
		}

		// Submit a SitRep
		$submit_sitrep_page = get_page_by_path( 'submit-sitrep' );
		if ( $submit_sitrep_page ) {
			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title'     => __( 'Submit a SitRep', 'resilient-hub' ),
				'menu-item-object'    => 'page',
				'menu-item-object-id' => $submit_sitrep_page->ID,
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish',
				'menu-item-position'  => ++$position,
				'menu-item-parent-id' => $resources_parent_id,
			) );
		}

		// Moderation Dashboard
		$mod_page = get_page_by_path( 'moderation-dashboard' );
		if ( $mod_page ) {
			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title'     => __( 'Moderation Dashboard', 'resilient-hub' ),
				'menu-item-object'    => 'page',
				'menu-item-object-id' => $mod_page->ID,
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish',
				'menu-item-position'  => ++$position,
				'menu-item-parent-id' => $resources_parent_id,
			) );
		}
	}

	// ── Donors ────────────────────────────────────────────────────────────
	$donors_page = get_page_by_path( 'donors' );
	if ( $donors_page ) {
		wp_update_nav_menu_item( $menu_id, 0, array(
			'menu-item-title'     => __( 'Donors', 'resilient-hub' ),
			'menu-item-object'    => 'page',
			'menu-item-object-id' => $donors_page->ID,
			'menu-item-type'      => 'post_type',
			'menu-item-status'    => 'publish',
			'menu-item-position'  => ++$position,
		) );
	}

	// ── Contact Us ────────────────────────────────────────────────────────
	$contact_page = get_page_by_path( 'contact-us' );
	if ( $contact_page ) {
		wp_update_nav_menu_item( $menu_id, 0, array(
			'menu-item-title'     => __( 'Contact Us', 'resilient-hub' ),
			'menu-item-object'    => 'page',
			'menu-item-object-id' => $contact_page->ID,
			'menu-item-type'      => 'post_type',
			'menu-item-status'    => 'publish',
			'menu-item-position'  => ++$position,
		) );
	}

	// Assign the menu to the theme location.
	$locations                    = get_theme_mod( 'nav_menu_locations', array() );
	$locations[ $menu_location ]  = $menu_id;
	set_theme_mod( 'nav_menu_locations', $locations );

	update_option( 'rp_nav_consolidated_v10', true );
}
add_action( 'admin_init', 'rp_child_consolidate_navigation_menu' );

function rp_child_menu_has_tinig_link( $menu_id ) {
	$items = wp_get_nav_menu_items( $menu_id );
	if ( ! $items ) {
		return false;
	}

	foreach ( $items as $item ) {
		$item_url = untrailingslashit( (string) $item->url );
		if ( false !== strpos( $item_url, '/tinig' ) ) {
			return true;
		}
	}

	return false;
}

function rp_child_add_tinig_to_menu( $menu_id, $title ) {
	if ( ! $menu_id || rp_child_menu_has_tinig_link( $menu_id ) ) {
		return;
	}

	wp_update_nav_menu_item(
		$menu_id,
		0,
		array(
			'menu-item-title'  => $title,
			'menu-item-url'    => home_url( '/tinig/' ),
			'menu-item-type'   => 'custom',
			'menu-item-status' => 'publish',
		)
	);
}

function rp_child_add_tinig_nav_links() {
	if ( get_option( 'rp_tinig_nav_links_v1' ) ) {
		return;
	}

	if ( ! get_page_by_path( 'tinig' ) ) {
		return;
	}

	$locations = get_nav_menu_locations();

	if ( ! empty( $locations['main-navigation'] ) ) {
		rp_child_add_tinig_to_menu( $locations['main-navigation'], __( 'Tinig', 'resilient-hub' ) );
	}

	if ( ! empty( $locations['rp-footer'] ) ) {
		rp_child_add_tinig_to_menu( $locations['rp-footer'], __( 'Tinig Feedback', 'resilient-hub' ) );
	}

	update_option( 'rp_tinig_nav_links_v1', true );
}
add_action( 'init', 'rp_child_add_tinig_nav_links', 30 );

function rp_child_menu_has_opportunities_link( $menu_id ) {
	$items = wp_get_nav_menu_items( $menu_id );
	if ( ! $items ) {
		return false;
	}

	foreach ( $items as $item ) {
		$item_url = untrailingslashit( (string) $item->url );
		if ( false !== strpos( $item_url, '/opportunities' ) ) {
			return true;
		}
	}

	return false;
}

function rp_child_add_opportunities_to_menu( $menu_id, $title ) {
	if ( ! $menu_id || rp_child_menu_has_opportunities_link( $menu_id ) ) {
		return;
	}

	wp_update_nav_menu_item(
		$menu_id,
		0,
		array(
			'menu-item-title'  => $title,
			'menu-item-url'    => home_url( '/opportunities/' ),
			'menu-item-type'   => 'custom',
			'menu-item-status' => 'publish',
		)
	);
}

function rp_child_add_opportunities_nav_links() {
	if ( get_option( 'rp_opportunities_nav_links_v1' ) ) {
		return;
	}

	if ( ! get_page_by_path( 'opportunities' ) ) {
		return;
	}

	$locations = get_nav_menu_locations();

	if ( ! empty( $locations['main-navigation'] ) ) {
		rp_child_add_opportunities_to_menu( $locations['main-navigation'], __( 'Opportunities', 'resilient-hub' ) );
	}

	if ( ! empty( $locations['rp-footer'] ) ) {
		rp_child_add_opportunities_to_menu( $locations['rp-footer'], __( 'Opportunities', 'resilient-hub' ) );
	}

	update_option( 'rp_opportunities_nav_links_v1', true );
}
add_action( 'init', 'rp_child_add_opportunities_nav_links', 31 );

/**
 * Filter the primary navigation menu to hide the Moderation Dashboard for users without moderation permissions.
 */
function rp_child_filter_primary_nav_menu( $items, $args ) {
	if ( 'main-navigation' !== $args->theme_location ) {
		return $items;
	}

	$can_moderate = current_user_can( 'manage_options' ) || current_user_can( 'publish_posts' ) || current_user_can( 'publish_partner_resources' ) || current_user_can( 'publish_rp_sitreps' );

	foreach ( $items as $key => $item ) {
		if ( strpos( strtolower( $item->url ), '/moderation-dashboard' ) !== false && ! $can_moderate ) {
			unset( $items[ $key ] );
		}
	}

	return $items;
}
add_filter( 'wp_nav_menu_objects', 'rp_child_filter_primary_nav_menu', 10, 2 );

/**
 * Intercept Data Portability JSON Exports
 */
function rp_handle_user_data_export() {
	if ( isset( $_GET['rp_download_user_data'] ) && is_user_logged_in() ) {
		$user_id = get_current_user_id();
		$user = get_userdata( $user_id );
		
		global $wpdb;
		// Fetch views from DB
		$views = $wpdb->get_results( $wpdb->prepare(
			"SELECT id, post_id, ip_address, user_agent, created_at FROM {$wpdb->prefix}rp_analytics_views WHERE user_id = %d ORDER BY created_at DESC",
			$user_id
		) );
		
		// Fetch downloads from DB
		$downloads = $wpdb->get_results( $wpdb->prepare(
			"SELECT id, post_id, ip_address, user_agent, created_at FROM {$wpdb->prefix}rp_analytics_downloads WHERE user_id = %d ORDER BY created_at DESC",
			$user_id
		) );
		
		$export_data = array(
			'profile'   => array(
				'username'     => $user->user_login,
				'email'        => $user->user_email,
				'display_name' => $user->display_name,
				'first_name'   => $user->first_name,
				'last_name'    => $user->last_name,
				'pronouns'     => get_user_meta( $user_id, '_rp_pronouns', true ),
				'affiliation'  => get_user_meta( $user_id, '_rp_affiliation', true ),
				'registered'   => $user->user_registered,
				'roles'        => $user->roles,
			),
			'activity'  => array(
				'page_views_logged' => $views,
				'file_downloads'    => $downloads,
			),
			'meta'      => array(
				'exported_at' => current_time( 'mysql' ),
				'platform'    => get_bloginfo( 'name' ),
			)
		);
		
		header( 'Content-Type: application/json; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename="rp-user-data-export-' . sanitize_key( $user->user_login ) . '.json"' );
		echo wp_json_encode( $export_data, JSON_PRETTY_PRINT );
		exit;
	}
}
add_action( 'init', 'rp_handle_user_data_export' );

/**
 * AJAX Handler: File Erasure/Deletion Request
 */
function rp_ajax_request_account_deletion_handler() {
	if ( ! is_user_logged_in() ) {
		wp_send_json_error( array( 'message' => __( 'You must be logged in.', 'resilient-hub' ) ) );
	}
	
	$user_id = get_current_user_id();
	$user = get_userdata( $user_id );
	
	$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
	if ( ! wp_verify_nonce( $nonce, 'rp_request_deletion_' . $user_id ) ) {
		wp_send_json_error( array( 'message' => __( 'Security check failed. Please refresh the page and try again.', 'resilient-hub' ) ) );
	}
	
	update_user_meta( $user_id, '_rp_deletion_requested', 1 );
	
	$admin_email = get_option( 'admin_email' );
	$subject = sprintf( __( '[Resilient Hub] Account Deletion Request: @%s', 'resilient-hub' ), $user->user_login );
	$message = sprintf(
		__( "User @%s (%s) has submitted an account deletion request under GDPR Right to Erasure.\n\nPlease process this request inside the User Management dashboard:\n%s", 'resilient-hub' ),
		$user->user_login,
		$user->user_email,
		home_url( '/user-management/' )
	);
	wp_mail( $admin_email, $subject, $message );
	
	wp_send_json_success( array( 'message' => __( 'Your deletion request has been submitted. The administrator will process it and delete your profile within 72 hours.', 'resilient-hub' ) ) );
}
add_action( 'wp_ajax_rp_request_account_deletion', 'rp_ajax_request_account_deletion_handler' );

/**
 * AJAX Handler: Admin Executing User Deletion
 */
function rp_ajax_delete_user_handler() {
	if ( ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( array( 'message' => __( 'You do not have permission to manage users.', 'resilient-hub' ) ) );
	}
	
	$user_id = isset( $_POST['user_id'] ) ? absint( $_POST['user_id'] ) : 0;
	$nonce   = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
	
	if ( ! $user_id || ! wp_verify_nonce( $nonce, 'rp_delete_user_' . $user_id ) ) {
		wp_send_json_error( array( 'message' => __( 'Security check failed. Please refresh the page and try again.', 'resilient-hub' ) ) );
	}
	
	if ( get_current_user_id() === $user_id ) {
		wp_send_json_error( array( 'message' => __( 'You cannot delete yourself.', 'resilient-hub' ) ) );
	}
	
	$user = get_userdata( $user_id );
	if ( ! $user ) {
		wp_send_json_error( array( 'message' => __( 'User not found.', 'resilient-hub' ) ) );
	}
	
	require_once ABSPATH . 'wp-admin/includes/user.php';
	$result = wp_delete_user( $user_id, get_current_user_id() );
	
	if ( $result ) {
		wp_send_json_success( array( 'message' => sprintf( __( 'User %s was successfully deleted and their resources were reassigned.', 'resilient-hub' ), $user->display_name ) ) );
	} else {
		wp_send_json_error( array( 'message' => __( 'Failed to delete the user. Please try again.', 'resilient-hub' ) ) );
	}
}
add_action( 'wp_ajax_rp_delete_user', 'rp_ajax_delete_user_handler' );

/**
 * AJAX Handler: Save User Profile Details
 */
function rp_ajax_save_user_profile_handler() {
	if ( ! is_user_logged_in() ) {
		wp_send_json_error( array( 'message' => __( 'You must be logged in.', 'resilient-hub' ) ) );
	}
	
	$user_id = get_current_user_id();
	
	$nonce = isset( $_POST['rp_profile_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['rp_profile_nonce'] ) ) : '';
	if ( ! wp_verify_nonce( $nonce, 'rp_save_profile_' . $user_id ) ) {
		wp_send_json_error( array( 'message' => __( 'Security check failed. Please refresh the page and try again.', 'resilient-hub' ) ) );
	}
	
	$display_name = isset( $_POST['rp_display_name'] ) ? sanitize_text_field( wp_unslash( $_POST['rp_display_name'] ) ) : '';
	$pronouns     = isset( $_POST['rp_pronouns'] ) ? sanitize_text_field( wp_unslash( $_POST['rp_pronouns'] ) ) : '';
	$affiliation  = isset( $_POST['rp_affiliation'] ) ? sanitize_text_field( wp_unslash( $_POST['rp_affiliation'] ) ) : '';
	
	if ( empty( $display_name ) ) {
		wp_send_json_error( array( 'message' => __( 'Display Name is required.', 'resilient-hub' ) ) );
	}
	
	// Update user standard fields
	require_once ABSPATH . 'wp-admin/includes/user.php';
	$update_result = wp_update_user( array(
		'ID'           => $user_id,
		'display_name' => $display_name,
	) );
	
	if ( is_wp_error( $update_result ) ) {
		wp_send_json_error( array( 'message' => $update_result->get_error_message() ) );
	}
	
	// Update custom user meta fields
	update_user_meta( $user_id, '_rp_pronouns', $pronouns );
	update_user_meta( $user_id, '_rp_affiliation', $affiliation );
	
	wp_send_json_success( array( 'message' => __( 'Profile details saved successfully.', 'resilient-hub' ) ) );
}
add_action( 'wp_ajax_rp_save_user_profile', 'rp_ajax_save_user_profile_handler' );

/**
 * Customize the excerpt more link for resource hub knowledge products and partner resources.
 */
function rp_child_resource_excerpt_more( $more ) {
	if ( is_singular( array( 'accord_library', 'partner_resources' ) ) || get_post_type() === 'accord_library' || get_post_type() === 'partner_resources' ) {
		return ' &hellip;';
	}
	return $more;
}
add_filter( 'excerpt_more', 'rp_child_resource_excerpt_more', 100 );

<?php
/**
 * Plugin Name: Resilient Philippines Resource Hub
 * Description: Custom post types, taxonomies, roles, upload workflow, and catalog shortcodes for the humanitarian resource hub.
 * Version: 1.12.0
 * Author: ACCORD
 * Text Domain: rp-resource-hub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'RP_RESOURCE_HUB_VERSION', '1.12.0' );
define( 'RP_RESOURCE_HUB_FILE', __FILE__ );
define( 'RP_RESOURCE_HUB_PATH', plugin_dir_path( __FILE__ ) );
define( 'RP_RESOURCE_HUB_URL', plugin_dir_url( __FILE__ ) );
define( 'RP_RESOURCE_HUB_MAX_UPLOAD_BYTES', 25 * 1024 * 1024 );
define( 'RP_TINIG_NOTIFICATION_EMAIL', 'tinig@accord.org.ph' );
define( 'RP_CONTACT_NOTIFICATION_EMAIL', 'info@accord.org.ph' );
define( 'RP_JOB_NOTIFICATION_EMAIL', 'hrd@accord.org.ph' );
define( 'RP_BID_NOTIFICATION_EMAIL', 'procurement@accord.org.ph' );
define( 'RP_TINIG_MAX_ATTACHMENT_BYTES', 10 * 1024 * 1024 );
define( 'RP_TINIG_MAX_ATTACHMENTS', 5 );
if ( ! defined( 'RP_GRAPH_MAIL_TENANT_ID' ) ) {
	define( 'RP_GRAPH_MAIL_TENANT_ID', '' );
}
if ( ! defined( 'RP_GRAPH_MAIL_CLIENT_ID' ) ) {
	define( 'RP_GRAPH_MAIL_CLIENT_ID', '' );
}
if ( ! defined( 'RP_GRAPH_MAIL_CLIENT_SECRET' ) ) {
	define( 'RP_GRAPH_MAIL_CLIENT_SECRET', '' );
}
if ( ! defined( 'RP_GRAPH_MAIL_SENDER' ) ) {
	define( 'RP_GRAPH_MAIL_SENDER', 'website.notifications@accord.org.ph' );
}

require_once RP_RESOURCE_HUB_PATH . 'includes/opportunities.php';
require_once RP_RESOURCE_HUB_PATH . 'includes/gallery.php';

function rp_resource_hub_register_post_types() {
	$shared_args = array(
		'public'              => true,
		'show_in_rest'        => true,
		'has_archive'         => true,
		'menu_position'       => 22,
		'supports'            => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'revisions', 'author' ),
		'map_meta_cap'        => true,
		'show_in_nav_menus'   => true,
		'exclude_from_search' => false,
	);

	register_post_type(
		'accord_library',
		array_merge(
			$shared_args,
			array(
				'labels'          => array(
					'name'               => __( 'ACCORD Knowledge Products', 'rp-resource-hub' ),
					'singular_name'      => __( 'ACCORD Knowledge Product', 'rp-resource-hub' ),
					'add_new_item'       => __( 'Add Knowledge Product', 'rp-resource-hub' ),
					'edit_item'          => __( 'Edit Knowledge Product', 'rp-resource-hub' ),
					'new_item'           => __( 'New Knowledge Product', 'rp-resource-hub' ),
					'view_item'          => __( 'View Knowledge Product', 'rp-resource-hub' ),
					'search_items'       => __( 'Search Knowledge Products', 'rp-resource-hub' ),
					'not_found'          => __( 'No knowledge products found.', 'rp-resource-hub' ),
					'menu_name'          => __( 'ACCORD Library', 'rp-resource-hub' ),
				),
				'menu_icon'       => 'dashicons-welcome-learn-more',
				'rewrite'         => array( 'slug' => 'accord-library' ),
				'capability_type' => array( 'accord_product', 'accord_products' ),
			)
		)
	);

	register_post_type(
		'partner_resources',
		array_merge(
			$shared_args,
			array(
				'labels'          => array(
					'name'               => __( 'Partner Resources', 'rp-resource-hub' ),
					'singular_name'      => __( 'Partner Resource', 'rp-resource-hub' ),
					'add_new_item'       => __( 'Add Partner Resource', 'rp-resource-hub' ),
					'edit_item'          => __( 'Edit Partner Resource', 'rp-resource-hub' ),
					'new_item'           => __( 'New Partner Resource', 'rp-resource-hub' ),
					'view_item'          => __( 'View Partner Resource', 'rp-resource-hub' ),
					'search_items'       => __( 'Search Partner Resources', 'rp-resource-hub' ),
					'not_found'          => __( 'No partner resources found.', 'rp-resource-hub' ),
					'menu_name'          => __( 'Partner Resources', 'rp-resource-hub' ),
				),
				'menu_icon'       => 'dashicons-groups',
				'rewrite'         => array( 'slug' => 'partner-resources' ),
				'capability_type' => array( 'partner_resource', 'partner_resources' ),
			)
		)
	);

	register_post_type(
		'rp_sitrep',
		array_merge(
			$shared_args,
			array(
				'labels'          => array(
					'name'               => __( 'Situation Reports', 'rp-resource-hub' ),
					'singular_name'      => __( 'Situation Report', 'rp-resource-hub' ),
					'add_new_item'       => __( 'Add Situation Report', 'rp-resource-hub' ),
					'edit_item'          => __( 'Edit Situation Report', 'rp-resource-hub' ),
					'new_item'           => __( 'New Situation Report', 'rp-resource-hub' ),
					'view_item'          => __( 'View Situation Report', 'rp-resource-hub' ),
					'search_items'       => __( 'Search Situation Reports', 'rp-resource-hub' ),
					'not_found'          => __( 'No situation reports found.', 'rp-resource-hub' ),
					'menu_name'          => __( 'Situation Reports', 'rp-resource-hub' ),
				),
				'menu_icon'       => 'dashicons-warning',
				'rewrite'         => array( 'slug' => 'situation-reports' ),
				'capability_type' => array( 'rp_sitrep', 'rp_sitreps' ),
			)
		)
	);

	register_post_type(
		'rp_incident',
		array_merge(
			$shared_args,
			array(
				'labels'          => array(
					'name'               => __( 'Crisis Incidents', 'rp-resource-hub' ),
					'singular_name'      => __( 'Crisis Incident', 'rp-resource-hub' ),
					'add_new_item'       => __( 'Add Crisis Incident', 'rp-resource-hub' ),
					'edit_item'          => __( 'Edit Crisis Incident', 'rp-resource-hub' ),
					'new_item'           => __( 'New Crisis Incident', 'rp-resource-hub' ),
					'view_item'          => __( 'View Crisis Incident', 'rp-resource-hub' ),
					'search_items'       => __( 'Search Crisis Incidents', 'rp-resource-hub' ),
					'not_found'          => __( 'No crisis incidents found.', 'rp-resource-hub' ),
					'menu_name'          => __( 'Crisis Incidents', 'rp-resource-hub' ),
				),
				'menu_icon'       => 'dashicons-location-alt',
				'rewrite'         => array( 'slug' => 'incident' ),
				'capability_type' => 'post',
			)
		)
	);
}
add_action( 'init', 'rp_resource_hub_register_post_types' );

function rp_resource_hub_register_taxonomies() {
	$taxonomies = array(
		'resource_category'  => array(
			'singular' => __( 'Resource Category', 'rp-resource-hub' ),
			'plural'   => __( 'Resource Categories', 'rp-resource-hub' ),
			'slug'     => 'resource-category',
		),
		'hazard_type'        => array(
			'singular' => __( 'Hazard Type', 'rp-resource-hub' ),
			'plural'   => __( 'Hazard Types', 'rp-resource-hub' ),
			'slug'     => 'hazard-type',
		),
		'target_audience'    => array(
			'singular' => __( 'Target Audience', 'rp-resource-hub' ),
			'plural'   => __( 'Target Audiences', 'rp-resource-hub' ),
			'slug'     => 'target-audience',
		),
		'contributing_org'   => array(
			'singular' => __( 'Contributing Organization', 'rp-resource-hub' ),
			'plural'   => __( 'Contributing Organizations', 'rp-resource-hub' ),
			'slug'     => 'contributing-organization',
		),
		'resource_visibility' => array(
			'singular' => __( 'Resource Visibility', 'rp-resource-hub' ),
			'plural'   => __( 'Resource Visibility', 'rp-resource-hub' ),
			'slug'     => 'resource-visibility',
		),
		'resource_format'    => array(
			'singular' => __( 'Resource Format', 'rp-resource-hub' ),
			'plural'   => __( 'Resource Formats', 'rp-resource-hub' ),
			'slug'     => 'resource-format',
		),
	);

	foreach ( $taxonomies as $taxonomy => $data ) {
		register_taxonomy(
			$taxonomy,
			array( 'accord_library', 'partner_resources', 'rp_sitrep', 'rp_incident' ),
			array(
				'labels'            => array(
					'name'          => $data['plural'],
					'singular_name' => $data['singular'],
					'search_items'  => sprintf( __( 'Search %s', 'rp-resource-hub' ), $data['plural'] ),
					'all_items'     => sprintf( __( 'All %s', 'rp-resource-hub' ), $data['plural'] ),
					'edit_item'     => sprintf( __( 'Edit %s', 'rp-resource-hub' ), $data['singular'] ),
					'add_new_item'  => sprintf( __( 'Add New %s', 'rp-resource-hub' ), $data['singular'] ),
				),
				'hierarchical'      => true,
				'public'            => true,
				'show_admin_column' => true,
				'show_in_rest'      => true,
				'rewrite'           => array( 'slug' => $data['slug'] ),
			)
		);
	}
}
add_action( 'init', 'rp_resource_hub_register_taxonomies' );

function rp_resource_hub_admin_caps() {
	return array(
		'edit_accord_product',
		'read_accord_product',
		'delete_accord_product',
		'edit_accord_products',
		'edit_others_accord_products',
		'publish_accord_products',
		'read_private_accord_products',
		'delete_accord_products',
		'delete_private_accord_products',
		'delete_published_accord_products',
		'delete_others_accord_products',
		'edit_private_accord_products',
		'edit_published_accord_products',
		'edit_partner_resource',
		'read_partner_resource',
		'delete_partner_resource',
		'edit_partner_resources',
		'edit_others_partner_resources',
		'publish_partner_resources',
		'read_private_partner_resources',
		'delete_partner_resources',
		'delete_private_partner_resources',
		'delete_published_partner_resources',
		'delete_others_partner_resources',
		'edit_private_partner_resources',
		'edit_published_partner_resources',
		'read_member_resources',
		'edit_rp_sitrep',
		'read_rp_sitrep',
		'delete_rp_sitrep',
		'edit_rp_sitreps',
		'edit_others_rp_sitreps',
		'publish_rp_sitreps',
		'read_private_rp_sitreps',
		'delete_rp_sitreps',
		'delete_private_rp_sitreps',
		'delete_published_rp_sitreps',
		'delete_others_rp_sitreps',
		'edit_private_rp_sitreps',
		'edit_published_rp_sitreps',
		'manage_tinig_cases',
	);
}

function rp_resource_hub_partner_caps() {
	return array(
		'read'                             => true,
		'upload_files'                     => true,
		'edit_partner_resource'            => true,
		'read_partner_resource'            => true,
		'delete_partner_resource'          => true,
		'edit_partner_resources'           => true,
		'edit_published_partner_resources' => true,
		'delete_partner_resources'         => true,
		'delete_published_partner_resources' => true,
		'read_member_resources'            => true,
		'edit_rp_sitrep'                   => true,
		'read_rp_sitrep'                   => true,
		'delete_rp_sitrep'                 => true,
		'edit_rp_sitreps'                  => true,
		'edit_published_rp_sitreps'        => true,
		'delete_rp_sitreps'                => true,
		'delete_published_rp_sitreps'      => true,
	);
}

function rp_resource_hub_apply_roles_and_caps() {
	$partner = get_role( 'partner_contributor' );
	if ( ! $partner ) {
		add_role( 'partner_contributor', __( 'Partner Contributor', 'rp-resource-hub' ), rp_resource_hub_partner_caps() );
		$partner = get_role( 'partner_contributor' );
	}

	if ( $partner ) {
		foreach ( rp_resource_hub_partner_caps() as $cap => $grant ) {
			$partner->add_cap( $cap, $grant );
		}
	}

	$subscriber = get_role( 'hub_subscriber' );
	if ( ! $subscriber ) {
		add_role(
			'hub_subscriber',
			__( 'Hub Subscriber', 'rp-resource-hub' ),
			array(
				'read'                  => true,
				'read_member_resources' => true,
			)
		);
		$subscriber = get_role( 'hub_subscriber' );
	}

	if ( $subscriber ) {
		$subscriber->add_cap( 'read', true );
		$subscriber->add_cap( 'read_member_resources', true );
	}

	foreach ( array( 'administrator', 'editor' ) as $role_name ) {
		$role = get_role( $role_name );
		if ( ! $role ) {
			continue;
		}

		foreach ( rp_resource_hub_admin_caps() as $cap ) {
			$role->add_cap( $cap );
		}
	}
}

function rp_resource_hub_create_db_table() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'rp_sitrep_locations';
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
		sitrep_id BIGINT(20) UNSIGNED NOT NULL,
		incident_id BIGINT(20) UNSIGNED NOT NULL,
		region VARCHAR(100) NOT NULL,
		province VARCHAR(100) NOT NULL,
		municipality VARCHAR(100) NOT NULL,
		barangay VARCHAR(100) NOT NULL,
		affected_barangays INT DEFAULT 0,
		households INT DEFAULT 0,
		individuals INT DEFAULT 0,
		displaced_inside INT DEFAULT 0,
		displaced_outside INT DEFAULT 0,
		displaced_total INT DEFAULT 0,
		displaced_households INT DEFAULT 0,
		data_source VARCHAR(255) DEFAULT '',
		conflict_mode VARCHAR(20) DEFAULT 'add',
		record_status VARCHAR(20) DEFAULT 'pending',
		PRIMARY KEY (id),
		KEY sitrep_id (sitrep_id),
		KEY incident_id (incident_id),
		KEY loc_key (province, municipality, barangay)
	) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );
}

function rp_resource_hub_create_analytics_tables() {
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();

	// 1. Views Table
	$views_table = $wpdb->prefix . 'rp_analytics_views';
	$sql_views = "CREATE TABLE $views_table (
		id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
		post_id BIGINT(20) UNSIGNED DEFAULT NULL,
		user_id BIGINT(20) UNSIGNED DEFAULT NULL,
		ip_address VARCHAR(45) NOT NULL,
		user_agent TEXT DEFAULT NULL,
		visitor_id VARCHAR(64) DEFAULT '',
		session_id VARCHAR(64) DEFAULT '',
		is_new_visitor TINYINT(1) NOT NULL DEFAULT 0,
		country_code CHAR(2) DEFAULT 'ZZ',
		device_type VARCHAR(20) DEFAULT 'unknown',
		referrer_url TEXT DEFAULT NULL,
		traffic_source VARCHAR(100) DEFAULT '',
		traffic_medium VARCHAR(100) DEFAULT '',
		campaign VARCHAR(190) DEFAULT '',
		created_at DATETIME NOT NULL,
		PRIMARY KEY (id),
		KEY post_id (post_id),
		KEY user_id (user_id),
		KEY visitor_id (visitor_id),
		KEY session_id (session_id),
		KEY country_code (country_code),
		KEY traffic_source (traffic_source),
		KEY created_at (created_at)
	) $charset_collate;";

	// 2. Downloads Table
	$downloads_table = $wpdb->prefix . 'rp_analytics_downloads';
	$sql_downloads = "CREATE TABLE $downloads_table (
		id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
		post_id BIGINT(20) UNSIGNED NOT NULL,
		user_id BIGINT(20) UNSIGNED DEFAULT NULL,
		ip_address VARCHAR(45) NOT NULL,
		user_agent TEXT DEFAULT NULL,
		visitor_id VARCHAR(64) DEFAULT '',
		session_id VARCHAR(64) DEFAULT '',
		is_new_visitor TINYINT(1) NOT NULL DEFAULT 0,
		country_code CHAR(2) DEFAULT 'ZZ',
		device_type VARCHAR(20) DEFAULT 'unknown',
		referrer_url TEXT DEFAULT NULL,
		traffic_source VARCHAR(100) DEFAULT '',
		traffic_medium VARCHAR(100) DEFAULT '',
		campaign VARCHAR(190) DEFAULT '',
		created_at DATETIME NOT NULL,
		PRIMARY KEY (id),
		KEY post_id (post_id),
		KEY user_id (user_id),
		KEY visitor_id (visitor_id),
		KEY session_id (session_id),
		KEY country_code (country_code),
		KEY traffic_source (traffic_source),
		KEY created_at (created_at)
	) $charset_collate;";

	$searches_table = $wpdb->prefix . 'rp_analytics_searches';
	$sql_searches = "CREATE TABLE $searches_table (
		id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
		search_term VARCHAR(190) NOT NULL,
		results_count BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
		visitor_id VARCHAR(64) DEFAULT '',
		session_id VARCHAR(64) DEFAULT '',
		country_code CHAR(2) DEFAULT 'ZZ',
		device_type VARCHAR(20) DEFAULT 'unknown',
		created_at DATETIME NOT NULL,
		PRIMARY KEY (id),
		KEY search_term (search_term),
		KEY session_id (session_id),
		KEY created_at (created_at)
	) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql_views );
	dbDelta( $sql_downloads );
	dbDelta( $sql_searches );
}

function rp_resource_hub_create_tinig_tables() {
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$cases_table     = $wpdb->prefix . 'rp_tinig_cases';
	$notes_table     = $wpdb->prefix . 'rp_tinig_case_notes';

	$sql_cases = "CREATE TABLE $cases_table (
		id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
		reference_code VARCHAR(32) NOT NULL DEFAULT '',
		submitted_at DATETIME NOT NULL,
		updated_at DATETIME NOT NULL,
		status VARCHAR(30) NOT NULL DEFAULT 'new',
		feedback_type VARCHAR(60) NOT NULL DEFAULT '',
		urgency VARCHAR(30) NOT NULL DEFAULT 'normal',
		is_sensitive TINYINT(1) NOT NULL DEFAULT 0,
		is_anonymous TINYINT(1) NOT NULL DEFAULT 0,
		safe_to_contact TINYINT(1) NOT NULL DEFAULT 0,
		contact_name VARCHAR(190) DEFAULT '',
		contact_email VARCHAR(190) DEFAULT '',
		contact_phone VARCHAR(80) DEFAULT '',
		preferred_contact VARCHAR(30) DEFAULT '',
		location VARCHAR(190) DEFAULT '',
		program VARCHAR(190) DEFAULT '',
		subject VARCHAR(190) DEFAULT '',
		message LONGTEXT NOT NULL,
		attachment_ids TEXT DEFAULT NULL,
		consent TINYINT(1) NOT NULL DEFAULT 0,
		ip_address VARCHAR(45) DEFAULT '',
		user_agent TEXT DEFAULT NULL,
		last_updated_by BIGINT(20) UNSIGNED DEFAULT NULL,
		resolution_summary TEXT DEFAULT NULL,
		PRIMARY KEY (id),
		UNIQUE KEY reference_code (reference_code),
		KEY status (status),
		KEY feedback_type (feedback_type),
		KEY submitted_at (submitted_at)
	) $charset_collate;";

	$sql_notes = "CREATE TABLE $notes_table (
		id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
		case_id BIGINT(20) UNSIGNED NOT NULL,
		user_id BIGINT(20) UNSIGNED DEFAULT NULL,
		note_type VARCHAR(30) NOT NULL DEFAULT 'internal',
		old_status VARCHAR(30) DEFAULT '',
		new_status VARCHAR(30) DEFAULT '',
		note LONGTEXT NOT NULL,
		created_at DATETIME NOT NULL,
		PRIMARY KEY (id),
		KEY case_id (case_id),
		KEY user_id (user_id),
		KEY created_at (created_at)
	) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql_cases );
	dbDelta( $sql_notes );
}

function rp_resource_hub_maybe_upgrade() {
	$tinig_page           = get_page_by_path( 'tinig' );
	$tinig_dashboard_page = get_page_by_path( 'tinig-dashboard' );

	// Fallback: Ensure required pages are created if missing
	if ( ! get_page_by_path( 'my-contributions' ) || ! get_page_by_path( 'news-stories' ) || ! get_page_by_path( 'submit-post' ) || ! $tinig_page || false === strpos( $tinig_page->post_content, '[rp_tinig_form]' ) || ! $tinig_dashboard_page || false === strpos( $tinig_dashboard_page->post_content, '[rp_tinig_dashboard]' ) ) {
		rp_resource_hub_create_pages();
		flush_rewrite_rules();
	}

	if ( RP_RESOURCE_HUB_VERSION === get_option( 'rp_resource_hub_version' ) ) {
		return;
	}

	rp_resource_hub_create_db_table();
	rp_resource_hub_create_analytics_tables();
	rp_resource_hub_create_tinig_tables();
	rp_resource_hub_apply_roles_and_caps();
	rp_resource_hub_seed_terms();
	rp_resource_hub_create_pages();
	flush_rewrite_rules();
	update_option( 'rp_resource_hub_version', RP_RESOURCE_HUB_VERSION );
}
add_action( 'init', 'rp_resource_hub_maybe_upgrade' );

function rp_resource_hub_activate() {
	rp_resource_hub_register_post_types();
	rp_resource_hub_register_taxonomies();
	rp_resource_hub_create_db_table();
	rp_resource_hub_create_analytics_tables();
	rp_resource_hub_create_tinig_tables();
	rp_resource_hub_apply_roles_and_caps();
	rp_resource_hub_seed_terms();
	rp_resource_hub_create_pages();
	flush_rewrite_rules();
	update_option( 'rp_resource_hub_version', RP_RESOURCE_HUB_VERSION );
}
register_activation_hook( __FILE__, 'rp_resource_hub_activate' );

function rp_resource_hub_get_ip() {
	$ip = '0.0.0.0';
	if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} elseif ( ! empty( $_SERVER['REMOTE_ADDR'] ) ) {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	if ( strpos( $ip, ',' ) !== false ) {
		$ips = explode( ',', $ip );
		$ip = trim( $ips[0] );
	}
	return sanitize_text_field( $ip );
}

function rp_resource_hub_set_analytics_cookie( $name, $value, $expires ) {
	setcookie( $name, $value, array(
		'expires'  => $expires,
		'path'     => COOKIEPATH ? COOKIEPATH : '/',
		'domain'   => COOKIE_DOMAIN,
		'secure'   => is_ssl(),
		'httponly' => true,
		'samesite' => 'Lax',
	) );
	$_COOKIE[ $name ] = $value;
}

function rp_resource_hub_get_country_code() {
	$headers = array( 'HTTP_CF_IPCOUNTRY', 'HTTP_X_COUNTRY_CODE', 'GEOIP_COUNTRY_CODE' );
	foreach ( $headers as $header ) {
		if ( ! empty( $_SERVER[ $header ] ) ) {
			$country = strtoupper( sanitize_text_field( wp_unslash( $_SERVER[ $header ] ) ) );
			if ( preg_match( '/^[A-Z]{2}$/', $country ) && ! in_array( $country, array( 'XX', 'T1' ), true ) ) {
				return $country;
			}
		}
	}
	return 'ZZ';
}

function rp_resource_hub_get_device_type( $user_agent ) {
	if ( preg_match( '/bot|crawl|spider|slurp|headless|lighthouse|monitor|uptime/i', $user_agent ) ) {
		return 'bot';
	}
	if ( preg_match( '/ipad|tablet|kindle|silk/i', $user_agent ) ) {
		return 'tablet';
	}
	if ( preg_match( '/mobile|iphone|ipod|android/i', $user_agent ) ) {
		return 'mobile';
	}
	return $user_agent ? 'desktop' : 'unknown';
}

function rp_resource_hub_get_acquisition( $persist = false ) {
	$source   = isset( $_GET['utm_source'] ) ? sanitize_text_field( wp_unslash( $_GET['utm_source'] ) ) : '';
	$medium   = isset( $_GET['utm_medium'] ) ? sanitize_text_field( wp_unslash( $_GET['utm_medium'] ) ) : '';
	$campaign = isset( $_GET['utm_campaign'] ) ? sanitize_text_field( wp_unslash( $_GET['utm_campaign'] ) ) : '';
	$has_utm  = (bool) ( $source || $medium || $campaign );
	$referrer = isset( $_SERVER['HTTP_REFERER'] ) ? esc_url_raw( wp_unslash( $_SERVER['HTTP_REFERER'] ) ) : '';

	if ( ! $source && ! empty( $_COOKIE['rp_analytics_source'] ) ) {
		$source   = sanitize_text_field( wp_unslash( $_COOKIE['rp_analytics_source'] ) );
		$medium   = isset( $_COOKIE['rp_analytics_medium'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['rp_analytics_medium'] ) ) : '';
		$campaign = isset( $_COOKIE['rp_analytics_campaign'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['rp_analytics_campaign'] ) ) : '';
	} elseif ( ! $source ) {
		$referrer_host = strtolower( (string) wp_parse_url( $referrer, PHP_URL_HOST ) );
		$site_host     = strtolower( (string) wp_parse_url( home_url(), PHP_URL_HOST ) );
		if ( ! $referrer_host || $referrer_host === $site_host ) {
			$source = 'direct';
			$medium = 'none';
		} elseif ( preg_match( '/(^|\.)(google|bing|yahoo|duckduckgo|ecosia)\./', $referrer_host, $match ) ) {
			$source = $match[2];
			$medium = 'organic_search';
		} elseif ( preg_match( '/(^|\.)(facebook|instagram|linkedin|x|twitter|youtube|tiktok)\./', $referrer_host, $match ) ) {
			$source = $match[2];
			$medium = 'social';
		} else {
			$source = $referrer_host;
			$medium = 'referral';
		}
	}

	if ( $persist || $has_utm ) {
		$expires = time() + ( 30 * MINUTE_IN_SECONDS );
		rp_resource_hub_set_analytics_cookie( 'rp_analytics_source', substr( $source, 0, 100 ), $expires );
		rp_resource_hub_set_analytics_cookie( 'rp_analytics_medium', substr( $medium, 0, 100 ), $expires );
		if ( $campaign ) {
			rp_resource_hub_set_analytics_cookie( 'rp_analytics_campaign', substr( $campaign, 0, 190 ), $expires );
		}
	}

	return array(
		'source'   => substr( $source, 0, 100 ),
		'medium'   => substr( $medium, 0, 100 ),
		'campaign' => substr( $campaign, 0, 190 ),
		'referrer' => substr( $referrer, 0, 1000 ),
	);
}

function rp_resource_hub_get_analytics_context() {
	static $context = null;
	if ( null !== $context ) {
		return $context;
	}

	$visitor_id = isset( $_COOKIE['rp_analytics_visitor'] ) && preg_match( '/^[a-f0-9]{32}$/', $_COOKIE['rp_analytics_visitor'] ) ? $_COOKIE['rp_analytics_visitor'] : '';
	$session_id = isset( $_COOKIE['rp_analytics_session'] ) && preg_match( '/^[a-f0-9]{32}$/', $_COOKIE['rp_analytics_session'] ) ? $_COOKIE['rp_analytics_session'] : '';
	$is_new     = ! $visitor_id;
	$is_new_session = ! $session_id;
	if ( ! $visitor_id ) {
		$visitor_id = bin2hex( random_bytes( 16 ) );
		rp_resource_hub_set_analytics_cookie( 'rp_analytics_visitor', $visitor_id, time() + YEAR_IN_SECONDS );
	}
	if ( ! $session_id ) {
		$session_id = bin2hex( random_bytes( 16 ) );
		rp_resource_hub_set_analytics_cookie( 'rp_analytics_session', $session_id, time() + ( 30 * MINUTE_IN_SECONDS ) );
	}

	$acquisition = rp_resource_hub_get_acquisition( $is_new_session );
	$user_agent  = isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : '';
	$context     = array_merge( $acquisition, array(
		'visitor_id'    => $visitor_id,
		'session_id'    => $session_id,
		'is_new_visitor'=> $is_new ? 1 : 0,
		'country_code'  => rp_resource_hub_get_country_code(),
		'device_type'   => rp_resource_hub_get_device_type( $user_agent ),
		'user_agent'    => $user_agent,
	) );

	return $context;
}

function rp_resource_hub_log_search( $search_term, $results_count ) {
	$search_term = trim( substr( sanitize_text_field( $search_term ), 0, 190 ) );
	if ( strlen( $search_term ) < 2 || ( isset( $_COOKIE['rp_cookie_consent'] ) && 'declined' === $_COOKIE['rp_cookie_consent'] ) || current_user_can( 'publish_posts' ) ) {
		return;
	}

	global $wpdb;
	$analytics = rp_resource_hub_get_analytics_context();
	$table     = $wpdb->prefix . 'rp_analytics_searches';
	$duplicate = $wpdb->get_var( $wpdb->prepare(
		"SELECT id FROM {$table} WHERE session_id = %s AND search_term = %s AND created_at >= DATE_SUB(NOW(), INTERVAL 10 MINUTE) LIMIT 1",
		$analytics['session_id'],
		$search_term
	) );
	if ( $duplicate ) {
		return;
	}

	$wpdb->insert( $table, array(
		'search_term'  => $search_term,
		'results_count'=> absint( $results_count ),
		'visitor_id'   => $analytics['visitor_id'],
		'session_id'   => $analytics['session_id'],
		'country_code' => $analytics['country_code'],
		'device_type'  => $analytics['device_type'],
		'created_at'   => current_time( 'mysql' ),
	), array( '%s', '%d', '%s', '%s', '%s', '%s', '%s' ) );
}

function rp_resource_hub_track_page_view() {
	// Check cookie opt-out choice
	if ( isset( $_COOKIE['rp_cookie_consent'] ) && 'declined' === $_COOKIE['rp_cookie_consent'] ) {
		return;
	}

	if ( is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
		return;
	}

	if ( current_user_can( 'publish_posts' ) ) {
		return;
	}

	global $post;
	$post_id = 0;

	if ( is_front_page() || is_home() ) {
		$post_id = 0;
	} elseif ( is_singular() && isset( $post->ID ) ) {
		$allowed_types = array( 'page', 'post', 'partner_resources', 'accord_library', 'rp_sitrep', 'rp_incident' );
		if ( ! in_array( $post->post_type, $allowed_types, true ) ) {
			return;
		}
		$post_id = $post->ID;
	} elseif ( is_post_type_archive( array( 'partner_resources', 'accord_library', 'rp_sitrep' ) ) ) {
		$post_id = -1;
	} else {
		return;
	}

	global $wpdb;
	$table_name = $wpdb->prefix . 'rp_analytics_views';
	$ip_address = rp_resource_hub_get_ip();
	$analytics  = rp_resource_hub_get_analytics_context();
	$user_agent = $analytics['user_agent'];
	$user_id    = get_current_user_id() ? get_current_user_id() : null;

	$wpdb->insert(
		$table_name,
		array(
			'post_id'    => $post_id ? $post_id : null,
			'user_id'    => $user_id,
			'ip_address' => $ip_address,
			'user_agent' => $user_agent,
			'visitor_id' => $analytics['visitor_id'],
			'session_id' => $analytics['session_id'],
			'is_new_visitor' => $analytics['is_new_visitor'],
			'country_code' => $analytics['country_code'],
			'device_type' => $analytics['device_type'],
			'referrer_url' => $analytics['referrer'],
			'traffic_source' => $analytics['source'],
			'traffic_medium' => $analytics['medium'],
			'campaign' => $analytics['campaign'],
			'created_at' => current_time( 'mysql' ),
		),
		array( '%d', '%d', '%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s' )
	);
}
add_action( 'template_redirect', 'rp_resource_hub_track_page_view' );

function rp_resource_hub_log_download( $post_id ) {
	// Check cookie opt-out choice
	if ( isset( $_COOKIE['rp_cookie_consent'] ) && 'declined' === $_COOKIE['rp_cookie_consent'] ) {
		return;
	}

	global $wpdb;
	$downloads_table = $wpdb->prefix . 'rp_analytics_downloads';
	$ip_address = rp_resource_hub_get_ip();
	$analytics  = rp_resource_hub_get_analytics_context();
	$user_agent = $analytics['user_agent'];
	$user_id    = get_current_user_id() ? get_current_user_id() : null;

	$wpdb->insert(
		$downloads_table,
		array(
			'post_id'    => $post_id,
			'user_id'    => $user_id,
			'ip_address' => $ip_address,
			'user_agent' => $user_agent,
			'visitor_id' => $analytics['visitor_id'],
			'session_id' => $analytics['session_id'],
			'is_new_visitor' => $analytics['is_new_visitor'],
			'country_code' => $analytics['country_code'],
			'device_type' => $analytics['device_type'],
			'referrer_url' => $analytics['referrer'],
			'traffic_source' => $analytics['source'],
			'traffic_medium' => $analytics['medium'],
			'campaign' => $analytics['campaign'],
			'created_at' => current_time( 'mysql' ),
		),
		array( '%d', '%d', '%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s' )
	);
}

function rp_resource_hub_deactivate() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'rp_resource_hub_deactivate' );

function rp_resource_hub_seed_terms() {
	$terms = array(
		'resource_category'   => array( 'Training Manuals', 'Information Flyers', 'Publications', 'Tools' ),
		'hazard_type'         => array( 'Typhoons', 'Earthquakes', 'Floods', 'Volcanic Eruption', 'Conflict' ),
		'target_audience'     => array( 'LGUs', 'CSOs', 'Public', 'Responders' ),
		'resource_visibility' => array( 'Public', 'Member Only' ),
		'contributing_org'    => array( 'ACCORD', 'Partner Organizations' ),
		'resource_format'     => array( 'Standard Document', 'Web Application', 'Data Archive' ),
	);

	foreach ( $terms as $taxonomy => $names ) {
		foreach ( $names as $name ) {
			if ( ! term_exists( $name, $taxonomy ) ) {
				wp_insert_term( $name, $taxonomy );
			}
		}
	}
}

/**
 * Migrate contributing organizations: remove CARE Philippines and move any of its posts to Partner Organizations
 */
function rp_resource_hub_migrate_contributing_orgs() {
	if ( ! taxonomy_exists( 'contributing_org' ) ) {
		return;
	}

	$target_term = get_term_by( 'name', 'Partner Organizations', 'contributing_org' );
	if ( ! $target_term ) {
		$new_term = wp_insert_term( 'Partner Organizations', 'contributing_org' );
		if ( ! is_wp_error( $new_term ) ) {
			$target_term_id = $new_term['term_id'];
		} else {
			$target_term_id = 0;
		}
	} else {
		$target_term_id = $target_term->term_id;
	}

	$old_term = get_term_by( 'name', 'CARE Philippines', 'contributing_org' );
	if ( $old_term && $target_term_id ) {
		$posts = get_posts( array(
			'post_type'   => array( 'accord_library', 'partner_resources' ),
			'numberposts' => -1,
			'tax_query'   => array(
				array(
					'taxonomy' => 'contributing_org',
					'field'    => 'term_id',
					'terms'    => $old_term->term_id,
				),
			),
		) );

		foreach ( $posts as $p ) {
			wp_set_object_terms( $p->ID, array( $target_term_id ), 'contributing_org', true );
			wp_remove_object_terms( $p->ID, $old_term->term_id, 'contributing_org' );
		}

		wp_delete_term( $old_term->term_id, 'contributing_org' );
	}
}
add_action( 'init', 'rp_resource_hub_migrate_contributing_orgs', 99 );


function rp_resource_hub_create_pages() {
	$pages = array(
		'resource-hub'    => array(
			'title'   => __( 'Resource Hub', 'rp-resource-hub' ),
			'content' => '[rp_resource_catalog limit="12" filters="true"]',
		),
		'submit-resource' => array(
			'title'   => __( 'Submit Resource', 'rp-resource-hub' ),
			'content' => '[rp_partner_upload_form]',
		),
		'resource-submitted' => array(
			'title'   => __( 'Resource Submitted', 'rp-resource-hub' ),
			'content' => '<p>' . __( 'Thank you. Your submission was sent for administrator review.', 'rp-resource-hub' ) . '</p><p><a class="rp-button" href="/resource-hub/">' . __( 'Return to Resource Hub', 'rp-resource-hub' ) . '</a></p>',
		),
		'portal-entry'    => array(
			'title'   => __( 'Portal Entry', 'rp-resource-hub' ),
			'content' => '',
		),
		'moderation-dashboard' => array(
			'title'   => __( 'Moderation Dashboard', 'rp-resource-hub' ),
			'content' => '',
		),
		'submit-sitrep'   => array(
			'title'   => __( 'Submit Situation Report', 'rp-resource-hub' ),
			'content' => '[rp_submit_sitrep_form]',
		),
		'sitrep-dashboard' => array(
			'title'   => __( 'Situation Report Dashboard', 'rp-resource-hub' ),
			'content' => '',
		),
		'my-contributions' => array(
			'title'   => __( 'My Contributions', 'rp-resource-hub' ),
			'content' => '[rp_my_contributions]',
		),
		'news-stories' => array(
			'title'   => __( 'News & Stories', 'rp-resource-hub' ),
			'content' => '[rp_news_catalog]',
		),
		'photo-gallery' => array(
			'title'   => __( 'Photo Gallery', 'rp-resource-hub' ),
			'content' => '[rp_photo_gallery]',
		),
		'submit-photo' => array(
			'title'   => __( 'Submit Photo', 'rp-resource-hub' ),
			'content' => '[rp_photo_upload_form]',
		),
		'submit-post' => array(
			'title'   => __( 'Submit Post or Story', 'rp-resource-hub' ),
			'content' => '[rp_submit_post_form]',
		),
		'tinig' => array(
			'title'   => __( 'Tinig', 'rp-resource-hub' ),
			'content' => '[rp_tinig_form]',
		),
		'tinig-dashboard' => array(
			'title'   => __( 'Tinig Dashboard', 'rp-resource-hub' ),
			'content' => '[rp_tinig_dashboard]',
		),
	);

	foreach ( $pages as $slug => $page ) {
		if ( get_page_by_path( $slug ) ) {
			$existing_page = get_page_by_path( $slug );
			if ( in_array( $slug, array( 'tinig', 'tinig-dashboard' ), true ) && false === strpos( $existing_page->post_content, $page['content'] ) ) {
				wp_update_post(
					array(
						'ID'           => $existing_page->ID,
						'post_content' => $page['content'],
					)
				);
			}
			continue;
		}

		$post_id = wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_name'    => $slug,
				'post_title'   => $page['title'],
				'post_content' => $page['content'],
			)
		);

		if ( $post_id && ! is_wp_error( $post_id ) ) {
			if ( 'portal-entry' === $slug ) {
				update_post_meta( $post_id, '_wp_page_template', 'template-auth.php' );
			} elseif ( 'moderation-dashboard' === $slug ) {
				update_post_meta( $post_id, '_wp_page_template', 'template-moderation.php' );
			} elseif ( 'sitrep-dashboard' === $slug ) {
				update_post_meta( $post_id, '_wp_page_template', 'template-sitrep-dashboard.php' );
			} elseif ( 'news-stories' === $slug ) {
				update_post_meta( $post_id, '_wp_page_template', 'template-news-stories.php' );
			} elseif ( 'submit-post' === $slug ) {
				update_post_meta( $post_id, '_wp_page_template', 'template-submit-post.php' );
			}
		}
	}
	rp_resource_hub_setup_secure_directory();
}

function rp_resource_hub_setup_secure_directory() {
	$upload_dir = wp_get_upload_dir();
	$secure_dir = $upload_dir['basedir'] . '/rp-secure';
	if ( ! file_exists( $secure_dir ) ) {
		wp_mkdir_p( $secure_dir );
	}
	
	$htaccess_path = $secure_dir . '/.htaccess';
	if ( ! file_exists( $htaccess_path ) ) {
		$rules = "<IfModule mod_rewrite.c>\nRewriteEngine On\nRewriteRule ^(.*)$ ../../../index.php?rp_secure_file=$1 [QSA,L]\n</IfModule>";
		file_put_contents( $htaccess_path, $rules );
	}
}

function rp_resource_hub_enqueue_assets() {
	wp_enqueue_style(
		'rp-resource-hub',
		RP_RESOURCE_HUB_URL . 'assets/resource-hub.css',
		array(),
		RP_RESOURCE_HUB_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'rp_resource_hub_enqueue_assets' );

function rp_resource_hub_user_can_upload() {
	return is_user_logged_in() && current_user_can( 'upload_files' ) && ( current_user_can( 'edit_partner_resources' ) || current_user_can( 'manage_options' ) );
}

function rp_resource_hub_redirect_backend() {
	if ( ! is_admin() || wp_doing_ajax() || ! is_user_logged_in() ) {
		return;
	}

	global $pagenow;
	if ( 'admin-post.php' === $pagenow ) {
		return;
	}

	if ( current_user_can( 'manage_options' ) || current_user_can( 'edit_partner_resources' ) ) {
		return;
	}

	wp_safe_redirect( home_url( '/' ) );
	exit;
}
add_action( 'admin_init', 'rp_resource_hub_redirect_backend' );

function rp_resource_hub_limit_partner_admin_list( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}

	$post_type = $query->get( 'post_type' );
	if ( 'partner_resources' === $post_type && ! current_user_can( 'edit_others_partner_resources' ) ) {
		$query->set( 'author', get_current_user_id() );
	} elseif ( 'rp_sitrep' === $post_type && ! current_user_can( 'edit_others_rp_sitreps' ) ) {
		$query->set( 'author', get_current_user_id() );
	}
}
add_action( 'pre_get_posts', 'rp_resource_hub_limit_partner_admin_list' );

function rp_resource_hub_remove_partner_admin_menus() {
	if ( current_user_can( 'manage_options' ) || ! current_user_can( 'edit_partner_resources' ) ) {
		return;
	}

	remove_menu_page( 'tools.php' );
	remove_menu_page( 'edit-comments.php' );
	remove_menu_page( 'profile.php' );
}
add_action( 'admin_menu', 'rp_resource_hub_remove_partner_admin_menus', 999 );

function rp_resource_hub_hide_admin_bar( $show ) {
	if ( current_user_can( 'manage_options' ) || current_user_can( 'edit_partner_resources' ) ) {
		return $show;
	}

	return false;
}
add_filter( 'show_admin_bar', 'rp_resource_hub_hide_admin_bar' );

function rp_resource_hub_allowed_mimes() {
	return array(
		'pdf'  => 'application/pdf',
		'doc'  => 'application/msword',
		'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		'xls'  => 'application/vnd.ms-excel',
		'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
		'html' => 'text/html',
		'zip'  => 'application/zip',
		'jpg'  => 'image/jpeg',
		'jpeg' => 'image/jpeg',
		'png'  => 'image/png',
	);
}

function rp_resource_hub_extend_upload_mimes( $mimes ) {
	if ( ! rp_resource_hub_user_can_upload() ) {
		return $mimes;
	}

	return array_merge( $mimes, rp_resource_hub_allowed_mimes() );
}
add_filter( 'upload_mimes', 'rp_resource_hub_extend_upload_mimes' );

function rp_resource_hub_format_bytes( $bytes ) {
	return size_format( $bytes, 1 );
}

function rp_resource_hub_success_url( $post_id = 0 ) {
	$page = get_page_by_path( 'resource-submitted' );
	$url  = $page ? get_permalink( $page ) : home_url( '/submit-resource/' );

	if ( $post_id ) {
		$url = add_query_arg( 'resource_id', absint( $post_id ), $url );
	}

	return $url;
}

function rp_resource_hub_store_upload_notice( $type, $message ) {
	$key = wp_generate_uuid4();
	set_transient(
		'rp_resource_upload_notice_' . $key,
		array(
			'type'    => sanitize_key( $type ),
			'message' => wp_strip_all_tags( $message ),
		),
		10 * MINUTE_IN_SECONDS
	);

	return $key;
}

function rp_resource_hub_get_upload_notice() {
	if ( empty( $_GET['rp_upload_notice'] ) ) {
		return null;
	}

	$key    = sanitize_key( wp_unslash( $_GET['rp_upload_notice'] ) );
	$notice = get_transient( 'rp_resource_upload_notice_' . $key );

	if ( $notice ) {
		delete_transient( 'rp_resource_upload_notice_' . $key );
	}

	return is_array( $notice ) ? $notice : null;
}

function rp_resource_hub_term_options( $taxonomy, $selected = array() ) {
	$terms = get_terms(
		array(
			'taxonomy'   => $taxonomy,
			'hide_empty' => false,
		)
	);

	if ( is_wp_error( $terms ) || empty( $terms ) ) {
		return;
	}

	$type = ( 'resource_visibility' === $taxonomy ) ? 'radio' : 'checkbox';

	foreach ( $terms as $term ) {
		printf(
			'<label><input type="%1$s" name="rp_terms[%2$s][]" value="%3$d" %4$s> %5$s</label>',
			esc_attr( $type ),
			esc_attr( $taxonomy ),
			absint( $term->term_id ),
			checked( in_array( (int) $term->term_id, array_map( 'intval', $selected ), true ), true, false ),
			esc_html( $term->name )
		);
	}
}

function rp_resource_hub_process_upload() {
	if ( 'POST' !== $_SERVER['REQUEST_METHOD'] || empty( $_POST['rp_resource_upload_nonce'] ) ) {
		return null;
	}

	if ( ! rp_resource_hub_user_can_upload() ) {
		return new WP_Error( 'rp_forbidden', __( 'You do not have permission to submit resources.', 'rp-resource-hub' ) );
	}

	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['rp_resource_upload_nonce'] ) ), 'rp_resource_upload' ) ) {
		return new WP_Error( 'rp_nonce', __( 'Security check failed. Please refresh and try again.', 'rp-resource-hub' ) );
	}

	if ( ! isset( $_POST['rp_authorized_consent'] ) || '1' !== $_POST['rp_authorized_consent'] ) {
		return new WP_Error( 'rp_unauthorized', __( 'You must confirm that you are authorized to share this resource.', 'rp-resource-hub' ) );
	}

	$title       = isset( $_POST['rp_title'] ) ? sanitize_text_field( wp_unslash( $_POST['rp_title'] ) ) : '';
	$description = isset( $_POST['rp_description'] ) ? wp_kses_post( wp_unslash( $_POST['rp_description'] ) ) : '';

	if ( '' === $title || '' === $description ) {
		return new WP_Error( 'rp_required', __( 'Title and description are required.', 'rp-resource-hub' ) );
	}

	if ( empty( $_FILES['rp_file'] ) || ! is_array( $_FILES['rp_file'] ) || empty( $_FILES['rp_file']['name'] ) ) {
		return new WP_Error( 'rp_file_required', __( 'Please upload a PDF, Word, or Excel file.', 'rp-resource-hub' ) );
	}

	if ( ! empty( $_FILES['rp_file']['error'] ) ) {
		return new WP_Error( 'rp_upload_error', __( 'The file could not be uploaded. Please try again with a smaller document.', 'rp-resource-hub' ) );
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		if ( empty( $_FILES['rp_file']['size'] ) || RP_RESOURCE_HUB_MAX_UPLOAD_BYTES < (int) $_FILES['rp_file']['size'] ) {
			return new WP_Error(
				'rp_file_size',
				sprintf(
					/* translators: %s: maximum upload size */
					__( 'The file is too large. Maximum upload size is %s.', 'rp-resource-hub' ),
					rp_resource_hub_format_bytes( RP_RESOURCE_HUB_MAX_UPLOAD_BYTES )
				)
			);
		}
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$file = $_FILES['rp_file'];
	$file_type = wp_check_filetype_and_ext( $file['tmp_name'], $file['name'], rp_resource_hub_allowed_mimes() );

	if ( empty( $file_type['ext'] ) || empty( $file_type['type'] ) ) {
		return new WP_Error( 'rp_file_type', __( 'Only PDF, DOC, DOCX, XLS, XLSX, HTML, and ZIP files are allowed.', 'rp-resource-hub' ) );
	}

	$_FILES['rp_file']['name'] = sanitize_file_name( $file['name'] );

	$submitted_terms = isset( $_POST['rp_terms'] ) && is_array( $_POST['rp_terms'] ) ? wp_unslash( $_POST['rp_terms'] ) : array();
	$post_type       = 'partner_resources';
	$accord_term     = get_term_by( 'name', 'ACCORD', 'contributing_org' );
	if ( $accord_term && ! empty( $submitted_terms['contributing_org'] ) && is_array( $submitted_terms['contributing_org'] ) ) {
		if ( in_array( (int) $accord_term->term_id, array_map( 'intval', $submitted_terms['contributing_org'] ), true ) ) {
			$post_type = 'accord_library';
		}
	}

	$post_id = wp_insert_post(
		array(
			'post_type'    => $post_type,
			'post_title'   => $title,
			'post_content' => $description,
			'post_status'  => 'pending',
			'post_author'  => get_current_user_id(),
		),
		true
	);

	if ( is_wp_error( $post_id ) ) {
		return $post_id;
	}

	add_filter( 'upload_dir', 'rp_resource_hub_secure_upload_dir' );
	$attachment_id = media_handle_upload( 'rp_file', $post_id );
	remove_filter( 'upload_dir', 'rp_resource_hub_secure_upload_dir' );

	if ( is_wp_error( $attachment_id ) ) {
		wp_delete_post( $post_id, true );
		return $attachment_id;
	}

	update_post_meta( $post_id, '_rp_resource_file_id', absint( $attachment_id ) );

	$taxonomies = array( 'resource_format', 'resource_category', 'hazard_type', 'target_audience', 'contributing_org', 'resource_visibility' );
	$submitted_terms = isset( $_POST['rp_terms'] ) && is_array( $_POST['rp_terms'] ) ? wp_unslash( $_POST['rp_terms'] ) : array();

	foreach ( $taxonomies as $taxonomy ) {
		if ( empty( $submitted_terms[ $taxonomy ] ) || ! is_array( $submitted_terms[ $taxonomy ] ) ) {
			continue;
		}

		$term_ids = array_map( 'absint', $submitted_terms[ $taxonomy ] );
		$term_ids = array_filter( $term_ids );
		wp_set_object_terms( $post_id, $term_ids, $taxonomy, false );
	}

	$admin_email = get_option( 'admin_email' );
	if ( is_email( $admin_email ) ) {
		wp_mail(
			$admin_email,
			sprintf( __( 'Resource pending review: %s', 'rp-resource-hub' ), $title ),
			sprintf(
				/* translators: 1: resource title, 2: edit URL */
				__( "A partner resource has been submitted and is pending review.\n\nTitle: %1\$s\nReview: %2\$s", 'rp-resource-hub' ),
				$title,
				admin_url( 'post.php?post=' . absint( $post_id ) . '&action=edit' )
			)
		);
	}

	return $post_id;
}

function rp_resource_hub_handle_upload_form() {
	$result = rp_resource_hub_process_upload();

	if ( is_wp_error( $result ) ) {
		$notice_key = rp_resource_hub_store_upload_notice( 'error', $result->get_error_message() );
		$redirect   = wp_get_referer() ? wp_get_referer() : home_url( '/submit-resource/' );

		wp_safe_redirect( add_query_arg( 'rp_upload_notice', $notice_key, $redirect ) );
		exit;
	}

	if ( ! is_numeric( $result ) ) {
		$notice_key = rp_resource_hub_store_upload_notice( 'error', __( 'Submission could not be processed. Please try again.', 'rp-resource-hub' ) );
		wp_safe_redirect( add_query_arg( 'rp_upload_notice', $notice_key, home_url( '/submit-resource/' ) ) );
		exit;
	}

	wp_safe_redirect( rp_resource_hub_success_url( absint( $result ) ) );
	exit;
}
add_action( 'admin_post_rp_resource_upload', 'rp_resource_hub_handle_upload_form' );

function rp_resource_hub_handle_upload_form_logged_out() {
	wp_safe_redirect( wp_login_url( home_url( '/submit-resource/' ) ) );
	exit;
}
add_action( 'admin_post_nopriv_rp_resource_upload', 'rp_resource_hub_handle_upload_form_logged_out' );

function rp_resource_hub_upload_shortcode() {
	ob_start();

	if ( ! rp_resource_hub_user_can_upload() ) {
		echo '<div class="rp-notice rp-notice-error">' . esc_html__( 'Please log in with a partner contributor account to submit resources.', 'rp-resource-hub' ) . '</div>';
		return ob_get_clean();
	}

	$notice = rp_resource_hub_get_upload_notice();
	if ( $notice ) {
		$notice_class = 'success' === $notice['type'] ? 'rp-notice-success' : 'rp-notice-error';
		echo '<div class="rp-notice ' . esc_attr( $notice_class ) . '">' . esc_html( $notice['message'] ) . '</div>';
	}
	?>
	<form class="rp-upload-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" enctype="multipart/form-data">
		<input type="hidden" name="action" value="rp_resource_upload">
		<?php wp_nonce_field( 'rp_resource_upload', 'rp_resource_upload_nonce' ); ?>
		<div class="rp-field">
			<label for="rp_title"><?php esc_html_e( 'Resource Title', 'rp-resource-hub' ); ?></label>
			<input id="rp_title" name="rp_title" type="text" required maxlength="160">
		</div>
		<div class="rp-field">
			<label for="rp_description"><?php esc_html_e( 'Description', 'rp-resource-hub' ); ?></label>
			<textarea id="rp_description" name="rp_description" rows="6" required></textarea>
		</div>
		<?php foreach ( array( 'resource_format', 'resource_category', 'hazard_type', 'target_audience', 'contributing_org', 'resource_visibility' ) as $taxonomy ) : ?>
			<?php
			$taxonomy_object = get_taxonomy( $taxonomy );
			if ( ! $taxonomy_object ) {
				continue;
			}
			?>
			<fieldset class="rp-field rp-checkbox-list">
				<legend><?php echo esc_html( $taxonomy_object->labels->name ); ?></legend>
				<?php rp_resource_hub_term_options( $taxonomy ); ?>
			</fieldset>
		<?php endforeach; ?>
		<div class="rp-field">
			<label for="rp_file"><?php esc_html_e( 'Upload File', 'rp-resource-hub' ); ?></label>
			<input id="rp_file" name="rp_file" type="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.html,.zip" required>
			<?php
			$max_size_text = current_user_can( 'manage_options' ) 
				? __( 'No limit (Administrator)', 'rp-resource-hub' ) 
				: rp_resource_hub_format_bytes( RP_RESOURCE_HUB_MAX_UPLOAD_BYTES );
			?>
			<p class="rp-field-help"><?php echo esc_html( sprintf( __( 'Accepted file types: PDF, DOC, DOCX, XLS, XLSX, HTML, ZIP (for Web Apps). Maximum size: %s.', 'rp-resource-hub' ), $max_size_text ) ); ?></p>
		</div>
		<div class="rp-field rp-field-consent">
			<label style="display: flex; align-items: flex-start; gap: 8px; font-weight: normal; cursor: pointer;">
				<input id="rp_authorized_consent" name="rp_authorized_consent" type="checkbox" value="1" required style="margin-top: 4px; width: auto; height: auto;">
				<span><?php esc_html_e( 'I confirm that I am authorized to share this resource publicly or on this website.', 'rp-resource-hub' ); ?></span>
			</label>
		</div>
		<button type="submit"><?php esc_html_e( 'Submit for Review', 'rp-resource-hub' ); ?></button>
	</form>
	<?php
	return ob_get_clean();
}
add_shortcode( 'rp_partner_upload_form', 'rp_resource_hub_upload_shortcode' );

function rp_resource_hub_is_member_only( $post_id ) {
	return has_term( 'Member Only', 'resource_visibility', $post_id );
}

function rp_resource_hub_download_url( $post_id ) {
	return wp_nonce_url(
		add_query_arg(
			array(
				'rp_resource_download' => absint( $post_id ),
			),
			home_url( '/' )
		),
		'rp_resource_download_' . absint( $post_id )
	);
}

function rp_resource_hub_secure_upload_dir( $uploads ) {
	$subdir = '/rp-secure';
	$uploads['subdir'] = $subdir;
	$uploads['path']   = $uploads['basedir'] . $subdir;
	$uploads['url']    = $uploads['baseurl'] . $subdir;
	return $uploads;
}

function rp_tinig_user_can_manage() {
	return is_user_logged_in() && ( current_user_can( 'manage_tinig_cases' ) || current_user_can( 'manage_options' ) );
}

function rp_tinig_feedback_type_options() {
	return array(
		'general_feedback' => __( 'General feedback', 'rp-resource-hub' ),
		'complaint'        => __( 'Complaint', 'rp-resource-hub' ),
		'suggestion'       => __( 'Suggestion', 'rp-resource-hub' ),
		'information'      => __( 'Request for information', 'rp-resource-hub' ),
		'safeguarding'     => __( 'Safeguarding/protection concern', 'rp-resource-hub' ),
		'fraud_corruption' => __( 'Corruption/fraud concern', 'rp-resource-hub' ),
		'data_privacy'     => __( 'Data privacy concern', 'rp-resource-hub' ),
	);
}

function rp_tinig_status_options() {
	return array(
		'new'          => __( 'New', 'rp-resource-hub' ),
		'triaged'      => __( 'Triaged', 'rp-resource-hub' ),
		'in_review'    => __( 'In Review', 'rp-resource-hub' ),
		'referred'     => __( 'Referred', 'rp-resource-hub' ),
		'action_taken' => __( 'Action Taken', 'rp-resource-hub' ),
		'resolved'     => __( 'Resolved', 'rp-resource-hub' ),
		'closed'       => __( 'Closed', 'rp-resource-hub' ),
	);
}

function rp_tinig_urgency_options() {
	return array(
		'normal' => __( 'Normal', 'rp-resource-hub' ),
		'high'   => __( 'High', 'rp-resource-hub' ),
		'urgent' => __( 'Urgent / sensitive', 'rp-resource-hub' ),
	);
}

function rp_tinig_allowed_mimes() {
	return array(
		'jpg'  => 'image/jpeg',
		'jpeg' => 'image/jpeg',
		'png'  => 'image/png',
		'gif'  => 'image/gif',
		'webp' => 'image/webp',
		'pdf'  => 'application/pdf',
		'doc'  => 'application/msword',
		'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		'xls'  => 'application/vnd.ms-excel',
		'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
		'txt'  => 'text/plain',
		'zip'  => 'application/zip',
	);
}

function rp_tinig_upload_dir( $uploads ) {
	$subdir = '/rp-secure/tinig';
	$uploads['subdir'] = $subdir;
	$uploads['path']   = $uploads['basedir'] . $subdir;
	$uploads['url']    = $uploads['baseurl'] . $subdir;
	return $uploads;
}

function rp_tinig_protect_upload_dir() {
	$uploads = wp_get_upload_dir();
	$dirs    = array(
		$uploads['basedir'] . '/rp-secure',
		$uploads['basedir'] . '/rp-secure/tinig',
	);

	foreach ( $dirs as $dir ) {
		if ( ! wp_mkdir_p( $dir ) || ! wp_is_writable( $dir ) ) {
			continue;
		}

		$index_file = trailingslashit( $dir ) . 'index.php';
		if ( ! file_exists( $index_file ) ) {
			file_put_contents( $index_file, "<?php\n// Silence is golden.\n" );
		}

		$htaccess_file = trailingslashit( $dir ) . '.htaccess';
		if ( ! file_exists( $htaccess_file ) ) {
			file_put_contents(
				$htaccess_file,
				"Options -Indexes\n<IfModule mod_authz_core.c>\nRequire all denied\n</IfModule>\n<IfModule !mod_authz_core.c>\nDeny from all\n</IfModule>\n"
			);
		}
	}
}

function rp_tinig_normalize_files( $field ) {
	if ( empty( $_FILES[ $field ] ) || empty( $_FILES[ $field ]['name'] ) ) {
		return array();
	}

	$files = $_FILES[ $field ];
	if ( ! is_array( $files['name'] ) ) {
		return array( $files );
	}

	$normalized = array();
	foreach ( $files['name'] as $index => $name ) {
		if ( '' === $name ) {
			continue;
		}

		$normalized[] = array(
			'name'     => $files['name'][ $index ],
			'type'     => $files['type'][ $index ],
			'tmp_name' => $files['tmp_name'][ $index ],
			'error'    => $files['error'][ $index ],
			'size'     => $files['size'][ $index ],
		);
	}

	return $normalized;
}

function rp_tinig_validate_files( $files ) {
	if ( count( $files ) > RP_TINIG_MAX_ATTACHMENTS ) {
		return new WP_Error(
			'rp_tinig_too_many_files',
			sprintf(
				/* translators: %d: maximum number of attachments */
				__( 'Please upload no more than %d evidence files.', 'rp-resource-hub' ),
				RP_TINIG_MAX_ATTACHMENTS
			)
		);
	}

	foreach ( $files as $file ) {
		if ( ! empty( $file['error'] ) ) {
			return new WP_Error( 'rp_tinig_upload_error', __( 'One of the evidence files could not be uploaded. Please try again.', 'rp-resource-hub' ) );
		}

		if ( empty( $file['size'] ) || RP_TINIG_MAX_ATTACHMENT_BYTES < (int) $file['size'] ) {
			return new WP_Error(
				'rp_tinig_file_size',
				sprintf(
					/* translators: %s: maximum upload size */
					__( 'Each evidence file must be %s or smaller.', 'rp-resource-hub' ),
					rp_resource_hub_format_bytes( RP_TINIG_MAX_ATTACHMENT_BYTES )
				)
			);
		}

		$file_type = wp_check_filetype_and_ext( $file['tmp_name'], $file['name'], rp_tinig_allowed_mimes() );
		if ( empty( $file_type['ext'] ) || empty( $file_type['type'] ) ) {
			return new WP_Error( 'rp_tinig_file_type', __( 'Evidence files may be images, PDF, Word, Excel, text, or ZIP files only.', 'rp-resource-hub' ) );
		}
	}

	return true;
}

function rp_tinig_get_case( $case_id ) {
	global $wpdb;
	$table = $wpdb->prefix . 'rp_tinig_cases';
	return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table WHERE id = %d", absint( $case_id ) ) );
}

function rp_tinig_capture_mail_error( $error ) {
	global $rp_tinig_last_mail_error;
	if ( is_wp_error( $error ) ) {
		$rp_tinig_last_mail_error = $error->get_error_message();
	}
}

function rp_tinig_graph_mail_is_configured() {
	return RP_GRAPH_MAIL_TENANT_ID && RP_GRAPH_MAIL_CLIENT_ID && RP_GRAPH_MAIL_CLIENT_SECRET && RP_GRAPH_MAIL_SENDER;
}

function rp_tinig_graph_request( $url, $args ) {
	$response = wp_remote_request(
		$url,
		array_merge(
			array(
				'timeout' => 20,
			),
			$args
		)
	);

	if ( is_wp_error( $response ) ) {
		return $response;
	}

	$code = wp_remote_retrieve_response_code( $response );
	$body = wp_remote_retrieve_body( $response );
	$data = json_decode( $body, true );

	if ( 200 <= $code && 300 > $code ) {
		return is_array( $data ) ? $data : array();
	}

	$message = '';
	if ( is_array( $data ) && ! empty( $data['error']['message'] ) ) {
		$message = $data['error']['message'];
	} elseif ( is_array( $data ) && ! empty( $data['error_description'] ) ) {
		$message = $data['error_description'];
	} elseif ( $body ) {
		$message = wp_strip_all_tags( $body );
	}

	return new WP_Error(
		'rp_tinig_graph_request_failed',
		sprintf(
			/* translators: 1: HTTP status code, 2: Graph response message */
			__( 'Microsoft Graph request failed with HTTP %1$d: %2$s', 'rp-resource-hub' ),
			$code,
			$message ? $message : __( 'No response body.', 'rp-resource-hub' )
		)
	);
}

function rp_tinig_graph_get_access_token() {
	$token_url = sprintf(
		'https://login.microsoftonline.com/%s/oauth2/v2.0/token',
		rawurlencode( RP_GRAPH_MAIL_TENANT_ID )
	);

	$response = wp_remote_post(
		$token_url,
		array(
			'timeout' => 20,
			'body'    => array(
				'client_id'     => RP_GRAPH_MAIL_CLIENT_ID,
				'client_secret' => RP_GRAPH_MAIL_CLIENT_SECRET,
				'scope'         => 'https://graph.microsoft.com/.default',
				'grant_type'    => 'client_credentials',
			),
		)
	);

	if ( is_wp_error( $response ) ) {
		return $response;
	}

	$code = wp_remote_retrieve_response_code( $response );
	$body = wp_remote_retrieve_body( $response );
	$data = json_decode( $body, true );

	if ( 200 !== $code || empty( $data['access_token'] ) ) {
		$message = '';
		if ( is_array( $data ) && ! empty( $data['error_description'] ) ) {
			$message = $data['error_description'];
		} elseif ( $body ) {
			$message = wp_strip_all_tags( $body );
		}

		return new WP_Error(
			'rp_tinig_graph_token_failed',
			sprintf(
				/* translators: 1: HTTP status code, 2: token response message */
				__( 'Microsoft Graph token request failed with HTTP %1$d: %2$s', 'rp-resource-hub' ),
				$code,
				$message ? $message : __( 'No access token returned.', 'rp-resource-hub' )
			)
		);
	}

	return $data['access_token'];
}

function rp_tinig_graph_send_mail( $subject, $message, $recipient = RP_TINIG_NOTIFICATION_EMAIL, $reply_to = '', $cc_recipients = array() ) {
	if ( ! rp_tinig_graph_mail_is_configured() ) {
		return new WP_Error( 'rp_tinig_graph_not_configured', __( 'Microsoft Graph mailer is not fully configured.', 'rp-resource-hub' ) );
	}

	if ( ! is_email( $recipient ) ) {
		return new WP_Error( 'rp_graph_invalid_recipient', __( 'Microsoft Graph mail recipient is invalid.', 'rp-resource-hub' ) );
	}

	$token = rp_tinig_graph_get_access_token();
	if ( is_wp_error( $token ) ) {
		return $token;
	}

	$reply_to      = $reply_to && is_email( $reply_to ) ? $reply_to : $recipient;
	$cc_recipients = array_filter( array_map( 'sanitize_email', (array) $cc_recipients ), 'is_email' );
	$payload = array(
		'message'         => array(
			'subject'      => $subject,
			'body'         => array(
				'contentType' => 'Text',
				'content'     => $message,
			),
			'toRecipients' => array(
				array(
					'emailAddress' => array(
						'address' => $recipient,
					),
				),
			),
			'replyTo'      => array(
				array(
					'emailAddress' => array(
						'address' => $reply_to,
					),
				),
			),
		),
		'saveToSentItems' => true,
	);

	if ( $cc_recipients ) {
		$payload['message']['ccRecipients'] = array_map(
			function ( $email ) {
				return array(
					'emailAddress' => array(
						'address' => $email,
					),
				);
			},
			array_values( array_unique( $cc_recipients ) )
		);
	}

	$send_url = sprintf(
		'https://graph.microsoft.com/v1.0/users/%s/sendMail',
		rawurlencode( RP_GRAPH_MAIL_SENDER )
	);

	$result = rp_tinig_graph_request(
		$send_url,
		array(
			'method'  => 'POST',
			'headers' => array(
				'Authorization' => 'Bearer ' . $token,
				'Content-Type'  => 'application/json',
			),
			'body'    => wp_json_encode( $payload ),
		)
	);

	return is_wp_error( $result ) ? $result : true;
}

function rp_tinig_wp_mail_send( $subject, $message ) {
	global $rp_tinig_last_mail_error;
	$rp_tinig_last_mail_error = '';
	add_action( 'wp_mail_failed', 'rp_tinig_capture_mail_error' );
	$sent = wp_mail( RP_TINIG_NOTIFICATION_EMAIL, $subject, $message );
	remove_action( 'wp_mail_failed', 'rp_tinig_capture_mail_error' );

	if ( ! $sent ) {
		return new WP_Error(
			'rp_tinig_mail_failed',
			$rp_tinig_last_mail_error ? $rp_tinig_last_mail_error : __( 'WordPress could not hand off the Tinig notification email to the configured mailer.', 'rp-resource-hub' )
		);
	}

	return true;
}

function rp_contact_graph_form_id() {
	return 814;
}

function rp_contact_graph_last_result_key() {
	return 'rp_contact_graph_mail_result_' . get_current_blog_id();
}

function rp_contact_send_graph_mail( $contact_form, &$abort, $submission ) {
	if ( ! $contact_form || (int) $contact_form->id() !== rp_contact_graph_form_id() || ! $submission ) {
		return;
	}

	$name    = sanitize_text_field( $submission->get_posted_string( 'your-name' ) );
	$email   = sanitize_email( $submission->get_posted_string( 'your-email' ) );
	$subject = sanitize_text_field( $submission->get_posted_string( 'your-subject' ) );
	$message = sanitize_textarea_field( $submission->get_posted_string( 'your-message' ) );

	if ( ! is_email( RP_CONTACT_NOTIFICATION_EMAIL ) ) {
		update_option( rp_contact_graph_last_result_key(), 'invalid_recipient', false );
		$abort = true;
		return;
	}

	if ( ! rp_tinig_graph_mail_is_configured() ) {
		update_option( rp_contact_graph_last_result_key(), 'graph_not_configured', false );
		return;
	}

	$mail_subject = sprintf(
		/* translators: %s: contact form subject */
		__( 'Contact Us: %s', 'rp-resource-hub' ),
		$subject ? $subject : __( 'Website inquiry', 'rp-resource-hub' )
	);

	$mail_message = sprintf(
		/* translators: 1: sender name, 2: sender email, 3: message subject, 4: message body, 5: source URL */
		__( "A new Contact Us message has been submitted through the ACCORD website.\n\nName: %1\$s\nEmail: %2\$s\nSubject: %3\$s\n\nMessage:\n%4\$s\n\nSource: %5\$s", 'rp-resource-hub' ),
		$name ? $name : __( 'Not provided', 'rp-resource-hub' ),
		$email ? $email : __( 'Not provided', 'rp-resource-hub' ),
		$subject ? $subject : __( 'No subject provided', 'rp-resource-hub' ),
		$message ? $message : __( 'No message provided', 'rp-resource-hub' ),
		home_url( '/contact-us/' )
	);

	$result = rp_tinig_graph_send_mail( $mail_subject, $mail_message, RP_CONTACT_NOTIFICATION_EMAIL, $email );
	if ( is_wp_error( $result ) ) {
		update_option( rp_contact_graph_last_result_key(), 'failed: ' . $result->get_error_message(), false );
		return;
	}

	update_option( rp_contact_graph_last_result_key(), 'sent', false );
}
add_action( 'wpcf7_before_send_mail', 'rp_contact_send_graph_mail', 10, 3 );

function rp_contact_skip_cf7_mail_after_graph_success( $skip_mail, $contact_form ) {
	if ( ! $contact_form || (int) $contact_form->id() !== rp_contact_graph_form_id() ) {
		return $skip_mail;
	}

	return 'sent' === get_option( rp_contact_graph_last_result_key() ) ? true : $skip_mail;
}
add_filter( 'wpcf7_skip_mail', 'rp_contact_skip_cf7_mail_after_graph_success', 10, 2 );

function rp_tinig_notify_new_case( $case_id ) {
	$case = rp_tinig_get_case( $case_id );
	if ( ! $case ) {
		return new WP_Error( 'rp_tinig_missing_case', __( 'Tinig notification skipped because the case could not be found.', 'rp-resource-hub' ) );
	}

	if ( ! is_email( RP_TINIG_NOTIFICATION_EMAIL ) ) {
		return new WP_Error( 'rp_tinig_invalid_notification_email', __( 'Tinig notification email is invalid.', 'rp-resource-hub' ) );
	}

	$type_options = rp_tinig_feedback_type_options();
	$dashboard    = add_query_arg( 'case', absint( $case_id ), home_url( '/tinig-dashboard/' ) );
	$subject      = sprintf( __( 'New Tinig case received: %s', 'rp-resource-hub' ), $case->reference_code );
	$attachments  = json_decode( (string) $case->attachment_ids, true );
	$count        = is_array( $attachments ) ? count( $attachments ) : 0;
	$message      = sprintf(
		/* translators: 1: reference code, 2: feedback type, 3: urgency, 4: submitted date, 5: contact status, 6: attachment count, 7: dashboard URL */
		__( "A new Tinig feedback/accountability case has been submitted.\n\nReference: %1\$s\nType: %2\$s\nUrgency: %3\$s\nSubmitted: %4\$s\nContact available: %5\$s\nEvidence files: %6\$d\n\nFor privacy and safety, full details are not included in this email. Log in to review the case:\n%7\$s", 'rp-resource-hub' ),
		$case->reference_code,
		isset( $type_options[ $case->feedback_type ] ) ? $type_options[ $case->feedback_type ] : $case->feedback_type,
		$case->urgency,
		$case->submitted_at,
		$case->safe_to_contact ? __( 'Yes', 'rp-resource-hub' ) : __( 'No/anonymous', 'rp-resource-hub' ),
		$count,
		$dashboard
	);

	if ( rp_tinig_graph_mail_is_configured() ) {
		$graph_result = rp_tinig_graph_send_mail( $subject, $message );
		if ( ! is_wp_error( $graph_result ) ) {
			return 'graph';
		}

		$wp_mail_result = rp_tinig_wp_mail_send( $subject, $message );
		if ( is_wp_error( $wp_mail_result ) ) {
			return new WP_Error(
				'rp_tinig_all_mail_failed',
				sprintf(
					/* translators: 1: Graph error, 2: WordPress mail error */
					__( 'Microsoft Graph failed: %1$s WordPress mail fallback also failed: %2$s', 'rp-resource-hub' ),
					$graph_result->get_error_message(),
					$wp_mail_result->get_error_message()
				)
			);
		}

		return 'wp_mail_fallback';
	}

	return rp_tinig_wp_mail_send( $subject, $message );
}

function rp_tinig_process_submission() {
	if ( 'POST' !== $_SERVER['REQUEST_METHOD'] || empty( $_POST['rp_tinig_nonce'] ) ) {
		return null;
	}

	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['rp_tinig_nonce'] ) ), 'rp_tinig_submit' ) ) {
		return new WP_Error( 'rp_tinig_nonce', __( 'Security check failed. Please refresh and try again.', 'rp-resource-hub' ) );
	}

	if ( ! empty( $_POST['rp_tinig_website'] ) ) {
		return new WP_Error( 'rp_tinig_spam', __( 'Submission could not be accepted.', 'rp-resource-hub' ) );
	}

	if ( empty( $_POST['rp_tinig_consent'] ) || '1' !== $_POST['rp_tinig_consent'] ) {
		return new WP_Error( 'rp_tinig_consent', __( 'Please confirm the privacy notice before submitting.', 'rp-resource-hub' ) );
	}

	$type_options    = rp_tinig_feedback_type_options();
	$urgency_options = rp_tinig_urgency_options();
	$feedback_type   = isset( $_POST['rp_tinig_type'] ) ? sanitize_key( wp_unslash( $_POST['rp_tinig_type'] ) ) : '';
	$urgency         = isset( $_POST['rp_tinig_urgency'] ) ? sanitize_key( wp_unslash( $_POST['rp_tinig_urgency'] ) ) : 'normal';
	$is_anonymous    = ! empty( $_POST['rp_tinig_anonymous'] ) ? 1 : 0;
	$safe_to_contact = ! empty( $_POST['rp_tinig_safe_contact'] ) ? 1 : 0;
	$message         = isset( $_POST['rp_tinig_message'] ) ? wp_kses_post( wp_unslash( $_POST['rp_tinig_message'] ) ) : '';

	if ( ! isset( $type_options[ $feedback_type ] ) ) {
		return new WP_Error( 'rp_tinig_type', __( 'Please choose a feedback type.', 'rp-resource-hub' ) );
	}

	if ( ! isset( $urgency_options[ $urgency ] ) ) {
		$urgency = 'normal';
	}

	if ( '' === trim( wp_strip_all_tags( $message ) ) ) {
		return new WP_Error( 'rp_tinig_message', __( 'Please describe your feedback, concern, or complaint.', 'rp-resource-hub' ) );
	}

	$files = rp_tinig_normalize_files( 'rp_tinig_files' );
	$file_validation = rp_tinig_validate_files( $files );
	if ( is_wp_error( $file_validation ) ) {
		return $file_validation;
	}

	$contact_name      = $is_anonymous ? '' : ( isset( $_POST['rp_tinig_name'] ) ? sanitize_text_field( wp_unslash( $_POST['rp_tinig_name'] ) ) : '' );
	$contact_email     = $is_anonymous ? '' : ( isset( $_POST['rp_tinig_email'] ) ? sanitize_email( wp_unslash( $_POST['rp_tinig_email'] ) ) : '' );
	$contact_phone     = $is_anonymous ? '' : ( isset( $_POST['rp_tinig_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['rp_tinig_phone'] ) ) : '' );
	$preferred_contact = $is_anonymous ? '' : ( isset( $_POST['rp_tinig_preferred_contact'] ) ? sanitize_key( wp_unslash( $_POST['rp_tinig_preferred_contact'] ) ) : '' );
	$location          = isset( $_POST['rp_tinig_location'] ) ? sanitize_text_field( wp_unslash( $_POST['rp_tinig_location'] ) ) : '';
	$program           = isset( $_POST['rp_tinig_program'] ) ? sanitize_text_field( wp_unslash( $_POST['rp_tinig_program'] ) ) : '';
	$subject           = isset( $_POST['rp_tinig_subject'] ) ? sanitize_text_field( wp_unslash( $_POST['rp_tinig_subject'] ) ) : '';
	$is_sensitive      = in_array( $feedback_type, array( 'safeguarding', 'fraud_corruption', 'data_privacy' ), true ) || 'urgent' === $urgency ? 1 : 0;

	if ( $contact_email && ! is_email( $contact_email ) ) {
		return new WP_Error( 'rp_tinig_email', __( 'Please enter a valid email address or leave it blank.', 'rp-resource-hub' ) );
	}

	global $wpdb;
	$table = $wpdb->prefix . 'rp_tinig_cases';
	$now   = current_time( 'mysql' );

	$inserted = $wpdb->insert(
		$table,
		array(
			'submitted_at'      => $now,
			'updated_at'        => $now,
			'status'            => 'new',
			'feedback_type'     => $feedback_type,
			'urgency'           => $urgency,
			'is_sensitive'      => $is_sensitive,
			'is_anonymous'      => $is_anonymous,
			'safe_to_contact'   => $safe_to_contact,
			'contact_name'      => $contact_name,
			'contact_email'     => $contact_email,
			'contact_phone'     => $contact_phone,
			'preferred_contact' => $preferred_contact,
			'location'          => $location,
			'program'           => $program,
			'subject'           => $subject,
			'message'           => $message,
			'attachment_ids'    => wp_json_encode( array() ),
			'consent'           => 1,
			'ip_address'        => rp_resource_hub_get_ip(),
			'user_agent'        => isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : '',
		),
		array( '%s', '%s', '%s', '%s', '%s', '%d', '%d', '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%s', '%s' )
	);

	if ( ! $inserted ) {
		return new WP_Error( 'rp_tinig_db', __( 'The case could not be saved. Please try again.', 'rp-resource-hub' ) );
	}

	$case_id        = absint( $wpdb->insert_id );
	$reference_code = 'TINIG-' . gmdate( 'Y' ) . '-' . str_pad( (string) $case_id, 5, '0', STR_PAD_LEFT );
	$attachment_ids = array();

	$wpdb->update(
		$table,
		array( 'reference_code' => $reference_code ),
		array( 'id' => $case_id ),
		array( '%s' ),
		array( '%d' )
	);

	if ( ! empty( $files ) ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';

		rp_tinig_protect_upload_dir();

		foreach ( $files as $index => $file ) {
			$key = 'rp_tinig_file_' . $index;
			$_FILES[ $key ] = $file;
			$_FILES[ $key ]['name'] = sanitize_file_name( $file['name'] );

			add_filter( 'upload_dir', 'rp_tinig_upload_dir' );
			$attachment_id = media_handle_upload( $key, 0 );
			remove_filter( 'upload_dir', 'rp_tinig_upload_dir' );

			unset( $_FILES[ $key ] );

			if ( is_wp_error( $attachment_id ) ) {
				rp_tinig_add_case_note( $case_id, 0, 'system', '', '', sprintf( 'Attachment upload failed: %s', $file['name'] ) );
				continue;
			}

			update_post_meta( $attachment_id, '_rp_tinig_case_id', $case_id );
			$attachment_ids[] = absint( $attachment_id );
		}

		$wpdb->update(
			$table,
			array( 'attachment_ids' => wp_json_encode( $attachment_ids ) ),
			array( 'id' => $case_id ),
			array( '%s' ),
			array( '%d' )
		);
	}

	rp_tinig_add_case_note( $case_id, 0, 'system', '', 'new', __( 'Case submitted through the public Tinig form.', 'rp-resource-hub' ) );
	$notification_result = rp_tinig_notify_new_case( $case_id );
	if ( is_wp_error( $notification_result ) ) {
		rp_tinig_add_case_note(
			$case_id,
			0,
			'system',
			'',
			'',
			sprintf(
				/* translators: %s: mail error message */
				__( 'Email notification to tinig@accord.org.ph failed: %s', 'rp-resource-hub' ),
				$notification_result->get_error_message()
			)
		);
	} elseif ( 'graph' === $notification_result ) {
		rp_tinig_add_case_note( $case_id, 0, 'system', '', '', __( 'Email notification was sent through Microsoft Graph to tinig@accord.org.ph.', 'rp-resource-hub' ) );
	} elseif ( 'wp_mail_fallback' === $notification_result ) {
		rp_tinig_add_case_note( $case_id, 0, 'system', '', '', __( 'Microsoft Graph mail failed, but WordPress mail fallback handed off the notification to tinig@accord.org.ph.', 'rp-resource-hub' ) );
	} else {
		rp_tinig_add_case_note( $case_id, 0, 'system', '', '', __( 'Email notification was handed off to WordPress mail for tinig@accord.org.ph.', 'rp-resource-hub' ) );
	}

	return array(
		'case_id'        => $case_id,
		'reference_code' => $reference_code,
	);
}

function rp_tinig_add_case_note( $case_id, $user_id, $note_type, $old_status, $new_status, $note ) {
	global $wpdb;
	$table = $wpdb->prefix . 'rp_tinig_case_notes';
	return $wpdb->insert(
		$table,
		array(
			'case_id'    => absint( $case_id ),
			'user_id'    => absint( $user_id ),
			'note_type'  => sanitize_key( $note_type ),
			'old_status' => sanitize_key( $old_status ),
			'new_status' => sanitize_key( $new_status ),
			'note'       => wp_kses_post( $note ),
			'created_at' => current_time( 'mysql' ),
		),
		array( '%d', '%d', '%s', '%s', '%s', '%s', '%s' )
	);
}

function rp_tinig_handle_submit() {
	$result = rp_tinig_process_submission();
	$redirect = wp_get_referer() ? wp_get_referer() : home_url( '/tinig/' );

	if ( is_wp_error( $result ) ) {
		$notice_key = rp_resource_hub_store_upload_notice( 'error', $result->get_error_message() );
		wp_safe_redirect( add_query_arg( 'rp_tinig_notice', $notice_key, $redirect ) );
		exit;
	}

	if ( empty( $result['reference_code'] ) ) {
		$notice_key = rp_resource_hub_store_upload_notice( 'error', __( 'Submission could not be processed. Please try again.', 'rp-resource-hub' ) );
		wp_safe_redirect( add_query_arg( 'rp_tinig_notice', $notice_key, $redirect ) );
		exit;
	}

	wp_safe_redirect( add_query_arg( 'tinig_ref', rawurlencode( $result['reference_code'] ), home_url( '/tinig/' ) ) );
	exit;
}
add_action( 'admin_post_rp_tinig_submit', 'rp_tinig_handle_submit' );
add_action( 'admin_post_nopriv_rp_tinig_submit', 'rp_tinig_handle_submit' );

function rp_tinig_form_shortcode() {
	$notice = null;
	if ( ! empty( $_GET['rp_tinig_notice'] ) ) {
		$key = sanitize_text_field( wp_unslash( $_GET['rp_tinig_notice'] ) );
		$notice = get_transient( 'rp_resource_upload_notice_' . $key );
		delete_transient( 'rp_resource_upload_notice_' . $key );
	}

	$submitted_ref = isset( $_GET['tinig_ref'] ) ? sanitize_text_field( wp_unslash( $_GET['tinig_ref'] ) ) : '';
	$type_options  = rp_tinig_feedback_type_options();
	$urgencies     = rp_tinig_urgency_options();

	ob_start();
	?>
	<div class="rp-tinig-shell">
		<div class="rp-tinig-intro">
			<p class="rp-eyebrow"><?php esc_html_e( 'Feedback and Accountability Mechanism', 'rp-resource-hub' ); ?></p>
			<h2><?php esc_html_e( 'Tinig: Your voice matters', 'rp-resource-hub' ); ?></h2>
			<p><?php esc_html_e( 'Use this channel to share feedback, complaints, suggestions, requests for information, or accountability concerns related to ACCORD programs and services.', 'rp-resource-hub' ); ?></p>
		</div>

		<?php if ( $submitted_ref ) : ?>
			<div class="rp-notice rp-notice-success">
				<strong><?php esc_html_e( 'Thank you. Your Tinig submission has been received.', 'rp-resource-hub' ); ?></strong>
				<p><?php echo esc_html( sprintf( __( 'Your reference code is %s. Please keep this code for follow-up.', 'rp-resource-hub' ), $submitted_ref ) ); ?></p>
			</div>
		<?php endif; ?>

		<?php if ( is_array( $notice ) && ! empty( $notice['message'] ) ) : ?>
			<div class="rp-notice rp-notice-<?php echo esc_attr( $notice['type'] ); ?>"><?php echo esc_html( $notice['message'] ); ?></div>
		<?php endif; ?>

		<form class="rp-upload-form rp-tinig-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data">
			<input type="hidden" name="action" value="rp_tinig_submit">
			<?php wp_nonce_field( 'rp_tinig_submit', 'rp_tinig_nonce' ); ?>
			<div class="rp-tinig-honeypot" aria-hidden="true">
				<label for="rp_tinig_website"><?php esc_html_e( 'Website', 'rp-resource-hub' ); ?></label>
				<input type="text" id="rp_tinig_website" name="rp_tinig_website" tabindex="-1" autocomplete="off">
			</div>

			<div class="rp-tinig-privacy">
				<strong><?php esc_html_e( 'Privacy and safety notice', 'rp-resource-hub' ); ?></strong>
				<p><?php esc_html_e( 'You may submit anonymously. If you provide contact details, ACCORD will use them only to acknowledge, verify, or follow up on your concern. Sensitive reports are reviewed through restricted staff access.', 'rp-resource-hub' ); ?></p>
			</div>

			<div class="rp-field">
				<label for="rp_tinig_type"><?php esc_html_e( 'Feedback type', 'rp-resource-hub' ); ?> <span aria-hidden="true">*</span></label>
				<select id="rp_tinig_type" name="rp_tinig_type" required>
					<option value=""><?php esc_html_e( 'Choose one', 'rp-resource-hub' ); ?></option>
					<?php foreach ( $type_options as $value => $label ) : ?>
						<option value="<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $label ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>

			<div class="rp-field">
				<label for="rp_tinig_urgency"><?php esc_html_e( 'Urgency', 'rp-resource-hub' ); ?></label>
				<select id="rp_tinig_urgency" name="rp_tinig_urgency">
					<?php foreach ( $urgencies as $value => $label ) : ?>
						<option value="<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $label ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>

			<div class="rp-field">
				<label for="rp_tinig_subject"><?php esc_html_e( 'Short subject', 'rp-resource-hub' ); ?></label>
				<input type="text" id="rp_tinig_subject" name="rp_tinig_subject" maxlength="190">
			</div>

			<div class="rp-field">
				<label for="rp_tinig_message"><?php esc_html_e( 'Details', 'rp-resource-hub' ); ?> <span aria-hidden="true">*</span></label>
				<textarea id="rp_tinig_message" name="rp_tinig_message" rows="8" required></textarea>
			</div>

			<div class="rp-auth-row">
				<div class="rp-field">
					<label for="rp_tinig_location"><?php esc_html_e( 'Location/community', 'rp-resource-hub' ); ?></label>
					<input type="text" id="rp_tinig_location" name="rp_tinig_location" maxlength="190">
				</div>
				<div class="rp-field">
					<label for="rp_tinig_program"><?php esc_html_e( 'Related project/program', 'rp-resource-hub' ); ?></label>
					<input type="text" id="rp_tinig_program" name="rp_tinig_program" maxlength="190">
				</div>
			</div>

			<div class="rp-field">
				<label><input type="checkbox" name="rp_tinig_anonymous" value="1"> <?php esc_html_e( 'Submit anonymously', 'rp-resource-hub' ); ?></label>
			</div>

			<div class="rp-auth-row">
				<div class="rp-field">
					<label for="rp_tinig_name"><?php esc_html_e( 'Name', 'rp-resource-hub' ); ?></label>
					<input type="text" id="rp_tinig_name" name="rp_tinig_name" maxlength="190">
				</div>
				<div class="rp-field">
					<label for="rp_tinig_email"><?php esc_html_e( 'Email', 'rp-resource-hub' ); ?></label>
					<input type="email" id="rp_tinig_email" name="rp_tinig_email" maxlength="190">
				</div>
			</div>

			<div class="rp-auth-row">
				<div class="rp-field">
					<label for="rp_tinig_phone"><?php esc_html_e( 'Phone', 'rp-resource-hub' ); ?></label>
					<input type="text" id="rp_tinig_phone" name="rp_tinig_phone" maxlength="80">
				</div>
				<div class="rp-field">
					<label for="rp_tinig_preferred_contact"><?php esc_html_e( 'Preferred contact method', 'rp-resource-hub' ); ?></label>
					<select id="rp_tinig_preferred_contact" name="rp_tinig_preferred_contact">
						<option value=""><?php esc_html_e( 'No preference', 'rp-resource-hub' ); ?></option>
						<option value="email"><?php esc_html_e( 'Email', 'rp-resource-hub' ); ?></option>
						<option value="phone"><?php esc_html_e( 'Phone', 'rp-resource-hub' ); ?></option>
					</select>
				</div>
			</div>

			<div class="rp-field">
				<label><input type="checkbox" name="rp_tinig_safe_contact" value="1"> <?php esc_html_e( 'It is safe for ACCORD to contact me using the details I provided.', 'rp-resource-hub' ); ?></label>
			</div>

			<div class="rp-field">
				<label for="rp_tinig_files"><?php esc_html_e( 'Evidence/proof attachments', 'rp-resource-hub' ); ?></label>
				<input type="file" id="rp_tinig_files" name="rp_tinig_files[]" multiple accept=".jpg,.jpeg,.png,.gif,.webp,.pdf,.doc,.docx,.xls,.xlsx,.txt,.zip">
				<p class="rp-field-help"><?php echo esc_html( sprintf( __( 'Optional. You may upload up to %1$d files. Each file must be %2$s or smaller.', 'rp-resource-hub' ), RP_TINIG_MAX_ATTACHMENTS, rp_resource_hub_format_bytes( RP_TINIG_MAX_ATTACHMENT_BYTES ) ) ); ?></p>
			</div>

			<div class="rp-field">
				<label><input type="checkbox" name="rp_tinig_consent" value="1" required> <?php esc_html_e( 'I understand and agree that ACCORD may process this submission for feedback, accountability, safeguarding, and follow-up purposes.', 'rp-resource-hub' ); ?></label>
			</div>

			<button type="submit"><?php esc_html_e( 'Submit to Tinig', 'rp-resource-hub' ); ?></button>
		</form>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'rp_tinig_form', 'rp_tinig_form_shortcode' );

function rp_tinig_handle_case_update() {
	if ( ! rp_tinig_user_can_manage() ) {
		auth_redirect();
	}

	$case_id = isset( $_POST['case_id'] ) ? absint( $_POST['case_id'] ) : 0;
	if ( ! $case_id || empty( $_POST['rp_tinig_case_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['rp_tinig_case_nonce'] ) ), 'rp_tinig_update_case_' . $case_id ) ) {
		wp_die( esc_html__( 'Security check failed.', 'rp-resource-hub' ), '', array( 'response' => 403 ) );
	}

	$case = rp_tinig_get_case( $case_id );
	if ( ! $case ) {
		wp_die( esc_html__( 'Case not found.', 'rp-resource-hub' ), '', array( 'response' => 404 ) );
	}

	$statuses = rp_tinig_status_options();
	$new_status = isset( $_POST['rp_tinig_status'] ) ? sanitize_key( wp_unslash( $_POST['rp_tinig_status'] ) ) : $case->status;
	if ( ! isset( $statuses[ $new_status ] ) ) {
		$new_status = $case->status;
	}

	$resolution = isset( $_POST['rp_tinig_resolution'] ) ? wp_kses_post( wp_unslash( $_POST['rp_tinig_resolution'] ) ) : '';
	$note       = isset( $_POST['rp_tinig_note'] ) ? wp_kses_post( wp_unslash( $_POST['rp_tinig_note'] ) ) : '';

	global $wpdb;
	$table = $wpdb->prefix . 'rp_tinig_cases';
	$wpdb->update(
		$table,
		array(
			'status'             => $new_status,
			'updated_at'         => current_time( 'mysql' ),
			'last_updated_by'    => get_current_user_id(),
			'resolution_summary' => $resolution,
		),
		array( 'id' => $case_id ),
		array( '%s', '%s', '%d', '%s' ),
		array( '%d' )
	);

	if ( $new_status !== $case->status || '' !== trim( wp_strip_all_tags( $note ) ) ) {
		rp_tinig_add_case_note( $case_id, get_current_user_id(), 'internal', $case->status, $new_status, $note ? $note : __( 'Status updated.', 'rp-resource-hub' ) );
	}

	wp_safe_redirect( add_query_arg( array( 'case' => $case_id, 'updated' => '1' ), home_url( '/tinig-dashboard/' ) ) );
	exit;
}
add_action( 'admin_post_rp_tinig_update_case', 'rp_tinig_handle_case_update' );

function rp_tinig_handle_attachment_download() {
	if ( ! rp_tinig_user_can_manage() ) {
		auth_redirect();
	}

	$attachment_id = isset( $_GET['attachment_id'] ) ? absint( $_GET['attachment_id'] ) : 0;
	$case_id       = isset( $_GET['case_id'] ) ? absint( $_GET['case_id'] ) : 0;
	$nonce         = isset( $_GET['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) : '';

	if ( ! $attachment_id || ! $case_id || ! wp_verify_nonce( $nonce, 'rp_tinig_download_' . $case_id . '_' . $attachment_id ) ) {
		wp_die( esc_html__( 'Invalid attachment link.', 'rp-resource-hub' ), '', array( 'response' => 403 ) );
	}

	if ( absint( get_post_meta( $attachment_id, '_rp_tinig_case_id', true ) ) !== $case_id ) {
		wp_die( esc_html__( 'Attachment does not belong to this case.', 'rp-resource-hub' ), '', array( 'response' => 403 ) );
	}

	$path = get_attached_file( $attachment_id );
	if ( ! $path || ! is_readable( $path ) ) {
		wp_die( esc_html__( 'Attachment not found.', 'rp-resource-hub' ), '', array( 'response' => 404 ) );
	}

	$real_path = realpath( $path );
	$uploads   = wp_get_upload_dir();
	$base_path = realpath( $uploads['basedir'] );
	if ( ! $real_path || ! $base_path || 0 !== strpos( $real_path, $base_path ) ) {
		wp_die( esc_html__( 'Invalid attachment path.', 'rp-resource-hub' ), '', array( 'response' => 403 ) );
	}

	$mime_type = get_post_mime_type( $attachment_id );
	$filename  = str_replace( array( "\r", "\n", '"' ), '', basename( $real_path ) );

	nocache_headers();
	header( 'Content-Type: ' . ( $mime_type ? $mime_type : 'application/octet-stream' ) );
	header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
	header( 'Content-Length: ' . filesize( $real_path ) );
	readfile( $real_path );
	exit;
}
add_action( 'admin_post_rp_tinig_download_attachment', 'rp_tinig_handle_attachment_download' );

function rp_tinig_handle_export() {
	if ( ! rp_tinig_user_can_manage() ) {
		auth_redirect();
	}

	check_admin_referer( 'rp_tinig_export' );

	global $wpdb;
	$table = $wpdb->prefix . 'rp_tinig_cases';
	$cases = $wpdb->get_results( "SELECT id, reference_code, submitted_at, updated_at, status, feedback_type, urgency, is_sensitive, is_anonymous, safe_to_contact, location, program, subject FROM $table ORDER BY submitted_at DESC LIMIT 1000" );

	nocache_headers();
	header( 'Content-Type: text/csv; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename="tinig-cases-' . gmdate( 'Y-m-d' ) . '.csv"' );

	$out = fopen( 'php://output', 'w' );
	fputcsv( $out, array( 'ID', 'Reference', 'Submitted', 'Updated', 'Status', 'Type', 'Urgency', 'Sensitive', 'Anonymous', 'Safe to Contact', 'Location', 'Program', 'Subject' ) );
	foreach ( $cases as $case ) {
		fputcsv( $out, array(
			$case->id,
			$case->reference_code,
			$case->submitted_at,
			$case->updated_at,
			$case->status,
			$case->feedback_type,
			$case->urgency,
			$case->is_sensitive ? 'yes' : 'no',
			$case->is_anonymous ? 'yes' : 'no',
			$case->safe_to_contact ? 'yes' : 'no',
			$case->location,
			$case->program,
			$case->subject,
		) );
	}
	fclose( $out );
	exit;
}
add_action( 'admin_post_rp_tinig_export_cases', 'rp_tinig_handle_export' );

function rp_tinig_dashboard_shortcode() {
	if ( ! rp_tinig_user_can_manage() ) {
		return '<div class="rp-empty-state"><p>' . esc_html__( 'You must be authorized to view Tinig cases.', 'rp-resource-hub' ) . '</p><p><a class="rp-button" href="' . esc_url( wp_login_url( home_url( '/tinig-dashboard/' ) ) ) . '">' . esc_html__( 'Log in', 'rp-resource-hub' ) . '</a></p></div>';
	}

	$case_id = isset( $_GET['case'] ) ? absint( $_GET['case'] ) : 0;
	return $case_id ? rp_tinig_render_case_detail( $case_id ) : rp_tinig_render_case_list();
}
add_shortcode( 'rp_tinig_dashboard', 'rp_tinig_dashboard_shortcode' );

function rp_tinig_render_case_list() {
	global $wpdb;
	$table = $wpdb->prefix . 'rp_tinig_cases';
	$status = isset( $_GET['status'] ) ? sanitize_key( wp_unslash( $_GET['status'] ) ) : '';
	$type   = isset( $_GET['type'] ) ? sanitize_key( wp_unslash( $_GET['type'] ) ) : '';
	$where  = 'WHERE 1=1';
	$args   = array();

	if ( $status && isset( rp_tinig_status_options()[ $status ] ) ) {
		$where .= ' AND status = %s';
		$args[] = $status;
	}

	if ( $type && isset( rp_tinig_feedback_type_options()[ $type ] ) ) {
		$where .= ' AND feedback_type = %s';
		$args[] = $type;
	}

	$sql = "SELECT * FROM $table $where ORDER BY submitted_at DESC LIMIT 200";
	$cases = $args ? $wpdb->get_results( $wpdb->prepare( $sql, $args ) ) : $wpdb->get_results( $sql );
	$statuses = rp_tinig_status_options();
	$types = rp_tinig_feedback_type_options();
	$export_url = wp_nonce_url( admin_url( 'admin-post.php?action=rp_tinig_export_cases' ), 'rp_tinig_export' );

	ob_start();
	?>
	<div class="rp-tinig-dashboard">
		<div class="rp-dashboard-header">
			<h2 class="rp-dashboard-title"><?php esc_html_e( 'Tinig Dashboard', 'rp-resource-hub' ); ?></h2>
			<p class="rp-dashboard-subtitle"><?php esc_html_e( 'Restricted case-management view for ACCORD feedback and accountability submissions.', 'rp-resource-hub' ); ?></p>
		</div>
		<form class="rp-user-mgmt-controls" method="get">
			<div class="rp-field">
				<label for="status"><?php esc_html_e( 'Status', 'rp-resource-hub' ); ?></label>
				<select id="status" name="status">
					<option value=""><?php esc_html_e( 'All statuses', 'rp-resource-hub' ); ?></option>
					<?php foreach ( $statuses as $value => $label ) : ?>
						<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $status, $value ); ?>><?php echo esc_html( $label ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="rp-field">
				<label for="type"><?php esc_html_e( 'Type', 'rp-resource-hub' ); ?></label>
				<select id="type" name="type">
					<option value=""><?php esc_html_e( 'All types', 'rp-resource-hub' ); ?></option>
					<?php foreach ( $types as $value => $label ) : ?>
						<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $type, $value ); ?>><?php echo esc_html( $label ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<button class="rp-button" type="submit"><?php esc_html_e( 'Filter', 'rp-resource-hub' ); ?></button>
			<a class="rp-button rp-button-secondary" href="<?php echo esc_url( $export_url ); ?>"><?php esc_html_e( 'Export CSV', 'rp-resource-hub' ); ?></a>
		</form>
		<div class="rp-moderation-container">
			<div class="rp-table-responsive">
				<table class="rp-moderation-table rp-tinig-table">
					<thead>
						<tr>
							<th><?php esc_html_e( 'Reference', 'rp-resource-hub' ); ?></th>
							<th><?php esc_html_e( 'Type', 'rp-resource-hub' ); ?></th>
							<th><?php esc_html_e( 'Status', 'rp-resource-hub' ); ?></th>
							<th><?php esc_html_e( 'Submitted', 'rp-resource-hub' ); ?></th>
							<th><?php esc_html_e( 'Flags', 'rp-resource-hub' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php if ( $cases ) : ?>
							<?php foreach ( $cases as $case ) : ?>
								<tr>
									<td><strong><a href="<?php echo esc_url( add_query_arg( 'case', absint( $case->id ), home_url( '/tinig-dashboard/' ) ) ); ?>"><?php echo esc_html( $case->reference_code ); ?></a></strong><br><span><?php echo esc_html( $case->subject ? $case->subject : wp_trim_words( wp_strip_all_tags( $case->message ), 8 ) ); ?></span></td>
									<td><?php echo esc_html( isset( $types[ $case->feedback_type ] ) ? $types[ $case->feedback_type ] : $case->feedback_type ); ?></td>
									<td><span class="rp-status-badge rp-tinig-status-<?php echo esc_attr( $case->status ); ?>"><?php echo esc_html( isset( $statuses[ $case->status ] ) ? $statuses[ $case->status ] : $case->status ); ?></span></td>
									<td><?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $case->submitted_at ) ) ); ?></td>
									<td>
										<?php if ( $case->is_sensitive ) : ?><span class="rp-role-badge rp-role-badge-editor"><?php esc_html_e( 'Sensitive', 'rp-resource-hub' ); ?></span><?php endif; ?>
										<?php if ( $case->is_anonymous ) : ?><span class="rp-role-badge rp-role-badge-subscriber"><?php esc_html_e( 'Anonymous', 'rp-resource-hub' ); ?></span><?php endif; ?>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php else : ?>
							<tr><td colspan="5"><?php esc_html_e( 'No Tinig cases found.', 'rp-resource-hub' ); ?></td></tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<?php
	return ob_get_clean();
}

function rp_tinig_render_case_detail( $case_id ) {
	global $wpdb;
	$case = rp_tinig_get_case( $case_id );
	if ( ! $case ) {
		return '<div class="rp-empty-state"><p>' . esc_html__( 'Tinig case not found.', 'rp-resource-hub' ) . '</p></div>';
	}

	$notes_table = $wpdb->prefix . 'rp_tinig_case_notes';
	$notes = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $notes_table WHERE case_id = %d ORDER BY created_at DESC", $case_id ) );
	$statuses = rp_tinig_status_options();
	$types = rp_tinig_feedback_type_options();
	$attachments = json_decode( (string) $case->attachment_ids, true );
	$attachments = is_array( $attachments ) ? array_map( 'absint', $attachments ) : array();

	ob_start();
	?>
	<div class="rp-tinig-case-detail">
		<p><a href="<?php echo esc_url( home_url( '/tinig-dashboard/' ) ); ?>">&larr; <?php esc_html_e( 'Back to Tinig Dashboard', 'rp-resource-hub' ); ?></a></p>
		<div class="rp-moderation-container">
			<div class="rp-dashboard-header">
				<h2 class="rp-dashboard-title"><?php echo esc_html( $case->reference_code ); ?></h2>
				<p class="rp-dashboard-subtitle"><?php echo esc_html( isset( $types[ $case->feedback_type ] ) ? $types[ $case->feedback_type ] : $case->feedback_type ); ?> &middot; <?php echo esc_html( $case->submitted_at ); ?></p>
			</div>
			<div class="rp-tinig-detail-grid">
				<section>
					<h3><?php esc_html_e( 'Case Details', 'rp-resource-hub' ); ?></h3>
					<p><strong><?php esc_html_e( 'Subject:', 'rp-resource-hub' ); ?></strong> <?php echo esc_html( $case->subject ? $case->subject : __( 'No subject provided', 'rp-resource-hub' ) ); ?></p>
					<p><strong><?php esc_html_e( 'Location:', 'rp-resource-hub' ); ?></strong> <?php echo esc_html( $case->location ? $case->location : __( 'Not provided', 'rp-resource-hub' ) ); ?></p>
					<p><strong><?php esc_html_e( 'Program:', 'rp-resource-hub' ); ?></strong> <?php echo esc_html( $case->program ? $case->program : __( 'Not provided', 'rp-resource-hub' ) ); ?></p>
					<div class="rp-tinig-message"><?php echo wpautop( wp_kses_post( $case->message ) ); ?></div>
				</section>
				<aside>
					<h3><?php esc_html_e( 'Contact & Flags', 'rp-resource-hub' ); ?></h3>
					<p><strong><?php esc_html_e( 'Anonymous:', 'rp-resource-hub' ); ?></strong> <?php echo esc_html( $case->is_anonymous ? __( 'Yes', 'rp-resource-hub' ) : __( 'No', 'rp-resource-hub' ) ); ?></p>
					<p><strong><?php esc_html_e( 'Safe to contact:', 'rp-resource-hub' ); ?></strong> <?php echo esc_html( $case->safe_to_contact ? __( 'Yes', 'rp-resource-hub' ) : __( 'No', 'rp-resource-hub' ) ); ?></p>
					<?php if ( ! $case->is_anonymous ) : ?>
						<p><strong><?php esc_html_e( 'Name:', 'rp-resource-hub' ); ?></strong> <?php echo esc_html( $case->contact_name ? $case->contact_name : __( 'Not provided', 'rp-resource-hub' ) ); ?></p>
						<p><strong><?php esc_html_e( 'Email:', 'rp-resource-hub' ); ?></strong> <?php echo esc_html( $case->contact_email ? $case->contact_email : __( 'Not provided', 'rp-resource-hub' ) ); ?></p>
						<p><strong><?php esc_html_e( 'Phone:', 'rp-resource-hub' ); ?></strong> <?php echo esc_html( $case->contact_phone ? $case->contact_phone : __( 'Not provided', 'rp-resource-hub' ) ); ?></p>
					<?php endif; ?>
					<p><strong><?php esc_html_e( 'Urgency:', 'rp-resource-hub' ); ?></strong> <?php echo esc_html( $case->urgency ); ?></p>
					<p><strong><?php esc_html_e( 'Sensitive:', 'rp-resource-hub' ); ?></strong> <?php echo esc_html( $case->is_sensitive ? __( 'Yes', 'rp-resource-hub' ) : __( 'No', 'rp-resource-hub' ) ); ?></p>
				</aside>
			</div>
			<section>
				<h3><?php esc_html_e( 'Evidence Files', 'rp-resource-hub' ); ?></h3>
				<?php if ( $attachments ) : ?>
					<ul class="rp-tinig-attachments">
						<?php foreach ( $attachments as $attachment_id ) : ?>
							<?php
							$url = wp_nonce_url(
								add_query_arg(
									array(
										'action'        => 'rp_tinig_download_attachment',
										'case_id'       => $case_id,
										'attachment_id' => $attachment_id,
									),
									admin_url( 'admin-post.php' )
								),
								'rp_tinig_download_' . $case_id . '_' . $attachment_id
							);
							?>
							<li><a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( get_the_title( $attachment_id ) ? get_the_title( $attachment_id ) : basename( get_attached_file( $attachment_id ) ) ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				<?php else : ?>
					<p><?php esc_html_e( 'No evidence files were attached.', 'rp-resource-hub' ); ?></p>
				<?php endif; ?>
			</section>
			<section>
				<h3><?php esc_html_e( 'Update Case', 'rp-resource-hub' ); ?></h3>
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="rp-tinig-update-form">
					<input type="hidden" name="action" value="rp_tinig_update_case">
					<input type="hidden" name="case_id" value="<?php echo esc_attr( $case_id ); ?>">
					<?php wp_nonce_field( 'rp_tinig_update_case_' . $case_id, 'rp_tinig_case_nonce' ); ?>
					<div class="rp-field">
						<label for="rp_tinig_status"><?php esc_html_e( 'Status', 'rp-resource-hub' ); ?></label>
						<select id="rp_tinig_status" name="rp_tinig_status">
							<?php foreach ( $statuses as $value => $label ) : ?>
								<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $case->status, $value ); ?>><?php echo esc_html( $label ); ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="rp-field">
						<label for="rp_tinig_note"><?php esc_html_e( 'Internal note', 'rp-resource-hub' ); ?></label>
						<textarea id="rp_tinig_note" name="rp_tinig_note" rows="4"></textarea>
					</div>
					<div class="rp-field">
						<label for="rp_tinig_resolution"><?php esc_html_e( 'Resolution summary', 'rp-resource-hub' ); ?></label>
						<textarea id="rp_tinig_resolution" name="rp_tinig_resolution" rows="4"><?php echo esc_textarea( $case->resolution_summary ); ?></textarea>
					</div>
					<button class="rp-button" type="submit"><?php esc_html_e( 'Save Case Update', 'rp-resource-hub' ); ?></button>
				</form>
			</section>
			<section>
				<h3><?php esc_html_e( 'Case Notes', 'rp-resource-hub' ); ?></h3>
				<?php if ( $notes ) : ?>
					<ol class="rp-tinig-notes">
						<?php foreach ( $notes as $note ) : ?>
							<li>
								<strong><?php echo esc_html( $note->created_at ); ?></strong>
								<?php if ( $note->old_status !== $note->new_status && $note->new_status ) : ?>
									<span><?php echo esc_html( sprintf( __( 'Status: %1$s to %2$s', 'rp-resource-hub' ), $note->old_status ? $note->old_status : __( 'none', 'rp-resource-hub' ), $note->new_status ) ); ?></span>
								<?php endif; ?>
								<div><?php echo wpautop( wp_kses_post( $note->note ) ); ?></div>
							</li>
						<?php endforeach; ?>
					</ol>
				<?php else : ?>
					<p><?php esc_html_e( 'No notes yet.', 'rp-resource-hub' ); ?></p>
				<?php endif; ?>
			</section>
		</div>
	</div>
	<?php
	return ob_get_clean();
}

function rp_resource_hub_add_query_vars( $vars ) {
	$vars[] = 'rp_resource_download';
	$vars[] = 'rp_secure_file';
	return $vars;
}
add_filter( 'query_vars', 'rp_resource_hub_add_query_vars' );

function rp_resource_hub_handle_download() {
	$post_id = absint( get_query_var( 'rp_resource_download' ) );
	if ( ! $post_id ) {
		return;
	}

	if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'rp_resource_download_' . $post_id ) ) {
		wp_die( esc_html__( 'The download link has expired. Please return to the catalog and try again.', 'rp-resource-hub' ), '', array( 'response' => 403 ) );
	}

	$post = get_post( $post_id );
	if ( ! $post || 'publish' !== $post->post_status || ! in_array( $post->post_type, array( 'accord_library', 'partner_resources', 'rp_sitrep' ), true ) ) {
		wp_die( esc_html__( 'Resource not found.', 'rp-resource-hub' ), '', array( 'response' => 404 ) );
	}

	if ( rp_resource_hub_is_member_only( $post_id ) && ! current_user_can( 'read_member_resources' ) ) {
		auth_redirect();
	}

	$file_id = absint( get_post_meta( $post_id, '_rp_resource_file_id', true ) );
	$path    = $file_id ? get_attached_file( $file_id ) : '';

	if ( ! $path || ! is_readable( $path ) ) {
		wp_die( esc_html__( 'The resource file is unavailable.', 'rp-resource-hub' ), '', array( 'response' => 404 ) );
	}

	$upload_dir = wp_get_upload_dir();
	$real_path  = realpath( $path );
	$base_path  = realpath( $upload_dir['basedir'] );

	if ( ! $real_path || ! $base_path || 0 !== strpos( $real_path, $base_path ) ) {
		wp_die( esc_html__( 'Invalid resource file.', 'rp-resource-hub' ), '', array( 'response' => 403 ) );
	}

	$mime_type   = get_post_mime_type( $file_id );
	$filename    = str_replace( array( "\r", "\n", '"' ), '', basename( $real_path ) );
	$disposition = ( 'text/html' === $mime_type ) ? 'inline' : 'attachment';

	// Log the download event
	rp_resource_hub_log_download( $post_id );

	nocache_headers();
	header( 'Content-Type: ' . ( $mime_type ? $mime_type : 'application/octet-stream' ) );
	header( 'Content-Disposition: ' . $disposition . '; filename="' . $filename . '"' );
	header( 'Content-Length: ' . filesize( $real_path ) );
	readfile( $real_path );
	exit;
}
add_action( 'template_redirect', 'rp_resource_hub_handle_download' );

/**
 * Intercepts requests for files in /rp-secure/ and enforces visibility rules.
 */
function rp_secure_download_handler() {
	$file_name = get_query_var( 'rp_secure_file' );
	if ( ! $file_name ) {
		return;
	}

	// Sanitize file name to prevent directory traversal
	$file_name = basename( sanitize_file_name( $file_name ) );

	$upload_dir = wp_get_upload_dir();
	$file_path  = $upload_dir['basedir'] . '/rp-secure/' . $file_name;

	if ( ! file_exists( $file_path ) || ! is_readable( $file_path ) ) {
		wp_die( esc_html__( 'File not found.', 'rp-resource-hub' ), '', array( 'response' => 404 ) );
	}

	// Query attachment references
	global $wpdb;
	$attachment_id = $wpdb->get_var( $wpdb->prepare(
		"SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_wp_attached_file' AND meta_value = %s LIMIT 1",
		'rp-secure/' . $file_name
	) );

	if ( ! $attachment_id ) {
		wp_die( esc_html__( 'Resource reference not found.', 'rp-resource-hub' ), '', array( 'response' => 404 ) );
	}

	// Find parent resource post
	$parent_post_id = wp_get_post_parent_id( $attachment_id );
	if ( ! $parent_post_id ) {
		// Fallback check meta key
		$parent_post_id = $wpdb->get_var( $wpdb->prepare(
			"SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_rp_resource_file_id' AND meta_value = %s LIMIT 1",
			$attachment_id
		) );
	}

	if ( ! $parent_post_id ) {
		wp_die( esc_html__( 'Parent resource not found.', 'rp-resource-hub' ), '', array( 'response' => 404 ) );
	}

	// Access check: visibility
	$is_member = rp_resource_hub_is_member_only( $parent_post_id );

	if ( $is_member && ! current_user_can( 'read_member_resources' ) ) {
		$login_url = home_url( '/portal-entry/' );
		$redirect  = add_query_arg( 'redirect_to', esc_url( home_url( $_SERVER['REQUEST_URI'] ) ), $login_url );
		wp_safe_redirect( $redirect );
		exit;
	}

	// Serve the file safely
	$mime_type   = get_post_mime_type( $attachment_id );
	$disposition = ( 'text/html' === $mime_type ) ? 'inline' : 'attachment';

	// Log the download event
	rp_resource_hub_log_download( $parent_post_id );

	nocache_headers();
	header( 'Content-Type: ' . ( $mime_type ? $mime_type : 'application/octet-stream' ) );
	header( 'Content-Disposition: ' . $disposition . '; filename="' . basename( $file_path ) . '"' );
	header( 'Content-Length: ' . filesize( $file_path ) );
	readfile( $file_path );
	exit;
}
add_action( 'template_redirect', 'rp_secure_download_handler' );

function rp_resource_hub_get_catalog_query( $params ) {
	$paged        = isset( $params['paged'] ) ? max( 1, absint( $params['paged'] ) ) : 1;
	$limit        = isset( $params['limit'] ) ? max( 1, min( 24, absint( $params['limit'] ) ) ) : 12;
	$search_query = isset( $params['q'] ) ? sanitize_text_field( wp_unslash( $params['q'] ) ) : '';
	$search_query = substr( $search_query, 0, 120 );

	$tax_query = array( 'relation' => 'AND' );
	$filter_taxonomies = array( 'resource_format', 'resource_category', 'hazard_type', 'target_audience', 'contributing_org' );

	foreach ( $filter_taxonomies as $taxonomy ) {
		$term_id = isset( $params[ $taxonomy ] ) ? absint( $params[ $taxonomy ] ) : 0;
		if ( $term_id ) {
			$tax_query[] = array(
				'taxonomy' => $taxonomy,
				'field'    => 'term_id',
				'terms'    => array( $term_id ),
			);
		}
	}

	if ( ! current_user_can( 'read_member_resources' ) ) {
		$member_term = get_term_by( 'name', 'Member Only', 'resource_visibility' );
		if ( $member_term ) {
			$tax_query[] = array(
				'taxonomy' => 'resource_visibility',
				'field'    => 'term_id',
				'terms'    => array( absint( $member_term->term_id ) ),
				'operator' => 'NOT IN',
			);
		}
	}

	$query_args = array(
		'post_type'           => array( 'accord_library', 'partner_resources' ),
		'post_status'         => 'publish',
		'posts_per_page'      => $limit,
		'paged'               => $paged,
		'ignore_sticky_posts' => true,
		's'                   => $search_query,
	);

	if ( count( $tax_query ) > 1 ) {
		$query_args['tax_query'] = $tax_query;
	}

	return new WP_Query( $query_args );
}

function rp_resource_hub_render_grid_items( $resources ) {
	ob_start();
	if ( $resources->have_posts() ) :
		while ( $resources->have_posts() ) :
			$resources->the_post();
			$post_id      = get_the_ID();
			$file_id      = absint( get_post_meta( $post_id, '_rp_resource_file_id', true ) );
			$is_web_app   = get_post_meta( $post_id, '_rp_is_web_app', true ) || has_term( 'Web Application', 'resource_format', $post_id );
			$download_url = $file_id ? rp_resource_hub_download_url( $post_id ) : '';
			$is_member    = rp_resource_hub_is_member_only( $post_id );
			$can_download = ! $is_member || current_user_can( 'read_member_resources' );
			?>
			<article class="rp-resource-card">
				<p class="rp-resource-type"><?php echo esc_html( 'accord_library' === get_post_type() ? __( 'ACCORD Library', 'rp-resource-hub' ) : __( 'Partner Resource', 'rp-resource-hub' ) ); ?></p>
				<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
				<div class="rp-resource-meta"><?php echo esc_html( get_the_date() ); ?></div>
				<?php the_excerpt(); ?>
				<a class="rp-resource-readmore" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read more', 'rp-resource-hub' ); ?></a>
				<div class="rp-card-spacer"></div>
				<?php if ( $is_web_app && $can_download ) : ?>
					<?php 
					$web_app_url = rp_resource_hub_get_web_app_url( $post_id ); 
					$btn_url = $web_app_url ? $web_app_url : $download_url;
					$target = $web_app_url ? ' target="_blank"' : '';
					if ( $btn_url ) :
					?>
						<a class="rp-button rp-resource-download" href="<?php echo esc_url( $btn_url ); ?>"<?php echo $target; ?>><?php esc_html_e( 'Launch', 'rp-resource-hub' ); ?></a>
					<?php endif; ?>
				<?php elseif ( $download_url && $can_download ) : ?>
					<a class="rp-button rp-resource-download" href="<?php echo esc_url( $download_url ); ?>"><?php esc_html_e( 'Download', 'rp-resource-hub' ); ?></a>
				<?php elseif ( $is_member ) : ?>
					<span class="rp-resource-locked"><?php esc_html_e( 'Member-only resource', 'rp-resource-hub' ); ?></span>
				<?php endif; ?>
			</article>
			<?php
		endwhile;
		wp_reset_postdata();
	else :
		?>
		<p><?php esc_html_e( 'No resources found.', 'rp-resource-hub' ); ?></p>
		<?php
	endif;
	return ob_get_clean();
}

function rp_resource_hub_render_pagination( $resources, $paged ) {
	if ( $resources->max_num_pages <= 1 ) {
		return '';
	}
	ob_start();
	?>
	<nav class="rp-pagination" aria-label="<?php esc_attr_e( 'Resource pagination', 'rp-resource-hub' ); ?>">
		<?php
		echo wp_kses_post(
			paginate_links(
				array(
					'base'      => esc_url_raw( add_query_arg( 'rp_page', '%#%' ) ),
					'format'    => '',
					'current'   => $paged,
					'total'     => $resources->max_num_pages,
					'type'      => 'list',
					'prev_text' => __( 'Previous', 'rp-resource-hub' ),
					'next_text' => __( 'Next', 'rp-resource-hub' ),
				)
			)
		);
		?>
	</nav>
	<?php
	return ob_get_clean();
}

function rp_resource_hub_catalog_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'limit'   => 12,
			'filters' => 'true',
		),
		$atts,
		'rp_resource_catalog'
	);

	$limit        = max( 1, min( 24, absint( $atts['limit'] ) ) );
	$show_filters = 'false' !== strtolower( $atts['filters'] );
	$paged        = $show_filters && isset( $_GET['rp_page'] ) ? max( 1, absint( $_GET['rp_page'] ) ) : 1;
	$filter_taxonomies = array( 'resource_format', 'resource_category', 'hazard_type', 'target_audience', 'contributing_org' );
	$search_query = isset( $_GET['rp_q'] ) ? sanitize_text_field( wp_unslash( $_GET['rp_q'] ) ) : '';
	$search_query = substr( $search_query, 0, 120 );

	$params = array(
		'paged' => $paged,
		'limit' => $limit,
		'q'     => $search_query,
	);
	foreach ( $filter_taxonomies as $taxonomy ) {
		$params[ $taxonomy ] = isset( $_GET[ 'rp_' . $taxonomy ] ) ? absint( $_GET[ 'rp_' . $taxonomy ] ) : 0;
	}

	$resources = rp_resource_hub_get_catalog_query( $params );
	if ( $search_query && 1 === $paged ) {
		rp_resource_hub_log_search( $search_query, $resources->found_posts );
	}

	ob_start();
	?>
	<div class="<?php echo esc_attr( $show_filters ? 'rp-catalog' : 'rp-catalog rp-catalog-no-filters' ); ?>">
		<?php if ( $show_filters ) : ?>
			<form class="rp-catalog-filters" method="get">
				<div class="rp-field">
					<label for="rp_q"><?php esc_html_e( 'Search resources', 'rp-resource-hub' ); ?></label>
					<input id="rp_q" name="rp_q" type="search" value="<?php echo esc_attr( $search_query ); ?>">
				</div>
				<?php foreach ( $filter_taxonomies as $taxonomy ) : ?>
					<?php
					$taxonomy_object = get_taxonomy( $taxonomy );
					if ( ! $taxonomy_object ) {
						continue;
					}
					$selected = $params[ $taxonomy ];
					?>
					<div class="rp-field">
						<label for="<?php echo esc_attr( 'rp_' . $taxonomy ); ?>"><?php echo esc_html( $taxonomy_object->labels->singular_name ); ?></label>
						<select id="<?php echo esc_attr( 'rp_' . $taxonomy ); ?>" name="<?php echo esc_attr( 'rp_' . $taxonomy ); ?>">
							<option value="0"><?php esc_html_e( 'All', 'rp-resource-hub' ); ?></option>
							<?php
							$terms = get_terms(
								array(
									'taxonomy'   => $taxonomy,
									'hide_empty' => true,
								)
							);
							if ( ! is_wp_error( $terms ) ) :
								foreach ( $terms as $term ) :
									?>
									<option value="<?php echo absint( $term->term_id ); ?>" <?php selected( $selected, $term->term_id ); ?>><?php echo esc_html( $term->name ); ?></option>
									<?php
								endforeach;
							endif;
							?>
						</select>
					</div>
				<?php endforeach; ?>
				<button class="rp-catalog-submit" type="submit"><?php esc_html_e( 'Filter', 'rp-resource-hub' ); ?></button>
			</form>
		<?php endif; ?>

		<div class="rp-resource-grid-wrapper" style="position: relative;">
			<div class="rp-resource-grid">
				<?php echo rp_resource_hub_render_grid_items( $resources ); ?>
			</div>
			<?php if ( $show_filters ) : ?>
				<div class="rp-pagination-wrapper">
					<?php echo rp_resource_hub_render_pagination( $resources, $paged ); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'rp_resource_catalog', 'rp_resource_hub_catalog_shortcode' );

function rp_ajax_filter_resources() {
	$filter_taxonomies = array( 'resource_format', 'resource_category', 'hazard_type', 'target_audience', 'contributing_org' );
	$params = array(
		'paged' => isset( $_POST['paged'] ) ? absint( $_POST['paged'] ) : 1,
		'limit' => isset( $_POST['limit'] ) ? absint( $_POST['limit'] ) : 12,
		'q'     => isset( $_POST['q'] ) ? sanitize_text_field( wp_unslash( $_POST['q'] ) ) : '',
	);
	foreach ( $filter_taxonomies as $taxonomy ) {
		$params[ $taxonomy ] = isset( $_POST[ $taxonomy ] ) ? absint( $_POST[ $taxonomy ] ) : 0;
	}

	$resources = rp_resource_hub_get_catalog_query( $params );
	if ( $params['q'] && 1 === $params['paged'] && ! empty( $_POST['track_search'] ) ) {
		rp_resource_hub_log_search( $params['q'], $resources->found_posts );
	}

	$grid_html       = rp_resource_hub_render_grid_items( $resources );
	$pagination_html = rp_resource_hub_render_pagination( $resources, $params['paged'] );

	wp_send_json_success( array(
		'grid'       => $grid_html,
		'pagination' => $pagination_html,
	) );
}
add_action( 'wp_ajax_rp_filter_resources', 'rp_ajax_filter_resources' );
add_action( 'wp_ajax_nopriv_rp_filter_resources', 'rp_ajax_filter_resources' );

/**
 * Handle extraction of web apps (.zip files) upon publishing.
 */
function rp_resource_hub_extract_web_app( $new_status, $old_status, $post ) {
	if ( ! in_array( $post->post_type, array( 'accord_library', 'partner_resources' ), true ) ) {
		return;
	}

	if ( 'publish' !== $new_status || 'publish' === $old_status ) {
		return;
	}

	$file_id = absint( get_post_meta( $post->ID, '_rp_resource_file_id', true ) );
	if ( ! $file_id ) {
		return;
	}

	$mime_type = get_post_mime_type( $file_id );
	if ( 'application/zip' !== $mime_type ) {
		return;
	}

	if ( ! has_term( 'Web Application', 'resource_format', $post->ID ) ) {
		return;
	}

	$zip_path = get_attached_file( $file_id );
	if ( ! $zip_path || ! file_exists( $zip_path ) ) {
		return;
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	WP_Filesystem();
	global $wp_filesystem;

	$upload_dir = wp_get_upload_dir();
	$extract_to = $upload_dir['basedir'] . '/rp-web-apps/' . $post->ID . '/';

	// Clean up old extraction if any
	if ( $wp_filesystem->exists( $extract_to ) ) {
		$wp_filesystem->delete( $extract_to, true );
	}

	$wp_filesystem->mkdir( $extract_to );

	$result = unzip_file( $zip_path, $extract_to );

	if ( is_wp_error( $result ) ) {
		// Log error or set notice
		return;
	}

	update_post_meta( $post->ID, '_rp_is_web_app', true );
}
add_action( 'transition_post_status', 'rp_resource_hub_extract_web_app', 10, 3 );

/**
 * Cleanup extracted web app when resource is deleted or trashed.
 */
function rp_resource_hub_cleanup_web_app( $post_id ) {
	if ( ! in_array( get_post_type( $post_id ), array( 'accord_library', 'partner_resources' ), true ) ) {
		return;
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	WP_Filesystem();
	global $wp_filesystem;

	$upload_dir = wp_get_upload_dir();
	$extract_to = $upload_dir['basedir'] . '/rp-web-apps/' . $post_id . '/';

	if ( $wp_filesystem->exists( $extract_to ) ) {
		$wp_filesystem->delete( $extract_to, true );
	}

	delete_post_meta( $post_id, '_rp_is_web_app' );
}
add_action( 'before_delete_post', 'rp_resource_hub_cleanup_web_app' );
add_action( 'trash_post', 'rp_resource_hub_cleanup_web_app' );

/**
 * Get the direct URL to the extracted web app's index.html.
 */
function rp_resource_hub_get_web_app_url( $post_id ) {
	$is_web_app = get_post_meta( $post_id, '_rp_is_web_app', true ) || has_term( 'Web Application', 'resource_format', $post_id );
	if ( ! $is_web_app ) {
		return false;
	}

	$upload_dir = wp_get_upload_dir();
	$base_url   = $upload_dir['baseurl'] . '/rp-web-apps/' . $post_id . '/';
	$base_dir   = $upload_dir['basedir'] . '/rp-web-apps/' . $post_id . '/';

	// Try to find index.html at root
	if ( file_exists( $base_dir . 'index.html' ) ) {
		return $base_url . 'index.html';
	}

	// Sometimes zips contain a single root folder, let's check one level deep
	$dirs = glob( $base_dir . '*', GLOB_ONLYDIR );
	if ( ! empty( $dirs ) ) {
		$sub_dir = basename( $dirs[0] );
		if ( file_exists( $base_dir . $sub_dir . '/index.html' ) ) {
			return $base_url . $sub_dir . '/index.html';
		}
	}

	return false;
}

/**
 * Shortcode to submit a Situation Report from the frontend.
 */
function rp_resource_hub_submit_sitrep_shortcode() {
	ob_start();

	if ( ! is_user_logged_in() || ! ( current_user_can( 'edit_rp_sitreps' ) || current_user_can( 'manage_options' ) ) ) {
		echo '<div class="rp-notice rp-notice-error">' . esc_html__( 'Please log in with a partner contributor account to submit Situation Reports.', 'rp-resource-hub' ) . '</div>';
		return ob_get_clean();
	}

	$notice = rp_resource_hub_get_upload_notice();
	if ( $notice ) {
		$notice_class = 'success' === $notice['type'] ? 'rp-notice-success' : 'rp-notice-error';
		echo '<div class="rp-notice ' . esc_attr( $notice_class ) . '">' . esc_html( $notice['message'] ) . '</div>';
	}

	$edit_id            = isset( $_GET['sitrep_id'] ) ? absint( $_GET['sitrep_id'] ) : 0;
	$edit_post          = $edit_id ? get_post( $edit_id ) : null;
	$existing_locations = array();
	$selected_terms     = array();

	if ( $edit_id ) {
		if ( ! $edit_post || 'rp_sitrep' !== $edit_post->post_type || ! current_user_can( 'edit_post', $edit_id ) ) {
			echo '<div class="rp-notice rp-notice-error">' . esc_html__( 'You do not have permission to edit this situation report.', 'rp-resource-hub' ) . '</div>';
			return ob_get_clean();
		}

		global $wpdb;
		$existing_locations = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}rp_sitrep_locations WHERE sitrep_id = %d ORDER BY id ASC",
				$edit_id
			),
			ARRAY_A
		);
		$selected_terms = wp_get_object_terms( $edit_id, 'hazard_type', array( 'fields' => 'ids' ) );
		if ( is_wp_error( $selected_terms ) ) {
			$selected_terms = array();
		}
	}

	if ( empty( $existing_locations ) ) {
		$existing_locations = array( array() );
	}

	$form_title       = $edit_post ? $edit_post->post_title : '';
	$form_description = $edit_post ? $edit_post->post_content : '';
	$form_incident_id = $edit_post ? absint( get_post_meta( $edit_id, '_sitrep_incident_id', true ) ) : 0;
	?>
	<form class="rp-upload-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" enctype="multipart/form-data">
		<input type="hidden" name="action" value="rp_sitrep_upload">
		<?php if ( $edit_id ) : ?>
			<input type="hidden" name="rp_sitrep_id" value="<?php echo absint( $edit_id ); ?>">
		<?php endif; ?>
		<?php wp_nonce_field( 'rp_sitrep_upload', 'rp_sitrep_upload_nonce' ); ?>
		
		<div class="rp-field">
			<label for="rp_title"><?php esc_html_e( 'Report Title', 'rp-resource-hub' ); ?> <span class="rp-required-star" style="color: #ef4444;">*</span></label>
			<input id="rp_title" name="rp_title" type="text" required placeholder="e.g. Typhoon Pepito - Contributor SitRep #1" maxlength="160" value="<?php echo esc_attr( $form_title ); ?>">
		</div>

		<div class="rp-field">
			<label for="rp_incident_id"><?php esc_html_e( 'Crisis Incident', 'rp-resource-hub' ); ?> <span class="rp-required-star" style="color: #ef4444;">*</span></label>
			<select id="rp_incident_id" name="rp_incident_id" required style="width: 100%; padding: 10px; border-radius: 4px; border: 1px solid #cbd5e1; background-color: #fff;">
				<option value=""><?php esc_html_e( 'Select an active incident...', 'rp-resource-hub' ); ?></option>
				<?php
				$incidents = get_posts( array(
					'post_type'      => 'rp_incident',
					'post_status'    => 'publish',
					'posts_per_page' => -1,
					'orderby'        => 'title',
					'order'          => 'ASC',
					'meta_query'     => array(
						'relation' => 'OR',
						array(
							'key'     => '_rp_incident_is_active',
							'value'   => '0',
							'compare' => '!=',
						),
						array(
							'key'     => '_rp_incident_is_active',
							'compare' => 'NOT EXISTS',
						),
					),
				) );
				foreach ( $incidents as $inc ) {
					printf( '<option value="%d"%s>%s</option>', absint( $inc->ID ), selected( $form_incident_id, $inc->ID, false ), esc_html( $inc->post_title ) );
				}
				?>
			</select>
		</div>

		<div class="rp-field">
			<label for="rp_description"><?php esc_html_e( 'Situation Summary', 'rp-resource-hub' ); ?> <span class="rp-required-star" style="color: #ef4444;">*</span></label>
			<textarea id="rp_description" name="rp_description" rows="5" required placeholder="Describe the current situation, main impacts, and timeline..."><?php echo esc_textarea( $form_description ); ?></textarea>
		</div>

		<fieldset class="rp-field rp-checkbox-list">
			<legend><?php esc_html_e( 'Hazard Type', 'rp-resource-hub' ); ?></legend>
			<?php rp_resource_hub_term_options( 'hazard_type', $selected_terms ); ?>
		</fieldset>

		<h3 style="margin-top: 30px; color: var(--rp-color-primary, #0f172a); border-bottom: 2px solid #e2e8f0; padding-bottom: 8px;">
			<?php esc_html_e( 'Affected Locations & Metrics', 'rp-resource-hub' ); ?>
		</h3>

		<div id="rp-locations-container">
			<?php foreach ( $existing_locations as $location_index => $location ) : ?>
			<div class="rp-location-row" data-idx="<?php echo absint( $location_index ); ?>" data-region="<?php echo esc_attr( $location['region'] ?? '' ); ?>" data-province="<?php echo esc_attr( $location['province'] ?? '' ); ?>" data-municipality="<?php echo esc_attr( $location['municipality'] ?? '' ); ?>" style="border: 1px solid #e2e8f0; padding: 20px; border-radius: 6px; margin-bottom: 20px; position: relative; background: #fafafa;">
				<?php if ( 0 < $location_index ) : ?>
					<button type="button" class="rp-remove-row-btn" style="position: absolute; top: 10px; right: 10px; background: none; border: none; color: #ef4444; font-weight: bold; cursor: pointer; font-size: 14px;">✕ <?php esc_html_e( 'Remove', 'rp-resource-hub' ); ?></button>
				<?php endif; ?>
				<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
					<div class="rp-field">
						<label>Region *</label>
						<select class="rp-loc-region" name="rp_locations[<?php echo absint( $location_index ); ?>][region]" required>
							<option value="">Loading regions...</option>
						</select>
					</div>
					<div class="rp-field">
						<label>Province *</label>
						<select class="rp-loc-province" name="rp_locations[<?php echo absint( $location_index ); ?>][province]" required>
							<option value="">Select Region first...</option>
						</select>
					</div>
					<div class="rp-field">
						<label>Municipality *</label>
						<select class="rp-loc-municipality" name="rp_locations[<?php echo absint( $location_index ); ?>][municipality]" required>
							<option value="">Select Province first...</option>
						</select>
					</div>
					<div class="rp-field">
						<label>Barangay</label>
						<input class="rp-loc-barangay" name="rp_locations[<?php echo absint( $location_index ); ?>][barangay]" type="text" placeholder="e.g. Seguinon" value="<?php echo esc_attr( $location['barangay'] ?? '' ); ?>">
					</div>
				</div>
				
				<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); gap: 15px; margin-top: 15px;">
					<div class="rp-field">
						<label>Affected Barangays</label>
						<input name="rp_locations[<?php echo absint( $location_index ); ?>][affected_barangays]" type="number" min="0" value="<?php echo absint( $location['affected_barangays'] ?? 0 ); ?>">
					</div>
					<div class="rp-field">
						<label>Affected Households</label>
						<input name="rp_locations[<?php echo absint( $location_index ); ?>][households]" type="number" min="0" value="<?php echo absint( $location['households'] ?? 0 ); ?>">
					</div>
					<div class="rp-field">
						<label>Affected Individuals</label>
						<input name="rp_locations[<?php echo absint( $location_index ); ?>][individuals]" type="number" min="0" value="<?php echo absint( $location['individuals'] ?? 0 ); ?>">
					</div>
					<div class="rp-field">
						<label>Displaced Inside EC</label>
						<input class="rp-displaced-inside" name="rp_locations[<?php echo absint( $location_index ); ?>][displaced_inside]" type="number" min="0" value="<?php echo absint( $location['displaced_inside'] ?? 0 ); ?>">
					</div>
					<div class="rp-field">
						<label>Displaced Outside EC</label>
						<input class="rp-displaced-outside" name="rp_locations[<?php echo absint( $location_index ); ?>][displaced_outside]" type="number" min="0" value="<?php echo absint( $location['displaced_outside'] ?? 0 ); ?>">
					</div>
					<div class="rp-field">
						<label>Total Displaced Indivs</label>
						<input class="rp-displaced-total" type="text" readonly value="<?php echo absint( $location['displaced_total'] ?? 0 ); ?>" style="background: #e2e8f0; font-weight: bold;">
					</div>
					<div class="rp-field">
						<label>Displaced Households</label>
						<input name="rp_locations[<?php echo absint( $location_index ); ?>][displaced_households]" type="number" min="0" value="<?php echo absint( $location['displaced_households'] ?? 0 ); ?>">
					</div>
					<div class="rp-field">
						<label>Data Source</label>
						<input name="rp_locations[<?php echo absint( $location_index ); ?>][data_source]" type="text" placeholder="e.g. MDRRMO" value="<?php echo esc_attr( $location['data_source'] ?? '' ); ?>">
					</div>
				</div>
				<div class="rp-duplicate-prompt" style="display: none;"></div>
			</div>
			<?php endforeach; ?>
		</div>

		<button type="button" id="rp-add-location-btn" style="background: #0f172a; color: #fff; padding: 8px 16px; border-radius: 4px; border: none; cursor: pointer; font-weight: 600; margin-bottom: 30px;">
			+ <?php esc_html_e( 'Add Another Location', 'rp-resource-hub' ); ?>
		</button>

		<h3 style="margin-top: 30px; color: var(--rp-color-primary, #0f172a); border-bottom: 2px solid #e2e8f0; padding-bottom: 8px;">
			<?php esc_html_e( 'Sectoral Situation Details', 'rp-resource-hub' ); ?>
		</h3>

		<div class="rp-field" style="margin-top: 15px;">
			<label for="rp_sectoral_fsl"><?php esc_html_e( 'Food Security & Livelihoods (FSL)', 'rp-resource-hub' ); ?></label>
			<textarea id="rp_sectoral_fsl" name="rp_sectoral_fsl" rows="3" placeholder="Food availability, markets status, farm damages, needed food packs..."><?php echo esc_textarea( $edit_post ? get_post_meta( $edit_id, '_sitrep_sectoral_fsl', true ) : '' ); ?></textarea>
		</div>

		<div class="rp-field" style="margin-top: 15px;">
			<label for="rp_sectoral_wash"><?php esc_html_e( 'Water, Sanitation & Hygiene (WASH)', 'rp-resource-hub' ); ?></label>
			<textarea id="rp_sectoral_wash" name="rp_sectoral_wash" rows="3" placeholder="Access to clean drinking water, sanitation facilities, hygiene kits needed..."><?php echo esc_textarea( $edit_post ? get_post_meta( $edit_id, '_sitrep_sectoral_wash', true ) : '' ); ?></textarea>
		</div>

		<div class="rp-field" style="margin-top: 15px;">
			<label for="rp_sectoral_shelter"><?php esc_html_e( 'Emergency Shelter', 'rp-resource-hub' ); ?></label>
			<textarea id="rp_sectoral_shelter" name="rp_sectoral_shelter" rows="3" placeholder="Damaged roofing, evacuation center congestion, tarpaulins needed..."><?php echo esc_textarea( $edit_post ? get_post_meta( $edit_id, '_sitrep_sectoral_shelter', true ) : '' ); ?></textarea>
		</div>

		<div class="rp-field" style="margin-top: 15px;">
			<label for="rp_sectoral_other"><?php esc_html_e( 'Other Sectors (Protection, Health, Education)', 'rp-resource-hub' ); ?></label>
			<textarea id="rp_sectoral_other" name="rp_sectoral_other" rows="3" placeholder="Health facilities status, child-friendly spaces, school damages..."><?php echo esc_textarea( $edit_post ? get_post_meta( $edit_id, '_sitrep_sectoral_other', true ) : '' ); ?></textarea>
		</div>

		<div class="rp-field" style="margin-top: 30px;">
			<label for="rp_file"><?php esc_html_e( 'Supporting Documents or Photos (Optional)', 'rp-resource-hub' ); ?></label>
			<input id="rp_file" name="rp_file" type="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
			<?php
			$sitrep_max_size_text = current_user_can( 'manage_options' ) 
				? __( 'No limit (Administrator)', 'rp-resource-hub' ) 
				: rp_resource_hub_format_bytes( RP_RESOURCE_HUB_MAX_UPLOAD_BYTES );
			?>
			<p class="rp-field-help"><?php echo esc_html( sprintf( __( 'Accepted files: PDF, Word, Excel, JPG, PNG. Maximum size: %s.', 'rp-resource-hub' ), $sitrep_max_size_text ) ); ?></p>
		</div>

		<button type="submit" style="margin-top: 30px; background: #ef4444; color: #fff; border: none; font-size: 16px; font-weight: bold; padding: 12px 24px; border-radius: 6px; cursor: pointer;">
			<?php echo esc_html( $edit_post ? __( 'Update Situation Report', 'rp-resource-hub' ) : __( 'Submit Situation Report for Verification', 'rp-resource-hub' ) ); ?>
		</button>
	</form>

	<script>
	document.addEventListener('DOMContentLoaded', function() {
		const container = document.getElementById('rp-locations-container');
		const addBtn = document.getElementById('rp-add-location-btn');
		let rowIdx = container.querySelectorAll('.rp-location-row').length;

		let geoData = {
			regions: [],
			provinces: [],
			municipalities: []
		};

		function updateRowTotals(row) {
			const insideInput = row.querySelector('.rp-displaced-inside');
			const outsideInput = row.querySelector('.rp-displaced-outside');
			const totalInput = row.querySelector('.rp-displaced-total');
			
			const inside = parseInt(insideInput.value) || 0;
			const outside = parseInt(outsideInput.value) || 0;
			totalInput.value = inside + outside;
		}

		container.addEventListener('input', function(e) {
			if (e.target.classList.contains('rp-displaced-inside') || e.target.classList.contains('rp-displaced-outside')) {
				const row = e.target.closest('.rp-location-row');
				updateRowTotals(row);
			}
		});

		container.addEventListener('change', function(e) {
			if (e.target.classList.contains('rp-loc-province') || e.target.classList.contains('rp-loc-municipality') || e.target.classList.contains('rp-loc-barangay')) {
				const row = e.target.closest('.rp-location-row');
				checkDuplicate(row);
			}
		});

		document.getElementById('rp_incident_id').addEventListener('change', function() {
			Array.from(container.querySelectorAll('.rp-location-row')).forEach(row => {
				checkDuplicate(row);
			});
		});

		function checkDuplicate(row) {
			const incidentSelect = document.getElementById('rp_incident_id');
			const provInput = row.querySelector('.rp-loc-province');
			const muniInput = row.querySelector('.rp-loc-municipality');
			const brgyInput = row.querySelector('.rp-loc-barangay');
			const promptEl = row.querySelector('.rp-duplicate-prompt');
			
			const incidentId = incidentSelect.value;
			const province = provInput.value.trim();
			const municipality = muniInput.value.trim();
			const barangay = brgyInput.value.trim();

			if (!incidentId || !province || !municipality) {
				promptEl.style.display = 'none';
				promptEl.innerHTML = '';
				return;
			}

			const formData = new FormData();
			formData.append('action', 'rp_check_location_duplicate');
			formData.append('incident_id', incidentId);
			formData.append('province', province);
			formData.append('municipality', municipality);
			formData.append('barangay', barangay);

			fetch('<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', {
				method: 'POST',
				body: formData
			})
			.then(res => res.json())
			.then(res => {
				if (res.success && res.data.exists) {
					const existing = res.data.data;
					const idx = row.dataset.idx || 0;
					promptEl.innerHTML = `
						<div style="background: #fffbeb; border: 1px solid #fde68a; color: #b45309; padding: 12px; border-radius: 6px; margin-top: 15px; font-size: 13px; grid-column: span 4; display: flex; flex-direction: column; gap: 8px;">
							<div>
								<strong>⚠️ Reported Data Already Exists for this location:</strong><br/>
								Families: ${existing.households} | Individuals: ${existing.individuals} | Displaced: ${existing.displaced_total}.
							</div>
							<div style="display: flex; gap: 15px; border-top: 1px solid #fcd34d; padding-top: 8px; margin-top: 4px;">
								<label style="font-weight: 600; cursor: pointer;">
									<input type="radio" name="rp_locations[${idx}][conflict_mode]" value="add" checked> Add to existing totals
								</label>
								<label style="font-weight: 600; cursor: pointer;">
									<input type="radio" name="rp_locations[${idx}][conflict_mode]" value="update"> Overwrite/Update existing data on approval
								</label>
							</div>
						</div>
					`;
					promptEl.style.display = 'block';
				} else {
					promptEl.style.display = 'none';
					promptEl.innerHTML = '';
				}
			})
			.catch(err => console.error(err));
		}

		function initializeRowLocations(row) {
			const regSelect = row.querySelector('.rp-loc-region');
			const provSelect = row.querySelector('.rp-loc-province');
			const muniSelect = row.querySelector('.rp-loc-municipality');
			const initialRegion = row.dataset.region || '';
			let initialProvince = row.dataset.province || '';
			let initialMunicipality = row.dataset.municipality || '';

			// Populate Region
			regSelect.innerHTML = '<option value="">Select Region...</option>';
			geoData.regions.forEach(r => {
				const opt = document.createElement('option');
				opt.value = r.region_name;
				opt.dataset.code = r.region_code;
				opt.textContent = r.region_name;
				regSelect.appendChild(opt);
			});
			regSelect.value = initialRegion || geoData.regions.find(r => r.region_name.includes('Region VIII'))?.region_name || '';

			provSelect.innerHTML = '<option value="">Select Province...</option>';
			provSelect.disabled = true;
			muniSelect.innerHTML = '<option value="">Select Municipality...</option>';
			muniSelect.disabled = true;

			function updateProvinces() {
				const selectedOpt = regSelect.options[regSelect.selectedIndex];
				const regCode = selectedOpt ? selectedOpt.dataset.code : '';

				provSelect.innerHTML = '<option value="">Select Province...</option>';
				muniSelect.innerHTML = '<option value="">Select Municipality...</option>';
				muniSelect.disabled = true;

				if (regCode) {
					const filteredProvs = geoData.provinces.filter(p => p.region_code === regCode);
					filteredProvs.sort((a, b) => a.province_name.localeCompare(b.province_name));
					filteredProvs.forEach(p => {
						const opt = document.createElement('option');
						opt.value = p.province_name;
						opt.dataset.code = p.province_code;
						opt.textContent = p.province_name;
						provSelect.appendChild(opt);
					});
					provSelect.disabled = false;
					if (initialProvince) {
						provSelect.value = initialProvince;
						initialProvince = '';
					}
				} else {
					provSelect.disabled = true;
				}
				provSelect.dispatchEvent(new Event('change'));
			}

			function updateMunicipalities() {
				const selectedOpt = provSelect.options[provSelect.selectedIndex];
				const provCode = selectedOpt ? selectedOpt.dataset.code : '';

				muniSelect.innerHTML = '<option value="">Select Municipality...</option>';

				if (provCode) {
					const filteredMunis = geoData.municipalities.filter(m => m.province_code === provCode);
					filteredMunis.sort((a, b) => a.city_name.localeCompare(b.city_name));
					filteredMunis.forEach(m => {
						const opt = document.createElement('option');
						opt.value = m.city_name;
						opt.textContent = m.city_name;
						muniSelect.appendChild(opt);
					});
					muniSelect.disabled = false;
					if (initialMunicipality) {
						muniSelect.value = initialMunicipality;
						initialMunicipality = '';
					}
				} else {
					muniSelect.disabled = true;
				}
				checkDuplicate(row);
			}

			regSelect.addEventListener('change', updateProvinces);
			provSelect.addEventListener('change', updateMunicipalities);

			// Run once initially to handle default selections (like Region VIII)
			if (regSelect.value) {
				updateProvinces();
			}
		}

		Promise.all([
			fetch('<?php echo esc_url( RP_RESOURCE_HUB_URL . "assets/regions.json" ); ?>').then(r => r.json()),
			fetch('<?php echo esc_url( RP_RESOURCE_HUB_URL . "assets/provinces.json" ); ?>').then(r => r.json()),
			fetch('<?php echo esc_url( RP_RESOURCE_HUB_URL . "assets/municipalities.json" ); ?>').then(r => r.json())
		]).then(([regions, provinces, municipalities]) => {
			geoData.regions = regions;
			geoData.provinces = provinces;
			geoData.municipalities = municipalities;

			// Initialize default row(s)
			Array.from(container.querySelectorAll('.rp-location-row')).forEach(row => {
				initializeRowLocations(row);
				const removeButton = row.querySelector('.rp-remove-row-btn');
				if (removeButton) {
					removeButton.addEventListener('click', function() {
						row.remove();
					});
				}
			});
		}).catch(err => console.error('Error loading geographic data:', err));

		addBtn.addEventListener('click', function() {
			const newRow = document.createElement('div');
			newRow.className = 'rp-location-row';
			newRow.dataset.idx = rowIdx;
			newRow.style = 'border: 1px solid #e2e8f0; padding: 20px; border-radius: 6px; margin-bottom: 20px; position: relative; background: #fafafa;';
			newRow.innerHTML = `
				<button type="button" class="rp-remove-row-btn" style="position: absolute; top: 10px; right: 10px; background: none; border: none; color: #ef4444; font-weight: bold; cursor: pointer; font-size: 14px;">✕ Remove</button>
				<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
					<div class="rp-field">
						<label>Region *</label>
						<select class="rp-loc-region" name="rp_locations[${rowIdx}][region]" required>
							<option value="">Loading regions...</option>
						</select>
					</div>
					<div class="rp-field">
						<label>Province *</label>
						<select class="rp-loc-province" name="rp_locations[${rowIdx}][province]" required>
							<option value="">Select Region first...</option>
						</select>
					</div>
					<div class="rp-field">
						<label>Municipality *</label>
						<select class="rp-loc-municipality" name="rp_locations[${rowIdx}][municipality]" required>
							<option value="">Select Province first...</option>
						</select>
					</div>
					<div class="rp-field">
						<label>Barangay</label>
						<input class="rp-loc-barangay" name="rp_locations[${rowIdx}][barangay]" type="text" placeholder="e.g. Seguinon">
					</div>
				</div>
				
				<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); gap: 15px; margin-top: 15px;">
					<div class="rp-field">
						<label>Affected Barangays</label>
						<input name="rp_locations[${rowIdx}][affected_barangays]" type="number" min="0" value="0">
					</div>
					<div class="rp-field">
						<label>Affected Households</label>
						<input name="rp_locations[${rowIdx}][households]" type="number" min="0" value="0">
					</div>
					<div class="rp-field">
						<label>Affected Individuals</label>
						<input name="rp_locations[${rowIdx}][individuals]" type="number" min="0" value="0">
					</div>
					<div class="rp-field">
						<label>Displaced Inside EC</label>
						<input class="rp-displaced-inside" name="rp_locations[${rowIdx}][displaced_inside]" type="number" min="0" value="0">
					</div>
					<div class="rp-field">
						<label>Displaced Outside EC</label>
						<input class="rp-displaced-outside" name="rp_locations[${rowIdx}][displaced_outside]" type="number" min="0" value="0">
					</div>
					<div class="rp-field">
						<label>Total Displaced Indivs</label>
						<input class="rp-displaced-total" type="text" readonly value="0" style="background: #e2e8f0; font-weight: bold;">
					</div>
					<div class="rp-field">
						<label>Displaced Households</label>
						<input name="rp_locations[${rowIdx}][displaced_households]" type="number" min="0" value="0">
					</div>
					<div class="rp-field">
						<label>Data Source</label>
						<input name="rp_locations[${rowIdx}][data_source]" type="text" placeholder="e.g. MDRRMO">
					</div>
				</div>
				<div class="rp-duplicate-prompt" style="display: none;"></div>
			`;
			container.appendChild(newRow);
			newRow.querySelector('.rp-remove-row-btn').addEventListener('click', function() {
				newRow.remove();
			});
			initializeRowLocations(newRow);
			rowIdx++;
		});
	});
	</script>
	<?php
	return ob_get_clean();
}
add_shortcode( 'rp_submit_sitrep_form', 'rp_resource_hub_submit_sitrep_shortcode' );

/**
 * Handle form submission of Situation Reports.
 */
function rp_resource_hub_process_sitrep_upload() {
	if ( 'POST' !== $_SERVER['REQUEST_METHOD'] || empty( $_POST['rp_sitrep_upload_nonce'] ) ) {
		return null;
	}

	if ( ! is_user_logged_in() || ! ( current_user_can( 'edit_rp_sitreps' ) || current_user_can( 'manage_options' ) ) ) {
		return new WP_Error( 'rp_forbidden', __( 'You do not have permission to submit situation reports.', 'rp-resource-hub' ) );
	}

	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['rp_sitrep_upload_nonce'] ) ), 'rp_sitrep_upload' ) ) {
		return new WP_Error( 'rp_nonce', __( 'Security check failed. Please refresh and try again.', 'rp-resource-hub' ) );
	}

	$title       = isset( $_POST['rp_title'] ) ? sanitize_text_field( wp_unslash( $_POST['rp_title'] ) ) : '';
	$description = isset( $_POST['rp_description'] ) ? wp_kses_post( wp_unslash( $_POST['rp_description'] ) ) : '';
	$incident_id = isset( $_POST['rp_incident_id'] ) ? absint( $_POST['rp_incident_id'] ) : 0;
	$edit_id     = isset( $_POST['rp_sitrep_id'] ) ? absint( $_POST['rp_sitrep_id'] ) : 0;
	$edit_post   = $edit_id ? get_post( $edit_id ) : null;

	if ( $edit_id && ( ! $edit_post || 'rp_sitrep' !== $edit_post->post_type || ! current_user_can( 'edit_post', $edit_id ) ) ) {
		return new WP_Error( 'rp_edit_forbidden', __( 'You do not have permission to edit this situation report.', 'rp-resource-hub' ) );
	}

	if ( '' === $title || '' === $description ) {
		return new WP_Error( 'rp_required', __( 'Title and situation summary are required.', 'rp-resource-hub' ) );
	}

	if ( ! $incident_id ) {
		return new WP_Error( 'rp_incident_required', __( 'Please select a Crisis Incident.', 'rp-resource-hub' ) );
	}

	$locations = isset( $_POST['rp_locations'] ) && is_array( $_POST['rp_locations'] ) ? wp_unslash( $_POST['rp_locations'] ) : array();
	if ( empty( $locations ) ) {
		return new WP_Error( 'rp_locations_required', __( 'Please add at least one affected location record.', 'rp-resource-hub' ) );
	}

	// Capture sectoral fields
	$sectoral_fsl     = isset( $_POST['rp_sectoral_fsl'] ) ? sanitize_textarea_field( wp_unslash( $_POST['rp_sectoral_fsl'] ) ) : '';
	$sectoral_wash    = isset( $_POST['rp_sectoral_wash'] ) ? sanitize_textarea_field( wp_unslash( $_POST['rp_sectoral_wash'] ) ) : '';
	$sectoral_shelter = isset( $_POST['rp_sectoral_shelter'] ) ? sanitize_textarea_field( wp_unslash( $_POST['rp_sectoral_shelter'] ) ) : '';
	$sectoral_other   = isset( $_POST['rp_sectoral_other'] ) ? sanitize_textarea_field( wp_unslash( $_POST['rp_sectoral_other'] ) ) : '';

	$post_data = array(
		'post_title'   => $title,
		'post_content' => $description,
	);

	if ( $edit_id ) {
		$post_data['ID'] = $edit_id;
		$post_id         = wp_update_post( $post_data, true );
	} else {
		$post_data['post_type']   = 'rp_sitrep';
		$post_data['post_status'] = 'pending';
		$post_data['post_author'] = get_current_user_id();
		$post_id                  = wp_insert_post( $post_data, true );
	}

	if ( is_wp_error( $post_id ) ) {
		return $post_id;
	}

	// Store meta data
	update_post_meta( $post_id, '_sitrep_incident_id', $incident_id );
	update_post_meta( $post_id, '_sitrep_sectoral_fsl', $sectoral_fsl );
	update_post_meta( $post_id, '_sitrep_sectoral_wash', $sectoral_wash );
	update_post_meta( $post_id, '_sitrep_sectoral_shelter', $sectoral_shelter );
	update_post_meta( $post_id, '_sitrep_sectoral_other', $sectoral_other );

	// Insert location records
	global $wpdb;
	$table_name = $wpdb->prefix . 'rp_sitrep_locations';
	if ( $edit_id ) {
		$wpdb->delete( $table_name, array( 'sitrep_id' => $post_id ), array( '%d' ) );
	}

	foreach ( $locations as $loc ) {
		$region        = isset( $loc['region'] ) ? sanitize_text_field( $loc['region'] ) : '';
		$province      = isset( $loc['province'] ) ? sanitize_text_field( $loc['province'] ) : '';
		$municipality  = isset( $loc['municipality'] ) ? sanitize_text_field( $loc['municipality'] ) : '';
		$barangay      = isset( $loc['barangay'] ) ? sanitize_text_field( $loc['barangay'] ) : '';

		if ( empty( $province ) || empty( $municipality ) ) {
			continue;
		}

		$affected_brgys  = isset( $loc['affected_barangays'] ) ? max( 0, intval( $loc['affected_barangays'] ) ) : 0;
		$households      = isset( $loc['households'] ) ? max( 0, intval( $loc['households'] ) ) : 0;
		$individuals     = isset( $loc['individuals'] ) ? max( 0, intval( $loc['individuals'] ) ) : 0;
		$inside_ec       = isset( $loc['displaced_inside'] ) ? max( 0, intval( $loc['displaced_inside'] ) ) : 0;
		$outside_ec      = isset( $loc['displaced_outside'] ) ? max( 0, intval( $loc['displaced_outside'] ) ) : 0;
		$disp_households = isset( $loc['displaced_households'] ) ? max( 0, intval( $loc['displaced_households'] ) ) : 0;
		$source          = isset( $loc['data_source'] ) ? sanitize_text_field( $loc['data_source'] ) : '';
		$conflict_mode   = isset( $loc['conflict_mode'] ) && in_array( $loc['conflict_mode'], array( 'add', 'update' ), true ) ? $loc['conflict_mode'] : 'add';

		$disp_total = $inside_ec + $outside_ec;

		$wpdb->insert(
			$table_name,
			array(
				'sitrep_id'            => $post_id,
				'incident_id'          => $incident_id,
				'region'               => $region,
				'province'             => $province,
				'municipality'         => $municipality,
				'barangay'             => $barangay,
				'affected_barangays'   => $affected_brgys,
				'households'           => $households,
				'individuals'          => $individuals,
				'displaced_inside'     => $inside_ec,
				'displaced_outside'    => $outside_ec,
				'displaced_total'      => $disp_total,
				'displaced_households' => $disp_households,
				'data_source'          => $source,
				'conflict_mode'        => $conflict_mode,
				'record_status'        => 'pending',
			),
			array( '%d', '%d', '%s', '%s', '%s', '%s', '%d', '%d', '%d', '%d', '%d', '%d', '%d', '%s', '%s', '%s' )
		);
	}

	// Handle Optional File Upload
	if ( ! empty( $_FILES['rp_file'] ) && ! empty( $_FILES['rp_file']['name'] ) ) {
		if ( empty( $_FILES['rp_file']['error'] ) ) {
			if ( current_user_can( 'manage_options' ) || empty( $_FILES['rp_file']['size'] ) || RP_RESOURCE_HUB_MAX_UPLOAD_BYTES >= (int) $_FILES['rp_file']['size'] ) {
				require_once ABSPATH . 'wp-admin/includes/file.php';
				require_once ABSPATH . 'wp-admin/includes/media.php';
				require_once ABSPATH . 'wp-admin/includes/image.php';

				$file = $_FILES['rp_file'];
				$file_type = wp_check_filetype_and_ext( $file['tmp_name'], $file['name'], rp_resource_hub_allowed_mimes() );

				if ( ! empty( $file_type['ext'] ) && ! empty( $file_type['type'] ) ) {
					$_FILES['rp_file']['name'] = sanitize_file_name( $file['name'] );

					add_filter( 'upload_dir', 'rp_resource_hub_secure_upload_dir' );
					$attachment_id = media_handle_upload( 'rp_file', $post_id );
					remove_filter( 'upload_dir', 'rp_resource_hub_secure_upload_dir' );

					if ( ! is_wp_error( $attachment_id ) ) {
						update_post_meta( $post_id, '_rp_resource_file_id', absint( $attachment_id ) );
					}
				}
			}
		}
	}

	// Store hazard types tax
	$submitted_terms = isset( $_POST['rp_terms'] ) && is_array( $_POST['rp_terms'] ) ? wp_unslash( $_POST['rp_terms'] ) : array();
	$term_ids = ! empty( $submitted_terms['hazard_type'] ) && is_array( $submitted_terms['hazard_type'] )
		? array_filter( array_map( 'absint', $submitted_terms['hazard_type'] ) )
		: array();
	wp_set_object_terms( $post_id, $term_ids, 'hazard_type', false );

	// Dispatch email
	$admin_email = get_option( 'admin_email' );
	if ( ! $edit_id && is_email( $admin_email ) ) {
		wp_mail(
			$admin_email,
			sprintf( __( 'Situation Report pending review: %s', 'rp-resource-hub' ), $title ),
			sprintf(
				"A multi-location situation report has been submitted and is pending review.\n\nTitle: %1\$s\nReview: %2\$s",
				$title,
				admin_url( 'post.php?post=' . absint( $post_id ) . '&action=edit' )
			)
		);
	}

	return $post_id;
}

function rp_resource_hub_handle_sitrep_form() {
	$is_edit = ! empty( $_POST['rp_sitrep_id'] );
	$result = rp_resource_hub_process_sitrep_upload();

	if ( is_wp_error( $result ) ) {
		$notice_key = rp_resource_hub_store_upload_notice( 'error', $result->get_error_message() );
		$redirect   = wp_get_referer() ? wp_get_referer() : home_url( '/submit-sitrep/' );

		wp_safe_redirect( add_query_arg( 'rp_upload_notice', $notice_key, $redirect ) );
		exit;
	}

	if ( ! is_numeric( $result ) ) {
		$notice_key = rp_resource_hub_store_upload_notice( 'error', __( 'Submission could not be processed. Please try again.', 'rp-resource-hub' ) );
		wp_safe_redirect( add_query_arg( 'rp_upload_notice', $notice_key, home_url( '/submit-sitrep/' ) ) );
		exit;
	}

	if ( $is_edit ) {
		wp_safe_redirect( add_query_arg( 'sitrep_updated', '1', home_url( '/moderation-dashboard/' ) ) );
		exit;
	}

	wp_safe_redirect( rp_resource_hub_success_url( absint( $result ) ) );
	exit;
}
add_action( 'admin_post_rp_sitrep_upload', 'rp_resource_hub_handle_sitrep_form' );
add_action( 'admin_post_nopriv_rp_sitrep_upload', 'rp_resource_hub_handle_upload_form_logged_out' );

/**
 * AJAX handler for checking if location data already exists for a specific incident.
 */
function rp_ajax_check_location_duplicate_handler() {
	$incident_id  = isset( $_POST['incident_id'] ) ? absint( $_POST['incident_id'] ) : 0;
	$province     = isset( $_POST['province'] ) ? sanitize_text_field( wp_unslash( $_POST['province'] ) ) : '';
	$municipality = isset( $_POST['municipality'] ) ? sanitize_text_field( wp_unslash( $_POST['municipality'] ) ) : '';
	$barangay     = isset( $_POST['barangay'] ) ? sanitize_text_field( wp_unslash( $_POST['barangay'] ) ) : '';

	if ( ! $incident_id || ! $province || ! $municipality ) {
		wp_send_json_error( array( 'message' => __( 'Missing parameters.', 'rp-resource-hub' ) ) );
	}

	global $wpdb;
	$table_name = $wpdb->prefix . 'rp_sitrep_locations';

	$existing = $wpdb->get_row( $wpdb->prepare(
		"SELECT households, individuals, displaced_inside, displaced_outside, displaced_total, displaced_households, data_source 
		 FROM $table_name 
		 WHERE incident_id = %d AND province = %s AND municipality = %s AND barangay = %s AND record_status = 'publish' 
		 ORDER BY id DESC LIMIT 1",
		$incident_id,
		$province,
		$municipality,
		$barangay
	), ARRAY_A );

	if ( $existing ) {
		wp_send_json_success( array(
			'exists' => true,
			'data'   => array(
				'households'           => intval( $existing['households'] ),
				'individuals'          => intval( $existing['individuals'] ),
				'displaced_inside'     => intval( $existing['displaced_inside'] ),
				'displaced_outside'    => intval( $existing['displaced_outside'] ),
				'displaced_total'      => intval( $existing['displaced_total'] ),
				'displaced_households' => intval( $existing['displaced_households'] ),
				'data_source'          => esc_html( $existing['data_source'] ),
			)
		) );
	} else {
		wp_send_json_success( array( 'exists' => false ) );
	}
}
add_action( 'wp_ajax_rp_check_location_duplicate', 'rp_ajax_check_location_duplicate_handler' );
add_action( 'wp_ajax_nopriv_rp_check_location_duplicate', 'rp_ajax_check_location_duplicate_handler' );

/**
 * Handle state transition of SitReps: publish location records
 */
function rp_resource_hub_transition_sitrep_status( $new_status, $old_status, $post ) {
	if ( 'rp_sitrep' !== $post->post_type ) {
		return;
	}

	global $wpdb;
	$table_name = $wpdb->prefix . 'rp_sitrep_locations';

	if ( 'publish' === $new_status && 'publish' !== $old_status ) {
		$rows = $wpdb->get_results( $wpdb->prepare(
			"SELECT * FROM $table_name WHERE sitrep_id = %d AND record_status = 'pending'",
			$post->ID
		) );

		foreach ( $rows as $row ) {
			if ( 'update' === $row->conflict_mode ) {
				$wpdb->query( $wpdb->prepare(
					"UPDATE $table_name 
					 SET record_status = 'superseded' 
					 WHERE incident_id = %d AND province = %s AND municipality = %s AND barangay = %s AND record_status = 'publish'",
					$row->incident_id,
					$row->province,
					$row->municipality,
					$row->barangay
				) );
			}
			
			$wpdb->update(
				$table_name,
				array( 'record_status' => 'publish' ),
				array( 'id' => $row->id ),
				array( '%s' ),
				array( '%d' )
			);
		}
	} elseif ( 'publish' !== $new_status && 'publish' === $old_status ) {
		$wpdb->update(
			$table_name,
			array( 'record_status' => 'pending' ),
			array( 'sitrep_id' => $post->ID, 'record_status' => 'publish' ),
			array( '%s' ),
			array( '%d' )
		);
	}
}
add_action( 'transition_post_status', 'rp_resource_hub_transition_sitrep_status', 10, 3 );

/**
 * Revert / clean up DB records on deletion
 */
function rp_resource_hub_delete_sitrep_records( $post_id ) {
	if ( 'rp_sitrep' !== get_post_type( $post_id ) ) {
		return;
	}

	global $wpdb;
	$table_name = $wpdb->prefix . 'rp_sitrep_locations';
	$wpdb->delete( $table_name, array( 'sitrep_id' => $post_id ), array( '%d' ) );
}
add_action( 'before_delete_post', 'rp_resource_hub_delete_sitrep_records' );

/**
 * Handle My Contributions Dashboard actions (Unpublish, Publish, Edit submission)
 */
function rp_resource_hub_handle_my_contributions_actions() {
	if ( ! is_user_logged_in() ) {
		return;
	}

	$action  = isset( $_GET['action'] ) ? sanitize_key( $_GET['action'] ) : '';
	$post_id = isset( $_GET['post_id'] ) ? absint( $_GET['post_id'] ) : 0;

	if ( ! $post_id ) {
		return;
	}

	// 1. Status Changes: unpublish / publish
	if ( in_array( $action, array( 'unpublish', 'publish' ), true ) ) {
		$nonce = isset( $_GET['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'rp_toggle_status_' . $post_id ) ) {
			wp_die( esc_html__( 'Security check failed.', 'rp-resource-hub' ) );
		}

		$post = get_post( $post_id );
		if ( ! $post || ! in_array( $post->post_type, array( 'accord_library', 'partner_resources', 'post' ), true ) ) {
			wp_die( esc_html__( 'Invalid resource.', 'rp-resource-hub' ) );
		}

		if ( (int) $post->post_author !== get_current_user_id() && ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Permission denied.', 'rp-resource-hub' ) );
		}

		$new_status = ( 'unpublish' === $action ) ? 'draft' : 'pending';
		wp_update_post(
			array(
				'ID'          => $post_id,
				'post_status' => $new_status,
			)
		);

		wp_safe_redirect( add_query_arg( 'message', $action . 'ed', home_url( '/my-contributions/' ) ) );
		exit;
	}

	// 2. Edit Resource Form Submission
	if ( isset( $_POST['rp_edit_resource_submit'] ) ) {
		$nonce = isset( $_POST['rp_edit_resource_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['rp_edit_resource_nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'rp_edit_resource_' . $post_id ) ) {
			wp_die( esc_html__( 'Security check failed.', 'rp-resource-hub' ) );
		}

		$post = get_post( $post_id );
		if ( ! $post || ! in_array( $post->post_type, array( 'accord_library', 'partner_resources', 'post' ), true ) ) {
			wp_die( esc_html__( 'Invalid resource.', 'rp-resource-hub' ) );
		}

		if ( (int) $post->post_author !== get_current_user_id() && ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Permission denied.', 'rp-resource-hub' ) );
		}

		$title = isset( $_POST['rp_title'] ) ? sanitize_text_field( wp_unslash( $_POST['rp_title'] ) ) : '';
		
		if ( 'post' === $post->post_type ) {
			$description = isset( $_POST['rp_content'] ) ? wp_kses_post( wp_unslash( $_POST['rp_content'] ) ) : '';
		} else {
			$description = isset( $_POST['rp_description'] ) ? sanitize_textarea_field( wp_unslash( $_POST['rp_description'] ) ) : '';
		}

		if ( empty( $title ) || empty( $description ) ) {
			wp_safe_redirect( add_query_arg( array( 'action' => 'edit', 'post_id' => $post_id, 'err' => 'empty_fields' ), home_url( '/my-contributions/' ) ) );
			exit;
		}

		if ( empty( $_POST['rp_authorized_consent'] ) ) {
			wp_safe_redirect( add_query_arg( array( 'action' => 'edit', 'post_id' => $post_id, 'err' => 'consent_required' ), home_url( '/my-contributions/' ) ) );
			exit;
		}

		if ( 'post' === $post->post_type ) {
			// Handle featured image update (if uploaded)
			if ( ! empty( $_FILES['rp_featured_image']['name'] ) ) {
				$file = $_FILES['rp_featured_image'];
				if ( $file['size'] > 5 * 1024 * 1024 ) {
					wp_safe_redirect( add_query_arg( array( 'action' => 'edit', 'post_id' => $post_id, 'err' => 'file_too_large' ), home_url( '/my-contributions/' ) ) );
					exit;
				}
				$file_type = wp_check_filetype( $file['name'] );
				$allowed_mimes = array( 'jpg', 'jpeg', 'png', 'gif', 'webp' );
				if ( ! in_array( strtolower( $file_type['ext'] ), $allowed_mimes, true ) ) {
					wp_safe_redirect( add_query_arg( array( 'action' => 'edit', 'post_id' => $post_id, 'err' => 'invalid_file_type' ), home_url( '/my-contributions/' ) ) );
					exit;
				}

				$_FILES['rp_featured_image']['name'] = sanitize_file_name( $file['name'] );
				require_once ABSPATH . 'wp-admin/includes/file.php';
				require_once ABSPATH . 'wp-admin/includes/media.php';
				require_once ABSPATH . 'wp-admin/includes/image.php';

				$attachment_id = media_handle_upload( 'rp_featured_image', $post_id );
				if ( ! is_wp_error( $attachment_id ) ) {
					$old_thumbnail_id = get_post_thumbnail_id( $post_id );
					if ( $old_thumbnail_id ) {
						wp_delete_attachment( $old_thumbnail_id, true );
					}
					set_post_thumbnail( $post_id, $attachment_id );
				} else {
					wp_safe_redirect( add_query_arg( array( 'action' => 'edit', 'post_id' => $post_id, 'err' => 'upload_error' ), home_url( '/my-contributions/' ) ) );
					exit;
				}
			}

			// Update post details
			wp_update_post( array(
				'ID'           => $post_id,
				'post_title'   => $title,
				'post_content' => $description,
				'post_status'  => 'pending',
			) );

			// Update category
			$submitted_category = isset( $_POST['rp_category'] ) ? absint( $_POST['rp_category'] ) : 0;
			if ( $submitted_category ) {
				wp_set_post_categories( $post_id, array( $submitted_category ) );
			}
		} else {
			// Handle secure resource CPT uploads
			$attachment_id = 0;
			if ( ! empty( $_FILES['rp_file']['name'] ) ) {
				$file = $_FILES['rp_file'];
				$file_type = wp_check_filetype_and_ext( $file['tmp_name'], $file['name'], rp_resource_hub_allowed_mimes() );

				if ( empty( $file_type['ext'] ) || empty( $file_type['type'] ) ) {
					wp_safe_redirect( add_query_arg( array( 'action' => 'edit', 'post_id' => $post_id, 'err' => 'invalid_file_type' ), home_url( '/my-contributions/' ) ) );
					exit;
				}

				if ( ! current_user_can( 'manage_options' ) && $file['size'] > RP_RESOURCE_HUB_MAX_UPLOAD_BYTES ) {
					wp_safe_redirect( add_query_arg( array( 'action' => 'edit', 'post_id' => $post_id, 'err' => 'file_too_large' ), home_url( '/my-contributions/' ) ) );
					exit;
				}

				$_FILES['rp_file']['name'] = sanitize_file_name( $file['name'] );

				add_filter( 'upload_dir', 'rp_resource_hub_secure_upload_dir' );
				$attachment_id = media_handle_upload( 'rp_file', $post_id );
				remove_filter( 'upload_dir', 'rp_resource_hub_secure_upload_dir' );

				if ( is_wp_error( $attachment_id ) ) {
					wp_safe_redirect( add_query_arg( array( 'action' => 'edit', 'post_id' => $post_id, 'err' => 'upload_error' ), home_url( '/my-contributions/' ) ) );
					exit;
				}

				$old_attachment_id = absint( get_post_meta( $post_id, '_rp_resource_file_id', true ) );
				if ( $old_attachment_id ) {
					wp_delete_attachment( $old_attachment_id, true );
				}
				update_post_meta( $post_id, '_rp_resource_file_id', absint( $attachment_id ) );

				if ( 'zip' === strtolower( $file_type['ext'] ) ) {
					update_post_meta( $post_id, '_rp_is_web_app', 1 );
				} else {
					delete_post_meta( $post_id, '_rp_is_web_app' );
				}
			}

			// Update CPT taxonomies
			$submitted_terms = isset( $_POST['rp_terms'] ) && is_array( $_POST['rp_terms'] ) ? wp_unslash( $_POST['rp_terms'] ) : array();
			$post_type       = 'partner_resources';
			$accord_term     = get_term_by( 'name', 'ACCORD', 'contributing_org' );
			if ( $accord_term && ! empty( $submitted_terms['contributing_org'] ) && is_array( $submitted_terms['contributing_org'] ) ) {
				if ( in_array( (int) $accord_term->term_id, array_map( 'intval', $submitted_terms['contributing_org'] ), true ) ) {
					$post_type = 'accord_library';
				}
			}

			wp_update_post( array(
				'ID'           => $post_id,
				'post_type'    => $post_type,
				'post_title'   => $title,
				'post_content' => $description,
				'post_status'  => 'pending',
			) );

			$taxonomies = array( 'resource_format', 'resource_category', 'hazard_type', 'target_audience', 'contributing_org', 'resource_visibility' );
			foreach ( $taxonomies as $taxonomy ) {
				$terms = ! empty( $submitted_terms[ $taxonomy ] ) && is_array( $submitted_terms[ $taxonomy ] )
					? array_map( 'intval', $submitted_terms[ $taxonomy ] )
					: array();
				wp_set_object_terms( $post_id, $terms, $taxonomy );
			}
		}

		wp_safe_redirect( add_query_arg( 'message', 'updated', home_url( '/my-contributions/' ) ) );
		exit;
	}
}
add_action( 'template_redirect', 'rp_resource_hub_handle_my_contributions_actions' );

/**
 * Render the [rp_my_contributions] shortcode
 */
function rp_resource_hub_my_contributions_shortcode() {
	if ( ! is_user_logged_in() ) {
		return '<div class="rp-notice rp-notice-error">' . sprintf(
			__( 'Please <a href="%s">log in</a> to view and manage your contributions.', 'rp-resource-hub' ),
			esc_url( home_url( '/portal-entry/?redirect_to=' . urlencode( home_url( '/my-contributions/' ) ) ) )
		) . '</div>';
	}

	ob_start();

	$action  = isset( $_GET['action'] ) ? sanitize_key( $_GET['action'] ) : '';
	$post_id = isset( $_GET['post_id'] ) ? absint( $_GET['post_id'] ) : 0;

	// Show messages
	$message = isset( $_GET['message'] ) ? sanitize_key( $_GET['message'] ) : '';
	if ( $message ) {
		$msg_text = '';
		$msg_class = 'rp-notice-success';
		if ( 'unpublished' === $message ) {
			$msg_text = __( 'Resource unpublished successfully. It is now saved as a draft.', 'rp-resource-hub' );
		} elseif ( 'published' === $message ) {
			$msg_text = __( 'Resource submitted for review successfully.', 'rp-resource-hub' );
		} elseif ( 'updated' === $message ) {
			$msg_text = __( 'Resource updated successfully and sent for review.', 'rp-resource-hub' );
		}
		if ( $msg_text ) {
			echo '<div class="rp-notice ' . esc_attr( $msg_class ) . '">' . esc_html( $msg_text ) . '</div>';
		}
	}

	// Show error messages
	$err = isset( $_GET['err'] ) ? sanitize_key( $_GET['err'] ) : '';
	if ( $err ) {
		$err_text = '';
		if ( 'empty_fields' === $err ) {
			$err_text = __( 'Title and Description fields are required.', 'rp-resource-hub' );
		} elseif ( 'consent_required' === $err ) {
			$err_text = __( 'You must confirm that you are authorized to share this resource.', 'rp-resource-hub' );
		} elseif ( 'invalid_file_type' === $err ) {
			$err_text = __( 'Only PDF, DOC, DOCX, XLS, XLSX, HTML, and ZIP files are allowed.', 'rp-resource-hub' );
		} elseif ( 'file_too_large' === $err ) {
			$err_text = sprintf( __( 'File size exceeds the limit of %s.', 'rp-resource-hub' ), rp_resource_hub_format_bytes( RP_RESOURCE_HUB_MAX_UPLOAD_BYTES ) );
		} elseif ( 'upload_error' === $err ) {
			$err_text = __( 'Failed to upload the file. Please try again.', 'rp-resource-hub' );
		}
		if ( $err_text ) {
			echo '<div class="rp-notice rp-notice-error">' . esc_html( $err_text ) . '</div>';
		}
	}

	// Render Edit View
	if ( 'edit' === $action && $post_id ) {
		$post = get_post( $post_id );
		if ( ! $post || ! in_array( $post->post_type, array( 'accord_library', 'partner_resources', 'post' ), true ) || ( (int) $post->post_author !== get_current_user_id() && ! current_user_can( 'manage_options' ) ) ) {
			echo '<div class="rp-notice rp-notice-error">' . esc_html__( 'Invalid resource or permission denied.', 'rp-resource-hub' ) . '</div>';
			return ob_get_clean();
		}

		if ( 'post' === $post->post_type ) {
			$selected_cat = 0;
			$cats = get_the_category( $post_id );
			if ( ! empty( $cats ) ) {
				$selected_cat = $cats[0]->term_id;
			}
			?>
			<div class="rp-edit-resource-container">
				<h3 style="margin-bottom: 20px; font-weight:800; color:var(--rp-color-navy);"><?php echo esc_html( sprintf( __( 'Edit Post/Story: %s', 'rp-resource-hub' ), $post->post_title ) ); ?></h3>
				<form class="rp-upload-form" action="<?php echo esc_url( add_query_arg( array( 'action' => 'edit', 'post_id' => $post_id ), home_url( '/my-contributions/' ) ) ); ?>" method="post" enctype="multipart/form-data">
					<?php wp_nonce_field( 'rp_edit_resource_' . $post_id, 'rp_edit_resource_nonce' ); ?>
					
					<div class="rp-field">
						<label for="rp_title"><?php esc_html_e( 'Post Title', 'rp-resource-hub' ); ?></label>
						<input id="rp_title" name="rp_title" type="text" required value="<?php echo esc_attr( $post->post_title ); ?>" maxlength="160">
					</div>
					
					<div class="rp-field">
						<label for="rp_category"><?php esc_html_e( 'Category', 'rp-resource-hub' ); ?></label>
						<select id="rp_category" name="rp_category" required>
							<option value=""><?php esc_html_e( 'Select a Category...', 'rp-resource-hub' ); ?></option>
							<?php
							$categories = get_categories( array( 'hide_empty' => false ) );
							foreach ( $categories as $cat ) :
								?>
								<option value="<?php echo absint( $cat->term_id ); ?>" <?php selected( $selected_cat, $cat->term_id ); ?>><?php echo esc_html( $cat->name ); ?></option>
								<?php
							endforeach;
							?>
						</select>
					</div>

					<div class="rp-field">
						<label for="rp_content"><?php esc_html_e( 'Content Body', 'rp-resource-hub' ); ?></label>
						<?php
						wp_editor(
							$post->post_content,
							'rp_content',
							array(
								'textarea_name' => 'rp_content',
								'textarea_rows' => 12,
								'media_buttons' => false,
								'teeny'         => true,
								'quicktags'     => true,
							)
						);
						?>
					</div>

					<div class="rp-field">
						<label><?php esc_html_e( 'Current Featured Image', 'rp-resource-hub' ); ?></label>
						<?php if ( has_post_thumbnail( $post_id ) ) : ?>
							<div class="rp-current-image-preview" style="margin-top:8px; margin-bottom:12px;">
								<?php echo get_the_post_thumbnail( $post_id, array( 150, 150 ), array( 'style' => 'border-radius:4px; border:1px solid var(--rp-color-border);' ) ); ?>
							</div>
						<?php else : ?>
							<p><?php esc_html_e( 'No featured image set.', 'rp-resource-hub' ); ?></p>
						<?php endif; ?>
					</div>

					<div class="rp-field">
						<label for="rp_featured_image"><?php esc_html_e( 'Replace Featured Image (Optional)', 'rp-resource-hub' ); ?></label>
						<input id="rp_featured_image" name="rp_featured_image" type="file" accept="image/*">
						<p class="rp-field-help"><?php esc_html_e( 'Upload a new featured image if you want to replace the current one. Max size: 5MB.', 'rp-resource-hub' ); ?></p>
					</div>

					<div class="rp-field rp-field-consent">
						<label style="display: flex; align-items: flex-start; gap: 8px; font-weight: normal; cursor: pointer;">
							<input id="rp_authorized_consent" name="rp_authorized_consent" type="checkbox" value="1" required style="margin-top: 4px; width: auto; height: auto;">
							<span><?php esc_html_e( 'I confirm that I am authorized to share this content publicly.', 'rp-resource-hub' ); ?></span>
						</label>
					</div>

					<div class="rp-edit-actions" style="margin-top:20px; display:flex; gap:12px;">
						<button type="submit" name="rp_edit_resource_submit" class="rp-button"><?php esc_html_e( 'Save Changes', 'rp-resource-hub' ); ?></button>
						<a href="<?php echo esc_url( home_url( '/my-contributions/' ) ); ?>" class="rp-button rp-button-secondary"><?php esc_html_e( 'Cancel', 'rp-resource-hub' ); ?></a>
					</div>
				</form>
			</div>
			<?php
			return ob_get_clean();
		}

		?>
		<div class="rp-edit-resource-container">
			<h3 style="margin-bottom: 20px; font-weight:800; color:var(--rp-color-navy);"><?php echo esc_html( sprintf( __( 'Edit Resource: %s', 'rp-resource-hub' ), $post->post_title ) ); ?></h3>
			<form class="rp-upload-form" action="<?php echo esc_url( add_query_arg( array( 'action' => 'edit', 'post_id' => $post_id ), home_url( '/my-contributions/' ) ) ); ?>" method="post" enctype="multipart/form-data">
				<?php wp_nonce_field( 'rp_edit_resource_' . $post_id, 'rp_edit_resource_nonce' ); ?>
				
				<div class="rp-field">
					<label for="rp_title"><?php esc_html_e( 'Resource Title', 'rp-resource-hub' ); ?></label>
					<input id="rp_title" name="rp_title" type="text" required value="<?php echo esc_attr( $post->post_title ); ?>" maxlength="160">
				</div>
				
				<div class="rp-field">
					<label for="rp_description"><?php esc_html_e( 'Description', 'rp-resource-hub' ); ?></label>
					<textarea id="rp_description" name="rp_description" rows="6" required><?php echo esc_textarea( $post->post_content ); ?></textarea>
				</div>
				
				<?php 
				$taxonomies = array( 'resource_format', 'resource_category', 'hazard_type', 'target_audience', 'contributing_org', 'resource_visibility' );
				foreach ( $taxonomies as $taxonomy ) : 
					$taxonomy_object = get_taxonomy( $taxonomy );
					if ( ! $taxonomy_object ) {
						continue;
					}
					$selected_terms = wp_get_object_terms( $post_id, $taxonomy, array( 'fields' => 'ids' ) );
					?>
					<fieldset class="rp-field rp-checkbox-list">
						<legend><?php echo esc_html( $taxonomy_object->labels->name ); ?></legend>
						<?php rp_resource_hub_term_options( $taxonomy, $selected_terms ); ?>
					</fieldset>
				<?php endforeach; ?>
				
				<div class="rp-field">
					<label><?php esc_html_e( 'Current File', 'rp-resource-hub' ); ?></label>
					<?php 
					$file_id = get_post_meta( $post_id, '_rp_resource_file_id', true );
					if ( $file_id ) {
						$file_url = wp_get_attachment_url( $file_id );
						$file_name = basename( get_attached_file( $file_id ) );
						echo '<p class="rp-current-file-link"><a href="' . esc_url( rp_resource_hub_download_url( $post_id ) ) . '" class="rp-button rp-button-secondary" style="min-height:0; padding:6px 12px; font-size:13px;"><span class="dashicons dashicons-media-document" style="margin-right:4px; font-size:16px; width:16px; height:16px; display:inline-block; vertical-align:middle;"></span>' . esc_html( $file_name ? $file_name : __( 'Download File', 'rp-resource-hub' ) ) . '</a></p>';
					} else {
						echo '<p>' . esc_html__( 'No file attached.', 'rp-resource-hub' ) . '</p>';
					}
					?>
				</div>

				<div class="rp-field">
					<label for="rp_file"><?php esc_html_e( 'Replace File (Optional)', 'rp-resource-hub' ); ?></label>
					<input id="rp_file" name="rp_file" type="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.html,.zip">
					<?php
					$max_size_text = current_user_can( 'manage_options' ) 
						? __( 'No limit (Administrator)', 'rp-resource-hub' ) 
						: rp_resource_hub_format_bytes( RP_RESOURCE_HUB_MAX_UPLOAD_BYTES );
					?>
					<p class="rp-field-help"><?php echo esc_html( sprintf( __( 'Accepted file types: PDF, DOC, DOCX, XLS, XLSX, HTML, ZIP (for Web Apps). Maximum size: %s. Leave blank to keep current file.', 'rp-resource-hub' ), $max_size_text ) ); ?></p>
				</div>
				
				<div class="rp-field rp-field-consent">
					<label style="display: flex; align-items: flex-start; gap: 8px; font-weight: normal; cursor: pointer;">
						<input id="rp_authorized_consent" name="rp_authorized_consent" type="checkbox" value="1" required style="margin-top: 4px; width: auto; height: auto;">
						<span><?php esc_html_e( 'I confirm that I am authorized to share this resource publicly or on this website.', 'rp-resource-hub' ); ?></span>
					</label>
				</div>
				
				<div class="rp-edit-actions" style="margin-top:20px; display:flex; gap:12px;">
					<button type="submit" name="rp_edit_resource_submit" class="rp-button"><?php esc_html_e( 'Save Changes', 'rp-resource-hub' ); ?></button>
					<a href="<?php echo esc_url( home_url( '/my-contributions/' ) ); ?>" class="rp-button rp-button-secondary"><?php esc_html_e( 'Cancel', 'rp-resource-hub' ); ?></a>
				</div>
			</form>
		</div>
		<?php
		return ob_get_clean();
	}

	// Render List View
	$paged = isset( $_GET['rp_page'] ) ? max( 1, absint( $_GET['rp_page'] ) ) : 1;
	$args = array(
		'post_type'           => array( 'accord_library', 'partner_resources', 'post' ),
		'post_status'         => array( 'publish', 'pending', 'draft' ),
		'author'              => get_current_user_id(),
		'posts_per_page'      => 10,
		'paged'               => $paged,
		'ignore_sticky_posts' => true,
	);

	$query = new WP_Query( $args );
	?>
	<div class="rp-my-contributions-dashboard">
		<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 24px; flex-wrap: wrap; gap:12px;">
			<p class="rp-dashboard-subtitle" style="margin:0; color:var(--rp-color-muted);"><?php esc_html_e( 'Manage your uploaded resource publications, check moderation status, or unpublish listings.', 'rp-resource-hub' ); ?></p>
			<a href="<?php echo esc_url( home_url( '/submit-resource/' ) ); ?>" class="rp-button" style="min-height:40px; padding:8px 16px;"><span class="dashicons dashicons-plus-alt" style="margin-right:6px; font-size:18px; width:18px; height:18px; display:inline-block; vertical-align:-3px;"></span><?php esc_html_e( 'Submit New Resource', 'rp-resource-hub' ); ?></a>
		</div>

		<?php if ( $query->have_posts() ) : ?>
			<div class="rp-table-wrapper" style="background:#ffffff; border:1px solid var(--rp-color-border); border-radius:var(--rp-radius); overflow-x:auto; box-shadow: 0 1px 4px rgba(16, 32, 39, 0.04);">
				<table class="rp-contributions-table" style="width:100%; border-collapse:collapse; text-align:left; min-width: 600px;">
					<thead>
						<tr style="border-bottom:1px solid var(--rp-color-border); background:var(--rp-color-sky, #f0f5f3);">
							<th style="padding:14px 18px; font-weight:800; color:var(--rp-color-navy);"><?php esc_html_e( 'Title', 'rp-resource-hub' ); ?></th>
							<th style="padding:14px 18px; font-weight:800; color:var(--rp-color-navy);"><?php esc_html_e( 'Type', 'rp-resource-hub' ); ?></th>
							<th style="padding:14px 18px; font-weight:800; color:var(--rp-color-navy);"><?php esc_html_e( 'Submitted Date', 'rp-resource-hub' ); ?></th>
							<th style="padding:14px 18px; font-weight:800; color:var(--rp-color-navy);"><?php esc_html_e( 'Status', 'rp-resource-hub' ); ?></th>
							<th style="padding:14px 18px; font-weight:800; color:var(--rp-color-navy); text-align:right;"><?php esc_html_e( 'Actions', 'rp-resource-hub' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php while ( $query->have_posts() ) : $query->the_post(); 
							$post_id             = get_the_ID();
							$status              = get_post_status( $post_id );
							$post_type           = get_post_type( $post_id );
							$moderation_decision = get_post_meta( $post_id, '_rp_moderation_decision', true );
							$rejection_reason    = get_post_meta( $post_id, '_rp_moderation_rejection_reason', true );
							?>
							<tr style="border-bottom:1px solid var(--rp-color-border); transition: background-color 150ms ease;">
								<td style="padding:16px 18px; font-weight:700;">
									<?php if ( 'publish' === $status ) : ?>
										<a href="<?php the_permalink(); ?>" target="_blank" style="color:var(--rp-color-navy); text-decoration:none;"><?php the_title(); ?></a>
									<?php else : ?>
										<span style="color:var(--rp-color-navy);"><?php the_title(); ?></span>
									<?php endif; ?>
								</td>
								<td style="padding:16px 18px; color:var(--rp-color-muted); font-size:14px;">
									<?php 
									if ( 'accord_library' === $post_type ) {
										echo esc_html__( 'ACCORD Library', 'rp-resource-hub' );
									} elseif ( 'partner_resources' === $post_type ) {
										echo esc_html__( 'Partner Resource', 'rp-resource-hub' );
									} elseif ( 'post' === $post_type ) {
										echo esc_html__( 'Post / Story', 'rp-resource-hub' );
									}
									?>
								</td>
								<td style="padding:16px 18px; color:var(--rp-color-muted); font-size:14px;">
									<?php echo esc_html( get_the_date() ); ?>
								</td>
								<td style="padding:16px 18px;">
									<?php 
									if ( 'publish' === $status ) {
										echo '<span class="rp-status-badge rp-status-publish">' . esc_html__( 'Published', 'rp-resource-hub' ) . '</span>';
									} elseif ( 'pending' === $status ) {
										echo '<span class="rp-status-badge rp-status-pending">' . esc_html__( 'Pending Review', 'rp-resource-hub' ) . '</span>';
									} elseif ( 'draft' === $status && 'rejected' === $moderation_decision ) {
										echo '<span class="rp-status-badge rp-status-draft">' . esc_html__( 'Needs Revision', 'rp-resource-hub' ) . '</span>';
										if ( $rejection_reason ) {
											echo '<div style="max-width:280px; margin-top:6px; color:#7f1d1d; font-size:12px; line-height:1.4;">' . esc_html( $rejection_reason ) . '</div>';
										}
									} elseif ( 'draft' === $status ) {
										echo '<span class="rp-status-badge rp-status-draft">' . esc_html__( 'Unpublished', 'rp-resource-hub' ) . '</span>';
									}
									?>
								</td>
								<td style="padding:16px 18px; text-align:right;">
									<div style="display:inline-flex; gap:8px;">
										<a href="<?php echo esc_url( add_query_arg( array( 'action' => 'edit', 'post_id' => $post_id ), home_url( '/my-contributions/' ) ) ); ?>" class="rp-button rp-button-secondary" style="min-height:0; padding:6px 12px; font-size:13px; font-weight:700;"><?php esc_html_e( 'Edit', 'rp-resource-hub' ); ?></a>
										
										<?php if ( 'publish' === $status ) : ?>
											<?php $unpub_url = wp_nonce_url( add_query_arg( array( 'action' => 'unpublish', 'post_id' => $post_id ), home_url( '/my-contributions/' ) ), 'rp_toggle_status_' . $post_id ); ?>
											<a href="<?php echo esc_url( $unpub_url ); ?>" class="rp-button rp-button-secondary rp-unpublish-btn" style="min-height:0; padding:6px 12px; font-size:13px; font-weight:700; color: #b91c1c !important; border-color: #fca5a5;"><?php esc_html_e( 'Unpublish', 'rp-resource-hub' ); ?></a>
										<?php elseif ( 'draft' === $status ) : ?>
											<?php $pub_url = wp_nonce_url( add_query_arg( array( 'action' => 'publish', 'post_id' => $post_id ), home_url( '/my-contributions/' ) ), 'rp_toggle_status_' . $post_id ); ?>
											<a href="<?php echo esc_url( $pub_url ); ?>" class="rp-button" style="min-height:0; padding:6px 12px; font-size:13px; font-weight:700;"><?php esc_html_e( 'Submit for Review', 'rp-resource-hub' ); ?></a>
										<?php endif; ?>
									</div>
								</td>
							</tr>
						<?php endwhile; wp_reset_postdata(); ?>
					</tbody>
				</table>
			</div>
			
			<?php 
			// Pagination
			if ( $query->max_num_pages > 1 ) :
				?>
				<nav class="rp-pagination" aria-label="<?php esc_attr_e( 'Contributions pagination', 'rp-resource-hub' ); ?>" style="margin-top:20px;">
					<ul>
						<?php 
						echo wp_kses_post(
							paginate_links(
								array(
									'base'      => esc_url_raw( add_query_arg( 'rp_page', '%#%' ) ),
									'format'    => '',
									'current'   => $paged,
									'total'     => $query->max_num_pages,
									'type'      => 'plain',
									'prev_text' => __( 'Previous', 'rp-resource-hub' ),
									'next_text' => __( 'Next', 'rp-resource-hub' ),
								)
							)
						);
						?>
					</ul>
				</nav>
			<?php endif; ?>

		<?php else : ?>
			<div class="rp-notice rp-notice-info">
				<?php esc_html_e( 'You have not submitted any resources yet.', 'rp-resource-hub' ); ?>
				<a href="<?php echo esc_url( home_url( '/submit-resource/' ) ); ?>" style="font-weight:bold; margin-left:6px; color:var(--rp-color-green); text-decoration:underline;"><?php esc_html_e( 'Submit your first resource', 'rp-resource-hub' ); ?> &rarr;</a>
			</div>
		<?php endif; ?>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'rp_my_contributions', 'rp_resource_hub_my_contributions_shortcode' );

/**
 * News & Stories Hub Catalog Shortcode
 */
function rp_resource_hub_news_catalog_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'limit' => 12,
		),
		$atts,
		'rp_news_catalog'
	);

	$limit        = max( 1, min( 24, absint( $atts['limit'] ) ) );
	$paged        = isset( $_GET['rp_page'] ) ? max( 1, absint( $_GET['rp_page'] ) ) : 1;
	$search_query = isset( $_GET['rp_q'] ) ? sanitize_text_field( wp_unslash( $_GET['rp_q'] ) ) : '';
	$search_query = substr( $search_query, 0, 120 );
	$category_id  = isset( $_GET['rp_news_category'] ) ? absint( $_GET['rp_news_category'] ) : 0;

	$args = array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => $limit,
		'paged'               => $paged,
		'ignore_sticky_posts' => true,
		's'                   => $search_query,
	);

	if ( $category_id ) {
		$args['cat'] = $category_id;
	}

	$query = new WP_Query( $args );

	ob_start();
	?>
	<div class="rp-catalog rp-news-catalog">
		<form class="rp-news-filters" method="get">
			<div class="rp-field">
				<label for="rp_q"><?php esc_html_e( 'Search news & stories', 'rp-resource-hub' ); ?></label>
				<input id="rp_q" name="rp_q" type="search" value="<?php echo esc_attr( $search_query ); ?>">
			</div>
			<div class="rp-field">
				<label for="rp_news_category"><?php esc_html_e( 'Category', 'rp-resource-hub' ); ?></label>
				<select id="rp_news_category" name="rp_news_category">
					<option value="0"><?php esc_html_e( 'All Categories', 'rp-resource-hub' ); ?></option>
					<?php
					$categories = get_categories( array( 'hide_empty' => true ) );
					foreach ( $categories as $cat ) :
						?>
						<option value="<?php echo absint( $cat->term_id ); ?>" <?php selected( $category_id, $cat->term_id ); ?>><?php echo esc_html( $cat->name ); ?></option>
						<?php
					endforeach;
					?>
				</select>
			</div>
			<button class="rp-catalog-submit" type="submit"><?php esc_html_e( 'Filter', 'rp-resource-hub' ); ?></button>
		</form>

		<div class="rp-news-grid-wrapper" style="position: relative;">
			<div class="rp-news-grid rp-resource-grid">
				<?php echo rp_resource_hub_render_news_grid_items( $query ); ?>
			</div>
			<div class="rp-news-pagination-wrapper">
				<?php echo rp_resource_hub_render_news_pagination( $query, $paged ); ?>
			</div>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'rp_news_catalog', 'rp_resource_hub_news_catalog_shortcode' );

function rp_resource_hub_render_news_grid_items( $query ) {
	ob_start();
	if ( $query->have_posts() ) :
		while ( $query->have_posts() ) :
			$query->the_post();
			$post_id = get_the_ID();
			$categories = get_the_category();
			$cat_list = array();
			if ( ! empty( $categories ) ) {
				foreach ( $categories as $cat ) {
					$cat_list[] = $cat->name;
				}
			}
			$cat_string = implode( ', ', $cat_list );
			?>
			<article class="rp-resource-card rp-news-card">
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="rp-card-image">
						<a href="<?php the_permalink(); ?>">
							<?php the_post_thumbnail( 'medium' ); ?>
						</a>
					</div>
				<?php endif; ?>
				<p class="rp-resource-type" style="color: var(--rp-color-green);"><?php echo esc_html( $cat_string ? $cat_string : __( 'News & Stories', 'rp-resource-hub' ) ); ?></p>
				<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
				<div class="rp-resource-meta"><?php echo esc_html( get_the_date() ); ?></div>
				<?php the_excerpt(); ?>
				<a class="rp-resource-readmore" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read more', 'rp-resource-hub' ); ?></a>
				<div class="rp-card-spacer"></div>
			</article>
			<?php
		endwhile;
		wp_reset_postdata();
	else :
		?>
		<p class="rp-no-results"><?php esc_html_e( 'No posts or stories found matching your criteria.', 'rp-resource-hub' ); ?></p>
		<?php
	endif;
	return ob_get_clean();
}

function rp_resource_hub_render_news_pagination( $query, $paged ) {
	ob_start();
	if ( $query->max_num_pages > 1 ) :
		?>
		<nav class="rp-pagination" aria-label="<?php esc_attr_e( 'News pagination', 'rp-resource-hub' ); ?>">
			<ul>
				<?php
				echo wp_kses_post(
					paginate_links(
						array(
							'base'      => esc_url_raw( add_query_arg( 'rp_page', '%#%' ) ),
							'format'    => '',
							'current'   => $paged,
							'total'     => $query->max_num_pages,
							'type'      => 'plain',
							'prev_text' => __( 'Previous', 'rp-resource-hub' ),
							'next_text' => __( 'Next', 'rp-resource-hub' ),
						)
					)
				);
				?>
			</ul>
		</nav>
		<?php
	endif;
	return ob_get_clean();
}

function rp_ajax_filter_news() {
	$paged        = isset( $_POST['paged'] ) ? max( 1, absint( $_POST['paged'] ) ) : 1;
	$search_query = isset( $_POST['q'] ) ? sanitize_text_field( wp_unslash( $_POST['q'] ) ) : '';
	$category_id  = isset( $_POST['category'] ) ? absint( $_POST['category'] ) : 0;

	$args = array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => 12,
		'paged'               => $paged,
		'ignore_sticky_posts' => true,
		's'                   => $search_query,
	);

	if ( $category_id ) {
		$args['cat'] = $category_id;
	}

	$query = new WP_Query( $args );

	$grid_html       = rp_resource_hub_render_news_grid_items( $query );
	$pagination_html = rp_resource_hub_render_news_pagination( $query, $paged );

	wp_send_json_success( array(
		'grid'       => $grid_html,
		'pagination' => $pagination_html,
	) );
}
add_action( 'wp_ajax_rp_filter_news', 'rp_ajax_filter_news' );
add_action( 'wp_ajax_nopriv_rp_filter_news', 'rp_ajax_filter_news' );

/**
 * Submit Post Form Shortcode
 */
function rp_resource_hub_submit_post_shortcode() {
	ob_start();

	if ( ! is_user_logged_in() ) {
		echo '<div class="rp-notice rp-notice-error">' . sprintf(
			__( 'Please <a href="%s">log in</a> to submit a post or story.', 'rp-resource-hub' ),
			esc_url( home_url( '/portal-entry/?redirect_to=' . urlencode( home_url( '/submit-post/' ) ) ) )
		) . '</div>';
		return ob_get_clean();
	}

	if ( ! current_user_can( 'publish_posts' ) && ! current_user_can( 'manage_options' ) ) {
		echo '<div class="rp-notice rp-notice-error">' . esc_html__( 'You do not have permission to submit posts or stories.', 'rp-resource-hub' ) . '</div>';
		return ob_get_clean();
	}

	$notice = rp_resource_hub_get_upload_notice();
	if ( $notice ) {
		$notice_class = 'success' === $notice['type'] ? 'rp-notice-success' : 'rp-notice-error';
		echo '<div class="rp-notice ' . esc_attr( $notice_class ) . '">' . esc_html( $notice['message'] ) . '</div>';
	}
	?>
	<form class="rp-upload-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" enctype="multipart/form-data">
		<input type="hidden" name="action" value="rp_post_upload">
		<?php wp_nonce_field( 'rp_post_upload', 'rp_post_upload_nonce' ); ?>
		
		<div class="rp-field">
			<label for="rp_title"><?php esc_html_e( 'Post Title', 'rp-resource-hub' ); ?></label>
			<input id="rp_title" name="rp_title" type="text" required maxlength="160">
		</div>

		<div class="rp-field">
			<label for="rp_category"><?php esc_html_e( 'Category', 'rp-resource-hub' ); ?></label>
			<select id="rp_category" name="rp_category" required>
				<option value=""><?php esc_html_e( 'Select a Category...', 'rp-resource-hub' ); ?></option>
				<?php
				$categories = get_categories( array( 'hide_empty' => false ) );
				foreach ( $categories as $cat ) :
					?>
					<option value="<?php echo absint( $cat->term_id ); ?>"><?php echo esc_html( $cat->name ); ?></option>
					<?php
				endforeach;
				?>
			</select>
		</div>

		<div class="rp-field">
			<label for="rp_content"><?php esc_html_e( 'Content Body', 'rp-resource-hub' ); ?></label>
			<?php
			wp_editor(
				'',
				'rp_content',
				array(
					'textarea_name' => 'rp_content',
					'textarea_rows' => 12,
					'media_buttons' => false,
					'teeny'         => true,
					'quicktags'     => true,
				)
			);
			?>
		</div>

		<div class="rp-field">
			<label for="rp_featured_image"><?php esc_html_e( 'Featured Image', 'rp-resource-hub' ); ?></label>
			<input id="rp_featured_image" name="rp_featured_image" type="file" accept="image/*" required>
			<p class="rp-field-help"><?php esc_html_e( 'Upload a featured image for your post (JPEG, PNG, WebP). Max size: 5MB.', 'rp-resource-hub' ); ?></p>
		</div>
		
		<div class="rp-field rp-field-consent">
			<label style="display: flex; align-items: flex-start; gap: 8px; font-weight: normal; cursor: pointer;">
				<input id="rp_authorized_consent" name="rp_authorized_consent" type="checkbox" value="1" required style="margin-top: 4px; width: auto; height: auto;">
				<span><?php esc_html_e( 'I confirm that I am authorized to share this content publicly.', 'rp-resource-hub' ); ?></span>
			</label>
		</div>

		<button type="submit" class="rp-button" style="margin-top: 20px;"><?php esc_html_e( 'Submit Post', 'rp-resource-hub' ); ?></button>
	</form>
	<?php
	return ob_get_clean();
}
add_shortcode( 'rp_submit_post_form', 'rp_resource_hub_submit_post_shortcode' );

function rp_resource_hub_handle_post_upload() {
	if ( ! is_user_logged_in() ) {
		wp_safe_redirect( wp_login_url( home_url( '/submit-post/' ) ) );
		exit;
	}

	if ( ! current_user_can( 'publish_posts' ) && ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to submit posts or stories.', 'rp-resource-hub' ) );
	}

	$nonce = isset( $_POST['rp_post_upload_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['rp_post_upload_nonce'] ) ) : '';
	if ( ! wp_verify_nonce( $nonce, 'rp_post_upload' ) ) {
		wp_die( esc_html__( 'Security check failed.', 'rp-resource-hub' ) );
	}

	if ( ! isset( $_POST['rp_authorized_consent'] ) || '1' !== $_POST['rp_authorized_consent'] ) {
		$notice_key = rp_resource_hub_store_upload_notice( 'error', __( 'You must confirm that you are authorized to share this content.', 'rp-resource-hub' ) );
		wp_safe_redirect( add_query_arg( 'rp_upload_notice', $notice_key, home_url( '/submit-post/' ) ) );
		exit;
	}

	$title    = isset( $_POST['rp_title'] ) ? sanitize_text_field( wp_unslash( $_POST['rp_title'] ) ) : '';
	$content  = isset( $_POST['rp_content'] ) ? wp_kses_post( wp_unslash( $_POST['rp_content'] ) ) : '';
	$category = isset( $_POST['rp_category'] ) ? absint( $_POST['rp_category'] ) : 0;

	if ( empty( $title ) || empty( $content ) || empty( $category ) ) {
		$notice_key = rp_resource_hub_store_upload_notice( 'error', __( 'Title, content, and category are required.', 'rp-resource-hub' ) );
		wp_safe_redirect( add_query_arg( 'rp_upload_notice', $notice_key, home_url( '/submit-post/' ) ) );
		exit;
	}

	if ( empty( $_FILES['rp_featured_image']['name'] ) ) {
		$notice_key = rp_resource_hub_store_upload_notice( 'error', __( 'Featured image is required.', 'rp-resource-hub' ) );
		wp_safe_redirect( add_query_arg( 'rp_upload_notice', $notice_key, home_url( '/submit-post/' ) ) );
		exit;
	}

	$file = $_FILES['rp_featured_image'];
	if ( $file['size'] > 5 * 1024 * 1024 ) {
		$notice_key = rp_resource_hub_store_upload_notice( 'error', __( 'Image file size exceeds the 5MB limit.', 'rp-resource-hub' ) );
		wp_safe_redirect( add_query_arg( 'rp_upload_notice', $notice_key, home_url( '/submit-post/' ) ) );
		exit;
	}

	$file_type = wp_check_filetype( $file['name'] );
	$allowed_mimes = array( 'jpg', 'jpeg', 'png', 'gif', 'webp' );
	if ( ! in_array( strtolower( $file_type['ext'] ), $allowed_mimes, true ) ) {
		$notice_key = rp_resource_hub_store_upload_notice( 'error', __( 'Only JPG, JPEG, PNG, GIF, and WebP images are allowed.', 'rp-resource-hub' ) );
		wp_safe_redirect( add_query_arg( 'rp_upload_notice', $notice_key, home_url( '/submit-post/' ) ) );
		exit;
	}

	$post_id = wp_insert_post( array(
		'post_type'    => 'post',
		'post_title'   => $title,
		'post_content' => $content,
		'post_status'  => 'pending',
		'post_author'  => get_current_user_id(),
	), true );

	if ( is_wp_error( $post_id ) ) {
		$notice_key = rp_resource_hub_store_upload_notice( 'error', $post_id->get_error_message() );
		wp_safe_redirect( add_query_arg( 'rp_upload_notice', $notice_key, home_url( '/submit-post/' ) ) );
		exit;
	}

	wp_set_post_categories( $post_id, array( $category ) );

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$_FILES['rp_featured_image']['name'] = sanitize_file_name( $file['name'] );
	$attachment_id = media_handle_upload( 'rp_featured_image', $post_id );

	if ( is_wp_error( $attachment_id ) ) {
		wp_delete_post( $post_id, true );
		$notice_key = rp_resource_hub_store_upload_notice( 'error', __( 'Failed to upload featured image. Please try again.', 'rp-resource-hub' ) );
		wp_safe_redirect( add_query_arg( 'rp_upload_notice', $notice_key, home_url( '/submit-post/' ) ) );
		exit;
	}

	set_post_thumbnail( $post_id, $attachment_id );

	wp_safe_redirect( home_url( '/resource-submitted/' ) );
	exit;
}
add_action( 'admin_post_rp_post_upload', 'rp_resource_hub_handle_post_upload' );

/**
 * Add active status metabox for Crisis Incidents (rp_incident CPT).
 */
function rp_incident_add_active_metabox() {
	add_meta_box(
		'rp_incident_status_metabox',
		__( 'Incident Status', 'rp-resource-hub' ),
		'rp_incident_render_status_metabox',
		'rp_incident',
		'side',
		'high'
	);
}
add_action( 'add_meta_boxes', 'rp_incident_add_active_metabox' );

/**
 * Render the Crisis Incident status metabox.
 */
function rp_incident_render_status_metabox( $post ) {
	$is_active = get_post_meta( $post->ID, '_rp_incident_is_active', true );
	if ( '' === $is_active ) {
		$is_active = '1'; // Default to active for backward compatibility
	}
	wp_nonce_field( 'rp_incident_status_save', 'rp_incident_status_nonce' );
	?>
	<p>
		<label>
			<input type="checkbox" name="rp_incident_is_active" value="1" <?php checked( $is_active, '1' ); ?>>
			<strong><?php esc_html_e( 'Active Incident', 'rp-resource-hub' ); ?></strong>
		</label>
	</p>
	<p class="description">
		<?php esc_html_e( 'Uncheck this to mark the incident as inactive. Inactive incidents will not appear in the active crisis list or SitRep submission dropdown, but their dashboards will remain readable.', 'rp-resource-hub' ); ?>
	</p>
	<?php
}

/**
 * Save Crisis Incident status metabox value.
 */
function rp_incident_save_status_metabox( $post_id ) {
	if ( ! isset( $_POST['rp_incident_status_nonce'] ) || ! wp_verify_nonce( $_POST['rp_incident_status_nonce'], 'rp_incident_status_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$is_active = isset( $_POST['rp_incident_is_active'] ) ? '1' : '0';
	update_post_meta( $post_id, '_rp_incident_is_active', $is_active );
}
add_action( 'save_post_rp_incident', 'rp_incident_save_status_metabox' );

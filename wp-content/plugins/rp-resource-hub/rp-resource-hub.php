<?php
/**
 * Plugin Name: Resilient Philippines Resource Hub
 * Description: Custom post types, taxonomies, roles, upload workflow, and catalog shortcodes for the humanitarian resource hub.
 * Version: 1.8.1
 * Author: ACCORD
 * Text Domain: rp-resource-hub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'RP_RESOURCE_HUB_VERSION', '1.8.1' );
define( 'RP_RESOURCE_HUB_FILE', __FILE__ );
define( 'RP_RESOURCE_HUB_PATH', plugin_dir_path( __FILE__ ) );
define( 'RP_RESOURCE_HUB_URL', plugin_dir_url( __FILE__ ) );
define( 'RP_RESOURCE_HUB_MAX_UPLOAD_BYTES', 25 * 1024 * 1024 );

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

function rp_resource_hub_maybe_upgrade() {
	if ( RP_RESOURCE_HUB_VERSION === get_option( 'rp_resource_hub_version' ) ) {
		return;
	}

	rp_resource_hub_create_db_table();
	rp_resource_hub_apply_roles_and_caps();
	rp_resource_hub_seed_terms();
	rp_resource_hub_create_pages();
	flush_rewrite_rules();
	update_option( 'rp_resource_hub_version', RP_RESOURCE_HUB_VERSION );
}
add_action( 'admin_init', 'rp_resource_hub_maybe_upgrade' );

function rp_resource_hub_activate() {
	rp_resource_hub_register_post_types();
	rp_resource_hub_register_taxonomies();
	rp_resource_hub_create_db_table();
	rp_resource_hub_apply_roles_and_caps();
	rp_resource_hub_seed_terms();
	rp_resource_hub_create_pages();
	flush_rewrite_rules();
	update_option( 'rp_resource_hub_version', RP_RESOURCE_HUB_VERSION );
}
register_activation_hook( __FILE__, 'rp_resource_hub_activate' );

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
	);

	foreach ( $pages as $slug => $page ) {
		if ( get_page_by_path( $slug ) ) {
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

	foreach ( $terms as $term ) {
		printf(
			'<label><input type="checkbox" name="rp_terms[%1$s][]" value="%2$d" %3$s> %4$s</label>',
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
	if ( ! $post || 'publish' !== $post->post_status || ! in_array( $post->post_type, array( 'accord_library', 'partner_resources' ), true ) ) {
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
			$is_web_app   = get_post_meta( $post_id, '_rp_is_web_app', true );
			$download_url = $file_id ? rp_resource_hub_download_url( $post_id ) : '';
			$is_member    = rp_resource_hub_is_member_only( $post_id );
			$can_download = ! $is_member || current_user_can( 'read_member_resources' );
			?>
			<article class="rp-resource-card">
				<p class="rp-resource-type"><?php echo esc_html( 'accord_library' === get_post_type() ? __( 'ACCORD Library', 'rp-resource-hub' ) : __( 'Partner Resource', 'rp-resource-hub' ) ); ?></p>
				<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
				<div class="rp-resource-meta"><?php echo esc_html( get_the_date() ); ?></div>
				<?php the_excerpt(); ?>
				<?php if ( $is_web_app && $can_download ) : ?>
					<?php $web_app_url = rp_resource_hub_get_web_app_url( $post_id ); ?>
					<?php if ( $web_app_url ) : ?>
						<a class="rp-button rp-resource-download" href="<?php echo esc_url( $web_app_url ); ?>" target="_blank"><?php esc_html_e( 'Launch Web App', 'rp-resource-hub' ); ?></a>
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
	$is_web_app = get_post_meta( $post_id, '_rp_is_web_app', true );
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
	?>
	<form class="rp-upload-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" enctype="multipart/form-data">
		<input type="hidden" name="action" value="rp_sitrep_upload">
		<?php wp_nonce_field( 'rp_sitrep_upload', 'rp_sitrep_upload_nonce' ); ?>
		
		<div class="rp-field">
			<label for="rp_title"><?php esc_html_e( 'Report Title', 'rp-resource-hub' ); ?> <span class="rp-required-star" style="color: #ef4444;">*</span></label>
			<input id="rp_title" name="rp_title" type="text" required placeholder="e.g. Typhoon Pepito - Contributor SitRep #1" maxlength="160">
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
				) );
				foreach ( $incidents as $inc ) {
					printf( '<option value="%d">%s</option>', absint( $inc->ID ), esc_html( $inc->post_title ) );
				}
				?>
			</select>
		</div>

		<div class="rp-field">
			<label for="rp_description"><?php esc_html_e( 'Situation Summary', 'rp-resource-hub' ); ?> <span class="rp-required-star" style="color: #ef4444;">*</span></label>
			<textarea id="rp_description" name="rp_description" rows="5" required placeholder="Describe the current situation, main impacts, and timeline..."></textarea>
		</div>

		<fieldset class="rp-field rp-checkbox-list">
			<legend><?php esc_html_e( 'Hazard Type', 'rp-resource-hub' ); ?></legend>
			<?php rp_resource_hub_term_options( 'hazard_type' ); ?>
		</fieldset>

		<h3 style="margin-top: 30px; color: var(--rp-color-primary, #0f172a); border-bottom: 2px solid #e2e8f0; padding-bottom: 8px;">
			<?php esc_html_e( 'Affected Locations & Metrics', 'rp-resource-hub' ); ?>
		</h3>

		<div id="rp-locations-container">
			<!-- Row 0 (Default) -->
			<div class="rp-location-row" data-idx="0" style="border: 1px solid #e2e8f0; padding: 20px; border-radius: 6px; margin-bottom: 20px; position: relative; background: #fafafa;">
				<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
					<div class="rp-field">
						<label>Region</label>
						<select class="rp-loc-region" name="rp_locations[0][region]">
							<option value="">Loading regions...</option>
						</select>
					</div>
					<div class="rp-field">
						<label>Province *</label>
						<select class="rp-loc-province" name="rp_locations[0][province]" required>
							<option value="">Select Region first...</option>
						</select>
					</div>
					<div class="rp-field">
						<label>Municipality *</label>
						<select class="rp-loc-municipality" name="rp_locations[0][municipality]" required>
							<option value="">Select Province first...</option>
						</select>
					</div>
					<div class="rp-field">
						<label>Barangay *</label>
						<input class="rp-loc-barangay" name="rp_locations[0][barangay]" type="text" required placeholder="e.g. Seguinon">
					</div>
				</div>
				
				<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); gap: 15px; margin-top: 15px;">
					<div class="rp-field">
						<label>Affected Barangays</label>
						<input name="rp_locations[0][affected_barangays]" type="number" min="0" value="0">
					</div>
					<div class="rp-field">
						<label>Affected Households</label>
						<input name="rp_locations[0][households]" type="number" min="0" value="0">
					</div>
					<div class="rp-field">
						<label>Affected Individuals</label>
						<input name="rp_locations[0][individuals]" type="number" min="0" value="0">
					</div>
					<div class="rp-field">
						<label>Displaced Inside EC</label>
						<input class="rp-displaced-inside" name="rp_locations[0][displaced_inside]" type="number" min="0" value="0">
					</div>
					<div class="rp-field">
						<label>Displaced Outside EC</label>
						<input class="rp-displaced-outside" name="rp_locations[0][displaced_outside]" type="number" min="0" value="0">
					</div>
					<div class="rp-field">
						<label>Total Displaced Indivs</label>
						<input class="rp-displaced-total" type="text" readonly value="0" style="background: #e2e8f0; font-weight: bold;">
					</div>
					<div class="rp-field">
						<label>Displaced Households</label>
						<input name="rp_locations[0][displaced_households]" type="number" min="0" value="0">
					</div>
					<div class="rp-field">
						<label>Data Source</label>
						<input name="rp_locations[0][data_source]" type="text" placeholder="e.g. MDRRMO">
					</div>
				</div>
				<div class="rp-duplicate-prompt" style="display: none;"></div>
			</div>
		</div>

		<button type="button" id="rp-add-location-btn" style="background: #0f172a; color: #fff; padding: 8px 16px; border-radius: 4px; border: none; cursor: pointer; font-weight: 600; margin-bottom: 30px;">
			+ <?php esc_html_e( 'Add Another Location', 'rp-resource-hub' ); ?>
		</button>

		<h3 style="margin-top: 30px; color: var(--rp-color-primary, #0f172a); border-bottom: 2px solid #e2e8f0; padding-bottom: 8px;">
			<?php esc_html_e( 'Sectoral Situation Details', 'rp-resource-hub' ); ?>
		</h3>

		<div class="rp-field" style="margin-top: 15px;">
			<label for="rp_sectoral_fsl"><?php esc_html_e( 'Food Security & Livelihoods (FSL)', 'rp-resource-hub' ); ?></label>
			<textarea id="rp_sectoral_fsl" name="rp_sectoral_fsl" rows="3" placeholder="Food availability, markets status, farm damages, needed food packs..."></textarea>
		</div>

		<div class="rp-field" style="margin-top: 15px;">
			<label for="rp_sectoral_wash"><?php esc_html_e( 'Water, Sanitation & Hygiene (WASH)', 'rp-resource-hub' ); ?></label>
			<textarea id="rp_sectoral_wash" name="rp_sectoral_wash" rows="3" placeholder="Access to clean drinking water, sanitation facilities, hygiene kits needed..."></textarea>
		</div>

		<div class="rp-field" style="margin-top: 15px;">
			<label for="rp_sectoral_shelter"><?php esc_html_e( 'Emergency Shelter', 'rp-resource-hub' ); ?></label>
			<textarea id="rp_sectoral_shelter" name="rp_sectoral_shelter" rows="3" placeholder="Damaged roofing, evacuation center congestion, tarpaulins needed..."></textarea>
		</div>

		<div class="rp-field" style="margin-top: 15px;">
			<label for="rp_sectoral_other"><?php esc_html_e( 'Other Sectors (Protection, Health, Education)', 'rp-resource-hub' ); ?></label>
			<textarea id="rp_sectoral_other" name="rp_sectoral_other" rows="3" placeholder="Health facilities status, child-friendly spaces, school damages..."></textarea>
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
			<?php esc_html_e( 'Submit Situation Report for Verification', 'rp-resource-hub' ); ?>
		</button>
	</form>

	<script>
	document.addEventListener('DOMContentLoaded', function() {
		const container = document.getElementById('rp-locations-container');
		const addBtn = document.getElementById('rp-add-location-btn');
		let rowIdx = 1;

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

			if (!incidentId || !province || !municipality || !barangay) {
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

			// Populate Region
			regSelect.innerHTML = '<option value="">Select Region...</option>';
			geoData.regions.forEach(r => {
				const opt = document.createElement('option');
				opt.value = r.region_name;
				opt.dataset.code = r.region_code;
				opt.textContent = r.region_name;
				if (r.region_name.includes('Region VIII')) {
					opt.selected = true;
				}
				regSelect.appendChild(opt);
			});

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
						<label>Region</label>
						<select class="rp-loc-region" name="rp_locations[${rowIdx}][region]">
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
						<label>Barangay *</label>
						<input class="rp-loc-barangay" name="rp_locations[${rowIdx}][barangay]" type="text" required placeholder="e.g. Seguinon">
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

	$post_id = wp_insert_post(
		array(
			'post_type'    => 'rp_sitrep',
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

	// Store meta data
	update_post_meta( $post_id, '_sitrep_incident_id', $incident_id );
	update_post_meta( $post_id, '_sitrep_sectoral_fsl', $sectoral_fsl );
	update_post_meta( $post_id, '_sitrep_sectoral_wash', $sectoral_wash );
	update_post_meta( $post_id, '_sitrep_sectoral_shelter', $sectoral_shelter );
	update_post_meta( $post_id, '_sitrep_sectoral_other', $sectoral_other );

	// Insert location records
	global $wpdb;
	$table_name = $wpdb->prefix . 'rp_sitrep_locations';

	foreach ( $locations as $loc ) {
		$region        = isset( $loc['region'] ) ? sanitize_text_field( $loc['region'] ) : '';
		$province      = isset( $loc['province'] ) ? sanitize_text_field( $loc['province'] ) : '';
		$municipality  = isset( $loc['municipality'] ) ? sanitize_text_field( $loc['municipality'] ) : '';
		$barangay      = isset( $loc['barangay'] ) ? sanitize_text_field( $loc['barangay'] ) : '';

		if ( empty( $province ) || empty( $municipality ) || empty( $barangay ) ) {
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
	if ( ! empty( $submitted_terms['hazard_type'] ) && is_array( $submitted_terms['hazard_type'] ) ) {
		$term_ids = array_map( 'absint', $submitted_terms['hazard_type'] );
		$term_ids = array_filter( $term_ids );
		wp_set_object_terms( $post_id, $term_ids, 'hazard_type', false );
	}

	// Dispatch email
	$admin_email = get_option( 'admin_email' );
	if ( is_email( $admin_email ) ) {
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

	if ( ! $incident_id || ! $province || ! $municipality || ! $barangay ) {
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

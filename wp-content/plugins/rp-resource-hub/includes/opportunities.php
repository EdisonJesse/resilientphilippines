<?php
/**
 * Opportunities, job applications, and invitation-to-bid submissions.
 *
 * @package RPResourceHub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'RP_OPPORTUNITIES_VERSION', '1.0.9' );
define( 'RP_JOB_MAX_ATTACHMENT_BYTES', 10 * 1024 * 1024 );
define( 'RP_JOB_MAX_ATTACHMENTS', 5 );
define( 'RP_BID_MAX_ATTACHMENT_BYTES', 25 * 1024 * 1024 );
define( 'RP_BID_MAX_ATTACHMENTS', 10 );

function rp_opportunities_type_options() {
	return array(
		'job' => __( 'Job Ad', 'rp-resource-hub' ),
		'itb' => __( 'Invitation to Bid', 'rp-resource-hub' ),
	);
}

function rp_opportunities_hiring_type_options() {
	return array(
		'full_time'  => __( 'Full-time', 'rp-resource-hub' ),
		'consultant' => __( 'Consultant', 'rp-resource-hub' ),
	);
}

function rp_opportunities_job_status_options() {
	return array(
		'received'     => __( 'Received', 'rp-resource-hub' ),
		'under_review' => __( 'Under Review', 'rp-resource-hub' ),
		'shortlisted'  => __( 'Shortlisted', 'rp-resource-hub' ),
		'interview'    => __( 'Interview', 'rp-resource-hub' ),
		'successful'   => __( 'Successful', 'rp-resource-hub' ),
		'unsuccessful' => __( 'Unsuccessful', 'rp-resource-hub' ),
		'withdrawn'    => __( 'Withdrawn', 'rp-resource-hub' ),
	);
}

function rp_opportunities_bid_status_options() {
	return array(
		'received'             => __( 'Received', 'rp-resource-hub' ),
		'under_review'         => __( 'Under Review', 'rp-resource-hub' ),
		'clarification_needed' => __( 'Clarification Needed', 'rp-resource-hub' ),
		'responsive'           => __( 'Responsive', 'rp-resource-hub' ),
		'non_responsive'       => __( 'Non-responsive', 'rp-resource-hub' ),
		'successful'           => __( 'Successful', 'rp-resource-hub' ),
		'unsuccessful'         => __( 'Unsuccessful', 'rp-resource-hub' ),
		'withdrawn'            => __( 'Withdrawn', 'rp-resource-hub' ),
	);
}

function rp_opportunities_user_can_manage_jobs() {
	return is_user_logged_in() && ( current_user_can( 'manage_job_applications' ) || current_user_can( 'manage_options' ) );
}

function rp_opportunities_user_can_manage_bids() {
	return is_user_logged_in() && ( current_user_can( 'manage_bid_submissions' ) || current_user_can( 'manage_options' ) );
}

function rp_opportunities_user_can_manage_submission_type( $type ) {
	return 'job' === $type ? rp_opportunities_user_can_manage_jobs() : rp_opportunities_user_can_manage_bids();
}

function rp_opportunities_user_can_submit() {
	return is_user_logged_in() && ( current_user_can( 'manage_opportunities' ) || current_user_can( 'manage_job_applications' ) || current_user_can( 'manage_bid_submissions' ) || current_user_can( 'submit_job_opportunities' ) || current_user_can( 'submit_itb_opportunities' ) || current_user_can( 'manage_options' ) );
}

function rp_opportunities_allowed_submit_types() {
	$types = array();
	if ( current_user_can( 'manage_options' ) || current_user_can( 'manage_opportunities' ) ) {
		return array_keys( rp_opportunities_type_options() );
	}
	if ( current_user_can( 'manage_job_applications' ) || current_user_can( 'submit_job_opportunities' ) ) {
		$types[] = 'job';
	}
	if ( current_user_can( 'manage_bid_submissions' ) || current_user_can( 'submit_itb_opportunities' ) ) {
		$types[] = 'itb';
	}
	return $types;
}

function rp_opportunities_register_post_type() {
	register_post_type(
		'rp_opportunity',
		array(
			'labels'            => array(
				'name'               => __( 'Opportunities', 'rp-resource-hub' ),
				'singular_name'      => __( 'Opportunity', 'rp-resource-hub' ),
				'add_new_item'       => __( 'Add Opportunity', 'rp-resource-hub' ),
				'edit_item'          => __( 'Edit Opportunity', 'rp-resource-hub' ),
				'new_item'           => __( 'New Opportunity', 'rp-resource-hub' ),
				'view_item'          => __( 'View Opportunity', 'rp-resource-hub' ),
				'search_items'       => __( 'Search Opportunities', 'rp-resource-hub' ),
				'not_found'          => __( 'No opportunities found.', 'rp-resource-hub' ),
				'menu_name'          => __( 'Opportunities', 'rp-resource-hub' ),
			),
			'public'            => true,
			'show_in_rest'      => true,
			'has_archive'       => false,
			'menu_position'     => 23,
			'menu_icon'         => 'dashicons-megaphone',
			'rewrite'           => array( 'slug' => 'opportunities' ),
			'supports'          => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'author' ),
			'map_meta_cap'      => true,
			'show_in_nav_menus' => true,
		)
	);
}
add_action( 'init', 'rp_opportunities_register_post_type' );

function rp_opportunities_apply_roles_and_caps() {
	$admin_caps = array(
		'manage_opportunities',
		'manage_job_applications',
		'manage_bid_submissions',
		'submit_job_opportunities',
		'submit_itb_opportunities',
	);

	foreach ( array( 'administrator', 'editor' ) as $role_name ) {
		$role = get_role( $role_name );
		if ( ! $role ) {
			continue;
		}
		$role->add_cap( 'manage_opportunities' );
		if ( 'administrator' === $role_name ) {
			foreach ( $admin_caps as $cap ) {
				$role->add_cap( $cap );
			}
		}
	}

	$hr_department = get_role( 'rp_hr_department' );
	if ( ! $hr_department ) {
		add_role(
			'rp_hr_department',
			__( 'ACCORD HR', 'rp-resource-hub' ),
			array(
				'read'                     => true,
				'upload_files'             => true,
				'manage_job_applications'  => true,
				'submit_job_opportunities' => true,
			)
		);
	} else {
		$hr_department->add_cap( 'read' );
		$hr_department->add_cap( 'upload_files' );
		$hr_department->add_cap( 'manage_job_applications' );
		$hr_department->add_cap( 'submit_job_opportunities' );
	}

	$procurement_department = get_role( 'rp_procurement_department' );
	if ( ! $procurement_department ) {
		add_role(
			'rp_procurement_department',
			__( 'ACCORD Procurement', 'rp-resource-hub' ),
			array(
				'read'                     => true,
				'upload_files'             => true,
				'manage_bid_submissions'   => true,
				'submit_itb_opportunities' => true,
			)
		);
	} else {
		$procurement_department->add_cap( 'read' );
		$procurement_department->add_cap( 'upload_files' );
		$procurement_department->add_cap( 'manage_bid_submissions' );
		$procurement_department->add_cap( 'submit_itb_opportunities' );
	}

	rp_opportunities_migrate_department_role( 'rp_hr_reviewer', 'rp_hr_department' );
	rp_opportunities_migrate_department_role( 'rp_procurement_reviewer', 'rp_procurement_department' );
	remove_role( 'rp_hr_reviewer' );
	remove_role( 'rp_procurement_reviewer' );
}

function rp_opportunities_migrate_department_role( $old_role, $new_role ) {
	$users = get_users(
		array(
			'role'   => $old_role,
			'fields' => array( 'ID' ),
		)
	);

	foreach ( $users as $user ) {
		$wp_user = new WP_User( $user->ID );
		$wp_user->set_role( $new_role );
	}
}

function rp_opportunities_create_tables() {
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$jobs_table      = $wpdb->prefix . 'rp_job_applications';
	$bids_table      = $wpdb->prefix . 'rp_bid_submissions';
	$notes_table     = $wpdb->prefix . 'rp_opportunity_submission_notes';

	$sql_jobs = "CREATE TABLE $jobs_table (
		id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
		opportunity_id BIGINT(20) UNSIGNED NOT NULL,
		submitted_at DATETIME NOT NULL,
		updated_at DATETIME NOT NULL,
		status VARCHAR(40) NOT NULL DEFAULT 'received',
		hiring_type VARCHAR(30) NOT NULL DEFAULT 'full_time',
		full_name VARCHAR(190) NOT NULL DEFAULT '',
		email VARCHAR(190) NOT NULL DEFAULT '',
		phone VARCHAR(80) DEFAULT '',
		fields LONGTEXT DEFAULT NULL,
		attachment_ids TEXT DEFAULT NULL,
		consent TINYINT(1) NOT NULL DEFAULT 0,
		ip_address VARCHAR(45) DEFAULT '',
		user_agent TEXT DEFAULT NULL,
		last_updated_by BIGINT(20) UNSIGNED DEFAULT NULL,
		success_email_sent_at DATETIME DEFAULT NULL,
		unsuccessful_email_sent_at DATETIME DEFAULT NULL,
		PRIMARY KEY (id),
		KEY opportunity_id (opportunity_id),
		KEY status (status),
		KEY submitted_at (submitted_at),
		KEY email (email)
	) $charset_collate;";

	$sql_bids = "CREATE TABLE $bids_table (
		id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
		opportunity_id BIGINT(20) UNSIGNED NOT NULL,
		submitted_at DATETIME NOT NULL,
		updated_at DATETIME NOT NULL,
		status VARCHAR(40) NOT NULL DEFAULT 'received',
		company_name VARCHAR(190) NOT NULL DEFAULT '',
		contact_person VARCHAR(190) NOT NULL DEFAULT '',
		email VARCHAR(190) NOT NULL DEFAULT '',
		phone VARCHAR(80) DEFAULT '',
		message LONGTEXT DEFAULT NULL,
		fields LONGTEXT DEFAULT NULL,
		attachment_ids TEXT DEFAULT NULL,
		consent TINYINT(1) NOT NULL DEFAULT 0,
		ip_address VARCHAR(45) DEFAULT '',
		user_agent TEXT DEFAULT NULL,
		last_updated_by BIGINT(20) UNSIGNED DEFAULT NULL,
		success_email_sent_at DATETIME DEFAULT NULL,
		unsuccessful_email_sent_at DATETIME DEFAULT NULL,
		PRIMARY KEY (id),
		KEY opportunity_id (opportunity_id),
		KEY status (status),
		KEY submitted_at (submitted_at),
		KEY email (email)
	) $charset_collate;";

	$sql_notes = "CREATE TABLE $notes_table (
		id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
		submission_type VARCHAR(20) NOT NULL DEFAULT '',
		submission_id BIGINT(20) UNSIGNED NOT NULL,
		user_id BIGINT(20) UNSIGNED DEFAULT NULL,
		note_type VARCHAR(30) NOT NULL DEFAULT 'internal',
		old_status VARCHAR(40) DEFAULT '',
		new_status VARCHAR(40) DEFAULT '',
		email_type VARCHAR(40) DEFAULT '',
		note LONGTEXT NOT NULL,
		created_at DATETIME NOT NULL,
		PRIMARY KEY (id),
		KEY submission (submission_type, submission_id),
		KEY user_id (user_id),
		KEY created_at (created_at)
	) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql_jobs );
	dbDelta( $sql_bids );
	dbDelta( $sql_notes );
}

function rp_opportunities_create_pages() {
	$pages = array(
		'opportunities'               => array(
			'title'   => __( 'Opportunities', 'rp-resource-hub' ),
			'content' => '[rp_opportunities]',
		),
		'job-ads'                     => array(
			'title'   => __( 'Job Ads', 'rp-resource-hub' ),
			'content' => '[rp_opportunities type="job"]',
		),
		'invitations-to-bid'          => array(
			'title'   => __( 'Invitations to Bid', 'rp-resource-hub' ),
			'content' => '[rp_opportunities type="itb"]',
		),
		'submit-opportunity'          => array(
			'title'   => __( 'Submit Opportunity', 'rp-resource-hub' ),
			'content' => '[rp_submit_opportunity]',
		),
		'submit-job-opportunity'      => array(
			'title'   => __( 'Submit Job Posting', 'rp-resource-hub' ),
			'content' => '[rp_submit_job_opportunity]',
		),
		'submit-invitation-to-bid'    => array(
			'title'   => __( 'Submit Invitation to Bid', 'rp-resource-hub' ),
			'content' => '[rp_submit_itb_opportunity]',
		),
		'job-applications-dashboard'  => array(
			'title'   => __( 'Job Applications Dashboard', 'rp-resource-hub' ),
			'content' => '[rp_job_applications_dashboard]',
		),
		'bid-submissions-dashboard'   => array(
			'title'   => __( 'Bid Submissions Dashboard', 'rp-resource-hub' ),
			'content' => '[rp_bid_submissions_dashboard]',
		),
	);

	foreach ( $pages as $slug => $page ) {
		$existing = get_page_by_path( $slug );
		if ( $existing ) {
			if ( false === strpos( $existing->post_content, $page['content'] ) ) {
				wp_update_post(
					array(
						'ID'           => $existing->ID,
						'post_content' => $page['content'],
					)
				);
			}
			continue;
		}

		wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_name'    => $slug,
				'post_title'   => $page['title'],
				'post_content' => $page['content'],
			)
		);
	}
}

function rp_opportunities_activate() {
	rp_opportunities_register_post_type();
	rp_opportunities_create_tables();
	rp_opportunities_apply_roles_and_caps();
	rp_opportunities_create_pages();
	rp_opportunities_publish_pending_posts();
	flush_rewrite_rules();
	update_option( 'rp_opportunities_version', RP_OPPORTUNITIES_VERSION );
}
register_activation_hook( RP_RESOURCE_HUB_FILE, 'rp_opportunities_activate' );

function rp_opportunities_publish_pending_posts() {
	$pending = get_posts(
		array(
			'post_type'      => 'rp_opportunity',
			'post_status'    => 'pending',
			'posts_per_page' => -1,
			'fields'         => 'ids',
		)
	);

	foreach ( $pending as $post_id ) {
		wp_update_post(
			array(
				'ID'          => absint( $post_id ),
				'post_status' => 'publish',
			)
		);
	}
}

function rp_opportunities_maybe_upgrade() {
	if ( RP_OPPORTUNITIES_VERSION === get_option( 'rp_opportunities_version' ) && get_page_by_path( 'opportunities' ) && get_page_by_path( 'job-ads' ) && get_page_by_path( 'invitations-to-bid' ) && get_page_by_path( 'submit-job-opportunity' ) && get_page_by_path( 'submit-invitation-to-bid' ) && get_page_by_path( 'job-applications-dashboard' ) && get_page_by_path( 'bid-submissions-dashboard' ) ) {
		return;
	}

	rp_opportunities_create_tables();
	rp_opportunities_apply_roles_and_caps();
	rp_opportunities_create_pages();
	rp_opportunities_publish_pending_posts();
	flush_rewrite_rules();
	update_option( 'rp_opportunities_version', RP_OPPORTUNITIES_VERSION );
}
add_action( 'init', 'rp_opportunities_maybe_upgrade', 30 );

function rp_opportunities_add_meta_boxes() {
	add_meta_box(
		'rp-opportunity-details',
		__( 'Opportunity Details', 'rp-resource-hub' ),
		'rp_opportunities_render_meta_box',
		'rp_opportunity',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'rp_opportunities_add_meta_boxes' );

function rp_opportunities_meta_fields() {
	return array(
		'_rp_opportunity_type',
		'_rp_opportunity_hiring_type',
		'_rp_opportunity_deadline',
		'_rp_opportunity_location',
		'_rp_opportunity_employment_type',
		'_rp_opportunity_reference_number',
		'_rp_opportunity_contact_email',
		'_rp_opportunity_bid_opening_date',
		'_rp_opportunity_clarification_period',
		'_rp_opportunity_duration',
		'_rp_opportunity_deliverables',
		'_rp_opportunity_require_portfolio',
		'_rp_opportunity_manual_status',
	);
}

function rp_opportunities_render_meta_box( $post ) {
	wp_nonce_field( 'rp_opportunity_save_meta', 'rp_opportunity_meta_nonce' );
	$type          = get_post_meta( $post->ID, '_rp_opportunity_type', true );
	$hiring_type   = get_post_meta( $post->ID, '_rp_opportunity_hiring_type', true );
	$deadline      = get_post_meta( $post->ID, '_rp_opportunity_deadline', true );
	$manual_status = get_post_meta( $post->ID, '_rp_opportunity_manual_status', true );
	$type          = $type && isset( rp_opportunities_type_options()[ $type ] ) ? $type : 'job';
	$hiring_type   = $hiring_type && isset( rp_opportunities_hiring_type_options()[ $hiring_type ] ) ? $hiring_type : 'full_time';
	?>
	<div class="rp-opportunity-admin-grid">
		<p>
			<label for="rp_opportunity_type"><strong><?php esc_html_e( 'Posting type', 'rp-resource-hub' ); ?></strong></label><br>
			<select id="rp_opportunity_type" name="_rp_opportunity_type">
				<?php foreach ( rp_opportunities_type_options() as $value => $label ) : ?>
					<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $type, $value ); ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>
		<p>
			<label for="rp_opportunity_hiring_type"><strong><?php esc_html_e( 'Hiring type', 'rp-resource-hub' ); ?></strong></label><br>
			<select id="rp_opportunity_hiring_type" name="_rp_opportunity_hiring_type">
				<?php foreach ( rp_opportunities_hiring_type_options() as $value => $label ) : ?>
					<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $hiring_type, $value ); ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>
		<p>
			<label for="rp_opportunity_deadline"><strong><?php esc_html_e( 'Deadline', 'rp-resource-hub' ); ?></strong></label><br>
			<input id="rp_opportunity_deadline" type="datetime-local" name="_rp_opportunity_deadline" value="<?php echo esc_attr( $deadline ); ?>">
		</p>
		<p>
			<label for="rp_opportunity_manual_status"><strong><?php esc_html_e( 'Posting status override', 'rp-resource-hub' ); ?></strong></label><br>
			<select id="rp_opportunity_manual_status" name="_rp_opportunity_manual_status">
				<option value="" <?php selected( $manual_status, '' ); ?>><?php esc_html_e( 'Automatic by deadline', 'rp-resource-hub' ); ?></option>
				<option value="open" <?php selected( $manual_status, 'open' ); ?>><?php esc_html_e( 'Force Open', 'rp-resource-hub' ); ?></option>
				<option value="closed" <?php selected( $manual_status, 'closed' ); ?>><?php esc_html_e( 'Force Closed', 'rp-resource-hub' ); ?></option>
			</select>
		</p>
		<p>
			<label for="rp_opportunity_location"><strong><?php esc_html_e( 'Location / duty station', 'rp-resource-hub' ); ?></strong></label><br>
			<input id="rp_opportunity_location" type="text" name="_rp_opportunity_location" value="<?php echo esc_attr( get_post_meta( $post->ID, '_rp_opportunity_location', true ) ); ?>" class="widefat">
		</p>
		<p>
			<label for="rp_opportunity_employment_type"><strong><?php esc_html_e( 'Employment type', 'rp-resource-hub' ); ?></strong></label><br>
			<input id="rp_opportunity_employment_type" type="text" name="_rp_opportunity_employment_type" value="<?php echo esc_attr( get_post_meta( $post->ID, '_rp_opportunity_employment_type', true ) ); ?>" class="widefat">
		</p>
		<p>
			<label for="rp_opportunity_reference_number"><strong><?php esc_html_e( 'ITB reference number', 'rp-resource-hub' ); ?></strong></label><br>
			<input id="rp_opportunity_reference_number" type="text" name="_rp_opportunity_reference_number" value="<?php echo esc_attr( get_post_meta( $post->ID, '_rp_opportunity_reference_number', true ) ); ?>" class="widefat">
		</p>
		<p>
			<label for="rp_opportunity_contact_email"><strong><?php esc_html_e( 'Contact email', 'rp-resource-hub' ); ?></strong></label><br>
			<input id="rp_opportunity_contact_email" type="email" name="_rp_opportunity_contact_email" value="<?php echo esc_attr( get_post_meta( $post->ID, '_rp_opportunity_contact_email', true ) ); ?>" class="widefat">
		</p>
		<p>
			<label for="rp_opportunity_bid_opening_date"><strong><?php esc_html_e( 'Bid opening date', 'rp-resource-hub' ); ?></strong></label><br>
			<input id="rp_opportunity_bid_opening_date" type="datetime-local" name="_rp_opportunity_bid_opening_date" value="<?php echo esc_attr( get_post_meta( $post->ID, '_rp_opportunity_bid_opening_date', true ) ); ?>">
		</p>
		<p>
			<label for="rp_opportunity_clarification_period"><strong><?php esc_html_e( 'Clarification period', 'rp-resource-hub' ); ?></strong></label><br>
			<input id="rp_opportunity_clarification_period" type="text" name="_rp_opportunity_clarification_period" value="<?php echo esc_attr( get_post_meta( $post->ID, '_rp_opportunity_clarification_period', true ) ); ?>" class="widefat">
		</p>
		<p>
			<label for="rp_opportunity_duration"><strong><?php esc_html_e( 'Consultancy duration of engagement', 'rp-resource-hub' ); ?></strong></label><br>
			<input id="rp_opportunity_duration" type="text" name="_rp_opportunity_duration" value="<?php echo esc_attr( get_post_meta( $post->ID, '_rp_opportunity_duration', true ) ); ?>" class="widefat">
		</p>
		<p>
			<label for="rp_opportunity_deliverables"><strong><?php esc_html_e( 'Expected deliverables / scope notes', 'rp-resource-hub' ); ?></strong></label><br>
			<textarea id="rp_opportunity_deliverables" name="_rp_opportunity_deliverables" class="widefat" rows="4"><?php echo esc_textarea( get_post_meta( $post->ID, '_rp_opportunity_deliverables', true ) ); ?></textarea>
		</p>
		<p>
			<label><input type="checkbox" name="_rp_opportunity_require_portfolio" value="1" <?php checked( get_post_meta( $post->ID, '_rp_opportunity_require_portfolio', true ), '1' ); ?>> <?php esc_html_e( 'Require portfolio/proof of work for consultant applications', 'rp-resource-hub' ); ?></label>
		</p>
		<p>
			<label for="rp_opportunity_tor"><strong><?php esc_html_e( 'Terms of Reference document', 'rp-resource-hub' ); ?></strong></label><br>
			<input id="rp_opportunity_tor" type="file" name="rp_opportunity_tor" accept=".pdf,.doc,.docx">
			<?php echo wp_kses_post( rp_opportunities_admin_attachment_link( get_post_meta( $post->ID, '_rp_opportunity_tor_id', true ) ) ); ?>
		</p>
		<p>
			<label for="rp_opportunity_document"><strong><?php esc_html_e( 'Additional posting document', 'rp-resource-hub' ); ?></strong></label><br>
			<input id="rp_opportunity_document" type="file" name="rp_opportunity_document" accept=".pdf,.doc,.docx,.xls,.xlsx,.zip">
			<?php echo wp_kses_post( rp_opportunities_admin_attachment_link( get_post_meta( $post->ID, '_rp_opportunity_document_id', true ) ) ); ?>
		</p>
	</div>
	<?php
}

function rp_opportunities_admin_attachment_link( $attachment_id ) {
	$attachment_id = absint( $attachment_id );
	if ( ! $attachment_id ) {
		return '';
	}
	$url   = wp_get_attachment_url( $attachment_id );
	$title = get_the_title( $attachment_id );
	if ( ! $url ) {
		return '';
	}
	return '<br><a href="' . esc_url( $url ) . '" target="_blank" rel="noopener">' . esc_html( $title ? $title : __( 'View current file', 'rp-resource-hub' ) ) . '</a>';
}

function rp_opportunities_save_meta( $post_id ) {
	if ( ! isset( $_POST['rp_opportunity_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['rp_opportunity_meta_nonce'] ) ), 'rp_opportunity_save_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	foreach ( rp_opportunities_meta_fields() as $field ) {
		if ( '_rp_opportunity_require_portfolio' === $field ) {
			update_post_meta( $post_id, $field, ! empty( $_POST[ $field ] ) ? '1' : '0' );
			continue;
		}
		$value = isset( $_POST[ $field ] ) ? sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) : '';
		if ( '_rp_opportunity_deliverables' === $field ) {
			$value = isset( $_POST[ $field ] ) ? wp_kses_post( wp_unslash( $_POST[ $field ] ) ) : '';
		} elseif ( '_rp_opportunity_contact_email' === $field ) {
			$value = sanitize_email( $value );
		}
		update_post_meta( $post_id, $field, $value );
	}

	rp_opportunities_save_admin_file( $post_id, 'rp_opportunity_tor', '_rp_opportunity_tor_id' );
	rp_opportunities_save_admin_file( $post_id, 'rp_opportunity_document', '_rp_opportunity_document_id' );
}
add_action( 'save_post_rp_opportunity', 'rp_opportunities_save_meta' );

function rp_opportunities_save_admin_file( $post_id, $field, $meta_key ) {
	if ( empty( $_FILES[ $field ]['name'] ) ) {
		return;
	}
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';
	$attachment_id = media_handle_upload( $field, $post_id );
	if ( ! is_wp_error( $attachment_id ) ) {
		update_post_meta( $post_id, $meta_key, absint( $attachment_id ) );
	}
}

function rp_opportunities_submit_notice_html() {
	if ( empty( $_GET['rp_opp_submit_notice'] ) ) {
		return;
	}
	$key = sanitize_key( wp_unslash( $_GET['rp_opp_submit_notice'] ) );
	$messages = array(
		'submitted' => __( 'Your opportunity was published.', 'rp-resource-hub' ),
		'error'     => __( 'The opportunity could not be submitted. Please check the required fields and try again.', 'rp-resource-hub' ),
	);
	if ( isset( $messages[ $key ] ) ) {
		echo '<div class="rp-form-success">' . esc_html( $messages[ $key ] ) . '</div>';
	}
}

function rp_opportunities_submit_redirect_for_type( $type = '' ) {
	if ( 'job' === $type ) {
		return home_url( '/submit-job-opportunity/' );
	}
	if ( 'itb' === $type ) {
		return home_url( '/submit-invitation-to-bid/' );
	}
	return home_url( '/submit-opportunity/' );
}

function rp_opportunities_submit_shortcode( $atts = array() ) {
	$atts = shortcode_atts(
		array(
			'type' => '',
		),
		$atts,
		'rp_submit_opportunity'
	);
	$forced_type = sanitize_key( $atts['type'] );
	if ( ! isset( rp_opportunities_type_options()[ $forced_type ] ) ) {
		$forced_type = '';
	}

	if ( ! rp_opportunities_user_can_submit() ) {
		$login_redirect = rp_opportunities_submit_redirect_for_type( $forced_type );
		return '<div class="rp-empty-state"><p>' . esc_html__( 'You must be authorized to submit this type of posting.', 'rp-resource-hub' ) . '</p><p><a class="rp-button" href="' . esc_url( wp_login_url( $login_redirect ) ) . '">' . esc_html__( 'Log in', 'rp-resource-hub' ) . '</a></p></div>';
	}

	$allowed_types = rp_opportunities_allowed_submit_types();
	if ( $forced_type ) {
		$allowed_types = in_array( $forced_type, $allowed_types, true ) ? array( $forced_type ) : array();
	}
	if ( ! $allowed_types ) {
		return '<div class="rp-empty-state"><p>' . esc_html__( 'Your account does not have access to submit this type of posting.', 'rp-resource-hub' ) . '</p></div>';
	}

	$single_type = 1 === count( $allowed_types ) ? $allowed_types[0] : '';
	$form_action = rp_opportunities_submit_redirect_for_type( $single_type );
	$title = __( 'Submit Opportunity', 'rp-resource-hub' );
	$subtitle = __( 'Create and publish a job ad or invitation to bid.', 'rp-resource-hub' );
	if ( 'job' === $single_type ) {
		$title = __( 'Submit Job Posting', 'rp-resource-hub' );
		$subtitle = __( 'Create and publish a job opportunity.', 'rp-resource-hub' );
	} elseif ( 'itb' === $single_type ) {
		$title = __( 'Submit Invitation to Bid', 'rp-resource-hub' );
		$subtitle = __( 'Create and publish a procurement posting.', 'rp-resource-hub' );
	}

	ob_start();
	?>
	<div class="rp-submit-opportunity-shell">
		<div class="rp-dashboard-header">
			<h2 class="rp-dashboard-title"><?php echo esc_html( $title ); ?></h2>
			<p class="rp-dashboard-subtitle"><?php echo esc_html( $subtitle ); ?></p>
		</div>
		<?php rp_opportunities_submit_notice_html(); ?>
		<form class="rp-upload-form rp-opportunity-form rp-submit-opportunity-form" method="post" action="<?php echo esc_url( $form_action ); ?>" enctype="multipart/form-data">
			<input type="hidden" name="rp_submit_opportunity_action" value="1">
			<?php wp_nonce_field( 'rp_submit_opportunity', 'rp_submit_opportunity_nonce' ); ?>
			<?php rp_opportunities_text_field( 'opportunity_title', __( 'Posting title', 'rp-resource-hub' ), true ); ?>
			<?php if ( $single_type ) : ?>
				<input type="hidden" name="opportunity_type" value="<?php echo esc_attr( $single_type ); ?>">
			<?php else : ?>
				<div class="rp-field">
					<label for="opportunity_type"><?php esc_html_e( 'Posting type', 'rp-resource-hub' ); ?> <span aria-hidden="true">*</span></label>
					<select id="opportunity_type" name="opportunity_type" required>
						<?php foreach ( $allowed_types as $type ) : ?>
							<option value="<?php echo esc_attr( $type ); ?>"><?php echo esc_html( rp_opportunities_type_options()[ $type ] ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			<?php endif; ?>
			<?php if ( ! $single_type ) : ?>
				<div class="rp-form-choice-note">
					<p><?php esc_html_e( 'For clearer workflows, use the dedicated Submit Job Posting or Submit Invitation to Bid pages from the user menu.', 'rp-resource-hub' ); ?></p>
				</div>
			<?php endif; ?>
			<?php if ( 'itb' !== $single_type ) : ?>
				<?php rp_opportunities_select_field( 'hiring_type', __( 'Hiring type', 'rp-resource-hub' ), rp_opportunities_hiring_type_options(), false, 'full_time', 'rp-opportunity-hiring-type' ); ?>
			<?php endif; ?>
			<?php rp_opportunities_date_field( 'deadline', __( 'Deadline', 'rp-resource-hub' ), true ); ?>
			<?php if ( 'itb' !== $single_type ) : ?>
				<?php rp_opportunities_text_field( 'location', __( 'Location / duty station', 'rp-resource-hub' ), false ); ?>
			<?php endif; ?>
			<?php if ( 'job' !== $single_type ) : ?>
				<?php rp_opportunities_text_field( 'reference_number', __( 'ITB reference number', 'rp-resource-hub' ), false ); ?>
			<?php endif; ?>
			<?php if ( 'itb' !== $single_type ) : ?>
				<div class="rp-consultant-only-field">
					<?php rp_opportunities_text_field( 'duration', __( 'Duration of engagement', 'rp-resource-hub' ), false ); ?>
					<label class="rp-checkbox-line"><input type="checkbox" name="require_portfolio" value="1"> <?php esc_html_e( 'Require portfolio/proof of work for consultant applications', 'rp-resource-hub' ); ?></label>
				</div>
			<?php endif; ?>
			<?php rp_opportunities_textarea_field( 'description', __( 'Posting description', 'rp-resource-hub' ), true ); ?>
			<?php rp_opportunities_textarea_field( 'deliverables', 'itb' === $single_type ? __( 'Procurement scope / requirements notes', 'rp-resource-hub' ) : __( 'Expected deliverables / scope notes', 'rp-resource-hub' ), false ); ?>
			<?php if ( 'itb' !== $single_type ) : ?>
				<?php rp_opportunities_file_field( 'opportunity_tor', __( 'Terms of Reference document', 'rp-resource-hub' ), false ); ?>
			<?php endif; ?>
			<?php rp_opportunities_file_field( 'opportunity_document', 'itb' === $single_type ? __( 'Bid / procurement document', 'rp-resource-hub' ) : __( 'Additional posting document', 'rp-resource-hub' ), false, 'bid' ); ?>
			<button class="rp-button" type="submit"><?php esc_html_e( 'Publish Posting', 'rp-resource-hub' ); ?></button>
		</form>
		<?php if ( 'itb' !== $single_type ) : ?>
			<script>
			(function() {
				var select = document.querySelector('.rp-opportunity-hiring-type');
				var field = document.querySelector('.rp-consultant-only-field');
				if (!select || !field) return;
				function syncConsultantField() {
					field.style.display = select.value === 'consultant' ? '' : 'none';
				}
				select.addEventListener('change', syncConsultantField);
				syncConsultantField();
			})();
			</script>
		<?php endif; ?>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'rp_submit_opportunity', 'rp_opportunities_submit_shortcode' );
add_shortcode( 'rp_submit_job_opportunity', function( $atts = array() ) {
	$atts['type'] = 'job';
	return rp_opportunities_submit_shortcode( $atts );
} );
add_shortcode( 'rp_submit_itb_opportunity', function( $atts = array() ) {
	$atts['type'] = 'itb';
	return rp_opportunities_submit_shortcode( $atts );
} );

function rp_opportunities_handle_frontend_submit() {
	$type = isset( $_POST['opportunity_type'] ) ? sanitize_key( wp_unslash( $_POST['opportunity_type'] ) ) : '';
	$redirect = rp_opportunities_submit_redirect_for_type( $type );
	if ( ! rp_opportunities_user_can_submit() || ! isset( $_POST['rp_submit_opportunity_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['rp_submit_opportunity_nonce'] ) ), 'rp_submit_opportunity' ) ) {
		wp_safe_redirect( add_query_arg( 'rp_opp_submit_notice', 'error', $redirect ) );
		exit;
	}

	$allowed_types = rp_opportunities_allowed_submit_types();
	if ( ! in_array( $type, $allowed_types, true ) ) {
		wp_safe_redirect( add_query_arg( 'rp_opp_submit_notice', 'error', $redirect ) );
		exit;
	}

	$title       = rp_opportunities_sanitize_text_post( 'opportunity_title' );
	$description = rp_opportunities_sanitize_textarea_post( 'description' );
	if ( ! $title || ! $description ) {
		wp_safe_redirect( add_query_arg( 'rp_opp_submit_notice', 'error', $redirect ) );
		exit;
	}

	$post_id = wp_insert_post(
		array(
			'post_type'    => 'rp_opportunity',
			'post_status'  => 'publish',
			'post_title'   => $title,
			'post_content' => $description,
			'post_author'  => get_current_user_id(),
		)
	);

	if ( ! $post_id || is_wp_error( $post_id ) ) {
		wp_safe_redirect( add_query_arg( 'rp_opp_submit_notice', 'error', $redirect ) );
		exit;
	}

	$deadline_date = rp_opportunities_sanitize_text_post( 'deadline' );
	$deadline      = $deadline_date ? $deadline_date . ' 23:59' : '';
	$hiring_type   = isset( $_POST['hiring_type'] ) && isset( rp_opportunities_hiring_type_options()[ sanitize_key( wp_unslash( $_POST['hiring_type'] ) ) ] ) ? sanitize_key( wp_unslash( $_POST['hiring_type'] ) ) : 'full_time';

	$meta = array(
		'_rp_opportunity_type'                 => $type,
		'_rp_opportunity_hiring_type'          => $hiring_type,
		'_rp_opportunity_deadline'             => $deadline,
		'_rp_opportunity_location'             => rp_opportunities_sanitize_text_post( 'location' ),
		'_rp_opportunity_employment_type'      => '',
		'_rp_opportunity_reference_number'     => rp_opportunities_sanitize_text_post( 'reference_number' ),
		'_rp_opportunity_contact_email'        => '',
		'_rp_opportunity_bid_opening_date'     => '',
		'_rp_opportunity_clarification_period' => '',
		'_rp_opportunity_duration'             => 'consultant' === $hiring_type ? rp_opportunities_sanitize_text_post( 'duration' ) : '',
		'_rp_opportunity_deliverables'         => rp_opportunities_sanitize_textarea_post( 'deliverables' ),
		'_rp_opportunity_require_portfolio'    => ! empty( $_POST['require_portfolio'] ) ? '1' : '0',
	);

	foreach ( $meta as $key => $value ) {
		update_post_meta( $post_id, $key, $value );
	}

	rp_opportunities_save_admin_file( $post_id, 'opportunity_tor', '_rp_opportunity_tor_id' );
	rp_opportunities_save_admin_file( $post_id, 'opportunity_document', '_rp_opportunity_document_id' );

	wp_safe_redirect( add_query_arg( 'rp_opp_submit_notice', 'submitted', $redirect ) );
	exit;
}
add_action( 'admin_post_rp_submit_opportunity', 'rp_opportunities_handle_frontend_submit' );

function rp_opportunities_maybe_handle_frontend_submit() {
	if ( 'POST' === $_SERVER['REQUEST_METHOD'] && ! empty( $_POST['rp_submit_opportunity_action'] ) ) {
		rp_opportunities_handle_frontend_submit();
	}
	if ( 'POST' === $_SERVER['REQUEST_METHOD'] && ! empty( $_POST['rp_update_opportunity_action'] ) ) {
		rp_opportunities_handle_frontend_update();
	}
}
add_action( 'template_redirect', 'rp_opportunities_maybe_handle_frontend_submit', 1 );

function rp_opportunities_handle_frontend_update() {
	$post_id = isset( $_POST['opportunity_id'] ) ? absint( $_POST['opportunity_id'] ) : 0;
	$type = isset( $_POST['opportunity_type'] ) ? sanitize_key( wp_unslash( $_POST['opportunity_type'] ) ) : '';
	$dashboard = 'itb' === $type ? home_url( '/bid-submissions-dashboard/' ) : home_url( '/job-applications-dashboard/' );
	$redirect = add_query_arg( 'edit_opportunity_id', $post_id, $dashboard );
	if ( ! $post_id || ! wp_verify_nonce( isset( $_POST['rp_update_opportunity_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['rp_update_opportunity_nonce'] ) ) : '', 'rp_update_opportunity_' . $post_id ) ) {
		wp_safe_redirect( add_query_arg( 'rp_opp_submit_notice', 'error', $redirect ) );
		exit;
	}
	if ( ( 'job' === $type && ! rp_opportunities_user_can_manage_jobs() ) || ( 'itb' === $type && ! rp_opportunities_user_can_manage_bids() ) ) {
		wp_safe_redirect( add_query_arg( 'rp_opp_submit_notice', 'error', $redirect ) );
		exit;
	}
	if ( ! get_post( $post_id ) || 'rp_opportunity' !== get_post_type( $post_id ) || rp_opportunities_get_type( $post_id ) !== $type ) {
		wp_safe_redirect( add_query_arg( 'rp_opp_submit_notice', 'error', $redirect ) );
		exit;
	}

	$title = rp_opportunities_sanitize_text_post( 'opportunity_title' );
	$description = rp_opportunities_sanitize_textarea_post( 'description' );
	if ( ! $title || ! $description ) {
		wp_safe_redirect( add_query_arg( 'rp_opp_submit_notice', 'error', $redirect ) );
		exit;
	}

	wp_update_post(
		array(
			'ID'           => $post_id,
			'post_title'   => $title,
			'post_content' => $description,
			'post_status'  => 'publish',
		)
	);

	$deadline_date = rp_opportunities_sanitize_text_post( 'deadline' );
	$deadline = $deadline_date ? $deadline_date . ' 23:59' : '';
	$manual_status = isset( $_POST['manual_status'] ) ? sanitize_key( wp_unslash( $_POST['manual_status'] ) ) : '';
	if ( ! in_array( $manual_status, array( '', 'open', 'closed' ), true ) ) {
		$manual_status = '';
	}
	$hiring_type = isset( $_POST['hiring_type'] ) && isset( rp_opportunities_hiring_type_options()[ sanitize_key( wp_unslash( $_POST['hiring_type'] ) ) ] ) ? sanitize_key( wp_unslash( $_POST['hiring_type'] ) ) : 'full_time';

	$meta = array(
		'_rp_opportunity_deadline'             => $deadline,
		'_rp_opportunity_manual_status'        => $manual_status,
		'_rp_opportunity_hiring_type'          => 'job' === $type ? $hiring_type : 'full_time',
		'_rp_opportunity_location'             => 'job' === $type ? rp_opportunities_sanitize_text_post( 'location' ) : '',
		'_rp_opportunity_employment_type'      => '',
		'_rp_opportunity_reference_number'     => 'itb' === $type ? rp_opportunities_sanitize_text_post( 'reference_number' ) : '',
		'_rp_opportunity_contact_email'        => '',
		'_rp_opportunity_bid_opening_date'     => '',
		'_rp_opportunity_clarification_period' => '',
		'_rp_opportunity_duration'             => ( 'job' === $type && 'consultant' === $hiring_type ) ? rp_opportunities_sanitize_text_post( 'duration' ) : '',
		'_rp_opportunity_deliverables'         => rp_opportunities_sanitize_textarea_post( 'deliverables' ),
		'_rp_opportunity_require_portfolio'    => ( 'job' === $type && ! empty( $_POST['require_portfolio'] ) ) ? '1' : '0',
	);
	foreach ( $meta as $key => $value ) {
		update_post_meta( $post_id, $key, $value );
	}

	rp_opportunities_save_admin_file( $post_id, 'opportunity_tor', '_rp_opportunity_tor_id' );
	rp_opportunities_save_admin_file( $post_id, 'opportunity_document', '_rp_opportunity_document_id' );

	wp_safe_redirect( add_query_arg( 'rp_opp_submit_notice', 'submitted', $redirect ) );
	exit;
}

function rp_opportunities_get_type( $post_id ) {
	$type = get_post_meta( $post_id, '_rp_opportunity_type', true );
	return isset( rp_opportunities_type_options()[ $type ] ) ? $type : 'job';
}

function rp_opportunities_get_hiring_type( $post_id ) {
	$type = get_post_meta( $post_id, '_rp_opportunity_hiring_type', true );
	return isset( rp_opportunities_hiring_type_options()[ $type ] ) ? $type : 'full_time';
}

function rp_opportunities_is_open( $post_id ) {
	$override = get_post_meta( $post_id, '_rp_opportunity_manual_status', true );
	if ( 'open' === $override ) {
		return true;
	}
	if ( 'closed' === $override ) {
		return false;
	}
	$deadline = get_post_meta( $post_id, '_rp_opportunity_deadline', true );
	if ( ! $deadline ) {
		return true;
	}
	return strtotime( $deadline ) >= current_time( 'timestamp' );
}

function rp_opportunities_format_datetime( $value ) {
	if ( ! $value ) {
		return '';
	}
	$timestamp = strtotime( $value );
	return $timestamp ? date_i18n( 'F j, Y g:i A', $timestamp ) : $value;
}

function rp_opportunities_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'type' => '',
		),
		$atts,
		'rp_opportunities'
	);
	$forced_type = sanitize_key( $atts['type'] );
	$type   = $forced_type ? $forced_type : ( isset( $_GET['opportunity_type'] ) ? sanitize_key( wp_unslash( $_GET['opportunity_type'] ) ) : '' );
	$status = isset( $_GET['opportunity_status'] ) ? sanitize_key( wp_unslash( $_GET['opportunity_status'] ) ) : 'open';
	if ( ! isset( rp_opportunities_type_options()[ $type ] ) ) {
		$type = '';
	}
	if ( ! in_array( $status, array( 'open', 'archive', 'all' ), true ) ) {
		$status = 'open';
	}

	$query = new WP_Query(
		array(
			'post_type'      => 'rp_opportunity',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'date',
			'order'          => 'DESC',
		)
	);

	$title = __( 'Opportunities', 'rp-resource-hub' );
	$subtitle = __( 'Browse ACCORD job opportunities and invitations to bid.', 'rp-resource-hub' );
	if ( 'job' === $type && $forced_type ) {
		$title = __( 'Job Ads', 'rp-resource-hub' );
		$subtitle = __( 'Browse current ACCORD job opportunities and submit applications online.', 'rp-resource-hub' );
	} elseif ( 'itb' === $type && $forced_type ) {
		$title = __( 'Invitations to Bid', 'rp-resource-hub' );
		$subtitle = __( 'Browse current ACCORD procurement opportunities and submit bid documents online.', 'rp-resource-hub' );
	}

	ob_start();
	?>
	<div class="rp-opportunities-shell">
		<div class="rp-dashboard-header">
			<h2 class="rp-dashboard-title"><?php echo esc_html( $title ); ?></h2>
			<p class="rp-dashboard-subtitle"><?php echo esc_html( $subtitle ); ?></p>
		</div>
		<form class="rp-opportunities-filter" method="get">
			<?php if ( ! $forced_type ) : ?>
				<label>
					<span><?php esc_html_e( 'Type', 'rp-resource-hub' ); ?></span>
					<select name="opportunity_type">
						<option value=""><?php esc_html_e( 'All opportunities', 'rp-resource-hub' ); ?></option>
						<?php foreach ( rp_opportunities_type_options() as $value => $label ) : ?>
							<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $type, $value ); ?>><?php echo esc_html( $label ); ?></option>
						<?php endforeach; ?>
					</select>
				</label>
			<?php endif; ?>
			<label>
				<span><?php esc_html_e( 'Status', 'rp-resource-hub' ); ?></span>
				<select name="opportunity_status">
					<option value="open" <?php selected( $status, 'open' ); ?>><?php esc_html_e( 'Open only', 'rp-resource-hub' ); ?></option>
					<option value="archive" <?php selected( $status, 'archive' ); ?>><?php esc_html_e( 'Archived / closed', 'rp-resource-hub' ); ?></option>
					<option value="all" <?php selected( $status, 'all' ); ?>><?php esc_html_e( 'All postings', 'rp-resource-hub' ); ?></option>
				</select>
			</label>
			<button class="rp-button" type="submit"><?php esc_html_e( 'Filter', 'rp-resource-hub' ); ?></button>
		</form>
		<div class="rp-opportunities-grid">
			<?php
			$count = 0;
			while ( $query->have_posts() ) :
				$query->the_post();
				$post_id = get_the_ID();
				$post_type = rp_opportunities_get_type( $post_id );
				$is_open = rp_opportunities_is_open( $post_id );
				if ( $type && $type !== $post_type ) {
					continue;
				}
				if ( 'open' === $status && ! $is_open ) {
					continue;
				}
				if ( 'archive' === $status && $is_open ) {
					continue;
				}
				$count++;
				?>
				<article class="rp-opportunity-card">
					<div class="rp-opportunity-card-top">
						<span class="rp-status-badge"><?php echo esc_html( rp_opportunities_type_options()[ $post_type ] ); ?></span>
						<span class="rp-status-badge <?php echo $is_open ? 'rp-opportunity-open' : 'rp-opportunity-closed'; ?>"><?php echo esc_html( $is_open ? __( 'Open', 'rp-resource-hub' ) : __( 'Closed', 'rp-resource-hub' ) ); ?></span>
					</div>
					<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<?php if ( get_post_meta( $post_id, '_rp_opportunity_deadline', true ) ) : ?>
						<p><strong><?php esc_html_e( 'Deadline:', 'rp-resource-hub' ); ?></strong> <?php echo esc_html( rp_opportunities_format_datetime( get_post_meta( $post_id, '_rp_opportunity_deadline', true ) ) ); ?></p>
					<?php endif; ?>
					<?php if ( get_post_meta( $post_id, '_rp_opportunity_location', true ) ) : ?>
						<p><strong><?php esc_html_e( 'Location:', 'rp-resource-hub' ); ?></strong> <?php echo esc_html( get_post_meta( $post_id, '_rp_opportunity_location', true ) ); ?></p>
					<?php endif; ?>
					<p><?php echo esc_html( get_the_excerpt() ? get_the_excerpt() : wp_trim_words( wp_strip_all_tags( get_the_content() ), 24 ) ); ?></p>
					<a class="rp-button" href="<?php the_permalink(); ?>"><?php echo esc_html( 'job' === $post_type ? __( 'View and Apply', 'rp-resource-hub' ) : __( 'View and Submit Bid', 'rp-resource-hub' ) ); ?></a>
				</article>
			<?php endwhile; wp_reset_postdata(); ?>
		</div>
		<?php if ( 0 === $count ) : ?>
			<div class="rp-empty-state"><p><?php esc_html_e( 'No opportunities match the selected filters.', 'rp-resource-hub' ); ?></p></div>
		<?php endif; ?>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'rp_opportunities', 'rp_opportunities_shortcode' );

function rp_opportunities_append_single_content( $content ) {
	if ( ! is_singular( 'rp_opportunity' ) || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}

	$post_id = get_the_ID();
	ob_start();
	echo wp_kses_post( $content );
	if ( 'job' === rp_opportunities_get_type( $post_id ) ) {
		rp_opportunities_render_job_intro();
	}
	rp_opportunities_render_single_details( $post_id );
	if ( rp_opportunities_is_open( $post_id ) ) {
		if ( 'job' === rp_opportunities_get_type( $post_id ) ) {
			rp_opportunities_render_job_form( $post_id );
		} else {
			rp_opportunities_render_bid_form( $post_id );
		}
	} else {
		echo '<div class="rp-empty-state"><p>' . esc_html__( 'This posting is closed. Submissions are no longer accepted.', 'rp-resource-hub' ) . '</p></div>';
	}
	return ob_get_clean();
}
add_filter( 'the_content', 'rp_opportunities_append_single_content', 20 );

function rp_opportunities_render_job_intro() {
	?>
	<section class="rp-opportunity-job-intro" aria-label="<?php esc_attr_e( 'ACCORD application information', 'rp-resource-hub' ); ?>">
		<div class="rp-opportunity-job-banner">
			<div class="rp-opportunity-job-brand"><?php esc_html_e( 'ACCORD', 'rp-resource-hub' ); ?></div>
			<p><?php esc_html_e( 'Join our team to help build resilient communities!', 'rp-resource-hub' ); ?></p>
		</div>
		<div class="rp-opportunity-job-intro-card">
			<h2><?php esc_html_e( "ACCORD's Application Form", 'rp-resource-hub' ); ?></h2>
			<p><?php esc_html_e( 'Assistance and Cooperation for Community Resilience and Development (ACCORD) Incorporated is one of the leading national organizations promoting and implementing the integration of disaster risk reduction, climate change adaptation, and ecosystem management and restoration as an approach to strengthening community resilience; working in partnership with the least-served, most vulnerable communities and applying innovative practices in humanitarian action and development programs towards inclusive resilience building.', 'rp-resource-hub' ); ?> <?php esc_html_e( 'Visit our website at', 'rp-resource-hub' ); ?> <a href="https://www.resilientphilippines.com">https://www.resilientphilippines.com</a> <?php esc_html_e( 'and our Facebook page at', 'rp-resource-hub' ); ?> <a href="https://www.facebook.com/accordinc">https://www.facebook.com/accordinc</a> <?php esc_html_e( 'to know more about us.', 'rp-resource-hub' ); ?></p>
			<p><em><?php esc_html_e( 'ACCORD is an', 'rp-resource-hub' ); ?> <strong><?php esc_html_e( 'equal-opportunity employer.', 'rp-resource-hub' ); ?></strong> <?php esc_html_e( 'We encourage all qualified applicants, regardless of race, gender, sexual orientation, religion, political views, ethnicity, disability, or other statuses to apply.', 'rp-resource-hub' ); ?></em></p>
			<p><em><?php esc_html_e( 'We uphold a', 'rp-resource-hub' ); ?> <strong><?php esc_html_e( 'zero-tolerance policy', 'rp-resource-hub' ); ?></strong> <?php esc_html_e( 'toward any form of abuse and we commit to the protection from sexual harassment, exploitation, and abuse, neglect, physical and emotional abuse of vulnerable adults and children, involving our employees and related personnel. All employees and associated personnel are expected to uphold these principles and contribute to a respectful and supportive environment. Job applicants will undergo screening, including checks with former employers for any history of misconduct or abuse, and employment offers are subject to satisfactory references and successful screening results. By submitting an application, the job applicant confirms his/her understanding of these recruitment procedures.', 'rp-resource-hub' ); ?></em></p>
		</div>
	</section>
	<?php
}

function rp_opportunities_render_single_details( $post_id ) {
	$type        = rp_opportunities_get_type( $post_id );
	$deadline    = get_post_meta( $post_id, '_rp_opportunity_deadline', true );
	$tor_id      = get_post_meta( $post_id, '_rp_opportunity_tor_id', true );
	$document_id = get_post_meta( $post_id, '_rp_opportunity_document_id', true );
	?>
	<section class="rp-opportunity-details">
		<div class="rp-opportunity-card-top">
			<span class="rp-status-badge"><?php echo esc_html( rp_opportunities_type_options()[ $type ] ); ?></span>
			<span class="rp-status-badge <?php echo rp_opportunities_is_open( $post_id ) ? 'rp-opportunity-open' : 'rp-opportunity-closed'; ?>"><?php echo esc_html( rp_opportunities_is_open( $post_id ) ? __( 'Open', 'rp-resource-hub' ) : __( 'Closed', 'rp-resource-hub' ) ); ?></span>
		</div>
		<dl class="rp-opportunity-meta">
			<?php if ( $deadline ) : ?><div><dt><?php esc_html_e( 'Deadline', 'rp-resource-hub' ); ?></dt><dd><?php echo esc_html( rp_opportunities_format_datetime( $deadline ) ); ?></dd></div><?php endif; ?>
			<?php if ( get_post_meta( $post_id, '_rp_opportunity_location', true ) ) : ?><div><dt><?php esc_html_e( 'Location', 'rp-resource-hub' ); ?></dt><dd><?php echo esc_html( get_post_meta( $post_id, '_rp_opportunity_location', true ) ); ?></dd></div><?php endif; ?>
			<?php if ( 'job' === $type ) : ?>
				<div><dt><?php esc_html_e( 'Hiring type', 'rp-resource-hub' ); ?></dt><dd><?php echo esc_html( rp_opportunities_hiring_type_options()[ rp_opportunities_get_hiring_type( $post_id ) ] ); ?></dd></div>
				<?php if ( get_post_meta( $post_id, '_rp_opportunity_duration', true ) ) : ?><div><dt><?php esc_html_e( 'Duration', 'rp-resource-hub' ); ?></dt><dd><?php echo esc_html( get_post_meta( $post_id, '_rp_opportunity_duration', true ) ); ?></dd></div><?php endif; ?>
			<?php else : ?>
				<?php if ( get_post_meta( $post_id, '_rp_opportunity_reference_number', true ) ) : ?><div><dt><?php esc_html_e( 'Reference number', 'rp-resource-hub' ); ?></dt><dd><?php echo esc_html( get_post_meta( $post_id, '_rp_opportunity_reference_number', true ) ); ?></dd></div><?php endif; ?>
			<?php endif; ?>
		</dl>
		<?php if ( get_post_meta( $post_id, '_rp_opportunity_deliverables', true ) ) : ?>
			<div class="rp-opportunity-note"><?php echo wpautop( wp_kses_post( get_post_meta( $post_id, '_rp_opportunity_deliverables', true ) ) ); ?></div>
		<?php endif; ?>
		<?php if ( $tor_id || $document_id ) : ?>
			<div class="rp-opportunity-downloads">
				<h3><?php esc_html_e( 'Documents', 'rp-resource-hub' ); ?></h3>
				<?php rp_opportunities_public_attachment_button( $tor_id, __( 'Download Terms of Reference', 'rp-resource-hub' ) ); ?>
				<?php rp_opportunities_public_attachment_button( $document_id, __( 'Download Posting Document', 'rp-resource-hub' ) ); ?>
			</div>
		<?php endif; ?>
		<?php if ( 'itb' === $type ) : ?>
			<div class="rp-opportunity-note">
				<h3><?php esc_html_e( 'Required ITB documents', 'rp-resource-hub' ); ?></h3>
				<ul>
					<li><?php esc_html_e( 'Quotation or proposal', 'rp-resource-hub' ); ?></li>
					<li><?php esc_html_e( "Copy of valid business or mayor's permit", 'rp-resource-hub' ); ?></li>
					<li><?php esc_html_e( 'Certificate of registration, if applicable', 'rp-resource-hub' ); ?></li>
					<li><?php esc_html_e( 'BIR 2303', 'rp-resource-hub' ); ?></li>
					<li><?php esc_html_e( 'Sample of official receipt/sales invoice', 'rp-resource-hub' ); ?></li>
				</ul>
			</div>
		<?php endif; ?>
	</section>
	<?php
}

function rp_opportunities_public_attachment_button( $attachment_id, $label ) {
	$attachment_id = absint( $attachment_id );
	if ( ! $attachment_id ) {
		return;
	}
	$url = wp_get_attachment_url( $attachment_id );
	if ( $url ) {
		echo '<a class="rp-button rp-button-secondary" href="' . esc_url( $url ) . '" target="_blank" rel="noopener">' . esc_html( $label ) . '</a> ';
	}
}

function rp_opportunities_notice_html() {
	if ( empty( $_GET['rp_opp_notice'] ) ) {
		return;
	}
	$key = sanitize_key( wp_unslash( $_GET['rp_opp_notice'] ) );
	$messages = array(
		'submitted' => __( 'Your submission has been received. Please check your email for confirmation.', 'rp-resource-hub' ),
		'error'     => __( 'Your submission could not be completed. Please check the required fields and try again.', 'rp-resource-hub' ),
	);
	if ( isset( $messages[ $key ] ) ) {
		echo '<div class="rp-form-success">' . esc_html( $messages[ $key ] ) . '</div>';
	}
}

function rp_opportunities_render_job_form( $post_id ) {
	$hiring_type = rp_opportunities_get_hiring_type( $post_id );
	rp_opportunities_notice_html();
	?>
	<section class="rp-opportunity-form-section">
		<h2><?php echo esc_html( 'consultant' === $hiring_type ? __( 'Submit Consultancy Application', 'rp-resource-hub' ) : __( 'Submit Job Application', 'rp-resource-hub' ) ); ?></h2>
		<form class="rp-upload-form rp-opportunity-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data">
			<input type="hidden" name="action" value="rp_job_application_submit">
			<input type="hidden" name="opportunity_id" value="<?php echo esc_attr( $post_id ); ?>">
			<?php wp_nonce_field( 'rp_job_application_submit_' . $post_id, 'rp_job_application_nonce' ); ?>
			<?php if ( 'consultant' === $hiring_type ) : ?>
				<?php rp_opportunities_text_field( 'full_name', __( 'Full name', 'rp-resource-hub' ), true ); ?>
				<?php rp_opportunities_email_field( 'email', __( 'Email address', 'rp-resource-hub' ), true ); ?>
				<?php rp_opportunities_text_field( 'phone', __( 'Phone number', 'rp-resource-hub' ), true ); ?>
				<?php rp_opportunities_textarea_field( 'cover_message', __( 'Cover message', 'rp-resource-hub' ), false ); ?>
				<?php rp_opportunities_text_field( 'consultancy_fee', __( 'Proposed consultancy fee amount', 'rp-resource-hub' ), true ); ?>
				<?php rp_opportunities_file_field( 'cv_resume', __( 'CV/resume', 'rp-resource-hub' ), true ); ?>
				<?php rp_opportunities_file_field( 'letter_of_intent', __( 'Letter of intent', 'rp-resource-hub' ), true ); ?>
				<?php rp_opportunities_file_field( 'consultancy_quotation', __( 'Proposed consultancy quotation', 'rp-resource-hub' ), true ); ?>
				<?php if ( '1' === get_post_meta( $post_id, '_rp_opportunity_require_portfolio', true ) ) : ?>
					<?php rp_opportunities_file_field( 'portfolio', __( 'Portfolio or proof of work', 'rp-resource-hub' ), true ); ?>
				<?php endif; ?>
			<?php else : ?>
				<?php rp_opportunities_text_field( 'full_name', __( 'Full name (First Name, Middle Name, Last Name)', 'rp-resource-hub' ), true ); ?>
				<?php rp_opportunities_text_field( 'phone', __( 'Contact number (mobile)', 'rp-resource-hub' ), true ); ?>
				<?php rp_opportunities_text_field( 'alternative_contact', __( 'Alternative contact details', 'rp-resource-hub' ), false ); ?>
				<?php rp_opportunities_email_field( 'email', __( 'Email address', 'rp-resource-hub' ), true ); ?>
				<?php rp_opportunities_textarea_field( 'present_address', __( 'Complete present address', 'rp-resource-hub' ), true ); ?>
				<?php rp_opportunities_date_field( 'date_of_birth', __( 'Date of birth', 'rp-resource-hub' ), true ); ?>
				<?php rp_opportunities_select_field( 'education', __( 'Highest educational attainment', 'rp-resource-hub' ), array( 'post_grad' => "Post Graduate Diploma/Master's Degree", 'bachelors' => "Bachelor's Degree", 'vocational' => 'Vocational Diploma', 'college_undergrad' => 'College Undergraduate', 'high_school' => 'High School Graduate', 'other' => 'Other' ), true ); ?>
				<?php rp_opportunities_text_field( 'university', __( 'University/school', 'rp-resource-hub' ), true ); ?>
				<?php rp_opportunities_text_field( 'course', __( 'Course/program', 'rp-resource-hub' ), true ); ?>
				<?php rp_opportunities_text_field( 'position_applied_for', __( 'Position applied for', 'rp-resource-hub' ), false, get_the_title( $post_id ) ); ?>
				<?php rp_opportunities_select_field( 'application_source', __( 'Source of application', 'rp-resource-hub' ), array( 'facebook' => 'Facebook', 'referral' => 'Referral', 'job_portal' => 'Job Portal', 'linkedin' => 'LinkedIn', 'word_of_mouth' => 'Word of Mouth', 'other' => 'Other' ), true ); ?>
				<?php rp_opportunities_text_field( 'referrer_name', __( 'If referred, name of the person who referred you', 'rp-resource-hub' ), false ); ?>
				<?php rp_opportunities_yes_no_field( 'emergency_work', __( 'Are you willing to work in emergency situations when required?', 'rp-resource-hub' ), true ); ?>
				<?php rp_opportunities_yes_no_field( 'travel_outside_location', __( 'Are you willing to travel or work outside of your current location?', 'rp-resource-hub' ), true ); ?>
				<?php rp_opportunities_yes_no_field( 'overtime_weekends', __( 'Are you open to working beyond working hours, including weekends and holidays at certain periods?', 'rp-resource-hub' ), true ); ?>
				<?php rp_opportunities_select_field( 'availability', __( 'How soon will you be available for employment?', 'rp-resource-hub' ), array( 'asap' => 'ASAP', '15_days' => '15 days notice', '30_days' => '30 days notice', 'more_than_30' => 'More than 30 days notice', 'other' => 'Other' ), true ); ?>
				<?php rp_opportunities_textarea_field( 'health_conditions', __( 'Do you have health conditions or medical procedures that may affect your ability to carry out work duties? If yes, specify details.', 'rp-resource-hub' ), true ); ?>
				<?php rp_opportunities_text_field( 'last_salary', __( 'What was your last drawn salary?', 'rp-resource-hub' ), true ); ?>
				<?php rp_opportunities_text_field( 'expected_salary', __( 'What is your expected salary?', 'rp-resource-hub' ), true ); ?>
				<?php rp_opportunities_textarea_field( 'skillsets', __( 'What skillsets do you have that are relevant to the role?', 'rp-resource-hub' ), true ); ?>
				<?php rp_opportunities_textarea_field( 'why_accord', __( 'Why do you want to work for ACCORD?', 'rp-resource-hub' ), true ); ?>
				<?php rp_opportunities_file_field( 'resume', __( 'Detailed and updated resume', 'rp-resource-hub' ), true ); ?>
				<?php rp_opportunities_file_field( 'cover_letter', __( 'Cover letter', 'rp-resource-hub' ), true ); ?>
			<?php endif; ?>
			<label class="rp-checkbox-line"><input type="checkbox" name="consent" value="1" required> <?php esc_html_e( 'I certify that the information I provided is true and I agree that ACCORD may process this application for recruitment purposes.', 'rp-resource-hub' ); ?></label>
			<button class="rp-button" type="submit"><?php esc_html_e( 'Submit Application', 'rp-resource-hub' ); ?></button>
		</form>
	</section>
	<?php
}

function rp_opportunities_render_bid_form( $post_id ) {
	rp_opportunities_notice_html();
	?>
	<section class="rp-opportunity-form-section">
		<h2><?php esc_html_e( 'Submit Bid', 'rp-resource-hub' ); ?></h2>
		<form class="rp-upload-form rp-opportunity-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data">
			<input type="hidden" name="action" value="rp_bid_submission_submit">
			<input type="hidden" name="opportunity_id" value="<?php echo esc_attr( $post_id ); ?>">
			<?php wp_nonce_field( 'rp_bid_submission_submit_' . $post_id, 'rp_bid_submission_nonce' ); ?>
			<?php rp_opportunities_text_field( 'company_name', __( 'Company or supplier name', 'rp-resource-hub' ), true ); ?>
			<?php rp_opportunities_text_field( 'contact_person', __( 'Contact person', 'rp-resource-hub' ), true ); ?>
			<?php rp_opportunities_email_field( 'email', __( 'Email address', 'rp-resource-hub' ), true ); ?>
			<?php rp_opportunities_text_field( 'phone', __( 'Phone number', 'rp-resource-hub' ), true ); ?>
			<?php rp_opportunities_textarea_field( 'message', __( 'Message or remarks', 'rp-resource-hub' ), false ); ?>
			<?php rp_opportunities_file_field( 'quotation', __( 'Quotation or proposal', 'rp-resource-hub' ), true, 'bid' ); ?>
			<?php rp_opportunities_file_field( 'business_permit', __( "Copy of valid business or mayor's permit", 'rp-resource-hub' ), true, 'bid' ); ?>
			<?php rp_opportunities_file_field( 'registration_certificate', __( 'Certificate of registration, if applicable', 'rp-resource-hub' ), false, 'bid' ); ?>
			<?php rp_opportunities_file_field( 'bir_2303', __( 'BIR 2303', 'rp-resource-hub' ), true, 'bid' ); ?>
			<?php rp_opportunities_file_field( 'receipt_sample', __( 'Sample of official receipt/sales invoice', 'rp-resource-hub' ), true, 'bid' ); ?>
			<label class="rp-checkbox-line"><input type="checkbox" name="consent" value="1" required> <?php esc_html_e( 'I certify that the information and documents submitted are true and I agree that ACCORD may process this submission for procurement purposes.', 'rp-resource-hub' ); ?></label>
			<button class="rp-button" type="submit"><?php esc_html_e( 'Submit Bid', 'rp-resource-hub' ); ?></button>
		</form>
	</section>
	<?php
}

function rp_opportunities_text_field( $name, $label, $required = false, $value = '' ) {
	echo '<div class="rp-field"><label for="' . esc_attr( $name ) . '">' . esc_html( $label ) . ( $required ? ' <span aria-hidden="true">*</span>' : '' ) . '</label><input type="text" id="' . esc_attr( $name ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '"' . ( $required ? ' required' : '' ) . '></div>';
}

function rp_opportunities_email_field( $name, $label, $required = false, $value = '' ) {
	echo '<div class="rp-field"><label for="' . esc_attr( $name ) . '">' . esc_html( $label ) . ( $required ? ' <span aria-hidden="true">*</span>' : '' ) . '</label><input type="email" id="' . esc_attr( $name ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '"' . ( $required ? ' required' : '' ) . '></div>';
}

function rp_opportunities_date_field( $name, $label, $required = false, $value = '' ) {
	echo '<div class="rp-field"><label for="' . esc_attr( $name ) . '">' . esc_html( $label ) . ( $required ? ' <span aria-hidden="true">*</span>' : '' ) . '</label><input type="date" id="' . esc_attr( $name ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '"' . ( $required ? ' required' : '' ) . '></div>';
}

function rp_opportunities_textarea_field( $name, $label, $required = false, $value = '' ) {
	echo '<div class="rp-field"><label for="' . esc_attr( $name ) . '">' . esc_html( $label ) . ( $required ? ' <span aria-hidden="true">*</span>' : '' ) . '</label><textarea id="' . esc_attr( $name ) . '" name="' . esc_attr( $name ) . '" rows="4"' . ( $required ? ' required' : '' ) . '>' . esc_textarea( $value ) . '</textarea></div>';
}

function rp_opportunities_select_field( $name, $label, $options, $required = false, $selected = '', $class = '' ) {
	echo '<div class="rp-field"><label for="' . esc_attr( $name ) . '">' . esc_html( $label ) . ( $required ? ' <span aria-hidden="true">*</span>' : '' ) . '</label><select id="' . esc_attr( $name ) . '" name="' . esc_attr( $name ) . '"' . ( $class ? ' class="' . esc_attr( $class ) . '"' : '' ) . ( $required ? ' required' : '' ) . '><option value="">' . esc_html__( 'Select one', 'rp-resource-hub' ) . '</option>';
	foreach ( $options as $value => $label_text ) {
		echo '<option value="' . esc_attr( $value ) . '"' . selected( $selected, $value, false ) . '>' . esc_html( $label_text ) . '</option>';
	}
	echo '</select></div>';
}

function rp_opportunities_yes_no_field( $name, $label, $required = false ) {
	echo '<fieldset class="rp-field"><legend>' . esc_html( $label ) . ( $required ? ' *' : '' ) . '</legend><label><input type="radio" name="' . esc_attr( $name ) . '" value="yes"' . ( $required ? ' required' : '' ) . '> ' . esc_html__( 'Yes', 'rp-resource-hub' ) . '</label><label><input type="radio" name="' . esc_attr( $name ) . '" value="no"> ' . esc_html__( 'No', 'rp-resource-hub' ) . '</label></fieldset>';
}

function rp_opportunities_file_field( $name, $label, $required = false, $context = 'job' ) {
	$accept = 'bid' === $context ? '.pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.zip' : '.pdf,.doc,.docx,.jpg,.jpeg,.png';
	echo '<div class="rp-field"><label for="' . esc_attr( $name ) . '">' . esc_html( $label ) . ( $required ? ' <span aria-hidden="true">*</span>' : '' ) . '</label><input type="file" id="' . esc_attr( $name ) . '" name="' . esc_attr( $name ) . '" accept="' . esc_attr( $accept ) . '"' . ( $required ? ' required' : '' ) . '><p class="rp-field-help">' . esc_html( 'bid' === $context ? __( 'Maximum file size: 25 MB.', 'rp-resource-hub' ) : __( 'Maximum file size: 10 MB.', 'rp-resource-hub' ) ) . '</p></div>';
}

function rp_opportunities_sanitize_text_post( $key ) {
	return isset( $_POST[ $key ] ) ? sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) : '';
}

function rp_opportunities_sanitize_textarea_post( $key ) {
	return isset( $_POST[ $key ] ) ? sanitize_textarea_field( wp_unslash( $_POST[ $key ] ) ) : '';
}

function rp_opportunities_handle_job_submit() {
	$opportunity_id = isset( $_POST['opportunity_id'] ) ? absint( $_POST['opportunity_id'] ) : 0;
	$redirect       = $opportunity_id ? get_permalink( $opportunity_id ) : home_url( '/opportunities/' );

	if ( ! $opportunity_id || ! wp_verify_nonce( isset( $_POST['rp_job_application_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['rp_job_application_nonce'] ) ) : '', 'rp_job_application_submit_' . $opportunity_id ) || 'job' !== rp_opportunities_get_type( $opportunity_id ) || ! rp_opportunities_is_open( $opportunity_id ) ) {
		wp_safe_redirect( add_query_arg( 'rp_opp_notice', 'error', $redirect ) );
		exit;
	}

	$hiring_type = rp_opportunities_get_hiring_type( $opportunity_id );
	$email       = sanitize_email( rp_opportunities_sanitize_text_post( 'email' ) );
	$full_name   = rp_opportunities_sanitize_text_post( 'full_name' );
	$phone       = rp_opportunities_sanitize_text_post( 'phone' );

	if ( ! $full_name || ! is_email( $email ) || empty( $_POST['consent'] ) ) {
		wp_safe_redirect( add_query_arg( 'rp_opp_notice', 'error', $redirect ) );
		exit;
	}

	$required_files = 'consultant' === $hiring_type ? array( 'cv_resume', 'letter_of_intent', 'consultancy_quotation' ) : array( 'resume', 'cover_letter' );
	if ( 'consultant' === $hiring_type && '1' === get_post_meta( $opportunity_id, '_rp_opportunity_require_portfolio', true ) ) {
		$required_files[] = 'portfolio';
	}
	if ( ! rp_opportunities_required_files_present( $required_files ) ) {
		wp_safe_redirect( add_query_arg( 'rp_opp_notice', 'error', $redirect ) );
		exit;
	}

	$fields = array();
	foreach ( array( 'alternative_contact', 'present_address', 'date_of_birth', 'education', 'university', 'course', 'position_applied_for', 'application_source', 'referrer_name', 'emergency_work', 'travel_outside_location', 'overtime_weekends', 'availability', 'health_conditions', 'last_salary', 'expected_salary', 'skillsets', 'why_accord', 'cover_message', 'consultancy_fee' ) as $key ) {
		$fields[ $key ] = rp_opportunities_sanitize_textarea_post( $key );
	}

	global $wpdb;
	$table = $wpdb->prefix . 'rp_job_applications';
	$now   = current_time( 'mysql' );
	$wpdb->insert(
		$table,
		array(
			'opportunity_id' => $opportunity_id,
			'submitted_at'   => $now,
			'updated_at'     => $now,
			'status'         => 'received',
			'hiring_type'    => $hiring_type,
			'full_name'      => $full_name,
			'email'          => $email,
			'phone'          => $phone,
			'fields'         => wp_json_encode( $fields ),
			'consent'        => 1,
			'ip_address'     => function_exists( 'rp_resource_hub_get_ip' ) ? rp_resource_hub_get_ip() : '',
			'user_agent'     => isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : '',
		),
		array( '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%s', '%s' )
	);

	$submission_id = absint( $wpdb->insert_id );
	if ( ! $submission_id ) {
		wp_safe_redirect( add_query_arg( 'rp_opp_notice', 'error', $redirect ) );
		exit;
	}

	$file_fields = 'consultant' === $hiring_type ? array( 'cv_resume', 'letter_of_intent', 'consultancy_quotation', 'portfolio' ) : array( 'resume', 'cover_letter' );
	$attachment_ids = rp_opportunities_upload_submission_files( $file_fields, 'job', $submission_id, RP_JOB_MAX_ATTACHMENT_BYTES );
	$wpdb->update( $table, array( 'attachment_ids' => wp_json_encode( $attachment_ids ) ), array( 'id' => $submission_id ), array( '%s' ), array( '%d' ) );

	rp_opportunities_add_note( 'job', $submission_id, 0, 'system', '', 'received', '', __( 'Application submitted through the public Opportunities form.', 'rp-resource-hub' ) );
	rp_opportunities_notify_submission_received( 'job', $submission_id );
	wp_safe_redirect( add_query_arg( 'rp_opp_notice', 'submitted', $redirect ) );
	exit;
}
add_action( 'admin_post_rp_job_application_submit', 'rp_opportunities_handle_job_submit' );
add_action( 'admin_post_nopriv_rp_job_application_submit', 'rp_opportunities_handle_job_submit' );

function rp_opportunities_handle_bid_submit() {
	$opportunity_id = isset( $_POST['opportunity_id'] ) ? absint( $_POST['opportunity_id'] ) : 0;
	$redirect       = $opportunity_id ? get_permalink( $opportunity_id ) : home_url( '/opportunities/' );

	if ( ! $opportunity_id || ! wp_verify_nonce( isset( $_POST['rp_bid_submission_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['rp_bid_submission_nonce'] ) ) : '', 'rp_bid_submission_submit_' . $opportunity_id ) || 'itb' !== rp_opportunities_get_type( $opportunity_id ) || ! rp_opportunities_is_open( $opportunity_id ) ) {
		wp_safe_redirect( add_query_arg( 'rp_opp_notice', 'error', $redirect ) );
		exit;
	}

	$email = sanitize_email( rp_opportunities_sanitize_text_post( 'email' ) );
	if ( ! is_email( $email ) || empty( $_POST['consent'] ) ) {
		wp_safe_redirect( add_query_arg( 'rp_opp_notice', 'error', $redirect ) );
		exit;
	}

	if ( ! rp_opportunities_required_files_present( array( 'quotation', 'business_permit', 'bir_2303', 'receipt_sample' ) ) ) {
		wp_safe_redirect( add_query_arg( 'rp_opp_notice', 'error', $redirect ) );
		exit;
	}

	global $wpdb;
	$table = $wpdb->prefix . 'rp_bid_submissions';
	$now   = current_time( 'mysql' );
	$wpdb->insert(
		$table,
		array(
			'opportunity_id' => $opportunity_id,
			'submitted_at'   => $now,
			'updated_at'     => $now,
			'status'         => 'received',
			'company_name'   => rp_opportunities_sanitize_text_post( 'company_name' ),
			'contact_person' => rp_opportunities_sanitize_text_post( 'contact_person' ),
			'email'          => $email,
			'phone'          => rp_opportunities_sanitize_text_post( 'phone' ),
			'message'        => rp_opportunities_sanitize_textarea_post( 'message' ),
			'fields'         => wp_json_encode( array() ),
			'consent'        => 1,
			'ip_address'     => function_exists( 'rp_resource_hub_get_ip' ) ? rp_resource_hub_get_ip() : '',
			'user_agent'     => isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : '',
		),
		array( '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%s', '%s' )
	);

	$submission_id = absint( $wpdb->insert_id );
	if ( ! $submission_id ) {
		wp_safe_redirect( add_query_arg( 'rp_opp_notice', 'error', $redirect ) );
		exit;
	}

	$attachment_ids = rp_opportunities_upload_submission_files( array( 'quotation', 'business_permit', 'registration_certificate', 'bir_2303', 'receipt_sample' ), 'bid', $submission_id, RP_BID_MAX_ATTACHMENT_BYTES );
	$wpdb->update( $table, array( 'attachment_ids' => wp_json_encode( $attachment_ids ) ), array( 'id' => $submission_id ), array( '%s' ), array( '%d' ) );

	rp_opportunities_add_note( 'bid', $submission_id, 0, 'system', '', 'received', '', __( 'Bid submitted through the public Opportunities form.', 'rp-resource-hub' ) );
	rp_opportunities_notify_submission_received( 'bid', $submission_id );
	wp_safe_redirect( add_query_arg( 'rp_opp_notice', 'submitted', $redirect ) );
	exit;
}
add_action( 'admin_post_rp_bid_submission_submit', 'rp_opportunities_handle_bid_submit' );
add_action( 'admin_post_nopriv_rp_bid_submission_submit', 'rp_opportunities_handle_bid_submit' );

function rp_opportunities_required_files_present( $fields ) {
	foreach ( $fields as $field ) {
		if ( empty( $_FILES[ $field ]['name'] ) || ! isset( $_FILES[ $field ]['error'] ) || UPLOAD_ERR_OK !== (int) $_FILES[ $field ]['error'] ) {
			return false;
		}
	}
	return true;
}

function rp_opportunities_upload_dir( $uploads ) {
	$subdir = '/rp-secure/opportunities';
	$uploads['path']   = $uploads['basedir'] . $subdir;
	$uploads['url']    = $uploads['baseurl'] . $subdir;
	$uploads['subdir'] = $subdir;
	return $uploads;
}

function rp_opportunities_protect_upload_dir() {
	$uploads = wp_upload_dir();
	$dir     = trailingslashit( $uploads['basedir'] ) . 'rp-secure/opportunities';
	if ( ! wp_mkdir_p( $dir ) ) {
		return;
	}
	if ( ! file_exists( $dir . '/index.html' ) ) {
		file_put_contents( $dir . '/index.html', '' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents
	}
	if ( ! file_exists( $dir . '/.htaccess' ) ) {
		file_put_contents( $dir . '/.htaccess', "Options -Indexes\n<Files *>\nRequire all denied\n</Files>\n" ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents
	}
}

function rp_opportunities_upload_submission_files( $fields, $submission_type, $submission_id, $max_size ) {
	require_once ABSPATH . 'wp-admin/includes/file.php';
	rp_opportunities_protect_upload_dir();

	$attachment_ids = array();
	foreach ( $fields as $field ) {
		if ( empty( $_FILES[ $field ]['name'] ) || ! empty( $_FILES[ $field ]['error'] ) ) {
			continue;
		}
		if ( ! empty( $_FILES[ $field ]['size'] ) && $_FILES[ $field ]['size'] > $max_size ) {
			rp_opportunities_add_note( $submission_type, $submission_id, 0, 'system', '', '', '', sprintf( 'Skipped oversized file for field %s.', $field ) );
			continue;
		}
		add_filter( 'upload_dir', 'rp_opportunities_upload_dir' );
		$upload = wp_handle_upload( $_FILES[ $field ], array( 'test_form' => false ) );
		remove_filter( 'upload_dir', 'rp_opportunities_upload_dir' );
		if ( empty( $upload['file'] ) || ! empty( $upload['error'] ) ) {
			rp_opportunities_add_note( $submission_type, $submission_id, 0, 'system', '', '', '', sprintf( 'Attachment upload failed for field %1$s: %2$s', $field, isset( $upload['error'] ) ? $upload['error'] : 'unknown error' ) );
			continue;
		}
		$filetype = wp_check_filetype( basename( $upload['file'] ), null );
		$attachment_id = wp_insert_attachment(
			array(
				'post_mime_type' => isset( $filetype['type'] ) ? $filetype['type'] : 'application/octet-stream',
				'post_title'     => sanitize_file_name( pathinfo( $upload['file'], PATHINFO_FILENAME ) ),
				'post_content'   => '',
				'post_status'    => 'private',
			),
			$upload['file']
		);
		if ( is_wp_error( $attachment_id ) || ! $attachment_id ) {
			rp_opportunities_add_note( $submission_type, $submission_id, 0, 'system', '', '', '', sprintf( 'Attachment record failed for field %s.', $field ) );
			continue;
		}
		update_post_meta( $attachment_id, '_rp_opportunity_submission_type', $submission_type );
		update_post_meta( $attachment_id, '_rp_opportunity_submission_id', $submission_id );
		update_post_meta( $attachment_id, '_rp_opportunity_upload_field', $field );
		$attachment_ids[ $field ] = absint( $attachment_id );
	}
	return $attachment_ids;
}

function rp_opportunities_get_submission( $type, $submission_id ) {
	global $wpdb;
	$table = 'job' === $type ? $wpdb->prefix . 'rp_job_applications' : $wpdb->prefix . 'rp_bid_submissions';
	return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table WHERE id = %d", $submission_id ) );
}

function rp_opportunities_add_note( $type, $submission_id, $user_id, $note_type, $old_status, $new_status, $email_type, $note ) {
	global $wpdb;
	$wpdb->insert(
		$wpdb->prefix . 'rp_opportunity_submission_notes',
		array(
			'submission_type' => $type,
			'submission_id'   => absint( $submission_id ),
			'user_id'         => $user_id ? absint( $user_id ) : null,
			'note_type'       => sanitize_key( $note_type ),
			'old_status'      => sanitize_key( $old_status ),
			'new_status'      => sanitize_key( $new_status ),
			'email_type'      => sanitize_key( $email_type ),
			'note'            => wp_kses_post( $note ),
			'created_at'      => current_time( 'mysql' ),
		),
		array( '%s', '%d', '%d', '%s', '%s', '%s', '%s', '%s', '%s' )
	);
}

function rp_opportunities_system_note( $department_email ) {
	return sprintf(
		/* translators: %s: department email */
		__( "This is a system-generated email from the ACCORD Resilient Philippines website. Please do not reply directly to this message. For questions or follow-up, contact %s.", 'rp-resource-hub' ),
		$department_email
	);
}

function rp_opportunities_send_mail( $recipient, $subject, $message, $cc = array(), $reply_to = '' ) {
	if ( function_exists( 'rp_tinig_graph_mail_is_configured' ) && rp_tinig_graph_mail_is_configured() && function_exists( 'rp_tinig_graph_send_mail' ) ) {
		$result = rp_tinig_graph_send_mail( $subject, $message, $recipient, $reply_to, $cc );
		if ( ! is_wp_error( $result ) ) {
			return true;
		}
	}

	$headers = array();
	foreach ( array_filter( array_map( 'sanitize_email', (array) $cc ), 'is_email' ) as $email ) {
		$headers[] = 'Cc: ' . $email;
	}
	if ( $reply_to && is_email( $reply_to ) ) {
		$headers[] = 'Reply-To: ' . $reply_to;
	}
	return wp_mail( $recipient, $subject, $message, $headers );
}

function rp_opportunities_notify_submission_received( $type, $submission_id ) {
	$submission = rp_opportunities_get_submission( $type, $submission_id );
	if ( ! $submission ) {
		return;
	}
	$post_title = get_the_title( $submission->opportunity_id );
	if ( 'job' === $type ) {
		$dept_email = RP_JOB_NOTIFICATION_EMAIL;
		$name       = $submission->full_name;
		$subject    = sprintf( 'Application received: %s', $post_title );
		$app_msg    = sprintf( "Dear %s,\n\nThank you for submitting your application for %s. ACCORD has received your application and HR will review it.\n\n%s", $name, $post_title, rp_opportunities_system_note( $dept_email ) );
		$dept_msg   = sprintf( "A new job application has been submitted.\n\nPosition: %s\nApplicant: %s\nEmail: %s\nDashboard: %s", $post_title, $name, $submission->email, add_query_arg( 'opportunity_id', $submission->opportunity_id, home_url( '/job-applications-dashboard/' ) ) );
	} else {
		$dept_email = RP_BID_NOTIFICATION_EMAIL;
		$name       = $submission->company_name;
		$subject    = sprintf( 'Bid submission received: %s', $post_title );
		$app_msg    = sprintf( "Dear %s,\n\nThank you for submitting your bid for %s. ACCORD has received your submission and Procurement will review it.\n\n%s", $name, $post_title, rp_opportunities_system_note( $dept_email ) );
		$dept_msg   = sprintf( "A new ITB submission has been submitted.\n\nITB: %s\nSupplier: %s\nEmail: %s\nDashboard: %s", $post_title, $name, $submission->email, add_query_arg( 'opportunity_id', $submission->opportunity_id, home_url( '/bid-submissions-dashboard/' ) ) );
	}
	rp_opportunities_send_mail( $submission->email, $subject, $app_msg, array( $dept_email ), $dept_email );
	rp_opportunities_send_mail( $dept_email, 'New ' . ( 'job' === $type ? 'job application' : 'bid submission' ) . ': ' . $post_title, $dept_msg, array(), $submission->email );
}

function rp_opportunities_job_dashboard_shortcode() {
	if ( ! rp_opportunities_user_can_manage_jobs() ) {
		return '<div class="rp-empty-state"><p>' . esc_html__( 'You must be authorized by HR to view job applications.', 'rp-resource-hub' ) . '</p><p><a class="rp-button" href="' . esc_url( wp_login_url( home_url( '/job-applications-dashboard/' ) ) ) . '">' . esc_html__( 'Log in', 'rp-resource-hub' ) . '</a></p></div>';
	}
	$edit_opportunity_id = isset( $_GET['edit_opportunity_id'] ) ? absint( $_GET['edit_opportunity_id'] ) : 0;
	if ( $edit_opportunity_id ) {
		return rp_opportunities_render_edit_posting( 'job', $edit_opportunity_id );
	}
	$opportunity_id = isset( $_GET['opportunity_id'] ) ? absint( $_GET['opportunity_id'] ) : 0;
	return $opportunity_id ? rp_opportunities_render_submission_layer( 'job', $opportunity_id ) : rp_opportunities_render_opening_layer( 'job' );
}
add_shortcode( 'rp_job_applications_dashboard', 'rp_opportunities_job_dashboard_shortcode' );

function rp_opportunities_bid_dashboard_shortcode() {
	if ( ! rp_opportunities_user_can_manage_bids() ) {
		return '<div class="rp-empty-state"><p>' . esc_html__( 'You must be authorized by Procurement to view bid submissions.', 'rp-resource-hub' ) . '</p><p><a class="rp-button" href="' . esc_url( wp_login_url( home_url( '/bid-submissions-dashboard/' ) ) ) . '">' . esc_html__( 'Log in', 'rp-resource-hub' ) . '</a></p></div>';
	}
	$edit_opportunity_id = isset( $_GET['edit_opportunity_id'] ) ? absint( $_GET['edit_opportunity_id'] ) : 0;
	if ( $edit_opportunity_id ) {
		return rp_opportunities_render_edit_posting( 'bid', $edit_opportunity_id );
	}
	$opportunity_id = isset( $_GET['opportunity_id'] ) ? absint( $_GET['opportunity_id'] ) : 0;
	return $opportunity_id ? rp_opportunities_render_submission_layer( 'bid', $opportunity_id ) : rp_opportunities_render_opening_layer( 'bid' );
}
add_shortcode( 'rp_bid_submissions_dashboard', 'rp_opportunities_bid_dashboard_shortcode' );

function rp_opportunities_render_opening_layer( $type ) {
	global $wpdb;
	$post_type = 'job' === $type ? 'job' : 'itb';
	$query = new WP_Query(
		array(
			'post_type'      => 'rp_opportunity',
			'post_status'    => array( 'publish', 'draft', 'pending' ),
			'posts_per_page' => -1,
			'meta_key'       => '_rp_opportunity_type',
			'meta_value'     => $post_type,
			'orderby'        => 'date',
			'order'          => 'DESC',
		)
	);
	$table = 'job' === $type ? $wpdb->prefix . 'rp_job_applications' : $wpdb->prefix . 'rp_bid_submissions';
	$dashboard_url = 'job' === $type ? home_url( '/job-applications-dashboard/' ) : home_url( '/bid-submissions-dashboard/' );
	$edit_label = 'job' === $type ? __( 'Edit Job Posting', 'rp-resource-hub' ) : __( 'Edit ITB Posting', 'rp-resource-hub' );
	ob_start();
	?>
	<div class="rp-opportunity-dashboard">
		<div class="rp-dashboard-header">
			<h2 class="rp-dashboard-title"><?php echo esc_html( 'job' === $type ? __( 'Job Applications Dashboard', 'rp-resource-hub' ) : __( 'Bid Submissions Dashboard', 'rp-resource-hub' ) ); ?></h2>
			<p class="rp-dashboard-subtitle"><?php echo esc_html( 'job' === $type ? __( 'Select a job opening to view related applications.', 'rp-resource-hub' ) : __( 'Select an ITB to view related submissions.', 'rp-resource-hub' ) ); ?></p>
		</div>
		<div class="rp-table-responsive">
			<table class="rp-moderation-table rp-opportunity-dashboard-table">
				<thead><tr><th><?php esc_html_e( 'Posting', 'rp-resource-hub' ); ?></th><th><?php esc_html_e( 'Deadline', 'rp-resource-hub' ); ?></th><th><?php esc_html_e( 'Status', 'rp-resource-hub' ); ?></th><th><?php esc_html_e( 'Total', 'rp-resource-hub' ); ?></th><th><?php esc_html_e( 'Under Review', 'rp-resource-hub' ); ?></th><th><?php esc_html_e( 'Successful', 'rp-resource-hub' ); ?></th><th><?php esc_html_e( 'Unsuccessful', 'rp-resource-hub' ); ?></th><th><?php esc_html_e( 'Last Submission', 'rp-resource-hub' ); ?></th><th><?php esc_html_e( 'Action', 'rp-resource-hub' ); ?></th></tr></thead>
				<tbody>
					<?php while ( $query->have_posts() ) : $query->the_post(); $post_id = get_the_ID(); $counts = rp_opportunities_submission_counts( $table, $post_id ); ?>
						<tr>
							<td><strong><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></strong><?php if ( 'bid' === $type && get_post_meta( $post_id, '_rp_opportunity_reference_number', true ) ) : ?><br><span><?php echo esc_html( get_post_meta( $post_id, '_rp_opportunity_reference_number', true ) ); ?></span><?php endif; ?></td>
							<td><?php echo esc_html( rp_opportunities_format_datetime( get_post_meta( $post_id, '_rp_opportunity_deadline', true ) ) ); ?></td>
							<td><span class="rp-status-badge <?php echo rp_opportunities_is_open( $post_id ) ? 'rp-opportunity-open' : 'rp-opportunity-closed'; ?>"><?php echo esc_html( rp_opportunities_is_open( $post_id ) ? __( 'Open', 'rp-resource-hub' ) : __( 'Closed', 'rp-resource-hub' ) ); ?></span></td>
							<td><?php echo esc_html( $counts['total'] ); ?></td>
							<td><?php echo esc_html( $counts['under_review'] ); ?></td>
							<td><?php echo esc_html( $counts['successful'] ); ?></td>
							<td><?php echo esc_html( $counts['unsuccessful'] ); ?></td>
							<td><?php echo esc_html( $counts['last_submission'] ); ?></td>
							<td><div class="rp-dashboard-actions"><a class="rp-button" href="<?php echo esc_url( add_query_arg( 'opportunity_id', $post_id, $dashboard_url ) ); ?>"><?php echo esc_html( 'job' === $type ? __( 'View Applications', 'rp-resource-hub' ) : __( 'View Submissions', 'rp-resource-hub' ) ); ?></a><a class="rp-button rp-button-secondary" href="<?php echo esc_url( add_query_arg( 'edit_opportunity_id', $post_id, $dashboard_url ) ); ?>"><?php echo esc_html( $edit_label ); ?></a></div></td>
						</tr>
					<?php endwhile; wp_reset_postdata(); ?>
				</tbody>
			</table>
		</div>
	</div>
	<?php
	return ob_get_clean();
}

function rp_opportunities_submission_counts( $table, $opportunity_id ) {
	global $wpdb;
	$rows = $wpdb->get_results( $wpdb->prepare( "SELECT status, COUNT(*) AS total FROM $table WHERE opportunity_id = %d GROUP BY status", $opportunity_id ) );
	$data = array( 'total' => 0, 'under_review' => 0, 'successful' => 0, 'unsuccessful' => 0, 'last_submission' => '' );
	foreach ( $rows as $row ) {
		$data['total'] += absint( $row->total );
		if ( isset( $data[ $row->status ] ) ) {
			$data[ $row->status ] = absint( $row->total );
		}
	}
	$last = $wpdb->get_var( $wpdb->prepare( "SELECT submitted_at FROM $table WHERE opportunity_id = %d ORDER BY submitted_at DESC LIMIT 1", $opportunity_id ) );
	$data['last_submission'] = $last ? rp_opportunities_format_datetime( $last ) : '-';
	return $data;
}

function rp_opportunities_render_edit_posting( $type, $post_id ) {
	$post = get_post( $post_id );
	if ( ! $post || 'rp_opportunity' !== $post->post_type || ( 'job' === $type && 'job' !== rp_opportunities_get_type( $post_id ) ) || ( 'bid' === $type && 'itb' !== rp_opportunities_get_type( $post_id ) ) ) {
		return '<div class="rp-empty-state"><p>' . esc_html__( 'Selected posting could not be found.', 'rp-resource-hub' ) . '</p></div>';
	}

	$dashboard_url = 'job' === $type ? home_url( '/job-applications-dashboard/' ) : home_url( '/bid-submissions-dashboard/' );
	$deadline = get_post_meta( $post_id, '_rp_opportunity_deadline', true );
	$deadline_date = $deadline ? gmdate( 'Y-m-d', strtotime( $deadline ) ) : '';
	$hiring_type = rp_opportunities_get_hiring_type( $post_id );
	$manual_status = get_post_meta( $post_id, '_rp_opportunity_manual_status', true );
	ob_start();
	?>
	<div class="rp-submit-opportunity-shell">
		<p><a href="<?php echo esc_url( $dashboard_url ); ?>">&larr; <?php esc_html_e( 'Back to postings', 'rp-resource-hub' ); ?></a></p>
		<div class="rp-dashboard-header">
			<h2 class="rp-dashboard-title"><?php echo esc_html( 'job' === $type ? __( 'Edit Job Posting', 'rp-resource-hub' ) : __( 'Edit ITB Posting', 'rp-resource-hub' ) ); ?></h2>
			<p class="rp-dashboard-subtitle"><?php esc_html_e( 'Update posting details and control whether the posting is open or closed.', 'rp-resource-hub' ); ?></p>
		</div>
		<?php rp_opportunities_submit_notice_html(); ?>
		<form class="rp-upload-form rp-opportunity-form rp-submit-opportunity-form" method="post" action="<?php echo esc_url( $dashboard_url ); ?>" enctype="multipart/form-data">
			<input type="hidden" name="rp_update_opportunity_action" value="1">
			<input type="hidden" name="opportunity_id" value="<?php echo esc_attr( $post_id ); ?>">
			<input type="hidden" name="opportunity_type" value="<?php echo esc_attr( 'job' === $type ? 'job' : 'itb' ); ?>">
			<?php wp_nonce_field( 'rp_update_opportunity_' . $post_id, 'rp_update_opportunity_nonce' ); ?>
			<?php rp_opportunities_text_field( 'opportunity_title', __( 'Posting title', 'rp-resource-hub' ), true, get_the_title( $post_id ) ); ?>
			<div class="rp-field">
				<label for="manual_status"><?php esc_html_e( 'Posting status', 'rp-resource-hub' ); ?></label>
				<select id="manual_status" name="manual_status">
					<option value="" <?php selected( $manual_status, '' ); ?>><?php esc_html_e( 'Automatic by deadline', 'rp-resource-hub' ); ?></option>
					<option value="open" <?php selected( $manual_status, 'open' ); ?>><?php esc_html_e( 'Force Open', 'rp-resource-hub' ); ?></option>
					<option value="closed" <?php selected( $manual_status, 'closed' ); ?>><?php esc_html_e( 'Force Closed', 'rp-resource-hub' ); ?></option>
				</select>
			</div>
			<?php if ( 'job' === $type ) : ?>
				<?php rp_opportunities_select_field( 'hiring_type', __( 'Hiring type', 'rp-resource-hub' ), rp_opportunities_hiring_type_options(), false, $hiring_type, 'rp-opportunity-hiring-type' ); ?>
			<?php endif; ?>
			<?php rp_opportunities_date_field( 'deadline', __( 'Deadline', 'rp-resource-hub' ), true, $deadline_date ); ?>
			<?php if ( 'job' === $type ) : ?>
				<?php rp_opportunities_text_field( 'location', __( 'Location / duty station', 'rp-resource-hub' ), false, get_post_meta( $post_id, '_rp_opportunity_location', true ) ); ?>
				<div class="rp-consultant-only-field">
					<?php rp_opportunities_text_field( 'duration', __( 'Duration of engagement', 'rp-resource-hub' ), false, get_post_meta( $post_id, '_rp_opportunity_duration', true ) ); ?>
					<label class="rp-checkbox-line"><input type="checkbox" name="require_portfolio" value="1" <?php checked( get_post_meta( $post_id, '_rp_opportunity_require_portfolio', true ), '1' ); ?>> <?php esc_html_e( 'Require portfolio/proof of work for consultant applications', 'rp-resource-hub' ); ?></label>
				</div>
			<?php else : ?>
				<?php rp_opportunities_text_field( 'reference_number', __( 'ITB reference number', 'rp-resource-hub' ), false, get_post_meta( $post_id, '_rp_opportunity_reference_number', true ) ); ?>
			<?php endif; ?>
			<?php rp_opportunities_textarea_field( 'description', __( 'Posting description', 'rp-resource-hub' ), true, $post->post_content ); ?>
			<?php rp_opportunities_textarea_field( 'deliverables', 'job' === $type ? __( 'Expected deliverables / scope notes', 'rp-resource-hub' ) : __( 'Procurement scope / requirements notes', 'rp-resource-hub' ), false, get_post_meta( $post_id, '_rp_opportunity_deliverables', true ) ); ?>
			<?php if ( 'job' === $type ) : ?>
				<?php rp_opportunities_file_field( 'opportunity_tor', __( 'Replace Terms of Reference document', 'rp-resource-hub' ), false ); ?>
				<?php echo wp_kses_post( rp_opportunities_admin_attachment_link( get_post_meta( $post_id, '_rp_opportunity_tor_id', true ) ) ); ?>
			<?php endif; ?>
			<?php rp_opportunities_file_field( 'opportunity_document', 'job' === $type ? __( 'Replace additional posting document', 'rp-resource-hub' ) : __( 'Replace bid / procurement document', 'rp-resource-hub' ), false, 'bid' ); ?>
			<?php echo wp_kses_post( rp_opportunities_admin_attachment_link( get_post_meta( $post_id, '_rp_opportunity_document_id', true ) ) ); ?>
			<button class="rp-button" type="submit"><?php esc_html_e( 'Save Posting', 'rp-resource-hub' ); ?></button>
		</form>
		<?php if ( 'job' === $type ) : ?>
			<script>
			(function() {
				var select = document.querySelector('.rp-opportunity-hiring-type');
				var field = document.querySelector('.rp-consultant-only-field');
				if (!select || !field) return;
				function syncConsultantField() {
					field.style.display = select.value === 'consultant' ? '' : 'none';
				}
				select.addEventListener('change', syncConsultantField);
				syncConsultantField();
			})();
			</script>
		<?php endif; ?>
	</div>
	<?php
	return ob_get_clean();
}

function rp_opportunities_render_submission_layer( $type, $opportunity_id ) {
	if ( ! get_post( $opportunity_id ) || ( 'job' === $type && 'job' !== rp_opportunities_get_type( $opportunity_id ) ) || ( 'bid' === $type && 'itb' !== rp_opportunities_get_type( $opportunity_id ) ) ) {
		return '<div class="rp-empty-state"><p>' . esc_html__( 'Selected posting could not be found.', 'rp-resource-hub' ) . '</p></div>';
	}
	$statuses = 'job' === $type ? rp_opportunities_job_status_options() : rp_opportunities_bid_status_options();
	$back_url = 'job' === $type ? home_url( '/job-applications-dashboard/' ) : home_url( '/bid-submissions-dashboard/' );
	$export_url = wp_nonce_url( admin_url( 'admin-post.php?action=rp_opportunity_export_submissions&type=' . $type . '&opportunity_id=' . absint( $opportunity_id ) ), 'rp_opportunity_export_' . $type . '_' . absint( $opportunity_id ) );
	ob_start();
	?>
	<div class="rp-opportunity-dashboard">
		<p><a href="<?php echo esc_url( $back_url ); ?>">&larr; <?php esc_html_e( 'Back to postings', 'rp-resource-hub' ); ?></a></p>
		<div class="rp-dashboard-header">
			<h2 class="rp-dashboard-title"><?php echo esc_html( get_the_title( $opportunity_id ) ); ?></h2>
			<p class="rp-dashboard-subtitle"><?php echo esc_html( 'job' === $type ? __( 'Applications related to this job opening.', 'rp-resource-hub' ) : __( 'Submissions related to this Invitation to Bid.', 'rp-resource-hub' ) ); ?></p>
			<a class="rp-button rp-button-secondary" href="<?php echo esc_url( $export_url ); ?>"><?php esc_html_e( 'Export CSV', 'rp-resource-hub' ); ?></a>
		</div>
		<div class="rp-opportunity-submissions-layout">
			<aside class="rp-opportunity-submissions-sidebar">
				<?php rp_opportunities_render_submission_filters( $type, $statuses, $opportunity_id ); ?>
			</aside>
			<div class="rp-opportunity-submissions-results" data-type="<?php echo esc_attr( $type ); ?>" data-opportunity-id="<?php echo esc_attr( $opportunity_id ); ?>">
				<?php echo rp_opportunities_render_submission_table( $type, $opportunity_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		</div>
	</div>
	<?php
	return ob_get_clean();
}

function rp_opportunities_submission_field_labels( $type ) {
	if ( 'job' === $type ) {
		return array(
			'alternative_contact'      => __( 'Alternative contact details', 'rp-resource-hub' ),
			'present_address'          => __( 'Complete present address', 'rp-resource-hub' ),
			'date_of_birth'            => __( 'Date of birth', 'rp-resource-hub' ),
			'education'                => __( 'Highest educational attainment', 'rp-resource-hub' ),
			'university'               => __( 'University/school', 'rp-resource-hub' ),
			'course'                   => __( 'Course/program', 'rp-resource-hub' ),
			'position_applied_for'     => __( 'Position applied for', 'rp-resource-hub' ),
			'application_source'       => __( 'Source of application', 'rp-resource-hub' ),
			'referrer_name'            => __( 'Referrer name', 'rp-resource-hub' ),
			'emergency_work'           => __( 'Emergency work', 'rp-resource-hub' ),
			'travel_outside_location'  => __( 'Travel/work outside location', 'rp-resource-hub' ),
			'overtime_weekends'        => __( 'Overtime/weekends/holidays', 'rp-resource-hub' ),
			'availability'             => __( 'Availability', 'rp-resource-hub' ),
			'health_conditions'        => __( 'Health conditions', 'rp-resource-hub' ),
			'last_salary'              => __( 'Last drawn salary', 'rp-resource-hub' ),
			'expected_salary'          => __( 'Expected salary', 'rp-resource-hub' ),
			'skillsets'                => __( 'Relevant skillsets', 'rp-resource-hub' ),
			'why_accord'               => __( 'Why ACCORD', 'rp-resource-hub' ),
			'cover_message'            => __( 'Cover message', 'rp-resource-hub' ),
			'consultancy_fee'          => __( 'Proposed consultancy fee', 'rp-resource-hub' ),
		);
	}

	return array(
		'message' => __( 'Message or remarks', 'rp-resource-hub' ),
	);
}

function rp_opportunities_decode_fields( $row ) {
	$fields = json_decode( $row->fields ? $row->fields : '{}', true );
	return is_array( $fields ) ? $fields : array();
}

function rp_opportunities_submission_filter_options( $type ) {
	if ( 'job' !== $type ) {
		return array();
	}

	$yes_no = array(
		'yes' => __( 'Yes', 'rp-resource-hub' ),
		'no'  => __( 'No', 'rp-resource-hub' ),
	);

	return array(
		'education'               => array(
			'post_grad'         => __( "Post Graduate Diploma/Master's Degree", 'rp-resource-hub' ),
			'bachelors'         => __( "Bachelor's Degree", 'rp-resource-hub' ),
			'vocational'        => __( 'Vocational Diploma', 'rp-resource-hub' ),
			'college_undergrad' => __( 'College Undergraduate', 'rp-resource-hub' ),
			'high_school'       => __( 'High School Graduate', 'rp-resource-hub' ),
			'other'             => __( 'Other', 'rp-resource-hub' ),
		),
		'application_source'      => array(
			'facebook'      => __( 'Facebook', 'rp-resource-hub' ),
			'referral'      => __( 'Referral', 'rp-resource-hub' ),
			'job_portal'    => __( 'Job Portal', 'rp-resource-hub' ),
			'linkedin'      => __( 'LinkedIn', 'rp-resource-hub' ),
			'word_of_mouth' => __( 'Word of Mouth', 'rp-resource-hub' ),
			'other'         => __( 'Other', 'rp-resource-hub' ),
		),
		'emergency_work'          => $yes_no,
		'travel_outside_location' => $yes_no,
		'overtime_weekends'       => $yes_no,
		'availability'            => array(
			'asap'         => __( 'ASAP', 'rp-resource-hub' ),
			'15_days'      => __( '15 days notice', 'rp-resource-hub' ),
			'30_days'      => __( '30 days notice', 'rp-resource-hub' ),
			'more_than_30' => __( 'More than 30 days notice', 'rp-resource-hub' ),
			'other'        => __( 'Other', 'rp-resource-hub' ),
		),
	);
}

function rp_opportunities_render_submission_filters( $type, $statuses, $opportunity_id = 0 ) {
	$labels = rp_opportunities_submission_field_labels( $type );
	$options = rp_opportunities_submission_filter_options( $type );
	?>
	<form class="rp-opportunity-submission-filters" method="get">
		<input type="hidden" name="opportunity_id" value="<?php echo esc_attr( $opportunity_id ? absint( $opportunity_id ) : ( isset( $_GET['opportunity_id'] ) ? absint( $_GET['opportunity_id'] ) : 0 ) ); ?>">
		<input type="hidden" name="submission_type" value="<?php echo esc_attr( $type ); ?>">
		<label><span><?php echo esc_html( 'job' === $type ? __( 'Full name', 'rp-resource-hub' ) : __( 'Company / supplier', 'rp-resource-hub' ) ); ?></span><input type="search" name="filter_name" value="<?php echo esc_attr( isset( $_GET['filter_name'] ) ? sanitize_text_field( wp_unslash( $_GET['filter_name'] ) ) : '' ); ?>"></label>
		<?php if ( 'bid' === $type ) : ?>
			<label><span><?php esc_html_e( 'Contact person', 'rp-resource-hub' ); ?></span><input type="search" name="filter_contact_person" value="<?php echo esc_attr( isset( $_GET['filter_contact_person'] ) ? sanitize_text_field( wp_unslash( $_GET['filter_contact_person'] ) ) : '' ); ?>"></label>
		<?php endif; ?>
		<label><span><?php esc_html_e( 'Email', 'rp-resource-hub' ); ?></span><input type="search" name="filter_email" value="<?php echo esc_attr( isset( $_GET['filter_email'] ) ? sanitize_text_field( wp_unslash( $_GET['filter_email'] ) ) : '' ); ?>"></label>
		<label><span><?php esc_html_e( 'Phone', 'rp-resource-hub' ); ?></span><input type="search" name="filter_phone" value="<?php echo esc_attr( isset( $_GET['filter_phone'] ) ? sanitize_text_field( wp_unslash( $_GET['filter_phone'] ) ) : '' ); ?>"></label>
		<label><span><?php esc_html_e( 'Status', 'rp-resource-hub' ); ?></span><select name="filter_status"><option value=""><?php esc_html_e( 'All statuses', 'rp-resource-hub' ); ?></option><?php foreach ( $statuses as $value => $label ) : ?><option value="<?php echo esc_attr( $value ); ?>" <?php selected( isset( $_GET['filter_status'] ) ? sanitize_key( wp_unslash( $_GET['filter_status'] ) ) : '', $value ); ?>><?php echo esc_html( $label ); ?></option><?php endforeach; ?></select></label>
		<?php foreach ( $labels as $key => $label ) : ?>
			<?php $filter_value = isset( $_GET[ 'filter_field_' . $key ] ) ? sanitize_text_field( wp_unslash( $_GET[ 'filter_field_' . $key ] ) ) : ''; ?>
			<label><span><?php echo esc_html( $label ); ?></span>
				<?php if ( isset( $options[ $key ] ) ) : ?>
					<select name="<?php echo esc_attr( 'filter_field_' . $key ); ?>">
						<option value=""><?php esc_html_e( 'Any', 'rp-resource-hub' ); ?></option>
						<?php foreach ( $options[ $key ] as $value => $option_label ) : ?>
							<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $filter_value, $value ); ?>><?php echo esc_html( $option_label ); ?></option>
						<?php endforeach; ?>
					</select>
				<?php else : ?>
					<input type="search" name="<?php echo esc_attr( 'filter_field_' . $key ); ?>" value="<?php echo esc_attr( $filter_value ); ?>">
				<?php endif; ?>
			</label>
		<?php endforeach; ?>
		<button class="rp-button" type="submit"><?php esc_html_e( 'Filter', 'rp-resource-hub' ); ?></button>
	</form>
	<?php
}

function rp_opportunities_render_submission_table( $type, $opportunity_id ) {
	global $wpdb;
	$table = 'job' === $type ? $wpdb->prefix . 'rp_job_applications' : $wpdb->prefix . 'rp_bid_submissions';
	$rows = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table WHERE opportunity_id = %d ORDER BY submitted_at DESC", $opportunity_id ) );
	$rows = rp_opportunities_filter_submission_rows( $type, $rows );
	$statuses = 'job' === $type ? rp_opportunities_job_status_options() : rp_opportunities_bid_status_options();
	$labels = rp_opportunities_submission_field_labels( $type );
	ob_start();
	?>
	<div class="rp-table-responsive">
		<table class="rp-moderation-table rp-opportunity-dashboard-table rp-opportunity-submissions-table">
			<thead>
				<tr>
					<th><?php echo esc_html( 'job' === $type ? __( 'Applicant', 'rp-resource-hub' ) : __( 'Supplier', 'rp-resource-hub' ) ); ?></th>
					<?php if ( 'bid' === $type ) : ?><th><?php esc_html_e( 'Contact Person', 'rp-resource-hub' ); ?></th><?php endif; ?>
					<th><?php esc_html_e( 'Email', 'rp-resource-hub' ); ?></th>
					<th><?php esc_html_e( 'Phone', 'rp-resource-hub' ); ?></th>
					<?php foreach ( $labels as $label ) : ?><th><?php echo esc_html( $label ); ?></th><?php endforeach; ?>
					<th><?php esc_html_e( 'Submitted', 'rp-resource-hub' ); ?></th>
					<th><?php esc_html_e( 'Status', 'rp-resource-hub' ); ?></th>
					<th><?php esc_html_e( 'Documents', 'rp-resource-hub' ); ?></th>
					<th><?php esc_html_e( 'Update', 'rp-resource-hub' ); ?></th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ( $rows as $row ) : $fields = rp_opportunities_submission_flat_fields( $type, $row ); ?>
				<tr>
					<td><strong><?php echo esc_html( 'job' === $type ? $row->full_name : $row->company_name ); ?></strong></td>
					<?php if ( 'bid' === $type ) : ?><td><?php echo esc_html( $row->contact_person ); ?></td><?php endif; ?>
					<td><?php echo esc_html( $row->email ); ?></td>
					<td><?php echo esc_html( $row->phone ); ?></td>
					<?php foreach ( $labels as $key => $label ) : ?><td><?php echo esc_html( isset( $fields[ $key ] ) ? rp_opportunities_submission_field_display_value( $type, $key, $fields[ $key ] ) : '' ); ?></td><?php endforeach; ?>
					<td><?php echo esc_html( rp_opportunities_format_datetime( $row->submitted_at ) ); ?></td>
					<td><span class="rp-status-badge"><?php echo esc_html( isset( $statuses[ $row->status ] ) ? $statuses[ $row->status ] : $row->status ); ?></span></td>
					<td><?php echo wp_kses_post( rp_opportunities_submission_attachment_links( $type, $row ) ); ?></td>
					<td><?php rp_opportunities_render_status_form( $type, $row, $statuses ); ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<?php if ( ! $rows ) : ?><div class="rp-empty-state"><p><?php esc_html_e( 'No submissions yet.', 'rp-resource-hub' ); ?></p></div><?php endif; ?>
	<?php
	return ob_get_clean();
}

function rp_opportunities_submission_flat_fields( $type, $row ) {
	$fields = rp_opportunities_decode_fields( $row );
	if ( 'bid' === $type && ! empty( $row->message ) ) {
		$fields['message'] = $row->message;
	}
	return $fields;
}

function rp_opportunities_submission_field_display_value( $type, $key, $value ) {
	$options = rp_opportunities_submission_filter_options( $type );
	if ( isset( $options[ $key ][ $value ] ) ) {
		return $options[ $key ][ $value ];
	}
	return $value;
}

function rp_opportunities_filter_submission_rows( $type, $rows ) {
	$name_filter = isset( $_GET['filter_name'] ) ? strtolower( sanitize_text_field( wp_unslash( $_GET['filter_name'] ) ) ) : '';
	$contact_person_filter = isset( $_GET['filter_contact_person'] ) ? strtolower( sanitize_text_field( wp_unslash( $_GET['filter_contact_person'] ) ) ) : '';
	$email_filter = isset( $_GET['filter_email'] ) ? strtolower( sanitize_text_field( wp_unslash( $_GET['filter_email'] ) ) ) : '';
	$phone_filter = isset( $_GET['filter_phone'] ) ? strtolower( sanitize_text_field( wp_unslash( $_GET['filter_phone'] ) ) ) : '';
	$status_filter = isset( $_GET['filter_status'] ) ? sanitize_key( wp_unslash( $_GET['filter_status'] ) ) : '';
	$field_labels = rp_opportunities_submission_field_labels( $type );

	return array_values(
		array_filter(
			$rows,
			function ( $row ) use ( $type, $name_filter, $contact_person_filter, $email_filter, $phone_filter, $status_filter, $field_labels ) {
				$name = 'job' === $type ? $row->full_name : $row->company_name;
				if ( $name_filter && false === strpos( strtolower( $name ), $name_filter ) ) {
					return false;
				}
				if ( 'bid' === $type && $contact_person_filter && false === strpos( strtolower( $row->contact_person ), $contact_person_filter ) ) {
					return false;
				}
				if ( $email_filter && false === strpos( strtolower( $row->email ), $email_filter ) ) {
					return false;
				}
				if ( $phone_filter && false === strpos( strtolower( $row->phone ), $phone_filter ) ) {
					return false;
				}
				if ( $status_filter && $row->status !== $status_filter ) {
					return false;
				}
				$fields = rp_opportunities_decode_fields( $row );
				foreach ( array_keys( $field_labels ) as $key ) {
					$filter_key = 'filter_field_' . $key;
					$needle = isset( $_GET[ $filter_key ] ) ? strtolower( sanitize_text_field( wp_unslash( $_GET[ $filter_key ] ) ) ) : '';
					if ( $needle && false === strpos( strtolower( isset( $fields[ $key ] ) ? (string) $fields[ $key ] : ( isset( $row->$key ) ? (string) $row->$key : '' ) ), $needle ) ) {
						return false;
					}
				}
				return true;
			}
		)
	);
}

function rp_opportunities_submission_details_html( $type, $row ) {
	$fields = rp_opportunities_submission_flat_fields( $type, $row );
	$labels = rp_opportunities_submission_field_labels( $type );
	$out = '<dl class="rp-submission-details">';
	foreach ( $labels as $key => $label ) {
		if ( ! isset( $fields[ $key ] ) || '' === (string) $fields[ $key ] ) {
			continue;
		}
		$out .= '<div><dt>' . esc_html( $label ) . '</dt><dd>' . esc_html( $fields[ $key ] ) . '</dd></div>';
	}
	$out .= '</dl>';
	return $out;
}

function rp_opportunities_ajax_filter_submissions() {
	$type = isset( $_POST['type'] ) ? sanitize_key( wp_unslash( $_POST['type'] ) ) : '';
	$opportunity_id = isset( $_POST['opportunity_id'] ) ? absint( $_POST['opportunity_id'] ) : 0;
	if ( ! in_array( $type, array( 'job', 'bid' ), true ) || ! $opportunity_id || ! rp_opportunities_user_can_manage_submission_type( $type ) ) {
		wp_send_json_error( array( 'message' => __( 'Unauthorized request.', 'rp-resource-hub' ) ), 403 );
	}

	foreach ( $_POST as $key => $value ) {
		if ( 0 === strpos( $key, 'filter_' ) ) {
			$_GET[ $key ] = wp_unslash( $value );
		}
	}

	wp_send_json_success(
		array(
			'html' => rp_opportunities_render_submission_table( $type, $opportunity_id ),
		)
	);
}
add_action( 'wp_ajax_rp_filter_opportunity_submissions', 'rp_opportunities_ajax_filter_submissions' );

function rp_opportunities_submission_attachment_links( $type, $row ) {
	$ids = json_decode( $row->attachment_ids ? $row->attachment_ids : '{}', true );
	if ( ! is_array( $ids ) || ! $ids ) {
		return '-';
	}
	$out = '<ul class="rp-opportunity-attachments">';
	foreach ( $ids as $field => $attachment_id ) {
		$attachment_id = absint( $attachment_id );
		$url = wp_nonce_url( admin_url( 'admin-post.php?action=rp_opportunity_download_attachment&type=' . $type . '&submission_id=' . absint( $row->id ) . '&attachment_id=' . $attachment_id ), 'rp_opportunity_download_' . $type . '_' . absint( $row->id ) . '_' . $attachment_id );
		$out .= '<li><a href="' . esc_url( $url ) . '">' . esc_html( ucwords( str_replace( '_', ' ', $field ) ) ) . '</a></li>';
	}
	$out .= '</ul>';
	return $out;
}

function rp_opportunities_render_status_form( $type, $row, $statuses ) {
	?>
	<form class="rp-opportunity-status-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		<input type="hidden" name="action" value="rp_opportunity_update_submission">
		<input type="hidden" name="type" value="<?php echo esc_attr( $type ); ?>">
		<input type="hidden" name="submission_id" value="<?php echo esc_attr( $row->id ); ?>">
		<?php wp_nonce_field( 'rp_opportunity_update_' . $type . '_' . absint( $row->id ), 'rp_opportunity_update_nonce' ); ?>
		<select name="status">
			<?php foreach ( $statuses as $value => $label ) : ?>
				<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $row->status, $value ); ?>><?php echo esc_html( $label ); ?></option>
			<?php endforeach; ?>
		</select>
		<textarea name="note" rows="2" placeholder="<?php esc_attr_e( 'Internal note', 'rp-resource-hub' ); ?>"></textarea>
		<div class="rp-dashboard-actions">
			<button class="rp-button" type="submit" name="save_status" value="1"><?php esc_html_e( 'Save Status', 'rp-resource-hub' ); ?></button>
			<button class="rp-button rp-button-secondary" type="submit" name="send_notice" value="1"><?php esc_html_e( 'Send Status Email', 'rp-resource-hub' ); ?></button>
		</div>
	</form>
	<?php
}

function rp_opportunities_handle_update_submission() {
	$type = isset( $_POST['type'] ) ? sanitize_key( wp_unslash( $_POST['type'] ) ) : '';
	$submission_id = isset( $_POST['submission_id'] ) ? absint( $_POST['submission_id'] ) : 0;
	if ( ! in_array( $type, array( 'job', 'bid' ), true ) || ! $submission_id || ! rp_opportunities_user_can_manage_submission_type( $type ) || ! wp_verify_nonce( isset( $_POST['rp_opportunity_update_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['rp_opportunity_update_nonce'] ) ) : '', 'rp_opportunity_update_' . $type . '_' . $submission_id ) ) {
		wp_die( esc_html__( 'Unauthorized request.', 'rp-resource-hub' ) );
	}

	$submission = rp_opportunities_get_submission( $type, $submission_id );
	if ( ! $submission ) {
		wp_die( esc_html__( 'Submission not found.', 'rp-resource-hub' ) );
	}
	$statuses = 'job' === $type ? rp_opportunities_job_status_options() : rp_opportunities_bid_status_options();
	$new_status = isset( $_POST['status'] ) ? sanitize_key( wp_unslash( $_POST['status'] ) ) : $submission->status;
	if ( ! isset( $statuses[ $new_status ] ) ) {
		$new_status = $submission->status;
	}
	$note = isset( $_POST['note'] ) ? wp_kses_post( wp_unslash( $_POST['note'] ) ) : '';
	global $wpdb;
	$table = 'job' === $type ? $wpdb->prefix . 'rp_job_applications' : $wpdb->prefix . 'rp_bid_submissions';
	$wpdb->update(
		$table,
		array(
			'status'          => $new_status,
			'updated_at'      => current_time( 'mysql' ),
			'last_updated_by' => get_current_user_id(),
		),
		array( 'id' => $submission_id ),
		array( '%s', '%s', '%d' ),
		array( '%d' )
	);
	rp_opportunities_add_note( $type, $submission_id, get_current_user_id(), 'internal', $submission->status, $new_status, '', $note ? $note : __( 'Status updated.', 'rp-resource-hub' ) );

	if ( ! empty( $_POST['send_notice'] ) ) {
		rp_opportunities_send_status_notice( $type, $submission_id, $new_status );
	}

	$dashboard = 'job' === $type ? home_url( '/job-applications-dashboard/' ) : home_url( '/bid-submissions-dashboard/' );
	wp_safe_redirect( add_query_arg( 'opportunity_id', absint( $submission->opportunity_id ), $dashboard ) );
	exit;
}
add_action( 'admin_post_rp_opportunity_update_submission', 'rp_opportunities_handle_update_submission' );

function rp_opportunities_send_status_notice( $type, $submission_id, $status ) {
	$submission = rp_opportunities_get_submission( $type, $submission_id );
	if ( ! $submission ) {
		return;
	}
	$dept_email = 'job' === $type ? RP_JOB_NOTIFICATION_EMAIL : RP_BID_NOTIFICATION_EMAIL;
	$name = 'job' === $type ? $submission->full_name : $submission->company_name;
	$post_title = get_the_title( $submission->opportunity_id );
	$email_col = 'successful' === $status ? 'success_email_sent_at' : 'unsuccessful_email_sent_at';
	if ( in_array( $status, array( 'successful', 'unsuccessful' ), true ) && ! empty( $submission->$email_col ) ) {
		rp_opportunities_add_note( $type, $submission_id, get_current_user_id(), 'system', $submission->status, $status, $status, __( 'Status email was not sent because this notice had already been sent.', 'rp-resource-hub' ) );
		return;
	}
	$status_labels = 'job' === $type ? rp_opportunities_job_status_options() : rp_opportunities_bid_status_options();
	$status_label = isset( $status_labels[ $status ] ) ? $status_labels[ $status ] : $status;
	$submission_label = 'job' === $type ? __( 'application', 'rp-resource-hub' ) : __( 'bid submission', 'rp-resource-hub' );
	$subject = sprintf( '%1$s: %2$s', $status_label, $post_title );
	$status_messages = array(
		'received'             => __( 'has been received and logged by ACCORD.', 'rp-resource-hub' ),
		'under_review'         => __( 'is currently under review.', 'rp-resource-hub' ),
		'shortlisted'          => __( 'has been shortlisted for the next stage.', 'rp-resource-hub' ),
		'interview'            => __( 'has been moved to the interview stage. HR will contact you with details when applicable.', 'rp-resource-hub' ),
		'clarification_needed' => __( 'requires clarification. Procurement will contact you if additional information is needed.', 'rp-resource-hub' ),
		'responsive'           => __( 'has been marked responsive after review.', 'rp-resource-hub' ),
		'non_responsive'       => __( 'has been marked non-responsive after review.', 'rp-resource-hub' ),
		'successful'           => __( 'has been marked successful.', 'rp-resource-hub' ),
		'unsuccessful'         => __( 'has been marked unsuccessful.', 'rp-resource-hub' ),
		'withdrawn'            => __( 'has been marked withdrawn.', 'rp-resource-hub' ),
	);
	$status_message = isset( $status_messages[ $status ] ) ? $status_messages[ $status ] : sprintf( __( 'has been updated to %s.', 'rp-resource-hub' ), $status_label );
	$message = sprintf( "Dear %s,\n\nThis is to inform you that your %s for %s %s\n\n%s", $name, $submission_label, $post_title, $status_message, rp_opportunities_system_note( $dept_email ) );
	$sent = rp_opportunities_send_mail( $submission->email, $subject, $message, array( $dept_email ), $dept_email );
	if ( $sent ) {
		global $wpdb;
		$table = 'job' === $type ? $wpdb->prefix . 'rp_job_applications' : $wpdb->prefix . 'rp_bid_submissions';
		if ( in_array( $status, array( 'successful', 'unsuccessful' ), true ) ) {
			$wpdb->update( $table, array( $email_col => current_time( 'mysql' ) ), array( 'id' => $submission_id ), array( '%s' ), array( '%d' ) );
		}
		rp_opportunities_add_note( $type, $submission_id, get_current_user_id(), 'system', $submission->status, $status, $status, __( 'Status email sent to submitter.', 'rp-resource-hub' ) );
	} else {
		rp_opportunities_add_note( $type, $submission_id, get_current_user_id(), 'system', $submission->status, $status, $status, __( 'Status email failed to send.', 'rp-resource-hub' ) );
	}
}

function rp_opportunities_handle_download_attachment() {
	$type = isset( $_GET['type'] ) ? sanitize_key( wp_unslash( $_GET['type'] ) ) : '';
	$submission_id = isset( $_GET['submission_id'] ) ? absint( $_GET['submission_id'] ) : 0;
	$attachment_id = isset( $_GET['attachment_id'] ) ? absint( $_GET['attachment_id'] ) : 0;
	if ( ! in_array( $type, array( 'job', 'bid' ), true ) || ! $submission_id || ! $attachment_id || ! rp_opportunities_user_can_manage_submission_type( $type ) || ! wp_verify_nonce( isset( $_GET['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) : '', 'rp_opportunity_download_' . $type . '_' . $submission_id . '_' . $attachment_id ) ) {
		wp_die( esc_html__( 'Unauthorized download.', 'rp-resource-hub' ) );
	}
	if ( get_post_meta( $attachment_id, '_rp_opportunity_submission_type', true ) !== $type || absint( get_post_meta( $attachment_id, '_rp_opportunity_submission_id', true ) ) !== $submission_id ) {
		wp_die( esc_html__( 'Attachment mismatch.', 'rp-resource-hub' ) );
	}
	$file = get_attached_file( $attachment_id );
	if ( ! $file || ! file_exists( $file ) ) {
		wp_die( esc_html__( 'File not found.', 'rp-resource-hub' ) );
	}
	nocache_headers();
	header( 'Content-Type: application/octet-stream' );
	header( 'Content-Disposition: attachment; filename="' . basename( $file ) . '"' );
	header( 'Content-Length: ' . filesize( $file ) );
	readfile( $file ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_readfile
	exit;
}
add_action( 'admin_post_rp_opportunity_download_attachment', 'rp_opportunities_handle_download_attachment' );

function rp_opportunities_handle_export() {
	$type = isset( $_GET['type'] ) ? sanitize_key( wp_unslash( $_GET['type'] ) ) : '';
	$opportunity_id = isset( $_GET['opportunity_id'] ) ? absint( $_GET['opportunity_id'] ) : 0;
	if ( ! in_array( $type, array( 'job', 'bid' ), true ) || ! $opportunity_id || ! rp_opportunities_user_can_manage_submission_type( $type ) ) {
		wp_die( esc_html__( 'Unauthorized export.', 'rp-resource-hub' ) );
	}
	check_admin_referer( 'rp_opportunity_export_' . $type . '_' . $opportunity_id );
	global $wpdb;
	$table = 'job' === $type ? $wpdb->prefix . 'rp_job_applications' : $wpdb->prefix . 'rp_bid_submissions';
	$rows = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table WHERE opportunity_id = %d ORDER BY submitted_at DESC", $opportunity_id ), ARRAY_A );
	$labels = rp_opportunities_submission_field_labels( $type );
	nocache_headers();
	header( 'Content-Type: text/csv; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename="' . $type . '-submissions-' . $opportunity_id . '-' . gmdate( 'Y-m-d' ) . '.csv"' );
	$out = fopen( 'php://output', 'w' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen
	if ( $rows ) {
		$headings = array( 'id', 'opportunity_id', 'name' );
		if ( 'bid' === $type ) {
			$headings[] = 'contact_person';
		}
		$headings = array_merge( $headings, array( 'email', 'phone' ), array_values( $labels ), array( 'status', 'submitted_at', 'updated_at' ) );
		fputcsv( $out, $headings );
		foreach ( $rows as $row ) {
			$field_row = (object) $row;
			$fields = rp_opportunities_submission_flat_fields( $type, $field_row );
			$csv_row = array(
				$row['id'],
				$row['opportunity_id'],
				'job' === $type ? $row['full_name'] : $row['company_name'],
			);
			if ( 'bid' === $type ) {
				$csv_row[] = $row['contact_person'];
			}
			$csv_row[] = $row['email'];
			$csv_row[] = $row['phone'];
			foreach ( array_keys( $labels ) as $key ) {
				$csv_row[] = isset( $fields[ $key ] ) ? $fields[ $key ] : '';
			}
			$csv_row[] = $row['status'];
			$csv_row[] = $row['submitted_at'];
			$csv_row[] = $row['updated_at'];
			fputcsv( $out, $csv_row );
		}
	} else {
		fputcsv( $out, array( 'No submissions' ) );
	}
	fclose( $out ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fclose
	exit;
}
add_action( 'admin_post_rp_opportunity_export_submissions', 'rp_opportunities_handle_export' );

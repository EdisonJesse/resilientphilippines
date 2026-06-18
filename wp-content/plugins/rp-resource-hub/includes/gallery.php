<?php
/**
 * Member photo gallery submissions with SharePoint original storage.
 *
 * @package RPResourceHub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'RP_GALLERY_MAX_UPLOAD_BYTES' ) ) {
	define( 'RP_GALLERY_MAX_UPLOAD_BYTES', 15 * 1024 * 1024 );
}
if ( ! defined( 'RP_GALLERY_MAX_BULK_FILES' ) ) {
	define( 'RP_GALLERY_MAX_BULK_FILES', 10 );
}
if ( ! defined( 'RP_GALLERY_SP_TENANT_ID' ) ) {
	define( 'RP_GALLERY_SP_TENANT_ID', '' );
}
if ( ! defined( 'RP_GALLERY_SP_CLIENT_ID' ) ) {
	define( 'RP_GALLERY_SP_CLIENT_ID', '' );
}
if ( ! defined( 'RP_GALLERY_SP_CLIENT_SECRET' ) ) {
	define( 'RP_GALLERY_SP_CLIENT_SECRET', '' );
}
if ( ! defined( 'RP_GALLERY_SP_DRIVE_ID' ) ) {
	define( 'RP_GALLERY_SP_DRIVE_ID', '' );
}
if ( ! defined( 'RP_GALLERY_SP_UPLOAD_ROOT' ) ) {
	define( 'RP_GALLERY_SP_UPLOAD_ROOT', '' );
}

function rp_gallery_register_post_type_and_taxonomy() {
	register_post_type(
		'rp_gallery_photo',
		array(
			'labels'              => array(
				'name'          => __( 'Gallery Photos', 'rp-resource-hub' ),
				'singular_name' => __( 'Gallery Photo', 'rp-resource-hub' ),
				'add_new_item'  => __( 'Add Gallery Photo', 'rp-resource-hub' ),
				'edit_item'     => __( 'Edit Gallery Photo', 'rp-resource-hub' ),
				'menu_name'     => __( 'Gallery Photos', 'rp-resource-hub' ),
			),
			'public'              => true,
			'show_in_rest'        => true,
			'has_archive'         => true,
			'menu_icon'           => 'dashicons-format-gallery',
			'supports'            => array( 'title', 'editor', 'thumbnail', 'author', 'custom-fields', 'revisions' ),
			'rewrite'             => array( 'slug' => 'gallery-photo' ),
			'exclude_from_search' => false,
		)
	);

	register_taxonomy(
		'rp_gallery_tag',
		array( 'rp_gallery_photo' ),
		array(
			'labels'            => array(
				'name'          => __( 'Gallery Tags', 'rp-resource-hub' ),
				'singular_name' => __( 'Gallery Tag', 'rp-resource-hub' ),
				'search_items'  => __( 'Search Gallery Tags', 'rp-resource-hub' ),
				'add_new_item'  => __( 'Add Gallery Tag', 'rp-resource-hub' ),
			),
			'hierarchical'      => false,
			'public'            => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'gallery-tag' ),
		)
	);
}
add_action( 'init', 'rp_gallery_register_post_type_and_taxonomy' );

function rp_gallery_seed_tags() {
	$tags = array( 'Portrait', 'Event', 'Disaster', 'WASH', 'Shelter', 'Livelihood', 'Training', 'Community', 'Assessment', 'Response', 'Recovery', 'Advocacy', 'Partnership', 'Volunteers' );
	foreach ( $tags as $tag ) {
		if ( ! term_exists( $tag, 'rp_gallery_tag' ) ) {
			wp_insert_term( $tag, 'rp_gallery_tag' );
		}
	}
}
add_action( 'init', 'rp_gallery_seed_tags', 20 );

function rp_gallery_project_options() {
	return apply_filters(
		'rp_gallery_project_options',
		array(
			'WASH',
			'Shelter',
			'Livelihood',
			'Disaster Response',
			'DRR',
			'Training',
			'Advocacy',
			'Partnership',
			'Event',
			'Other',
		)
	);
}

function rp_gallery_upload_url() {
	return home_url( '/submit-photo/' );
}

function rp_gallery_shortcode_upload_form() {
	ob_start();

	if ( ! is_user_logged_in() ) {
		echo '<div class="rp-notice rp-notice-error">' . sprintf(
			__( 'Please <a href="%s">log in</a> to submit a photo.', 'rp-resource-hub' ),
			esc_url( home_url( '/portal-entry/?redirect_to=' . urlencode( rp_gallery_upload_url() ) ) )
		) . '</div>';
		return ob_get_clean();
	}

	$notice = function_exists( 'rp_resource_hub_get_upload_notice' ) ? rp_resource_hub_get_upload_notice() : false;
	if ( $notice ) {
		$notice_class = 'success' === $notice['type'] ? 'rp-notice-success' : 'rp-notice-error';
		echo '<div class="rp-notice ' . esc_attr( $notice_class ) . '">' . esc_html( $notice['message'] ) . '</div>';
	}

	$tags = get_terms(
		array(
			'taxonomy'   => 'rp_gallery_tag',
			'hide_empty' => false,
			'orderby'    => 'name',
		)
	);
	?>
	<form class="rp-upload-form rp-gallery-upload-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" enctype="multipart/form-data">
		<input type="hidden" name="action" value="rp_gallery_photo_upload">
		<?php wp_nonce_field( 'rp_gallery_photo_upload', 'rp_gallery_photo_upload_nonce' ); ?>

		<div class="rp-field">
			<label for="rp_gallery_photo"><?php esc_html_e( 'Photos', 'rp-resource-hub' ); ?></label>
			<input id="rp_gallery_photo" name="rp_gallery_photo[]" type="file" accept="image/jpeg,image/png,image/webp" multiple required>
			<p class="rp-field-help"><?php printf( esc_html__( 'Upload up to %d JPG, PNG, or WebP files. Shared information, tags, and consent will apply to every photo. Originals will be stored privately in SharePoint.', 'rp-resource-hub' ), absint( RP_GALLERY_MAX_BULK_FILES ) ); ?></p>
		</div>

		<div class="rp-field">
			<label for="rp_gallery_location"><?php esc_html_e( 'Location where photo was taken', 'rp-resource-hub' ); ?></label>
			<input id="rp_gallery_location" name="rp_gallery_location" type="text" required maxlength="190">
		</div>

		<div class="rp-field">
			<label for="rp_gallery_photo_date"><?php esc_html_e( 'Date photo was taken', 'rp-resource-hub' ); ?></label>
			<input id="rp_gallery_photo_date" name="rp_gallery_photo_date" type="date" required>
		</div>

		<div class="rp-field">
			<label for="rp_gallery_caption"><?php esc_html_e( 'Caption', 'rp-resource-hub' ); ?></label>
			<textarea id="rp_gallery_caption" name="rp_gallery_caption" rows="4" required maxlength="1000"></textarea>
		</div>

		<div class="rp-field">
			<label for="rp_gallery_project"><?php esc_html_e( 'Project / Program', 'rp-resource-hub' ); ?></label>
			<select id="rp_gallery_project" name="rp_gallery_project" required>
				<option value=""><?php esc_html_e( 'Select a project or program...', 'rp-resource-hub' ); ?></option>
				<?php foreach ( rp_gallery_project_options() as $option ) : ?>
					<option value="<?php echo esc_attr( $option ); ?>"><?php echo esc_html( $option ); ?></option>
				<?php endforeach; ?>
			</select>
		</div>

		<div class="rp-field">
			<label for="rp_gallery_project_other"><?php esc_html_e( 'Other project / program', 'rp-resource-hub' ); ?></label>
			<input id="rp_gallery_project_other" name="rp_gallery_project_other" type="text" maxlength="190">
		</div>

		<div class="rp-field">
			<label for="rp_gallery_photographer"><?php esc_html_e( 'Photographer or source name', 'rp-resource-hub' ); ?></label>
			<input id="rp_gallery_photographer" name="rp_gallery_photographer" type="text" maxlength="190">
		</div>

		<?php if ( ! empty( $tags ) && ! is_wp_error( $tags ) ) : ?>
			<div class="rp-field rp-gallery-tags-field">
				<label><?php esc_html_e( 'Suggested tags', 'rp-resource-hub' ); ?></label>
				<div class="rp-checkbox-list">
					<?php foreach ( $tags as $tag ) : ?>
						<label><input type="checkbox" name="rp_gallery_tags[]" value="<?php echo absint( $tag->term_id ); ?>"> <?php echo esc_html( $tag->name ); ?></label>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>

		<div class="rp-field">
			<label for="rp_gallery_notes"><?php esc_html_e( 'Optional notes for reviewers', 'rp-resource-hub' ); ?></label>
			<textarea id="rp_gallery_notes" name="rp_gallery_notes" rows="3" maxlength="1000"></textarea>
		</div>

		<div class="rp-opportunity-consent-statement">
			<h3><?php esc_html_e( 'Consent agreement', 'rp-resource-hub' ); ?></h3>
			<div class="rp-opportunity-consent-body">
				<label class="rp-checkbox-line"><input type="checkbox" name="rp_gallery_consent_use" value="1" required> <?php esc_html_e( 'I allow ACCORD to use, publish, archive, edit, crop, resize, and adapt this photo for communications, reporting, advocacy, fundraising, documentation, and other organizational purposes.', 'rp-resource-hub' ); ?></label>
				<label class="rp-checkbox-line"><input type="checkbox" name="rp_gallery_consent_people" value="1" required> <?php esc_html_e( 'I confirm that people visible in the photo gave consent to be photographed and for the image to be shared with ACCORD.', 'rp-resource-hub' ); ?></label>
				<label class="rp-checkbox-line"><input type="checkbox" name="rp_gallery_consent_sensitive" value="1" required> <?php esc_html_e( 'I confirm that this photo does not expose private, unsafe, sensitive, or harmful information.', 'rp-resource-hub' ); ?></label>
				<label class="rp-checkbox-line"><input type="checkbox" name="rp_gallery_consent_rights" value="1" required> <?php esc_html_e( 'I confirm that I have the right to submit this photo.', 'rp-resource-hub' ); ?></label>
			</div>
		</div>

		<button type="submit" class="rp-button"><?php esc_html_e( 'Submit Photo(s)', 'rp-resource-hub' ); ?></button>
	</form>
	<?php
	return ob_get_clean();
}
add_shortcode( 'rp_photo_upload_form', 'rp_gallery_shortcode_upload_form' );

function rp_gallery_store_notice_and_redirect( $type, $message ) {
	if ( function_exists( 'rp_resource_hub_store_upload_notice' ) ) {
		$key = rp_resource_hub_store_upload_notice( $type, $message );
		wp_safe_redirect( add_query_arg( 'rp_upload_notice', $key, rp_gallery_upload_url() ) );
		exit;
	}

	wp_safe_redirect( rp_gallery_upload_url() );
	exit;
}

function rp_gallery_allowed_file( $file ) {
	if ( empty( $file['tmp_name'] ) || ! is_uploaded_file( $file['tmp_name'] ) ) {
		return new WP_Error( 'missing_file', __( 'Photo file is required.', 'rp-resource-hub' ) );
	}
	if ( ! empty( $file['size'] ) && $file['size'] > RP_GALLERY_MAX_UPLOAD_BYTES ) {
		return new WP_Error( 'file_too_large', __( 'Photo file is too large.', 'rp-resource-hub' ) );
	}
	$type = wp_check_filetype_and_ext( $file['tmp_name'], $file['name'] );
	$allowed = array( 'jpg', 'jpeg', 'png', 'webp' );
	if ( empty( $type['ext'] ) || ! in_array( strtolower( $type['ext'] ), $allowed, true ) ) {
		return new WP_Error( 'invalid_type', __( 'Only JPG, PNG, and WebP images are allowed.', 'rp-resource-hub' ) );
	}
	return $type;
}

function rp_gallery_normalize_uploaded_files( $field ) {
	if ( empty( $field['name'] ) ) {
		return array();
	}

	$files = array();
	if ( is_array( $field['name'] ) ) {
		foreach ( $field['name'] as $index => $name ) {
			if ( empty( $name ) ) {
				continue;
			}

			$files[] = array(
				'name'     => $name,
				'type'     => isset( $field['type'][ $index ] ) ? $field['type'][ $index ] : '',
				'tmp_name' => isset( $field['tmp_name'][ $index ] ) ? $field['tmp_name'][ $index ] : '',
				'error'    => isset( $field['error'][ $index ] ) ? $field['error'][ $index ] : UPLOAD_ERR_NO_FILE,
				'size'     => isset( $field['size'][ $index ] ) ? $field['size'][ $index ] : 0,
			);
		}
	} else {
		$files[] = $field;
	}

	return $files;
}

function rp_gallery_handle_upload() {
	if ( ! is_user_logged_in() ) {
		wp_safe_redirect( home_url( '/portal-entry/?redirect_to=' . urlencode( rp_gallery_upload_url() ) ) );
		exit;
	}

	$nonce = isset( $_POST['rp_gallery_photo_upload_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['rp_gallery_photo_upload_nonce'] ) ) : '';
	if ( ! wp_verify_nonce( $nonce, 'rp_gallery_photo_upload' ) ) {
		wp_die( esc_html__( 'Security check failed.', 'rp-resource-hub' ) );
	}

	foreach ( array( 'rp_gallery_consent_use', 'rp_gallery_consent_people', 'rp_gallery_consent_sensitive', 'rp_gallery_consent_rights' ) as $consent_field ) {
		if ( empty( $_POST[ $consent_field ] ) || '1' !== $_POST[ $consent_field ] ) {
			rp_gallery_store_notice_and_redirect( 'error', __( 'All consent confirmations are required.', 'rp-resource-hub' ) );
		}
	}

	$files = rp_gallery_normalize_uploaded_files( isset( $_FILES['rp_gallery_photo'] ) ? $_FILES['rp_gallery_photo'] : array() );
	if ( empty( $files ) ) {
		rp_gallery_store_notice_and_redirect( 'error', __( 'At least one photo file is required.', 'rp-resource-hub' ) );
	}
	if ( count( $files ) > RP_GALLERY_MAX_BULK_FILES ) {
		rp_gallery_store_notice_and_redirect( 'error', sprintf( __( 'Please upload no more than %d photos at a time.', 'rp-resource-hub' ), absint( RP_GALLERY_MAX_BULK_FILES ) ) );
	}

	$file_types = array();
	foreach ( $files as $index => $file ) {
		if ( ! empty( $file['error'] ) && UPLOAD_ERR_OK !== (int) $file['error'] ) {
			rp_gallery_store_notice_and_redirect( 'error', sprintf( __( 'Upload failed for %s.', 'rp-resource-hub' ), sanitize_file_name( $file['name'] ) ) );
		}

		$file_type = rp_gallery_allowed_file( $file );
		if ( is_wp_error( $file_type ) ) {
			rp_gallery_store_notice_and_redirect( 'error', sprintf( '%s: %s', sanitize_file_name( $file['name'] ), $file_type->get_error_message() ) );
		}
		$file_types[ $index ] = $file_type;
	}

	$location       = isset( $_POST['rp_gallery_location'] ) ? sanitize_text_field( wp_unslash( $_POST['rp_gallery_location'] ) ) : '';
	$photo_date     = isset( $_POST['rp_gallery_photo_date'] ) ? sanitize_text_field( wp_unslash( $_POST['rp_gallery_photo_date'] ) ) : '';
	$caption        = isset( $_POST['rp_gallery_caption'] ) ? sanitize_textarea_field( wp_unslash( $_POST['rp_gallery_caption'] ) ) : '';
	$project        = isset( $_POST['rp_gallery_project'] ) ? sanitize_text_field( wp_unslash( $_POST['rp_gallery_project'] ) ) : '';
	$project_other  = isset( $_POST['rp_gallery_project_other'] ) ? sanitize_text_field( wp_unslash( $_POST['rp_gallery_project_other'] ) ) : '';
	$photographer   = isset( $_POST['rp_gallery_photographer'] ) ? sanitize_text_field( wp_unslash( $_POST['rp_gallery_photographer'] ) ) : '';
	$reviewer_notes = isset( $_POST['rp_gallery_notes'] ) ? sanitize_textarea_field( wp_unslash( $_POST['rp_gallery_notes'] ) ) : '';
	$project_value  = 'Other' === $project && $project_other ? $project_other : $project;

	if ( empty( $location ) || empty( $photo_date ) || empty( $caption ) || empty( $project_value ) ) {
		rp_gallery_store_notice_and_redirect( 'error', __( 'Photos, location, date, caption, and project/program are required.', 'rp-resource-hub' ) );
	}

	$user          = wp_get_current_user();
	$submitted_at  = current_time( 'mysql' );
	$title         = wp_trim_words( $caption, 10, '' );
	$title         = $title ? $title : __( 'Gallery Photo Submission', 'rp-resource-hub' );
	$tag_ids = isset( $_POST['rp_gallery_tags'] ) ? array_map( 'absint', (array) $_POST['rp_gallery_tags'] ) : array();
	$tag_ids = array_filter( $tag_ids );
	$tag_names = rp_gallery_selected_tag_names();
	$success_count = 0;
	$errors = array();

	foreach ( $files as $index => $file ) {
		$file_title = $title;
		if ( count( $files ) > 1 ) {
			$file_title = sprintf( '%s %d', $title, $index + 1 );
		}

		$post_id = wp_insert_post(
			array(
				'post_type'    => 'rp_gallery_photo',
				'post_title'   => $file_title,
				'post_content' => $caption,
				'post_status'  => 'pending',
				'post_author'  => get_current_user_id(),
			),
			true
		);

		if ( is_wp_error( $post_id ) ) {
			$errors[] = sanitize_file_name( $file['name'] ) . ': ' . $post_id->get_error_message();
			continue;
		}

		$submission_id = 'RP-' . gmdate( 'Ymd' ) . '-' . str_pad( (string) $post_id, 6, '0', STR_PAD_LEFT );
		$extension     = strtolower( $file_types[ $index ]['ext'] );
		$base_name     = sanitize_file_name( $submission_id . '-original.' . $extension );

		$sharepoint = rp_gallery_sharepoint_upload( $file['tmp_name'], $base_name, $submission_id, array(
			'SubmittedBy'     => $user->display_name ? $user->display_name : $user->user_login,
			'WebsiteUserID'   => (string) get_current_user_id(),
			'PhotoDate'       => $photo_date,
			'Location'        => $location,
			'Caption'         => $caption,
			'ProjectProgram'  => $project_value,
			'Tags'            => $tag_names,
		) );

		if ( is_wp_error( $sharepoint ) ) {
			wp_delete_post( $post_id, true );
			$errors[] = sanitize_file_name( $file['name'] ) . ': ' . $sharepoint->get_error_message();
			continue;
		}

		$attachment_id = rp_gallery_create_public_image( $file['tmp_name'], $post_id, $submission_id, $extension );
		if ( is_wp_error( $attachment_id ) ) {
			wp_delete_post( $post_id, true );
			$errors[] = sanitize_file_name( $file['name'] ) . ': ' . $attachment_id->get_error_message();
			continue;
		}

		set_post_thumbnail( $post_id, $attachment_id );

		update_post_meta( $post_id, '_rp_gallery_submission_id', $submission_id );
		update_post_meta( $post_id, '_rp_gallery_location', $location );
		update_post_meta( $post_id, '_rp_gallery_photo_date', $photo_date );
		update_post_meta( $post_id, '_rp_gallery_project_program', $project_value );
		update_post_meta( $post_id, '_rp_gallery_photographer', $photographer );
		update_post_meta( $post_id, '_rp_gallery_reviewer_notes', $reviewer_notes );
		update_post_meta( $post_id, '_rp_gallery_consent_timestamp', $submitted_at );
		update_post_meta( $post_id, '_rp_gallery_consent_version', '2026-06-18' );
		update_post_meta( $post_id, '_rp_gallery_original_filename', sanitize_file_name( $file['name'] ) );
		update_post_meta( $post_id, '_rp_gallery_sharepoint_item_id', isset( $sharepoint['id'] ) ? $sharepoint['id'] : '' );
		update_post_meta( $post_id, '_rp_gallery_sharepoint_web_url', isset( $sharepoint['webUrl'] ) ? esc_url_raw( $sharepoint['webUrl'] ) : '' );

		if ( $tag_ids ) {
			wp_set_object_terms( $post_id, $tag_ids, 'rp_gallery_tag' );
		}

		$success_count++;
	}

	if ( $success_count && empty( $errors ) ) {
		rp_gallery_store_notice_and_redirect( 'success', sprintf( _n( '%d photo submitted for review. Thank you.', '%d photos submitted for review. Thank you.', $success_count, 'rp-resource-hub' ), $success_count ) );
	}

	if ( $success_count ) {
		rp_gallery_store_notice_and_redirect( 'error', sprintf( __( '%1$d photo(s) were submitted, but %2$d failed: %3$s', 'rp-resource-hub' ), $success_count, count( $errors ), implode( '; ', array_slice( $errors, 0, 3 ) ) ) );
	}

	rp_gallery_store_notice_and_redirect( 'error', sprintf( __( 'No photos were submitted. %s', 'rp-resource-hub' ), implode( '; ', array_slice( $errors, 0, 3 ) ) ) );
}
add_action( 'admin_post_rp_gallery_photo_upload', 'rp_gallery_handle_upload' );

function rp_gallery_selected_tag_names() {
	$tag_ids = isset( $_POST['rp_gallery_tags'] ) ? array_map( 'absint', (array) $_POST['rp_gallery_tags'] ) : array();
	$names = array();
	foreach ( array_filter( $tag_ids ) as $tag_id ) {
		$term = get_term( $tag_id, 'rp_gallery_tag' );
		if ( $term && ! is_wp_error( $term ) ) {
			$names[] = $term->name;
		}
	}
	return implode( ', ', $names );
}

function rp_gallery_sharepoint_upload( $file_path, $file_name, $submission_id, $metadata ) {
	if ( ! RP_GALLERY_SP_TENANT_ID || ! RP_GALLERY_SP_CLIENT_ID || ! RP_GALLERY_SP_CLIENT_SECRET || ! RP_GALLERY_SP_DRIVE_ID ) {
		return new WP_Error( 'sharepoint_not_configured', __( 'SharePoint upload is not configured yet.', 'rp-resource-hub' ) );
	}

	$token = rp_gallery_graph_token();
	if ( is_wp_error( $token ) ) {
		return $token;
	}

	$year        = gmdate( 'Y' );
	$month       = gmdate( 'Y-m' );
	$folder_path = ltrim( trim( RP_GALLERY_SP_UPLOAD_ROOT, '/' ) . '/' . $year . '/' . $month . '/' . $submission_id, '/' );
	$folder      = rp_gallery_sharepoint_ensure_folder_path( $token, $folder_path );
	if ( is_wp_error( $folder ) ) {
		return $folder;
	}

	$remote_path = $folder_path . '/' . $file_name;
	$url         = 'https://graph.microsoft.com/v1.0/drives/' . rawurlencode( RP_GALLERY_SP_DRIVE_ID ) . '/root:/' . str_replace( '%2F', '/', rawurlencode( $remote_path ) ) . ':/content';

	$response = wp_remote_request(
		$url,
		array(
			'method'  => 'PUT',
			'timeout' => 60,
			'headers' => array(
				'Authorization' => 'Bearer ' . $token,
				'Content-Type'  => 'application/octet-stream',
			),
			'body'    => file_get_contents( $file_path ),
		)
	);

	if ( is_wp_error( $response ) ) {
		return $response;
	}

	$code = wp_remote_retrieve_response_code( $response );
	$body = json_decode( wp_remote_retrieve_body( $response ), true );
	if ( $code < 200 || $code > 299 || empty( $body['id'] ) ) {
		return new WP_Error( 'sharepoint_upload_failed', rp_gallery_sharepoint_error_message( $response, __( 'Original photo could not be uploaded to SharePoint.', 'rp-resource-hub' ) ) );
	}

	rp_gallery_sharepoint_update_metadata( $token, $body['id'], $submission_id, $metadata );

	return $body;
}

function rp_gallery_sharepoint_ensure_folder_path( $token, $folder_path ) {
	$segments = array_filter( explode( '/', trim( $folder_path, '/' ) ) );
	$current = '';

	foreach ( $segments as $segment ) {
		$parent_path = $current;
		$current     = $current ? $current . '/' . $segment : $segment;
		$get_url     = 'https://graph.microsoft.com/v1.0/drives/' . rawurlencode( RP_GALLERY_SP_DRIVE_ID ) . '/root:/' . str_replace( '%2F', '/', rawurlencode( $current ) );
		$get         = wp_remote_get(
			$get_url,
			array(
				'timeout' => 30,
				'headers' => array(
					'Authorization' => 'Bearer ' . $token,
				),
			)
		);

		if ( ! is_wp_error( $get ) && 200 === wp_remote_retrieve_response_code( $get ) ) {
			continue;
		}

		$parent_url = $parent_path
			? 'https://graph.microsoft.com/v1.0/drives/' . rawurlencode( RP_GALLERY_SP_DRIVE_ID ) . '/root:/' . str_replace( '%2F', '/', rawurlencode( $parent_path ) ) . ':/children'
			: 'https://graph.microsoft.com/v1.0/drives/' . rawurlencode( RP_GALLERY_SP_DRIVE_ID ) . '/root/children';

		$create = wp_remote_post(
			$parent_url,
			array(
				'timeout' => 30,
				'headers' => array(
					'Authorization' => 'Bearer ' . $token,
					'Content-Type'  => 'application/json',
				),
				'body'    => wp_json_encode(
					array(
						'name'                                => $segment,
						'folder'                              => new stdClass(),
						'@microsoft.graph.conflictBehavior'   => 'replace',
					)
				),
			)
		);

		if ( is_wp_error( $create ) ) {
			return $create;
		}

		$code = wp_remote_retrieve_response_code( $create );
		if ( $code < 200 || $code > 299 ) {
			return new WP_Error( 'sharepoint_folder_failed', rp_gallery_sharepoint_error_message( $create, __( 'SharePoint upload folder could not be created.', 'rp-resource-hub' ) ) );
		}
	}

	return true;
}

function rp_gallery_sharepoint_error_message( $response, $fallback ) {
	$code = wp_remote_retrieve_response_code( $response );
	$body = json_decode( wp_remote_retrieve_body( $response ), true );

	if ( ! empty( $body['error']['message'] ) ) {
		return sprintf( '%s Graph returned %d: %s', $fallback, $code, sanitize_text_field( $body['error']['message'] ) );
	}

	return sprintf( '%s Graph returned HTTP %d.', $fallback, $code );
}

function rp_gallery_graph_token() {
	$response = wp_remote_post(
		'https://login.microsoftonline.com/' . rawurlencode( RP_GALLERY_SP_TENANT_ID ) . '/oauth2/v2.0/token',
		array(
			'timeout' => 30,
			'body'    => array(
				'client_id'     => RP_GALLERY_SP_CLIENT_ID,
				'client_secret' => RP_GALLERY_SP_CLIENT_SECRET,
				'scope'         => 'https://graph.microsoft.com/.default',
				'grant_type'    => 'client_credentials',
			),
		)
	);

	if ( is_wp_error( $response ) ) {
		return $response;
	}

	$body = json_decode( wp_remote_retrieve_body( $response ), true );
	if ( empty( $body['access_token'] ) ) {
		return new WP_Error( 'sharepoint_token_failed', __( 'Could not authenticate with SharePoint.', 'rp-resource-hub' ) );
	}

	return $body['access_token'];
}

function rp_gallery_sharepoint_update_metadata( $token, $item_id, $submission_id, $metadata ) {
	$fields = apply_filters(
		'rp_gallery_sharepoint_fields',
		array(
			'Title'              => $submission_id,
			'SubmissionID'       => $submission_id,
			'SubmittedBy'        => $metadata['SubmittedBy'],
			'WebsiteUserID'      => $metadata['WebsiteUserID'],
			'PhotoDate'          => $metadata['PhotoDate'],
			'Location'           => $metadata['Location'],
			'Caption'            => $metadata['Caption'],
			'ProjectProgram'     => $metadata['ProjectProgram'],
			'Tags'               => $metadata['Tags'],
		),
		$metadata,
		$submission_id
	);

	$url = 'https://graph.microsoft.com/v1.0/drives/' . rawurlencode( RP_GALLERY_SP_DRIVE_ID ) . '/items/' . rawurlencode( $item_id ) . '/listItem/fields';
	wp_remote_request(
		$url,
		array(
			'method'  => 'PATCH',
			'timeout' => 30,
			'headers' => array(
				'Authorization' => 'Bearer ' . $token,
				'Content-Type'  => 'application/json',
			),
			'body'    => wp_json_encode( $fields ),
		)
	);
}

function rp_gallery_create_public_image( $source_path, $post_id, $submission_id, $extension ) {
	require_once ABSPATH . 'wp-admin/includes/image.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';

	$upload_dir = wp_upload_dir();
	$target_dir = trailingslashit( $upload_dir['basedir'] ) . 'rp-gallery';
	if ( ! wp_mkdir_p( $target_dir ) ) {
		return new WP_Error( 'gallery_dir_failed', __( 'Could not create gallery upload directory.', 'rp-resource-hub' ) );
	}

	$target_path = trailingslashit( $target_dir ) . sanitize_file_name( $submission_id . '-public.jpg' );
	$editor = wp_get_image_editor( $source_path );
	if ( is_wp_error( $editor ) ) {
		return $editor;
	}

	$resized = $editor->resize( 1800, 1800, false );
	if ( is_wp_error( $resized ) ) {
		return $resized;
	}
	$editor->set_quality( 82 );
	$saved = $editor->save( $target_path, 'image/jpeg' );
	if ( is_wp_error( $saved ) ) {
		return $saved;
	}

	$logo_path = get_stylesheet_directory() . '/assets/images/accord-logo.png';
	if ( file_exists( $logo_path ) ) {
		rp_gallery_apply_watermark( $target_path, $logo_path );
	}

	$attachment_id = wp_insert_attachment(
		array(
			'post_mime_type' => 'image/jpeg',
			'post_title'     => $submission_id . ' public gallery image',
			'post_status'    => 'inherit',
		),
		$target_path,
		$post_id
	);

	if ( is_wp_error( $attachment_id ) ) {
		return $attachment_id;
	}

	$metadata = wp_generate_attachment_metadata( $attachment_id, $target_path );
	wp_update_attachment_metadata( $attachment_id, $metadata );

	return $attachment_id;
}

function rp_gallery_apply_watermark( $image_path, $logo_path ) {
	if ( ! function_exists( 'imagecreatefrompng' ) || ! function_exists( 'imagecopyresampled' ) ) {
		return;
	}

	$main = imagecreatefromstring( file_get_contents( $image_path ) );
	$logo = imagecreatefrompng( $logo_path );
	if ( ! $main || ! $logo ) {
		return;
	}

	$image_width = imagesx( $main );
	imagealphablending( $main, true );
	imagesavealpha( $main, true );

	$logo_width = imagesx( $logo );
	$logo_height = imagesy( $logo );
	$target_width = max( 120, (int) round( $image_width * 0.16 ) );
	$target_height = (int) round( $logo_height * ( $target_width / $logo_width ) );
	$scaled_logo = imagecreatetruecolor( $target_width, $target_height );
	imagealphablending( $scaled_logo, false );
	imagesavealpha( $scaled_logo, true );
	imagecopyresampled( $scaled_logo, $logo, 0, 0, 0, 0, $target_width, $target_height, $logo_width, $logo_height );

	$padding = max( 24, (int) round( $image_width * 0.025 ) );
	imagecopy( $main, $scaled_logo, $image_width - $target_width - $padding, $padding, 0, 0, $target_width, $target_height );
	imagejpeg( $main, $image_path, 82 );

	imagedestroy( $main );
	imagedestroy( $logo );
	imagedestroy( $scaled_logo );
}

function rp_gallery_shortcode_gallery( $atts ) {
	$atts = shortcode_atts( array( 'limit' => 12 ), $atts, 'rp_photo_gallery' );
	$limit = max( 1, min( 48, absint( $atts['limit'] ) ) );
	$tag = isset( $_GET['rp_gallery_tag'] ) ? sanitize_text_field( wp_unslash( $_GET['rp_gallery_tag'] ) ) : '';
	$project = isset( $_GET['rp_gallery_project'] ) ? sanitize_text_field( wp_unslash( $_GET['rp_gallery_project'] ) ) : '';

	$args = array(
		'post_type'      => 'rp_gallery_photo',
		'post_status'    => 'publish',
		'posts_per_page' => $limit,
	);

	if ( $tag ) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'rp_gallery_tag',
				'field'    => 'slug',
				'terms'    => $tag,
			),
		);
	}
	if ( $project ) {
		$args['meta_query'] = array(
			array(
				'key'   => '_rp_gallery_project_program',
				'value' => $project,
			),
		);
	}

	$query = new WP_Query( $args );
	$tags = get_terms( array( 'taxonomy' => 'rp_gallery_tag', 'hide_empty' => true, 'orderby' => 'name' ) );

	ob_start();
	?>
	<div class="rp-gallery">
		<form class="rp-gallery-filters" method="get">
			<select name="rp_gallery_tag">
				<option value=""><?php esc_html_e( 'All tags', 'rp-resource-hub' ); ?></option>
				<?php foreach ( $tags as $term ) : ?>
					<option value="<?php echo esc_attr( $term->slug ); ?>" <?php selected( $tag, $term->slug ); ?>><?php echo esc_html( $term->name ); ?></option>
				<?php endforeach; ?>
			</select>
			<select name="rp_gallery_project">
				<option value=""><?php esc_html_e( 'All projects/programs', 'rp-resource-hub' ); ?></option>
				<?php foreach ( rp_gallery_project_options() as $option ) : ?>
					<option value="<?php echo esc_attr( $option ); ?>" <?php selected( $project, $option ); ?>><?php echo esc_html( $option ); ?></option>
				<?php endforeach; ?>
			</select>
			<button class="rp-button" type="submit"><?php esc_html_e( 'Filter', 'rp-resource-hub' ); ?></button>
		</form>

		<div class="rp-gallery-grid">
			<?php if ( $query->have_posts() ) : ?>
				<?php while ( $query->have_posts() ) : $query->the_post(); ?>
					<article class="rp-gallery-card">
						<a href="<?php the_permalink(); ?>">
							<?php the_post_thumbnail( 'large' ); ?>
						</a>
						<div class="rp-gallery-card-body">
							<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							<p><?php echo esc_html( get_post_meta( get_the_ID(), '_rp_gallery_location', true ) ); ?> <?php echo esc_html( get_post_meta( get_the_ID(), '_rp_gallery_photo_date', true ) ); ?></p>
						</div>
					</article>
				<?php endwhile; wp_reset_postdata(); ?>
			<?php else : ?>
				<p class="rp-no-results"><?php esc_html_e( 'No gallery photos found.', 'rp-resource-hub' ); ?></p>
			<?php endif; ?>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'rp_photo_gallery', 'rp_gallery_shortcode_gallery' );

function rp_gallery_add_meta_boxes() {
	add_meta_box(
		'rp_gallery_submission_details',
		__( 'Photo Submission Details', 'rp-resource-hub' ),
		'rp_gallery_render_submission_meta_box',
		'rp_gallery_photo',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'rp_gallery_add_meta_boxes' );

function rp_gallery_render_submission_meta_box( $post ) {
	$fields = array(
		__( 'Submission ID', 'rp-resource-hub' )       => get_post_meta( $post->ID, '_rp_gallery_submission_id', true ),
		__( 'Location', 'rp-resource-hub' )            => get_post_meta( $post->ID, '_rp_gallery_location', true ),
		__( 'Photo Date', 'rp-resource-hub' )          => get_post_meta( $post->ID, '_rp_gallery_photo_date', true ),
		__( 'Project / Program', 'rp-resource-hub' )   => get_post_meta( $post->ID, '_rp_gallery_project_program', true ),
		__( 'Photographer / Source', 'rp-resource-hub' ) => get_post_meta( $post->ID, '_rp_gallery_photographer', true ),
		__( 'Consent Timestamp', 'rp-resource-hub' )   => get_post_meta( $post->ID, '_rp_gallery_consent_timestamp', true ),
	);
	$sharepoint_url = get_post_meta( $post->ID, '_rp_gallery_sharepoint_web_url', true );
	$notes          = get_post_meta( $post->ID, '_rp_gallery_reviewer_notes', true );
	?>
	<table class="widefat striped">
		<tbody>
			<?php foreach ( $fields as $label => $value ) : ?>
				<tr>
					<th scope="row" style="width:220px;"><?php echo esc_html( $label ); ?></th>
					<td><?php echo esc_html( $value ? $value : '-' ); ?></td>
				</tr>
			<?php endforeach; ?>
			<tr>
				<th scope="row"><?php esc_html_e( 'SharePoint Original', 'rp-resource-hub' ); ?></th>
				<td>
					<?php if ( $sharepoint_url ) : ?>
						<a href="<?php echo esc_url( $sharepoint_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Open original in SharePoint', 'rp-resource-hub' ); ?></a>
					<?php else : ?>
						<?php esc_html_e( 'No SharePoint link saved.', 'rp-resource-hub' ); ?>
					<?php endif; ?>
				</td>
			</tr>
			<?php if ( $notes ) : ?>
				<tr>
					<th scope="row"><?php esc_html_e( 'Reviewer Notes', 'rp-resource-hub' ); ?></th>
					<td><?php echo esc_html( $notes ); ?></td>
				</tr>
			<?php endif; ?>
		</tbody>
	</table>
	<?php
}

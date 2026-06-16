<?php
/**
 * Template Name: User Management
 *
 * A front-end admin page where administrators can view all registered
 * users and change their roles without going to WP Admin.
 *
 * @package ResilientHub
 */

// Gate check: administrators only
if ( ! is_user_logged_in() ) {
	wp_safe_redirect( add_query_arg( 'redirect_to', esc_url( home_url( '/user-management/' ) ), home_url( '/portal-entry/' ) ) );
	exit;
}

if ( ! current_user_can( 'manage_options' ) ) {
	wp_safe_redirect( home_url( '/' ) );
	exit;
}

// Available roles for the dropdown
$editable_roles = array(
	'administrator'        => __( 'Administrator', 'resilient-hub' ),
	'editor'               => __( 'Editor', 'resilient-hub' ),
	'partner_contributor'  => __( 'Partner Contributor', 'resilient-hub' ),
	'hub_subscriber'       => __( 'Hub Subscriber', 'resilient-hub' ),
	'subscriber'           => __( 'Subscriber', 'resilient-hub' ),
);

// Filter parameters
$filter_role   = isset( $_GET['rp_role'] ) ? sanitize_key( $_GET['rp_role'] ) : '';
$search_query  = isset( $_GET['rp_search'] ) ? sanitize_text_field( wp_unslash( $_GET['rp_search'] ) ) : '';

// Build user query
$user_args = array(
	'orderby' => 'registered',
	'order'   => 'DESC',
	'number'  => 50,
);

if ( $filter_role && array_key_exists( $filter_role, $editable_roles ) ) {
	$user_args['role'] = $filter_role;
}

if ( $search_query ) {
	$user_args['search']         = '*' . $search_query . '*';
	$user_args['search_columns'] = array( 'user_login', 'user_email', 'display_name', 'user_nicename' );
}

$user_query = new WP_User_Query( $user_args );
$users      = $user_query->get_results();
$total      = $user_query->get_total();

get_header();
?>

<main id="primary" class="rp-moderation-dashboard-main">
	<section class="rp-dashboard-hero">
		<div class="rp-page-shell">
			<p class="rp-eyebrow"><?php esc_html_e( 'Admin Panel', 'resilient-hub' ); ?></p>
			<h1 class="rp-page-title"><?php esc_html_e( 'User Management', 'resilient-hub' ); ?></h1>
		</div>
	</section>
	<div class="rp-dashboard-body">
	<div class="rp-page-shell">
		<header class="rp-dashboard-header">
			<p class="rp-dashboard-subtitle"><?php esc_html_e( 'View all registered users, search by name or email, and assign roles.', 'resilient-hub' ); ?></p>
		</header>

		<div class="rp-moderation-container">
			<!-- Filter controls -->
			<form class="rp-user-mgmt-controls" method="get">
				<div class="rp-field">
					<label for="rp_search"><?php esc_html_e( 'Search Users', 'resilient-hub' ); ?></label>
					<input type="search" id="rp_search" name="rp_search" value="<?php echo esc_attr( $search_query ); ?>" placeholder="<?php esc_attr_e( 'Name, email, or username...', 'resilient-hub' ); ?>">
				</div>
				<div class="rp-field">
					<label for="rp_role"><?php esc_html_e( 'Filter by Role', 'resilient-hub' ); ?></label>
					<select id="rp_role" name="rp_role">
						<option value=""><?php esc_html_e( 'All Roles', 'resilient-hub' ); ?></option>
						<?php foreach ( $editable_roles as $role_key => $role_label ) : ?>
							<option value="<?php echo esc_attr( $role_key ); ?>" <?php selected( $filter_role, $role_key ); ?>><?php echo esc_html( $role_label ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<button class="rp-button" type="submit" style="min-height:44px; align-self: flex-end;"><?php esc_html_e( 'Filter', 'resilient-hub' ); ?></button>
			</form>

			<p class="rp-user-count">
				<?php
				printf(
					/* translators: %d: number of users */
					esc_html( _n( '%d user found', '%d users found', $total, 'resilient-hub' ) ),
					absint( $total )
				);
				?>
			</p>

			<?php if ( ! empty( $users ) ) : ?>
				<div class="rp-table-responsive">
					<table class="rp-user-table">
						<thead>
							<tr>
								<th><?php esc_html_e( 'User', 'resilient-hub' ); ?></th>
								<th><?php esc_html_e( 'Email', 'resilient-hub' ); ?></th>
								<th><?php esc_html_e( 'Current Role', 'resilient-hub' ); ?></th>
								<th><?php esc_html_e( 'Registered', 'resilient-hub' ); ?></th>
								<th><?php esc_html_e( 'Change Role', 'resilient-hub' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $users as $user ) :
								$user_roles  = $user->roles;
								$primary_role = ! empty( $user_roles ) ? $user_roles[0] : 'subscriber';
								$role_label   = isset( $editable_roles[ $primary_role ] ) ? $editable_roles[ $primary_role ] : ucwords( str_replace( '_', ' ', $primary_role ) );
								$is_self      = ( get_current_user_id() === $user->ID );
								?>
								<tr id="rp-user-row-<?php echo absint( $user->ID ); ?>">
									<td>
										<?php 
										$deletion_requested = get_user_meta( $user->ID, '_rp_deletion_requested', true ); 
										?>
										<div class="rp-user-name-cell">
											<?php echo get_avatar( $user->ID, 36, '', '', array( 'class' => 'rp-user-avatar' ) ); ?>
											<div>
												<span class="rp-user-display-name"><?php echo esc_html( $user->display_name ); ?></span>
												<span class="rp-user-login-name">@<?php echo esc_html( $user->user_login ); ?></span>
												<?php if ( $deletion_requested ) : ?>
													<span class="rp-role-badge" style="background: #fee2e2; color: #b91c1c; font-size: 10px; margin-top: 4px; display: inline-block; font-weight: 700; border-radius: 4px; padding: 2px 6px;"><?php esc_html_e( 'DELETION REQUESTED', 'resilient-hub' ); ?></span>
												<?php endif; ?>
											</div>
										</div>
									</td>
									<td><?php echo esc_html( $user->user_email ); ?></td>
									<td>
										<span class="rp-role-badge rp-role-badge-<?php echo esc_attr( $primary_role ); ?>"><?php echo esc_html( $role_label ); ?></span>
									</td>
									<td><?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $user->user_registered ) ) ); ?></td>
									<td>
										<?php if ( $is_self ) : ?>
											<span class="rp-user-login-name"><?php esc_html_e( 'Cannot change own role', 'resilient-hub' ); ?></span>
										<?php else : ?>
											<div class="rp-role-update-cell">
												<select class="rp-role-select" data-user-id="<?php echo absint( $user->ID ); ?>">
													<?php foreach ( $editable_roles as $role_key => $rl ) : ?>
														<option value="<?php echo esc_attr( $role_key ); ?>" <?php selected( $primary_role, $role_key ); ?>><?php echo esc_html( $rl ); ?></option>
													<?php endforeach; ?>
												</select>
												<button class="rp-btn-update-role" data-user-id="<?php echo absint( $user->ID ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'rp_update_role_' . $user->ID ) ); ?>">
													<?php esc_html_e( 'Update', 'resilient-hub' ); ?>
												</button>
												<button class="rp-btn-delete-user" data-user-id="<?php echo absint( $user->ID ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'rp_delete_user_' . $user->ID ) ); ?>" style="background: #dc2626; border-color: #dc2626; color: #fff; margin-left: 8px;">
													<?php esc_html_e( 'Delete', 'resilient-hub' ); ?>
												</button>
											</div>
										<?php endif; ?>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			<?php else : ?>
				<div class="rp-moderation-empty">
					<p><?php esc_html_e( 'No users found matching your search.', 'resilient-hub' ); ?></p>
				</div>
			<?php endif; ?>
		</div>
	</div>
	</div>
</main>

<script>
(function() {
	document.querySelectorAll('.rp-btn-update-role').forEach(function(btn) {
		btn.addEventListener('click', function(e) {
			e.preventDefault();
			var button  = this;
			var userId  = button.getAttribute('data-user-id');
			var nonce   = button.getAttribute('data-nonce');
			var select  = button.parentElement.querySelector('.rp-role-select');
			var newRole = select.value;

			button.disabled = true;
			button.textContent = '<?php echo esc_js( __( 'Updating...', 'resilient-hub' ) ); ?>';

			// Remove any previous status messages
			var prev = button.parentElement.querySelector('.rp-role-status');
			if (prev) prev.remove();

			var formData = new FormData();
			formData.append('action', 'rp_update_user_role');
			formData.append('user_id', userId);
			formData.append('new_role', newRole);
			formData.append('nonce', nonce);

			fetch('<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', {
				method: 'POST',
				credentials: 'same-origin',
				body: formData
			})
			.then(function(r) { return r.json(); })
			.then(function(data) {
				button.disabled = false;
				button.textContent = '<?php echo esc_js( __( 'Update', 'resilient-hub' ) ); ?>';

				var status = document.createElement('span');
				status.className = 'rp-role-status ' + (data.success ? 'rp-role-status-success' : 'rp-role-status-error');
				status.textContent = data.data && data.data.message ? data.data.message : (data.success ? '<?php echo esc_js( __( 'Updated', 'resilient-hub' ) ); ?>' : '<?php echo esc_js( __( 'Error', 'resilient-hub' ) ); ?>');
				button.parentElement.appendChild(status);

				// Update the badge if successful
				if (data.success) {
					var row  = document.getElementById('rp-user-row-' + userId);
					var badge = row.querySelector('.rp-role-badge');
					if (badge) {
						badge.className = 'rp-role-badge rp-role-badge-' + newRole;
						badge.textContent = select.options[select.selectedIndex].text;
					}
					setTimeout(function() { status.remove(); }, 3000);
				}
			})
			.catch(function() {
				button.disabled = false;
				button.textContent = '<?php echo esc_js( __( 'Update', 'resilient-hub' ) ); ?>';
			});
		});
	});

	// Handle Admin Deleting User
	document.querySelectorAll('.rp-btn-delete-user').forEach(function(btn) {
		btn.addEventListener('click', function(e) {
			e.preventDefault();
			var button = this;
			var userId = button.getAttribute('data-user-id');
			var nonce = button.getAttribute('data-nonce');
			var row = document.getElementById('rp-user-row-' + userId);

			if (!confirm('<?php echo esc_js( __( 'Are you sure you want to permanently delete this user? All their submitted resources will be reassigned to you.', 'resilient-hub' ) ); ?>')) {
				return;
			}

			button.disabled = true;
			var originalText = button.textContent;
			button.textContent = '<?php echo esc_js( __( 'Deleting...', 'resilient-hub' ) ); ?>';

			var formData = new FormData();
			formData.append('action', 'rp_delete_user');
			formData.append('user_id', userId);
			formData.append('nonce', nonce);

			fetch('<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', {
				method: 'POST',
				credentials: 'same-origin',
				body: formData
			})
			.then(function(r) { return r.json(); })
			.then(function(data) {
				if (data.success) {
					row.style.opacity = '0.5';
					row.style.background = '#fee2e2';
					row.style.transition = 'all 0.5s ease';
					setTimeout(function() {
						row.remove();
					}, 800);
				} else {
					alert(data.data.message || 'Error occurred.');
					button.disabled = false;
					button.textContent = originalText;
				}
			})
			.catch(function() {
				button.disabled = false;
				button.textContent = originalText;
			});
		});
	});
})();
</script>

<?php
get_footer();

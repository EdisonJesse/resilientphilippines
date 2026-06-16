<?php
/**
 * Template Name: Portal Entry (Auth)
 *
 * @package ResilientHub
 */

if ( is_user_logged_in() ) {
	$redirect = ! empty( $_REQUEST['redirect_to'] ) ? esc_url_raw( $_REQUEST['redirect_to'] ) : home_url( '/resource-hub/' );
	wp_safe_redirect( $redirect );
	exit;
}

$login_errors    = array();
$register_errors = array();
$active_tab      = 'login';

// Handle Login submission
if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST['rp_login_submit'] ) ) {
	$active_tab = 'login';
	if ( ! isset( $_POST['rp_login_nonce'] ) || ! wp_verify_nonce( $_POST['rp_login_nonce'], 'rp_login_action' ) ) {
		$login_errors[] = __( 'Security check failed. Please refresh the page and try again.', 'resilient-hub' );
	} else {
		$creds = array(
			'user_login'    => sanitize_text_field( $_POST['log'] ),
			'user_password' => $_POST['pwd'],
			'remember'      => isset( $_POST['rememberme'] ),
		);

		$user = wp_signon( $creds, is_ssl() );

		if ( is_wp_error( $user ) ) {
			$login_errors[] = $user->get_error_message();
		} else {
			$redirect = ! empty( $_REQUEST['redirect_to'] ) ? esc_url_raw( $_REQUEST['redirect_to'] ) : home_url( '/resource-hub/' );
			wp_safe_redirect( $redirect );
			exit;
		}
	}
}

// Handle Registration submission
if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST['rp_register_submit'] ) ) {
	$active_tab = 'register';
	if ( ! isset( $_POST['rp_register_nonce'] ) || ! wp_verify_nonce( $_POST['rp_register_nonce'], 'rp_register_action' ) ) {
		$register_errors[] = __( 'Security check failed. Please refresh the page and try again.', 'resilient-hub' );
	} elseif ( ! get_option( 'users_can_register' ) ) {
		$register_errors[] = __( 'User registration is currently disabled.', 'resilient-hub' );
	} else {
		$username         = sanitize_user( $_POST['user_login'] );
		$email            = sanitize_email( $_POST['user_email'] );
		$first_name       = sanitize_text_field( $_POST['first_name'] );
		$last_name        = sanitize_text_field( $_POST['last_name'] );
		$password         = $_POST['user_password'];
		$confirm_password = $_POST['confirm_password'];

		if ( ! isset( $_POST['rp_privacy_consent'] ) ) {
			$register_errors[] = __( 'You must agree to the Privacy Policy and Terms of Service to register.', 'resilient-hub' );
		}

		if ( empty( $username ) || empty( $email ) || empty( $first_name ) || empty( $last_name ) || empty( $password ) || empty( $confirm_password ) ) {
			$register_errors[] = __( 'All fields are required.', 'resilient-hub' );
		}

		if ( ! is_email( $email ) ) {
			$register_errors[] = __( 'Invalid email address.', 'resilient-hub' );
		}

		if ( username_exists( $username ) ) {
			$register_errors[] = __( 'Username is already taken.', 'resilient-hub' );
		}

		if ( email_exists( $email ) ) {
			$register_errors[] = __( 'Email address is already registered.', 'resilient-hub' );
		}

		if ( strlen( $password ) < 8 ) {
			$register_errors[] = __( 'Password must be at least 8 characters long.', 'resilient-hub' );
		}

		if ( $password !== $confirm_password ) {
			$register_errors[] = __( 'Passwords do not match.', 'resilient-hub' );
		}

		if ( empty( $register_errors ) ) {
			$user_id = wp_insert_user(
				array(
					'user_login' => $username,
					'user_email' => $email,
					'user_pass'  => $password,
					'first_name' => $first_name,
					'last_name'  => $last_name,
					'role'       => 'hub_subscriber',
				)
			);

			if ( is_wp_error( $user_id ) ) {
				$register_errors[] = $user_id->get_error_message();
			} else {
				// Auto-login after registration
				$creds = array(
					'user_login'    => $username,
					'user_password' => $password,
					'remember'      => true,
				);
				$user  = wp_signon( $creds, is_ssl() );

				$redirect = ! empty( $_REQUEST['redirect_to'] ) ? esc_url_raw( $_REQUEST['redirect_to'] ) : home_url( '/resource-hub/' );
				wp_safe_redirect( $redirect );
				exit;
			}
		}
	}
}

get_header();
?>

<main id="primary" class="rp-auth-main">
	<div class="rp-auth-container">
		<!-- Left Panel: Mangrove Photo -->
		<div class="rp-auth-left" style="background-image: url('<?php echo esc_url( rp_child_upload_url( '2020/07/A2C-Cagsao-mangroves-Jes-Aznar-6-scaled.jpg' ) ); ?>');">
			<div class="rp-auth-left-overlay"></div>
			<div class="rp-auth-left-content">
				<span class="rp-auth-eyebrow"><?php esc_html_e( 'Resilient Philippines', 'resilient-hub' ); ?></span>
				<h2 class="rp-auth-heading"><?php esc_html_e( 'Humanitarian Collaborative Hub', 'resilient-hub' ); ?></h2>
				<p class="rp-auth-description"><?php esc_html_e( 'Access scientific knowledge products, partner guidelines, anticipatory actions, and coordinate local disaster risk reduction efforts.', 'resilient-hub' ); ?></p>
				<div class="rp-auth-left-footer">
					<p>© <?php echo esc_html( date( 'Y' ) ); ?> ACCORD. All rights reserved.</p>
				</div>
			</div>
		</div>

		<!-- Right Panel: Login & Register Forms -->
		<div class="rp-auth-right">
			<div class="rp-auth-form-box">
				<!-- Tabs headers -->
				<div class="rp-auth-tabs">
					<button type="button" class="rp-auth-tab-btn<?php echo 'login' === $active_tab ? ' active' : ''; ?>" data-tab="login-tab"><?php esc_html_e( 'Sign In', 'resilient-hub' ); ?></button>
					<?php if ( get_option( 'users_can_register' ) ) : ?>
						<button type="button" class="rp-auth-tab-btn<?php echo 'register' === $active_tab ? ' active' : ''; ?>" data-tab="register-tab"><?php esc_html_e( 'Create Account', 'resilient-hub' ); ?></button>
					<?php endif; ?>
				</div>

				<!-- Login Tab Content -->
				<div id="login-tab" class="rp-auth-tab-content<?php echo 'login' === $active_tab ? ' active' : ''; ?>">
					<div class="rp-auth-intro">
						<h3><?php esc_html_e( 'Welcome Back', 'resilient-hub' ); ?></h3>
						<p><?php esc_html_e( 'Sign in to access member-only publications and submit resources.', 'resilient-hub' ); ?></p>
					</div>

					<?php if ( ! empty( $login_errors ) ) : ?>
						<div class="rp-auth-errors" role="alert">
							<?php foreach ( $login_errors as $err ) : ?>
								<p><?php echo esc_html( $err ); ?></p>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>

					<form action="" method="post" class="rp-auth-form">
						<?php wp_nonce_field( 'rp_login_action', 'rp_login_nonce' ); ?>
						<div class="rp-auth-field">
							<label for="log"><?php esc_html_e( 'Username or Email Address', 'resilient-hub' ); ?></label>
							<input type="text" name="log" id="log" class="input" value="<?php echo isset( $_POST['log'] ) ? esc_attr( $_POST['log'] ) : ''; ?>" required autocomplete="username">
						</div>
						<div class="rp-auth-field">
							<label for="pwd"><?php esc_html_e( 'Password', 'resilient-hub' ); ?></label>
							<input type="password" name="pwd" id="pwd" class="input" required autocomplete="current-password">
						</div>
						<div class="rp-auth-options">
							<label for="rememberme">
								<input name="rememberme" type="checkbox" id="rememberme" value="forever"> <?php esc_html_e( 'Remember Me', 'resilient-hub' ); ?>
							</label>
						</div>
						<button type="submit" name="rp_login_submit" class="rp-button rp-auth-submit"><?php esc_html_e( 'Sign In', 'resilient-hub' ); ?></button>
					</form>
				</div>

				<!-- Register Tab Content -->
				<?php if ( get_option( 'users_can_register' ) ) : ?>
					<div id="register-tab" class="rp-auth-tab-content<?php echo 'register' === $active_tab ? ' active' : ''; ?>">
						<div class="rp-auth-intro">
							<h3><?php esc_html_e( 'Join the Collaborative Hub', 'resilient-hub' ); ?></h3>
							<p><?php esc_html_e( 'Register to access member-only materials and contribute knowledge.', 'resilient-hub' ); ?></p>
						</div>

						<?php if ( ! empty( $register_errors ) ) : ?>
							<div class="rp-auth-errors" role="alert">
								<?php foreach ( $register_errors as $err ) : ?>
									<p><?php echo esc_html( $err ); ?></p>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>

						<form action="" method="post" class="rp-auth-form">
							<?php wp_nonce_field( 'rp_register_action', 'rp_register_nonce' ); ?>
							<div class="rp-auth-row">
								<div class="rp-auth-field">
									<label for="first_name"><?php esc_html_e( 'First Name', 'resilient-hub' ); ?></label>
									<input type="text" name="first_name" id="first_name" class="input" value="<?php echo isset( $_POST['first_name'] ) ? esc_attr( $_POST['first_name'] ) : ''; ?>" required>
								</div>
								<div class="rp-auth-field">
									<label for="last_name"><?php esc_html_e( 'Last Name', 'resilient-hub' ); ?></label>
									<input type="text" name="last_name" id="last_name" class="input" value="<?php echo isset( $_POST['last_name'] ) ? esc_attr( $_POST['last_name'] ) : ''; ?>" required>
								</div>
							</div>
							<div class="rp-auth-field">
								<label for="user_login"><?php esc_html_e( 'Username', 'resilient-hub' ); ?></label>
								<input type="text" name="user_login" id="user_login" class="input" value="<?php echo isset( $_POST['user_login'] ) ? esc_attr( $_POST['user_login'] ) : ''; ?>" required autocomplete="username">
							</div>
							<div class="rp-auth-field">
								<label for="user_email"><?php esc_html_e( 'Email Address', 'resilient-hub' ); ?></label>
								<input type="email" name="user_email" id="user_email" class="input" value="<?php echo isset( $_POST['user_email'] ) ? esc_attr( $_POST['user_email'] ) : ''; ?>" required autocomplete="email">
							</div>
							<div class="rp-auth-row">
								<div class="rp-auth-field">
									<label for="user_password"><?php esc_html_e( 'Password', 'resilient-hub' ); ?></label>
									<input type="password" name="user_password" id="user_password" class="input" required autocomplete="new-password">
								</div>
								<div class="rp-auth-field">
									<label for="confirm_password"><?php esc_html_e( 'Confirm Password', 'resilient-hub' ); ?></label>
									<input type="password" name="confirm_password" id="confirm_password" class="input" required autocomplete="new-password">
								</div>
							</div>
							
							<div class="rp-auth-consent" style="margin-top: 16px; margin-bottom: 20px; font-size: 13px; color: #4b5563; display: flex; align-items: flex-start; gap: 8px;">
								<input type="checkbox" name="rp_privacy_consent" id="rp_privacy_consent" style="margin-top: 3px;" required>
								<label for="rp_privacy_consent">
									<?php printf(
										/* translators: 1: Privacy Policy URL, 2: Terms of Service URL */
										__( 'I agree to the %1$s and %2$s. I consent to my registration details being processed in accordance with these policies.', 'resilient-hub' ),
										'<a href="' . esc_url( home_url( '/privacy-policy/' ) ) . '" target="_blank">' . __( 'Privacy Policy', 'resilient-hub' ) . '</a>',
										'<a href="' . esc_url( home_url( '/terms-of-service/' ) ) . '" target="_blank">' . __( 'Terms of Service', 'resilient-hub' ) . '</a>'
									); ?>
								</label>
							</div>
							
							<button type="submit" name="rp_register_submit" class="rp-button rp-auth-submit"><?php esc_html_e( 'Create Account', 'resilient-hub' ); ?></button>
						</form>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
	const tabButtons = document.querySelectorAll('.rp-auth-tab-btn');
	const tabContents = document.querySelectorAll('.rp-auth-tab-content');

	tabButtons.forEach(btn => {
		btn.addEventListener('click', function() {
			const targetTab = this.getAttribute('data-tab');

			tabButtons.forEach(b => b.classList.remove('active'));
			tabContents.forEach(c => c.classList.remove('active'));

			this.classList.add('active');
			const targetEl = document.getElementById(targetTab);
			if (targetEl) {
				targetEl.classList.add('active');
			}
		});
	});

	// Check URL parameters to switch tab initially
	const urlParams = new URLSearchParams(window.location.search);
	const action = urlParams.get('action');
	if (action === 'register') {
		const regBtn = document.querySelector('.rp-auth-tab-btn[data-tab="register-tab"]');
		if (regBtn) {
			regBtn.click();
		}
	}
});
</script>

<?php
get_footer();

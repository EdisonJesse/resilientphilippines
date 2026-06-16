<?php
/**
 * Template Name: My Profile & Privacy
 *
 * A unified front-end user portal page for logged-in subscribers to edit
 * their profile information (pronouns, affiliation), download user data
 * exports (JSON), manage consent, and request account erasure.
 *
 * @package ResilientHub
 */

if ( ! is_user_logged_in() ) {
	wp_safe_redirect( add_query_arg( 'redirect_to', esc_url( home_url( '/profile/' ) ), home_url( '/portal-entry/' ) ) );
	exit;
}

$current_user = wp_get_current_user();
$deletion_requested = get_user_meta( $current_user->ID, '_rp_deletion_requested', true );

get_header();
?>

<style>
/* Glassmorphism Self-Service Compliance Styling */
.rp-privacy-grid {
	display: grid;
	grid-template-columns: 1fr;
	gap: 24px;
	margin-bottom: 40px;
}
.rp-privacy-section-card {
	background: #ffffff;
	border: 1px solid #e5e7eb;
	border-radius: 12px;
	padding: 32px;
	box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
}
.rp-privacy-section-title {
	font-size: 20px;
	font-weight: 700;
	color: var(--rp-color-navy, #12324a);
	margin: 0 0 12px 0;
	display: flex;
	align-items: center;
	gap: 12px;
}
.rp-privacy-description {
	font-size: 14px;
	color: var(--rp-color-muted, #53666f);
	margin-bottom: 24px;
	line-height: 1.6;
}
.rp-profile-meta-grid {
	display: grid;
	grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
	gap: 20px;
	background: #f9fafb;
	padding: 20px;
	border-radius: 8px;
	border: 1px solid #e5e7eb;
	margin-bottom: 8px;
}
.rp-profile-meta-item {
	display: flex;
	flex-direction: column;
}
.rp-profile-meta-label {
	font-size: 11px;
	font-weight: 600;
	color: #9ca3af;
	text-transform: uppercase;
	margin-bottom: 4px;
}
.rp-profile-meta-val {
	font-size: 15px;
	font-weight: 700;
	color: #1f2937;
}
.rp-consent-switch-wrapper {
	display: flex;
	justify-content: space-between;
	align-items: center;
	background: #f9fafb;
	padding: 20px;
	border-radius: 8px;
	border: 1px solid #e5e7eb;
}
.rp-consent-label-wrapper {
	display: flex;
	flex-direction: column;
}
.rp-consent-label {
	font-size: 15px;
	font-weight: 700;
	color: #1f2937;
}
.rp-consent-sublabel {
	font-size: 13px;
	color: var(--rp-color-muted, #53666f);
}
.rp-switch {
	position: relative;
	display: inline-block;
	width: 52px;
	height: 28px;
}
.rp-switch input {
	opacity: 0;
	width: 0;
	height: 0;
}
.rp-slider {
	position: absolute;
	cursor: pointer;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	background-color: #d1d5db;
	transition: .3s;
	border-radius: 34px;
}
.rp-slider:before {
	position: absolute;
	content: "";
	height: 20px;
	width: 20px;
	left: 4px;
	bottom: 4px;
	background-color: white;
	transition: .3s;
	border-radius: 50%;
}
input:checked + .rp-slider {
	background-color: var(--rp-color-green, #176b52);
}
input:checked + .rp-slider:before {
	transform: translateX(24px);
}
.rp-privacy-actions-row {
	display: flex;
	gap: 16px;
	flex-wrap: wrap;
}
.rp-erasure-alert {
	background: #fffbeb;
	border: 1px solid #fef3c7;
	color: #b45309;
	padding: 16px;
	border-radius: 8px;
	font-size: 14px;
	margin-bottom: 20px;
	font-weight: 600;
}
.rp-btn-erasure {
	background: #dc2626 !important;
	border-color: #dc2626 !important;
}
.rp-btn-erasure:hover {
	background: #b91c1c !important;
	border-color: #b91c1c !important;
}
</style>

<main id="primary" class="rp-moderation-dashboard-main">
	<section class="rp-dashboard-hero">
		<div class="rp-page-shell">
			<p class="rp-eyebrow"><?php esc_html_e( 'Settings', 'resilient-hub' ); ?></p>
			<h1 class="rp-page-title"><?php esc_html_e( 'My Profile & Privacy', 'resilient-hub' ); ?></h1>
		</div>
	</section>
	
	<div class="rp-dashboard-body">
		<div class="rp-page-shell">
			<header class="rp-dashboard-header">
				<p class="rp-dashboard-subtitle"><?php esc_html_e( 'Configure your display parameters, download your information, and manage your cookie settings.', 'resilient-hub' ); ?></p>
			</header>

			<div class="rp-privacy-grid">
				<!-- 1. Profile Information Form -->
				<div class="rp-privacy-section-card">
					<h3 class="rp-privacy-section-title">
						<span class="dashicons dashicons-admin-users"></span>
						<?php esc_html_e( 'Profile Settings', 'resilient-hub' ); ?>
					</h3>
					<p class="rp-privacy-description"><?php esc_html_e( 'Configure your public display name, pronouns, and organization affiliation.', 'resilient-hub' ); ?></p>
					
					<form id="rp-profile-form" style="max-width: 600px;">
						<?php wp_nonce_field( 'rp_save_profile_' . $current_user->ID, 'rp_profile_nonce' ); ?>
						
						<div style="display: flex; flex-direction: column; gap: 16px; margin-bottom: 24px;">
							<div style="display: flex; flex-direction: column; gap: 6px;">
								<label for="rp_display_name" style="font-size: 14px; font-weight: 700; color: var(--rp-color-navy, #12324a);"><?php esc_html_e( 'Display Name', 'resilient-hub' ); ?></label>
								<input id="rp_display_name" name="rp_display_name" type="text" value="<?php echo esc_attr( $current_user->display_name ); ?>" required style="padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 15px; width: 100%;">
							</div>
							
							<div style="display: flex; flex-direction: column; gap: 6px;">
								<label for="rp_pronouns" style="font-size: 14px; font-weight: 700; color: var(--rp-color-navy, #12324a);"><?php esc_html_e( 'Pronouns', 'resilient-hub' ); ?></label>
								<input id="rp_pronouns" name="rp_pronouns" type="text" value="<?php echo esc_attr( get_user_meta( $current_user->ID, '_rp_pronouns', true ) ); ?>" placeholder="<?php esc_attr_e( 'e.g. He/Him, She/Her, They/Them', 'resilient-hub' ); ?>" style="padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 15px; width: 100%;">
							</div>
							
							<div style="display: flex; flex-direction: column; gap: 6px;">
								<label for="rp_affiliation" style="font-size: 14px; font-weight: 700; color: var(--rp-color-navy, #12324a);"><?php esc_html_e( 'Affiliation / Organization', 'resilient-hub' ); ?></label>
								<input id="rp_affiliation" name="rp_affiliation" type="text" value="<?php echo esc_attr( get_user_meta( $current_user->ID, '_rp_affiliation', true ) ); ?>" placeholder="<?php esc_attr_e( 'e.g. ACCORD, LGU Malabon, CARE', 'resilient-hub' ); ?>" style="padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 15px; width: 100%;">
							</div>
						</div>
						
						<button type="submit" id="rp-btn-save-profile" class="rp-button">
							<?php esc_html_e( 'Save Profile Details', 'resilient-hub' ); ?>
						</button>
					</form>
				</div>

				<!-- 2. Data Portability (JSON Export) -->
				<div class="rp-privacy-section-card">
					<h3 class="rp-privacy-section-title">
						<span class="dashicons dashicons-download"></span>
						<?php esc_html_e( 'Data Portability (GDPR Right of Access)', 'resilient-hub' ); ?>
					</h3>
					<p class="rp-privacy-description"><?php esc_html_e( 'Under the GDPR, you have the right to request a copy of all information logged on your profile. Clicking the button below downloads a machine-readable JSON file containing your user details, page view history, and resource download logs.', 'resilient-hub' ); ?></p>
					
					<a href="<?php echo esc_url( add_query_arg( 'rp_download_user_data', '1', home_url( '/profile/' ) ) ); ?>" class="rp-button">
						<?php esc_html_e( 'Download My Data (JSON)', 'resilient-hub' ); ?>
					</a>
				</div>

				<!-- 3. Consent Toggles (Cookie Preferences) -->
				<div class="rp-privacy-section-card">
					<h3 class="rp-privacy-section-title">
						<span class="dashicons dashicons-admin-settings"></span>
						<?php esc_html_e( 'Consent Preferences Dashboard', 'resilient-hub' ); ?>
					</h3>
					<p class="rp-privacy-description"><?php esc_html_e( 'Optional analytics track which learning resources are popular to help us design better materials. Toggle your tracking consent preferences below.', 'resilient-hub' ); ?></p>
					
					<div class="rp-consent-switch-wrapper">
						<div class="rp-consent-label-wrapper">
							<span class="rp-consent-label"><?php esc_html_e( 'Optional Analytics & Usage Tracking', 'resilient-hub' ); ?></span>
							<span class="rp-consent-sublabel"><?php esc_html_e( 'Allows the platform to log your resource page views and download activity.', 'resilient-hub' ); ?></span>
						</div>
						<label class="rp-switch">
							<input type="checkbox" id="rp-analytics-toggle">
							<span class="rp-slider"></span>
						</label>
					</div>
				</div>

				<!-- 4. Right to Erasure (Account Deletion) -->
				<div class="rp-privacy-section-card">
					<h3 class="rp-privacy-section-title" style="color: #dc2626;">
						<span class="dashicons dashicons-trash" style="color: #dc2626;"></span>
						<?php esc_html_e( 'Right to Erasure (Account Deletion)', 'resilient-hub' ); ?>
					</h3>
					<p class="rp-privacy-description"><?php esc_html_e( 'You can request the complete deletion of your profile and data records from our database. Note that this action is irreversible and reassigned to system files. Deletions are processed by the site administrator.', 'resilient-hub' ); ?></p>
					
					<div class="rp-erasure-container">
						<?php if ( $deletion_requested ) : ?>
							<div class="rp-erasure-alert">
								<?php esc_html_e( 'You have already submitted an account deletion request. The administrator will process and delete your profile within 72 hours.', 'resilient-hub' ); ?>
							</div>
						<?php else : ?>
							<div class="rp-privacy-actions-row">
								<button id="rp-btn-request-erasure" class="rp-button rp-btn-erasure" data-user-id="<?php echo absint( $current_user->ID ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'rp_request_deletion_' . $current_user->ID ) ); ?>">
									<?php esc_html_e( 'Submit Account Deletion Request', 'resilient-hub' ); ?>
								</button>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
	// 1. Save Profile Details AJAX
	var profileForm = document.getElementById('rp-profile-form');
	var saveBtn = document.getElementById('rp-btn-save-profile');
	
	if (profileForm) {
		profileForm.addEventListener('submit', function(e) {
			e.preventDefault();
			
			saveBtn.disabled = true;
			saveBtn.textContent = '<?php echo esc_js( __( 'Saving...', 'resilient-hub' ) ); ?>';
			
			var formData = new FormData(profileForm);
			formData.append('action', 'rp_save_user_profile');
			
			fetch('<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', {
				method: 'POST',
				credentials: 'same-origin',
				body: formData
			})
			.then(r => r.json())
			.then(data => {
				saveBtn.disabled = false;
				saveBtn.textContent = '<?php echo esc_js( __( 'Save Profile Details', 'resilient-hub' ) ); ?>';
				
				// Show feedback notification
				var status = document.createElement('div');
				status.style.position = 'fixed';
				status.style.bottom = '24px';
				status.style.right = '24px';
				status.style.background = data.success ? '#065f46' : '#dc2626';
				status.style.color = '#fff';
				status.style.padding = '12px 20px';
				status.style.borderRadius = '8px';
				status.style.fontSize = '14px';
				status.style.fontWeight = 'bold';
				status.style.boxShadow = '0 4px 6px rgba(0,0,0,0.1)';
				status.style.zIndex = '999999';
				status.textContent = data.data.message || 'Saved successfully.';
				
				document.body.appendChild(status);
				setTimeout(function() { status.remove(); }, 3000);
			})
			.catch(err => {
				console.error(err);
				saveBtn.disabled = false;
				saveBtn.textContent = '<?php echo esc_js( __( 'Save Profile Details', 'resilient-hub' ) ); ?>';
				alert('An error occurred. Please try again.');
			});
		});
	}

	// 2. Manage Consent toggle state
	var consentToggle = document.getElementById('rp-analytics-toggle');
	if (consentToggle) {
		var consentCookie = getCookie('rp_cookie_consent');
		if (!consentCookie) {
			try {
				consentCookie = localStorage.getItem('rp_cookie_consent');
			} catch (e) {}
		}
		
		// If cookie is empty or accepted, toggle is ON. If cookie is declined, toggle is OFF.
		consentToggle.checked = (consentCookie !== 'declined');
		
		consentToggle.addEventListener('change', function() {
			var choice = this.checked ? 'accepted' : 'declined';
			setCookie('rp_cookie_consent', choice, 365);
			
			// Alert feedback
			var status = document.createElement('div');
			status.style.position = 'fixed';
			status.style.bottom = '24px';
			status.style.right = '24px';
			status.style.background = '#065f46';
			status.style.color = '#fff';
			status.style.padding = '12px 20px';
			status.style.borderRadius = '8px';
			status.style.fontSize = '14px';
			status.style.fontWeight = 'bold';
			status.style.boxShadow = '0 4px 6px rgba(0,0,0,0.1)';
			status.style.zIndex = '999999';
			status.textContent = choice === 'accepted' ? '<?php echo esc_js( __( 'Optional analytics enabled.', 'resilient-hub' ) ); ?>' : '<?php echo esc_js( __( 'Optional analytics disabled.', 'resilient-hub' ) ); ?>';
			
			if (choice === 'declined') {
				status.style.background = '#374151';
			}
			
			document.body.appendChild(status);
			setTimeout(function() { status.remove(); }, 3000);
		});
	}

	// 3. Submit Erasure Request AJAX
	var erasureBtn = document.getElementById('rp-btn-request-erasure');
	if (erasureBtn) {
		erasureBtn.addEventListener('click', function(e) {
			e.preventDefault();
			
			if (!confirm('<?php echo esc_js( __( 'Are you sure you want to request complete account deletion? This will permanently delete your account profile.', 'resilient-hub' ) ); ?>')) {
				return;
			}
			
			var btn = this;
			var userId = btn.getAttribute('data-user-id');
			var nonce = btn.getAttribute('data-nonce');
			var container = btn.closest('.rp-erasure-container');
			
			btn.disabled = true;
			btn.textContent = '<?php echo esc_js( __( 'Submitting...', 'resilient-hub' ) ); ?>';
			
			var formData = new FormData();
			formData.append('action', 'rp_request_account_deletion');
			formData.append('nonce', nonce);
			
			fetch('<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', {
				method: 'POST',
				credentials: 'same-origin',
				body: formData
			})
			.then(r => r.json())
			.then(data => {
				if (data.success) {
					container.innerHTML = '<div class="rp-erasure-alert">' + data.data.message + '</div>';
				} else {
					alert(data.data.message || 'An error occurred. Please try again.');
					btn.disabled = false;
					btn.textContent = '<?php echo esc_js( __( 'Submit Account Deletion Request', 'resilient-hub' ) ); ?>';
				}
			})
			.catch(err => {
				console.error(err);
				alert('Connection error. Please try again.');
				btn.disabled = false;
				btn.textContent = '<?php echo esc_js( __( 'Submit Account Deletion Request', 'resilient-hub' ) ); ?>';
			});
		});
	}
	
	// Cookies helpers
	function setCookie(name, value, days) {
		var expires = "";
		if (days) {
			var date = new Date();
			date.setTime(date.getTime() + (days*24*60*60*1000));
			expires = "; expires=" + date.toUTCString();
		}
		var secure = window.location.protocol === 'https:' ? '; Secure' : '';
		document.cookie = name + "=" + (value || "")  + expires + "; path=/; SameSite=Lax" + secure;
		if (name === 'rp_cookie_consent') {
			try {
				localStorage.setItem(name, value);
			} catch (e) {}
		}
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

<?php
get_footer();

<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! current_user_can( 'manage_options' ) ) {
	return;
}
?>
<div class="dplr_settings dp-library">
	<!-- This inline style is a hack to avoid loading content before the loading screen is hidden. -->
	<div class="dplr_connect" id="dplr_body_content" style="display: none;">
		<a href="<?php echo esc_url( 'https://www.fromdoppler.com/en/?utm_source=landing&utm_medium=integracion&utm_campaign=wordpress' ); ?>" target="_blank" class="dplr-logo-header" rel="noopener noreferrer">
			<img src="<?php echo esc_url( DOPPLER_PLUGIN_URL ); ?>admin/img/logo-doppler.svg" alt="<?php esc_attr_e( 'Doppler logo', 'doppler-form' ); ?>"/>
		</a>
		<?php if ( ! $relay_connected ) : ?>
			<header class="hero-banner">
				<div class="dp-rowflex">
					<div class="col-sm-12 col-md-12 col-lg-12">
						<h2><?php esc_html_e("SMTP Configuration", "doppler-form" ); ?></h2>
					</div>
					<div class="col-sm-6">
						<p><?php esc_html_e("Connect to your Doppler Relay account to enable SMTP for WordPress.","doppler-form") ;?></p>
						<p>
							<?php esc_html_e( 'Don\'t have a Doppler Relay account yet?', 'doppler-form' ); ?>
							<a href="<?php echo esc_url( $relay_signup_url ); ?>" target="_blank" rel="noopener noreferrer">
								<?php esc_html_e( 'Sign up here', 'doppler-form' ); ?>
							</a>
						</p>
					</div>
				</div>
				<span class="arrow"></span>
			</header>
			<div class="col-lg-6 col-lmd-6 col-sm-8 awa-form">
				<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" class="dplr-relay-loading-form">
					<?php wp_nonce_field( 'dplr-connect-relay', 'dplr_relay_nonce' ); ?>
					<input type="hidden" name="action" value="dplr_connect_relay">
					<label for="dplr-relay-account-name" class="labelcontrol">
						<?php esc_html_e( 'Account Name', 'doppler-form' ); ?>
						<input type="text"
							id="dplr-relay-account-name"
							class="visible box-shado-0"
							name="dplr_relay_account_name"
							autocomplete="off"
							value=""/>
					</label>
					<label for="dplr-relay-api-key" class="labelcontrol">
						<div class="dp-icons-group" style="justify-content: start">
							<span class="m-r-12">API Key</span>
							<div class="dp-tooltip-container ">
								<div class="ms-icon icon-info-icon">
								</div>
								<div class="dp-tooltip-top dp-tooltip-top-bubble">
									<span>
										<?php esc_html_e("How do you find your API Key? Press", "doppler-form"); ?>
										<a href="<?php esc_html_e('https://help.dopplerrelay.com/en/where-can-i-find-my-api-key-and-smtp-credentials', 'doppler-form')?>">
											<?php esc_html_e("HELP", "doppler-form"); ?>
										</a>
									</span>
								</div>
							</div>
						</div>
						<input type="password"
							id="dplr-relay-api-key"
							name="dplr_relay_api_key"
							class="visible box-shado-0"
							autocomplete="new-password"
							value="" />
					</label>
					<button class="dp-button button-big primary-green button-big">
						<?php esc_html_e("Connect", "doppler-form"); ?>
					</button>
				</form>
				<?php if($error): ?>
					<div class="tooltip tooltip-warning tooltip--user_api_error">
						<div class="text-red text-left">
								<span><?php echo esc_html($errorMessage)  ?></span>
						</div>
					</div>
				<?php endif;?>
			</div>
		<?php else : ?>
			<?php
			$smtp_notices = array();

			foreach ( get_settings_errors( $this->smtp_manager->get_option_name() ) as $smtp_settings_error ) {
				if ( empty( $smtp_settings_error['message'] ) ) {
					continue;
				}

				$smtp_notices[] = array(
					'type'    => isset( $smtp_settings_error['type'] ) ? $smtp_settings_error['type'] : 'error',
					'code'    => isset( $smtp_settings_error['code'] ) ? $smtp_settings_error['code'] : '',
					'message' => $smtp_settings_error['message'],
				);
			}

			if ( ! empty( $smtp_page_notice ) && ! empty( $smtp_page_notice['message'] ) ) {
				$smtp_notices[] = $smtp_page_notice;
			}

			if ( ! empty( $smtp_test_notice ) && ! empty( $smtp_test_notice['message'] ) ) {
				$smtp_notices[] = $smtp_test_notice;
			}
			?>
			<div class="dp-container m-t-24">
				<?php foreach ( $smtp_notices as $smtp_notice ) : ?>
					<?php
					$smtp_notice_type  = ! empty( $smtp_notice['type'] ) ? $smtp_notice['type'] : 'error';
					$smtp_notice_type  = 'updated' === $smtp_notice_type ? 'success' : $smtp_notice_type;
					$smtp_notice_class = 'success' === $smtp_notice_type ? 'dp-wrap-success' : 'dp-wrap-cancel';
					$smtp_notice_class = 'warning' === $smtp_notice_type ? 'dp-wrap-warning' : $smtp_notice_class;
					?>
					<div class="dp-rowflex">
						<div class="dp-wrap-message <?php echo esc_attr( $smtp_notice_class ); ?> m-b-12">
							<span class="dp-message-icon"></span>
							<div class="dp-content-message dp-content-full">
								<p><?php echo esc_html( $smtp_notice['message'] ); ?></p>
								<a href="#" class="dp-message-link dplr-message-dismiss"><?php echo esc_html( strtoupper( __( 'Got it', 'doppler-form' ) ) ); ?></a>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
				<section class="col-sm-12 col-md-10 col-lg-7">
					<h2 class="main-title"><?php esc_html_e( 'SMTP Configuration', 'doppler-form' ); ?></h2>
					<header>
						<div class="dp-container">
							<div class="dp-rowflex space-between">
								<div class="col-sm-10 col-md-10 col-lg-10">
									<h3><?php esc_html_e( 'Doppler Relay connection', 'doppler-form' ); ?></h3>
								</div>
								<div class="col-sm-10 col-md-10 col-lg-10">
									<p><?php esc_html_e( 'Your Doppler Relay account is connected. SMTP settings below will use that connection.', 'doppler-form' ); ?></p>
								</div>
								<div class="col-sm-8 dp-icon-wrapper m-t-24">
									<span>
										<strong> <?php esc_html_e("Account","doppler-form");?>:</strong>
										<span> <?php echo esc_html($relay_connection['account_name'])?></span>
									</span>
									<span>
										<strong> <?php esc_html_e("Status","doppler-form");?>:</strong>
										<span> <?php esc_html_e("Active","doppler-form")?></span>
									</span>
								</div>
								<div class="col-sm-2">
									<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" class="dplr-relay-loading-form">
										<?php wp_nonce_field( 'dplr-disconnect-relay', 'dplr_relay_disconnect_nonce' ); ?>
										<input type="hidden" name="action" value="dplr_disconnect_relay">
										<button class="dp-button button-medium primary-green" type="submit">
											<?php esc_html_e( 'Disconnect', 'doppler-form' ); ?>
										</button>
									</form>
								</div>
							</div>
						</div>
					</header>
				</section>

				<?php if ( ! empty( $relay_domains_error ) ) : ?>
					<div class="col-sm-12 m-l-24">
						<div class="dp-rowflex m-t-24">
							<div class="dp-wrap-message dp-wrap-cancel m-b-12">
								<span class="dp-message-icon"></span>
								<div class="dp-content-message dp-content-full">
									<p><?php echo esc_html( "$relay_domains_error" ); ?></p>
									<a href="#" class="dp-message-link dplr-message-dismiss"><?php echo esc_html( strtoupper( __( 'Got it', 'doppler-form' ) ) ); ?></a>
								</div>
							</div>
						</div>
					</div>
				<?php elseif ( $relay_blocked ) : ?>
					<div class="col-sm-12 m-l-24">
						<div class="dp-rowflex m-t-24">
							<div class="dp-wrap-message dp-wrap-cancel m-b-12">
								<span class="dp-message-icon"></span>
								<div class="dp-content-message dp-content-full">
									<p><?php esc_html_e( 'You need at least one verified domain with DKIM, SPF and DMARC ready in Doppler Relay before enabling SMTP.', 'doppler-form' ); ?></p>
									<a href="#" class="dp-message-link dplr-message-dismiss"><?php echo esc_html( strtoupper( __( 'Got it', 'doppler-form' ) ) ); ?></a>
								</div>
							</div>
						</div>
					</div>
				<?php endif; ?>

				<form action="options.php" method="post" class="m-t-24 dplr-relay-loading-form">
					<?php settings_fields( $this->smtp_manager->get_option_group() ); ?>

					<section class="col-sm-12 col-md-10 col-lg-7">
						<header>
							<div class="dp-container">
								<div class="dp-rowflex space-between">
									<div class="col-sm-10 col-md-10 col-lg-10">
										<h3><?php esc_html_e( 'Use SMTP for wp_mail()', 'doppler-form' ); ?></h3>
									</div>
									<div class="dp-switch">
										<input type="hidden" name="dplr_smtp_settings[enabled]" value="0">
										<input type="checkbox" id="dplr-smtp-enabled" name="dplr_smtp_settings[enabled]" value="1" <?php checked( ! empty( $smtp_settings['enabled'] ), true ); ?> <?php disabled( $relay_blocked, true ); ?>>
										<label for="dplr-smtp-enabled">
											<span></span>
										</label>
									</div>
									<div class="col-sm-10 col-md-10 col-lg-10">
										<p><?php esc_html_e( 'When enabled, all emails sent from your WordPress site will be delivered through Doppler Relay.', 'doppler-form' ); ?></p>
									</div>
								</div>
							</div>
						</header>
						<div class="dp-container">
							<div class="dp-rowflex space-between">
								<div class="col-sm-6 m-t-24">
									<span>
										<strong> <?php esc_html_e( 'HOST SMTP', 'doppler-form' ); ?>:</strong>
										<span> <?php echo esc_html( $relay_host ); ?></span>
									</span>
								</div>
								<div class="col-sm-4 m-t-24">
									<span>
										<strong> <?php esc_html_e( 'PORT SMTP', 'doppler-form' ); ?>:</strong>
										<span> <?php echo esc_html( $relay_port ); ?></span>
									</span>
								</div>
								<div class="col-sm-2 m-t-24">
									<span>
										<strong> <?php esc_html_e( 'Security', 'doppler-form' ); ?>:</strong>
										<span> <?php echo esc_html( $relay_encryption ); ?></span>
									</span>
								</div>
							</div>
						</div>

						<div class="awa-form col-sm-12 col-md-12 col-lg-12 m-t-24">
							<label for="dplr-smtp-user" class="labelcontrol d-flex align-center">
								<span class="col-sm-3 col-md-3 col-lg-3"><?php esc_html_e( 'USER SMTP', 'doppler-form' ); ?>:</span>
								<input
									type="text"
									id="dplr-smtp-user"
									class="col-sm-8 col-md-8 col-lg-8 box-shado-0"
									name="dplr_smtp_settings[smtp_user]"
									value="<?php echo esc_attr( $smtp_settings['smtp_user'] ); ?>"
									autocomplete="off"
									required="required"
									aria-required="true"
									<?php disabled( $relay_blocked, true ); ?>>
							</label>
						</div>

						<header class="m-t-24">
							<div class="dp-container">
								<div class="dp-rowflex space-between">
									<div class="col-sm-10 col-md-10 col-lg-10">
										<h3><?php esc_html_e( 'Forced sender', 'doppler-form' ); ?></h3>
									</div>
									<div class="col-sm-10 col-md-10 col-lg-10">
										<p><?php esc_html_e( 'Enter the From alias and select one verified domain from Doppler Relay.', 'doppler-form' ); ?></p>
									</div>
								</div>
							</div>
						</header>

						<div class="awa-form col-sm-12 col-md-12 col-lg-12 m-t-24">
							<label for="dplr-smtp-from-local-part" class="labelcontrol d-flex align-center">
								<span class="col-sm-3"><?php esc_html_e( 'From email', 'doppler-form' ); ?>:</span>
								<input
									type="text"
									id="dplr-smtp-from-local-part"
									class="col-sm-4 box-shado-0"
									name="dplr_smtp_settings[from_local_part]"
									value="<?php echo esc_attr( $smtp_settings['from_local_part'] ); ?>"
									autocomplete="off"
									placeholder="<?php esc_attr_e( 'notifications', 'doppler-form' ); ?>"
									pattern="[^@\s]+"
									title="<?php esc_attr_e( 'Enter only the email alias, without @ or domain.', 'doppler-form' ); ?>"
									required="required"
									aria-required="true"
									<?php disabled( $relay_blocked, true ); ?>>
								<span>@</span>
								<div class="dp-select col-sm-4 box-shado-0 pl-0">
									<span class="dropdown-arrow m-r-12"></span>
									<select id="dplr-smtp-from-domain" name="dplr_smtp_settings[from_domain]" required="required" aria-required="true" <?php disabled( $relay_blocked, true ); ?>>
										<?php if ( empty( $verified_domains ) ) : ?>
											<option value=""><?php esc_html_e( 'No verified domains available', 'doppler-form' ); ?></option>
										<?php else : ?>
											<?php foreach ( $verified_domains as $domain_name ) : ?>
												<option value="<?php echo esc_attr( $domain_name ); ?>" <?php selected( $selected_domain, $domain_name ); ?>>
													<?php echo esc_html( $domain_name ); ?>
												</option>
											<?php endforeach; ?>
										<?php endif; ?>
									</select>
								</div>
							</label>
						</div>

						<div class="awa-form col-sm-12 col-md-12 col-lg-12">
							<label for="dplr-smtp-from-name" class="labelcontrol d-flex align-center">
								<span class="col-sm-3 col-md-3 col-lg-3"><?php esc_html_e( 'From name', 'doppler-form' ); ?>:</span>
								<input
									type="text"
									id="dplr-smtp-from-name"
									class="col-sm-8 col-md-8 col-lg-8 box-shado-0"
									name="dplr_smtp_settings[from_name]"
									value="<?php echo esc_attr( $smtp_settings['from_name'] ); ?>"
									placeholder="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
									required="required"
									aria-required="true"
									<?php disabled( $relay_blocked, true ); ?>>
							</label>
						</div>
					</section>

					<div class="col-sm-12 col-md-10 col-lg-7 m-t-36 d-flex justify-end">
						<button class="dp-button button-medium primary-green text-align--right" <?php disabled( $relay_blocked, true ); ?>>
							<?php esc_html_e( 'Save', 'doppler-form' ); ?>
						</button>
					</div>
				</form>

				<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" class="m-t-36 dplr-relay-loading-form">
					<?php wp_nonce_field( 'dplr-send-smtp-test', 'dplr_smtp_test_nonce' ); ?>
					<input type="hidden" name="action" value="dplr_send_smtp_test">

					<section class="col-sm-12 col-md-10 col-lg-7">
						<h2 class="main-title"><?php esc_html_e( 'Send test email', 'doppler-form' ); ?></h2>
						<div class="awa-form col-sm-12 col-md-12 col-lg-12">
							<label for="dplr-smtp-test-email" class="labelcontrol d-flex align-center">
								<span class="col-sm-3 col-md-3 col-lg-3"><?php esc_html_e( 'Test email', 'doppler-form' ); ?>:</span>
								<input
									type="email"
									id="dplr-smtp-test-email"
									class="col-sm-8 col-md-8 col-lg-8 box-shado-0"
									name="dplr_smtp_test_email"
									value="<?php echo esc_attr( wp_get_current_user()->user_email ); ?>"
									placeholder="<?php esc_attr_e( 'name@example.com', 'doppler-form' ); ?>"
									<?php disabled( $relay_blocked, true ); ?>>
							</label>
						</div>
						<div class="col-sm-12 col-md-12 col-lg-12">
							<p><?php esc_html_e( 'Use this button after saving the Relay SMTP configuration. The plugin will send a real test message using wp_mail().', 'doppler-form' ); ?></p>
						</div>
					</section>

					<div class="col-sm-12 col-md-10 col-lg-7 m-t-24 d-flex justify-end">
						<button class="dp-button button-medium primary-green text-align--right" <?php disabled( $relay_blocked, true ); ?>>
							<?php esc_html_e( 'Send test email', 'doppler-form' ); ?>
						</button>
					</div>
				</form>
			</div>
		<?php endif; ?>
	</div>
</div>

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
	<div class="dplr_connect relay_section" id="dplr_body_content" style="display: none;">
		<?php if ( ! $relay_connected ) : ?>
			<a href="<?php echo esc_url( 'https://www.dopplerrelay.com/' ); ?>" target="_blank" class="relay-logo-header" rel="noopener noreferrer">
				<img src="<?php echo esc_url( DOPPLER_PLUGIN_URL ); ?>admin/img/logo-dopplerrelay.svg" alt="<?php esc_attr_e( 'Doppler logo', 'doppler-form' ); ?>"/>
			</a>
			<header class="hero-banner">
				<div class="dp-rowflex">
					<div class="col-sm-12 col-md-12 col-lg-12">
						<h2><?php esc_html_e("Doppler Relay Account", "doppler-form" ); ?></h2>
					</div>
					<div class="col-sm-6">
						<p><?php esc_html_e("Connect to your Doppler Relay account to send emails using SMTP for Wordpress.","doppler-form") ;?></p>
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
			<div class="col-lg-3 col-lmd-4 col-sm-4 awa-form">
				<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" class="dplr-relay-loading-form">
					<?php wp_nonce_field( 'dplr-connect-relay', 'dplr_relay_nonce' ); ?>
					<input type="hidden" name="action" value="dplr_connect_relay">
					<label for="dplr-relay-account-name">
						<?php esc_html_e( 'Account Name', 'doppler-form' ); ?>
						<input type="text"
							id="dplr-relay-account-name"
							class="visible box-shado-0"
							name="dplr_relay_account_name"
							autocomplete="off"
							placeholder="<?php esc_attr_e( 'Example: dopplerrelay', 'doppler-form' ); ?>"
							value=""/>
					</label>
					<label for="dplr-relay-api-key" class="m-t-12">
						<div class="dp-icons-group" style="justify-content: start">
							<span class="m-r-12">API Key</span>
							<div class="dp-tooltip-container m-b-6">
								<div class="ms-icon help-icon">
								</div>
								<div class="dp-tooltip-top dp-tooltip-top-bubble">
									<span>
										<?php esc_html_e("How do you find your API Key? Press", "doppler-form"); ?>
										<a href="<?php esc_html_e('https://help.dopplerrelay.com/en/where-can-i-find-my-api-key-and-smtp-credentials', 'doppler-form')?>" target="_blank" rel="noopener noreferrer">
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
							placeholder="<?php esc_attr_e( 'Insert API Key', 'doppler-form' ); ?>"
							value="" />
					</label>
					<button class="dp-button button-big primary-green button-big m-t-24">
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
			$dplr_smtp_notices = array();

			foreach ( get_settings_errors( $this->smtp_manager->get_option_name() ) as $dplr_smtp_settings_error ) {
				if ( empty( $dplr_smtp_settings_error['message'] ) ) {
					continue;
				}

				$dplr_smtp_notices[] = array(
					'type'    => isset( $dplr_smtp_settings_error['type'] ) ? $dplr_smtp_settings_error['type'] : 'error',
					'code'    => isset( $dplr_smtp_settings_error['code'] ) ? $dplr_smtp_settings_error['code'] : '',
					'message' => $dplr_smtp_settings_error['message'],
				);
			}

			if ( ! empty( $smtp_page_notice ) && ! empty( $smtp_page_notice['message'] ) ) {
				$dplr_smtp_notices[] = $smtp_page_notice;
			}

			if ( ! empty( $smtp_test_notice ) && ! empty( $smtp_test_notice['message'] ) ) {
				$dplr_smtp_notices[] = $smtp_test_notice;
			}

			$dplr_smtp_configuration_errors = $this->smtp_manager->get_configuration_errors( $smtp_settings );
			$dplr_smtp_test_blocked         = $relay_blocked
				|| ! empty( $dplr_smtp_configuration_errors )
				|| empty( $smtp_settings['smtp_user'] )
				|| empty( $smtp_settings['from_email'] );
			$dplr_smtp_settings_save_blocked = $relay_blocked || ! empty( $relay_domains_error );
			?>
			<div class="dp-container m-t-24">
				<div class="dp-rowflex">
					<section class="col-sm-12 dp-box-shadow">
						<header>
							<div class="dp-rowflex space-between p-l-24">
								<div class="col-sm-10 m-t-24">
									<h2><?php esc_html_e( 'Welcome to your Doppler Relay account', 'doppler-form' ); ?></h2>
									<p><?php esc_html_e( 'Here you can view your account status and configure the SMTP provider to send your emails.', 'doppler-form' ); ?></p>
								</div>
								<div class="col-sm-2 m-t-24">
									<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" class="dplr-relay-loading-form">
										<?php wp_nonce_field( 'dplr-disconnect-relay', 'dplr_relay_disconnect_nonce' ); ?>
										<input type="hidden" name="action" value="dplr_disconnect_relay">
										<button class="dp-button button-medium primary-green" type="submit">
											<?php esc_html_e( 'Disconnect', 'doppler-form' ); ?>
										</button>
									</form>
								</div>
								<div class="col-sm-8 dp-icon-wrapper m-t-24 m-b-24">
									<span>
										<strong> <?php esc_html_e("Account","doppler-form");?>:</strong>
										<span> <?php echo esc_html($relay_connection['account_name'])?></span>
									</span>
									<span>
										<strong> <?php esc_html_e("Status","doppler-form");?>:</strong>
										<span> <?php esc_html_e("Active","doppler-form")?></span>
									</span>
									<span>
										<strong> <?php esc_html_e("Connection Date","doppler-form");?>:</strong>
										<span> <?php echo esc_html(
											wp_date(
												'd/m/Y',
												strtotime( $relay_connection['connected_at'] )
											))?>
										</span>
									</span>
								</div>
							</div>
						</header>
					</section>

					<?php foreach ( $dplr_smtp_notices as $dplr_smtp_notice ) : ?>
						<?php
						$dplr_smtp_notice_type  = ! empty( $dplr_smtp_notice['type'] ) ? $dplr_smtp_notice['type'] : 'error';
						$dplr_smtp_notice_type  = 'updated' === $dplr_smtp_notice_type ? 'success' : $dplr_smtp_notice_type;
						$dplr_smtp_notice_class = 'success' === $dplr_smtp_notice_type ? 'dp-wrap-success' : 'dp-wrap-cancel';
						$dplr_smtp_notice_class = 'warning' === $dplr_smtp_notice_type ? 'dp-wrap-warning' : $dplr_smtp_notice_class;
						?>
						<div class="m-t-24">
							<div class="dp-wrap-message <?php echo esc_attr( $dplr_smtp_notice_class ); ?> m-b-12">
								<span class="dp-message-icon"></span>
								<div class="dp-content-message dp-content-full">
									<p><?php echo esc_html( $dplr_smtp_notice['message'] ); ?></p>
									<a href="#" class="dp-message-link dplr-message-dismiss"><?php echo esc_html( strtoupper( __( 'Got it', 'doppler-form' ) ) ); ?></a>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
					<?php if ( ! empty( $relay_domains_error ) ) : ?>
						<div class="m-t-24">
							<div class="dp-wrap-message dp-wrap-cancel m-b-12">
								<span class="dp-message-icon"></span>
								<div class="dp-content-message dp-content-full">
									<p><?php echo esc_html( "$relay_domains_error" ); ?></p>
									<a href="#" class="dp-message-link dplr-message-dismiss"><?php echo esc_html( strtoupper( __( 'Got it', 'doppler-form' ) ) ); ?></a>
								</div>
							</div>
						</div>
					<?php elseif ( $relay_blocked ) : ?>
						<div class="m-t-24">
							<div class="dp-wrap-message dp-wrap-cancel m-b-12">
								<span class="dp-message-icon"></span>
								<div class="dp-content-message dp-content-full">
									<p><?php esc_html_e( 'You need at least one verified domain with DKIM, SPF and DMARC ready in Doppler Relay before configuring SMTP.', 'doppler-form' ); ?></p>
									<a href="#" class="dp-message-link dplr-message-dismiss"><?php echo esc_html( strtoupper( __( 'Got it', 'doppler-form' ) ) ); ?></a>
								</div>
							</div>
						</div>
					<?php endif; ?>

					<section class="col-sm-12 dp-box-shadow m-t-12">
						<form action="options.php" method="post" class="dplr-relay-loading-form m-l-12">
							<?php settings_fields( $this->smtp_manager->get_option_group() ); ?>	
							<header>
								<div class="col-sm-9 m-t-24">
									<h3><?php esc_html_e( 'SMTP configuration', 'doppler-form' ); ?></h3>
									<p><?php esc_html_e( 'Send your Emails through Doppler Relay\'s SMTP provider without needing your own email servers, and send large campaigns without ending up in spam.', 'doppler-form' ); ?></p>
								</div>
							</header>
							<div class="col-sm-8">
								<div class="dp-rowflex space-between">
									<div class="col-sm-6 m-t-24">
										<label for="dplr-smtp-user">
											<?php esc_html_e( 'USER SMTP', 'doppler-form' ); ?>
											<input 
												type="text"
												id="dplr-smtp-user"
												class="col-sm-8 col-md-8 col-lg-8 box-shado-0"
												name="dplr_smtp_settings[smtp_user]"
												value="<?php echo esc_attr( $smtp_settings['smtp_user'] ); ?>"
												placeholder="<?php esc_attr_e( 'Example', 'doppler-form' ); ?>: info@fromdoppler.com"
												autocomplete="off"
												required="required"
												aria-required="true"
												<?php disabled( $relay_blocked, true ); ?>>
										</label>
									</div>
									<div class="col-sm-6 m-t-24">
										<label for="dplr-smtp-host" class="labelcontrol" aria-disabled="true">
											<?php esc_html_e( 'Server host', 'doppler-form' ); ?>
											<input 
												type="text"
												id="dplr-smtp-host"
												class="col-sm-8 col-md-8 col-lg-8 box-shado-0"
												value="<?php echo esc_attr( $relay_host ); ?>"
												autocomplete="off"
												disabled="true">
										</label>
									</div>
									<div class="col-sm-6 m-t-12">
										<label for="dplr-smtp-port" class="labelcontrol" aria-disabled="true">
											<?php esc_html_e( 'Port', 'doppler-form' ); ?>
											<input 
												type="text"
												id="dplr-smtp-port"
												class="col-sm-8 col-md-8 col-lg-8 box-shado-0"
												value="<?php echo esc_attr( $relay_port ); ?>"
												autocomplete="off"
												disabled="true">
										</label>
									</div>
									<div class="col-sm-6 m-t-12">
										<label for="dplr-smtp-security" class="labelcontrol" aria-disabled="true">
											<?php esc_html_e( 'Security', 'doppler-form' ); ?>
											<input 
												type="text"
												id="dplr-smtp-security"
												class="col-sm-8 col-md-8 col-lg-8 box-shado-0"
												value="<?php echo esc_attr( $relay_encryption ); ?>"
												autocomplete="off"
												disabled="true">
										</label>
									</div>
								</div>
							</div>
							<header class="m-t-24">
								<div class="col-sm-9">
									<h3><?php esc_html_e( 'Sender email', 'doppler-form' ); ?></h3>
									<p><?php esc_html_e( 'Enter the sender name and email address you want to use to deliver your emails. Remember that it must belong to a verified Doppler Relay domain.', 'doppler-form' ); ?></p>
								</div>
							</header>
							<div class="col-sm-8 m-t-24">
								<div class="dp-rowflex space-between">
									<div class="col-sm-5">
										<label for="dplr-smtp-from-name" class="labelcontrol">
											<?php esc_html_e( 'From name', 'doppler-form' ); ?>
											<input
												type="text"
												id="dplr-smtp-from-name"
												class="col-sm-3 box-shado-0"
												name="dplr_smtp_settings[from_name]"
												value="<?php echo esc_attr( $smtp_settings['from_name'] ); ?>"
												placeholder="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
												required="required"
												aria-required="true"
												<?php disabled( $relay_blocked, true ); ?>>
										</label>
									</div>
									<div class="col-sm-7">
										<label for="dplr-smtp-from-local-part" class="labelcontrol">
											<?php esc_html_e( 'From email', 'doppler-form' ); ?>
											<div class="dplr-from-email-inline">
												<input
													type="text"
													id="dplr-smtp-from-local-part"
													class="box-shado-0"
													name="dplr_smtp_settings[from_local_part]"
													value="<?php echo esc_attr( $smtp_settings['from_local_part'] ); ?>"
													autocomplete="off"
													placeholder="<?php esc_attr_e( 'Example: notifications', 'doppler-form' ); ?>"
													pattern="[^@\s]+"
													title="<?php esc_attr_e( 'Enter only the email alias, without @ or domain.', 'doppler-form' ); ?>"
													required="required"
													aria-required="true"
													<?php disabled( $relay_blocked, true ); ?>>
												<span>@</span>
												<div class="dp-select box-shado-0 pl-0">
													<span class="dropdown-arrow m-r-12"></span>
													<select id="dplr-smtp-from-domain" name="dplr_smtp_settings[from_domain]" required="required" aria-required="true" <?php disabled( $relay_blocked, true ); ?>>
														<?php if ( empty( $verified_domains ) ) : ?>
															<option value=""><?php esc_html_e( 'No verified domains available', 'doppler-form' ); ?></option>
														<?php else : ?>
															<?php foreach ( $verified_domains as $dplr_domain_name ) : ?>
																<option value="<?php echo esc_attr( $dplr_domain_name ); ?>" <?php selected( $selected_domain, $dplr_domain_name ); ?>>
																	<?php echo esc_html( $dplr_domain_name ); ?>
																</option>
															<?php endforeach; ?>
														<?php endif; ?>
													</select>
												</div>
											</div>
										</label>
									</div>
								</div>
							</div>
							<div class="col-sm-9 m-t-42 m-b-30">
								<div class="dp-h-divider"></div>
							</div>
							<button class="dp-button button-medium secondary-green m-l-12 m-b-30" <?php disabled( $dplr_smtp_settings_save_blocked, true ); ?>>
								<?php esc_html_e( 'Save changes', 'doppler-form' ); ?>
							</button>
						</form>
					</section>

					<section class="col-sm-12 dp-box-shadow m-t-12">
						<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" class="m-t-36 dplr-relay-loading-form">
							<?php wp_nonce_field( 'dplr-send-smtp-test', 'dplr_smtp_test_nonce' ); ?>
							<input type="hidden" name="action" value="dplr_send_smtp_test">
							
							<header class="m-t-24">
								<div class="col-sm-8">
									<h3><?php esc_html_e( 'Send test email', 'doppler-form' ); ?></h3>
									<p><?php esc_html_e( 'After setting up Relay SMTP, you can test proper email delivery from here by entering a test email address.', 'doppler-form' ); ?></p>
								</div>
							</header>
							<div class="col-sm-8 dp-rowflex m-t-24 m-b-24">
								<label for="dplr-smtp-test-email" class="labelcontrol col-sm-6">
									<?php esc_html_e( 'Test email', 'doppler-form' ); ?>
									<input
										type="email"
										id="dplr-smtp-test-email"
										class="col-sm-8 col-md-8 col-lg-8 box-shado-0"
										name="dplr_smtp_test_email"
										value="<?php echo esc_attr( wp_get_current_user()->user_email ); ?>"
										<?php disabled( $relay_blocked, true ); ?>>
								</label>

								<button class="dp-button button-medium secondary-green text-align--right m-t-18 m-b-6" <?php disabled( $dplr_smtp_test_blocked, true ); ?>>
									<span class="m-r-48 m-l-48"><?php esc_html_e( 'Send', 'doppler-form' ); ?></span>
								</button>
							</div>
						</form>
					</section>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>

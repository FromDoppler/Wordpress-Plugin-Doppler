<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<section class="dplr_settings dp-library">
	<!-- This inline style is a hack to avoid loading content before the loading screen is hidden. -->
	<div class="dplr_connect" id="dplr_body_content" style="display: none;">
		<?php
		if ($connected) {
			$dplr_woocommerce_plugin_is_active = $this->extension_manager->is_active('doppler-for-woocommerce');
			$dplr_learnpress_plugin_is_active = $this->extension_manager->is_active('doppler-for-learnpress');
			$dplr_woocommerce_lists = get_option('dplr_subscribers_list');
        	$dplr_woocommerce_plugin_is_connected = is_array($dplr_woocommerce_lists) 
				&& ( !empty($dplr_woocommerce_lists['contacts']) 
					|| !empty($dplr_woocommerce_lists['buyers']) 
				);
		?>
			<div class="dp-container m-t-24">
				<div class="dp-rowflex">
					<div class="col-sm-12">
						<div class="dp-box-shadow dp-white m-t-12 p-t-36 p-b-36 p-l-36 p-r-36">
							<div class="dp-rowflex">
								<div class="col-sm-10">
									<h2><?php esc_html_e("Welcome!", "doppler-form" ); ?></h2>
								</div>
								<div class="col-sm-2 text-align--right">
									<form method="POST" action="options.php" id="dplr-disconnect-form">
										<button type="submit" class="dp-button button-medium primary-green">
											<?php esc_html_e("Disconnect", "doppler-form"); ?>
										</button>
									</form>
								</div>
								<div class="col-sm-12">
									<p>
										<?php esc_html_e("Here you can view information about the performance of your forms and the status of your integrations.","doppler-form");?>
									</p>
								</div>
								<div class="col-sm-6 dp-icon-wrapper m-t-24">
									<span>
										<strong> <?php esc_html_e("Account","doppler-form");?>:</strong>
										<span> <?php echo esc_html($options['dplr_option_useraccount'])?></span>
									</span>
									<span>
										<strong> <?php esc_html_e("Status","doppler-form");?>:</strong>
										<span> <?php esc_html_e("Active","doppler-form")?></span>
									</span>
								</div>
								<?php if($dplr_woocommerce_plugin_is_active &&
									$dplr_woocommerce_plugin_is_connected &&
									has_action('wp_ajax_dplrwoo_ajax_check_status')): ?>
									<div class="col-sm-12 dp-icon-wrapper m-t-24" id="dplrwoo-status-wrapper">
										<span>
											<strong><?php esc_html_e("WooCommerce integration","doppler-form");?>:</strong>
											<span id="dplrwoo-status-text"><?php esc_html_e("Checking status...","doppler-form");?></span>
											<span id="dplrwoo-status-detail" class="m-t-6"></span>
											<a href="<?php echo esc_url(admin_url('admin.php?page=doppler_woocommerce_menu'))?>" class="d-none" id="dplrwoo-status-link">
												<?php esc_html_e("click here","doppler-form"); ?>
											</a>
										</span>
									</div>
								<?php elseif ($dplr_woocommerce_plugin_is_active && !$dplr_woocommerce_plugin_is_connected): ?>
									<div class="col-sm-12 dp-icon-wrapper m-t-24">
										<span>
											<strong><?php esc_html_e("WooCommerce integration","doppler-form");?>:</strong>
											<span><?php esc_html_e("Offline","doppler-form");?> | </span>
											<span><?php esc_html_e("To configure, ","doppler-form");?></span>
											<a href="<?php echo esc_url(admin_url('admin.php?page=doppler_woocommerce_menu'))?>">
												<?php esc_html_e("click here","doppler-form"); ?>
											</a>
										</span>
									</div>
								<?php endif; ?>
							</div>
						</div>
						<?php if (!empty($notification_messages)) : 
						?>
							<div class="dp-carousel m-t-24" id="dp-notification-carousel">
								<div class="dp-carousel-wrapper dp-carousel-orange">
									<div class="dp-carousel-content p-l-24 p-r-24">
										<?php foreach ($notification_messages as $dplr_index => $dplr_notification) : ?>
											<div class="dp-carousel-slide<?php echo ($dplr_index === 0) ? ' active' : ''; ?>" data-order="<?php echo esc_html($dplr_index); ?>">
												<div class="dp-carousel-slide-title">
													<?php if (isset($dplr_notification['icon']) && !empty($dplr_notification['icon'])) : ?>
														<img class="dp-carousel-icon m-r-12" src="<?php echo esc_url($dplr_notification['icon']); ?>" alt="" />
													<?php endif; ?>
													<h3><?php echo esc_html($dplr_notification['title']); ?></h3>
												</div>
												<div class="dp-carousel-slide-header">
													<p><?php echo esc_html($dplr_notification['description']); ?></p>
												</div>
												<a href="<?php echo esc_url($dplr_notification['url']); ?>" target="_blank" rel="noopener">
													<span class="ms-icon icon-arrow-next"></span>
													<?php echo esc_html($dplr_notification['url_text']); ?>
												</a>
											</div>
										<?php endforeach; ?>
									</div>
								</div>
								<div class="dp-carousel-dots">
									<?php foreach ($notification_messages as $dplr_index => $dplr_notification) : ?>
										<input class="dp-carousel-dot" <?php echo ($dplr_index === 0) ? 'checked="checked"' : ''; ?> type="radio" value="<?php echo esc_attr($dplr_index); ?>" id="carousel-dot-<?php echo esc_attr($dplr_index); ?>" name="carousel">
									<?php endforeach; ?>
								</div>
							</div>
						<?php endif; ?>
						<!-- KPIs -->
						<div class="dp-wrapper-as-kpi <?php
							echo ($dplr_woocommerce_plugin_is_active && $dplr_learnpress_plugin_is_active ? "col-lg-12"
								: ($dplr_woocommerce_plugin_is_active || $dplr_learnpress_plugin_is_active ? "col-lg-10"
								: "col-lg-8")) ?> col-md-12 col-sm-12"
						>
							<ul class="kpi-ul">
								<li>
									<div class="dp-kpi-card dp-white">
										<span class="dp-assisted-sales-icon dpicon iconapp-web-list"></span>
										<div class="dp-assisted-sales-text">
											<h3>
												<?php echo count($forms); ?>
											</h3>
											<span><?php esc_html_e("Forms", "doppler-form" ); ?></span>
										</div>
									</div>
								</li>
								<li>
									<div class="dp-kpi-card dp-white">
										<span class="dp-assisted-sales-icon dpicon iconapp-iris-recognition"></span>
										<div class="dp-assisted-sales-text">
											<h3>
												<?php 
													$dplr_totalDisplays = 0;
													foreach ($forms as $dplr_form) {
														$dplr_totalDisplays += $dplr_form->events['Display'] ?? 0;
													}
													echo esc_html($this->sanitize_kpi_values($dplr_totalDisplays));
												?>
											</h3>
											<span><?php esc_html_e("Impressions", "doppler-form" ); ?></span>
										</div>
									</div>
								</li>
								<li>
									<div class="dp-kpi-card dp-white">
										<span class="dp-assisted-sales-icon dpicon iconapp-bell1"></span>
										<div class="dp-assisted-sales-text">
											<h3>
											<?php 
													$dplr_totalSubmits = 0;
													foreach ($forms as $dplr_form) {
														$dplr_totalSubmits += $dplr_form->events['Submit'] ?? 0;
													}
													echo esc_html($this->sanitize_kpi_values($dplr_totalSubmits));
												?>
											</h3>
											<span><?php esc_html_e("Subscribed", "doppler-form" ); ?></span>
										</div>
									</div>
								</li>
								<?php if($dplr_woocommerce_plugin_is_active):
									$dplr_woocommerce_synch = get_option('dplrwoo_last_synch');
								?>
									<li>
										<div class="dp-kpi-card dp-white">
											<span class="dp-assisted-sales-icon dpicon iconapp-job-transfer"></span>
											<div class="dp-assisted-sales-text">
												<h3>
													<?php echo esc_html($dplr_woocommerce_synch != null
															? $this->sanitize_kpi_values(($dplr_woocommerce_synch['contacts']['counter'] ?? 0) + ($dplr_woocommerce_synch['buyers']['counter'] ?? 0))
															: 0);
													?>
												</h3>
												<span><?php esc_html_e("Woocommerce contacts", "doppler-form" ); ?></span>
											</div>
										</div>
									</li>
								<?php endif; ?>
								<?php if($dplr_learnpress_plugin_is_active):
									$dplr_learnpress_synch = get_option('dplr_learnpress_subscribers_list');
								?>
								<li>
									<div class="dp-kpi-card dp-white">
										<span class="dp-assisted-sales-icon dpicon iconapp-job-transfer"></span>
										<div class="dp-assisted-sales-text">
											<h3>
												<?php
													echo esc_html($dplr_learnpress_synch != null 
														? $this->sanitize_kpi_values($dplr_learnpress_synch['count'] ?? 0)
														: 0);
												?></h3>
											<span><?php esc_html_e("Learnpress contacts", "doppler-form" ); ?></span>
										</div>
									</div>
								</li>
								<?php endif; ?>
							</ul>
						</div>
						<!-- end of KPIs -->
					</div>
					<div class="d-flex space-between col-sm-12 pl-0">
						<div class="col-sm-9 m-r-12">
							<!-- Statics -->
							<div class="dp-box-shadow dp-white m-b-36">
								<div class="m-t-24 m-l-24">
									<h3><?php esc_html_e("Forms Report","doppler-form");?></h3>
									<p><?php esc_html_e("Here you can see the performance of your forms","doppler-form");?></p>
								</div>
								<div class="dp-tabs-plans">
									<nav class="tabs-wrapper">
										<ul class="tabs-nav" data-tab-active="1">
											<li class="tab--item">
												<a href="#" class="tab--link active" data-tab="1"><?php esc_html_e("Conversion Rate","doppler-form");?></a>
											</li>
											<li class="tab--item">
												<a href="#" class="tab--link" data-tab="2"><?php esc_html_e("All Forms","doppler-form");?></a>
											</li>
										</ul>
									</nav>
								</div>
								<section class="tab--container col-sm-12 dp-white">
									<article class="tab--content active" id="tab-1">
										<div class="col-sm-12" id="doppler-forms-chart"></div>
									</article>
									<article class="tab--content" id="tab-2">
										<div class="dp-table-responsive dp-white dp-table-border">
											<table class="dp-table-multilogin dp-no-header-border-top dp-table-text-lg p-t-12 p-b-12 p-l-12 p-r-12">
												<thead>
													<tr>
														<th><?php echo esc_html(strtoupper(__("Name", "doppler-form"))); ?></th>
														<th><?php echo esc_html(strtoupper(__("Form Type", "doppler-form"))); ?></th>
														<th><?php echo esc_html(strtoupper(__("Impressions", "doppler-form"))); ?></th>
														<th><?php echo esc_html(strtoupper(__("Subscribed", "doppler-form"))); ?></th>
														<th><?php echo esc_html(strtoupper(__("Conversion", "doppler-form"))); ?></th>
													</tr>
												</thead>
												<tbody>
													<?php
													foreach ($forms as $dplr_form) {
														?>
														<tr>
															<td>
																<?php echo esc_html($dplr_form->name) ?>
															</td>
															<td>
																<?php echo $dplr_form->settings['form_doble_optin'] == 'yes' ? esc_html_e("Double Opt-In", "doppler-form" ) : esc_html_e("Simple Opt-In", "doppler-form" ); ?>
															</td>
															<td>
																<?php echo esc_html($dplr_form->events['Display'] ?? '0') ?>
															</td>
															<td>
																<?php echo esc_html($dplr_form->events['Submit'] ?? '0') ?>
															</td>
															<td>
																<?php $dplr_conversion_rate = ($dplr_form->events['Display'] ?? 0) > 0 
																	? rtrim(rtrim(number_format((($dplr_form->events['Submit'] ?? 0) / $dplr_form->events['Display']) * 100, 2), '0'), '.') . '%'
																	: '0%';
																
																	echo esc_html($dplr_conversion_rate);
																?>
															</td>
														</tr>
														<?php
													}
													?>
												</tbody>
											</table>
										</div>
									</article>
								</section>
							</div>
							<!--end of Statics -->
							<!-- Quick Access -->
							<div class="dp-box-shadow dp-white">
								<div class="dplr-quick-access">
									<h3><?php esc_html_e("Quick Access","doppler-form");?></h3>
									<p><?php esc_html_e("Access all the features from here","doppler-form");?></p>
									<div class="dplr-quick-access-wrapper m-t-12">
										<div class="dplr-item">
											<div class="dplr-circle">
												<img src="<?php echo esc_url(plugins_url( '/../img/checklist.svg', __FILE__ )); ?>" alt="<?php esc_attr_e("Doppler Forms","doppler-form");?>">
											</div>
											<div class="dplr-texts">
												<p><?php esc_html_e("Doppler Forms","doppler-form");?></p>
												<a href="<?php echo esc_url(admin_url('admin.php?page=doppler_forms_main'))?>"><?php esc_html_e("Access","doppler-form");?> →</a>
											</div>
										</div>
										<div class="dplr-item">
											<div class="dplr-circle">
												<img src="<?php echo esc_url(plugins_url( '/../img/search.svg', __FILE__ )); ?>" alt="<?php esc_attr_e("On-site tracking","doppler-form");?>">
											</div>
											<div class="dplr-texts">
												<p><?php esc_html_e("On-site tracking","doppler-form");?></p>
												<a href="<?php echo esc_url(admin_url('admin.php?page=doppler-settings'))?>"><?php esc_html_e("Access","doppler-form");?> →</a>
											</div>
										</div>
										<div class="dplr-item">
											<div class="dplr-circle">
												<img src="<?php echo esc_url(plugins_url( '/../img/bullet-points.svg', __FILE__ )); ?>" alt="<?php esc_attr_e("List Management","doppler-form");?>">
											</div>
											<div class="dplr-texts">
												<p><?php esc_html_e("List Management","doppler-form");?></p>
												<a href="<?php echo esc_url(admin_url('admin.php?page=doppler_list_management'))?>"><?php esc_html_e("Access","doppler-form");?> →</a>
											</div>
										</div>
										<?php if($dplr_learnpress_plugin_is_active):  
											$dplr_learnpress_synch = get_option('dplr_learnpress_subscribers_list');
										?>
										<div class="dplr-item">
											<div class="dplr-circle">
												<img src="<?php echo esc_url(plugins_url( '/../img/shopping.svg', __FILE__ )); ?>" alt="<?php esc_attr_e("Doppler for Learnpress","doppler-form");?>">
											</div>
											<div class="dplr-texts">
												<p><?php esc_html_e("Doppler for Learnpress","doppler-form");?></p>
												<a href="<?php echo esc_url(admin_url('admin.php?page=doppler_learnpress_menu'))?>"><?php esc_html_e("Access","doppler-form");?> →</a>
											</div>
										</div>
										<?php endif; ?>
										<?php if($dplr_woocommerce_plugin_is_active):
											$dplr_woocommerce_synch = get_option('dplrwoo_last_synch');	
										?>
										<div class="dplr-item">
											<div class="dplr-circle">
												<img src="<?php echo esc_url(plugins_url( '/../img/shopping.svg', __FILE__ )); ?>" alt="<?php esc_attr_e("Doppler for Woocommerce","doppler-form");?>">
											</div>
											<div class="dplr-texts">
												<p><?php esc_html_e("Doppler for Woocommerce","doppler-form");?></p>
												<a href="<?php echo esc_url(admin_url('admin.php?page=doppler_woocommerce_menu'))?>"><?php esc_html_e("Access","doppler-form");?> →</a>
											</div>
										</div>
										<?php endif; ?>
									</div>
								</div>
							</div>
							<!-- end of Quick Access -->
						</div>
						<div class="col-sm-3">
							<!-- Helpfull links -->
							<div class="dp-box-shadow dp-white m-b-36">
								<div class="dplr-wrapper-help-links">
									<ul>
										<li>
											<a href="<?php esc_html_e("https://help.fromdoppler.com/en/","doppler-form");?>">
												<div class="dplr-circle">
													<img src="<?php echo esc_url(plugins_url( '/../img/headphones.svg', __FILE__ )); ?>" alt="<?php esc_attr_e("Help Center","doppler-form");?>">
												</div>
												<span><?php esc_html_e("Help Center","doppler-form");?></span>
											</a>
										</li>
										<li>
											<a href="https://academy.fromdoppler.com/?utm_source=wordpress-plugin">
												<div class="dplr-circle">
													<img src="<?php echo esc_url(plugins_url( '/../img/hat.svg', __FILE__ )); ?>" alt="<?php esc_attr_e("Academy","doppler-form");?>">
												</div>
												<span><?php esc_html_e("Academy","doppler-form");?></span>
											</a>
										</li>
										<li>
											<a href="https://blog.fromdoppler.com/?utm_source=wordpress-plugin">
												<div class="dplr-circle">
													<img src="<?php echo esc_url(plugins_url( '/../img/pencil.svg', __FILE__ )); ?>" alt="<?php esc_attr_e("Blog","doppler-form");?>">
												</div>
												<span><?php esc_html_e("Blog","doppler-form");?></span>
											</a>
										</li>
										<li  style="border-bottom: 0px;">
											<a href="https://www.youtube.com/@FromDopplerYT">
												<div class="dplr-circle">
													<img src="<?php echo esc_url(plugins_url( '/../img/youtube.svg', __FILE__ )); ?>" alt="<?php esc_attr_e("Youtube","doppler-form");?>">
												</div>
												<span><?php esc_html_e("Youtube","doppler-form");?></span>
											</a>
										</li>
									</ul>
								</div>
							</div>
							<!--end of Helpfull links -->
							<!-- Plugin Extensions -->
							<div class="dp-box-shadow dplr-extensions dp-white p-t-12 p-b-12 p-l-12 p-r-12">
								<h3><?php esc_html_e("Extensions","doppler-form");?></h3>
								<div class="extension-item m-t-24">
									<div class="dp-rowflex p-r-24 p-l-12">
										<div class="dplr-circle">
											<img src="<?php echo esc_url(plugins_url( '/../img/shopping-violet.svg', __FILE__ )); ?>" alt="<?php esc_attr_e("Doppler for Woocommerce","doppler-form");?>">
										</div>
										<div class="col-sm-9">
											<h4><?php esc_html_e('Doppler for WooCommerce', 'doppler-form')?></h4>
											<p>
												<?php 
													if($dplr_woocommerce_plugin_is_active):  
														esc_html_e('Successfully Instaled', 'doppler-form'); ?>
													<?php else:
														esc_html_e('Extension deactivated', 'doppler-form');
													endif;
												?>
											</p>
										</div>
									</div>
									<?php 
									if(!$dplr_woocommerce_plugin_is_active): ?>
										<?php if(!$this->extension_manager->has_dependency('doppler-for-woocommerce')): ?>
											<?php /* translators: %s: plugin name with link. */ ?>
											<p class="text-italic"><?php echo wp_kses_post( sprintf( __( 'You should have %s installed and active first.', 'doppler-form' ), '<a href="https://wordpress.org/plugins/woocommerce/">WooCommerce plugin</a>' ) ); ?></p>
										<?php else: ?>
											<button type="button" class="dp-button button-big primary-green button-small dp-install m-t-12 col-sm-12"
													data-extension="doppler-for-woocommerce"
													data-nonce="<?php echo esc_attr(wp_create_nonce('dplr-install-extension-nonce')); ?>"
													data-click-action=<?php $this->extension_manager->is_plugin_installed('doppler-for-woocommerce') ? esc_attr_e('Activating', 'doppler-form') : esc_attr_e('Installing', 'doppler-form') ?>>
												<?php $this->extension_manager->is_plugin_installed('doppler-for-woocommerce') ? esc_html_e('Activate', 'doppler-form') : esc_html_e('Install', 'doppler-form') ?>
											</button>
										<?php endif; ?>
									<?php else:
										if(!$this->extension_manager->has_latest_plugin_version('doppler-for-woocommerce')): ?>
											<button type="button" class="dp-button button-big primary-green button-small dp-install m-t-12 col-sm-12" 
													data-extension="doppler-for-woocommerce"
													data-nonce="<?php echo esc_attr(wp_create_nonce('dplr-install-extension-nonce')); ?>"
													data-click-action=<?php esc_attr_e('Updating', 'doppler-form') ?>>
												<?php esc_html_e('Update Version', 'doppler-form') ?>
											</button>
										<?php endif; ?>
									<?php endif; ?>
								</div>
								<div class="extension-item m-t-24">
									<div class="dp-rowflex p-r-24 p-l-12">
										<div class="dplr-circle">
											<img src="<?php echo esc_url(plugins_url( '/../img/shopping-violet.svg', __FILE__ )); ?>" alt="<?php esc_attr_e('Doppler for LearnPress', 'doppler-form')?>">
										</div>
										<div class="col-sm-9">
											<h4><?php esc_html_e('Doppler for LearnPress', 'doppler-form');?></h4>
											<p>
												<?php 
													if($dplr_learnpress_plugin_is_active):  
														esc_html_e('Successfully Instaled', 'doppler-form'); ?>
													<?php else:
														esc_html_e('Extension deactivated', 'doppler-form');
													endif;
												?>
											</p>
										</div>
									</div>
									<?php 
									if(!$dplr_learnpress_plugin_is_active): ?>
										<?php if(!$this->extension_manager->has_dependency('doppler-for-learnpress')): ?>
											<?php /* translators: %s: plugin name with link. */ ?>
											<p class="text-italic"><?php echo wp_kses_post( sprintf( __( 'You should have %s installed and active first.', 'doppler-form' ), '<a href="https://wordpress.org/plugins/learnpress/">LearnPress plugin</a>' ) ); ?></p>
										<?php else: ?>
											<button type="button" class="dp-button button-big primary-green button-small dp-install m-t-12 col-sm-12" 
													data-extension="doppler-for-learnpress"
													data-nonce="<?php echo esc_attr(wp_create_nonce('dplr-install-extension-nonce')); ?>"
													ata-click-action=<?php $this->extension_manager->is_plugin_installed('doppler-for-learnpress') ? esc_attr_e('Activating', 'doppler-form') : esc_attr_e('Installing', 'doppler-form') ?>>
												<?php $this->extension_manager->is_plugin_installed('doppler-for-learnpress') ? esc_html_e('Activate', 'doppler-form') : esc_html_e('Install', 'doppler-form') ?>
											</button>
										<?php endif; ?>
									<?php else:
										if(!$this->extension_manager->has_latest_plugin_version('doppler-for-learnpress')): ?>
											<button type="button" class="dp-button button-big primary-green button-small dp-install m-t-12 col-sm-12"
													data-extension="doppler-for-learnpress"
													data-nonce="<?php echo esc_attr(wp_create_nonce('dplr-install-extension-nonce')); ?>"
													data-click-action=<?php esc_attr_e('Updating', 'doppler-form') ?>>
												<?php esc_html_e('Update Version', 'doppler-form') ?>
											</button>
										<?php endif; ?>
									<?php endif; ?>
								</div>
							</div>
							<!--end of Plugin Extensions -->
						</div>
					</div>
				</div>
			</div>
		<?php
		} else {
		?>
			<div id="dplr-sign-in">
				<header class="hero-banner">
					<div class="dp-rowflex">
						<div class="col-sm-12 col-md-12 col-lg-12">
							<h2><?php esc_html_e("Connect your WordPress Forms with Doppler", "doppler-form" ); ?></h2>
						</div>
						<div class="col-sm-6">
							<p><?php esc_html_e("Create Subscription Forms that respect your Website styles and automatically send your new Subscribers from WordPress to Doppler Lists.","doppler-form") ;?></p>
							<p><?php esc_html_e("Don't have an account yet?","doppler-form") ;?> <a href="#" id="dplr-sign-up-link" target="_blank"><?php esc_html_e("Sign up for free", "doppler-form"); ?></a></p>
						</div>
					</div>
					<span class="arrow"></span>
				</header>
				<div class="col-lg-6 col-lmd-6 col-sm-8 awa-form">
					<form method="POST" action="options.php" id="dplr-connect-form" class="form-horizontal <?php echo $error?'error':''; ?>">
						<?php 
							wp_nonce_field( 'dplr-connect-nonce', 'dplr_nonce_field' );
							settings_fields('dplr_plugin_options');
						?>
						<label for="email" class="labelcontrol">
							<?php esc_html_e('Username', 'doppler-form');?>
							<input type="email"
								id="user-account"
								class="validation visible box-shado-0"
								name="dplr_settings[dplr_option_useraccount];"
								aria-invalid="<?php echo (isset($errorMessages['user_account']) || $error) ? "true" : "false" ?>"
								data-validation-email="<?php esc_attr_e("Ouch! Enter a valid Email.", "doppler-form"); ?>"
								placeholder=""
								autocomplete="off"
								value="<?php echo isset($options['dplr_option_useraccount']) ? esc_attr($options['dplr_option_useraccount']) : ''; ?>"/>
							<div class="assistance-wrap">
								<span><?php echo (isset($errorMessages['user_account']) || $error) ? esc_html_e("Ouch! Enter a valid Email.", "doppler-form") :  ""; ?></span>
							</div>
						</label>

						<label for="name" class="labelcontrol">
							<div class="dp-icons-group" style="justify-content: start">
								<span class="m-r-12">API Key</span>
								<div class="dp-tooltip-container ">
									<div class="ms-icon icon-info-icon">
									</div>
									<div class="dp-tooltip-top dp-tooltip-top-bubble">
										<span>
											<?php esc_html_e("How do you find your API Key? Press", "doppler-form"); ?>
											<a href="<?php esc_html_e('https://help.fromdoppler.com/en/where-do-i-find-my-api-key/?utm_source=landing&utm_medium=integracion&utm_campaign=wordpress', 'doppler-form')?>">
												<?php esc_html_e("HELP", "doppler-form"); ?>
											</a>
										</span>
									</div>
								</div>
							</div>
							<input type="text"
								id="api-key"
								name="dplr_settings[dplr_option_apikey];"
								data-validation-required="<?php esc_attr_e("Ouch! The field is empty.", "doppler-form"); ?>"
								class="visible box-shado-0"
								autocomplete="off"
								placeholder=""
								aria-invalid="<?php echo (isset($errorMessages['api_key']) || $error) ? "true" : "false" ?>"
								value="<?php echo (isset($options['dplr_option_apikey'])) ? esc_attr($options['dplr_option_apikey']) : '' ?>" />
							<div class="assistance-wrap">
							<span><?php echo (isset($errorMessages['api_key']) || $error) ? esc_html_e("Ouch! Enter a valid Email.", "doppler-form") :  ""?></span>
							</div>
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
				<p class="m-t-24">
					<?php esc_html_e("Do you have any doubts about how to connect your Forms with Doppler? Press", "doppler-form")?>
					<a href="<?php esc_attr_e('https://help.fromdoppler.com/en/how-to-integrate-wordpress-forms-with-doppler?utm_source=landing&utm_medium=integracion&utm_campaign=wordpress','doppler-form') ?>" target="_blank"><?php esc_html_e('HELP','doppler-form') ?></a>.
				</p>
			</div>
						
			<div id="dplr-sign-up" class="d-none dp-container">
				<main class="confirmation-main">
					<header class="confirmation-header">
						<h1 class="logo-doppler-new">
							<a target="_blank" href="https://www.fromdoppler.com/" rel="noopener noreferrer">Doppler</a>
						</h1>
					</header>
					<div class="dp-rowflex">
						<div class="col-sm-3"></div>
						<div class="col-sm-6">
							<header class="hero-banner">
								<div class="dp-rowflex">
									<div class="col-sm-12">
										<h2><?php esc_html_e("Connect your WordPress Forms with Doppler", "doppler-form" ); ?></h2>
									</div>
									<div class="col-sm-12">
										<p><?php esc_html_e("Create Subscription Forms that respect your Website styles and automatically send your new Subscribers from WordPress to Doppler Lists.","doppler-form") ;?></p>
										<p><?php esc_html_e("Already have an account?","doppler-form") ;?> <a href="#" id="dplr-sign-in-link" target="_blank"><?php esc_html_e("LOG IN", "doppler-form"); ?></a></p>
									</div>
								</div>
								<span class="arrow"></span>
							</header>
							<div class="awa-form">
								<form method="POST" action="options.php" id="dplr-sign-up-form" class="signup-form">
									<?php wp_nonce_field( 'dplr-signup-nonce', 'dplr_signup_nonce_field' ); ?>
									<fieldset>
										<ul class="field-group">
											<li class="field-item awa-form field-item--50">
												<label for="firstname" class="labelcontrol"><?php esc_html_e("First name","doppler-form") ;?>:
													<input name="firstname" type="text" id="firstname" maxlength="100" placeholder="<?php esc_attr_e("Enter your first name","doppler-form") ;?>" required
														data-validation-required="<?php esc_attr_e("Ouch! The field is empty.", "doppler-form"); ?>"
														data-validation-minlength="<?php esc_attr_e("Ouch! Write at least two characters.", "doppler-form"); ?>"
														aria-invalid="false" class="box-shado-0">
													<div class="assistance-wrap d-none"><span></span></div>
												</label>
											</li>
											<li class="field-item awa-form field-item--50">
												<label for="lastname" class="labelcontrol"><?php esc_html_e("Last name","doppler-form") ;?>:
													<input name="lastname" type="text" id="lastname" maxlength="100" placeholder="<?php esc_attr_e("Enter your last name","doppler-form") ;?>" required
														data-validation-required="<?php esc_attr_e("Ouch! The field is empty.", "doppler-form"); ?>"
														data-validation-minlength="<?php esc_attr_e("Ouch! Write at least two characters.", "doppler-form"); ?>"
														aria-invalid="false" class="box-shado-0">
													<div class="assistance-wrap d-none"><span></span></div>
												</label>
											</li>
											<li class="field-item awa-form">
												<label for="phone" class="labelcontrol"><?php esc_html_e("Phone number","doppler-form") ;?>:
													<input type="tel" class="phone-doppler box-shado-0" name="phone" placeholder="011 15-2345-6789"
														data-validation-phone-invalid="<?php esc_attr_e('Enter a valid phone.', 'doppler-form'); ?>"
														data-validation-phone-short="<?php esc_attr_e('Ouch! The phone number is too short.', 'doppler-form'); ?>"
														data-validation-phone-long="<?php esc_attr_e('Ouch! The phone number is too long.', 'doppler-form'); ?>"
														aria-invalid="false">
													<div id="country-selector"></div>
													<div class="assistance-wrap d-none"><span></span></div>
												</label>
											</li>
										</ul>
									</fieldset>
									<fieldset>
										<ul class="field-group">
											<li class="field-item awa-form">
												<label for="email" class="labelcontrol"><?php esc_html_e("Business email","doppler-form") ;?>:
													<input name="email" type="text" id="email" placeholder="<?php esc_attr_e("Enter your email. You must have access","doppler-form") ;?>"
														autocomplete="off" maxlength="200" required
														data-validation-email-invalid="<?php esc_attr_e("Enter a valid Email.", "doppler-form"); ?>"
														aria-invalid="false" class="box-shado-0">
													<div class="assistance-wrap d-none"><span></span></div>
												</label>
											</li>
											<li class="field-item awa-form">
												<label for="password" class="labelpassword" data-required="true">
													<?php esc_html_e("Password","doppler-form") ;?>: 
													<div class="dp-wrap-eyed">
														<button type="button" id="dplr-hide-show-pw" class="show-hide ms-icon icon-view" tabindex="-1"></button>
														<input name="password" type="password" autocomplete="new-password" id="dplr-sign-up-password" class="box-shado-0"
															placeholder="<?php esc_attr_e("Create your password","doppler-form") ;?>" spellcheck="false" badinput="false" autocapitalize="off" required>
														<div class="wrapper-password">
															<p class="password-message">
																<span class="dp-message--default" id="dplr-signup-minimum-length"><?php esc_html_e("8 characters minimum", "doppler-form"); ?></span>
																<span class="dp-message--default" id="dplr-signup-one-letter"><?php esc_html_e("One letter", "doppler-form"); ?></span>
																<span class="dp-message--default" id="dplr-signup-one-number"><?php esc_html_e("One number", "doppler-form"); ?></span>
																<span class="dp-message--secure d-none"><?php esc_html_e("Your Password is secure!", "doppler-form"); ?></span>
															</p>
														</div>
													</div>
												</label>
											</li>
										</ul>
									</fieldset>
									<fieldset>
										<ul class="field-group dp-items-accept">
											<li class="field-item awa-form label--policy">
												<label for="dplr-signup-accept-privacy-policies" class="dp-label-checkbox" required aria-errormessage="erraccept_privacy_policies" aria-invalid="false">
													<input name="accept_privacy_policies" type="checkbox" id="dplr-signup-accept-privacy-policies" value="false">
													<span><?php esc_html_e("I accept the", "doppler-form"); ?> <a href="<?php esc_attr_e("https://www.fromdoppler.com/en/legal/privacy","doppler-form") ;?>" target="_blank"><?php esc_html_e("Privacy Policy", "doppler-form"); ?></a>.</span>
												</label>
												<div id="erraccept_privacy_policies" class="dp-errormessage d-none"><p><?php esc_html_e("Ouch! You haven't accepted the Doppler's Privacy Policy.", "doppler-form"); ?></p></div>
											</li>
											<li class="field-item awa-form">
												<label for="dplr-signup-accept-promotions" class="dp-label-checkbox">
													<input name="accept_promotions" type="checkbox" id="dplr-signup-accept-promotions" value="false">
													<span><?php esc_html_e("I want to receive news and promotions.", "doppler-form"); ?></span>
												</label>
											</li>
										</ul>
									</fieldset>
									<fieldset class="btn-login">
										<button type="submit" class="dp-button button-medium primary-green button--round"><?php esc_html_e("CREATE FREE ACCOUNT", "doppler-form"); ?></button>
									</fieldset>
									<div id="dplr-signup-server-error" class="m-t-12 dp-wrap-message dp-wrap-cancel d-none" role="alert" aria-label="cancel">
										<span class="dp-message-icon"></span>
										<div class="dp-content-message dp-content-full">
											<p></p>
											<a href="#" class="dp-message-link dplr-message-dismiss"><?php echo esc_html( strtoupper( __( 'Got it', 'doppler-form' ) ) ); ?></a>
										</div>
									</div>
								</form>
							</div>
							<ul id="legal-accordion" class="dp-accordion content-legal">
								<li>
									<span class="dp-accordion-thumb"><?php esc_html_e("Read about data protection in Doppler.", "doppler-form"); ?></span>
									<div class="dp-accordion-panel d-none">
										<p><?php esc_html_e("Doppler informs you that the personal data you provide by completing this form will be treated by Doppler LLC as responsible for this web site.", "doppler-form"); ?></p>
										<p><strong><?php esc_html_e("Purpose", "doppler-form"); ?>:</strong> <?php esc_html_e("Sign you up into our platform and provide the services that you require.", "doppler-form"); ?></p>
										<p><strong><?php esc_html_e("Legitimation", "doppler-form"); ?>:</strong> <?php esc_html_e("Consent of the applicant.", "doppler-form"); ?></p>
										<p><strong><?php esc_html_e("Recipients", "doppler-form"); ?>:</strong><?php esc_html_e("Your data will be saved by Doppler, Zoho as CRM, Google as the provider of reCAPTCHA service, Digital Ocean, Cogeco Peer1 and Rackspace as hosting companies.", "doppler-form"); ?></p>
										<p><strong><?php esc_html_e("Additional information", "doppler-form"); ?>:</strong> <?php esc_html_e("In Doppler's", "doppler-form"); ?> <a href="<?php esc_attr_e("https://www.fromdoppler.com/en/legal/privacy","doppler-form") ;?>" target="_blank" style="display: inline;"><?php esc_html_e("Privacy Policy", "doppler-form"); ?></a> <?php esc_html_e("you'll find additional information about the data storage and use of your personal information, including information on access, conservation, rectification, deletion, security, cross-border data transfers and other issues.", "doppler-form"); ?></p>
									</div>
								</li>
							</ul>
							<footer>
								<small>
									<p>© <?php esc_html(gmdate('Y')); ?> <?php esc_html_e("Doppler LLC. All rights reserved.", "doppler-form"); ?> <a target="_blank" href="<?php esc_attr_e("https://www.fromdoppler.com/en/legal/privacy","doppler-form") ;?>"><?php esc_html_e("Privacy Policy & Legals", "doppler-form"); ?></a>.</p>
								</small>
							</footer>
						</div>
					</div>
				</main>
			</div>
			<div id="dplr-sign-up-success" class="d-none dp-container">
				<main class="confirmation-main">
					<header class="confirmation-header">
						<h1 class="logo-doppler-new">
							<a target="_blank" href="https://www.fromdoppler.com/" rel="noopener noreferrer">Doppler</a>
						</h1>
					</header>
					<article class="confirmation-article">
						<h1><?php esc_html_e("You’re just one step away from scaling your strategy", "doppler-form"); ?>
							<br>
							<br>
							<?php esc_html_e("Check your email", "doppler-form"); ?>
						</h1>
						<h2><?php esc_html_e("We sent an email to", "doppler-form"); ?> <strong id="dplr-created-account"></strong> <?php esc_html_e("to validate your account.", "doppler-form"); ?>
							<br>
							<?php esc_html_e("You just need to open it and click the confirmation button to activate it.", "doppler-form"); ?>
						</h2>
						<h2><?php esc_html_e("Once activated, you can start creating Campaigns, Automations and exploring", "doppler-form"); ?>
							<br><?php esc_html_e("all the features of", "doppler-form"); ?> <strong>Doppler.</strong>
						</h2>
						<p><?php esc_html_e("Go back to", "doppler-form"); ?> <a href="<?php echo esc_url(admin_url('admin.php?page=doppler_forms_menu')); ?>"><?php esc_html_e("sign in", "doppler-form"); ?></a></p>
						<div class="dp-divisor-line">
							<span class="dp-line"></span>
						</div>
						<div class="dp-icons-content">
							<div class="dp-icons-divisor">
								<span class="dp-rectangle--violet"></span>
								<span class="dp-rectangle--orange"></span>
							</div>
						</div>
						<p class="dp-text--italic"><?php esc_html_e("Do you have doubts?", "doppler-form"); ?> <a href="https://fromdoppler.com/es/contact/?utm_source=direct" rel="noopener" target="_blank"><?php esc_html_e("Contact us", "doppler-form"); ?></a> <?php esc_html_e("and we will help you", "doppler-form"); ?></p>
					</article>
				</main>
			</div>
		<?php
		}
		?>
		<div id="dplr-dialog-confirm" title="<?php esc_html_e('Are you sure you want to uninstall the extension?', 'doppler-form'); ?>">
			<p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span> <?php esc_html_e('This will deactivate and uninstall the plugin.', 'doppler-form')?></p>
		</div>
	</div>
</section>

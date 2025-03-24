<section class="dplr_settings dp-library">
	<div class="dplr_connect">
		<?php
		if ($connected) {
		?>
			<div class="dp-container m-t-24">
				<div class="dp-rowflex">
					<div class="col-sm-12">
						<div class="dp-box-shadow dp-white m-t-12 p-t-36 p-b-36 p-l-36 p-r-36">
							<div class="dp-rowflex">
								<div class="col-sm-10">
									<h2><?php _e("Welcome!", "doppler-form" ); ?></h2>
								</div>
								<div class="col-sm-2 text-align--right">
									<form method="POST" action="options.php" id="dplr-disconnect-form">
										<button type="submit" class="dp-button button-medium primary-green">
											<?php _e("Disconnect", "doppler-form"); ?>
										</button>
									</form>
								</div>
								<div class="col-sm-12">
									<p>
										<?php _e("Here you can view information about the performance of your forms and the status of your integrations.","doppler-form");?>
									</p>
								</div>
								<div class="col-sm-6 dp-icon-wrapper m-t-24">
									<span>
										<strong> <?php _e("Account","doppler-form");?>:</strong>
										<span> <?php echo $options['dplr_option_useraccount']?></span>
									</span>
									<span>
										<strong> <?php _e("Status","doppler-form");?>:</strong>
										<span> <?php _e("Active","doppler-form")?></span>
									</span>
								</div>
							</div>
						</div>
						<!-- KPIs -->
						<div class="dp-wrapper-as-kpi">
							<ul class="kpi-ul">
								<li>
									<div class="dp-kpi-card dp-white">
										<span class="dp-assisted-sales-icon dpicon iconapp-web-list"></span>
										<div class="dp-assisted-sales-text">
											<h3>
												<?php echo count($forms); ?>
											</h3>
											<span><?php _e("Forms", "doppler-form" ); ?></span>
										</div>
									</div>
								</li>
								<li>
									<div class="dp-kpi-card dp-white">
										<span class="dp-assisted-sales-icon dpicon iconapp-iris-recognition"></span>
										<div class="dp-assisted-sales-text">
											<h3>
												<?php 
													$totalDisplays = 0;
													foreach ($forms as $form) {
														$totalDisplays += $form->events['Display'] ?? 0;
													}
													echo $this->sanitize_kpi_values($totalDisplays);
												?>
											</h3>
											<span><?php _e("Impressions", "doppler-form" ); ?></span>
										</div>
									</div>
								</li>
								<li>
									<div class="dp-kpi-card dp-white">
										<span class="dp-assisted-sales-icon dpicon iconapp-bell1"></span>
										<div class="dp-assisted-sales-text">
											<h3>
											<?php 
													$totalSubmits = 0;
													foreach ($forms as $form) {
														$totalSubmits += $form->events['Submit'] ?? 0;
													}
													echo $this->sanitize_kpi_values($totalSubmits);
												?>
											</h3>
											<span><?php _e("Subscribed", "doppler-form" ); ?></span>
										</div>
									</div>
								</li>
								<?php if($this->extension_manager->is_active('doppler-for-woocommerce')):
									$woocommerce_synch = get_option('dplrwoo_last_synch');
								?>
									<li>
										<div class="dp-kpi-card dp-white">
											<span class="dp-assisted-sales-icon dpicon iconapp-job-transfer"></span>
											<div class="dp-assisted-sales-text">
												<h3>
													<?php echo $woocommerce_synch != null
															? $this->sanitize_kpi_values($woocommerce_synch['contacts']['counter'] ?? 0 + $woocommerce_synch['buyers']['counter'] ?? 0)
															: 0;
													?>
												</h3>
												<span><?php _e("Woocommerce contacts", "doppler-form" ); ?></span>
											</div>
										</div>
									</li>
								<?php endif; ?>
								<?php if($this->extension_manager->is_active('doppler-for-learnpress')):
									$learnpress_synch = get_option('dplr_learnpress_subscribers_list');
								?>
								<li>
									<div class="dp-kpi-card dp-white">
										<span class="dp-assisted-sales-icon dpicon iconapp-job-transfer"></span>
										<div class="dp-assisted-sales-text">
											<h3>
												<?php
													echo $learnpress_synch != null 
														? $this->sanitize_kpi_values($learnpress_synch['count'] ?? 0)
														: 0
												?></h3>
											<span><?php _e("Learnpress contacts", "doppler-form" ); ?></span>
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
									<h3><?php _e("Forms Report","doppler-form");?></h3>
									<p><?php _e("Here you can see the performance of your forms","doppler-form");?></p>
								</div>
								<div class="dp-tabs-plans">
									<nav class="tabs-wrapper">
										<ul class="tabs-nav" data-tab-active="1">
											<li class="tab--item">
												<a href="#" class="tab--link active" data-tab="1"><?php _e("Conversion Rate","doppler-form");?></a>
											</li>
											<li class="tab--item">
												<a href="#" class="tab--link" data-tab="2"><?php _e("All Forms","doppler-form");?></a>
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
														<th><?php echo strtoupper(__("Name", "doppler-form")); ?></th>
														<th><?php echo strtoupper(__("Form Type", "doppler-form")); ?></th>
														<th><?php echo strtoupper(__("Impressions", "doppler-form")); ?></th>
														<th><?php echo strtoupper(__("Subscribed", "doppler-form")); ?></th>
														<th><?php echo strtoupper(__("Conversion", "doppler-form")); ?></th>
													</tr>
												</thead>
												<tbody>
													<?php
													foreach ($forms as $form) {
														?>
														<tr>
															<td>
																<?= $form->name ?>
															</td>
															<td>
																<?php echo $form->settings['form_doble_optin'] == 'yes' ? _e("Double Opt-In", "doppler-form" ) : _e("Simple Opt-In", "doppler-form" ); ?>
															</td>
															<td>
																<?= $form->events['Display'] ?? '0' ?>
															</td>
															<td>
																<?= $form->events['Submit'] ?? '0' ?>
															</td>
															<td>
																<?php echo ($form->events['Display'] ?? 0) > 0 
																	? rtrim(rtrim(number_format(($form->events['Submit'] / $form->events['Display']) * 100, 2), '0'), '.') . '%'
																	: '0%';
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
									<h3><?php _e("Quick Access","doppler-form");?></h3>
									<p><?php _e("Access all the features from here","doppler-form");?></p>
									<div class="dplr-quick-access-wrapper m-t-12">
										<div class="dplr-item">
											<div class="dplr-circle">
												<img src="<?= plugins_url( '/../img/checklist.svg', __FILE__ ); ?>" alt="<?php _e("Doppler Forms","doppler-form");?>">
											</div>
											<div class="dplr-texts">
												<p><?php _e("Doppler Forms","doppler-form");?></p>
												<a href="<?php echo admin_url('admin.php?page=doppler_forms_main')?>"><?php _e("Access","doppler-form");?> →</a>
											</div>
										</div>
										<div class="dplr-item">
											<div class="dplr-circle">
												<img src="<?= plugins_url( '/../img/search.svg', __FILE__ ); ?>" alt="<?php _e("On-site tracking","doppler-form");?>">
											</div>
											<div class="dplr-texts">
												<p><?php _e("On-site tracking","doppler-form");?></p>
												<a href="<?php echo admin_url('admin.php?page=doppler-data-hub')?>"><?php _e("Access","doppler-form");?> →</a>
											</div>
										</div>
										<div class="dplr-item">
											<div class="dplr-circle">
												<img src="<?= plugins_url( '/../img/bullet-points.svg', __FILE__ ); ?>" alt="<?php _e("List Management","doppler-form");?>">
											</div>
											<div class="dplr-texts">
												<p><?php _e("List Management","doppler-form");?></p>
												<a href="<?php echo admin_url('admin.php?page=doppler_list_management')?>"><?php _e("Access","doppler-form");?> →</a>
											</div>
										</div>
										<?php if($this->extension_manager->is_active('doppler-for-learnpress')):  
											$learnpress_synch = get_option('dplr_learnpress_subscribers_list');
										?>
										<div class="dplr-item">
											<div class="dplr-circle">
												<img src="<?= plugins_url( '/../img/shopping.svg', __FILE__ ); ?>" alt="<?php _e("Doppler for Learnpress","doppler-form");?>">
											</div>
											<div class="dplr-texts">
												<p><?php _e("Doppler for Learnpress","doppler-form");?></p>
												<a href="<?php echo admin_url('admin.php?page=doppler_learnpress_menu')?>"><?php _e("Access","doppler-form");?> →</a>
											</div>
										</div>
										<?php endif; ?>
										<?php if($this->extension_manager->is_active('doppler-for-woocommerce')):
											$woocommerce_synch = get_option('dplrwoo_last_synch');	
										?>
										<div class="dplr-item">
											<div class="dplr-circle">
												<img src="<?= plugins_url( '/../img/shopping.svg', __FILE__ ); ?>" alt="<?php _e("Doppler for Woocommerce","doppler-form");?>">
											</div>
											<div class="dplr-texts">
												<p><?php _e("Doppler for Woocommerce","doppler-form");?></p>
												<a href="<?php echo admin_url('admin.php?page=doppler_woocommerce_menu')?>"><?php _e("Access","doppler-form");?> →</a>
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
											<a href="<?php _e("https://help.fromdoppler.com/en/","doppler-form");?>">
												<div class="dplr-circle">
													<img src="<?= plugins_url( '/../img/headphones.svg', __FILE__ ); ?>" alt="<?php _e("Help Center","doppler-form");?>">
												</div>
												<span><?php _e("Help Center","doppler-form");?></span>
											</a>
										</li>
										<li>
											<a href="https://academy.fromdoppler.com/?utm_source=wordpress-plugin">
												<div class="dplr-circle">
													<img src="<?= plugins_url( '/../img/hat.svg', __FILE__ ); ?>" alt="<?php _e("Academy","doppler-form");?>">
												</div>
												<span><?php _e("Academy","doppler-form");?></span>
											</a>
										</li>
										<li>
											<a href="https://blog.fromdoppler.com/?utm_source=wordpress-plugin">
												<div class="dplr-circle">
													<img src="<?= plugins_url( '/../img/pencil.svg', __FILE__ ); ?>" alt="<?php _e("Blog","doppler-form");?>">
												</div>
												<span><?php _e("Blog","doppler-form");?></span>
											</a>
										</li>
										<li  style="border-bottom: 0px;">
											<a href="https://www.youtube.com/@FromDopplerYT">
												<div class="dplr-circle">
													<img src="<?= plugins_url( '/../img/youtube.svg', __FILE__ ); ?>" alt="<?php _e("Youtube","doppler-form");?>">
												</div>
												<span><?php _e("Youtube","doppler-form");?></span>
											</a>
										</li>
									</ul>
								</div>
							</div>
							<!--end of Helpfull links -->
							<!-- Plugin Extensions -->
							<div class="dp-box-shadow dplr-extensions dp-white p-t-12 p-b-12 p-l-12 p-r-12">
								<h3><?php _e("Extensions","doppler-form");?></h3>
								<div class="extension-item m-t-24">
									<div class="dp-rowflex p-r-24 p-l-12">
										<div class="dplr-circle">
											<img src="<?= plugins_url( '/../img/shopping-violet.svg', __FILE__ ); ?>" alt="<?php _e("Doppler for Woocommerce","doppler-form");?>">
										</div>
										<div class="col-sm-9">
											<h4><?php _e('Doppler for WooCommerce', 'doppler-form')?></h4>
											<p>
												<?php 
													if($this->extension_manager->is_active('doppler-for-woocommerce')):  
														_e('Successfully Instaled', 'doppler-form'); ?>
													<?php else:
														_e('Extension deactivated', 'doppler-form');
													endif;
												?>
											</p>
										</div>
									</div>
									<?php 
									if(!$this->extension_manager->is_active('doppler-for-woocommerce')): ?>
										<?php if(!$this->extension_manager->has_dependency('doppler-for-woocommerce')): ?>
											<p class="text-italic"><?php _e('You should have <a href="https://wordpress.org/plugins/woocommerce/">WooCommerce plugin</a> installed and active first.', 'doppler-form')?></p>
										<?php else: ?>
											<button type="button" class="dp-button button-big primary-green button-small dp-install m-t-12 col-sm-12"
													data-extension="doppler-for-woocommerce" data-click-action=<?php $this->extension_manager->is_plugin_installed('doppler-for-woocommerce') ? _e('Activating', 'doppler-form') : _e('Installing', 'doppler-form') ?>>
												<?php $this->extension_manager->is_plugin_installed('doppler-for-woocommerce') ? _e('Activate', 'doppler-form') : _e('Install', 'doppler-form') ?>
											</button>
										<?php endif; ?>
									<?php else:
										if(!$this->extension_manager->has_latest_plugin_version('doppler-for-woocommerce')): ?>
											<button type="button" class="dp-button button-big primary-green button-small dp-install m-t-12 col-sm-12 button--loading" 
													data-extension="doppler-for-woocommerce" data-click-action=<?php _e('Updating', 'doppler-form') ?>>
												<?php _e('Update Version', 'doppler-form') ?>
											</button>
										<?php endif; ?>
									<?php endif; ?>
								</div>
								<div class="extension-item m-t-24">
									<div class="dp-rowflex p-r-24 p-l-12">
										<div class="dplr-circle">
											<img src="<?= plugins_url( '/../img/shopping-violet.svg', __FILE__ ); ?>" alt="<?php _e('Doppler for LearnPress', 'doppler-form')?>">
										</div>
										<div class="col-sm-9">
											<h4><?php _e('Doppler for LearnPress', 'doppler-form');?></h4>
											<p>
												<?php 
													if($this->extension_manager->is_active('doppler-for-learnpress')):  
														_e('Successfully Instaled', 'doppler-form'); ?>
													<?php else:
														_e('Extension deactivated', 'doppler-form');
													endif;
												?>
											</p>
										</div>
									</div>
									<?php 
									if(!$this->extension_manager->is_active('doppler-for-learnpress')): ?>
										<?php if(!$this->extension_manager->has_dependency('doppler-for-learnpress')): ?>
											<p class="text-italic"><?php _e('You should have <a href="https://wordpress.org/plugins/learnpress/">LearnPress plugin</a> installed and active first.', 'doppler-form')?></p>
										<?php else: ?>
											<button type="button" class="dp-button button-big primary-green button-small dp-install m-t-12 col-sm-12" 
													data-extension="doppler-for-learnpress" data-click-action=<?php $this->extension_manager->is_plugin_installed('doppler-for-learnpress') ? _e('Activating', 'doppler-form') : _e('Installing', 'doppler-form') ?>>
												<?php $this->extension_manager->is_plugin_installed('doppler-for-learnpress') ? _e('Activate', 'doppler-form') : _e('Install', 'doppler-form') ?>
											</button>
										<?php endif; ?>
									<?php else:
										if(!$this->extension_manager->has_latest_plugin_version('doppler-for-learnpress')): ?>
											<button type="button" class="dp-button button-big primary-green button-small dp-install m-t-12 col-sm-12" 
													data-extension="doppler-for-learnpress" data-click-action=<?php _e('Updating', 'doppler-form') ?>>
												<?php _e('Update Version', 'doppler-form') ?>
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
			<header class="hero-banner">
				<div class="dp-rowflex">
					<div class="col-sm-12 col-md-12 col-lg-12">
						<h2><?php _e("Connect your WordPress Forms with Doppler", "doppler-form" ); ?></h2>
					</div>
					<div class="col-sm-7">
						<p><?php _e("Create Subscription Forms that respect your Website styles and automatically send your new Subscribers from WordPress to Doppler Lists.","doppler-form") ;?></p>
					</div>
				</div>
				<span class="arrow"></span>
			</header>
			<div class="col-lg-6 col-lmd-6 col-sm-8 awa-form">
				<form method="POST" action="options.php" id="dplr-connect-form" class="form-horizontal <?= $error?'error':''; ?>">
					<?php settings_fields('dplr_plugin_options'); ?>
					<label for="email" class="labelcontrol">
						<?php _e('Username', 'doppler-form');?>
						<input type="email"
							id="user-account"
							class="validation visible"
							name="dplr_settings[dplr_option_useraccount];"
							aria-invalid="<?= (isset($errorMessages['user_account']) || $error) ? "true" : "false" ?>"
							data-validation-email="<?php _e("Ouch! Enter a valid Email.", "doppler-form"); ?>"
							placeholder=""
							autocomplete="off"
							value="<?php echo isset($options['dplr_option_useraccount'])? $options['dplr_option_useraccount']:'';?>"/>
						<div class="assistance-wrap">
							<span><?= (isset($errorMessages['user_account']) || $error) ? _e("Ouch! Enter a valid Email.", "doppler-form"):  ""?></span>
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
										<?php _e("How do you find your API Key? Press", "doppler-form"); ?>
										<a href="<?php _e('https://help.fromdoppler.com/en/where-do-i-find-my-api-key/?utm_source=landing&utm_medium=integracion&utm_campaign=wordpress', 'doppler-form')?>">
											<?php _e("HELP", "doppler-form"); ?>
										</a>
									</span>
								</div>
							</div>
						</div>
						<input type="text"
							id="api-key"
							name="dplr_settings[dplr_option_apikey];"
							data-validation-required="<?php _e("Ouch! The field is empty.", "doppler-form"); ?>"
							class="visible"
							autocomplete="off"
							placeholder=""
							aria-invalid="<?= (isset($errorMessages['api_key']) || $error) ? "true" : "false" ?>"
							value="<?php echo (isset($options['dplr_option_apikey']))? $options['dplr_option_apikey']:'' ?>" />
						<div class="assistance-wrap">
						<span><?= (isset($errorMessages['api_key']) || $error) ? _e("Ouch! Enter a valid Email.", "doppler-form"):  ""?></span>
						</div>
					</label>
					<button class="dp-button button-big primary-green button-big">
						<?php _e("Connect", "doppler-form"); ?>
					</button>
				</form>
				<?php if($error): ?>
					<div class="tooltip tooltip-warning tooltip--user_api_error">
						<div class="text-red text-left">
								<span><?php echo $errorMessage  ?></span>
						</div>
					</div>
				<?php endif;?>
			</div>
			<p class="m-t-24">
				<?php _e("Do you have any doubts about how to connect your Forms with Doppler? Press", "doppler-form")?>
				<?php echo  '<a href="'.__('https://help.fromdoppler.com/en/how-to-integrate-wordpress-forms-with-doppler?utm_source=landing&utm_medium=integracion&utm_campaign=wordpress','doppler-form').'" target="blank">'.__('HELP','doppler-form').'</a>'?>.
			</p>
		<?php
		}
		?>
	</div>
</section>

<div id="dplr-dialog-confirm" title="<?php _e('Are you sure you want to uninstall the extension?', 'doppler-form'); ?>">
    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span> <?php _e('This will deactivate and uninstall the plugin.', 'doppler-form')?></p>
</div>
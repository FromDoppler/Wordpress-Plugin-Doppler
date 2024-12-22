
<section class="dplr_settings dp-library">

<div class="dplr_connect">

		<h2></h2>

		<a href="<?php _e('https://www.fromdoppler.com/en/?utm_source=landing&utm_medium=integracion&utm_campaign=wordpress', 'doppler-form')?>" target="_blank" id="dplr_logo" class="d-inline-block"><img src="<?= plugins_url( '/../img/logo-doppler.svg', __FILE__ ); ?>" alt="Doppler"></a>

		<?php
		if ($connected) {
		?>
			<header class="hero-banner">
				<div class="dp-rowflex col-sm-11">
					<div class="col-sm-12 col-md-12 col-lg-12">
						<h2><?php _e("Successful connection!", "doppler-form" ); ?></h2>
					</div>
					<div class="col-sm-10">
						<p>
							<?php _e("Your account is now officially connected","doppler-form");?>:
							<strong> <?php echo $options['dplr_option_useraccount']?></strong>
						</p>
					</div>
					<div class="col-sm-2 text-align--right">
						<form method="POST" action="options.php" id="dplr-disconnect-form">
							<button type="submit" class="dp-button button-medium primary-green">
								<?php _e("Disconnect", "doppler-form"); ?>
							</button>
						</form>
					</div>
				</div>
				<span class="arrow"></span>
			</header>
			<div class="dp-container">
				<div class="dp-wrapper-as-kpi">
					<ul class="kpi-ul">
						<li>
							<div class="dp-kpi-card dp-white">
								<span class="dp-assisted-sales-icon dpicon iconapp-persons"></span>
								<div class="dp-assisted-sales-text">
									<h3>4</h3>
									<span>Formularios Activos</span>
								</div>
							</div>
						</li>
						<li>
							<div class="dp-kpi-card dp-white">
								<span class="dp-assisted-sales-icon dpicon iconapp-website1"></span>
								<div class="dp-assisted-sales-text">
									<h3>4</h3>
									<span>Suscriptores</span>
								</div>
							</div>
						</li>
						<li>
							<div class="dp-kpi-card dp-white">
								<span class="dp-assisted-sales-icon dpicon iconapp-web-eye"></span>
								<div class="dp-assisted-sales-text">
									<h3>524</h3>
									<span>Contactos sincronizados</span>
								</div>
							</div>
						</li>
					</ul>
				</div>
			</div>
			<div class="updated-message">
				<hr class="mb-1 mt-1">
				<div class="dplr-pasos">
					<!--<h2><?php _e('You are almost done! Follow this steps', 'doppler-form')?></h2>-->
					<div><!-- 3 boxes -->
						<div>
							<figure>
								<img src="<?= plugins_url( '/../img/'.__('screenshot-1.png', 'doppler-form'), __FILE__ ); ?>" alt="step 1"/>
							</figure>
							<span>
								1.
							</span>
							<p>
								<?php
									_e('Go to Doppler Forms > Create Form.', 'doppler-form');
								?>
							</p>
						</div>

						<div>
							<figure>
								<img src="<?= plugins_url( '/../img/'.__('screenshot-2.png', 'doppler-form'), __FILE__ ); ?>" alt="step 2"/>
							</figure>
							<span>
								2.
							</span>
							<p>
								<?php
									_e('Go to Appearance > Widgets > Doppler Form and select where do you want the Forms to be displayed.', 'doppler-form');
								?>
							</p>
						</div>

						<div>
							<figure>
								<img src="<?= plugins_url( '/../img/'.__('screenshot-3.png', 'doppler-form'), __FILE__ ); ?>" alt="step 3"/>
							</figure>
							<span>
								3.
							</span>
							<p>
								<?php
									_e('Done! You should now see your Form published on your Website.', 'doppler-form');
								?>
							</p>
						</div>
					</div> <!-- fin 3 boxes -->
				</div> <!-- fin dplr_pasos -->
			</div> <!-- fin updated message -->
			<section class="dplr_settings dplr-extensions mt-12">
				<div class="dplr_connect text-center">
					<div class="dplr-boxes">                          
						<div>
							<div class="extension-card">
								<figure>
									<img src="<?php echo plugins_url( '/../img/woocommerce-logo.png', __FILE__ ); ?>" alt="<?php _e('Doppler for WooCommerce', 'doppler-form')?>"/>
								</figure>
								
								<h3><?php _e('Doppler for WooCommerce', 'doppler-form')?></h3>
								
								<p>
									<?php _e('Import customers to your Doppler Lists.', 'doppler-form') ?>
								</p>
								<div class="box-footer">
									<?php if(!$this->extension_manager->is_active('doppler-for-woocommerce')):  ?>
										<button class="dp-button primary-green button-medium dp-install" 
												<?php if(!$this->extension_manager->has_dependency('doppler-for-woocommerce')) echo 'disabled'?>
												data-extension="doppler-for-woocommerce">
												<?php _e('Install', 'doppler-form') ?>
										</button>
										<?php if(!$this->extension_manager->has_dependency('doppler-for-woocommerce')):?>
										<p class="text-italic"><?php _e('You should have <a href="https://wordpress.org/plugins/woocommerce/">WooCommerce plugin</a> installed and active first.', 'doppler-form')?></p>
										<?php endif; ?>
									<?php else: ?>
										<span clasS="text-regular-grey"><img src="<?php echo plugins_url('/../img/status-ckeck-icon.svg', __FILE__ );?>"> <?php _e('Successfully Instaled', 'doppler-form');?></span>
									<?php endif; ?>
								</div>
							</div>
							<?php if($this->extension_manager->is_active('doppler-for-woocommerce')):  ?>
							<a href="<?php echo admin_url('admin.php?page='.$this->extension_manager->extensions['doppler-for-woocommerce']['settings'])?>"><?php _e('Configure extension', 'doppler-form')?> >></a>
							<?php endif; ?>
						</div>
					
						<div>
							<div class="extension-card">
								<figure>
									<img src="<?php echo plugins_url( '/../img/learnpress-logo.png', __FILE__ ); ?>" alt="<?php _e('Doppler for LearnPress', 'doppler-form');?>"/>
								</figure>
							
								<h3><?php _e('Doppler for LearnPress', 'doppler-form');?></h3>
								<p>
									<?php _e('Import students to your Doppler Lists.', 'doppler-form') ?>
								</p>
								<div class="box-footer">
									<?php if( !$this->extension_manager->is_active('doppler-for-learnpress')):  ?>
										<button class="dp-button primary-green button-medium dp-install" 
												<?php if(!$this->extension_manager->has_dependency('doppler-for-learnpress')) echo 'disabled'?>
												data-extension="doppler-for-learnpress">
												<?php _e('Install', 'doppler-form') ?>
										</button>
										<?php if(!$this->extension_manager->has_dependency('doppler-for-learnpress')):?>
											<p class="text-italic"><?php _e('You should have <a href="https://wordpress.org/plugins/learnpress/">LearnPress plugin</a> installed and active first.', 'doppler-form')?></p>
										<?php endif; ?>
									<?php else: ?>
										<span clasS="text-regular-grey"><img src="<?php echo plugins_url('/../img/status-ckeck-icon.svg', __FILE__ );?>"> <?php _e('Successfully Instaled', 'doppler-form');?></span>
									<?php endif; ?>
								</div>
							</div>
							<?php if($this->extension_manager->is_active('doppler-for-learnpress')):  ?>
							<a href="<?php echo admin_url('admin.php?page='.$this->extension_manager->extensions['doppler-for-learnpress']['settings'])?>"><?php _e('Configure extension', 'doppler-form')?> >></a>
							<?php endif; ?>
						</div>            
					</div>
				</div>
			</section>
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
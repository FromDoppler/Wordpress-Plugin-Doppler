<?php
if ( ! current_user_can( 'manage_options' ) ) {
    return;
}
?>
<div class="dp-library">
    <!-- This inline style is a hack to avoid loading content before the loading screen is hidden. -->
    <div class="dp-container" id="dplr_body_content" style="display: none;">
        <div class="dplr_settings">
            <a href="<?php _e('https://www.fromdoppler.com/en/?utm_source=landing&utm_medium=integracion&utm_campaign=wordpress', 'doppler-form')?>" target="_blank" class="dplr-logo-header">
                <img id="" src="<?php echo DOPPLER_PLUGIN_URL?>admin/img/logo-doppler.svg" alt="Doppler logo"/>
            </a>

            <?php $this->display_success_message() ?>
            <?php $this->display_error_message() ?>

            <form id="dplrwoo-form-settings" action="" method="post">
                <?php wp_nonce_field( 'use-settings' );?>
                <section class="col-sm-12 col-md-10 col-lg-7">
                    <h2 class="main-title">
                        <?php _e('Doppler Forms', 'doppler-form')?> <?php echo $this->get_version()?>
                    </h2>
                    <header>
                        <div class="dp-container">
                            <div class="dp-rowflex">
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <h3><?php _e('On-Site Tracking', 'doppler-form')?></h3>
                                </div>
                                <div class="col-sm-10 col-md-10 col-lg-10">
                                    <p><?php _e('Get your Site Tracking Code from Doppler and paste it below to track your visitors activity. Not sure how to get your code? Press <a href="https://help.fromdoppler.com/en/create-onsite-tracking-automation" class="green-link">HELP</a>','doppler-form') ?>.</p>
                                </div>
                            </div>
                        </div>
                    </header>
                    <div class="dplr-tab-content">
                        <p>
                            <textarea name="dplr_hub_script" rows="3" class="m-b-12" placeholder="<?php _e('Paste tracking code here.','doppler-form')?>"><?php echo stripslashes(html_entity_decode($dplr_hub_script)) ?></textarea>
                        </p>            
                    </div>
                </section>
            
                <?php if($this->extension_manager->is_active('doppler-for-woocommerce')):
                    $dplr_wc_consent = get_option('dplr_wc_consent', 0);
                    $dplr_wc_consent_location = get_option('dplr_wc_consent_location', "contact");
                    $dplr_wc_consent_text = get_option('dplr_wc_consent_text', '');
                ?>
                <section class="col-sm-12 col-md-10 col-lg-7 m-t-36">
                    <h2 class="main-title">
                        <?php _e('Doppler for WooCommerce', 'doppler-for-woocommerce')?> <?php echo DOPPLER_FOR_WOOCOMMERCE_VERSION ?>
                    </h2>
                    <header>
                        <div class="dp-container">
                            <div class="dp-rowflex space-between">
                                <div class="col-sm-10 col-md-10 col-lg-10">
                                    <h3><?php _e('Consent checkbox in Checkout', 'doppler-form')?></h3>
                                </div>
                                <div class="dp-switch">
                                    <input type="hidden" name="dplr-consent-checkbox" value="0">
                                    <input type="checkbox" id="dplr-consent-checkbox" name="dplr-consent-checkbox" <?php checked($dplr_wc_consent, 1); ?>>
                                    <label for="dplr-consent-checkbox">
                                        <span></span>
                                    </label>
                                </div>
                                <div class="col-sm-10 col-md-10 col-lg-10">
                                    <p><?php _e('Adds a checkbox in the checkout for users to consent to email marketing emails','doppler-form') ?>.</p>
                                </div>
                            </div>
                        </div>
                    </header>
                    <div class="dplr-tab-content m-t-12">
                        <div class="awa-form col-sm-12 col-md-12 col-lg-12">
                            <label for="dplr-consent-location" class="labelcontrol d-flex align-center" <?php echo $dplr_wc_consent ? '' : 'aria-disabled="true"'; ?>>
                                <span class="col-sm-3 col-md-3 col-lg-3"><?php _e('Location', 'doppler-form'); ?>:</span>
                                <div class="dp-select col-sm-8 col-md-8 col-lg-8 pl-0 pr-0">
                                    <span class="dropdown-arrow"></span>
                                    <select id="dplr-consent-location" name="dplr-consent-location" <?php echo $dplr_wc_consent ? '' : 'disabled'; ?>>
                                        <option value="contact" <?php selected($dplr_wc_consent_location, "contact"); ?>><?php _e('Bellow contact Email', 'doppler-form'); ?></option>
                                        <option value="order" <?php selected($dplr_wc_consent_location, "order"); ?>><?php _e('Checkout notes', 'doppler-form'); ?></option>
                                    </select>
                                </div>
                            </label>
                        </div>
                        <div class="awa-form col-sm-12 col-md-12 col-lg-12">
                            <label for="dplr-consent-text" class="labelcontrol d-flex align-center" <?php echo $dplr_wc_consent ? '' : 'aria-disabled="true"'; ?>>
                                <span class="col-sm-3 col-md-3 col-lg-3"><?php _e('Consent', 'doppler-form'); ?>:</span>
                                <input type="text" id="dplr-consent-text" class="col-sm-8 col-md-8 col-lg-8 box-shado-0"
                                    name="dplr-consent-text" placeholder="<?php _e('Your consent text', 'doppler-form'); ?>" 
                                    value="<?php echo esc_attr($dplr_wc_consent_text); ?>" <?php echo $dplr_wc_consent ? '' : 'disabled'; ?>>
                            </label>
                        </div>
                    </div>
                </section>
                <?php endif; ?>
                <div class="col-sm-12 col-md-10 col-lg-7 m-t-36 d-flex justify-end">
                    <button id="dplrwoo-hub-btn" class="dp-button button-medium primary-green text-align--right">
                        <?php _e('Save', 'doppler-form') ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
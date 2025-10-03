<?php
if ( ! current_user_can( 'manage_options' ) ) {
    return;
}
?>
<div class="dp-library">
    <!-- This inline style is a hack to avoid loading content before the loading screen is hidden. -->
    <div class="dp-container" id="dplr_body_content" style="display: none;">
        <div class="dplr_settings">
            <a href="<?php esc_attr_e('https://www.fromdoppler.com/en/?utm_source=landing&utm_medium=integracion&utm_campaign=wordpress', 'doppler-form')?>" target="_blank" class="dplr-logo-header">
                <img id="" src="<?php echo esc_url(DOPPLER_PLUGIN_URL)?>admin/img/logo-doppler.svg" alt="Doppler logo"/>
            </a>

            <?php $this->display_success_message() ?>
            <?php $this->display_error_message() ?>

            <form id="dplrwoo-form-settings" action="" method="post">
                <?php wp_nonce_field( 'use-settings' );
                    $dplr_tracking = get_option('dplr_hub_script', '');
                ?>
                <section class="col-sm-12 col-md-10 col-lg-7">
                    <h2 class="main-title">
                        <?php esc_html_e('Doppler Forms', 'doppler-form')?> <?php echo esc_html($this->get_version())?>
                    </h2>
                    <header>
                        <div class="dp-container">
                            <div class="dp-rowflex space-between">
                                <div class="col-sm-10 col-md-10 col-lg-10">
                                    <h3><?php esc_html_e('On-Site Tracking', 'doppler-form')?></h3>
                                </div>
                                <div class="dp-switch">
                                    <input type="hidden" name="dplr-tracking-checkbox" value="0">
                                    <input type="checkbox" id="dplr-tracking-checkbox" name="dplr-tracking-checkbox" <?php echo $dplr_tracking !== '' ? 'checked' : ''; ?>>
                                    <label for="dplr-tracking-checkbox">
                                        <span></span>
                                    </label>
                                </div>
                                <div class="col-sm-10 col-md-10 col-lg-10">
                                    <p><?php esc_html_e('Adds Doppler\'s Site Tracking Code to your site to track your visitors activity. Not sure how it works? Press <a href="https://help.fromdoppler.com/en/create-onsite-tracking-automation" class="green-link">HELP</a>','doppler-form') ?>.</p>
                                </div>
                                <blockquote class="code-block">
                                    <p>
                                        <code>&lt;script async="async" type="text/javascript" src="https://hub.fromdoppler.com/public/dhtrack.js"&gt;&lt;/script&gt;</code>
                                    </p>
                                </blockquote>
                            </div>
                        </div>
                    </header>
                </section>
            
                <?php if($this->extension_manager->is_active('doppler-for-woocommerce')):
                    $dplr_wc_consent = get_option('dplr_wc_consent', 0);
                    $dplr_wc_consent_location = get_option('dplr_wc_consent_location', "contact");
                    $dplr_wc_consent_text = get_option('dplr_wc_consent_text', '');
                    $dplr_wc_open_graph = get_option('dplr_wc_open_graph_meta', 0);
                ?>
                <section class="col-sm-12 col-md-10 col-lg-7 m-t-36">
                    <h2 class="main-title">
                        <?php esc_html_e('Doppler for WooCommerce', 'doppler-form')?> <?php echo esc_html($this->DOPPLER_FOR_WOOCOMMERCE_VERSION) ?>
                    </h2>
                    <div>
                        <header>
                            <div class="dp-container">
                                <div class="dp-rowflex space-between">
                                    <div class="col-sm-10 col-md-10 col-lg-10">
                                        <h3><?php esc_html_e('Consent checkbox in Checkout', 'doppler-form')?></h3>
                                    </div>
                                    <div class="dp-switch">
                                        <input type="hidden" name="dplr-consent-checkbox" value="0">
                                        <input type="checkbox" id="dplr-consent-checkbox" name="dplr-consent-checkbox" <?php checked($dplr_wc_consent, 1); ?>>
                                        <label for="dplr-consent-checkbox">
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-10 col-md-10 col-lg-10">
                                        <p><?php esc_html_e('Adds a checkbox in the checkout for users to consent to email marketing emails. Not available when using WooCommerce Checkout Block.','doppler-form') ?></p>
                                    </div>
                                </div>
                            </div>
                        </header>
                        <div class="dplr-tab-content m-t-12">
                            <div class="awa-form col-sm-12 col-md-12 col-lg-12">
                                <label for="dplr-consent-location" class="labelcontrol d-flex align-center" <?php echo $dplr_wc_consent ? '' : 'aria-disabled="true"'; ?>>
                                    <span class="col-sm-3 col-md-3 col-lg-3"><?php esc_html_e('Location', 'doppler-form'); ?>:</span>
                                    <div class="dp-select col-sm-8 col-md-8 col-lg-8 pl-0 pr-0">
                                        <span class="dropdown-arrow"></span>
                                        <select id="dplr-consent-location" name="dplr-consent-location" <?php echo $dplr_wc_consent ? '' : 'disabled'; ?>>
                                            <option value="contact" <?php selected($dplr_wc_consent_location, "contact"); ?>><?php esc_html_e('Bellow contact Email', 'doppler-form'); ?></option>
                                            <option value="order" <?php selected($dplr_wc_consent_location, "order"); ?>><?php esc_html_e('Checkout notes', 'doppler-form'); ?></option>
                                        </select>
                                    </div>
                                </label>
                            </div>
                            <div class="awa-form col-sm-12 col-md-12 col-lg-12">
                                <label for="dplr-consent-text" class="labelcontrol d-flex align-center" <?php echo $dplr_wc_consent ? '' : 'aria-disabled="true"'; ?>>
                                    <span class="col-sm-3 col-md-3 col-lg-3"><?php esc_html_e('Consent', 'doppler-form'); ?>:</span>
                                    <input type="text" id="dplr-consent-text" class="col-sm-8 col-md-8 col-lg-8 box-shado-0"
                                        name="dplr-consent-text" placeholder="<?php esc_attr_e('Your consent text', 'doppler-form'); ?>" 
                                        value="<?php echo esc_attr($dplr_wc_consent_text); ?>" <?php echo $dplr_wc_consent ? '' : 'disabled'; ?>>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="m-t-36">
                        <header>
                            <div class="dp-container">
                                <div class="dp-rowflex space-between">
                                    <div class="col-sm-10 col-md-10 col-lg-10">
                                        <h3><?php esc_html_e('Boost your site\'s shared content with Open Graph', 'doppler-form')?></h3>
                                    </div>
                                    <div class="dp-switch">
                                        <input type="hidden" name="dplr-wc-open-graph-checkbox" value="0">
                                        <input type="checkbox" id="dplr-wc-open-graph-checkbox" name="dplr-wc-open-graph-checkbox" <?php checked($dplr_wc_open_graph, 1); ?>>
                                        <label for="dplr-wc-open-graph-checkbox">
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-10 col-md-10 col-lg-10">
                                        <p><?php esc_html_e('Open Graph is a protocol that makes your pages look better when shared on social media. At Doppler, they help you track your product pages more effectively, optimizing the Automation and OnSite widgets features.','doppler-form') ?></p>
                                        <p class="m-t-6"><?php esc_html_e('The properties added are:','doppler-form') ?></p>
                                        <ul class="settings-list m-t-6">
                                            <li><p>og:type</p></li>
                                            <li><p>og:title</p></li>
                                            <li><p>og:description</p></li>
                                            <li><p>og:url</p></li>
                                            <li><p>og:image</p></li>
                                            <li><p>og:price</p></li>
                                            <li><p>og:currency</p></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </header>
                    </div>
                </section>
                <?php endif; ?>
                <div class="col-sm-12 col-md-10 col-lg-7 m-t-36 d-flex justify-end">
                    <button id="dplrwoo-hub-btn" class="dp-button button-medium primary-green text-align--right">
                        <?php esc_html_e('Save', 'doppler-form') ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
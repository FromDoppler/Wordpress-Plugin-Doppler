<?php
if ( ! current_user_can( 'manage_options' ) ) {
    return;
}
?>
<div class="dp-library">
    <div class="dp-container">
        <div class="wrap dplr_settings">
            <a href="<?php _e('https://www.fromdoppler.com/en/?utm_source=landing&utm_medium=integracion&utm_campaign=wordpress', 'doppler-form')?>" target="_blank" class="dplr-logo-header">
                <img id="" src="<?php echo DOPPLER_PLUGIN_URL?>admin/img/logo-doppler.svg" alt="Doppler logo"/>
            </a>

            <h2 class="main-title">
                <?php _e('Doppler Forms', 'doppler-form')?> <?php echo $this->get_version()?>
            </h2>

            <h2 class="nav-tab-wrapper">
                <a href="?page=doppler-data-hub" class="nav-tab nav-tab-active">
                    <?php _e('On-Site Tracking', 'doppler-form')?>
                </a>
            </h2>

            <div class="dplr-tab-content">
                <?php $this->display_success_message() ?>
                <?php $this->display_error_message() ?>

                <p class="size-medium m-b-30">
                    <?php _e('Get your Site Tracking Code from Doppler and paste it below to track your visitors activity. Not sure how to get your code? Press <a href="https://help.fromdoppler.com/en/create-onsite-tracking-automation" class="green-link">HELP</a>','doppler-form') ?>.
                </p>

                <form id="dplrwoo-form-hub" action="" method="post" class="mw-7">

                    <?php wp_nonce_field( 'use-hub' );?>
                    <p>
                        <textarea name="dplr_hub_script" rows="3" class="m-b-12" placeholder="<?php _e('Paste tracking code here.','doppler-form')?>"><?php echo stripslashes(html_entity_decode($dplr_hub_script)) ?></textarea>
                    </p>
                    <button id="dplrwoo-hub-btn" class="dp-button button-medium primary-green">
                        <?php _e('Save', 'doppler-form') ?>
                    </button>

                </form>
            </div>
        </div>
    </div>
</div>
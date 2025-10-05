<?php

if ( ! current_user_can( 'manage_options' ) ) {
    return;
}

//Makes call to API.
$response =  $this->doppler_service->connectionStatus();
?>
<div class="dp-library">
    <!-- This inline style is a hack to avoid loading content before the loading screen is hidden. -->
    <div class="dp-container" id="dplr_body_content" style="display: none;">
        <div class="dplr_settings">

            <a href="<?php esc_html_e('https://www.fromdoppler.com/en/?utm_source=landing&utm_medium=integracion&utm_campaign=wordpress', 'doppler-form')?>" target="_blank" class="dplr-logo-header">
                <img id="" src="<?php echo esc_url(DOPPLER_PLUGIN_URL)?>admin/img/logo-doppler.svg" alt="Doppler logo"/>
            </a>

            <h2 class="main-title"><?php esc_html_e('Doppler Forms', 'doppler-form')?> <?php echo esc_html($this->get_version())?></h2> 
            <?php
            if( is_array($response) && $response['response']['code']>=400 && true ){
                ?>
                <div class="mt-1">
                    <?php
                    $this->set_error_message(__('Ouch! An error ocurred while trying to communicate with the API. Try again later.','doppler-form'));
                    $this->display_error_message();
                    return false;
                    ?>
                </div>
            <?php
            }
            ?>

            <?php

            switch($active_tab){
                case 'forms':
                    include plugin_dir_path( __FILE__ ) . "../partials/forms-list.php";
                    break;
                case 'new':
                    $this->display_error_message();
                    $this->form_controller->showCreateEditForm();
                    break;
                case 'edit':
                    $this->display_error_message();
                    check_admin_referer('dplr-create-edit-form');
                    $form_id = 0;

                    if(isset($_GET['form_id'])){
                        $form_id = absint(wp_unslash($_GET['form_id']));
                    }
                    elseif(isset($_POST['form_id'])){
                        $form_id = absint(wp_unslash($_POST['form_id']));
                    }
                    else{
                        die(esc_html__('Form ID is required','doppler-form'));
                    }
                    $this->form_controller->showCreateEditForm($form_id);
                    break;
                default:
                    break;
            }
            ?>
        </div>
    </div>
</div>
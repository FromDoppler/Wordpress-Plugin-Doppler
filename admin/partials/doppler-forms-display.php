<?php

if ( ! current_user_can( 'manage_options' ) ) {
    return;
}

//Makes call to API.
$response =  $this->doppler_service->connectionStatus();

?>
<div class="dp-library">
    <div class="dp-container">
        <div class="dplr_settings">

            <a href="<?php _e('https://www.fromdoppler.com/en/?utm_source=landing&utm_medium=integracion&utm_campaign=wordpress', 'doppler-form')?>" target="_blank" class="dplr-logo-header">
                <img id="" src="<?php echo DOPPLER_PLUGIN_URL?>admin/img/logo-doppler.svg" alt="Doppler logo"/>
            </a>

            <h2 class="main-title"><?php _e('Doppler Forms', 'doppler-form')?> <?php echo $this->get_version()?></h2> 
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
                    $form_id = isset($_GET['form_id']) ? $_GET['form_id'] : $_POST['form_id'];
                    $this->form_controller->showCreateEditForm($form_id);
                    break;
                default:
                    break;
            }
            ?>
        </div>
    </div>
</div>
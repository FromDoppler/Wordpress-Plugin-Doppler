<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="dplr dplr-tab-content dplr-tab-content--list">

  <header class="hero-banner">
    <div class="dp-container">
      <div class="dp-rowflex">
        <div class="col-sm-12 col-md-12 col-lg-12">
          <h2><?php esc_html_e('Manage your forms', 'doppler-form')?></h2>
        </div>
        <div class="col-sm-7">
          <p>
            <?php
            $dplr_form_count = count( $forms );
            $dplr_text = sprintf(
                /* translators: %s: Number of forms. */
                _n(
                    'Here you will find all your subscription forms. You currently have <strong>%s</strong> form',
                    'Here you will find all your subscription forms. You currently have <strong>%s</strong> forms',
                    $dplr_form_count,
                    'doppler-form'
                ),
                number_format_i18n( $dplr_form_count )
            );
            $dplr_allowed_html = array( 'strong' => array() );
            echo wp_kses( $dplr_text, $dplr_allowed_html );
            ?>
          </p>
        </div>
        <div class="col-sm-5 text-align--right">
        <a href="<?php echo esc_url($create_form_url); ?>">
          <button type="button" class="dp-button button-medium primary-green">
            <?php esc_html_e('Create Form', 'doppler-form')?>
          </button>
        </a>
        </div>
      </div>
      <span class="arrow"></span>
    </div>
  </header>
  <?php $this->display_success_message() ?>
  <?php $this->display_error_message() ?>
  
  <?php   
  if(count($forms) == 0){
    ?><h2><?php esc_html_e('You don\'t have Forms yet!','doppler-form'); ?></h2><?php
  }else{
    ?>

      <table class="dp-c-table" aria-label="Forms list" summary="Forms list">
        <thead>
          <tr>
            <th aria-label="Form name" scope="col">
              <span><?php esc_html_e('Form Name', 'doppler-form')?></span>
            </th>
            <th aria-label="Form Opt-In" scope="col">
              <span><?php esc_html_e('Form Opt-In', 'doppler-form')?></span>
            </th>
            <th aria-label="List Name" scope="col">
              <span><?php esc_html_e('List Name', 'doppler-form')?></span>
            </th>
            <th aria-label="Shortcode" scope="col">
              <span><?php esc_html_e('Shortcode', 'doppler-form')?></span>
            </th>
            <th aria-label="Actions" scope="col" style="width: 25px;">
              <span><?php esc_html_e('Actions', 'doppler-form')?></span>
            </th>
          </tr>
        </thead>
        <tbody>
          <?php for ($dplr_i=0; $dplr_i <count($forms) ; $dplr_i++) {
              $dplr_form = DPLR_Form_Model::get($forms[$dplr_i]->id, true);
              $dplr_edit_form_url = admin_url( 'admin.php?page=doppler_forms_main&tab=edit&form_id=' . $dplr_form->id );
              $dplr_edit_nonce_url = wp_nonce_url( $dplr_edit_form_url, 'dplr-create-edit-form' );
              ?>
            <tr>
              <td aria-label="Form name">
                <a href="<?php echo esc_url( $dplr_edit_nonce_url ); ?>" role="link" rel="noopener" class="bold">
                  <?php echo esc_html($dplr_form->name); ?>
                </a>
              </td>
              <td aria-label="Form Opt-In">
                <span><?php echo ($dplr_form->settings["form_doble_optin"] == "yes") ? esc_html_e('Double Opt-In', 'doppler-form') : esc_html_e('Simple Opt-In', 'doppler-form') ?></span>
              </td>
              <td aria-label="List Name">
                <span><?php echo isset($dplr_lists_arr[$dplr_form->list_id]) ? esc_html($dplr_lists_arr[$dplr_form->list_id]) : '' ?></span>
              </td>
              <td aria-label="Shortcode">
                <div class="dp-rowflex">
                  <span>[doppler-form id='<?php echo esc_html($dplr_form->id) ?>']</span>
                  <div class="dp-icons-group col-lg-2 col-sm-2 col-md-2">
                    <a class="copy-shortcode">
                      <div class="dp-tooltip-container">
                          <span class="ms-icon dpicon iconapp-copy-file"></span>
                          <div class="dp-tooltip-top">
                            <span><?php esc_html_e('Copy to clipboard', 'doppler-form')?></span>
                          </div>
                      </div>
                    </a>
                  </div>
                </div>
              </td>
              <td aria-label="Actions">
                <div class="dp-icons-group">
                  <a href="<?php echo esc_url( $dplr_edit_nonce_url ); ?>" class="p-r-6">
                    <div class="dp-tooltip-container">
                      <span class="ms-icon icon-edit"></span>
                      <div class="dp-tooltip-top">
                        <span><?php esc_html_e('Edit', 'doppler-form')?></span>
                      </div>
                    </div>
                  </a>
                  <?php
                    $dplr_delete_form_url = admin_url( 'admin.php?page=doppler_forms_main&action=delete&form_id=' . $dplr_form->id );
                    $dplr_delete_nonce_url = wp_nonce_url( $dplr_delete_form_url, 'dplr-delete-form_' . $dplr_form->id );
                  ?>
                  <a href="<?php echo esc_url( $dplr_delete_nonce_url ); ?>"
                    data-list-id="<?php echo esc_attr($dplr_form->id) ?>"
                    data-nonce="<?php echo esc_attr(wp_create_nonce('dplr-delete-form-nonce')); ?>"
                    class="dplr-remove">
                    <div class="dp-tooltip-container">
                      <span class="ms-icon icon-delete"></span>
                      <div class="dp-tooltip-top">
                        <span><?php esc_html_e('Delete', 'doppler-form')?></span>
                      </div>
                    </div>
                  </a>
                </div>
              </td>
            </tr>
            <?php } ?>
        </tbody>
      </table>
  <?php
  }
  ?>

  <div id="dplr-dialog-confirm" title="<?php esc_attr_e('Are you sure you want to delete the Form?', 'doppler-form'); ?>">
    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span> <?php esc_html_e('It\'ll be deleted and can\'t be recovered.', 'doppler-form')?></p>
  </div>  

</div>
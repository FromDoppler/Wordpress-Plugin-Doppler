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
            $form_count = count( $forms );
            $text = sprintf(
                _n(
                    'Here you will find all your subscription forms. You currently have <strong>%s</strong> form',
                    'Here you will find all your subscription forms. You currently have <strong>%s</strong> forms',
                    $form_count,
                    'doppler-form'
                ),
                number_format_i18n( $form_count )
            );
            $allowed_html = array( 'strong' => array() );
            echo wp_kses( $text, $allowed_html );
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
          <?php for ($i=0; $i <count($forms) ; $i++) {
              $form = DPLR_Form_Model::get($forms[$i]->id, true);
              ?>
            <tr>
              <td aria-label="Form name">
                <a href="<?php echo esc_url(str_replace('[FORM_ID]', $form->id , $edit_form_url)); ?>" role="link" rel="noopener" class="bold"><?php echo esc_html($form->name); ?></a>
              </td>
              <td aria-label="Form Opt-In">
                <span><?php echo ($form->settings["form_doble_optin"] == "yes") ? esc_html_e('Double Opt-In', 'doppler-form') : esc_html_e('Simple Opt-In', 'doppler-form') ?></span>
              </td>
              <td aria-label="List Name">
                <span><?php echo isset($dplr_lists_arr[$form->list_id]) ? esc_html($dplr_lists_arr[$form->list_id]) : '' ?></span>
              </td>
              <td aria-label="Shortcode">
                <div class="dp-rowflex">
                  <span>[doppler-form id='<?php echo esc_html($form->id) ?>']</span>
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
                  <a href="<?php echo esc_url(str_replace('[FORM_ID]', $form->id , $edit_form_url)); ?>" class="p-r-6">
                    <div class="dp-tooltip-container">
                      <span class="ms-icon icon-edit"></span>
                      <div class="dp-tooltip-top">
                        <span><?php esc_html_e('Edit', 'doppler-form')?></span>
                      </div>
                    </div>
                  </a>
                  <?php
                    $delete_form_url = admin_url( 'admin.php?page=doppler_forms_main&action=delete&form_id=' . $form->id );
                    $delete_nonce_url = wp_nonce_url( $delete_form_url, 'dplr-delete-form_' . $form->id );
                  ?>
                  <a href="<?php echo esc_url( $delete_nonce_url ); ?>"
                    data-list-id="<?php echo esc_attr($form->id) ?>"
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
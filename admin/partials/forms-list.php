<div class="dplr dplr-tab-content dplr-tab-content--list">

  <?php $this->display_success_message() ?>

  <?php $this->display_error_message() ?>

  <a href="<?php echo $create_form_url; ?>" class="dp-button primary-green button-medium mb-1"><?php _e('Create Form', 'doppler-form')?></a>

  <?php   
  if(count($forms) == 0){
    ?><h2><?php _e('You don\'t have Forms yet!','doppler-form'); ?></h2><?php
  }else{
    ?>

      <table class="dp-c-table" aria-label="Forms list" summary="Forms list">
        <caption>
          <span> 
            <?php printf(
              _n(
                'You have <strong>%s</strong> form',
                'You have <strong>%s</strong> forms',
                count($forms),
                'doppler-form'
              ),
              number_format_i18n( count($forms) )
            );
            ?>
          </span>
        </caption>
        <thead>
          <tr>
            <th aria-label="Form name" scope="col">
              <span><?php _e('Form Name', 'doppler-form')?></span>
            </th>
            <th aria-label="Form Opt-In" scope="col">
              <span><?php _e('Form Opt-In', 'doppler-form')?></span>
            </th>
            <th aria-label="List Name" scope="col">
              <span><?php _e('List Name', 'doppler-form')?></span>
            </th>
            <th aria-label="Shortcode" scope="col">
              <span><?php _e('Shortcode', 'doppler-form')?></span>
            </th>
            <th aria-label="Actions" scope="col">
              <span><?php _e('Actions', 'doppler-form')?></span>
            </th>
          </tr>
        </thead>
        <tbody>
          <?php for ($i=0; $i <count($forms) ; $i++) {
              $form = DPLR_Form_Model::get($forms[$i]->id, true);
              ?>
            <tr>
              <td aria-label="Form name">
                <a href="<?php echo str_replace('[FORM_ID]', $form->id , $edit_form_url); ?>" role="link" rel="noopener" class="bold"><?php echo $form->name; ?></a>
              </td>
              <td aria-label="Form Opt-In">
                <span><?php echo ($form->settings["form_doble_optin"] == "yes") ? _e('Double Opt-In', 'doppler-form') : _e('Simple Opt-In', 'doppler-form') ?></span>
              </td>
              <td aria-label="List Name">
                <span><?php echo isset($dplr_lists_arr[$form->list_id])? $dplr_lists_arr[$form->list_id] : '' ?></span>
              </td>
              <td aria-label="Shortcode">
                <span>[doppler-form id='<?= $form->id ?>']</span>
              </td>
              <td aria-label="Actions">
                <div class="dp-icons-group">
                  <a href="<?php echo str_replace('[FORM_ID]', $form->id , $edit_form_url); ?>" class="p-r-6">
                    <div class="dp-tooltip-container">
                      <span class="ms-icon icon-edit"></span>
                      <div class="dp-tooltip-top">
                        <span><?php _e('Edit', 'doppler-form')?></span>
                      </div>
                    </div>
                  </a>
                  <a href="<?php echo str_replace('[FORM_ID]', $form->id , $delete_form_url); ?>" 
                    data-list-id="<?php echo $form->id ?>"
                    class="dplr-remove">
                    <div class="dp-tooltip-container">
                      <span class="ms-icon icon-delete"></span>
                      <div class="dp-tooltip-top">
                        <span><?php _e('Delete', 'doppler-form')?></span>
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

<div id="dplr-dialog-confirm" title="<?php _e('Are you sure you want to delete the Form?', 'doppler-form'); ?>">
  <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span> <?php _e('It\'ll be deleted and can\'t be recovered.', 'doppler-form')?></p>
</div>  

</div>
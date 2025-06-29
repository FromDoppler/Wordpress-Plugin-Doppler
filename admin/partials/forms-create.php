<div class="dplr dplr-tab-content dplr-tab-content--form-create">
  <form method="post" action="<?php admin_url() ?>admin.php?page=doppler_forms_main">
    
    <input type="hidden" name="create" value="true">
    
    <div class="grid">
      <div class="col-4-5 panel nopd dp-box-shadow">
        <div class="panel-header">
          <h2><?php _e('Form basic information', 'doppler-form')?></h2>
        </div>
        <div class="panel-body">
          <div class="dplr_input_section">
            <label for="name"><?php _e('Name', 'doppler-form')?> <span class="req"><?php _e('(Required)', 'doppler-form') ?></span></label>
            <input type="text" name="name" placeholder="" value="<?php echo isset($form['name'])?$form['name']:''; ?>" required maxlength="80"/>
          </div>
          <div class="dplr_input_section awa-form">
            <label for="list_id">
              <?php _e('Doppler List', 'doppler-form')?> <span class="req"><?php _e('(Required)', 'doppler-form') ?></span>
              <div class="dp-select">
                <span class="dropdown-arrow"></span>
                <select name="list_id" id="list-id" required>
                  <option value=""><?php _e('Select the destination List where your new Subscribers will be sent', 'doppler-form'); ?></option>
                  <?php 
                    for ($i=0; $i < count($dplr_lists); $i++) { 
                    ?><option <?php echo isset($form['list_id']) && intval($form['list_id']) == intval($dplr_lists[$i]->listId) ? 'selected="selected"' : ''; ?> value="<?php echo $dplr_lists[$i]->listId; ?>"><?php echo trim($dplr_lists[$i]->name); ?></option><?php
                    }
                  ?>
                </select>
              </div>
            </label>
          </div>
      </div>
    </div>
    
    <div class="grid">
      <div class="col-4-5 panel nopd dp-box-shadow">
        <div class="panel-header">
          <h2><?php _e('Form Fields', 'doppler-form')?></h2>
        </div>
        <div class="panel-body grid">
          <div class="col-1-2 dplr_input_section awa-form">
            <label for="fieldList"><?php _e('Fields to include', 'doppler-form')?> 
              <span class="hlp"><?php _e('Learn how to create Custom Fields with Doppler. Press', 'doppler-form')?> <a href="<?php _e('https://help.fromdoppler.com/en/how-to-create-a-customized-field/?utm_source=landing&utm_medium=integracion&utm_campaign=wordpress', 'doppler-form')?>" class="green-link" target="_blank"><?php _e('HELP', 'doppler-form')?></a>.</span>
              <div class="dp-select">
                <span class="dropdown-arrow"></span>
                <select name="fieldList" id="fieldList">
                  <option value=""><?php _e('Select the Fields that will appear on your Form', 'doppler-form')?></option>
                </select>
              </div>
            </label>
          </div>
          <div class="col-1-2 p-b-24">
            <span class="noti"><?php _e('Drag and drop the Fields to give them the order you want', 'doppler-form')?></span>
            <ul class="sortable accordion" id="formFields">
            </ul>
          </div>
        </div>
      </div>
    </div>
    
    <div class="grid">
      <div class="col-4-5 panel nopd dp-box-shadow">
        <div class="panel-header">
          <h2><?php _e('Form settings', 'doppler-form')?></h2>
        </div>
        <div class="panel-body grid">
          <div class="dplr_input_section">
            <label for="title"><?php _e('Title', 'doppler-form')?></label>
            <input type="text" name="title" placeholder="<?php _e('Subscribe to our Newsletter!', 'doppler-form')?>" value="<?php echo isset($form['title'])?$form['title']:''; ?>" maxlength="150"/>
          </div>
          <div class="dplr_input_section">
            <label for="submit_text"><?php _e('Button text', 'doppler-form')?></label>
            <input type="text" name="settings[button_text]" value="<?php echo isset($form['settings']['button_text'])?$form['settings']['button_text']:''; ?>" placeholder="<?php _e('Submit', 'doppler-form')?>" maxlength="40"/>
          </div>
          <div class="dplr_input_section awa-form">
            <label for="settings[button_position]">
              <?php _e('Button alignment', 'doppler-form')?>
              <div class="dp-select">
                <span class="dropdown-arrow"></span>
                <select name="settings[button_position]" id="settings[button_position]" required>
                <option <?php if(isset($form['settings']['button_position']) && $form['settings']['button_position'] == 'left') echo 'selected="selected"';?> value="left"><?php _e('Left', 'doppler-form')?></option>
                <option <?php if(isset($form['settings']['button_position']) && $form['settings']['button_position'] == 'center') echo 'selected="selected"';?> value="center"><?php _e('Center', 'doppler-form')?></option>
                <option <?php if(isset($form['settings']['button_position']) && $form['settings']['button_position'] == 'right') echo 'selected="selected"';?> value="right"><?php _e('Right', 'doppler-form')?></option>
                <option <?php if(isset($form['settings']['button_position']) && $form['settings']['button_position'] == 'fill') echo 'selected="selected"';?> value="fill"><?php _e('Full width', 'doppler-form')?></option>
                </select>
              </div>
            </label>
          </div>
          <div class="dplr_input_section">
            <label for="settings[change_button_bg]"><?php _e('Button background color', 'doppler-form')?></label>
            <div class="dp-input--radio">
              <label aria-disabled="false">
                <input type="radio"
                  name="settings[change_button_bg]"
                  value="no"
                  <?php if(!isset($form['settings']['change_button_bg']) || $form['settings']['change_button_bg']==='no') echo 'checked'?>
                  class="dplr-toggle-selector"/>
                <span><?php _e('Use my theme\'s default color', 'doppler-form')?></span>
              </label>
            </div>
            <div class="dp-input--radio">
              <label aria-disabled="false">
                <input type="radio"
                name="settings[change_button_bg]"
                value="yes"
                <?php if(isset($form['settings']['change_button_bg']) && $form['settings']['change_button_bg']==='yes') echo 'checked'?>
                class="dplr-toggle-selector"/>
                <span><?php _e('Choose another color', 'doppler-form')?></span>
              </label>
            </div>
            <div class="radio_section">
              <input  class="color-selector d-none" 
                      type="text" 
                      pattern="^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$" 
                      name="settings[button_color]"
                      oninvalid="setCustomValidity(object_string.hexValidationError)"
                      oninput="setCustomValidity('')" 
                      value="<?php echo isset($form['settings']["button_color"])?$form['settings']["button_color"]:''; ?>"/>
            </div>   
          </div>
          <div class="dplr_input_section">
            <label for="settings[use_thankyou_page]"><?php _e('What do you want to show to your users after submitting the Form?', 'doppler-form')?></label>
            <div class="radio_section">
              <div class="dp-input--radio">
                <label aria-disabled="false">
                  <input type="radio"
                    name="settings[use_thankyou_page]"
                    value="yes"
                    <?php if(isset($form['settings']['use_thankyou_page']) && $form['settings']['use_thankyou_page']==='yes') echo 'checked'?>
                    class="dplr-toggle-thankyou"/>
                  <span><?php _e('Custom confirmation page', 'doppler-form')?></span>
                </label>
              </div>
              <div class="dp-input--radio">
                <label aria-disabled="false">
                  <input type="radio"
                    name="settings[use_thankyou_page]"
                    value="no"
                    <?php if(!isset($form['settings']['use_thankyou_page']) || $form['settings']['use_thankyou_page']==='no') echo 'checked'?>
                    class="dplr-toggle-thankyou"/>
                  <span><?php _e('Confirmation message', 'doppler-form')?></span>
                </label>
              </div>
            </div>
          </div>
          <div class="dplr_input_section dplr_confirmation_message" <?= (isset($form['settings']['use_thankyou_page']) && $form['settings']['use_thankyou_page']==='yes')? 'style="display:none"' : 'style="display:block"'; ?>>
            <label for="submit_text"><?php _e('Confirmation message', 'doppler-form')?></label>
            <input type="text" name="settings[message_success]" value="<?=isset($form['settings']["message_success"])?$form['settings']["message_success"]:'' ?>" placeholder="<?php _e('Example: Thanks for subscribing!', 'doppler-form')?>" maxlength="150"/>
          </div>
          <div class="dplr_input_section dplr_thankyou_url" <?= (isset($form['settings']['use_thankyou_page']) && $form['settings']['use_thankyou_page']==='yes')? 'style="display:block"' : 'style="display:none"'; ?>>
            <label for="submit_text"><?php _e('Custom confirmation page URL', 'doppler-form')?> <span class="hlp"><?php _e('Enter the URL of the page that you\'ve created.', 'doppler-form')?></span></label>
            <input type="url" name="settings[thankyou_page_url]" value="<?=isset($form['settings']["thankyou_page_url"]) ? $form['settings']["thankyou_page_url"]:'' ?>" pattern="https?://.+" placeholder="" maxlength="150" <?php if(isset($form['settings']['use_thankyou_page']) && $form['settings']['use_thankyou_page']==='yes') echo 'required';?>/>
          </div>
          <div class="dplr_input_section">
            <label for="settings[form_orientation]"><?php _e('Form orientation', 'doppler-form')?> <span class="req"><?php _e('(Required)', 'doppler-form') ?></span></label>
            <div class="radio_section">
              <div class="dp-input--radio">
                <label aria-disabled="false">
                  <input type="radio"
                    name="settings[form_orientation]"
                    value="vertical"
                    <?php if(!isset($form['settings']['form_orientation']) || $form['settings']['form_orientation']==='vertical') echo 'checked'?>
                    class="dplr-toggle-thankyou"
                    required />
                  <span><?php _e('Vertical','doppler-form')?></span>
                </label>
              </div>
              <div class="dp-input--radio">
                <label aria-disabled="false">
                  <input type="radio"
                    name="settings[form_orientation]"
                    value="horizontal"
                    <?php if(isset($form['settings']['form_orientation']) && $form['settings']['form_orientation']==='horizontal') echo 'checked'?>
                    class="dplr-toggle-thankyou"
                    required />
                  <span><?php _e('Horizontal','doppler-form')?></span>
                </label>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="grid">
      <div class="col-4-5 panel nopd dp-box-shadow">
        <div class="panel-header">
          <h2><?php _e('Subscription Type', 'doppler-form')?></h2>
        </div>
        <div class="panel-body grid">
          <div class="dplr_input_section awa-form" id ="doble_optin_section">
            <label for="settings[form_doble_optin]"><?php _e('Choose Opt-In type:', 'doppler-form')?>
              <span class="req"><?php _e('(Required)', 'doppler-form') ?></span>
              <div class="dp-select">
                <span class="dropdown-arrow"></span>
                <select name="settings[form_doble_optin]" id="settings[form_doble_optin]">
                  <option <?php if(isset($form['settings']['form_doble_optin']) && $form['settings']['form_doble_optin'] === 'no') echo 'selected="selected"';?> value="no">Simple Opt-in</option>
                  <option <?php if(isset($form['settings']['form_doble_optin']) && $form['settings']['form_doble_optin'] === 'yes') echo 'selected="selected"';?> value="yes">Doble Opt-in</option>
                </select>
              </div>
            </label>
          </div>
          <p>
            <?php _e('If you\'d like to know the difference between Simple Opt-In and Double Opt-In, click:', 'doppler-form')?>
            <a href="https://help.fromdoppler.com/es/diferencias-entre-simple-y-doble-opt-in"><?php _e('HELP', 'doppler-form') ?></a>.
          </p>

          <div id="dplr_doble_opt_in_section">
            <div class="dplr_input_section" id="section_email_confirmacion">
              <h2><?php _e('Confirmation email', 'doppler-form') ?></h2>
            <div class="dplr_input_section">
              <label for="settings[form_email_confirmacion_asunto]"><?php _e('Subject', 'doppler-form')?> <span class="req"><?php _e('(Required)', 'doppler-form') ?></span></label>
              <input type="text" id="form_email_confirmacion_asunto" name="settings[form_email_confirmacion_asunto]" value="<?php echo isset($form['settings']["form_email_confirmacion_asunto"])?$form['settings']["form_email_confirmacion_asunto"]:'' ?>" placeholder="<?php _e('This is the subject of the email.', 'doppler-form')?>" maxlength="40"/>
            </div>
            <div class="dplr_input_section">
              <label for="settings[form_email_confirmacion_pre_encabezado]"><?php _e('Pre header', 'doppler-form')?></label>
            <input type="text" name="settings[form_email_confirmacion_pre_encabezado]" value="<?php echo isset($form['settings']["form_email_confirmacion_pre_encabezado"])?$form['settings']["form_email_confirmacion_pre_encabezado"]:'' ?>" placeholder="<?php _e('This is the email\'s pre header', 'doppler-form')?>" maxlength="40"/>
            </div>
            <div class="dplr_input_section">
              <label for="settings[form_email_confirmacion_email_remitente]"><?php _e('Email sender', 'doppler-form')?> <span class="req"><?php _e('(Required)', 'doppler-form') ?></span></label>
              <input type="email" id="form_email_confirmacion_email_remitente" name="settings[form_email_confirmacion_email_remitente]" value="<?php echo isset($form['settings']["form_email_confirmacion_email_remitente"])?$form['settings']["form_email_confirmacion_email_remitente"]:'' ?>" placeholder="<?php _e('Example: some_direction@hotmail.com', 'doppler-form')?>" maxlength="40"/>
            </div>
            <div class="dplr_input_section">
              <label for="settings[form_email_confirmacion_nombre_remitente]"><?php _e('Email sender name', 'doppler-form')?> <span class="req"><?php _e('(Required)', 'doppler-form') ?></span></label>
              <input type="text" id="form_email_confirmacion_nombre_remitente" name="settings[form_email_confirmacion_nombre_remitente]" value="<?php echo isset($form['settings']["form_email_confirmacion_nombre_remitente"])?$form['settings']["form_email_confirmacion_nombre_remitente"]:'' ?>" placeholder="<?php _e('Example: Josh', 'doppler-form')?>" maxlength="40"/>
            </div>
            <input type="hidden" id="form_name" name="settings[form_name]" maxlength="40"/>
            <div class="dplr_input_section">
              <label for="settings[form_email_reply_to]"><?php _e('Email reply-to', 'doppler-form')?></label>
              <input type="text" name="settings[form_email_reply_to]" value="<?php echo isset($form['settings']["form_email_reply_to"])?$form['settings']["form_email_reply_to"]:'' ?>" placeholder="<?php _e('Example: something-reply-to@hotmail.com', 'doppler-form')?>" maxlength="40"/>
            </div>
            <div class="dplr_input_section">
              <label for="settings_form_email_confirmacion_email_contenido"><?php _e('Email content. Must obligatorily have an anchor element with the attribute: href=[[[ConfirmationLink]]]', 'doppler-form')?> <span class="req"><?php _e('(Required)', 'doppler-form') ?></span></label>
              <?php 
                // wp_tiny_mce($form->settings["form_email_confirmacion_email_contenido"], 'settings_form_email_confirmacion_email_contenidotings[form_email_confirmacion_email_contenido]');
                // the_editor(); 
                $settings = array(
                  'textarea_name' => 'content',
                  'media_buttons' => true,
                  'tinymce' => array(
                  'init_instance_callback' => 'function(editor) {
                              editor.on("blur", function(){
                                var content = tinymce.activeEditor.getContent();
                                validateEmailContent(null, content);
                            });
                      }'
                  )
                );
                wp_editor( isset($form["content"])?stripslashes(html_entity_decode($form["content"])):'', 'content', $settings );
              ?>
            </div>
          </div>

          <div class="dplr_input_section" id="section_pagina_confirmacion">
            <h2><?php _e('Confirmation page', 'doppler-form');?></h2>
            <label for="settings[form_pagina_confirmacion]"><?php _e('Choose an option:', 'doppler-form')?> <span class="req"><?php _e('(Required)', 'doppler-form') ?></span></label>
            <div class="radio-inputs-landing-or-url">
              <div class="radio_section">
                <div class="dp-input--radio">
                  <label aria-disabled="false">
                    <input type="radio"
                      id="mostrar_landing" 
                      name="settings[form_pagina_confirmacion]"
                      value="landing"
                      <?php if(isset($form['settings']['form_pagina_confirmacion']) && $form['settings']['form_pagina_confirmacion']==='landing') echo 'checked'?>/>
                    <span><?php _e('Redirect to landing page', 'doppler-form');?></span>
                  </label>
                </div>
                <div class="dp-input--radio">
                  <label aria-disabled="false">
                    <input type="radio"
                      id="mostrar_url"
                      name="settings[form_pagina_confirmacion]"
                      value="url"
                      <?php if(isset($form['settings']['form_pagina_confirmacion']) && $form['settings']['form_pagina_confirmacion']==='url') echo 'checked'?>/>
                    <span><?php _e('Redirect to URL (must have https:// prefix!).', 'doppler-form');?></span>
                  </label>
                </div>
              </div>
            </div>
            <div id="div_url_destino">
              <label for="settings[form_pagina_confirmacion_url]"><?php _e('Target URL', 'doppler-form')?> <span class="req"></span></label>
              <input type="text" id="form_pagina_confirmacion_url" name="settings[form_pagina_confirmacion_url]" value="<?php echo isset($form['settings']["form_pagina_confirmacion_url"])?$form['settings']["form_pagina_confirmacion_url"]:'' ?>" placeholder="<?php _e('Example: https://www.fromdoppler.com', 'doppler-form')?>" maxlength="150"/>
            </div>
              
            <div id="div_landing_page" class="awa-form">
              <label for="settings[form_pagina_confirmacion_select_landing]"><?php _e('Choose the page:', 'doppler-form')?>
              <span class="req"></span>
              <div class="dp-select">
                <span class="dropdown-arrow"></span>
                <select name="settings[form_pagina_confirmacion_select_landing]" id="settings[form_pagina_confirmacion_url]">
                  <?php
                    $pages = get_pages();
                    foreach($pages as $page):
                        if(isset($form['settings']["form_pagina_confirmacion_select_landing"]) && $form['settings']["form_pagina_confirmacion_select_landing"] == $page->ID){
                        ?>
                          <option selected value="<?php echo $page->ID ?>"><?php echo $page->post_title ?></option>
                        <?php
                        }
                        else{
                        ?>
                          <option value="<?php echo $page->ID ?>"><?php echo $page->post_title ?></option>
                        <?php
                        }
                      ?>
                      <?php
                    endforeach;
                  ?>
                </select>
              </div>
              </label>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="grid" id="dplr_consent_section">
      <div class="col-4-5 panel nopd dp-box-shadow">
        <div class="panel-header">
          <h2><?php _e('Consent Field settings', 'doppler-form')?></h2>
        </div>
        <div class="panel-body grid">
          <div class="dp-rowflex">
            <div class="col-sm-9 col-md-9 col-lg-9
            m-b-12">
              <span class="hlp"><?php _e('Automatically include checkboxes for opt-in, editable text and link that allow you to explain how and why you are using contact data. Whant to know more? Press','doppler-form')?> <?= '<a href="'.__('https://help.fromdoppler.com/en/general-data-protection-regulation?utm_source=landing&utm_medium=integracion&utm_campaign=wordpress', 'doppler-form').'" target="blank">'.__('HELP','doppler-form').'</a>'?>.</span>
            </div>
            <div class="col-sm-3 col-md-3 col-lg-3 m-b-12">
              <button type="button" id="gdpr_add_button" class="dp-button primary-green button-medium button-right"><?php _e('Add new consent','doppler-form') ?></button>
            </div>
          </div>
          <ul class="accordion panel-body grid mt-1" id="gdpr_section"></ul>
        </div>
      </div>
    </div>
    
    <div id="error-message" class="dp-wrap-message dp-wrap-cancel m-b-12 d-none">
      <span class="dp-message-icon"></span>
      <div class="dp-content-message">
        <p><?php _e('Error! Remember that the email\'s content must obligatorily have an anchor element with the attribute: href=[[[ConfirmationLink]]]', 'doppler-form'); ?></p>
      </div>
    </div>

    <button id="submit_button" type="submit" name="form-create" class="dp-button primary-green button-medium">
      <?php _e('Save', 'doppler-form')?>
    </button>
    <a href="<?php echo admin_url('admin.php?page=doppler_forms_main')?>"  class="dp-button primary-grey button-medium"><?php _e('Cancel', 'doppler-form')?></a>
  </form>

</div>
<script type="text/javascript">
var gdprAmount = 0;

function hideShowConfigDobleOptIn(){
  if(document.getElementById("settings[form_doble_optin]").value === 'yes'){
    document.getElementById("dplr_doble_opt_in_section").style.display = "block";

    document.getElementById("form_email_confirmacion_asunto").required = true;
    document.getElementById("form_email_confirmacion_email_remitente").required = true;
    document.getElementById("form_email_confirmacion_nombre_remitente").required = true;
    document.getElementById("form_name").required = false;
    document.getElementById("mostrar_landing").required = true;
    document.getElementById("mostrar_url").required = true;
  }
  else{
    document.getElementById("dplr_doble_opt_in_section").style.display = "none";

    document.getElementById("form_email_confirmacion_asunto").required = false;
    document.getElementById("form_email_confirmacion_email_remitente").required = false;
    document.getElementById("form_email_confirmacion_nombre_remitente").required = false;
    document.getElementById("form_name").required = false;
    document.getElementById("mostrar_landing").required = false;
    document.getElementById("mostrar_url").required = false;
  }
}

window.onload = function(){
  hideShowConfigDobleOptIn();
  hideShowConfigLandingOrURL();
  removeQuoteMarksFromConfirmationLink();
}

String.prototype.replaceHtmlEntites = function() {
  var s = this;
  var translate_re = /&(nbsp|amp|quot|lt|gt);/g;
  var translate = {"nbsp": " ","amp" : "&","quot": "\"","lt"  : "<","gt"  : ">"};
  return ( s.replace(translate_re, function(match, entity) {
    return translate[entity];
  }) );
};

document.getElementById('wp-content-wrap').addEventListener("click", function(){
  document.getElementById('content').value = document.getElementById('content').value.replaceHtmlEntites();
  removeQuoteMarksFromConfirmationLink();
});

document.getElementById("submit_button").addEventListener("click", function(){
  document.getElementById('content').value = document.getElementById('content').value.replaceHtmlEntites();
  if(document.getElementById("settings[form_doble_optin]").value === 'yes'){
    jQuery(".wp-editor-wrap .switch-tmce").trigger("click");
    var content = tinymce.activeEditor.getContent();
    validateEmailContent(event, content);
  }
});

document.getElementById("doble_optin_section").addEventListener("click", function(){
  hideShowConfigDobleOptIn();
  if(document.getElementById("settings[form_doble_optin]").value !== 'yes'){
    document.getElementById("error-message").classList.add('d-none');
  }
});

document.getElementById("section_pagina_confirmacion").addEventListener("click", function(){
  hideShowConfigLandingOrURL();
});

var all_fields = <?php echo json_encode($dplr_fields); ?>;
all_fields = jQuery.grep(all_fields, function(el, idx) {return el.type == "consent"}, true)
var form_fields = <?php echo json_encode($fields); ?>;
var view = new FormFieldsView(all_fields, form_fields, jQuery("#fieldList"), jQuery("#formFields"));
</script>
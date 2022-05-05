<div class="dplr dplr-tab-content dplr-tab-content--form-create">
  
  <form method="post" action="<?php admin_url() ?>admin.php?page=doppler_forms_main">
    
    <input type="hidden" name="create" value="true">
    
    <div class="grid">
      <div class="col-4-5 panel nopd">
        <div class="panel-header">
          <h2><?php _e('Form basic information', 'doppler-form')?></h2>
        </div>
        <div class="panel-body">
          <div class="dplr_input_section">
            <label for="name"><?php _e('Name', 'doppler-form')?> <span class="req">(Obligatorio)</span></label>
            <input type="text" name="name" placeholder="" value="" required maxlength="80"/>
          </div>
          <div class="dplr_input_section">
            <label for="list_id"><?php _e('Doppler List', 'doppler-form')?> <span class="req">(Obligatorio)</span></label>
            <select class="" name="list_id" id="list-id" required>
              <option value=""><?php _e('Select the destination List where your new Subscribers will be sent', 'doppler-form'); ?></option>
              <?php 
                for ($i=0; $i < count($dplr_lists); $i++) { 
                ?><option value="<?php echo $dplr_lists[$i]->listId; ?>"><?php echo trim($dplr_lists[$i]->name); ?></option><?php
                }
              ?>
            </select>
          </div>
      </div>
    </div>
    
    <div class="grid">
      <div class="col-4-5 panel nopd">
        <div class="panel-header">
          <h2><?php _e('Form Fields', 'doppler-form')?></h2>
        </div>
        <div class="panel-body grid">
          <div class="col-1-2 dplr_input_section">
            <label for="list_id"><?php _e('Fields to include', 'doppler-form')?>  <span class="hlp"><?php _e('Learn how to create Custom Fields with Doppler. Press', 'doppler-form')?> <a href="<?php _e('https://help.fromdoppler.com/en/how-to-create-a-customized-field/?utm_source=landing&utm_medium=integracion&utm_campaign=wordpress', 'doppler-form')?>" clasS="green-link" target="_blank"><?php _e('HELP', 'doppler-form')?></a>.</span></label>
            <select id="fieldList" class="" name="">
              <option value=""><?php _e('Select the Fields that will appear on your Form', 'doppler-form')?></option>
            </select>
          </div>
          <div class="col-1-2">
            <span class="noti"><?php _e('Drag and drop the Fields to give them the order you want', 'doppler-form')?></span>
            <ul class="sortable accordion" id="formFields">
            </ul>
          </div>
        </div>
      </div>
    </div>
    
    <div class="grid">
      <div class="col-4-5 panel nopd">
        <div class="panel-header">
          <h2><?php _e('Form settings', 'doppler-form')?></h2>
        </div>
        <div class="panel-body grid">
          <div class="dplr_input_section">
            <label for="title"><?php _e('Title', 'doppler-form')?></label>
            <input type="text" name="title" placeholder="<?php _e('Subscribe to our Newsletter!', 'doppler-form')?>" value="" maxlength="150"/>
          </div>
          <div class="dplr_input_section">
            <label for="submit_text"><?php _e('Button text', 'doppler-form')?></label>
            <input type="text" name="settings[button_text]" value="" placeholder="<?php _e('Submit', 'doppler-form')?>" maxlength="40"/>
          </div>
          <div class="dplr_input_section">
            <label for="settings[button_position]"><?php _e('Button alignment', 'doppler-form')?></label>
            <select class="" name="settings[button_position]">
              <option value="left"><?php _e('Left', 'doppler-form')?></option>
              <option value="center"><?php _e('Center', 'doppler-form')?></option>
              <option value="right"><?php _e('Right', 'doppler-form')?></option>
              <option value="fill"><?php _e('Full width', 'doppler-form')?></option>
            </select>
          </div>
          <div class="dplr_input_section">
            <label for="settings[change_button_bg]"><?php _e('Button background color', 'doppler-form')?></label>
            <div class="radio_section">
              <?php _e('Use my theme\'s default color', 'doppler-form')?><input type="radio" name="settings[change_button_bg]" class="dplr-toggle-selector" value="no" checked>&nbsp; 
              <?php _e('Choose another color', 'doppler-form')?><input type="radio" name="settings[change_button_bg]" class="dplr-toggle-selector" value="yes"> 
              <input  class="color-selector d-none" 
                      type="text" 
                      pattern="^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$" 
                      name="settings[button_color]"
                      oninvalid="setCustomValidity(object_string.hexValidationError)"
                      oninput="setCustomValidity('')" 
                      value=""/>
            </div>   
          </div>
          <div class="dplr_input_section">
            <label for="settings[use_thankyou_page]"><?php _e('What do you want to show to your users after submitting the Form?', 'doppler-form')?></label>
            <div class="radio_section">
              <?php _e('Custom confirmation page', 'doppler-form')?><input type="radio" name="settings[use_thankyou_page]" class="dplr-toggle-thankyou" value="yes">&nbsp; 
              <?php _e('Confirmation message', 'doppler-form')?><input type="radio" name="settings[use_thankyou_page]" class="dplr-toggle-thankyou" value="no" checked> 
            </div>
          </div>
          <div class="dplr_input_section dplr_confirmation_message">
            <label for="submit_text"><?php _e('Confirmation message', 'doppler-form')?></label>
            <input type="text" name="settings[message_success]" value="" placeholder="<?php _e('Thanks for subscribing!', 'doppler-form')?>" maxlength="150"/>
          </div>
          <div class="dplr_input_section dplr_thankyou_url d-none">
            <label for="submit_text"><?php _e('Custom confirmation page URL', 'doppler-form')?> <span class="hlp"><?php _e('Enter the URL of the page that you\'ve created.', 'doppler-form')?></span></label>
            <input type="url" name="settings[thankyou_page_url]" value="" pattern="https?://.+" placeholder="" maxlength="150"/>
          </div>
          <div class="dplr_input_section">
            <label for="settings[use_consent_field]"><?php _e('Consent Field (GDPR)', 'doppler-form')?> <!--<span class="hlp"><?php _e('What is it? Press','doppler-form')?> <?= '<a href="'.__('https://help.fromdoppler.com/en/general-data-protection-regulation?utm_source=landing&utm_medium=integracion&utm_campaign=wordpress', 'doppler-form').'" target="blank">'.__('HELP','doppler-form').'</a>'?>.</span>--></label>
            <div class="radio_section">
              <?php _e('Yes', 'doppler-form')?><input type="radio" name="settings[use_consent_field]" class="dplr-toggle-consent" value="yes" checked>&nbsp; 
              <?php _e('No', 'doppler-form')?><input type="radio" name="settings[use_consent_field]" class="dplr-toggle-consent" value="no"> 
            </div>
          </div>
          <div class="dplr_input_section">
            <label for="settings[form_orientation]"><?php _e('Form orientation', 'doppler-form')?> <span class="req">(Obligatorio)</span></label>
            <div class="form_orientation">
              <div style="display: flex; align-items: center;">
                <label for="vertical"><?php _e('Vertical','doppler-form')?></label>
                <input type="radio" name="settings[form_orientation]" value="vertical" checked/>
              </div>
              <div style="display: flex; align-items: center;">
                <label for="horizontal"><?php _e('Horizontal','doppler-form')?></label>
                <input type="radio" name="settings[form_orientation]" value="horizontal"/>
              </div>
            </div>
          </div>
          
          

          
        </div>
      </div>
    </div>



    <div class="grid" id="dplr_doble_opt_in_section">
      <div class="col-4-5 panel nopd">
        <div class="panel-header">
          <h2><?php _e('Tipo de suscripción', 'doppler-form')?></h2>
        </div>
        <div class="panel-body grid">
          <div class="dplr_input_section" id ="doble_optin_section">
              <label for="settings[form_doble_optin]"><?php _e('Choose Opt-In type:', 'doppler-form')?> <span class="req">(Obligatorio)</span></label>
              <select name="settings[form_doble_optin]" id="settings[form_doble_optin]">
                <?php 
                  if($form->settings['form_doble_optin'] === 'yes'): 
                ?>
                  <option value="no">Simple Opt-in</option>
                  <option selected value="yes">Doble Opt-in</option>
                <?php 
                  else: 
                ?>
                  <option selected value="no">Simple Opt-in</option>
                  <option value="yes">Doble Opt-in</option>
                <?php
                endif;
                ?>
              </select>
              <p>
                ¡Psst! Necesitas seleccionar la Lista a la que serán enviados tus nuevos Suscriptores y también configurar los Emails de confirmación y bienvenida.
              </p>
              <p>
                ¿Quieres saber la diferencia entre Simple y Doble Opt-In? Presiona <a href="https://help.fromdoppler.com/es/diferencias-entre-simple-y-doble-opt-in">HELP</a>.
              </p>

            </div>
          <div class="dplr_input_section" id="section_email_confirmacion">
            <h2>Email de confirmacion</h2>
            <label for="settings[form_email_confirmacion_asunto]"><?php _e('Asunto', 'doppler-form')?> <span class="req">(Obligatorio)</span></label>
            <input type="text" name="settings[form_email_confirmacion_asunto]" value="<?php echo $form->settings["form_email_confirmacion_asunto"] ?>" placeholder="<?php _e('Asunto', 'doppler-form')?>" maxlength="40" required/>

            <label for="settings[form_email_confirmacion_pre_encabezado]"><?php _e('Pre encabezado', 'doppler-form')?> <span class="req">(Obligatorio)</span></label>
            <input type="text" name="settings[form_email_confirmacion_pre_encabezado]" value="<?php echo $form->settings["form_email_confirmacion_pre_encabezado"] ?>" placeholder="<?php _e('Pre encabezado', 'doppler-form')?>" maxlength="40" required/>

            <label for="settings[form_email_confirmacion_email_remitente]"><?php _e('Email del remitente', 'doppler-form')?> <span class="req">(Obligatorio)</span></label>
            <input type="email" name="settings[form_email_confirmacion_email_remitente]" value="<?php echo $form->settings["form_email_confirmacion_email_remitente"] ?>" placeholder="<?php _e('Email del remitente', 'doppler-form')?>" maxlength="40" required/>

            <label for="settings[form_email_confirmacion_nombre_remitente]"><?php _e('Nombre del remitente', 'doppler-form')?> <span class="req">(Obligatorio)</span></label>
            <input type="text" name="settings[form_email_confirmacion_nombre_remitente]" value="<?php echo $form->settings["form_email_confirmacion_nombre_remitente"] ?>" placeholder="<?php _e('Nombre del remitente', 'doppler-form')?>" maxlength="40" required/>

            <label for="settings[form_email_confirmacion_email_contenido]"><?php _e('Contenido del email. Tiene que tener, obligatoriamente, un anchor element con: href=[[[ConfirmationLink]]]', 'doppler-form')?> <span class="req">(Obligatorio)</span></label>
            <textarea 
              name="settings[form_email_confirmacion_email_contenido]" 
              id="settings[form_email_confirmacion_email_contenido]" 
              placeholder="<?php _e('Contenido del email (HTML). Tiene que tener, obligatoriamente, un anchor element con: href=[[[ConfirmationLink]]]', 'doppler-form')?>" 
              rows="25" 
              cols="50"
              required
            ><?php echo $form->settings["form_email_confirmacion_email_contenido"] ?></textarea>
          </div>

          <div class="dplr_input_section" id="section_pagina_confirmacion">
            <h2>Pagina de confirmacion</h2>

            <label for="settings[form_pagina_confirmacion]"><?php _e('Elija la opcion:', 'doppler-form')?> <span class="req">(Obligatorio)</span></label>
            <div class="radio-inputs-landing-or-url">
              <div style="display: flex; align-items: center;">
                <label for="yes">Mostrar landing page</label>
                <input 
                type="radio" 
                id="mostrar_landing" 
                name="settings[form_pagina_confirmacion]" 
                value="landing" 
                <?php if($form->settings['form_pagina_confirmacion']==='landing') echo 'checked'?> />
              </div>
              <div style="display: flex; align-items: center;">
                <label for="no">Enviar a URL (obligatoriamente con el prefijo https)</label>
                <input type="radio" name="settings[form_pagina_confirmacion]" value="url" <?php if($form->settings['form_pagina_confirmacion']==='url') echo 'checked'?> />
              </div>
            </div>

            <div id="div_url_destino">
              <label for="settings[form_pagina_confirmacion_url]"><?php _e('URL de destino', 'doppler-form')?> <span class="req"></span></label>
              <input type="text" name="settings[form_pagina_confirmacion_url]" value="<?php echo $form->settings["form_pagina_confirmacion_url"] ?>" placeholder="<?php _e('Example: https://www.fromdoppler.com', 'doppler-form')?>" maxlength="40"/>
            </div>
            
            <div id="div_landing_page">
              <label for="settings[form_pagina_confirmacion_select_landing]"><?php _e('Landing page a mostrar:', 'doppler-form')?> <span class="req"></span></label>
              <select name="settings[form_pagina_confirmacion_select_landing]" id="settings[form_pagina_confirmacion_url]">
                <?php
                  $pages = get_pages();
                  foreach($pages as $page):
                      if($form->settings["form_pagina_confirmacion_select_landing"] == $page->ID){
                      ?>
                        <option selected value="<?php echo $page->ID ?>"><?php echo $page->post_title ?></option>
                      <?
                      }
                      else{
                      ?>
                        <option value="<?php echo $page->ID ?>"><?php echo $page->post_title ?></option>
                      <?
                      }
                    ?>
                    <?
                  endforeach;
                ?>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>





    
    <div class="grid" id="dplr_consent_section">
      <div class="col-4-5 panel nopd">
        <div class="panel-header">
          <h2><?php _e('Consent Field settings', 'doppler-form')?></h2>
        </div>
        <div class="panel-body grid">
            <div class="dplr_input_section">
              <label for="settings[consent_field_text]"><?php _e('Checkbox label', 'doppler-form')?></label>
              <input type="text" name="settings[consent_field_text]" value="" placeholder="<?php _e("I've read and accept the privacy policy", "doppler-form")?>" maxlength="150"/>
            </div>
            <div class="dplr_input_section">
              <label for="settings[consent_field_url]">
                <?php _e('Enter the URL of your privacy policy', 'doppler-form'); ?> 
              </label>
              <input type="url" name="settings[consent_field_url]" pattern="https?://.+" value="" placeholder="" maxlength="150"/>
            </div>
        </div>
      </div>
    </div>
    
    <p id="error-message" class="error-message">
        Error! Recuerde que el contenido del email tiene que tener, obligatoriamente, un anchor element con: href=[[[ConfirmationLink]]]
      </p>
    <input id="submit_button" type="submit" name="form-create" value="<?php _e('Save', 'doppler-form')?>" class="dp-button primary-green button-medium"/> <a href="<?php echo admin_url('admin.php?page=doppler_forms_main')?>"  class="dp-button primary-grey button-medium"><?php _e('Cancel', 'doppler-form')?></a>
  
  </form>

</div>
<script type="text/javascript">

function hideShowConfigDobleOptIn(){
  if(document.getElementById("settings[form_doble_optin]").value === 'yes'){
    document.getElementById("section_email_confirmacion").style.display = "block";
    document.getElementById("section_pagina_confirmacion").style.display = "block";
  }
  else{
    document.getElementById("section_email_confirmacion").style.display = "none";
    document.getElementById("section_pagina_confirmacion").style.display = "none";
  }
}

function hideShowConfigLandingOrURL(){
  if(document.getElementById("mostrar_landing").checked){
    document.getElementById("div_landing_page").style.display = "block";
    document.getElementById("div_url_destino").style.display = "none";
  }
  else{
    document.getElementById("div_landing_page").style.display = "none";
    document.getElementById("div_url_destino").style.display = "block";
  }
}

function validateEmailContent(e){
  if(document.getElementById("settings[form_doble_optin]").value === 'yes'){
    if(!document.getElementById("settings[form_email_confirmacion_email_contenido]").value.includes("href=[[[ConfirmationLink]]]")){
      // display div con mensaje de error
      document.getElementById("error-message").style.display = "block";
      // cancelar el submit del formulario
      e.preventDefault();
    }
    else{
      document.getElementById("error-message").style.display = "none";
    }
  }
  if(document.getElementById("settings[form_doble_optin]").value !== 'yes'){
    document.getElementById("error-message").style.display = 'none';
  }
}

window.onload = function(){
  hideShowConfigDobleOptIn();
  hideShowConfigLandingOrURL();
}

document.getElementById("submit_button").addEventListener("click", function(){
  if(document.getElementById("settings[form_doble_optin]").value === 'yes'){
    validateEmailContent(event);
  }
});

document.getElementById("doble_optin_section").addEventListener("click", function(){
  hideShowConfigDobleOptIn();
  if(document.getElementById("settings[form_doble_optin]").value !== 'yes'){
    document.getElementById("error-message").style.display = 'none';
  }
});

document.getElementById("settings[form_email_confirmacion_email_contenido]").addEventListener("blur", function(){
  validateEmailContent(event);
});

document.getElementById("section_pagina_confirmacion").addEventListener("click", function(){
  hideShowConfigLandingOrURL();
});

var all_fields = <?php echo json_encode($dplr_fields); ?>;
all_fields = jQuery.grep(all_fields, function(el, idx) {return el.type == "consent"}, true)
var form_fields = [];
var view = new FormFieldsView(all_fields, form_fields, jQuery("#fieldList"), jQuery("#formFields"));
</script>
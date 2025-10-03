<?php
require_once(plugin_dir_path( __FILE__ ) . "../enums/EventType.php");

class DPLR_Form_helper
{
	public static function generate($context, $options = NULL) {
		$doppler_settings = get_option('dplr_settings');
		if (!$doppler_settings) {
			return '<div class="dplr_settings_not_founded">El formulario no se puede mostrar porque la cuenta no está conectada a Doppler API!</div>';
		}
	
		$form = $context['form'];
		$fields = isset($context['fields']) ? $context['fields'] : [];
		$form_class = isset($context['classes']) ? implode(" ", $context['classes']) : "";
		$form_orientation_horizontal = isset($form->settings["form_orientation"]) && $form->settings["form_orientation"] === 'horizontal';

		self::registerDisplayEvent($form->id);
	
		ob_start();
	
		?>
		<form class="dplr_form <?php echo esc_attr($form_class); ?>">
			<?php if ($form_orientation_horizontal): ?>
				<div class="container">
					<input type="hidden" name="list_id" value="<?php echo esc_attr($form->list_id); ?>">
					<input type="hidden" name="form_id" value="<?php echo esc_attr($form->id); ?>">
					<?php foreach ($fields as $field) :
						$label = isset($field->settings['label']) ? $field->settings['label'] : $field->name; ?>
						<div class="<?php echo ($field->type !== "permission") ? "flex-item" : ""; ?> input-field <?php echo isset($field->settings['required']) ? 'required' : ''; ?>">
							<?php if ($label !== ''): ?>
								<?php if ($field->type !== 'permission'): ?>
									<label for="<?php echo esc_attr($field->name); ?>" class="horizontal_label">
										<?php echo esc_html($label);; ?>
									</label>
								<?php endif; ?>
							<?php endif;
							if ($field->type !== 'permission'):
								echo esc_html(self::printInput($field, $form, $label, $form_orientation_horizontal));
							endif; ?>
						</div>
					<?php endforeach; ?>
				</div>
				<?php foreach ($fields as $field):
					if ($field->type === "permission"): ?>
						<label for="<?php echo esc_attr($field->name); ?>">
							<?php echo esc_html($field->settings["label"]); ?>
						</label>
						<?php echo esc_html(self::printInput($field, $form, $label, $form_orientation_horizontal));
					endif;
				endforeach;
				if (isset($form->settings['use_consent_field']) && $form->settings['use_consent_field'] === 'yes'): ?>
					<div class="input-field consent_field" required>
						<input type="checkbox" name="fields-CONSENT" value="true" required/>
						<?= isset($form->settings['consent_field_text']) && !empty($form->settings['consent_field_text']) ? esc_html($form->settings['consent_field_text']) : esc_html_e("I've read and accept the privacy policy", "doppler-form");
						if (isset($form->settings['consent_field_url']) && !empty($form->settings['consent_field_url'])): ?>
							<a href="<?= esc_url($form->settings['consent_field_url']) ?>"><?php esc_html_e('Read more', 'doppler-form') ?></a>
						<?php endif; ?>
					</div>
				<?php endif;
				if (isset($form->settings['use_thankyou_page']) && $form->settings['use_thankyou_page'] === 'yes'): ?>
					<input type="hidden" value="<?php echo esc_attr($form->settings['thankyou_page_url']) ?>" name="thankyou"/>
				<?php endif;
			else: ?>
				<div>
					<input type="hidden" name="list_id" value="<?php echo esc_attr($form->list_id); ?>">
					<input type="hidden" name="form_id" value="<?php echo esc_attr($form->id); ?>">
					<?php foreach ($fields as $field) :
						$label = isset($field->settings['label']) ? $field->settings['label'] : $field->name; ?>
						<div class="input-field <?php echo isset($field->settings['required']) ? 'required' : ''; ?>">
							<?php if ($label !== ''): ?>
								<?php if ($field->type !== 'permission'): ?>
									<label for="<?php echo esc_attr($field->name); ?>">
										<?php echo esc_html($label); ?>
									</label>
								<?php endif; ?>
							<?php endif;
							echo esc_html(self::printInput($field, $form, $label, $form_orientation_horizontal)); ?>
						</div>
					<?php endforeach;
					if (isset($form->settings['use_consent_field']) && $form->settings['use_consent_field'] === 'yes'): ?>
						<div class="input-field consent_field" required>
							<input type="checkbox" name="fields-CONSENT" value="true" required/>
							<?= isset($form->settings['consent_field_text']) && !empty($form->settings['consent_field_text']) ? esc_html($form->settings['consent_field_text']) : esc_html_e("I've read and accept the privacy policy", "doppler-form");
							if (isset($form->settings['consent_field_url']) && !empty($form->settings['consent_field_url'])): ?>
								<a href="<?= esc_url($form->settings['consent_field_url']) ?>"><?php esc_html_e('Read more', 'doppler-form') ?></a>
							<?php endif; ?>
						</div>
					<?php endif;
					if (isset($form->settings['use_thankyou_page']) && $form->settings['use_thankyou_page'] === 'yes'): ?>
						<input type="hidden" value="<?php echo esc_url($form->settings['thankyou_page_url']) ?>" name="thankyou"/>
					<?php endif; ?>
				</div>
			<?php endif;
			if(!isset($form->settings['use_consent_field']) 
			&& isset($form->settings['consent_field_text']) 
			&& isset($form->settings['consent_field_url'])
			) {
				$consentTextArray = explode("||", $form->settings["consent_field_text"]);
				$consentUrlArray = explode("||", $form->settings["consent_field_url"]);

				foreach ($consentTextArray as $key => $value)
				{ 
					if (!empty($value))
					{  ?>
					<div class="input-field consent_field" required>
						<input type="checkbox" name="fields-CONSENT" value="true" required/>
						<?php echo esc_html($value) ?>
						<a href="<?= esc_url($consentUrlArray[$key]) ?>"><?php esc_html_e('Read more', 'doppler-form')?></a>
					</div>
					<?php }
				}
			}?>

			<input type="text" name="secondary-dplrEmail" value="" class="dplr-secondary-email"/>
			<label class="msg-data-sending"><?php echo isset($form->settings["message_success"]) ? esc_html($form->settings["message_success"]) : esc_html_e('Thanks for subscribing', 'doppler-form'); ?></label>
			<div class="input-button">
				<button 
				type="submit" 
				name="submit" 
				class="<?php echo isset($form->settings["button_position"]) ? esc_attr($form->settings["button_position"]) : 'left'; ?>"
				style="<?php echo isset($form->settings["button_color"]) && !empty(trim($form->settings["button_color"])) ? "background: ". esc_attr($form->settings["button_color"]) .";" : ""; ?>"
				>
					<img src="<?php echo esc_url(plugin_dir_url(__FILE__)) ?>../../public/img/spinner.svg"/>
					<span><?php echo isset($form->settings["button_text"]) ? esc_html($form->settings["button_text"]) : esc_html_e('Submit', 'doppler-form'); ?></span>
				</button>
			</div>
		</form>
		<?php
	
		return ob_get_clean();
	}

  	private static function printInput($input, $form, $label, $form_orientation_horizontal) {

		$required = isset($input->settings["required"]) ? "required" : "";
    	switch ($input->type) {
		case 'string':
			if (isset($input->settings['text_lines']) && $input->settings['text_lines'] == 'multi')
			{
				?>
					<textarea <?=esc_attr($required)?>
						name="fields-<?php echo esc_attr($input->name . '-' . $form->id); ?>"
						placeholder="<?php echo isset($input->settings['placeholder']) ? esc_attr($input->settings['placeholder']) : ''; ?>"
						rows="3"
						cols="80"
						maxlength="150">
					</textarea>
				<?php 
			}
			else if (isset($input->settings['text_lines']) && $input->settings['text_lines'] == 'options')
			{
				?>
					<select <?=esc_attr($required)?>
						name="fields-<?php echo esc_attr($input->name . '-' . $form->id); ?>">
						<?php echo (isset($input->settings['placeholder']) && $input->settings['placeholder'] !== '' )
							? '<option value="">' . esc_html($input->settings['placeholder']) . '</option>' 
							: ''; 
						?>
						<?php if (isset($input->settings['text_options']) && $input->settings['text_options'] !== '') {
							$lines = explode("\n", $input->settings['text_options']);
							foreach (array_map('trim', $lines) as $line) {
								echo '<option value="' . esc_attr($line) . '">' . esc_html($line) . '</option>';
							}
						} ?>
					</select>
				<?php 
			}
			else 
			{
				?>
					<input <?=esc_attr($required)?>
					type="text"
					name="fields-<?php echo esc_attr($input->name . '-' . $form->id); ?>"
					placeholder="<?php echo isset($input->settings['placeholder']) ? esc_attr($input->settings['placeholder']) : ''; ?>"
					maxlength="150"/>
				<?php
			}
			break;
		case 'number':?>
			<input <?=esc_attr($required)?>
			type="number"
			oninvalid="this.setCustomValidity('<?php esc_html_e('Please enter only numbers.', 'doppler-form') ?>')"
			pattern="[0-9]"
			name="fields-<?php echo esc_attr($input->name . '-' . $form->id); ?>"
			oninput="this.setCustomValidity('')"
			value=""
			placeholder="<?php echo $form_orientation_horizontal ? esc_attr(isset($input->settings['placeholder'])) 
					? esc_attr($input->settings['placeholder'])
					: ''
				: esc_attr($label) ?>"
			maxlength="27"/>
			<?php
			break;
		case 'phone':?>
				<input <?=esc_attr($required)?>
				type="tel"
				class = "phone-doppler"
				name="fields-<?php echo esc_attr($input->name . '-' . $form->id); ?>"
				oninput="this.setCustomValidity('')"
				value=""
				placeholder="<?php echo $form_orientation_horizontal ?
					esc_attr($label)
					: (isset($input->settings['placeholder']) ?
						esc_attr($input->settings['placeholder'])
						: '')
				?>"
				maxlength="150"/>
				<?php echo $form_orientation_horizontal ? '' : '<div id="country-selector"></div>' ?>
			<?php
			break;
		case 'consent':?>
			<input <?=esc_attr($required)?>
			type="checkbox"
			name="fields-<?php echo esc_attr($input->name . '-' . $form->id); ?>"
			value = "true"/>
			<?php
			break;
		case 'permission':?>
			<div class="permission-field permission-form-container">
				<input <?=esc_attr($required)?>
				type="checkbox"
				name="fields-<?php echo esc_attr($input->name . '-' . $form->id); ?>"
				value = "true"/>
				<label for="fields-<?php echo esc_attr($input->name); ?>">
					<?php echo esc_html($input->name); ?>
				</label>
			</div>
			<?php
			break;
		case 'boolean':
			?>
			<input <?=esc_attr($required)?>
			type="radio"
			name="fields-<?php echo esc_attr($input->name . '-' . $form->id); ?>"
			value="true">Si
			<input <?=esc_attr($required)?>
			type="radio"
			name="fields-<?php echo esc_attr($input->name . '-' . $form->id); ?>"
			value="false">No
			<br/>
			<?php
			break;
		case 'email':
			?>
			<input <?=esc_attr($required)?>
			type="email"
			oninvalid="this.setCustomValidity('<?php esc_html_e('Please enter a valid email address.', 'doppler-form') ?>')"
			pattern="[A-Za-z0-9&#46;&#95;&#37;&#43;&minus;]+@[A-Za-z0-9&#46;&minus;]+\.[A-Za-z]{1,63}$"
			name="<?php echo esc_attr($input->name); ?>"
			oninput="this.setCustomValidity('')"
			value=""
			maxlength="150"
			placeholder="<?php echo isset($input->settings['placeholder']) ? esc_attr($input->settings['placeholder']) : ''; ?>">
			<?php
			break;
		case 'date':
			?>
			<input <?=esc_attr($required)?>
			type="text"
			name="<?php echo esc_attr($input->name); ?>"
			data-form-id="<?php echo esc_attr($form->id); ?>"
			data-date-format="<?php echo esc_attr($input->settings['dateFormat'] ?? '"dd/mm/yy'); ?>"
			oninvalid="this.setCustomValidity('<?php esc_html_e('Please enter a valid date.', 'doppler-form') ?>')"
			pattern="^((\d{1,2}(\/|-| )){2}\d{4,4})|(\d{2,4}(\/|-| )\d{1,2}(\/|-| )\d{1,2})$"
			value=""
			class="date"
			maxlength="150"
			placeholder= "<?php echo isset($input->settings['placeholder']) ?
				esc_attr($input->settings['placeholder'])
				: "" ?>">
			<input type="hidden"
			name="fields-<?php echo esc_attr($input->name . '-' . $form->id); ?>"
			value="">
			<?php
			break;
		case 'gender':
		?>
			<input <?=esc_attr($required)?> type="radio" name="fields-<?php echo esc_attr($input->name . '-' . $form->id); ?>" value="M">M
			<input <?=esc_attr($required)?> type="radio" name="fields-<?php echo esc_attr($input->name . '-' . $form->id); ?>" value="F">F
			<input <?=esc_attr($required)?> type="radio" name="fields-<?php echo esc_attr($input->name . '-' . $form->id); ?>" value="N/A">N/A<?php
			break;
		case 'country':
			?><select <?php echo esc_attr($required); ?> name="fields-<?php echo esc_attr($input->name . '-' . $form->id); ?>">
				<option value="AF">Afghanistan</option>
				<option value="AX">Åland Islands</option>
				<option value="AL">Albania</option>
				<option value="DZ">Algeria</option>
				<option value="AS">American Samoa</option>
				<option value="AD">Andorra</option>
				<option value="AO">Angola</option>
				<option value="AI">Anguilla</option>
				<option value="AQ">Antarctica</option>
				<option value="AG">Antigua and Barbuda</option>
				<option value="AR">Argentina</option>
				<option value="AM">Armenia</option>
				<option value="AW">Aruba</option>
				<option value="AU">Australia</option>
				<option value="AT">Austria</option>
				<option value="AZ">Azerbaijan</option>
				<option value="BS">Bahamas</option>
				<option value="BH">Bahrain</option>
				<option value="BD">Bangladesh</option>
				<option value="BB">Barbados</option>
				<option value="BY">Belarus</option>
				<option value="BE">Belgium</option>
				<option value="BZ">Belize</option>
				<option value="BJ">Benin</option>
				<option value="BM">Bermuda</option>
				<option value="BT">Bhutan</option>
				<option value="BO">Bolivia, Plurinational State of</option>
				<option value="BQ">Bonaire, Sint Eustatius and Saba</option>
				<option value="BA">Bosnia and Herzegovina</option>
				<option value="BW">Botswana</option>
				<option value="BV">Bouvet Island</option>
				<option value="BR">Brazil</option>
				<option value="IO">British Indian Ocean Territory</option>
				<option value="BN">Brunei Darussalam</option>
				<option value="BG">Bulgaria</option>
				<option value="BF">Burkina Faso</option>
				<option value="BI">Burundi</option>
				<option value="KH">Cambodia</option>
				<option value="CM">Cameroon</option>
				<option value="CA">Canada</option>
				<option value="CV">Cape Verde</option>
				<option value="KY">Cayman Islands</option>
				<option value="CF">Central African Republic</option>
				<option value="TD">Chad</option>
				<option value="CL">Chile</option>
				<option value="CN">China</option>
				<option value="CX">Christmas Island</option>
				<option value="CC">Cocos (Keeling) Islands</option>
				<option value="CO">Colombia</option>
				<option value="KM">Comoros</option>
				<option value="CG">Congo</option>
				<option value="CD">Congo, the Democratic Republic of the</option>
				<option value="CK">Cook Islands</option>
				<option value="CR">Costa Rica</option>
				<option value="CI">Côte d'Ivoire</option>
				<option value="HR">Croatia</option>
				<option value="CU">Cuba</option>
				<option value="CW">Curaçao</option>
				<option value="CY">Cyprus</option>
				<option value="CZ">Czech Republic</option>
				<option value="DK">Denmark</option>
				<option value="DJ">Djibouti</option>
				<option value="DM">Dominica</option>
				<option value="DO">Dominican Republic</option>
				<option value="EC">Ecuador</option>
				<option value="EG">Egypt</option>
				<option value="SV">El Salvador</option>
				<option value="GQ">Equatorial Guinea</option>
				<option value="ER">Eritrea</option>
				<option value="EE">Estonia</option>
				<option value="ET">Ethiopia</option>
				<option value="FK">Falkland Islands (Malvinas)</option>
				<option value="FO">Faroe Islands</option>
				<option value="FJ">Fiji</option>
				<option value="FI">Finland</option>
				<option value="FR">France</option>
				<option value="GF">French Guiana</option>
				<option value="PF">French Polynesia</option>
				<option value="TF">French Southern Territories</option>
				<option value="GA">Gabon</option>
				<option value="GM">Gambia</option>
				<option value="GE">Georgia</option>
				<option value="DE">Germany</option>
				<option value="GH">Ghana</option>
				<option value="GI">Gibraltar</option>
				<option value="GR">Greece</option>
				<option value="GL">Greenland</option>
				<option value="GD">Grenada</option>
				<option value="GP">Guadeloupe</option>
				<option value="GU">Guam</option>
				<option value="GT">Guatemala</option>
				<option value="GG">Guernsey</option>
				<option value="GN">Guinea</option>
				<option value="GW">Guinea-Bissau</option>
				<option value="GY">Guyana</option>
				<option value="HT">Haiti</option>
				<option value="HM">Heard Island and McDonald Islands</option>
				<option value="VA">Holy See (Vatican City State)</option>
				<option value="HN">Honduras</option>
				<option value="HK">Hong Kong</option>
				<option value="HU">Hungary</option>
				<option value="IS">Iceland</option>
				<option value="IN">India</option>
				<option value="ID">Indonesia</option>
				<option value="IR">Iran, Islamic Republic of</option>
				<option value="IQ">Iraq</option>
				<option value="IE">Ireland</option>
				<option value="IM">Isle of Man</option>
				<option value="IL">Israel</option>
				<option value="IT">Italy</option>
				<option value="JM">Jamaica</option>
				<option value="JP">Japan</option>
				<option value="JE">Jersey</option>
				<option value="JO">Jordan</option>
				<option value="KZ">Kazakhstan</option>
				<option value="KE">Kenya</option>
				<option value="KI">Kiribati</option>
				<option value="KP">Korea, Democratic People's Republic of</option>
				<option value="KR">Korea, Republic of</option>
				<option value="KW">Kuwait</option>
				<option value="KG">Kyrgyzstan</option>
				<option value="LA">Lao People's Democratic Republic</option>
				<option value="LV">Latvia</option>
				<option value="LB">Lebanon</option>
				<option value="LS">Lesotho</option>
				<option value="LR">Liberia</option>
				<option value="LY">Libya</option>
				<option value="LI">Liechtenstein</option>
				<option value="LT">Lithuania</option>
				<option value="LU">Luxembourg</option>
				<option value="MO">Macao</option>
				<option value="MK">Macedonia, the former Yugoslav Republic of</option>
				<option value="MG">Madagascar</option>
				<option value="MW">Malawi</option>
				<option value="MY">Malaysia</option>
				<option value="MV">Maldives</option>
				<option value="ML">Mali</option>
				<option value="MT">Malta</option>
				<option value="MH">Marshall Islands</option>
				<option value="MQ">Martinique</option>
				<option value="MR">Mauritania</option>
				<option value="MU">Mauritius</option>
				<option value="YT">Mayotte</option>
				<option value="MX">Mexico</option>
				<option value="FM">Micronesia, Federated States of</option>
				<option value="MD">Moldova, Republic of</option>
				<option value="MC">Monaco</option>
				<option value="MN">Mongolia</option>
				<option value="ME">Montenegro</option>
				<option value="MS">Montserrat</option>
				<option value="MA">Morocco</option>
				<option value="MZ">Mozambique</option>
				<option value="MM">Myanmar</option>
				<option value="NA">Namibia</option>
				<option value="NR">Nauru</option>
				<option value="NP">Nepal</option>
				<option value="NL">Netherlands</option>
				<option value="NC">New Caledonia</option>
				<option value="NZ">New Zealand</option>
				<option value="NI">Nicaragua</option>
				<option value="NE">Niger</option>
				<option value="NG">Nigeria</option>
				<option value="NU">Niue</option>
				<option value="NF">Norfolk Island</option>
				<option value="MP">Northern Mariana Islands</option>
				<option value="NO">Norway</option>
				<option value="OM">Oman</option>
				<option value="PK">Pakistan</option>
				<option value="PW">Palau</option>
				<option value="PS">Palestinian Territory, Occupied</option>
				<option value="PA">Panama</option>
				<option value="PG">Papua New Guinea</option>
				<option value="PY">Paraguay</option>
				<option value="PE">Peru</option>
				<option value="PH">Philippines</option>
				<option value="PN">Pitcairn</option>
				<option value="PL">Poland</option>
				<option value="PT">Portugal</option>
				<option value="PR">Puerto Rico</option>
				<option value="QA">Qatar</option>
				<option value="RE">Réunion</option>
				<option value="RO">Romania</option>
				<option value="RU">Russian Federation</option>
				<option value="RW">Rwanda</option>
				<option value="BL">Saint Barthélemy</option>
				<option value="SH">Saint Helena, Ascension and Tristan da Cunha</option>
				<option value="KN">Saint Kitts and Nevis</option>
				<option value="LC">Saint Lucia</option>
				<option value="MF">Saint Martin (French part)</option>
				<option value="PM">Saint Pierre and Miquelon</option>
				<option value="VC">Saint Vincent and the Grenadines</option>
				<option value="WS">Samoa</option>
				<option value="SM">San Marino</option>
				<option value="ST">Sao Tome and Principe</option>
				<option value="SA">Saudi Arabia</option>
				<option value="SN">Senegal</option>
				<option value="RS">Serbia</option>
				<option value="SC">Seychelles</option>
				<option value="SL">Sierra Leone</option>
				<option value="SG">Singapore</option>
				<option value="SX">Sint Maarten (Dutch part)</option>
				<option value="SK">Slovakia</option>
				<option value="SI">Slovenia</option>
				<option value="SB">Solomon Islands</option>
				<option value="SO">Somalia</option>
				<option value="ZA">South Africa</option>
				<option value="GS">South Georgia and the South Sandwich Islands</option>
				<option value="SS">South Sudan</option>
				<option value="ES">Spain</option>
				<option value="LK">Sri Lanka</option>
				<option value="SD">Sudan</option>
				<option value="SR">Suriname</option>
				<option value="SJ">Svalbard and Jan Mayen</option>
				<option value="SZ">Swaziland</option>
				<option value="SE">Sweden</option>
				<option value="CH">Switzerland</option>
				<option value="SY">Syrian Arab Republic</option>
				<option value="TW">Taiwan, Province of China</option>
				<option value="TJ">Tajikistan</option>
				<option value="TZ">Tanzania, United Republic of</option>
				<option value="TH">Thailand</option>
				<option value="TL">Timor-Leste</option>
				<option value="TG">Togo</option>
				<option value="TK">Tokelau</option>
				<option value="TO">Tonga</option>
				<option value="TT">Trinidad and Tobago</option>
				<option value="TN">Tunisia</option>
				<option value="TR">Turkey</option>
				<option value="TM">Turkmenistan</option>
				<option value="TC">Turks and Caicos Islands</option>
				<option value="TV">Tuvalu</option>
				<option value="UG">Uganda</option>
				<option value="UA">Ukraine</option>
				<option value="AE">United Arab Emirates</option>
				<option value="GB">United Kingdom</option>
				<option value="US">United States</option>
				<option value="UM">United States Minor Outlying Islands</option>
				<option value="UY">Uruguay</option>
				<option value="UZ">Uzbekistan</option>
				<option value="VU">Vanuatu</option>
				<option value="VE">Venezuela, Bolivarian Republic of</option>
				<option value="VN">Viet Nam</option>
				<option value="VG">Virgin Islands, British</option>
				<option value="VI">Virgin Islands, U.S.</option>
				<option value="WF">Wallis and Futuna</option>
				<option value="EH">Western Sahara</option>
				<option value="YE">Yemen</option>
				<option value="ZM">Zambia</option>
				<option value="ZW">Zimbabwe</option>
			</select>
		<?php
        break;
    	}
  	}

	private static function registerDisplayEvent($formId) {
		DPLR_Form_Model::insertEvent([
			'parent_id'=>$formId,
			'event_type' => EventType::DISPLAY,
			'event_date' => DPLR_Form_Model::now()
		]);
	}
}
?>

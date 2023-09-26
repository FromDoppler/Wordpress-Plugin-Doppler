(function( $ ) {
	'use strict';
	$(document).ready(function() {
		$("form.dplr_form input[type='text'].date").each(function() {
			var dateElement = $(this);
			var elementName = dateElement.attr('name');
			dateElement.datepicker({
				'dateFormat': 'dd/mm/yy',
				'altFormat': 'yy-mm-dd',
				'yearRange': '-100:+0',
				'changeMonth': true,
      			'changeYear': true,
				'altField': 'input[name="fields-'+elementName+'"]'
			});
		});

		$('.dplr_form input[name="EMAIL"]').focus(function(){
			var f = $(this).closest('form');
			f.find('.msg-data-sending').hide();
		});

		//Input Phone doppler flags
		const input = document.querySelector("#phone-doppler");
        const countrySelector = document.querySelector("#country-selector");
        const iti = window.intlTelInput(input, {
        	utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.12/js/utils.js",
        	initialCountry: "auto",
        	separateDialCode: true,
        	customPlaceholder: function (selectedCountryPlaceholder, selectedCountryData) {
            	return selectedCountryPlaceholder;
        	},
        });
        iti.promise.then(function () {
        	iti.setPlaceholderNumber();
        	iti.setCountry("auto");
        });

		//Input Date doopler
		$("#date-picker").datepicker({
			dateFormat: "yy-mm-dd", // Format as "YYYY-MM-DD"
		});
		$('.dplr_form').submit(function(ev) {
						
			ev.preventDefault();

			var f = $(this);
			var s = $(this).find("button[name='submit']");
			var m = $(this).find(".msg-data-sending");
			var l = $(this).find("input[name='list_id']");
			var d = $(this).find("input[name='form_id']");
			var e = $(this).find("input[name='EMAIL']");
			var honey =  $(this).find("input[name='secondary-dplrEmail']");
			var thankyou = $(this).find("input[name='thankyou']");
			var fields = $(this).find("input[name|='fields'], select[name|='fields'], textarea[name|='fields']");

			s.attr("disabled", "disabled");
			s.addClass('sending');

			var subscriber = {},
			list_id = l.val();
			let form_id = d.val();
			subscriber.email = e.val();
			subscriber.hp = honey.val();
			subscriber.fields = [];

			fields.each(function(index) {
				var input = $(fields[index]);

				if (input.attr('type') == 'radio' && !input.is(':checked')) return;
				if (input.attr('type') == 'checkbox' && !input.is(':checked')) return;

				var name = input.attr('name');
				name = name.split('-');
				name = name.slice(1);
				name = !Array.isArray(name) ? name : name.join('-');

				var field = {};
				field['name'] = name;
				field['value'] = input.val();
				subscriber.fields.push(field);
			});
			
			$.post(dplr_obj_vars.ajax_url,
				{
					"action": 'submit_form', 
					"subscriber": subscriber, 
					"list_id": list_id,
					"form_id": form_id
				},
				function(res) {
					if(thankyou.length !== 0){
						window.location.href = thankyou.val();
					}else{
						s.removeClass('sending');
						m.show();
						s.removeAttr("disabled");
						f.trigger('reset');
						setTimeout(function() {
							m.hide();
							f[0].reset();
						}, 8000);
					}
			});
		});
	});
})( jQuery );

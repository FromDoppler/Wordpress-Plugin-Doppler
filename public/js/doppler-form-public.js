(function ($) {
	"use strict";
	function isValidDateFormat(dateFormat) {
		var regExDateFormat =
			/^(?:(((mm|dd)(\/|-| )){2}y{1,2})|(y{1,2}(\/|-| )(mm|dd)(\/|-| )(mm|dd)))$/;
		return regExDateFormat.test(dateFormat);
	}

	function submitDplrForm(form) {
		var f = form;
		var s = form.find("button[name='submit']");
		var m = form.find(".msg-data-sending");
		var l = form.find("input[name='list_id']");
		var d = form.find("input[name='form_id']");
		var e = form.find("input[name='EMAIL']");
		var honey = form.find("input[name='dplr-hp-field']");
		var thankyou = form.find("input[name='thankyou']");
		let form_id = d.val();
		var fields = form.find(
			"input[name|='fields'], select[name|='fields'], textarea[name|='fields']" &&
				[
					"input[name$='-",
					"'], select[name$='-",
					"'], textarea[name$='-",
					"']",
				].join(form_id)
		);

		s.attr("disabled", "disabled");
		s.addClass("sending");

		var subscriber = {},
			list_id = l.val();
		subscriber.email = e.val();
		subscriber.hp = honey.val();
		subscriber.fields = [];

		fields.each(function (index) {
			var input = $(fields[index]);

			if (input.attr("type") == "radio" && !input.is(":checked")) return;
			if (input.attr("type") == "checkbox" && !input.is(":checked")) return;

			var name = input.attr("name");
			name = name.split("-");
			name = name[1];
			name = !Array.isArray(name) ? name : name.join("-");

			var field = {};
			field["name"] = name;
			field["value"] =
				input.attr("type") == "radio" && input.val() == "N/A"
					? ""
					: input.val();
			subscriber.fields.push(field);
		});

		$.post(
			dplr_obj_vars.ajax_url,
			{
				action: "submit_form",
				subscriber: subscriber,
				list_id: list_id,
				form_id: form_id,
			},
			function (res) {
				if (thankyou.length !== 0) {
					window.location.href = thankyou.val();
				} else {
					s.removeClass("sending");
					m.show();
					s.removeAttr("disabled");
					f.trigger("reset");
					setTimeout(function () {
						m.hide();
						f[0].reset();
					}, 8000);
				}
			}
		);
	}

	function addDatePickerToDplrDateFields(form) {
		form.find("input[type='text'].date").each(function () {
			var dateElement = $(this);
			var elementName = dateElement.attr("name");
			var elementFormId = dateElement.attr("data-form-id");
			var elementDateFormat = dateElement.attr("data-date-format");
			dateElement
				.datepicker({
					dateFormat: isValidDateFormat(elementDateFormat)
						? elementDateFormat
						: "dd/mm/yy",
					altFormat: "yy-mm-dd",
					yearRange: "-100:+100",
					changeMonth: true,
					changeYear: true,
					altField:
						'input[name="fields-' + elementName + "-" + elementFormId + '"]',
				})
				.on("blur", function () {
					if (
						this.value.match(
							/((\d{1,2}(\/|-| )){2}\d{4,4})|(\d{2,4}(\/|-| )\d{1,2}(\/|-| )\d{1,2})/gi
						) == null
					) {
						this.reportValidity();
					}
				});

			//This is a patch to avoid closing Elementor's popups when clicking on the calendar
			const datepicker = $(".ui-datepicker");
			datepicker.addClass("flatpickr-calendar");
		});
	}

	function addFlagsAndValidationToDplrPhoneFields(form) {
		const inputs = form.find(".phone-doppler");
		if (inputs != null) {
			inputs.each(function () {
				const input = $(this)[0];
				const iti = window.intlTelInput(input, {
					utilsScript:
						"https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js",
					initialCountry: "ar",
					separateDialCode: true,
					customPlaceholder: function (
						selectedCountryPlaceholder,
						selectedCountryData
					) {
						return selectedCountryPlaceholder;
					},
				});

				function validateTel(phone_input) {
					if (input.value.trim()) {
						if (!iti.isValidNumber()) {
							phone_input.setCustomValidity(errorMsg.err);
							phone_input.reportValidity();
							return false;
						} else {
							return true;
						}
					}
				}

				$(".phone-doppler").blur(function () {
					validateTel(this);
				});

				$("form.dplr_form").submit(function () {
					const dopplerPhone = $(this).find(".phone-doppler");
					if (validateTel(dopplerPhone)) {
						var phoneNumber = dopplerPhone.val();
						if (!phoneNumber.startsWith("+")) {
							var countryCode = $(".iti__selected-dial-code").html();
							dopplerPhone.val(countryCode + phoneNumber);
						}
					}
				});
			});
		}
	}

	$(document).ready(function () {
		$(document).on("elementor/popup/show", (event, popupId) => {
			const popupSelector = $(`#elementor-popup-modal-${popupId}`);
			const dplrForm = popupSelector.find("form.dplr_form");

			if (dplrForm) {
				addDatePickerToDplrDateFields(dplrForm);
				addFlagsAndValidationToDplrPhoneFields(dplrForm);

				dplrForm.submit(function (ev) {
					ev.preventDefault();
					submitDplrForm($(this));
				});
			}
		});

		const dplrForm = $("form.dplr_form").filter(function () {
			return $(this).closest(".elementor").length === 0;
		});
		addDatePickerToDplrDateFields(dplrForm);
		addFlagsAndValidationToDplrPhoneFields(dplrForm);

		$('.dplr_form input[name="EMAIL"]').focus(function () {
			var f = $(this).closest("form");
			f.find(".msg-data-sending").hide();
		});

		$(".dplr_form").submit(function (ev) {
			ev.preventDefault();
			submitDplrForm($(this));
		});
	});
})(jQuery);

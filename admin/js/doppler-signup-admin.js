(function ($) {
	function getAssistanceWrap($input) {
		const $siblingWrap = $input.siblings(".assistance-wrap").first();
		if ($siblingWrap.length) {
			return $siblingWrap;
		}

		return $input.closest("label").find(".assistance-wrap").first();
	}

	function setAssistanceMessage($wrap, message) {
		if (!$wrap || !$wrap.length) {
			return;
		}

		const $span = $wrap.find("span");
		if ($span.length) {
			$span.text(message || "");
			return;
		}

		$wrap.text(message || "");
	}

	function showInputError($input, message) {
		$input.attr("aria-invalid", "true");
		const $wrap = getAssistanceWrap($input);
		if ($wrap.length) {
			$wrap.removeClass("d-none");
			setAssistanceMessage($wrap, message);
		}
	}

	function clearInputError($input) {
		$input.attr("aria-invalid", "false");
		const $wrap = getAssistanceWrap($input);
		if ($wrap.length) {
			$wrap.addClass("d-none");
			setAssistanceMessage($wrap, "");
		}
	}

	function showSignupServerError(message) {
		const $errorContainer = $("#dplr-signup-server-error");
		if (!$errorContainer.length) {
			return;
		}

		const $messageTarget = $errorContainer
			.find("p, .dp-content-message p, span")
			.first();
		if ($messageTarget.length) {
			$messageTarget.text(message || "");
		} else {
			$errorContainer.text(message || "");
		}
		$errorContainer.removeClass("d-none");
	}

	function clearSignupServerError() {
		const $errorContainer = $("#dplr-signup-server-error");
		if (!$errorContainer.length) {
			return;
		}

		const $messageTarget = $errorContainer
			.find("p, .dp-content-message p, span")
			.first();
		if ($messageTarget.length) {
			$messageTarget.text("");
		}
		$errorContainer.addClass("d-none");
	}

	function validatePhoneFields($form) {
		let isValid = true;
		$form.find(".phone-doppler").each(function () {
			const validator = $(this).data("dplrPhoneValidator");
			if (typeof validator === "function" && !validator()) {
				isValid = false;
			}
		});

		return isValid;
	}

	function normalizePhoneFields($form) {
		$form.find(".phone-doppler").each(function () {
			const formatter = $(this).data("dplrPhoneFormatter");
			if (typeof formatter === "function") {
				formatter();
			}
		});
	}

	function getSignupErrorMessage(response, fallbackMessage) {
		if (
			response &&
			response.data &&
			typeof response.data.message === "string" &&
			response.data.message.length
		) {
			return response.data.message;
		}

		return fallbackMessage;
	}

	function validateNameField($input) {
		const value = ($input.val() || "").trim();
		const requiredMessage = $input.attr("data-validation-required");
		const minLengthMessage = $input.attr("data-validation-minlength");

		if (!value.length) {
			showInputError($input, requiredMessage || "");
			return false;
		}

		if (value.length === 1) {
			showInputError($input, minLengthMessage || "");
			return false;
		}

		clearInputError($input);
		return true;
	}

	function syncCheckboxValue($checkbox) {
		$checkbox.val($checkbox.is(":checked") ? "true" : "false");
	}

	function setCheckboxValidationState($checkbox, hasError) {
		const $label = $checkbox.closest("label.dp-label-checkbox");
		if (!$label.length) {
			return;
		}

		$label.attr("aria-invalid", hasError ? "true" : "false");
		const errorId = $label.attr("aria-errormessage");
		if (!errorId) {
			return;
		}

		const $errorMessage = $("#" + errorId);
		if (!$errorMessage.length) {
			return;
		}

		$errorMessage.toggleClass("d-none", !hasError);
	}

	function validateRequiredCheckbox($checkbox) {
		syncCheckboxValue($checkbox);
		const isChecked = $checkbox.is(":checked");
		setCheckboxValidationState($checkbox, !isChecked);
		return isChecked;
	}

	function validateEmailField($input) {
		const value = $input.val() || "";
		const invalidMessage = $input.attr("data-validation-email-invalid") || "";
		const emailRegex =
			/^\s*((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\s*$/i;

		if (!emailRegex.test(value)) {
			showInputError($input, invalidMessage);
			return false;
		}

		clearInputError($input);
		return true;
	}

	function setPasswordRuleState($rule, isValid, shouldShowFeedback) {
		$rule.removeClass(
			"dp-message--default dp-message--denied dp-message--success",
		);

		if (!shouldShowFeedback) {
			$rule.addClass("dp-message--default");
			return;
		}

		$rule.addClass(isValid ? "dp-message--success" : "dp-message--denied");
	}

	function validatePasswordField($input, shouldShowFeedback) {
		const value = $input.val() || "";
		const isEmpty = value.length === 0;
		const hasMinLength = value.length >= 8;
		const hasLetter = /[A-Za-z]/.test(value);
		const hasNumber = /\d/.test(value);
		const isValid = hasMinLength && hasLetter && hasNumber;

		const $minLengthRule = $("#dplr-signup-minimum-length");
		const $oneLetterRule = $("#dplr-signup-one-letter");
		const $oneNumberRule = $("#dplr-signup-one-number");
		const $secureMessage = $input
			.siblings(".wrapper-password")
			.find(".dp-message--secure");

		setPasswordRuleState($minLengthRule, hasMinLength, shouldShowFeedback);
		setPasswordRuleState($oneLetterRule, hasLetter, shouldShowFeedback);
		setPasswordRuleState($oneNumberRule, hasNumber, shouldShowFeedback);

		if (isValid) {
			$minLengthRule.addClass("d-none");
			$oneLetterRule.addClass("d-none");
			$oneNumberRule.addClass("d-none");
			$secureMessage.removeClass("d-none");
			$input.attr("aria-invalid", "false");
		} else {
			$minLengthRule.removeClass("d-none");
			$oneLetterRule.removeClass("d-none");
			$oneNumberRule.removeClass("d-none");
			$secureMessage.addClass("d-none");
			$input.attr(
				"aria-invalid",
				shouldShowFeedback || !isEmpty ? "true" : "false",
			);
		}

		return isValid;
	}

	function addFlagsAndValidationToAdminPhoneFields() {
		const inputs = $(".phone-doppler");
		if (!inputs.length || typeof window.intlTelInput === "undefined") {
			return;
		}

		inputs.each(function () {
			const input = $(this)[0];
			if (!input) {
				return;
			}

			const iti = window.intlTelInput(input, {
				utilsScript:
					"https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js",
				initialCountry: "ar",
				preferredCountries: ["ar", "mx", "cl", "pe", "uy", "co", "ve"],
				separateDialCode: true,
				customPlaceholder: function (selectedCountryPlaceholder) {
					return selectedCountryPlaceholder;
				},
			});

			function validateTel(phoneInput) {
				const $input = $(phoneInput);
				const value = (phoneInput.value || "").trim();
				const invalidMessage = $input.attr("data-validation-phone-invalid");
				const shortMessage = $input.attr("data-validation-phone-short");
				const longMessage = $input.attr("data-validation-phone-long");

				if (!value) {
					showInputError($input, invalidMessage || "");
					return false;
				}

				if (!iti.isValidNumber()) {
					const utils = window.intlTelInputUtils;
					const validationError =
						typeof iti.getValidationError === "function"
							? iti.getValidationError()
							: null;
					const tooShortCode = utils?.validationError?.TOO_SHORT;
					const tooLongCode = utils?.validationError?.TOO_LONG;
					let message = invalidMessage || "";

					if (validationError === tooShortCode || validationError === 2) {
						message = shortMessage || message;
					} else if (validationError === tooLongCode || validationError === 3) {
						message = longMessage || message;
					}

					showInputError($input, message);
					return false;
				}

				phoneInput.setCustomValidity("");
				clearInputError($input);
				return true;
			}

			$(input).on("blur", function () {
				validateTel(this);
			});

			$(input).data("dplrPhoneValidator", function () {
				return validateTel(input);
			});

			$(input).data("dplrPhoneFormatter", function () {
				const phoneNumber = $(input).val();
				if (phoneNumber && !phoneNumber.startsWith("+")) {
					const selected = iti.getSelectedCountryData();
					if (selected && selected.dialCode) {
						$(input).val("+" + selected.dialCode + phoneNumber);
					}
				}
			});
		});
	}

	$(document).ready(function () {
		const $signUpForm = $("#dplr-sign-up-form");
		const $nameInputs = $signUpForm.find(
			"input[name='firstname'], input[name='lastname']",
		);
		const $emailInput = $signUpForm.find("input[name='email']");
		const $passwordInput = $signUpForm.find("#dplr-sign-up-password");
		const $privacyPoliciesCheckbox = $signUpForm.find(
			"#dplr-signup-accept-privacy-policies",
		);
		const $promotionsCheckbox = $signUpForm.find(
			"#dplr-signup-accept-promotions",
		);
		const $signUpSubmitButton = $signUpForm.find("button[type='submit']");
		const genericSignupErrorMessage =
			"Ouch! An error ocurred while trying to communicate with the API. Try again later.";

		if ($nameInputs.length) {
			$nameInputs.on("blur", function () {
				validateNameField($(this));
			});
		}

		if ($emailInput.length) {
			$emailInput.on("blur", function () {
				validateEmailField($(this));
			});
		}

		if ($passwordInput.length) {
			validatePasswordField($passwordInput, false);

			$passwordInput.on("input", function () {
				validatePasswordField($(this), true);
			});
		}

		if ($privacyPoliciesCheckbox.length) {
			syncCheckboxValue($privacyPoliciesCheckbox);
			setCheckboxValidationState($privacyPoliciesCheckbox, false);
			$privacyPoliciesCheckbox.on("change", function () {
				validateRequiredCheckbox($(this));
			});
		}

		if ($promotionsCheckbox.length) {
			syncCheckboxValue($promotionsCheckbox);
			$promotionsCheckbox.on("change", function () {
				syncCheckboxValue($(this));
			});
		}

		if (
			$nameInputs.length ||
			$emailInput.length ||
			$passwordInput.length ||
			$privacyPoliciesCheckbox.length
		) {
			$signUpForm.on("submit", function (event) {
				event.preventDefault();
				let hasError = false;
				clearSignupServerError();

				$nameInputs.each(function () {
					if (!validateNameField($(this))) {
						hasError = true;
					}
				});

				if ($emailInput.length && !validateEmailField($emailInput)) {
					hasError = true;
				}

				if (
					$passwordInput.length &&
					!validatePasswordField($passwordInput, true)
				) {
					hasError = true;
				}

				if (
					$privacyPoliciesCheckbox.length &&
					!validateRequiredCheckbox($privacyPoliciesCheckbox)
				) {
					hasError = true;
				}

				if (!validatePhoneFields($signUpForm)) {
					hasError = true;
				}

				if (hasError) {
					return;
				}

				normalizePhoneFields($signUpForm);
				$signUpSubmitButton
					.attr("disabled", "disabled")
					.addClass("button--loading");

				const requestData = {
					action: "dplr_ajax_signup",
					nonce: $("#dplr_signup_nonce_field").val(),
					firstname: $signUpForm.find("input[name='firstname']").val(),
					lastname: $signUpForm.find("input[name='lastname']").val(),
					email: $emailInput.val(),
					password: $passwordInput.val(),
					phone: $signUpForm.find("input[name='phone']").val(),
					accept_privacy_policies: $privacyPoliciesCheckbox.val(),
					accept_promotions: $promotionsCheckbox.val(),
				};

				$.ajax({
					url: ajaxurl,
					type: "POST",
					data: requestData,
				})
					.done(function (response) {
						if (response && response.success) {
							clearSignupServerError();
							$("#dplr-created-account").text(requestData.email);
							$("#dplr-sign-up").addClass("d-none");
							$("#dplr-sign-up-success").removeClass("d-none");
							return;
						}

						showSignupServerError(
							getSignupErrorMessage(response, genericSignupErrorMessage),
						);
					})
					.fail(function (jqXHR) {
						const responseJSON =
							jqXHR && jqXHR.responseJSON ? jqXHR.responseJSON : null;
						showSignupServerError(
							getSignupErrorMessage(responseJSON, genericSignupErrorMessage),
						);
					})
					.always(function () {
						$signUpSubmitButton
							.removeAttr("disabled")
							.removeClass("button--loading");
					});
			});
		}

		$("#dplr-sign-up-link").click(function (e) {
			e.preventDefault();
			clearSignupServerError();

			$("#dplr-sign-in").addClass("d-none");
			$("#dplr-sign-up-success").addClass("d-none");
			$("#dplr-sign-up").removeClass("d-none");
		});

		$("#dplr-sign-in-link, #dplr-sign-in-link-success").click(function (e) {
			e.preventDefault();
			clearSignupServerError();

			$("#dplr-sign-up").addClass("d-none");
			$("#dplr-sign-up-success").addClass("d-none");
			$("#dplr-sign-in").removeClass("d-none");
		});

		$("#legal-accordion").on("click", ".dp-accordion-thumb", function (e) {
			e.preventDefault();
			const $item = $(this).closest("li");
			const $panel = $(this).siblings(".dp-accordion-panel").first();
			if (!$panel.length) {
				return;
			}

			const willShowPanel = $panel.hasClass("d-none");
			$panel.toggleClass("d-none");
			$item.toggleClass("active", willShowPanel);
		});

		$("#dplr-hide-show-pw").on("click", function () {
			const $btn = $(this);
			const $input = $("#dplr-sign-up-password");
			if (!$input.length) {
				return;
			}

			const isPassword = $input.attr("type") === "password";
			$input.attr("type", isPassword ? "text" : "password");

			$btn.toggleClass("icon-view", !isPassword);
			$btn.toggleClass("icon-hide", isPassword);
		});

		addFlagsAndValidationToAdminPhoneFields();
	});
})(jQuery);

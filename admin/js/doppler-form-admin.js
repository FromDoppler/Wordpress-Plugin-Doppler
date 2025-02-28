(function ($) {
	function validateEmail(emailInput) {
		var email = emailInput.val();
		var assistanceWrap = emailInput[0].nextElementSibling;

		if (
			email.match(
				/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
			)
		) {
			emailInput.attr("aria-invalid", "false");
			$(assistanceWrap).find("span").html("");
		} else {
			emailInput.attr("aria-invalid", "true");
			$(assistanceWrap)
				.find("span")
				.html(emailInput.attr("data-validation-email"));
		}
	}

	function validateRequired(requiredElement) {
		var value = requiredElement.val();
		var assistanceWrap = requiredElement[0].nextElementSibling;
		if (value) {
			requiredElement.attr("aria-invalid", "false");
			$(assistanceWrap).find("span").html("");
		} else {
			requiredElement.attr("aria-invalid", "true");
			$(assistanceWrap)
				.find("span")
				.html(requiredElement.attr("data-validation-required"));
		}
	}

	function hideUserApiError() {
		$(".tooltip--user_api_error").css("display", "none");
	}

	$(document).ready(function () {
		var colorSelector = $(".color-selector");
		var default_page_size = 200;

		$("input[data-validation-email]").focusout(function () {
			hideUserApiError();
			validateEmail($(this));
		});

		$("input[data-validation-required]").focusout(function () {
			hideUserApiError();
			validateRequired($(this));
		});

		$(".dplr-input-section input[type='text']").focusin(function (e) {
			$(this)
				.closest(".dplr-input-section")
				.addClass("notempty")
				.find(".tooltip-container span")
				.html("");
			$(this).addClass("notempty");
		});

		$(".dplr-input-section input[type='text']").focusout(function (e) {
			if ($(this).val() == "") {
				$(this).closest(".dplr-input-section").removeClass("notempty");
				$(this).removeClass("notempty");
			}
		});

		$("#dplr-disconnect-form").submit(function (event) {
			event.preventDefault();
			hideUserApiError();

			var form = $(this);
			var button = $(this).find("button");
			button.attr("disabled", "disabled").addClass("button--loading");

			var data = {
				action: "dplr_ajax_disconnect",
			};

			$.post(ajaxurl, data, function (response) {
				var obj = JSON.parse(response);
				if (obj.response.code == "200") {
					window.location.reload();
				} else {
					var body = JSON.parse(obj.body);
					var msg = "";
					if (body.status != "401") {
						msg = generateErrorMsg(body);
					} else {
						msg = object_string.wrongCredentials;
					}
					var error =
						'<div class="tooltip tooltip-warning tooltip--user_api_error">';
					error += '<div class="text-red text-left">';
					error += "<span>" + msg + "</span>";
					error += "</div>";
					error += "</div>";
					form.after(error);
					button.removeAttr("disabled").removeClass("button--loading");
				}
			});
		});

		/**
		 * Check against api first,
		 * then save credentials.
		 */
		$("#dplr-connect-form").submit(function (event) {
			event.preventDefault();
			hideUserApiError();

			var form = $(this);
			var button = $(this).find("button");
			var userfield = $("#user-account");
			var keyfield = $("#api-key");

			validateEmail($("input[data-validation-email]"));
			validateRequired($("input[data-validation-required]"));

			var inputErrors = $(this).find(".input-error");
			if (inputErrors.length > 0) {
				button.removeClass("button--loading");
				return false;
			}

			button.attr("disabled", "disabled").addClass("button--loading");

			var data = {
				action: "dplr_ajax_connect",
				user: userfield.val(),
				key: keyfield.val(),
			};

			$.post(ajaxurl, data, function (response) {
				var obj = JSON.parse(response);
				if (obj.response.code == "200") {
					var fields = form.serialize();
					$.post("options.php", fields, function () {
						window.location.reload(false);
					});
				} else {
					var body = JSON.parse(obj.body);
					var msg = "";
					if (body.status != "401") {
						msg = generateErrorMsg(body);
					} else {
						msg = object_string.wrongCredentials;
					}
					var error =
						'<div class="tooltip tooltip-warning tooltip--user_api_error">';
					error += '<div class="text-red text-left">';
					error += "<span>" + msg + "</span>";
					error += "</div>";
					error += "</div>";
					form.after(error);
					button.removeAttr("disabled").removeClass("button--loading");
				}
			});
		});

		$("#dplr-connect-form.error label input[type='text']").keyup(function (
			event
		) {
			$(".error").each(function (index, el) {
				$(this).removeClass("error");
			});
		});

		$(".multiple-selec").each(function () {
			var elem = $(this);
			var elemID = elem.attr("id");
			if (elemID != "widget-dplr_subscription_widget-__i__-selected_lists") {
				elem.chosen({
					width: "100%",
				});
				elem.addClass("selecAdded");
			}
		});

		$(".sortable").sortable({
			placeholder: "ui-state-mark",
		});

		$(".sortable").disableSelection();

		/*
	var fields = {
		container: $("ul#formFields"),

		items: [],
		addItem: function(item) {
			var domElement = $(item.renderItem());
			var _this = this;
			this.items.push(item);
			domElement.find(".icon-close").on("click", function(){
				$(this).parent().remove();
			});
			this.container.append(domElement);
		},
		removeItem: function(element) {

		}
	};*/

		$("body").on("click", "li .alt-toggle", function (e) {
			$(this).closest("li").toggleClass("active");
		});

		$(".dplr-toggle-thankyou").change(function () {
			var o = $(".dplr-toggle-thankyou:checked").val();
			if (o === "yes") {
				$(".dplr_thankyou_url input").attr("required", "required");
				$(".dplr_thankyou_url input").removeAttr("disabled");
				$(".dplr_thankyou_url").css("display", "block");
				$(".dplr_confirmation_message").val("").css("display", "none");
			} else {
				$(".dplr_thankyou_url input").removeAttr("required");
				$(".dplr_thankyou_url input").attr("disabled", "disabled");
				$(".dplr_thankyou_url").val("").css("display", "none");
				$(".dplr_confirmation_message").css("display", "block");
			}
		});

		$(".dplr-toggle-consent").change(function () {
			var o = $(".dplr-toggle-consent:checked").val();
			if (o === "yes") {
				$("#dplr_consent_section").fadeIn();
			} else {
				$("#dplr_consent_section").fadeOut();
			}
		});

		if ($(".dplr-toggle-selector").length > 0) {
			colorSelector.iris({
				change: function (event, ui) {
					$(".color-selector")[0].setCustomValidity("");
				},
			});
			showColorSelector();
		}

		$(".dplr-toggle-selector").change(function () {
			if (!colorSelector.val().match("^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$"))
				colorSelector.val("");
			showColorSelector();
		});

		if ($("#dplr-dialog-confirm").length > 0) {
			$("#dplr-dialog-confirm").dialog({
				autoOpen: false,
				resizable: false,
				height: "auto",
				width: 400,
				modal: true,
			});
		}

		$(".dplr-tab-content--list .dplr-remove").click(function (e) {
			e.preventDefault();
			clearResponseMessages();
			var a = $(this);
			var listId = a.attr("data-list-id");
			var row = a.closest("tr");
			if (!listId > 0) return false;

			$("#dplr-dialog-confirm").dialog("option", "buttons", [
				{
					text: object_string.Delete,
					click: function () {
						var data = { action: "dplr_delete_form", listId: listId };
						$(this).dialog("close");
						row.addClass("deleting");
						$.post(ajaxurl, data, function (resp) {
							if (resp == "1") {
								row.remove();
							}
						});
					},
				},
				{
					text: object_string.Cancel,
					click: function () {
						$(this).dialog("close");
					},
				},
			]);

			$("#dplr-dialog-confirm").dialog("open");
		});

		/* CRUD */

		$("#dplr-save-list").click(function (e) {
			e.preventDefault();
			clearResponseMessages();
			var listName = $(this).closest("form").find('input[type="text"]').val();
			if (listName !== "") {
				var data = {
					action: "dplr_save_list",
					listName: listName,
				};
				listsLoading();
				$.post(ajaxurl, data, function (response) {
					var body = JSON.parse(response);
					if (body.createdResourceId) {
						var html = "<tr>";
						html += "<td><strong>" + listName + "</strong></td>";
						html += "<td>0</td>";
						html +=
							'<td><div class="dp-icons-group"><a href="#" class="dplr-remove" data-list-id="' +
							body.createdResourceId +
							'">' +
							'<div class="dp-tooltip-container"> \
							<span class="ms-icon icon-delete"></span> \
							<div class="dp-tooltip-top"> \
							<span>' +
							object_string.Delete +
							"</span> \
							</div> \
							</div>" +
							"</a></div></td>";
						html += "</tr>";
						$("#dplr-tbl-lists tbody").prepend(html);
					} else {
						if (body.status >= 400) {
							//body.status,body.errorCode
							displayErrors(body);
						}
					}
					listsLoaded();
				});
			}
		});

		$("#dplr-tbl-lists tbody").on("click", "tr a", deleteList);

		$(".dplr-extensions .extension-item button.dp-install").click(function () {
			var button = $(this);
			var extension = button.attr("data-extension");
			button.addClass("button--loading").html(object_string.installing);
			button
				.closest(".dplr-extensions")
				.find("button")
				.css("pointer-events", "none");
			var data = {
				action: "install_extension",
				extensionName: extension,
			};
			$.post(ajaxurl, data, function (resp) {
				window.location.reload(false);
			});
		});

		$(".tab--link").click(function (e) {
			e.preventDefault();
			$(".tab--link").removeClass("active");
			$(this).addClass("active");
			var tab = $(this).attr("data-tab");
			$(".tab--content").removeClass("active");
			$("#tab-" + tab).addClass("active");
		});

		if ($("#dplr-tbl-lists").length > 0) {
			loadLists(1, default_page_size);
		}

		function showColorSelector() {
			colorSelector.val() == ""
				? (btnColor = "#000000")
				: (btnColor = colorSelector.val());
			$(".dplr-toggle-selector:checked").val() === "yes"
				? colorSelector
						.css("display", "block")
						.iris("color", btnColor)
						.iris("show")
				: colorSelector.css("display", "none").iris("hide");
		}

		//Autocomplete the hidden button with the form_name in the forms of Double Opt-In
		$('input[name="name"]').on("change", function () {
			$("#form_name").val($(this).val());
		});

		if ($('input[name="name"]').val()) {
			$("#form_name").val($('input[name="name"]').val());
		}

		$("#gdpr_add_button").on("click", function () {
			var html =
				"<li id='gdpr_input_section_" +
				gdprAmount +
				"' class='active' >" +
				"<div class='ms-icon icon-close' id='gdpr_remove_button' >" +
				"</div>" +
				"<a class='alt-toggle'>" +
				object_string.editField +
				"<i></i></a>" +
				"<div class='accordion-content field-settings'>" +
				"<div class='dplr_input_section'>" +
				"<label for='settings[consent_field_text][" +
				gdprAmount +
				"]'>" +
				object_string.privacyPolicyLabel +
				"</label>" +
				"<input type='text' name='settings[consent_field_text][" +
				gdprAmount +
				"]' value=''" +
				"placeholder='" +
				object_string.privacyPolicyPlaceholder +
				"' maxlength='150' required/>" +
				"</div>" +
				"<div class='dplr_input_section'>" +
				"<label for='settings[consent_field_url][" +
				gdprAmount +
				"]'>" +
				object_string.privacyPolicyUrlPlaceholder +
				"</label>" +
				"<input type='url' name='settings[consent_field_url][" +
				gdprAmount +
				"]' pattern='https?://.+'" +
				"value='' placeholder='' maxlength='150' required/>" +
				"</div>" +
				"</div>" +
				"</li>";

			$("#gdpr_section").prepend(html);

			$("#gdpr_remove_button").on("click", function () {
				var fieldContainer = $(this).closest("li");
				fieldContainer.remove();
			});
			gdprAmount += 1;
		});

		$(".copy-shortcode").click(function (e) {
			e.preventDefault();

			var shortcode = $(this)
				.closest(".dp-rowflex")
				.find("span")
				.first()
				.text();

			navigator.clipboard.writeText(shortcode);
		});

		if ($("#doppler-forms-chart").length) {
			const formNames = ["x"];
			const classicForms = ["Clásico"];
			const dobleOptinForms = ["Doble OptIn"];

			chartData.data.forEach(form => {
				formNames.push(form.name || "Sin Nombre");

				const displays = parseInt(form.events?.Display) || 0;
				const submits = parseInt(form.events?.Submit) || 0;
				const ratio = displays > 0 ? ((submits / displays) * 100).toFixed(2) : 0;

				if (form.settings?.form_doble_optin === "yes") {
					dobleOptinForms.push(parseFloat(ratio));
					classicForms.push(null);
				} else {
					classicForms.push(parseFloat(ratio));
					dobleOptinForms.push(null);
				}
			});

			bb.generate({
				data: {
					x: "x",
					columns: [formNames, classicForms, dobleOptinForms],
					type: "bar",
					colors: {
						"Clásico": "#A783C7",
						"Doble OptIn": "#F5B128"
					},
					groups: [["Clásico", "Doble OptIn"]],
					labels: true
				},
				size: {
					width: 800
				},
				axis: {
					x: { type: "category" },
					y: {
						tick: { format: d => d + "%" }
					}
				},
				tooltip: {
					grouped: false,
					contents: function (d) {
						let data = d[0];
						let formIndex = data.index;
						let form = chartData.data[formIndex];
			
						let ratio = data.value + "%";
						let displays = form.events?.Display || 0;
						let submits = form.events?.Submit || 0;
						let tipo = form.settings?.form_doble_optin === "yes" ? "Doble OptIn" : "Clásico";
			
						return `
						<div class="bb-tooltip p-t-12 p-b-12 p-l-12 p-r-12">
									<strong>${form.name}</strong><br>
									Ratio de conversión: <strong>${ratio}</strong><br>
									Impresiones: <strong>${displays}</strong><br>
									Suscripciones: <strong>${submits}</strong><br>
									Tipo: <strong>${tipo}</strong>
								</div>`;
					}
				},
				bindto: "#doppler-forms-chart"
			});
		}
	});

	function listsLoading() {
		$("form input, form button").prop("disabled", true);
		$("#dplr-crud").addClass("loading");
	}

	function listsLoaded() {
		$("form input, form button").prop("disabled", false);
		$("form input").val("");
		$("#dplr-crud").removeClass("loading");
		$("#dplr-tbl-lists").removeClass("d-none");
	}

	function loadLists(page, per_page) {
		var data = {
			action: "dplr_get_lists",
			page: page,
			per_page: per_page,
		};

		if (page == 1) {
			listsLoading();
			$("#dplr-tbl-lists tbody tr").remove();
		} else {
			$("#crud-show-more").addClass("button--loading");
		}

		$.post(ajaxurl, data, function (response) {
			if (response.length > 0) {
				var obj = JSON.parse(response);
				var items = obj.items;
				var html = "";
				for (const key in items) {
					var value = items[key];
					html += "<tr>";
					html += "<td><strong>" + value.name + "</strong></td>";
					html += "<td>" + value.subscribersCount + "</td>";
					html +=
						'<td><div class="dp-icons-group"><a href="#" class="dplr-remove" data-list-id="' +
						value.listId +
						'">' +
						'<div class="dp-tooltip-container"> \
						<span class="ms-icon icon-delete"></span> \
						<div class="dp-tooltip-top"> \
							<span>' +
						object_string.Delete +
						"</span> \
							</div> \
						</div>" +
						"</a></div></td>";
					html += "</tr>";
				}

				$("#dplr-tbl-lists tbody").prepend(html);
				if (page == 1) {
					listsLoaded();
				} else {
					$("#crud-show-more").removeClass("button--loading");
				}

				if (page < parseInt(obj.pagesCount)) {
					$("#crud-show-more")
						.css("visibility", "visible")
						.attr("data-next-page", parseInt(page) + 1);
				} else {
					$("#crud-show-more").css("visibility", "hidden");
				}
			}
		});
	}

	function deleteList(e) {
		e.preventDefault();

		var a = $(this);
		var tr = a.closest("tr");
		var listId = a.attr("data-list-id");
		var data = {
			action: "dplr_delete_list",
			listId: listId,
		};

		clearResponseMessages();

		$("#dplr-dialog-confirm").dialog({
			width: 450,
			create: function (event, ui) {
				$(".ui-dialog-title").css(
					"max-width",
					$(this).closest(".ui-dialog").width() - 50 + "px"
				);
			},
			buttons: [
				{
					text: object_string.Delete,
					click: function () {
						$(this).dialog("close");
						tr.addClass("deleting");
						$.post(ajaxurl, data, function (response) {
							var obj = JSON.parse(response);
							if (obj.response.code == 200) {
								tr.remove();
								return;
							}
							obj.response.code == 0
								? $("#showErrorResponse")
										.css("display", "flex")
										.html("<p>" + obj.response.message + "</p>")
								: displayErrors(JSON.parse(obj.body));
							tr.removeClass("deleting");
						});
					},
				},
				{
					text: object_string.Cancel,
					click: function () {
						$(this).dialog("close");
					},
				},
			],
		});

		$("#dplr-dialog-confirm").dialog("open");
	}
})(jQuery);

function displayErrors(body) {
	jQuery("#showErrorResponse")
		.css("display", "flex")
		.html("<p>" + generateErrorMsg(body) + "</p>");
}

function displaySuccess(successMsg) {
	if (successMsg == "") return false;
	jQuery("#showSuccessResponse")
		.css("display", "flex")
		.html("<p>" + successMsg + "</p>");
}

function clearResponseMessages() {
	jQuery("#showSuccessResponse,#showErrorResponse")
		.html("")
		.css("display", "none");
	jQuery("#displaySuccessMessage,#displayErrorMessage").remove();
}

function generateErrorMsg(body) {
	let status = body.status,
		code = body.errorCode,
		title = body.title,
		detail = body.detail;
	let err = "";
	let errors = {
		400: {
			1: object_string.validationError,
			2: object_string.duplicatedName,
			3: object_string.maxListsReached,
			8:
				typeof body.blockingReasonCode !== "undefined"
					? object_string[body.blockingReasonCode]
					: "",
		},
		401: {},
		404: {},
		429: { 0: object_string.tooManyConn },
	};
	if (status === 528) {
		err = object_string.cURL28Error;
		return err;
	}
	if (typeof errors[status] === "undefined")
		err = object_string.APIConnectionErr;
	else
		typeof errors[status][code] === "undefined"
			? (err = "<strong>" + title + "</strong> " + detail)
			: (err = errors[status][code]);
	return err;
}

function validateEmailContent(e) {
	var content = "";

	if (!tinyMCE.activeEditor)
		jQuery(".wp-editor-wrap .switch-tmce").trigger("click");

	content = tinymce.activeEditor.getContent();

	content = content.replace(
		'href="[[[ConfirmationLink]]]"',
		"href=[[[ConfirmationLink]]]"
	);
	content = content.replace(
		'href="http://[[[ConfirmationLink]]]"',
		"href=[[[ConfirmationLink]]]"
	);

	if (!tinyMCE.activeEditor) {
		tinyMCE.activeEditor.setContent(content);
	} else {
		document.getElementById("content").value = content;
	}

	if (document.getElementById("settings[form_doble_optin]").value === "yes") {
		if (!content.includes("href=[[[ConfirmationLink]]]")) {
			document.getElementById("error-message").classList.remove("d-none");

			//cancel form submition
			if (e) {
				e.preventDefault();
			}
		} else {
			document.getElementById("error-message").classList.add("d-none");
		}
	}
}

function hideShowConfigLandingOrURL() {
	if (document.getElementById("mostrar_landing").checked) {
		document.getElementById("div_landing_page").style.display = "block";
		document.getElementById("div_url_destino").style.display = "none";
	} else {
		if (!document.getElementById("mostrar_url").checked) {
			document.getElementById("div_landing_page").style.display = "none";
			document.getElementById("div_url_destino").style.display = "none";
		} else {
			document.getElementById("div_landing_page").style.display = "none";
			document.getElementById("div_url_destino").style.display = "block";
		}
	}
}

// remove quote marks from ConfirmationLink href.
function removeQuoteMarksFromConfirmationLink() {
	if (!tinyMCE.activeEditor)
		jQuery(".wp-editor-wrap .switch-tmce").trigger("click");

	if (!tinyMCE.activeEditor) {
		var content = document.getElementById("content").value;
		if (content.includes('href="[[[ConfirmationLink]]]"')) {
			content = content.replace(
				'href="[[[ConfirmationLink]]]"',
				"href=[[[ConfirmationLink]]]"
			);
			document.getElementById("content").value = content;
		}
	} else {
		var content = tinyMCE.activeEditor.getContent();
		if (content.includes('href="[[[ConfirmationLink]]]"')) {
			content = content.replace(
				'href="[[[ConfirmationLink]]]"',
				"href=[[[ConfirmationLink]]]"
			);
			tinyMCE.activeEditor.setContent(content);
		}
	}
}

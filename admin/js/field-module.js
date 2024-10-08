var FormFieldsView;
var FieldModel;

(function ($) {
	FormFieldsView = (function () {
		function FormFieldsView(
			unselectedFields,
			selectedFields,
			unselectedFieldsContainer,
			selectedFieldsContainer,
			readOnly = false
		) {
			var _this = this;
			this.unselectedFieldsContainer = unselectedFieldsContainer;
			this.selectedFieldsContainer = selectedFieldsContainer;
			this.unselectedFields = unselectedFields;
			this.readOnly = readOnly;
			this.selectedFields = [];

			this.getIndexOfCustom = function (elem, index, arrayOfElements) {
				if (arrayOfElements.length < index) return -1;

				if (arrayOfElements[index].name == elem.name) return index;

				return this.getIndexOfCustom(elem, ++index, arrayOfElements);
			};

			for (var index in unselectedFields) {
				var unselectedField = unselectedFields[index];
				this.addUnselectElement(unselectedField);
				if (
					(selectedFields == null || selectedFields.length == 0) &&
					unselectedField.name == "EMAIL"
				) {
					unselectedField;
					selectedFields = [unselectedField];
				}
			}

			for (var index in selectedFields) {
				this.selectElement(selectedFields[index]);
			}

			this.unselectedFieldsContainer.on("change", function () {
				_this.selectElement({ name: $(this).val() });
			});
		}

		FormFieldsView.prototype.addUnselectElement = function (element) {
			this.unselectedFieldsContainer.append(
				'<option data-field-id="' +
					element.id +
					'" data-field-type="' +
					element.type +
					'" value="' +
					element.name +
					'">' +
					element.name +
					"</option>"
			);
		};

		FormFieldsView.prototype.selectElement = function (fieldToSelect) {
			var _this = this;
			var selectedIndex = this.getIndexOfCustom(
				fieldToSelect,
				0,
				this.unselectedFields
			);
			var unselectedField = this.unselectedFields[selectedIndex];

			if (fieldToSelect.settings != undefined)
				unselectedField.settings = fieldToSelect.settings;

			var selectedOption = this.unselectedFieldsContainer.find(
				"option[value='" + unselectedField.name + "']"
			);

			this.selectedFields.push(unselectedField);
			this.unselectedFields.splice(selectedIndex, 1);

			selectedOption.addClass("selected");

			selectedOption.removeAttr("selected");

			//Add Selected Item view
			var newSelectedFieldDOMElement = $(
				this.renderSelectedField(unselectedField)
			);

			newSelectedFieldDOMElement.find(".icon-close").click(function () {
				var selectedFieldContainer = $(this).closest("li");
				_this.unselectItem(selectedFieldContainer);
			});

			this.selectedFieldsContainer.append(newSelectedFieldDOMElement);

			$("#select-" + unselectedField.name).on("change", function () {
				if ($(this).val() === "options") {
					$("#optionsArea-" + unselectedField.name).show();
				} else {
					$("#optionsArea-" + unselectedField.name).hide();
				}
			});

			$("textarea[data-validation]").on("blur", function () {
				var value = $(this).val();
				if (value.length > 0 && value.indexOf("\n") !== -1) {
					var trimedValues = value.split("\n").map(function (item) {
						return item.trim();
					});
					var filteredValues = trimedValues.filter(function (el) {
						return el !== "";
					});
					$(this).val(filteredValues.join("\n"));
				}
			});
		};

		FormFieldsView.prototype.renderSelectedField = function (field) {
			field.settings =
				field.settings != undefined
					? field.settings
					: {
							required: false,
							description: "",
							placeholder: "",
					  };
			var dateFormats = [
				"dd/mm/yy",
				"dd/mm/y",
				"mm/dd/yy",
				"mm/dd/y",
				"yy/mm/dd",
				"y/mm/dd",
				"yy/dd/mm",
				"y/dd/mm",
				"dd-mm-yy",
				"dd-mm-y",
				"mm-dd-yy",
				"mm-dd-y",
				"yy-mm-dd",
				"y-mm-dd",
				"yy-dd-mm",
				"y-dd-mm",
			];

			var html = "<li data-field-name='" + field.name + "'>";
			html +=
				field.name != "EMAIL" ? "<div class='ms-icon icon-close'></div>" : "";
			html +=
				field.name +
				"<span class='type'> (" +
				field.type +
				")</span> <a class='alt-toggle'>" +
				ObjStr.editField +
				" <i></i></a> ";
			html +=
				' <input type="hidden" name="fields[' +
				field.name +
				'][type]" value="' +
				field.type +
				'">';
			html += ' <div class="accordion-content field-settings">';

			var checkedRequired =
				field.settings.required || field.readonly ? "checked" : "";
			var readonly =
				field.readonly || this.readOnly ? "disabled='disabled'" : "";
			var label =
				field.settings.label != undefined ? field.settings.label : field.name;
			html += field.readonly
				? '		<input type="hidden" name="fields[' +
				  field.name +
				  '][settings][required]" value="required">'
				: "";
			html +=
				'<div class="dplr_input_section horizontal">' +
				'<div class="awa-form">';
			html +=
				'<label class="dp-label-checkbox" for="fields[' +
				field.name +
				'][settings][required]">' +
				'<input type="checkbox" class="setting-required"' +
				readonly +
				' name="fields[' +
				field.name +
				'][settings][required]" value="required"' +
				checkedRequired +
				' id="fields[' +
				field.name +
				'][settings][required]">' +
				"<span>" +
				ObjStr.Required +
				"</span>";
			("</label>");
			html += "</div>";
			html += "		</div>";
			html += '		<div class="dplr_input_section horizontal">';
			html +=
				'			<label for="fields[' +
				field.name +
				'][settings][label]">' +
				ObjStr.LabelToShow +
				"</label>";
			html +=
				'   	<input class="setting-required" type="text" name="fields[' +
				field.name +
				'][settings][label]" value="' +
				label +
				'" ' +
				(this.readOnly ? "disabled" : "") +
				"><br>";
			html += "		</div>";
			html += '		<div class="dplr_input_section horizontal">';
			html +=
				'			<label for="fields[' +
				field.name +
				'][settings][description]">' +
				ObjStr.Description +
				"</label>";
			html +=
				'   	<textarea name="fields[' +
				field.name +
				'][settings][description]" ' +
				(this.readOnly ? "disabled" : "") +
				">" +
				field.settings.description +
				"</textarea><br>";
			html += "		</div>";
			if (field.type === "date") {
				html += '		<div class="dplr_input_section horizontal">';
				html +=
					'			<label for="fields[' +
					field.name +
					'][settings][placeholder]">' +
					ObjStr.Placeholder +
					"</label>";
				html +=
					'   	<input type="text" name="fields[' +
					field.name +
					'][settings][placeholder]" value="' +
					(typeof field.settings.placeholder != "undefined" &&
					field.settings.placeholder != ""
						? field.settings.placeholder
						: "dd/mm/yyyy") +
					'" ' +
					(this.readOnly ? "disabled" : "") +
					">";
				html += "		</div>";
				html += '		<div class="dplr_input_section horizontal">';
				html +=
					'<label for="fields[' +
					field.name +
					'][settings][dateFormat]">' +
					ObjStr.DateFormat +
					"</label>";
				html +=
					'<select name="fields[' + field.name + '][settings][dateFormat]">';
				dateFormats.forEach((format) => {
					html +=
						'<option value="' +
						format +
						'"' +
						(field.settings.dateFormat == format ? "selected" : "") +
						">" +
						format +
						"</option>";
				});
				html += "</select>";
				html += "		</div>";
			}
			if ($.inArray(field.type, ["boolean", "gender", "date"]) == -1) {
				html += '		<div class="dplr_input_section horizontal">';
				html +=
					'			<label for="fields[' +
					field.name +
					'][settings][placeholder]">' +
					ObjStr.Placeholder +
					"</label>";
				html +=
					'   	<input type="text" name="fields[' +
					field.name +
					'][settings][placeholder]" value="' +
					field.settings.placeholder +
					'" ' +
					(this.readOnly ? "disabled" : "") +
					">";
				html += "		</div>";
			}
			if (field.type === "string") {
				html += '		<div class="dplr_input_section horizontal">';
				html +=
					'			<label for="fields[' +
					field.name +
					'][settings][text_lines]">' +
					ObjStr.TextType +
					"</label>";
				html +=
					'				<select name="fields[' +
					field.name +
					'][settings][text_lines]" ' +
					(this.readOnly ? "disabled" : "") +
					'id="select-' +
					field.name +
					'">';
				html +=
					field.settings.text_lines == "single"
						? '				<option selected="selected" value="single">' +
						  ObjStr.OneSingleLine +
						  "</option>"
						: '				<option value="single">' + ObjStr.OneSingleLine + "</option>";
				html +=
					field.settings.text_lines == "multi"
						? '   		<option selected="selected" value="multi">' +
						  ObjStr.MultipleLines +
						  "</option>"
						: '   		<option value="multi">' + ObjStr.MultipleLines + "</option>";
				html +=
					field.settings.text_lines == "options"
						? '				<option selected="selected" value="options">' +
						  ObjStr.optionsLine +
						  "</option>"
						: '				<option value="options">' + ObjStr.optionsLine + "</option>";
				html += "			</select>";
				html += "		</div>";
				html +=
					'		<div class="dplr_input_section horizontal" id="optionsArea-' +
					field.name +
					'" ' +
					(field.settings.text_lines == "options" ? "" : "hidden") +
					">";
				html +=
					' <label for="name="fields[' +
					field.name +
					'][settings][text_options]">' +
					ObjStr.OptionsLabel +
					"</label>";
				html +=
					' <textarea name="fields[' +
					field.name +
					'][settings][text_options]"' +
					' rows="3" cols="80" maxlength="150" data-validation>' +
					(field.settings.text_options ? field.settings.text_options : "") +
					"</textarea>";
				html += "<p>" + ObjStr.OptionsDescription + "</p>";
				html += "	</div>";
			}
			html += " </div>";
			html += "</li>";
			return html;
		};

		FormFieldsView.prototype.unselectItem = function (item) {
			var fieldName = item.attr("data-field-name");

			var selectedIndex = this.getIndexOfCustom(
				{ name: fieldName },
				0,
				this.selectedFields
			);
			var fieldToUnselect = this.selectedFields[selectedIndex];

			var selectedOption = this.unselectedFieldsContainer.find(
				"option[value='" + fieldName + "']"
			);

			item.remove();

			this.unselectedFields.push(fieldToUnselect);
			this.selectedFields.splice(selectedIndex, 1);

			selectedOption.removeClass("selected");
		};

		return FormFieldsView;
	})();
})(jQuery);

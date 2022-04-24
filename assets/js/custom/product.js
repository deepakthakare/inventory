/**
 * @Author: Deepak
 */
$(document).ready(function () {
	//add attributes
	$("#btnAddNewAttributes").on("click", function () {
		let attributes = $("#attributes option:selected").val();
		let attributes_text = $("#attributes option:selected").text();
		let attributes_value = $("#attributes_value option:selected").val();
		let stylecode = $("#stylecode").val();
		let inventory = $("#inventory").val();
		let barcode = $("#barcode").val();

		if (attributes == "" || attributes == null) {
			$("#attributes").focus();
			if ($("#attributes").parent("div").find(".text-danger").length == 0) {
				$("#attributes").after(
					'<span class="text-danger">Select An Attributes.</span>'
				);
			}
			return false;
		} else {
			$("#attributes").parent("div").find(".text-danger").remove();
		}

		if (attributes_value == "" || attributes_value == null) {
			$("#attributes_value").focus();
			if (
				$("#attributes_value").parent("div").find(".text-danger").length == 0
			) {
				$("#attributes_value").after(
					'<span class="text-danger">Select An Attributes Value.</span>'
				);
			}
			return false;
		} else {
			$("#attributes_value").parent("div").find(".text-danger").remove();
		}
		if (stylecode == "" || stylecode == null) {
			$("#stylecode").focus();
			if ($("#stylecode").parent("div").find(".text-danger").length == 0) {
				$("#stylecode").after(
					'<span class="text-danger">Enter Stylecode.</span>'
				);
			}
			return false;
		} else {
			$("#stylecode").parent("div").find(".text-danger").remove();
		}

		if (inventory == "" || inventory == null) {
			$("#inventory").focus();
			if ($("#inventory").parent("div").find(".text-danger").length == 0) {
				$("#inventory").after(
					'<span class="text-danger">Enter Inventory.</span>'
				);
			}
			return false;
		} else {
			$("#inventory").parent("div").find(".text-danger").remove();
		}

		let new_row =
			"<tr >" +
			'<td><input type="hidden" class="form-control" readonly name="attributes[]" value="' +
			attributes +
			'"><input type="text" class="form-control" readonly name="attributes_text[]" value="' +
			attributes_text +
			'"></td>' +
			'<td><input type="text" class="form-control"  readonly name="attributes_value[]" value="' +
			attributes_value +
			'" ></td>' +
			'<td><input type="text" class="form-control"  readonly name="stylecode[]" value="' +
			stylecode +
			'" ></td>' +
			'<td><input type="text" class="form-control" name="inventory[]" value="' +
			inventory +
			'" ></td>' +
			'<td><input type="text" class="form-control" readonly name="barcode[]" value="' +
			barcode +
			'"></td>' +
			'<td><a href="#!" class="btn-danger btn-circle btn-sm text-white btn_remove_row" onclick="removeROW(this);" ><i class="fas fa-trash-alt"></i></a></td>' +
			"</tr>";
		$("#tbl_attributes tbody").append(new_row);
		clearText();
	});
	//end of add attributes

	$("#attributes").change(function () {
		let attributes = $("#attributes option:selected").val();
		if (attributes == "1" || attributes == null) {
			$("#qtyDisplay").css("display", "none");
			$("#inventory").val("0");
			$("#barcode").val("999999999999");
		} else {
			$("#qtyDisplay").css("display", "block");
			$("#inventory").val("");
			document.getElementById("barcode").value = generateBarcode();
		}
	});

	$("#addAttributesBRD").on("click", function () {
		document.getElementById("barcode").value = generateBarcode();
	});
	// const abc
	const clearText = () => {
		$("#attributes").val("");
		$("#attributes_value").val("");
		$("#stylecode").val("");
		$("#inventory").val("");
		document.getElementById("barcode").value = generateBarcode();
	};

	const generateBarcode = () => {
		let uniqueNumber = Math.floor(200000000000 + Math.random() * 900000000000);
		let brdWithCheckSum = getLastEan13Digit(uniqueNumber);
		let result = "".concat(uniqueNumber, brdWithCheckSum);
		return result;
	};

	const getLastEan13Digit = (eanNum) => {
		let ean = eanNum.toString();
		if (!ean || ean.length !== 12)
			throw new Error("Invalid EAN 13, should have 12 digits");
		const multiply = [1, 3];
		let total = 0;
		ean.split("").forEach((letter, index) => {
			total += parseInt(letter, 10) * multiply[index % 2];
		});
		const base10Superior = Math.ceil(total / 10) * 10;
		return base10Superior - total;
	};
	//remove an added table row
	removeROW = function (row) {
		row.closest("tr").remove();
	};
	//change attributes
	$("#attributes").on("change", function () {
		let attributes = $(this).find(":selected").attr("data-attr_values");
		if (attributes != null) {
			let attributes_value = JSON.parse(attributes);
			let attrSelect = $("#attributes_value");
			attrSelect.html("");
			attrSelect.append($("<option></option>").val("").html("Please Select"));
			$.each(attributes_value, function (key, val) {
				attrSelect.append(
					$("<option></option>").val(val.value).html(val.value)
				);
			});
		}
	});
	// end of change attributes
});

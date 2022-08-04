/**
 * @Author: Deepak
 */
$(document).ready(function () {
	//add attributes
	$("#btnAddNewAttributes").on("click", function () {
		// let attributes = $("#attributes option:selected").val();
		// let attributes_text = $("#attributes option:selected").text();
		let attributes_value = $("#attributes_value option:selected").val();
		let colors_value = $("#colors_value option:selected").val();
		let sizes_value = $("#sizes_value option:selected").val();
		let stylecode = $("#stylecode").val();
		let inventory = $("#inventory").val();
		let barcode = $("#barcode").val();

		/* if (attributes == "" || attributes == null) {
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
		} */

		if (colors_value == "" || colors_value == null) {
			$("#colors_value").focus();
			if ($("#colors_value").parent("div").find(".text-danger").length == 0) {
				$("#colors_value").after(
					'<span class="text-danger">Select An Color Value.</span>'
				);
			}
			return false;
		} else {
			$("#colors_value").parent("div").find(".text-danger").remove();
		}

		if (sizes_value == "" || sizes_value == null) {
			$("#sizes_value").focus();
			if ($("#sizes_value").parent("div").find(".text-danger").length == 0) {
				$("#sizes_value").after(
					'<span class="text-danger">Select An Size Value.</span>'
				);
			}
			return false;
		} else {
			$("#sizes_value").parent("div").find(".text-danger").remove();
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
			'<td><input type="text" class="form-control"  readonly name="colors_value[]" value="' +
			colors_value +
			'" ></td>' +
			'<td><input type="text" class="form-control" readonly name="sizes_value[]" value="' +
			sizes_value +
			'"></td>' +
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

	// $("#attributes").change(function () {
	// 	let attributes = $("#attributes option:selected").val();
	// 	if (attributes == "1" || attributes == null) {
	// 		$("#qtyDisplay").css("display", "none");
	// 		$("#inventory").val("0");
	// 		$("#barcode").val("999999999999");
	// 	} else {
	// 		$("#qtyDisplay").css("display", "block");
	// 		$("#inventory").val("");
	// 		document.getElementById("barcode").value = generateBarcode();
	// 	}
	// });

	$("#addAttributesBRD").on("click", function () {
		document.getElementById("barcode").value = generateBarcode();

		// For Get colors and display in dropdownItem
		let color = $("#color").find("option").last().attr("data-color_values");
		let colors_value = JSON.parse(color);
		let colorSelect = $("#colors_value");
		colorSelect.html("");
		colorSelect.append($("<option></option>").val("").html("Please Select"));
		$.each(colors_value, function (key, val) {
			colorSelect.append($("<option></option>").val(val.value).html(val.value));
		});
		//console.log(colorSelect, "colorSelect");

		// For get Sizes and display in dropdownItem

		let size = $("#size").find("option").last().attr("data-size_values");
		let sizes_value = JSON.parse(size);
		let sizeSelect = $("#sizes_value");
		sizeSelect.html("");
		sizeSelect.append($("<option></option>").val("").html("Please Select"));
		$.each(sizes_value, function (key, val) {
			sizeSelect.append($("<option></option>").val(val.value).html(val.value));
		});
	});
	// const abc
	const clearText = () => {
		// $("#attributes").val("");
		// $("#attributes_value").val("");
		$("#colors_value").val("");
		$("#sizes_value").val("");
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
	/* $("#attributes").on("change", function () {
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
			console.log(attrSelect, "attrSelect");
		}
	}); */
	// end of change attributes
});

$(function () {
	$("#p_price, #weight")
		.on("input", function (e) {
			if (/^(\d+(\.\d{0,2})?)?$/.test($(this).val())) {
				// Input is OK. Remember this value
				$(this).data("prevValue", $(this).val());
			} else {
				// Input is not OK. Restore previous value
				$(this).val($(this).data("prevValue") || "");
			}
		})
		.trigger("input"); // Initialise the `prevValue` data properties
});

const printBarcode = (id, barcode) => {
	/* swal("Write something here:", {
		content: "input",
	}).then((value) => {
		swal(`You typed: ${value}`);
	}); */
	swal(
		{
			title: "",
			text: "Enter Barcode Quantity:",
			type: "input",
			showCancelButton: true,
			closeOnConfirm: false,
			inputPlaceholder: "Barcode Quantity",
			customClass: "swal-wide",
		},
		function (inputValue) {
			if (inputValue === false) return false;
			if (inputValue === "") {
				swal.showInputError("Enter Barcode Quantity.");
				return false;
			}
			console.log(`${inputValue} - ${id} -${barcode} `);
			if (id !== "" || barcode !== "") {
				swal("", "Barcode Generated successfully", "success");
				/* let hostname = window.location.origin;
				console.log(hostname); */
				let url =
					"http://localhost/sales-inventory-ci/" +
					"html/ean13.php?id=" +
					id +
					"&barcode=" +
					barcode +
					"&quantity=" +
					inputValue;
				window.open(url, "_blank");
			}
		}
	);
};

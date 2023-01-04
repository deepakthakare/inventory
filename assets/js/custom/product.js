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
					'<span class="text-danger">Enter Quantity.</span>'
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

	$("#btnAddVendorWarehouse").on("click", function () {
		//console.log("clicked add button");
		let vendor_value = $("#vendors option:selected").val();
		let warehouse_value = $("#warehouse_value option:selected").val();
		let place_value = $("#place_value option:selected").val();
		let aisle_value = $("#aisle_value option:selected").val();
		let section_value = $("#section_value option:selected").val();
		let subsection_value = $("#subsection_value option:selected").val();
		let number_value = $("#number_value").val();

		//Validation
		if (vendor_value == "" || vendor_value == null) {
			$("#vendors").focus();
			if ($("#vendors").parent("div").find(".text-danger").length == 0) {
				$("#vendors").after(
					'<span class="text-danger">Select An Vendor Value.</span>'
				);
			}
			return false;
		} else {
			$("#vendors").parent("div").find(".text-danger").remove();
		}
		//end validation

		//Get Code values
		let vendor_code = $("#vendors").find(":selected").attr("data-vendor_code");
		let warehouse_code = $("#warehouse_value")
			.find(":selected")
			.attr("data-warehouse_code");
		let place_code = $("#place_value")
			.find(":selected")
			.attr("data-place_code");
		let aisle_code = $("#aisle_value")
			.find(":selected")
			.attr("data-aisle_code");
		let section_code = $("#section_value")
			.find(":selected")
			.attr("data-section_code");
		let subsection_code = $("#subsection_value")
			.find(":selected")
			.attr("data-subsection_code");

		warehouse_code = warehouse_code = warehouse_code || "99";
		place_code = place_code = place_code || "99";
		aisle_code = aisle_code = aisle_code || "99";
		section_code = section_code = section_code || "99";
		subsection_code = subsection_code = subsection_code || "99";

		warehouse_value = warehouse_value = warehouse_value || 0;
		place_value = place_value = place_value || 0;
		aisle_value = aisle_value = aisle_value || 0;
		section_value = section_value = section_value || 0;
		subsection_value = subsection_value = subsection_value || 0;
		number_value = number_value = number_value || 0;
		
		let allValues = {
			vendor_value,
			warehouse_value,
			place_value,
			aisle_value,
			section_value,
			subsection_value,
			number_value,
		};

		allValues = JSON.stringify(allValues).replace(/\"/g, '"');
		//console.log(allValues,"asdfasdfasdf")
		let main = document.getElementById("tbl_vendordetails");
		main.setAttribute("value", allValues);
		let location =
			vendor_code +
			"-" +
			warehouse_code +
			"" +
			place_code +
			"" +
			aisle_code +
			"" +
			section_code +
			"" +
			subsection_code +
			"" +
			number_value;

		if (vendor_code !== "undefined") {
			$("#location").val(location);

			let div = document.createElement("div");
			div.id = "locationC";
			div.className = "btn btn-warning btn-sm";
			div.title = "Generated Location";
			document.getElementById("locationP").appendChild(div);
			$("#locationC").html(location);
		}
		$("#locationModal").modal("hide");
	});

	// const abc
	const clearText = () => {
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

$("#vendors").on("change", function () {
	let vendorID = $("#vendors").val();
	$.ajax({
		type: "POST",
		data: { vendorID: vendorID },
		url: ADMIN_URL + "products/onchangeVendor",
		success: function (result) {
			//console.log(result,"result")
			$("#warehouse_value").html(result);
		},
	});
});

$("#warehouse_value").on("change", function () {
	let warehouseID = $("#warehouse_value").val();
	$.ajax({
		type: "POST",
		data: { warehouseID: warehouseID },
		url: ADMIN_URL + "products/onchangeWarehouse",
		success: function (result) {
			$("#place_value").html(result);
		},
	});
});

$("#place_value").on("change", function () {
	let aisleID = $("#place_value").val();
	$.ajax({
		type: "POST",
		data: { aisleID: aisleID },
		url: ADMIN_URL + "products/onchangePlace",
		success: function (result) {
			$("#aisle_value").html(result);
		},
	});
});

$("#aisle_value").on("change", function () {
	let sectionID = $("#aisle_value").val();
	$.ajax({
		type: "POST",
		data: { sectionID: sectionID },
		url: ADMIN_URL + "products/onchangeAisle",
		success: function (result) {
			$("#section_value").html(result);
		},
	});
});

$("#section_value").on("change", function () {
	let subsectionID = $("#section_value").val();
	console.log(subsectionID, "subsectionID");
	$.ajax({
		type: "POST",
		data: { subsectionID: subsectionID },
		url: ADMIN_URL + "products/onchangeSection",
		success: function (result) {
			$("#subsection_value").html(result);
		},
	});
});

const printBarcode = (id, barcode) => {
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
				// let hostname = window.location.origin;
				// console.log(hostname);
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

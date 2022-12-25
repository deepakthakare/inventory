/* #@Author: Deepak Thakare */

const displayVariantsPopup = (id_product) => {
	$("#myLargeModalLabel").modal(
		{ backdrop: "static", keyboard: false },
		"show"
	);
	// For Get colors and display in dropdownItem
	let color = $("#color").find("option").last().attr("data-color_values");
	let colors_value = JSON.parse(color);
	let colorSelect = $("#colors_value");
	colorSelect.html("");
	colorSelect.append($("<option></option>").val("").html("Please Select"));
	$.each(colors_value, function (key, val) {
		colorSelect.append($("<option></option>").val(val.value).html(val.value));
	});

	// For get Sizes and display in dropdownItem

	let size = $("#size").find("option").last().attr("data-size_values");
	let sizes_value = JSON.parse(size);
	let sizeSelect = $("#sizes_value");
	sizeSelect.html("");
	sizeSelect.append($("<option></option>").val("").html("Please Select"));
	$.each(sizes_value, function (key, val) {
		sizeSelect.append($("<option></option>").val(val.value).html(val.value));
	});
	$("#product_id").val(id_product);
	$("#editVarinatBtn").hide();
	$.ajax({
		type: "POST",
		data: { id_product: id_product },

		url: ADMIN_URL + "delnote/getAllProductVariants",
		success: function (data) {
			let resp = JSON.parse(data);
			console.log(resp, "responseData");
			if (resp.length !== 0) {
				let new_row = "";
				$.each(resp, function (i, variantData) {
					let id = variantData.id_variant;
					new_row +=
						"<tr style='font-weight:700;' id='row-" +
						id +
						"'><td id='color-" +
						id +
						"'>" +
						variantData.color +
						"</td><td id='size-" +
						id +
						"'>" +
						variantData.size +
						"</td><td id='qty-" +
						id +
						"'>" +
						variantData.variant_quantity +
						"</td><td id='barcode-" +
						id +
						"'>" +
						variantData.variant_barcode +
						"</td><td style='display:none'>" +
						variantData.id_variant +
						"</td><td>" +
						'<a href="#!" class="btn-success btn-circle btn-sm text-white btn_remove_row" data-toggle="tooltip" data-placement="top" title="Edit" id="editVariant" style="margin-right: 3px;"><i class="fas fa-edit"></i></a>' +
						'<a href="#!" class="btn-primary btn-circle btn-sm text-white btn_remove_row" data-toggle="tooltip" data-placement="top" title="Print Barcode" onclick="barcodePrinting(' +
						id +
						"," +
						variantData.variant_barcode +
						');" style="margin-right: 3px;"><i class="fa fa-barcode"></i></a>' +
						'<a href="#!" class="btn-danger btn-circle btn-sm text-white btn_remove_row" onclick="removeVariant(' +
						id +
						')"  title="Delete" ><i class="fas fa-trash-alt"></i></a>' +
						"</td>" +
						"</td></tr>";
				});
				$("#tbl_variants").append(new_row);
			}
		},
	});
};

const clearAllRows = () => {
	$("#tbl_variants").empty();
};
const addProductVariants = () => {
	let colors_value = $("#colors_value option:selected").val();
	let sizes_value = $("#sizes_value option:selected").val();
	let variant_quantity = $("#variant_quantity").val();
	let variant_barcode = $("#variant_barcode").val();
	let id_product = $("#product_id").val();
	// check validation
	if (
		variant_quantity == "" ||
		variant_quantity == null ||
		variant_quantity <= 0
	) {
		$("#variant_quantity").focus();
		if ($("#variant_quantity").parent("div").find(".show_error").length == 0) {
			$("#variant_quantity").addClass("show_error");
		}
		return false;
	} else {
		$("#variant_quantity").parent("div").find(".text-danger").remove();
		$("#variant_quantity").removeClass("show_error");
	}

	if (variant_barcode == "" || variant_barcode == null) {
		$("#variant_barcode").focus();
		if ($("#variant_barcode").parent("div").find(".show_error").length == 0) {
			$("#variant_barcode").addClass("show_error");
		}
		return false;
	} else {
		$("#variant_barcode").parent("div").find(".text-danger").remove();
		$("#variant_barcode").removeClass("show_error");
	}
	$.ajax({
		type: "POST",
		data: {
			colors_value,
			sizes_value,
			variant_quantity,
			variant_barcode,
			id_product,
		},
		url: ADMIN_URL + "delnote/addProductVariants",
		success: function (data) {
			let res = JSON.parse(data);
			console.log(res, "responseData");
			if (res.length !== 0) {
				clearText();
				let new_row = "";
				$.each(res, function (i, variantData) {
					let id = variantData.id_variant;
					new_row +=
						"<tr style='font-weight:700;' id='row-" +
						id +
						"'><td id='color-" +
						id +
						"'>" +
						variantData.color +
						"</td><td id='size-" +
						id +
						"'>" +
						variantData.size +
						"</td><td id='qty-" +
						id +
						"'>" +
						variantData.variant_quantity +
						"</td><td id='barcode-" +
						id +
						"'>" +
						variantData.variant_barcode +
						"</td><td style='display:none'>" +
						variantData.id_variant +
						"</td><td>" +
						'<a href="#!" class="btn-success btn-circle btn-sm text-white btn_remove_row" data-toggle="tooltip" data-placement="top" title="Print Barcode" id="editVariant" style="margin-right: 3px;"><i class="fas fa-edit"></i></a>' +
						'<a href="#!" class="btn-primary btn-circle btn-sm text-white btn_remove_row" data-toggle="tooltip" data-placement="top" title="Print Barcode" onclick="barcodePrinting(' +
						id +
						"," +
						variantData.variant_barcode +
						');" style="margin-right: 3px;"><i class="fa fa-barcode"></i></a>' +
						'<a href="#!" class="btn-danger btn-circle btn-sm text-white btn_remove_row" onclick="removeVariant(' +
						id +
						')"  title="Delete" ><i class="fas fa-trash-alt"></i></a>' +
						"</td>" +
						"</td></tr>";
				});
				$("#tbl_variants").append(new_row);
			} else {
				/* //	console.log('array empty');
				$("#internal_ref").val(supplier_ref);
				barcodeGeneratorAndCheckExist();
				$("#is_barcode_recall").val(0); */
			}
		},
	});
};

$("#tbl_variants").on("click", "#editVariant", function () {
	const currentRow = $(this).closest("tr");
	const color = currentRow.find("td:eq(0)").text();
	const size = currentRow.find("td:eq(1)").text();
	const qty = currentRow.find("td:eq(2)").text();
	const barcode = currentRow.find("td:eq(3)").text();
	const id_variant = currentRow.find("td:eq(4)").text();
	$("#colors_value").val(color).trigger("click");
	$("#sizes_value").val(size).trigger("click");
	$("#variant_barcode").val(barcode);
	$("#variant_quantity").val(qty);
	$("#id_variant").val(id_variant);
	$("#colors_value").attr("disabled", true);
	$("#sizes_value").attr("disabled", true);
	$("#saveVariant").hide();
	$("#editVarinatBtn").show();
});

const updateProductVariants = () => {
	$("#saveVariant").show();
	$("#editVarinatBtn").hide();
	let id_variant = $("#id_variant").val();
	let qty = $("#variant_quantity").val();
	if (qty == "" || qty == null || qty <= 0) {
		$("#variant_quantity").focus();
		if ($("#variant_quantity").parent("div").find(".show_error").length == 0) {
			$("#variant_quantity").addClass("show_error");
		}
		return false;
	} else {
		$("#variant_quantity").parent("div").find(".text-danger").remove();
		$("#variant_quantity").removeClass("show_error");
	}

	$.ajax({
		type: "POST",
		data: { id_variant: id_variant, qty: qty },
		url: ADMIN_URL + "delnote/updateProductVariant",
		success: function (data) {
			let res = JSON.parse(data);
			console.log(res.row, "responseData");
			if (res.row == 1) {
				setTimeout(function () {
					swal("", "Variant Quantity Updated", "success");
					$("#qty-" + res.id_variant).text(res.qty);
					$("#colors_value").val("").trigger("click");
					$("#sizes_value").val("").trigger("click");
					$("#colors_value").attr("disabled", false);
					$("#sizes_value").attr("disabled", false);
					$("#variant_barcode").val("");
					$("#variant_quantity").val("");
				}, 1000);
			}
		},
	});
};

const clearFields = () => {
	$("#colors_value").val("").trigger("click");
	$("#sizes_value").val("").trigger("click");
	$("#variant_barcode").val("");
	$("#variant_quantity").val("");
	$("#saveVariant").show();
	$("#editVarinatBtn").hide();
	$("#colors_value").attr("disabled", false);
	$("#sizes_value").attr("disabled", false);
};

const removeVariant = (id) => {
	// console.log(id,'id');
	$.ajax({
		type: "POST",
		data: { id: id },
		url: ADMIN_URL + "delnote/removeProductVariant",
		success: function (data) {
			let res = JSON.parse(data);
			console.log(res, "responseData");
			if (res == true) {
				setTimeout(function () {
					$("#row-" + id).remove();
				}, 1000);
			}
		},
	});
};

const barcodePrinting = (id, brd) => {
	$("#myModaBarcode").modal("show");
	$("#brdqty").focus();
	$("#varinatid").val(id);
	$("#variantbrd").val(brd);
	let qty = $("#brdqty").val();
	// console.log(`${id} - ${brd} - ${qty}`,'brdID');
	if (qty == "" || qty == null || qty <= 0) {
		$("#variant_quantity").focus();
		if ($("#variant_quantity").parent("div").find(".show_error").length == 0) {
			$("#variant_quantity").addClass("show_error");
		}
		return false;
	} else {
		$("#variant_quantity").parent("div").find(".text-danger").remove();
		$("#variant_quantity").removeClass("show_error");
	}
	goToBarcodePage(id, brd);
};
const goToBarcodePage = () => {
	let v_qty = $("#brdqty").val();
	var v_id = $("#varinatid").val();
	var v_brd = $("#variantbrd").val();
	// console.log(`${v_id} - ${v_brd} - ${v_qty}`,'brdIDDDD');
	if (v_id !== "" && v_brd !== "" && v_qty !== "") {
		swal("", "Barcode Generated successfully", "success");
		// let hostname = window.location.origin;
		// console.log(hostname);
		let url =
			"http://localhost/sales-inventory-ci/" +
			"html/ean13.php?id=" +
			v_id +
			"&barcode=" +
			v_brd +
			"&quantity=" +
			v_qty;
		window.open(url, "_blank");
		$("#myModaBarcode").modal("hide");
		$("#brdqty").val("");
	}
};

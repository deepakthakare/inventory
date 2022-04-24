/**
 * @Last modified by:   Deepak
 */

$(document).ready(function () {
	// get all customers
	$.ajax({
		type: "post",
		url: ADMIN_URL + "sales/getShopifyCustomers",
		success: function (data) {
			$("#customer").html(data);
		},
	});

	// get shopify product
	$.ajax({
		type: "post",
		url: ADMIN_URL + "sales/getAllProducts",
		success: function (data) {
			$("#all_products").html(data);
		},
	});

	//add attributes
	$("#btnAddProduct").on("click", function () {
		let prod_id = $("#all_products option:selected").val();
		let image_path = $("#all_products option:selected").attr("data-image_path");
		let attributes_value = $("#product_variants option:selected").attr(
			"data-attributes_value"
		);
		let price = $("#all_products option:selected").attr("data-p_price");
		let product_name = $("#all_products option:selected").attr(
			"data-product_name"
		);
		let tax_rate = $("#product_variants option:selected").attr("data-tax_rate");
		let color = $("#product_variants option:selected").attr("data-color");
		let product_variants = $("#product_variants option:selected").attr(
			"data-variant_id"
		);
		let stylecode = $("#product_variants option:selected").attr(
			"data-stylecode"
		);
		///let customer = $("#customer option:selected").attr("data-tax_rate");
		let qty = $("#quantity").val();
		let row_id = $("#tbl_attributes tbody tr").length;
		let shipping = $("#shipping").val();
		let customer = $("#customer").val();

		var total_amount =
			parseFloat(price) + (parseFloat(price) * parseFloat(tax_rate)) / 100;
		total_amount = parseFloat(total_amount) * parseInt(qty);

		if (customer == "" || customer == null) {
			$("#customer").focus();
			if ($("#customer").parent("div").find(".text-danger").length == 0) {
				$("#customer").after(
					'<span class="text-danger">Select a Customer.</span>'
				);
			}
			return false;
		} else {
			$("#customer").parent("div").find(".text-danger").remove();
		}

		if (shipping == "" || shipping == null) {
			$("#shipping").focus();
			if ($("#shipping").parent("div").find(".text-danger").length == 0) {
				$("#shipping").after(
					'<span class="text-danger">Select a Shipping Method.</span>'
				);
			}
			return false;
		} else {
			$("#shipping").parent("div").find(".text-danger").remove();
		}

		if (prod_id == "" || prod_id == null) {
			$("#all_products").focus();
			if ($("#all_products").parent("div").find(".text-danger").length == 0) {
				$("#all_products").after(
					'<span class="text-danger">Select a product.</span>'
				);
			}
			return false;
		} else {
			$("#all_products").parent("div").find(".text-danger").remove();
		}

		if (product_variants == "" || product_variants == null) {
			$("#product_variants").focus();
			if (
				$("#product_variants").parent("div").find(".text-danger").length == 0
			) {
				$("#product_variants").after(
					'<span class="text-danger">Select a Variant.</span>'
				);
			}
			return false;
		} else {
			$("#product_variants").parent("div").find(".text-danger").remove();
		}

		if (qty == "" || qty == null) {
			$("#quantity").focus();
			if ($("#quantity").parent("div").find(".text-danger").length == 0) {
				$("#quantity").after(
					'<span class="text-danger">Enter quantity.</span>'
				);
			}
			return false;
		} else {
			$("#quantity").parent("div").find(".text-danger").remove();
		}

		var new_row =
			'<tr id="row_' +
			row_id +
			'">' +
			"<td>" +
			'<img src="' +
			image_path +
			'" width="50" height="50">' +
			'<input type="hidden" class="form-control" readonly name="image_path[]" value="' +
			image_path +
			'"></td>' +
			"<td>" +
			product_name +
			'<input type="hidden" class="form-control" readonly name="prod_id[]" value="' +
			prod_id +
			'"></td>' +
			"<td>" +
			stylecode +
			'<input type="hidden" class="form-control" readonly name="product_variants[]" value="' +
			product_variants +
			'"></td>' +
			"<td>" +
			color +
			'<input type="hidden" class="form-control" readonly name="color[]" value="' +
			color +
			'"></td>' +
			"<td>" +
			attributes_value +
			'<input type="hidden" class="form-control"  readonly name="attributes_value[]" value="' +
			attributes_value +
			'" ></td>' +
			"<td>" +
			price +
			'<input type="hidden" class="form-control"  name="price[]" value="' +
			price +
			'"></td>' +
			"<td>" +
			qty +
			'<input type="hidden" class="form-control"  name="qty[]" value="' +
			qty +
			'"></td>' +
			"<td>" +
			tax_rate +
			'<input type="hidden" class="form-control"  name="tax_rate[]" value="' +
			tax_rate +
			'"></td>' +
			"<td>" +
			total_amount.toFixed(2) +
			'<input type="hidden" class="form-control"  id="amount_' +
			row_id +
			'" name="total_amount[]" value="' +
			total_amount.toFixed(2) +
			'"></td>' +
			'<td><a href="#!" class="btn-danger btn-circle btn-sm text-white btn_remove_row" onclick="removeROW(this);" ><i class="fas fa-trash-alt"></i></a></td>' +
			"</tr>";

		$("#tbl_attributes tbody").append(new_row);
		subTotal();
		clearText();
	});

	//remove an added table row
	removeROW = function (row) {
		row.closest("tr").remove();
		subTotal();
	};

	const subTotal = () => {
		let tableProductLength = $("#tbl_attributes tbody tr").length;
		let productLength = tableProductLength - 1;
		let totalSubAmount = 0;
		for (x = 0; x < productLength; x++) {
			let tr = $("#tbl_attributes tbody tr").not(":first")[x];
			var count = $(tr).attr("id");
			count = count.substring(4);

			totalSubAmount =
				Number(totalSubAmount) + Number($("#amount_" + count).val());
		} // /for

		let vat_charge = 20;
		totalSubAmount = totalSubAmount.toFixed(2);
		console.log(totalSubAmount, "totalSubAmount");

		let amountExVat = (totalSubAmount / (100 + vat_charge)) * 100;
		let sumVat = totalSubAmount - amountExVat;
		let ttlAmountWithoutTax = totalSubAmount - sumVat;
		ttlAmountWithoutTax = ttlAmountWithoutTax.toFixed(2);
		// gross total
		$("#gross_amount").val(ttlAmountWithoutTax);
		$("#gross_amount_value").val(ttlAmountWithoutTax);

		// vat amount
		let vat = (Number($("#gross_amount").val()) / 100) * vat_charge;
		vat = vat.toFixed(2);
		$("#vat_charge").val(vat);
		$("#vat_charge_value").val(vat);

		// shipping
		let shipping = Number($("#shipping").val());
		//shipping = shipping.toFixed(2);
		$("#shipping_amount").val(shipping);
		$("#shipping_amount_value").val(shipping);

		// Final Amount
		let finalAmt = parseFloat(totalSubAmount) + parseFloat(shipping);

		finalAmt.toFixed(2);
		$("#final_amount").val(finalAmt);
		$("#final_amount_value").val(finalAmt);
	};
	const clearText = () => {
		$("#product_name").val("");
		$("#all_products").get(0).selectedIndex = 0;
		$("#product_variants").get(0).selectedIndex = 0;
		$("#quantity").val("");
	};
	// get product variants
	$("#all_products").on("change", function () {
		let product_id = $("#all_products option:selected").val();
		//console.log(product_id);
		$.ajax({
			type: "post",
			url: ADMIN_URL + "sales/getVariants",
			data: {
				product_id: product_id,
			},
			success: function (data) {
				//	console.log(data, "data");
				$("#product_variants").html(data);
			},
		});
	});

	$("#product_variants").on("change", function () {
		let val = 1;
		$("#quantity").val(val);
	});

	//const addOrderStat = () => {};
});

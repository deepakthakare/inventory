/* #@Author: Deepak Thakare */ 
var i = 0;
var currentRow = null;

const totalSPAndTotalQTY = () => {
	let ttl_qty = 0;
	let ttl_cp = 0.00
	let ttl_sp = 0.00

	$(".prdqty").each(function () {
		let qty = parseFloat($(this).val());
		ttl_qty += isNaN(qty) ? 0 : qty;
	});
	$(".costprice").each(function () {
		let costprice = parseFloat($(this).val());
		ttl_cp += isNaN(costprice) ? 0.00 : costprice;
	});
	$(".sprice").each(function () {
		let sp = parseFloat($(this).val());
		ttl_sp += isNaN(sp) ? 0.00 : sp;
	});
	
	let profit = ttl_sp - ttl_cp;
	let profitMargin = (profit / ttl_cp) * 100;
	profitMargin = profitMargin.toFixed(2);
	//profitMargin += isNaN(profitMargin) ? 0 : profitMargin;

	$("#ttl_sp").val(ttl_sp);
	$("#ttl_qty").val(ttl_qty);
	$("#ttl_cp").val(ttl_cp);
	$("#profit_margin").val(profitMargin);
};

totalSPAndTotalQTY();

const btnAddNewProduct = () => {
	let exchange_rate = $("#exchange_rate").val();
	exchange_rate = exchange_rate.slice(2);

	let single_mix = $("#single_mix option:selected").val();
	let product_category = $("#product_category option:selected").val();
	let supplier_ref = $("#supplier_ref").val();
	let internal_ref = $("#internal_ref").val();
	let product_qty = $("#product_qty").val();
	let price_euro = $("#price_euro").val();
	let recallBarcode = $("#is_barcode_recall").val();

	//calculation
	let cp = price_euro / exchange_rate;
	cp = cp.toFixed(2);
	$("#cost_price").val(cp);
	let cost_price = $("#cost_price").val();
	// cost_price = cost_price.toFixed(2)
	let selling_price = $("#selling_price").val();
	let bin_number = $("#bin_number").val();
	let hs_code = $("#hs_code").val();
	let product_barcode = $("#product_barcode").val();

	/* let productObject = {
		single_mix,
		product_category,
		supplier_ref,
		internal_ref,
		product_qty,
		price_euro,
		cost_price,
		selling_price,
		bin_number,
		hs_code,
		product_barcode,
		exchange_rate,
	};

	console.log(productObject, "productObject"); */

	if (supplier_ref == "" || supplier_ref == null) {
		$("#supplier_ref").focus();
		if ($("#supplier_ref").parent("div").find(".text-danger").length == 0) {
			/*  */
			$("#supplier_ref").after(
				' <span class="text-danger">Enter Supplier Reference</span>'
			);
			$(".supplier_ref").addClass("validation");
		}
		return false;
	} else {
		$("#supplier_ref").parent("div").find(".text-danger").remove();
	}

	if (product_qty == "" || product_qty == null) {
		$("#product_qty").focus();
		if ($("#product_qty").parent("div").find(".text-danger").length == 0) {
			$("#product_qty").after(
				'<span class="text-danger">Enter Product QTY</span>'
			);
			$(".product_qty").addClass("validation");
		}
		return false;
	} else {
		$("#product_qty").parent("div").find(".text-danger").remove();
	}

	if (price_euro == "" || price_euro == null) {
		$("#price_euro").focus();
		if ($("#price_euro").parent("div").find(".text-danger").length == 0) {
			$("#price_euro").after(
				'<span class="text-danger">Enter Price Euro</span>'
			);
			// $(".price_euro").addClass("validation");
		}
		return false;
	} else {
		$("#price_euro").parent("div").find(".text-danger").remove();
	}

	if (cost_price == "" || cost_price == null) {
		$("#cost_price").focus();
		if ($("#cost_price").parent("div").find(".text-danger").length == 0) {
			$("#cost_price").after(
				'<span class="text-danger">CP is not be blank</span>'
			);
			// $(".cost_price").addClass("validation");
		}
		return false;
	} else {
		$("#cost_price").parent("div").find(".text-danger").remove();
	}

	if (product_barcode == "" || product_barcode == null) {
		$("#product_barcode").focus();
		if ($("#product_barcode").parent("div").find(".text-danger").length == 0) {
			$("#product_barcode").after(
				'<span class="text-danger">Barcode is not generated</span>'
			);
			$(".product_barcode").addClass("validation");
		}
		return false;
	} else {
		$("#product_barcode").parent("div").find(".text-danger").remove();
	}

	let new_row =
		"<tr class='recall'>" +
		'<td><input type="text" class="form-control"  readonly name="single_mix[]" value="' +
		single_mix +
		'" >' +
		'<input type="hidden" class="form-control"  readonly name="product_category[]" value="' +
		product_category +
		'" >' +
		'<input type="hidden" class="form-control"  readonly name="is_barcode_recall[]" value="' +
		recallBarcode +
		'" >' +
		'<input type="hidden" class="form-control"  readonly name="internal_ref[]" value="' +
		internal_ref +
		'" >' +
		"</td>" +
		'<td><input type="text" class="form-control sup_ref"  readonly name="supplier_ref[]" value="' +
		supplier_ref +
		'" ></td>' +
		'<td><input type="text" class="form-control" name="product_qty[]" value="' +
		product_qty +
		'" ></td>' +
		'<td><input type="text" class="form-control" readonly name="price_euro[]" value="' +
		price_euro +
		'"></td>' +
		'<td><input type="text" class="form-control" readonly name="cost_price[]" value="' +
		cost_price +
		'"></td>' +
		'<td><input type="text" class="form-control" readonly name="selling_price[]" value="' +
		selling_price +
		'"></td>' +
		'<td><input type="text" class="form-control" readonly name="bin_number[]" value="' +
		bin_number +
		'"></td>' +
		'<td><input type="text" class="form-control" readonly name="hs_code[]" value="' +
		hs_code +
		'"></td>' +
		'<td><input type="text" class="form-control" readonly name="product_barcode[]" value="' +
		product_barcode +
		'"></td>' +
		'<td><a href="#!" class="btn-danger btn-circle btn-sm text-white btn_remove_row" onclick="removeROW(this);" ><i class="fas fa-trash-alt"></i></a></td>' +
		"</tr>";
	$("#tbl_attributes tbody").append(new_row);
	if (recallBarcode == 1) {
		$(".recall").addClass("bgColor");
	}
	totalSPAndTotalQTY();
	clearText();
};



const btnAddProduct = () => {
	clearText();
	$("#supplier_ref").attr("readonly", false);
	$("#disable").removeClass("disable_product");
};

const clearText = () => {
	$("#single_mix").val('').trigger("click");
	$("#product_category").val(0);
	$("#supplier_ref").val("");
	$("#internal_ref").val("");
	$("#product_qty").val("");
	$("#price_euro").val("");
	$("#cost_price").val("");
	$("#selling_price").val("");
	$("#bin_number").val("");
	$("#hs_code").val("");
	$("#product_barcode").val("");
	$("#colors_value").val('').trigger("click");
	$("#sizes_value").val('').trigger("click");
	$("#variant_quantity").val("");
	$("#variant_barcode").val("");
};

$(function () {
	$("#price_euro, #selling_price")
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

const totalCpPound = () => {
	let exchange_rate = $("#exchange_rate").val();
	exchange_rate = exchange_rate.slice(2);
	let price_euro = $("#price_euro").val();
	let customer_markup = $("#customer_markup").val();

	// CP calculation
	if (exchange_rate !== "") {
		var costprice = (price_euro / exchange_rate).toFixed(2);
	} else {
		alert("Exchange Rate is not added");
		costprice = 0.0;
	}
	$("#cost_price").val(costprice);

	// SP calculation
	let sellingPrice = ((costprice * customer_markup) / 100).toFixed(2);
	sellingPrice = Number(costprice) + Number(sellingPrice);
	sellingPrice = toNearest(sellingPrice, 0.25);
	$("#selling_price").val(sellingPrice);
};

const toNearest = (num, frac) => {
	return (Math.ceil(num / frac) * frac).toFixed(2);
};

$(".showSingle").click(function () {
	$(".targetDiv").hide(".cnt");
	$(".targetHeading").hide(".cnt");
	$("#div" + $(this).attr("target")).slideToggle();
	$("#myModalLabel" + $(this).attr("target")).slideToggle();
});

$("#supplier_ref").on("change", function () {
	let product_supplier = $("#product_supplier option:selected").val();
	let supplier_ref = $("#supplier_ref").val();

	$.ajax({
		type: "POST",
		data: { product_supplier, supplier_ref },
		url: ADMIN_URL + "delnote/checkBarcodeExist",
		success: function (data) {
			let res = JSON.parse(data);
			if (res.length !== 0) {
				$("#price_euro").val(res[0]["price_euro"]);
				$("#product_barcode").val(res[0]["barcode"]);
				$("#internal_ref").val(supplier_ref);
				$("#is_barcode_recall").val(1);
				console.log("recallBarcode");
			} else {
				//	console.log('array empty');
				$("#internal_ref").val(supplier_ref);
				barcodeGeneratorAndCheckExist();
				$("#is_barcode_recall").val(0);
			}
		},
	});
});

const barcodeGeneratorAndCheckExist = () => {
	let brd = barcodeGenerator();
	$.ajax({
		type: "POST",
		data: { brd },
		url: ADMIN_URL + "delnote/checkAndGenerateUniqueBarcode",
		success: function (data) {
			console.log(data, "Check Barcode Exist");
			if (data === "YES") {
				barcodeGeneratorAndCheckExist();
			} else {
				$("#product_barcode").val(brd);
				$("#is_barcode_recall").val(0);

				// add on variant barcode
				$("#variant_barcode").val(brd);
				
			}
		},
	});
};

const editDenoteProduct = (id) => {
	currentRow = $(this).parents("tr");
	$("#currentRow").val(id);
	$.ajax({
		type: "POST",
		data: { id },
		url: ADMIN_URL + "delnote/updateDelProduct",
		success: function (data) {
			let res = JSON.parse(data);
			console.log(res);
			if (res !== "null" || res !== null) {
				$("#supplier_ref").attr("readonly", true);
				$("#disable").addClass("disable_product");
				
				// push/Display values
				$("#supplier_ref").val(res[0].supplier_ref);
				$("#single_mix").val(res[0].single_mix);
				$("#internal_ref").val(res[0].internal_ref);
				$("#product_qty").val(res[0].product_qty);
				$("#price_euro").val(res[0].price_euro);
				$("#cost_price").val(res[0].cost_price);
				$("#selling_price").val(res[0].selling_price);
				$("#bin_number").val(res[0].bin_number);
				$("#hs_code").val(res[0].hs_code);
				$("#product_barcode").val(res[0].product_barcode);
			} else {
				console.log("No data found in the database");
			}
		},
	});
};

//remove an added table row

const removeROW = (row, rowqty, rowsp, rowcp) => {
	let ttlsp = $("#ttl_sp").val();
	let ttlqty = $("#ttl_qty").val();
	let ttlcp = $("#ttl_cp").val();

	ttlqty = Number(ttlqty) - Number(rowqty);
	ttlcp = (ttlcp - rowcp).toFixed(2);
	ttlsp = (ttlsp - rowsp).toFixed(2);
	let profit = ttlsp - ttlcp;
	let profitMargin = (profit / ttlcp) * 100;
	profitMargin = profitMargin.toFixed(2);
	$("#ttl_qty").val(ttlqty);
	$("#ttl_sp").val(ttlsp);
	$("#ttl_cp").val(ttlcp);
	$("#profit_margin").val(profitMargin);

	setTimeout(function () {
		row.closest("tr").remove();
	}, 2000);
};

const barcodeGenerator = () => {
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

const btnUpdateProduct = () => {
	let id = $("#currentRow").val();
	let single_mix = $("#single_mix option:selected").val();
	let product_category = $("#product_category option:selected").val();
	//let supplier_ref = $("#supplier_ref").val();
	let internal_ref = $("#internal_ref").val();
	let product_qty = $("#product_qty").val();
	let price_euro = $("#price_euro").val();
	let cost_price = $("#cost_price").val();
	let selling_price = $("#selling_price").val();
	let bin_number = $("#bin_number").val();
	let hs_code = $("#hs_code").val();
	$("#single_mix-" + id + "").val(single_mix);
	$("#product_category-" + id + "").val(product_category);
	$("#internal_ref-" + id + "").val(internal_ref);
	$("#product_qty-" + id + "").val(product_qty);
	$("#price_euro-" + id + "").val(price_euro);
	$("#cost_price-" + id + "").val(cost_price);
	$("#selling_price-" + id + "").val(selling_price);
	$("#bin_number-" + id + "").val(bin_number);
	$("#hs_code-" + id + "").val(hs_code);
	totalSPAndTotalQTY();
	clearText();
};

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

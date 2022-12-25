$("#product_supplier").on("change", function () {
	let id_supplier = $("#product_supplier").val();
	//$("#country_details").html(id_supplier);
	var url = ADMIN_URL + "suppliers/get_by_id";
	var param = { id_supplier: id_supplier };
	trigger_ajax(url, param)
		.done(function (res) {
			var res = JSON.parse(res);
			if (res["type"] === "success") {
				$.each(res["data"], function (key, val) {
					let html = "";
					html +=
						'<span class="supp"><b>Country: </b>' +
						val.country +
						'</span>\
        <span>\
            <a href="#" class="btn btn-primary btn-sm btn-icon-split supplier_details"  id="supplier_details" data-id_supplier =' +
						val.id_supplier +
						' = >\
                <span class="icon text-white-50"><i class="fa-solid fa-eye"></i></span>\
            </a>\
        </span>';
					let rate = val.symbol + " " + val.exchange_rate;
					let markup = val.customer_markup;
					let cust_markup = Number(markup).toFixed(2);
					let intialValue = 0
					$("#country_details").html(html);
					$("#customer_markup").val(cust_markup);
					$("#exchange_rate").val(rate);
					$("#ttl_sp").val(intialValue);
					$("#ttl_qty").val(intialValue);
					$("#profit_margin").val(intialValue);
					$("#ttl_cp").val(intialValue);
				});
			}
		})
		.fail(function () {
			console.log("falied");
		});
});

// view Supplier
$("#country_details").on("click", ".supplier_details", function () {
	//console.log("clicked");
	// $("#myTable").on("click", ".checkSupplier", function () {
	var id_supplier = $(this).data("id_supplier");
	console.log(id_supplier);
	var url = ADMIN_URL + "suppliers/get_by_id";
	var param = { id_supplier: id_supplier };
	trigger_ajax(url, param)
		.done(function (res) {
			var res = JSON.parse(res);
			console.log(res["data"]);
			var html = "";
			if (res["type"] === "success") {
				$.each(res["data"], function (key, val) {
					html +=
						'<div class="row supp_center">\
            <div class="col-sm-8">\
            <div class="row">\
              <div class="col-sm-12">\
                <div class="supplierdetails"> <b>Supplier Name: </b>' +
						val.name +
						'</div>\
              </div>\
             <div class="col-sm-12">\
                <div class="supplierdetails"><b> Company: </b>' +
						val.company +
						'</div>\
              </div>\
             <div class="col-sm-12">\
                <div class="supplierdetails"><b> Supplier Code: </b>' +
						val.supplier_code +
						'</div>\
              </div>\
              <div class="col-sm-12">\
                <div class="supplierdetails"><b> Email: </b>' +
						val.email +
						'</div>\
              </div>\
              <div class="col-sm-12">\
                <div class="supplierdetails"><b>Description: </b>' +
						val.description +
						'</div>\
              </div>\
              <div class="col-sm-12">\
                <div class="supplierdetails"><b>Phone: </b>' +
						val.phone +
						'</div>\
              </div>\
              <div class="col-sm-12">\
                <div class="supplierdetails"><b>Mobile: </b>' +
						val.mobile +
						'</div>\
              </div>\
              <div class="col-sm-12">\
                <div class="supplierdetails"><b>Fax: </b>' +
						val.fax +
						'</div>\
              </div>\
              <div class="col-sm-12">\
                <div class="supplierdetails"><b>Address: </b>' +
						val.address +
						'</div>\
              </div>\
              <div class="col-sm-12">\
                <div class="supplierdetails"><b>address 2: </b>' +
						val.address_two +
						'</div>\
              </div>\
              <div class="col-sm-12">\
                <div class="supplierdetails"><b>Zipcode: </b>' +
						val.zipcode +
						'</div>\
              </div>\
              <div class="col-sm-12">\
                <div class="supplierdetails"><b>City: </b>' +
						val.city +
						'</div>\
              </div>\
              <div class="col-sm-12">\
                <div class="supplierdetails"><b>Country: </b>' +
						val.country +
						'</div>\
              </div>\
              <div class="col-sm-12">\
                <div class="supplierdetails"><b>Currency: </b>' +
						val.currency +
						'</div>\
              </div>\
              <div class="col-sm-12">\
                <div class="supplierdetails"><b>Customer Markup: </b>' +
						val.customer_markup +
						'</div>\
              </div>\
              <div class="col-sm-12">\
                <div class="supplierdetails"><b>Markup PN: </b>' +
						val.markup_pn +
						"</div>\
              </div>\
            </div>\
          </div>\
        </div>";
					if (res["data"].length > 1 && key < res["data"].length - 1) {
						html += "<hr/>";
					}
				});
				$("#supplier_modal_body").html(html);
				$("#SupplierModal").modal("show");
			}
		})
		.fail(function () {
			console.log("falied");
		});
});

/**
 * @Author: Deepak
 */

$(document).ready(function () {
	var myTable = $("#myTable").dataTable({
		bStateSave: true,
		processing: true,
		bPaginate: true,
		serverSide: true,
		bProcessing: true,
		iDisplayLength: 10,
		bServerSide: true,
		sAjaxSource: ADMIN_URL + "suppliers/get_supplier",
		bPaginate: true,
		fnServerParams: function (aoData) {
			var acolumns = this.fnSettings().aoColumns,
				columns = [];
			$.each(acolumns, function (i, item) {
				columns.push(item.data);
			});
			aoData.push({ name: "columns", value: columns });
		},
		columns: [
			{ data: "id_supplier" },
			{ data: "name" },
			{ data: "supplier_code" },
			{ data: "email" },
			// { data: "address" },
			// { data: "description" },
			{ data: "mobile" },
			// { data: "city" },
			{ data: "country" },
			{ data: "customer_markup" },
		],
		order: [[0, "desc"]],
		lengthMenu: [
			[10, 25, 50, 100],

			[10, 25, 50, 100],
		],
		oLanguage: {
			sLengthMenu: "_MENU_",
		},
		fnInitComplete: function () {
			//oTable.fnAdjustColumnSizing();
		},
		fnServerData: function (sSource, aoData, fnCallback) {
			$.ajax({
				dataType: "json",
				type: "POST",
				url: sSource,
				data: aoData,
				success: fnCallback,
			});
		},
		fnDrawCallback: function () {
			$("body").css("min-height", $("#table1 tr").length * 50 + 200);
			$(window).trigger("resize");
		},
		columnDefs: [
			{
				render: function (data, type, row) {
					return (
						'<a class="btn btn-outline-primary btn-sm checkSupplier" data-id_supplier="' +
						row.id_supplier +
						'" href="#!" title="View Order"> <i class="fa-solid fa-eye"></i> </a>'
					);
				},
				targets: $("#myTable th#supplier_view").index(),
				orderable: true,
				bSortable: true,
			},
			{
				render: function (data, type, row) {
					return (
						'<a class="btn-primary btn-circle btn-sm" href="' +
						ADMIN_URL +
						"suppliers/edit/" +
						row.id_supplier +
						'" ><i class="fas fa-pencil-alt"></i></a> <a class="btn-danger btn-circle btn-sm text-white"  data-id_supplier=' +
						row.id_supplier +
						' id="btnDelete"><i class="fas fa-trash-alt"></i></a>'
					);
				},
				targets: $("#myTable th#action").index(),
				orderable: true,
				bSortable: true,
			},
		],
	});
	$(".dataTables_filter input").attr("placeholder", "Search...");

	$("#myTable").on("click", "#btnDelete", function () {
		var id_supplier = $(this).data("id_supplier");
		if (confirm("Are you sure?")) {
			var url = ADMIN_URL + "suppliers/delete";
			var param = { id_supplier: id_supplier };
			trigger_ajax(url, param)
				.done(function (res) {
					var res = JSON.parse(res);
					if (res["type"] === "success") {
						var myTable = $("#myTable").DataTable();
						// If you want totally refresh the datatable use this
						// myTable.ajax.reload();
						// If you want to refresh but keep the paging you can you this
						myTable.ajax.reload(null, false);
					}
				})
				.fail(function () {
					console.log("falied");
				});
		}
	});

	// view Supplier
	$("#myTable").on("click", ".checkSupplier", function () {
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
                    <div class="product_name"> <b>Supplier Name: </b>' +
							val.name +
							'</div>\
                  </div>\
                 <div class="col-sm-12">\
                    <div class="product_attribute"><b> Company: </b>' +
							val.company +
							'</div>\
                  </div>\
                 <div class="col-sm-12">\
                    <div class="product_attribute"><b> Supplier Code: </b>' +
							val.supplier_code +
							'</div>\
                  </div>\
                  <div class="col-sm-12">\
                    <div class="product_price"><b> Email: </b>' +
							val.email +
							'</div>\
                  </div>\
                  <div class="col-sm-12">\
                    <div class="product_selling_price"><b>Description: </b>' +
							val.description +
							'</div>\
                  </div>\
                  <div class="col-sm-12">\
                    <div class="product_selling_price"><b>Phone: </b>' +
							val.phone +
							'</div>\
                  </div>\
                  <div class="col-sm-12">\
                    <div class="product_selling_price"><b>Mobile: </b>' +
							val.mobile +
							'</div>\
                  </div>\
                  <div class="col-sm-12">\
                    <div class="product_selling_price"><b>Fax: </b>' +
							val.fax +
							'</div>\
                  </div>\
                  <div class="col-sm-12">\
                    <div class="product_selling_price"><b>Address: </b>' +
							val.address +
							'</div>\
                  </div>\
                  <div class="col-sm-12">\
                    <div class="product_selling_price"><b>address 2: </b>' +
							val.address_two +
							'</div>\
                  </div>\
                  <div class="col-sm-12">\
                    <div class="product_selling_price"><b>Zipcode: </b>' +
							val.zipcode +
							'</div>\
                  </div>\
                  <div class="col-sm-12">\
                    <div class="product_selling_price"><b>City: </b>' +
							val.city +
							'</div>\
                  </div>\
                  <div class="col-sm-12">\
                    <div class="product_selling_price"><b>Country: </b>' +
							val.country +
							'</div>\
                  </div>\
                  <div class="col-sm-12">\
                    <div class="product_selling_price"><b>Currency: </b>' +
							val.currency +
							'</div>\
                  </div>\
                  <div class="col-sm-12">\
                    <div class="product_selling_price"><b>Customer Markup: </b>' +
							val.customer_markup +
							'</div>\
                  </div>\
                  <div class="col-sm-12">\
                    <div class="product_inventory"><b>Markup PN: </b>' +
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
});

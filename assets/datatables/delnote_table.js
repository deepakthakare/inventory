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
		sAjaxSource: ADMIN_URL + "delnote/get_delnotes",
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
			{ data: "id_delnote" },
			{ data: "created_at" },
			{ data: "landed_date" },
			{ data: "supplier_name" },
			{ data: "country_name" },
			{ data: "cust_delno" },
			{ data: "ttl_qty" },
			// { data: "id_delnote" }
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
			/* {
				render: function (data, type, row) {
					return (
						'<a class="btn btn-outline-primary btn-sm checkInventory" data-id_delnote="' +
						row.id_delnote +
						'" href="#!" title="View Delnote"> <i class="fa-solid fa-eye"></i> </a>'
					);
				},
				targets: $("#myTable th#inventory").index(),
				orderable: true,
				bSortable: true,
			}, */
			{
				render: function (data, type, row) {
					return (
						'<a class="btn btn-outline-primary btn-sm checkOrderDetails" data-id_delnote="' +
						row.id_delnote +
						'" href="#!" title="View Delnote"><i class="fa-solid fa-eye"></i> </a>  <a class="btn btn-outline-success btn-sm" data-toggle="tooltip" title="Edit Delnote" href="' +
                        ADMIN_URL +
                        "delnote/edit/" +
                        row.id_delnote +
                        '" ><i class="fas fa-pencil-alt"></i></a><a href="#!" class="btn btn-outline-danger btn-sm mg-left" style="margin-left: 3px;"  data-toggle="tooltip" data-id_delnote=' +
						row.id_delnote +
						' id="btnDelete" title="Delete"> <i class="fas fa-trash-alt"></i></a>'
					);
				},
				targets: $("#myTable th#action").index(),
				orderable: true,
				bSortable: true,
			},
		],
	});

	$(".dataTables_filter input").attr("placeholder", "Search...");

	// Zoom Image
	$("#myTable").on("click", "#btnImgpop", function (e) {
		let imgPath = $(this).data("prod_id");
		html =
			'<img width="231" height="347" src="' +
			imgPath +
			'" id="product_image"/>';
		$("#image_modal_body").html(html);
		$("#ImageModal").modal("show");
	});

	// delete product
	$("#myTable").on("click", "#btnDelete", function () {
		var id_delnote = $(this).data("id_delnote");
		if (confirm("Are you sure?")) {
			var url = ADMIN_URL + "delnote/delete";
			var param = { id_delnote: id_delnote };
			trigger_ajax(url, param)
				.done(function (res) {
					var res = JSON.parse(res);
					console.log(res, "res");
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

	// view product
	$("#myTable").on("click", ".checkInventory", function () {
		var prod_id = $(this).data("prod_id");
		var url = ADMIN_URL + "products/get_product_inventory";
		var param = { prod_id: prod_id };
		trigger_ajax(url, param)
			.done(function (res) {
				var res = JSON.parse(res);
				console.log(res["data"]);
				var html = "";
				if (res["type"] === "success") {
					$.each(res["data"], function (key, val) {
						var sell_price =
							parseFloat(val.price) +
							parseFloat(val.price) * (parseInt(val.tax_rate) / 100);
						var imag = "";
						if (val.image_path == "" || val.image_path == null) {
							imag = BASE_URL + "assets/img/not-found.png";
						} else {
							imag = val.image_path;
						}
						html +=
							'<div class="row">\
              <div class="col-sm-4">\
                <img width="100" height="100" src="' +
							imag +
							'" id="product_image"/>\
              </div>\
              <div class="col-sm-8">\
                <div class="row">\
                  <div class="col-sm-12">\
                    <div class="product_name"> <b>Product Name: </b>' +
							val.product_name +
							'</div>\
                  </div>\
                 <div class="col-sm-12">\
                    <div class="product_attribute"><b> Color: </b>' +
							val.color +
							'</div>\
                  </div>\
                 <div class="col-sm-12">\
                    <div class="product_attribute"><b> Size: </b>' +
							val.size +
							'</div>\
                  </div>\
                  <div class="col-sm-12">\
                    <div class="product_price"><b>Price: </b>£' +
							val.p_price +
							'</div>\
                  </div>\
                  <div class="col-sm-12">\
                    <div class="product_selling_price"><b>Barcode: </b>' +
							val.barcode +
							'</div>\
                  </div>\
                  <div class="col-sm-12">\
                    <div class="product_inventory"><b>Quantity: </b>' +
							val.inventory +
							"</div>\
                  </div>\
                </div>\
              </div>\
            </div>";
						if (res["data"].length > 1 && key < res["data"].length - 1) {
							html += "<hr/>";
						}
					});
					$("#inventory_modal_body").html(html);
					$("#InventoryModal").modal("show");
				}
			})
			.fail(function () {
				console.log("falied");
			});
	});
});

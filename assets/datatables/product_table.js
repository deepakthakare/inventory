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
		sAjaxSource: ADMIN_URL + "products/get_products",
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
			{ data: "prod_id" },
			{ data: "image_path" },
			{ data: "name" },
			{ data: "prd_barcode" },
			{ data: "inventory" },
			{ data: "prod_id" },
			{ data: "shopi_status" },
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
					if (row.image_path == "" || row.image_path == null) {
						return (
							'<img src="' +
							BASE_URL +
							'assets/img/not-found.png" width="30" height="30"/>'
						);
					} else {
						return (
							'<img src="' +
							row.image_path +
							'" data-prod_id="' +
							row.image_path +
							'"width="40" height="40" id="btnImgpop"/>'
						);
					}
				},
				targets: $("#myTable th#image").index(),
				orderable: true,
				bSortable: true,
			},
			{
				render: function (data, type, row) {
					return (
						'<a class="btn btn-outline-primary btn-sm checkInventory" data-prod_id="' +
						row.prod_id +
						'" href="#!" > View Details <i class="far fa-hand-pointer"></i> </a>'
					);
				},
				targets: $("#myTable th#inventory").index(),
				orderable: true,
				bSortable: true,
			},
			{
				render: function (data, type, row) {
					let status =
						row.shopi_status === "Pushed"
							? '<button type="button" class="btn btn-success btn-sm" title="Pushed to Shopify Website.">' +
							  row.shopi_status +
							  " </button>"
							: '<button type="button" class="btn btn-warning btn-sm" title="Not Pushed to Shopify Website.">' +
							  row.shopi_status +
							  "</button>";
					return status;
				}, ///
				targets: $("#myTable th#status").index(),
				orderable: true,
				bSortable: true,
			},

			{
				render: function (data, type, row) {
					let prdID = row.prod_id;
					return (
						'<a class="btn-primary btn-circle btn-sm" data-toggle="tooltip" title="Edit" href="' +
						ADMIN_URL +
						"products/edit/" +
						row.prod_id +
						'" ><i class="fas fa-pencil-alt"></i></a> <a href="#" class="btn-danger btn-circle btn-sm text-white" data-toggle="tooltip" title="Delete"  data-prod_id=' +
						row.prod_id +
						' id="btnDelete"><i class="fas fa-trash-alt"></i></a> <a href="#" class="btn-success btn-circle btn-sm text-white" data-toggle="tooltip" title="Push to Shopify" data-prod_id=' +
						row.prod_id +
						' id="btnPush" onclick="pushToShopify(' +
						prdID +
						');"><i class="fa-solid fa-cloud-arrow-up"></i></a>'
					);
				},
				targets: $("#myTable th#action").index(),
				orderable: true,
				bSortable: true,
			},
		],
	});
	$(".dataTables_filter input").attr("placeholder", "Search...");

	$("#myTable").on("click", "#btnImgpop", function (e) {
		let imgPath = $(this).data("prod_id");
		html =
			'<img width="231" height="347" src="' +
			imgPath +
			'" id="product_image"/>';
		$("#image_modal_body").html(html);
		$("#ImageModal").modal("show");
	});

	$("#myTable").on("click", "#btnDelete", function () {
		var prod_id = $(this).data("prod_id");
		if (confirm("Are you sure?")) {
			var url = ADMIN_URL + "products/delete";
			var param = { prod_id: prod_id };
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
                    <div class="product_attribute"><b>' +
							val.attributes_name +
							" : </b>" +
							val.attributes_value +
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

	$("#imgpop").on(
		"click",
		function (e) {
			console.log("ddd");
			var href = $(this).attr("href");
			var popup = $('<div id="myPopup"></div>');

			var image = $('<img id="image" src="' + href + '"/>');
			popup.append(image);

			var text = $("<p>My Text</p>");
			popup.append(text);

			var closeBtn = $("<button>X</button>");
			popup.append(closeBtn);

			popup.css("top", e.pageY + offsetY).css("left", e.pageX + offsetX);
			popup.append("body");

			closeBtn.button().click(function () {
				$("#myPopup").remove();
			});
		},
		function (e) {
			// do nothing
		}
	);
});

const pushToShopify = (prod_id) => {
	if (confirm("Are you sure, To transter the product to shopify? ")) {
		var url = ADMIN_URL + "products/push";
		var param = { prod_id: prod_id };
		trigger_ajax(url, param)
			.done(function (res) {
				var res = JSON.parse(res);
				console.log(res, "res");
				if (res.product.id !== "") {
					alert("Product Successfully Pushed");
					var myTable = $("#myTable").DataTable();
					myTable.ajax.reload(null, false);
				}
			})
			.fail(function () {
				console.log("falied");
			});
	}
};

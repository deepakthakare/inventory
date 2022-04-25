/**
 * @Author: sahebul
 * @Date:   2019-06-11T11:44:01+05:30
 * @Last modified by:   sahebul
 * @Last modified time: 2019-06-11T11:44:10+05:30
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
		sAjaxSource: ADMIN_URL + "sales/get_sales",
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
			{ data: "order_id" },
			{ data: "customer_name" },
			{ data: "qty" },
			{
				data: "total",
			},
			{ data: "created_date" },
			{ data: "order_id" },
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
						'<a class="btn btn-outline-primary btn-sm checkOrderDetails" data-order_id="' +
						row.order_id +
						'" href="#!" > View Details <i class="fa-solid fa-eye"></i> </a>'
					);
				},
				targets: $("#myTable th#orderdetails").index(),
				orderable: true,
				bSortable: true,
			},
			{
				render: function (data, type, row) {
					return (
						' <a class="btn-danger btn-circle btn-sm text-white"  data-order_id=' +
						row.order_id +
						' id="btnDelete"><i class="fas fa-trash-alt"></i></a> <a href="#" class="btn-success btn-circle btn-sm text-white" data-toggle="tooltip" title="Order Push to Shopify" data-prod_id=' +
						row.order_id +
						' id="btnOrderPush" onclick="orderPushToShopify(' +
						row.order_id +
						');"><i class="fa-solid fa-cloud-arrow-up"></i></a> '
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
		var order_id = $(this).data("order_id");
		if (confirm("Are you sure?")) {
			var url = ADMIN_URL + "sales/delete";
			var param = { order_id: order_id };
			trigger_ajax(url, param)
				.done(function (res) {
					let resDelPrd = JSON.parse(res);
					if (resDelPrd["type"] === "success") {
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

	// view Order Details
	$("#myTable").on("click", ".checkOrderDetails", function () {
		let order_id = $(this).data("order_id");
		console.log(order_id);
		let url = ADMIN_URL + "sales/getOrderDetails";
		let param = { order_id: order_id };
		trigger_ajax(url, param)
			.done(function (res) {
				let resp = JSON.parse(res);
				console.log(resp["data"]);
				var html = "";
				if (resp["type"] === "success") {
					$.each(resp["data"], function (key, val) {
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
							val.name +
							'</div>\
							</div>\
							<div class="col-sm-12">\
								<div class="product_inventory"><b>Quantity: </b>' +
							val.qty +
							"</div>\
							</div>\
							</div>\
						</div>\
            		</div>";
						if (resp["data"].length > 1 && key < resp["data"].length - 1) {
							html += "<hr/>";
						}
					});
					$("#order_modal_body").html(html);
					$("#orderDetailsModal").modal("show");
				}
			})
			.fail(function () {
				console.log("falied");
			});
	});
});

const orderPushToShopify = (order_id) => {
	if (confirm("Are you sure, To push the order to shopify? ")) {
		var url = ADMIN_URL + "sales/pushOrderToShopify";
		var param = { order_id: order_id };
		trigger_ajax(url, param)
			.done(function (res) {
				let resPrd = JSON.parse(res);
				console.log(resPrd, "res");
				if (resPrd.draft_order.id !== "") {
					alert("Order Successfully Created");
					let myTable = $("#myTable").DataTable();
					myTable.ajax.reload(null, false);
				}
			})
			.fail(function () {
				console.log("falied");
			});
	}
};

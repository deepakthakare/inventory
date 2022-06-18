/**
 * @Author: Deepak
 */
$(document).ready(function () {
	let DOMAIN = "IFIF LifeStyle";
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
			{ data: "draft_order_id" },
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
					let status =
						row.draft_order_id !== "0"
							? '<button type="button" class="btn btn-success btn-sm" title="Draft Order Created.">' +
							  "Created" +
							  " </button>"
							: '<button type="button" class="btn btn-warning btn-sm" title="Draft Order Pending.">' +
							  "Pending" +
							  "</button>";
					return status;
				}, ///
				targets: $("#myTable th#status").index(),
				orderable: true,
				bSortable: true,
			},
			{
				render: function (data, type, row) {
					return (
						'<a class="btn btn-outline-primary btn-sm checkOrderDetails" data-order_id="' +
						row.order_id +
						'" href="#!" title="View Order"><i class="fa-solid fa-eye"></i> </a> <a class="btn-danger btn-circle btn-sm text-white"  data-order_id=' +
						row.order_id +
						' id="btnDelete"><i class="fas fa-trash-alt"></i></a> <a href="#" class="btn-success btn-circle btn-sm text-white" data-toggle="tooltip" title="Order Push to ' +
						DOMAIN +
						'" data-prod_id=' +
						row.order_id +
						' id="btnOrderPush" onclick="orderPushToShopify(' +
						row.order_id +
						"," +
						row.draft_order_id +
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

	// Start view Order Details
	$("#myTable").on("click", ".checkOrderDetails", function () {
		let order_id = $(this).data("order_id");
		console.log(order_id);
		let url = ADMIN_URL + "sales/getOrderDetails";
		let param = { order_id: order_id };
		trigger_ajax(url, param)
			.done(function (res) {
				let resp = JSON.parse(res);
				console.log(resp["data"]);
				if (resp["data"][0]["draft_order_id"] === "0") {
					$("#pause_button").html(
						"<p class='draft_order_not'>Draft Order Not Created</p>"
					);
				} else {
					$("#pause_button").html(
						"<p class='draft_order'>Draft Order Created</p>"
					);
				}
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
								<div class="prd_attribute"> <b>Variants: </b>' +
							val.attributes_value +
							'</div>\
							</div>\
							<div class="col-sm-12">\
								<div> <b>Price: </b>' +
							val.price +
							'</div>\
							</div>\
							<div class="col-sm-12">\
								<div> <b>Stylecode: </b>' +
							val.stylecode +
							'</div>\
							</div>\
							<div class="col-sm-12">\
								<div> <b>Barcode: </b>' +
							val.barcode +
							'</div>\
							</div>\
							<div class="col-sm-12">\
								<div> <b>Location: </b>' +
							val.location +
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
					html += "<hr/>";
					html +=
						'<div class="row" style="float:right">\
					<div class="col-sm-12">\
					<div class="prd_attribute"> <b>Customer Name: </b>' +
						resp["data"][0]["customer_name"] +
						'</div>\
					</div>\
					<div class="col-sm-12">\
					<div class="prd_attribute"> <b>Shipping: </b>' +
						resp["data"][0]["shipping"] +
						"</div>\
					</div>\
					</div>";
					$("#order_modal_body").html(html);
					$("#orderDetailsModal").modal("show");
				}
			})
			.fail(function () {
				console.log("falied");
			});
	});

	//End view order Details
});

const orderPushToShopify = (order_id, variantID) => {
	console.log(variantID, "variantID");
	if (variantID !== 0) {
		swal("Draft Order Already Created!", "", "warning");
	} else {
		swal(
			{
				title: "Are you sure?",
				text: "To create Draft Order",
				type: "warning",
				showCancelButton: true,
				confirmButtonClass: "btn-danger",
				confirmButtonText: "Yes",
				cancelButtonText: "No",
				closeOnConfirm: false,
				closeOnCancel: false,
			},
			function (isConfirm) {
				if (isConfirm) {
					var url = ADMIN_URL + "sales/pushOrderToShopify";
					var param = { order_id: order_id };
					trigger_ajax(url, param)
						.done(function (res) {
							let resPrd = JSON.parse(res);
							console.log(resPrd, "res");
							if (resPrd.draft_order.id !== "") {
								swal(
									"Created!",
									"Draft Order successfully created!.",
									"success"
								);
								let myTable = $("#myTable").DataTable();
								myTable.ajax.reload(null, false);
							}
						})
						.fail(function () {
							console.log("falied");
						});
				} else {
					swal("Cancelled", "", "error");
				}
			}
		);
	}
};

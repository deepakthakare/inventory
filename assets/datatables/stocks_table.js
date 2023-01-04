/**
 * @Author: Deepak
 */
$(document).ready(function () {
	var myTable = $("#myTable").dataTable({
		bStateSave: true,
		processing: true,
		bPaginate: true,
		//serverSide: true,
		bProcessing: true,
		iDisplayLength: 10,
		searching: true,
		//	bServerSide: true,
		sAjaxSource: ADMIN_URL + "stocks/get_products",
		fnServerParams: function (aoData) {
			var acolumns = this.fnSettings().aoColumns,
				columns = [];
			$.each(acolumns, function (i, item) {
				columns.push(item.data);
			});
			aoData.push({ name: "columns", value: columns });
		},
		columns: [
			{
				data: "",
				render: function (data, type, row, meta) {
					return '<input type="checkbox">';
				},
			},
			{
				data: "sr.no",
				render: function (data, type, row, meta) {
					return meta.row + meta.settings._iDisplayStart + 1;
				},
			},
			{ data: "image" },
			{ data: "name" },
			{ data: "product_id" },
			{ data: "title" },
			{ data: "barcode" },
			{ data: "price" },
			{ data: "inventory_quantity" },
			// { data: "quantity" },
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
				/* render: function (data, type, row) {
					return (
						'<a class="btn-primary btn-circle btn-sm" href="' +
						ADMIN_URL +
						"orders/edit/" +
						row.brand_id +
						'" ></a>'
					);
				}, */
				targets: $("#myTable th#action").index(),
				orderable: true,
				bSortable: true,
			},
		],
	});

	$(".dataTables_filter input").attr("placeholder", "Search...");

	const filterGlobal = () => {
		$("#myTable").DataTable().search($("#myCustomSearchBox").val()).draw();
	};
	$("#myCustomSearchBox").keyup(function () {
		filterGlobal();
	});
	const resetFilter = () => {
		$("#myTable").DataTable().search('').draw();

	}
	$("#resetTxtbx").on("click", function (e) {
		document.getElementById("myCustomSearchBox").value = "";
		resetFilter()
	});
	// Image Zoom
	$("#myTable").on("click", "#btnImgpop", function (e) {
		let imgPath = $(this).data("image_path");
		html =
			'<img width="231" height="347" src="' +
			imgPath +
			'" id="product_image"/>';
		$("#image_modal_body").html(html);
		$("#ImageModal").modal("show");
	});

	//Stock Update
	$("#myTable").on("change", ".inventory_container", function () {
		var variant_id = $(this).attr("data-variant_id");
		var new_inventory = $(this).val();

		var url = ADMIN_URL + "stocks/edit";
		var param = {
			variant_id: variant_id,
			new_inventory: new_inventory,
		};
		console.log(param, "param");
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
	});
});

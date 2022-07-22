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
		//	bServerSide: true,
		sAjaxSource: ADMIN_URL + "stocks/get_products",
		// sAjaxSource: ADMIN_URL + "brands/get_brands",
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
});

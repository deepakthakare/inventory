/**
 * @Author: Deepak

 */

$(document).ready(function () {
	var customersTable = $("#myTable").dataTable({
		bStateSave: true,
		processing: true,
		bPaginate: true,
		serverSide: true,
		bProcessing: true,
		iDisplayLength: 10,
		bServerSide: true,
		sAjaxSource: ADMIN_URL + "users/get_users",
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
			{ data: "login_id" },
			{ data: "username" },
			{ data: "fname" },
			{ data: "lname" },
			{ data: "store_name" },
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
						'<a class="btn-primary btn-circle btn-sm" href="' +
						ADMIN_URL +
						"users/edit/" +
						row.login_id +
						'" ><i class="fas fa-pencil-alt"></i></a> <a class="btn-danger btn-circle btn-sm text-white"  data-login_id=' +
						row.login_id +
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
		var login_id = $(this).data("login_id");
		if (confirm("Are you sure?")) {
			var url = ADMIN_URL + "users/delete";
			var param = { login_id: login_id };
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
});
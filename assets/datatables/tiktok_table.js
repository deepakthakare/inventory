/**
 * @Author: Deepak
 */
$(document).ready(function () {
	function updateDataTableSelectAllCtrl(table) {
		var $table = table.table().node();
		var $chkbox_all = $('tbody input[type="checkbox"]', $table);
		var $chkbox_checked = $('tbody input[type="checkbox"]:checked', $table);
		var chkbox_select_all = $('thead input[name="select_all"]', $table).get(0);

		// If none of the checkboxes are checked
		if ($chkbox_checked.length === 0) {
			chkbox_select_all.checked = false;
			if ("indeterminate" in chkbox_select_all) {
				chkbox_select_all.indeterminate = false;
			}

			// If all of the checkboxes are checked
		} else if ($chkbox_checked.length === $chkbox_all.length) {
			chkbox_select_all.checked = true;
			if ("indeterminate" in chkbox_select_all) {
				chkbox_select_all.indeterminate = false;
			}

			// If some of the checkboxes are checked
		} else {
			chkbox_select_all.checked = true;
			if ("indeterminate" in chkbox_select_all) {
				chkbox_select_all.indeterminate = true;
			}
		}
	}
	var myTable = $("#myTable").dataTable({
		bStateSave: true,
		processing: true,
		bPaginate: true,
		//serverSide: true,
		bProcessing: true,
		iDisplayLength: 10,
		//	bServerSide: true,
		sAjaxSource: ADMIN_URL + "tiktok/get_tiktok_products",
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

			//	{ data: "prod_price_id" },
			{ data: "prod_price_id" },
			{ data: "image" },
			{ data: "product_name" },
			{ data: "prd_barcode" },
			{ data: "barcode" },
			{ data: "stylecode" },
			{ data: "attributes" },
			{ data: "p_price" },
			{ data: "selling_price" },
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
			// {
			// 	targets: 0,
			// 	searchable: false,
			// 	orderable: false,
			// 	width: "2%",
			// 	className: "text-center",
			// 	render: function (data, type, full, meta) {
			// 		return '<input type="checkbox">';
			// 	},
			// },
			{
				targets: $("#myTable th#action").index(),
				orderable: true,
				bSortable: true,
			},
		],
	});
	$("#myCustomSearchBox").keyup(function () {
		myTable.search($(this).val()).draw();
	});

	$("#resetTxtbx").on("click", function (e) {
		document.getElementById("myCustomSearchBox").value = "";
		myTable.search("").draw();
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
		var prod_price_id = $(this).attr("data-prod_price_id");
		var prod_id = $(this).attr("data-id_product");
		var attributes_value = $(this).attr("data-attributes_value");
		var new_selling_price = $(this).val();
		console.log("object");
		var url = ADMIN_URL + "tiktok/edit";
		var param = {
			prod_price_id: prod_price_id,
			new_selling_price: new_selling_price,
			prod_id: prod_id,
			attributes_value: attributes_value,
		};
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

	// Handle click on checkbox
	$("#myTable tbody").on("click", 'input[type="checkbox"]', function (e) {
		console.log("one");
		var $row = $(this).closest("tr");

		// Get row data
		var data = myTable.row($row).data();

		// Get row ID
		var rowId = data[0];

		// Determine whether row ID is in the list of selected row IDs
		var index = $.inArray(rowId, rows_selected);

		// If checkbox is checked and row ID is not in list of selected row IDs
		if (this.checked && index === -1) {
			// rows_selected.push(rowId);
			// Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
		} else if (!this.checked && index !== -1) {
			rows_selected.splice(index, 1);
		}

		if (this.checked) {
			$row.addClass("selected");
		} else {
			$row.removeClass("selected");
		}

		// Update state of "Select all" control
		updateDataTableSelectAllCtrl(myTable);

		// Prevent click event from propagating to parent
		e.stopPropagation();
	});
	// Handle click on table cells with checkboxes
	$("#myTable").on("click", "tbody td, thead th:first-child", function (e) {
		console.log("two");
		$(this).parent().find('input[type="checkbox"]').trigger("click");
	});

	// Handle click on "Select all" control
	$('thead input[name="select_all"]', myTable.table().container()).on(
		"click",
		function (e) {
			console.log("three");
			if (this.checked) {
				$('#myTable tbody input[type="checkbox"]:not(:checked)').trigger(
					"click"
				);
			} else {
				$('#myTable tbody input[type="checkbox"]:checked').trigger("click");
			}

			// Prevent click event from propagating to parent
			e.stopPropagation();
		}
	);

	// Handle table draw event
	myTable.on("draw", function () {
		console.log("four");
		// Update state of "Select all" control
		updateDataTableSelectAllCtrl(myTable);
	});

	// Handle form submission event
	$("#frm-table").on("submit", function (e) {
		// Prevent actual form submission
		e.preventDefault();
		var form = this;
		// var rows_selected = myTable.column(0).checkboxes.selected();
		// console.log("Form submission", rows_selected.join(","));
		// Iterate over all selected checkboxes
		$.each(rows_selected, function (index, rowId) {
			// Create a hidden element
			$(form).append(
				$("<input>").attr("type", "hidden").attr("name", "id[]").val(rowId)
			);
		});
		// Output form data to a console
		$("#example-console-form").text($(form).serialize());
		$("#example-console-rows").text(rows_selected.join(","));

		// Remove added elements
		$('input[name="id[]"]', form).remove();

		// Prevent actual form submission
		e.preventDefault();
	});

	$(".dataTables_filter input").attr("placeholder", "Search...");
});

<style>
    .table {
        color: #000000 !important;
    }

    td,
    th {
        text-align: center !important;
    }

    #input_container {
        position: relative;
        width: 110px;
    }

    #input {
        margin: 0;
        padding-right: 30px;
        width: 100%;
    }

    #input_img {
        position: absolute;
        bottom: 2px;
        right: 5px;
        width: 24px;
        height: 24px;
    }

    .dataTables_length {
        display: none;
    }

    .top {
        margin-top: 25px
    }
</style>
<div class="mb-4">
    <?= $this->breadcrumbs->show(); ?>
</div>
<div class="row">
    <div class="col-xl-12 col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Tiktok Product Details </h6>
            </div>
            <div class="container top">
                <div class="row">
                    <div class="col-sm-6 col-md-4 col-xs-12" id="searchTxt">
                        <input type="text" id="myCustomSearchBox" class="form-control" placeholder="Search Barcode" autofocus="autofocus">
                    </div>
                    <div class=" col-sm-6 col-md-2 col-xs-12">
                        <button id="resetTxtbx" value="Reset" class="btn btn-primary form-control btn-block" type="button">Reset</button>
                    </div>
                </div>
            </div>

            <!-- Add some JS code -->
            <div class="card-body">
                <div class="table-responsive">
                    <form id="frm-table" action="" method="POST">
                        <div class="mg-top-20">
                            <p>
                                <button class="btn btn-success btn-sm btn-icon-split" style="float: right;margin-bottom: 15px;">
                                    <span class="text">Export</span>
                                    <span class="icon text-white-50"><i class="fas fa-download"></i></span>
                                </button>
                            </p>

                            <table class="table table-hover table-bordered" id="myTable">
                                <thead style="background: #7474e9;color:#fff">
                                    <tr>
                                        <th><input name="select_all" value="1" type="checkbox"></th>
                                        <th>Id</th>
                                        <th id="image">Image</th>
                                        <th>Name</th>
                                        <th>Parent Barcode</th>
                                        <th>Child Barcode</th>
                                        <th>Stylecode</th>
                                        <th>Attributes</th>
                                        <th>Base Price</th>
                                        <th width="80px" id="inventory">Selling Price</th>
                                    </tr>
                                </thead>
                            </table>
                    </form>



                </div>
            </div>

        </div>
    </div>
</div>
</div>

<script type="text/javascript">
    function updateDataTableSelectAllCtrl(table) {
        var $table = table.table().node();
        var $chkbox_all = $('tbody input[type="checkbox"]', $table);
        var $chkbox_checked = $('tbody input[type="checkbox"]:checked', $table);
        var chkbox_select_all = $('thead input[name="select_all"]', $table).get(0);

        // If none of the checkboxes are checked
        if ($chkbox_checked.length === 0) {
            chkbox_select_all.checked = false;
            if ('indeterminate' in chkbox_select_all) {
                chkbox_select_all.indeterminate = false;
            }

            // If all of the checkboxes are checked
        } else if ($chkbox_checked.length === $chkbox_all.length) {
            chkbox_select_all.checked = true;
            if ('indeterminate' in chkbox_select_all) {
                chkbox_select_all.indeterminate = false;
            }

            // If some of the checkboxes are checked
        } else {
            chkbox_select_all.checked = true;
            if ('indeterminate' in chkbox_select_all) {
                chkbox_select_all.indeterminate = true;
            }
        }
    }
    $(document).ready(function() {
        var rows_selected = [];

        $.extend($.fn.DataTable.ext.classes, {
            sWrapper: "dataTables_wrapper dt-bootstrap4",
        });
        myTable = $("#myTable").DataTable({
            "bPaginate": true,
            "deferRender": true,
            "columnDefs": [{
                    'targets': 0,
                    'searchable': false,
                    'orderable': false,
                    'width': '2%',
                    'className': 'text-center',
                    'render': function(data, type, full, meta) {
                        return '<input type="checkbox">';
                    },
                },
                {
                    "className": "dt-center",
                    "targets": "_all"
                },
                {
                    className: 'text-center',
                    orderable: false,
                    targets: 1,
                },
                {
                    className: 'text-center',
                    targets: 4
                },
                {
                    className: 'text-center',
                    orderable: false,
                    targets: 5
                },
                {
                    className: 'text-center',
                    targets: 6
                },
                {
                    className: 'text-center',
                    orderable: false,
                    targets: 7
                },
            ],
            "columns": [{
                    "data": ""
                },
                {
                    "data": "prod_price_id"
                },
                {
                    "data": "image"
                },
                {
                    "data": "product_name"
                },
                {
                    "data": "prd_barcode"
                },
                {
                    "data": "barcode"
                },
                {
                    "data": "stylecode"
                },
                {
                    "data": "attributes"
                },
                {
                    "data": "p_price"
                },
                {
                    "data": "selling_price"
                },
                // {"data": "id"}
            ],
            "processing": true,
            //  "serverSide": true,
            "bStateSave": true,
            'bPaginate': true,
            "bProcessing": true,
            // 'paging': true,
            "ajax": {
                "url": "<?= base_url("tiktok/get_tiktok_products") ?>",
                "dataType": "json",
                "type": "POST",
            },
            language: {
                processing: "<div class='loading'></div>",
            },

            "order": [
                [1, 'desc']
            ],
            'rowCallback': function(row, data, dataIndex) {
                // Get row ID
                var rowId = data[0];

                // If row ID is in the list of selected row IDs
                if ($.inArray(rowId, rows_selected) !== -1) {
                    $(row).find('input[type="checkbox"]').prop('checked', true);
                    $(row).addClass('selected');
                }
            },
            "lengthMenu": [
                [5, 30, 50, 100, 200],
                [5, 30, 50, 100, 200]
            ],
            "dom": "lrtip"
        });

        // this  is for customized searchbox with datatable search feature.
        $('#myCustomSearchBox').keyup(function() {
            myTable.search($(this).val()).draw();
        })

        $("#resetTxtbx").on("click", function(e) {
            document.getElementById("myCustomSearchBox").value = '';
            myTable.search('').draw();
        });
        // Image Zoom
        $("#myTable").on("click", "#btnImgpop", function(e) {
            let imgPath = $(this).data("image_path");
            html =
                '<img width="231" height="347" src="' +
                imgPath +
                '" id="product_image"/>';
            $("#image_modal_body").html(html);
            $("#ImageModal").modal("show");
        });

        //Stock Update
        $("#myTable").on('change', '.inventory_container', function() {
            var prod_price_id = $(this).attr('data-prod_price_id');
            var prod_id = $(this).attr('data-id_product');
            var attributes_value = $(this).attr('data-attributes_value');
            var new_selling_price = $(this).val();
            console.log('object');
            var url = ADMIN_URL + 'tiktok/edit';
            var param = {
                prod_price_id: prod_price_id,
                new_selling_price: new_selling_price,
                prod_id: prod_id,
                attributes_value: attributes_value,
            };
            trigger_ajax(url, param).done(function(res) {
                var res = JSON.parse(res);
                if (res['type'] === "success") {
                    var myTable = $('#myTable').DataTable();
                    // If you want totally refresh the datatable use this
                    // myTable.ajax.reload();
                    // If you want to refresh but keep the paging you can you this
                    myTable.ajax.reload(null, false);
                }
            }).fail(function() {
                console.log("falied");
            });
        });

        // Handle click on checkbox
        $('#myTable tbody').on('click', 'input[type="checkbox"]', function(e) {
            var $row = $(this).closest('tr');

            // Get row data
            var data = myTable.row($row).data();
            const checkboxIds = Object.values(data);

            // Get row ID
            var rowId = checkboxIds[0];
            // console.log(rowId, 'data')

            // Determine whether row ID is in the list of selected row IDs
            var index = $.inArray(rowId, rows_selected);

            // If checkbox is checked and row ID is not in list of selected row IDs
            if (this.checked && index === -1) {
                rows_selected.push(rowId);


                // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
            } else if (!this.checked && index !== -1) {
                rows_selected.splice(index, 1);

            }

            if (this.checked) {
                $row.addClass('selected');
            } else {
                $row.removeClass('selected');
            }

            // Update state of "Select all" control
            updateDataTableSelectAllCtrl(myTable);

            // Prevent click event from propagating to parent
            e.stopPropagation();
        });
        // Handle click on table cells with checkboxes
        $('#myTable').on('click', 'tbody td, thead th:first-child', function(e) {
            console.log('two')
            $(this).parent().find('input[type="checkbox"]').trigger('click');
        });

        // Handle click on "Select all" control
        $('thead input[name="select_all"]', myTable.table().container()).on('click', function(e) {
            console.log('three')
            if (this.checked) {
                $('#myTable tbody input[type="checkbox"]:not(:checked)').trigger('click');
            } else {
                $('#myTable tbody input[type="checkbox"]:checked').trigger('click');
            }

            // Prevent click event from propagating to parent
            e.stopPropagation();
        });

        // Handle table draw event
        myTable.on('draw', function() {
            // Update state of "Select all" control
            updateDataTableSelectAllCtrl(myTable);
        });

        // Handle form submission event
        $('#frm-table').on('submit', function(e) {
            // Prevent actual form submission
            //  e.preventDefault();
            var form = this;
            // Iterate over all selected checkboxes
            $.each(rows_selected, function(index, rowId) {
                // Create a hidden element
                $(form).append(
                    $('<input>')
                    .attr('type', 'hidden')
                    .attr('name', 'id[]')
                    .val(rowId)
                );
            });
            // Output form data to a console 
            /* $('#example-console-form').text($(form).serialize());
             $('#example-console-rows').text(rows_selected.join(","));
 */
            let product_ids = JSON.parse(JSON.stringify(rows_selected.join(",")))
            // Remove added elements
            $('input[name="id\[\]"]', form).remove();


            // Send ID to controllers
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: ADMIN_URL + "tiktok/getExportProducts",
                data: {
                    product_ids: product_ids,
                },
                success: function(data) {
                    let downloadLink = document.createElement("a");
                    let fileData = ['\ufeff' + data];
                    var blobObject = new Blob(fileData, {
                        type: "text/csv;charset=utf-8;"
                    });
                    let url = URL.createObjectURL(blobObject);
                    downloadLink.href = url;
                    downloadLink.download = "tiktokProducts.csv";
                    document.body.appendChild(downloadLink);
                    downloadLink.click();
                    document.body.removeChild(downloadLink);

                },
            });
        });

    });
</script>
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
                <h6 class="m-0 font-weight-bold text-primary">Stock Details </h6>
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

            <!--  <script type="text/javascript">
                $(document).ready(function() {

                    $.extend($.fn.DataTable.ext.classes, {
                        sWrapper: "dataTables_wrapper dt-bootstrap4",
                    });
                    myTable = $("#myTable").DataTable({
                        "bPaginate": true,
                        "deferRender": true,
                        // "iDisplayLength": 10,
                        "columnDefs": [{
                                "className": "dt-center",
                                "targets": "_all"
                            }, {
                                className: 'text-center',
                                targets: 1
                            },
                            {
                                className: 'text-center',
                                orderable: false,
                                targets: 2
                            },
                            {
                                className: 'text-center',
                                targets: 3
                            },
                            {
                                className: 'text-center',
                                orderable: true,
                                targets: 4
                            },
                        ],
                        "columns": [{
                                data: 'SrNo',
                                render: function(data, type, row, meta) {
                                    return meta.row + meta.settings._iDisplayStart + 1;
                                }
                            },
                            {
                                "data": "image"
                            },
                            {
                                "data": "name"
                            },
                            {
                                "data": "product_id"
                            },
                            {
                                "data": "title"
                            },
                            {
                                "data": "barcode",

                            },
                            {
                                "data": "price"
                            },
                            {
                                "data": "inventory_quantity"
                            },

                        ],
                        "processing": true,
                        //"serverSide": true,

                        "ajax": {
                            "url": "<?= base_url("stocks/get_products") ?>",
                            "dataType": "json",
                            "type": "POST",
                        },
                        language: {
                            processing: "<div class='loading'></div>",
                        },
                        "order": [
                            [0, 'asc']
                        ],
                        "lengthMenu": [
                            [20, 30, 50, 100, 200],
                            [20, 30, 50, 100, 200]
                        ],
                        "dom": "lrtip"
                    });

                    // this  is for customized searchbox with datatable search feature.
                    // $('#myCustomSearchBox').keyup(function() {
                    $('#myCustomSearchBox').on('keyup', function() {
                        myTable.search($(this).val()).draw();
                        // myTable.search(this.value).draw();
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
                        var variant_id = $(this).attr('data-variant_id');
                        var new_inventory = $(this).val();

                        var url = ADMIN_URL + 'stocks/edit';
                        var param = {
                            variant_id: variant_id,
                            new_inventory: new_inventory
                        };
                        console.log(param, "param");
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
                });
            </script> -->

            <!-- Card Body -->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="myTable">
                        <thead style="background: #7474e9;color:#fff">
                            <tr>
                                <th></th>
                                <th>ID</th>
                                <th id="image">Image</th>
                                <th>Name</th>
                                <th>Product ID</th>
                                <th>Variants</th>
                                <th>Barcode</th>
                                <th>Price</th>
                                <th width="80px" id="inventory">Inventory</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal for Image Zoom -->
<div class="modal fade" id="ImageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 265px !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="image_modal_body"></div>
        </div>
    </div>
</div>
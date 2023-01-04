<style>
    .table {
        color: #000000 !important;
    }

    td,
    th {
        text-align: center !important;
    }
    .supp_center {
    display: flex;
    justify-content: center;
    line-height: 22px;
  }
</style>
<div class="mb-4">
    <?= $this->breadcrumbs->show(); ?>
</div>
<div class="row">
    <div class="col-xl-12 col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Supplier List</h6>
                <div class="dropdown no-arrow">
                    <a class="btn btn-outline-success btn-sm" href="<?= admin_url('suppliers/add'); ?>" role="button">
                        <i class="fas fa-plus fa-sm"></i> Add Supplier
                    </a>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="myTable">
                        <thead style="background: #7474e9;color:#fff">
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Supplier Code</th>
                                <th>Email</th>
                                <!-- <th>Address</th> -->
                                <!-- <th>Description</th> -->
                                <th>Mobile</th>
                                <!-- <th>City</th> -->
                                <th>Country</th>
                                <th>Markup (%)</th>
                                <!-- <th id="inventory">View</th> -->
                                <th id="supplier_view">View</th>
                                <th id="action" width="12%">Action</th>
                                <!-- <th id="image">Image</th> -->
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="SupplierModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Supplier Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="supplier_modal_body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

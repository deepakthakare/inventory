<div class="mb-4">
    <?= $this->breadcrumbs->show(); ?>
</div>
<div class="row">
    <div class="col-xl-12 col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">IFIF Orders List</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="myTable">
                        <thead style="background: #7474e9;color:#fff">
                            <tr>
                                <th width="10%">Order Id</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Quantity</th>
                                <th id="action" width="10%">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
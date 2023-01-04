
<div class="mb-4">
  <?= $this->breadcrumbs->show(); ?>
</div>
<div class="row">
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Delivery Inwards</h6>
        <?php if (in_array('createBrand', $user_permission)) { ?>
          <div class="dropdown no-arrow">
            <a class="btn btn-outline-success btn-sm" href="<?= admin_url('delnote/add'); ?>" role="button">
              <i class="fas fa-plus fa-sm"></i> Add Delnote
            </a>
          </div>
        <?php } ?>
      </div>
      <!-- Card Body -->
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover table-bordered" id="myTable">
            <thead style="background: #7474e9;color:#fff">
              <tr>
                <th width="10%">Id</th>
                <th width="15%">Created Date</th>
                <th width="15%">Landed Date</th>
                <th width="15%">Supplier Name</th>
                <th width="15%">Country</th>
                <th width="15%">Del No.</th>
                <th width="10%">TTL QTY</th>
                <!-- <th width="15%">View</th> -->
                <th id="action" width="15%">Action</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>



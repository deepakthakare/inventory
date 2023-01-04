<div class="mb-4">
  <?= $this->breadcrumbs->show();

  ?>
</div>
<div class="row">
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Warehouse Places List</h6>
        <?php if (in_array('createBrand', $user_permission)) { ?>
          <div class="dropdown no-arrow">
            <a class="btn btn-outline-success btn-sm" href="<?= admin_url('places/add'); ?>" role="button">
              <i class="fas fa-plus fa-sm"></i> Add Warehouse Place
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
                <th width="20%">Id</th>
                <th width="50%">Name</th>
                <th width="20%">Code</th>
                <th id="action" width="10%">Action</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
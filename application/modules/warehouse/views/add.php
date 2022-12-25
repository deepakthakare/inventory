<script src="<?= base_url("assets/js/jquery-2.2.4.min.js"); ?>"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.css">
<script src="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    var multipleCancelButton = new Choices('#place_value', {
      removeItemButton: true,
      //    maxItemCount:15,
      //    searchResultLimit:15,
      //    renderChoiceLimit:15
    });
  });
</script>
<div class="mb-4">
  <?= $this->breadcrumbs->show(); ?>
</div>
<div class="row">
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary"><?= $button; ?> Warehouse</h6>
      </div>
      <!-- Card Body -->
      <form action="<?= $action; ?>" method="post">
        <div class="card-body">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label for="usr">Name:</label>
                <input type="text" class="form-control" id="" name="name" value="<?php echo $name; ?>">
                <?php echo form_error('name'); ?>
              </div>
            </div>
            <div class="col-sm-2">
              <div class="form-group">
                <label for="code">Code:</label>
                <input type="text" class="form-control" id="" name="code" value="<?php echo $code; ?>">
                <?php echo form_error('code'); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label for="adr">Address:</label>
                <input type="text" class="form-control" id="" name="address" value="<?php echo $address; ?>">
                <?php echo form_error('address'); ?>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label for="place">Select Warehouse Places:</label>
                <select name="place_value[]" id="place_value" placeholder="Select Warehouse Places" multiple>
                  <?php foreach ($places_list as $key => $place) { ?>
                    <option value="<?= $place->place_id ?>"><?= $place->name . " - " . $place->code ?></option>
                  <?php } ?>
                </select>
                <!-- <?php echo form_error('places_value') ?> -->
              </div>
            </div>
          </div>
          <input type="hidden" name="id" value="<?php echo $id; ?>" />

        </div>
        <div class="card-footer">
          <a href="<?= admin_url('warehouse') ?>" class="btn btn-danger btn-sm btn-icon-split">
            <span class="icon text-white-50"><i class="fas fa-arrow-left"></i></span><span class="text">Back</span>
          </a>
          <button type="submit" class="btn btn-success btn-sm btn-icon-split">
            <span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text"><?= $button; ?></span>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
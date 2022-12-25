<style>
  .modal.left .modal-dialog,
  .modal.right .modal-dialog {
    position: fixed;
    margin: auto;
    width: 320px;
    height: 100%;
    -webkit-transform: translate3d(0%, 0, 0);
    -ms-transform: translate3d(0%, 0, 0);
    -o-transform: translate3d(0%, 0, 0);
    transform: translate3d(0%, 0, 0);
  }

  .modal.left .modal-content,
  .modal.right .modal-content {
    height: 100%;
    overflow-y: auto;
  }

  .modal.left .modal-body,
  .modal.right .modal-body {
    padding: 15px 15px 80px;
  }

  /*Left*/
  .modal.left.fade .modal-dialog {
    left: 2px;
    -webkit-transition: opacity 0.3s linear, left 0.3s ease-out;
    -moz-transition: opacity 0.3s linear, left 0.3s ease-out;
    -o-transition: opacity 0.3s linear, left 0.3s ease-out;
    transition: opacity 0.3s linear, left 0.3s ease-out;
  }

  .modal.left.fade.in .modal-dialog {
    left: 0;
  }

  /*Right*/
  .modal.right.fade .modal-dialog {
    right: 2px;
    -webkit-transition: opacity 0.3s linear, right 0.3s ease-out;
    -moz-transition: opacity 0.3s linear, right 0.3s ease-out;
    -o-transition: opacity 0.3s linear, right 0.3s ease-out;
    transition: opacity 0.3s linear, right 0.3s ease-out;
  }

  .modal.right.fade.in .modal-dialog {
    right: 0;
  }

  /* ----- MODAL STYLE ----- */
  .modal-content {
    border-radius: 0;
    border: none;
  }

  .modal-header {
    border-bottom-color: #EEEEEE;
    background-color: #FAFAFA;
  }

  .dropdown.bootstrap-select.form-control {
    height: 40px !important;
    border: 1px solid #00000024 !important;
  }

  .modal-location {
    border-bottom-color: #1cc88a !important;
    background-color: #1cc88a !important;
  }

  .txt-color {
    color: white !important;
  }
</style>
<div class="mb-4">
  <?= $this->breadcrumbs->show(); ?>
</div>
<div class="row">
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary"><?= $button; ?> Product</h6>
      </div>
      <!-- Card Body -->
      <form action="<?= $action; ?>" method="post">
        <div class="card-body">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label for="usr">Name:</label>
                <input type="text" class="form-control" id="" name="name" value="<?php echo $edit_data[0]->name; ?>">
                <?php echo form_error('name'); ?>
              </div>
            </div>
            <div class="col-sm-2">
              <div class="form-group">
                <label for="usr">Price:</label>
                <input type="text" class="form-control" id="p_price" name="p_price" value="<?php echo $edit_data[0]->p_price; ?>">
                <?php echo form_error('p_price'); ?>
              </div>
            </div>
            <div class="col-sm-2">
              <div class="form-group">
                <label for="location">Location:</label>
                <input type="text" class="form-control" id="location" name="location" value="<?php echo $edit_data[0]->location; ?>">
                <?php echo form_error('location'); ?>
              </div>
            </div>
            <div class="col-sm-2">
              <div class="form-group">
                <label for="weight">Weight:</label>
                <input type="text" class="form-control" id="weight" name="weight" value="<?php echo $edit_data[0]->weight; ?>">
                <?php echo form_error('weight'); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label for="usr">Description:</label>
                <textarea name="description" class="form-control"> <?= $edit_data[0]->description; ?></textarea>
                <?php echo form_error('name'); ?>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label for="usr">Barcode:</label>
                <input type="text" class="form-control" id="" name="prd_barcode" value="<?php echo $edit_data[0]->prd_barcode; ?>" readonly>
                <?php echo form_error('barcode'); ?>
              </div>
            </div>

          </div>
          <div class="row">
            <div class="col-sm-4">
              <label>Category:</label>
              <select class="form-control selectpicker" name="product_category" id="product_category" data-show-subtext="true" data-live-search="true">
                <option value="">Please Select</option>
                <?php foreach ($category_list as $key => $category) { ?>
                  <option <?php if ($category->category_id == $edit_data[0]->category_id) {
                            echo "selected";
                          } ?> value="<?= $category->category_id ?>"><?= $category->name ?></option>
                <?php } ?>
              </select>
              <?php echo form_error('product_category') ?>
            </div>
            <div class="input-group col-sm-2">
              <div class="form-group">
                <label for="length">Length(cm):</label>
                <input type="number" class="form-control" id="length" name="length" value="<?php echo $edit_data[0]->length; ?>" placeholder="In CM">
                <?php echo form_error('length'); ?>
              </div>
            </div>

            <div class="input-group col-sm-2">
              <div class="form-group">
                <label for="width">Width(cm):</label>
                <input type="number" class="form-control" id="width" name="width" value="<?php echo $edit_data[0]->width; ?>" placeholder="In CM">
                <?php echo form_error('width'); ?>
              </div>
            </div>

            <div class="input-group col-sm-2">
              <div class="form-group">
                <label for="height">Height(cm):</label>
                <input type="number" class="form-control" id="height" name="height" value="<?php echo $edit_data[0]->height; ?>" placeholder="In CM">
                <?php echo form_error('height'); ?>
              </div>
            </div>
          </div>
          <p><br></p>
          <div class="row">
            <div class="col-sm-3">
              <div class="form-group">
                <label>Add Vendor/Warehouse/Location:</label>
                <div class="col-sm-24" align="left">
                  <button type="button" id="addVendorWarehouse" class="btn btn-success btn-sm btn-icon-split" data-toggle="modal" data-target="#locationModal">
                    <span class="icon text-white-50"><i class="fa fa-pencil fa-fw" aria-hidden="true"></i></span><span class="text">Update </span>
                  </button>
                  <div id="locationP" style="float: right;"></div>
                </div>
              </div>
            </div>
          </div>

          <p><br></p>
          <div class="row">
            <div class=" col-md-6 form-group">
              <label for="varchar">Upload Image <?php echo form_error('picture') ?></label>
              <?php if ($edit_data[0]->image_path) { ?>
                <input type="file" name="image" id="image" data-error="Please upload  image." value="<?php echo $edit_data[0]->image_path; ?>" />
                <br /><img src="<?php echo $edit_data[0]->image_path; ?>" class="img-responsive" style="width:120px;height:100px;">

              <?php } else { ?>
                <input type="file" name="image" id="image" data-error="Please upload product image." value="<?php echo $edit_data[0]->image_path; ?>" />
              <?php } ?>
            </div>
          </div>
          <!-- attributes block -->
          <div>
            <table class="table" id="tbl_attributes">
              <tr>
                <th>Color</th>
                <th>Size</th>
                <th>StyleCode</th>
                <th>Quantity</th>
                <th>Barcode</th>
                <th width="8%">Action</th>
              </tr>
              <?php foreach ($edit_data as $key => $value) { ?>
                <tr>
                  <td>
                    <input type="hidden" class="form-control" readonly name="prod_price_ids[]" value="<?= $value->prod_price_id; ?>">
                    <input type="text" class="form-control" readonly name="colors_value[]" value="<?= $value->color; ?>">
                  </td>
                  <td><input type="text" class="form-control" readonly name="sizes_value[]" value="<?= $value->size; ?>"></td>
                  <td><input type="text" class="form-control" readonly name="stylecode[]" value="<?= $value->stylecode; ?>"></td>
                  <td><input type="text" class="form-control" name="inventory[]" value="<?= $value->inventory; ?>"></td>
                  <td><input type="text" class="form-control" readonly name="barcode[]" value="<?= $value->barcode; ?>"></td>
                  <td><a href="#!" class="btn-danger btn-circle btn-sm text-white btn_remove_row" onclick="removeROW(this);"><i class="fas fa-trash-alt"></i></a>
                    <a href="#!" class="btn-primary btn-circle btn-sm text-white btn_remove_row" title="Print Barcode" onclick="printBarcode(<?= $value->prod_price_id; ?>,<?= $value->barcode; ?>);"><i class="fa fa-barcode"></i></a>
                  </td>
                </tr>
              <?php } ?>


            </table>
          </div>
          <!-- end of attributes block -->
          <!-- vendor Location block pass values -->
          <input id="tbl_vendordetails" name="tbl_vendordetails" type="hidden" />
          <br />
          <div class="row">
            <div class="col-sm-12" align="center">
              <button type="button" id="addAttributesBRD" class="btn btn-success btn-sm btn-icon-split" data-toggle="modal" data-target="#myModal2">
                <span class="icon text-white-50"><i class="fas fa-plus"></i></span><span class="text">Add Attributes</span>
              </button>
              <?php echo form_error('attributes[]'); ?>
            </div>

          </div>


          <input type="hidden" name="prod_id" value="<?php echo $prod_id; ?>" />

        </div>
        <div class="card-footer">
          <a href="<?= admin_url('products') ?>" class="btn btn-danger btn-sm btn-icon-split">
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
<!-- Modal -->
<div class="modal left fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel2">Add Attributes/price</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>

      <div class="modal-body">
        <div class="row">
          <!-- <div class="col-sm-12">
            <div class="form-group">
              <label>Attribute:</label>
              <select class="form-control attributes" id="attributes">
                <option value="">Please Select</option>
                <?php foreach ($attributes_list as $key => $attributes) {
                  $attVal = ($attributes->values); ?>
                  <option data-attr_values='<?= $attVal; ?>' value="<?= $attributes->attributes_id ?>"><?= $attributes->name ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group">
              <label>Attribute Value:</label>
              <select class="form-control" id="attributes_value">
                <option value="">Please Select</option>
              </select>
            </div>
          </div> -->

          <!-- Hide the Color and Size Dropdown for get the items -->
          <div class="col-sm-12" style="display:none !important">
            <div class="form-group">
              <label>Color:</label>
              <select class="form-control attributes" id="color">
                <option value="">Please Select</option>
                <?php foreach ($color_list as $key => $colors) {
                  $attVal = ($colors->values); ?>
                  <option data-color_values='<?= $attVal; ?>' value="<?= $colors->attributes_id ?>"><?= $colors->values ?></option>
                <?php } ?>
              </select>
            </div>
          </div>

          <div class="col-sm-12" style="display:none !important">
            <div class="form-group">
              <label>Size:</label>
              <select class="form-control attributes" id="size">
                <option value="">Please Select</option>
                <?php foreach ($size_list as $key => $sizes) {
                  $attVal = ($sizes->values); ?>
                  <option data-size_values='<?= $attVal; ?>' value="<?= $sizes->attributes_id ?>"><?= $sizes->values ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <!-- end code hide the Color and Size Dropdown for get the items -->

          <!-- Color -->
          <div class="col-sm-12">
            <div class="form-group">
              <label>Color:</label>
              <select class="form-control" id="colors_value">
                <option value="">Please Select</option>
              </select>
            </div>
          </div>

          <!-- Size -->

          <div class="col-sm-12">
            <div class="form-group">
              <label>Size:</label>
              <select class="form-control" id="sizes_value">
                <option value="">Please Select</option>
              </select>
            </div>
          </div>


        </div>
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label>Stylecode:</label>
              <input type="text" class="form-control" id="stylecode" name="stylecode">
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group" id="qtyDisplay">
              <label>Quantity:</label>
              <input type="number" class="form-control" name="inventory" id="inventory">
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group">
              <label>Barcode:</label>
              <input type="text" class="form-control" name="barcode" id="barcode" readonly>
            </div>
          </div>

          <div class="col-sm-12 text-center">
            <button type="button" id="btnAddNewAttributes" class="btn btn-success btn-sm btn-icon-split">
              <span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text"><?= $button; ?></span>
            </button>
          </div>
        </div>
      </div>

    </div><!-- modal-content -->
  </div><!-- modal-dialog -->
</div><!-- modal -->

<!-- Start Vendor, Warehouse and Location Modal -->
<div class="modal right fade" id="locationModal" tabindex="-1" role="dialog" aria-labelledby="locationModalLable">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header modal-location">
        <h5 class="modal-title txt-color" id="locationModalLable">Update Vendor, Warehouse and Location</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>

      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label>Vendor:</label>
              <select class="form-control" name="vendors" id="vendors">
                <option value="">Please Select</option>
                <?php foreach ($vendor_list as $key => $vendor) {
                  $warehouesName = ($vendor->warehouse_name); ?>
                  <option data-attr_values='<?= $warehouesName; ?>' data-vendor_code='<?= $vendor->code ?>' <?php if ($vendor->id == $edit_data[0]->vendor_id) {
                                                                                                              echo "selected";
                                                                                                            } ?> value="<?= $vendor->id ?>"><?= $vendor->name ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group">
              <label>Warehouse:</label>
              <select class="form-control" name="warehouse_value" id="warehouse_value">
                <option value="">Please Select</option>
                <?php foreach ($warehouse_list as $key => $warehouse) { ?>
                  <option data-warehouse_code='<?= $warehouse['code'] ?>' <?php if ($warehouse['id'] == $edit_data[0]->warehouse_id) {
                                                                            echo "selected";
                                                                          } ?> value="<?= $warehouse['id'] ?>"><?= $warehouse['name'] ?></option>
                <?php } ?>
              </select>
            </div>
          </div>

          <div class="col-sm-12">
            <div class="form-group">
              <label>Place:</label>
              <select class="form-control" name="place_value" id="place_value">
                <option value="">Please Select</option>
                <?php foreach ($place_list as $key => $place) { ?>
                  <option data-place_code='<?= $place['code'] ?>' <?php if ($place['place_id'] == $edit_data[0]->place_id) {
                                                                    echo "selected";
                                                                  } ?> value="<?= $place['place_id'] ?>"><?= $place['name'] ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group">
              <label>Aisle:</label>
              <select class="form-control" name="aisle_value" id="aisle_value">
                <option value="">Please Select</option>
                <?php foreach ($aisle_list as $key => $aisle) { ?>
                  <option data-aisle_code='<?= $aisle['code'] ?>' <?php if ($aisle['aisle_id'] == $edit_data[0]->aisle_id) {
                                                                    echo "selected";
                                                                  } ?> value="<?= $aisle['aisle_id'] ?>"><?= $aisle['name'] ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group">
              <label>Section:</label>
              <select class="form-control" name="section_value" id="section_value">
                <option value="">Please Select</option>
                <?php foreach ($section_list as $key => $section) { ?>
                  <option data-section_code='<?= $section['code'] ?>' <?php if ($section['section_id'] == $edit_data[0]->section_id) {
                                                                        echo "selected";
                                                                      } ?> value="<?= $section['section_id'] ?>"><?= $section['name'] ?></option>
                <?php } ?>
              </select>
            </div>
          </div>

          <div class="col-sm-12">
            <div class="form-group">
              <label>Sub-Section:</label>
              <select class="form-control" name="subsection_value" id="subsection_value">
                <option value="">Please Select</option>
                <?php foreach ($subsection_list as $key => $subsection) { ?>

                  <option data-subsection_code='<?= $subsection['code'] ?>' <?php if ($subsection['subsection_id'] == $edit_data[0]->subsection_id) {
                                                                              echo "selected";
                                                                            } ?> value="<?= $subsection['subsection_id'] ?>"><?= $subsection['name'] ?></option>
                <?php } ?>
              </select>
            </div>
          </div>

          <div class="col-sm-12">
            <div class="form-group">
              <label>Number:</label>
              <input type="number" class="form-control" name="number_value" id="number_value" value="<?php echo $edit_data[0]->number_value; ?>">
            </div>
          </div>


          <div class="col-sm-12 text-center">
            <button type="button" id="btnAddVendorWarehouse" class="btn btn-success btn-sm btn-icon-split">
              <span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text"><?= $button; ?></span>
            </button>
          </div>
        </div>
      </div><!-- modal-content -->
    </div><!-- modal-dialog -->
  </div>
</div>

<!-- End Vendor, Warehouse and Location Modal -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css" rel="stylesheet" />

<script>
  $(document).ready(function($) {
    $("#image").pekeUpload({
      bootstrap: true,
      url: "<?= admin_url("upload/"); ?>",
      data: {
        file: "image"
      },
      limit: 1,
      allowedExtensions: "JPG|JPEG|GIF|PNG|PDF|jpg|jpeg|gif|png|pdf"
    });

  });
  $(function() {
    $('.selectpicker').selectpicker();
  });
</script>
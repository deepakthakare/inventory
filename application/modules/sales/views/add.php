<style>
  .pull-right {
    float: right !important;
  }

  .control-label {
    padding-top: 11px;
    margin-bottom: 0;
    text-align: right;
    letter-spacing: 0.6px;
    font-weight: 800;

  }

  .table th,
  .table td {
    padding-top: 9px !important;
  }

  .form-control {
    border-radius: unset;
  }

  .m-bottom {
    margin-bottom: 12px;
  }
</style>
<div class="mb-4">
  <?= $this->breadcrumbs->show(); ?>
</div>
<div class="row">
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Add Order</h6>
      </div>
      <!-- Card Body -->
      <form action="<?= $action; ?>" method="post">
        <div class="card-body">
          <div class="row">
            <div class="col-sm-3 form-group">
              <label>Order Date<span class="text-danger">*</span></label>
              <input type="date" name="sales_date" class="form-control" />
            </div>
            <div class="col-sm-3 form-group">
              <label>Select Customer<span class="text-danger">*</span></label>
              <select name="customer" id="customer" class="form-control">
              </select>
              <?php echo form_error('customer') ?>
            </div>
            <div class="col-sm-5 form-group">
              <label>Shiping Method<span class="text-danger">*</span></label>
              <select class="form-control" name="shipping" id="shipping">
                <option value="">Please Select</option>
                <?php foreach ($shipping as $key => $shipping_amt) { ?>
                  <option value="<?= $shipping_amt ?>"><?= $key . ' - ' . '<b>' . $shipping_amt . '</b>' ?></option>
                <?php } ?>
              </select>
              <?php echo form_error('shipping') ?>
            </div>
          </div>
          <div class="row">
            <!-- <div class="col-sm-3">
              <select class="form-control" name="product_category" id="product_category">
                <option value="">Select Category</option>
                <?php foreach ($category_list as $key => $category) { ?>
                  <option value="<?= $category->category_id ?>"><?= $category->name ?></option>
                <?php } ?>
              </select>
              <?php echo form_error('product_category') ?>
            </div> -->
            <!-- <div class="col-sm-3">
              <select class="form-control" name="brand" id="prod_brand">
                <option value="">Select Brand</option>
                <?php foreach ($brand_list as $key => $brand) { ?>
                  <option value="<?= $brand->brand_id ?>"><?= $brand->name ?></option>
                <?php } ?>
              </select>
              <?php echo form_error('brand') ?>
            </div> -->
            <div class="col-sm-3">
              <select class="form-control" name="all_products" id="all_products">

              </select>
              <?php echo form_error('all_products') ?>
            </div>
            <!-- <div class="col-sm-3">
              <select class="form-control" name="product" id="product">

              </select>
              <?php echo form_error('product') ?>
            </div> -->
            <div class="col-sm-3">
              <select class="form-control" name="product_variants" id="product_variants">

              </select>
              <?php echo form_error('product_variants') ?>
            </div>
            <div class="col-sm-2">
              <input type="number" class="form-control" name="quantity" id="quantity">
              <?php echo form_error('quantity') ?>
            </div>
            <div class="col-sm-1">
              <div class="col-sm-12" align="center">
                <button type="button" class="btn btn-success btn-sm btn-icon-split" id="btnAddProduct">
                  <span class="icon text-white-50"><i class="fas fa-plus"></i></span>
                </button>
              </div>
            </div>

          </div>
          <br />
          <!-- attributes block -->
          <div>
            <table class="table" id="tbl_attributes">
              <tr style="background-color: #5d83d9;color: white;">
                <th>Image</th>

                <th>Product</th>
                <th>StyleCode</th>
                <th>Color</th>
                <th>Attribute</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Tax (%)</th>
                <th>Total Amount</th>
                <th>Action</th>
              </tr>


            </table>
          </div>
          <!-- end of attributes block -->
          <input type="hidden" name="sales_id" value="<?php echo $sales_id; ?>" />
          <div class="box-body" style="border-top: 2px solid #5d83d9;">
            <br>
            <div class="d-flex justify-content-end m-bottom ">
              <div class="d-flex justify-content-end">
                <label for="gross_amount" class="col-sm-5 control-label">Gross Amount</label>
                <div class="col-sm-7">
                  <input type="text" class="form-control" id="gross_amount" name="gross_amount" disabled autocomplete="off">
                  <input type="hidden" class="form-control" id="gross_amount_value" name="gross_amount_value" autocomplete="off">
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-end m-bottom ">
              <div class="d-flex justify-content-end">
                <label for="shipping" class="col-sm-5 control-label">Shipping &nbsp;&nbsp;&nbsp;&nbsp;</label>
                <div class="col-sm-7">
                  <input type="text" class="form-control" id="shipping_amount" name="shipping_amount" disabled autocomplete="off">
                  <input type="hidden" class="form-control" id="shipping_amount_value" name="shipping_amount_value" autocomplete="off">
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-end m-bottom ">
              <div class="d-flex justify-content-end">
                <label for="vat" class="col-sm-5 control-label">VAT &nbsp;&nbsp;&nbsp;&nbsp;</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="vat_charge" name="vat_charge" disabled autocomplete="off">
                  <input type="hidden" class="form-control" id="vat_charge_value" name="vat_charge_value" autocomplete="off">
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-end m-bottom ">
              <div class="d-flex justify-content-end">
                <label for="vat" class="col-sm-5 control-label">Final Amount</label>
                <div class="col-sm-7">
                  <input type="text" class="form-control" id="final_amount" name="final_amount" disabled autocomplete="off">
                  <input type="hidden" class="form-control" id="final_amount_value" name="final_amount_value" autocomplete="off">
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <a href="<?= admin_url('sales') ?>" class="btn btn-danger btn-sm btn-icon-split">
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
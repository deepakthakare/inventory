<link href="<?= base_url("assets/css/product_modal.css"); ?>" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css" type="text/css" />


<div class="mb-4">
    <?= $this->breadcrumbs->show(); ?>
</div>
<div class="row">
    <div class="col-xl-12 col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary"><?= $button; ?> Delivery Inward</h6>
            </div>
            <!-- Card Body -->
            <form action="<?= $action; ?>" method="post">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group has-float-label">
                                <select class="form-control selectpicker custom-select" name="product_supplier" id="product_supplier" data-show-subtext="true" data-live-search="true">
                                    <option value="">Select Supplier</option>
                                    <?php foreach ($supplier_list as $key => $supplier) { ?>
                                        <option value="<?= $supplier->id_supplier ?>"><?= $supplier->name ?></option>
                                    <?php } ?>
                                </select>
                                <?php echo form_error('product_supplier') ?>
                            </div>
                        </div>
                        <div class="col-sm-4" id="country_details" style="display: flex; justify-content: space-around;"> </div>
                        <div class="col-sm-2">
                            <div class="form-group floating">
                                <input type="text" class="form-control floating text-uppercase" id="cust_delno" name="cust_delno" value="<?php echo $cust_delno; ?>">
                                <label for="usr">Cust Del No.</label>
                                <?php echo form_error('cust_delno'); ?>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group floating" data-date="<?php echo date('d-m-Y'); ?>" data-date-format="dd-mm-yyyy" data-date-viewmode="years">
                                <input type="text" id="denote_date" name="denote_date" class="form-control floating date-input" value="<?php echo date('d-m-Y'); ?>" />
                                <label for="datedate">Delnote Date</label>

                                <?php echo form_error('denote_date'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group floating">
                                <input type="text" class="form-control floating" id="customer_markup" name="customer_markup" value="" readonly>
                                <label for="usr" class="disable">Cust Markup</label>
                                <?php echo form_error('customer_markup'); ?>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group floating">
                                <input type="text" class="form-control floating" id="exchange_rate" name="exchange_rate" value="" readonly>
                                <label for="usr" class="disable">Exchange Rate</label>
                                <?php echo form_error('exchange_rate'); ?>
                            </div>
                        </div>

                        <div class="col-sm-2"></div>


                        <div class="col-sm-2">
                            <div class="form-group floating">
                                <input type="text" class="form-control floating" id="ttl_sp" name="ttl_sp" value="" readonly>
                                <label for="usr" class="disable">Total SP(£)</label>
                                <!-- <?php echo form_error('ttl_sp'); ?> -->
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group floating">
                                <input type="text" class="form-control floating hide" id="ttl_qty" name="ttl_qty" value="" readonly>
                                <label for="usr" class="disable">Total QTY</label>
                                <!-- <?php echo form_error('ttl_qty'); ?> -->
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group floating">
                                <input type="hidden" class="form-control floating hide" id="ttl_cp" name="ttl_cp" value="" readonly>
                                <input type="text" class="form-control floating hide" id="profit_margin" name="profit_margin" value="" readonly>
                                <label for="prf" class="disable">Profit Margin(%)</label>
                                <!-- <?php echo form_error('profit_margin'); ?> -->
                            </div>
                        </div>
                        <div class="col-sm-12" style="border-top: 2px solid #5d83d9;"></div>
                        <br />
                        <!-- Product block -->
                        <div class="col-sm-12">
                            <table class="table product_table" id="tbl_attributes">
                                <tr style="background-color: #5d83d9;color: white;">
                                    <th>IMAGE</th>
                                    <th>SUPPL REF.</th>
                                    <!-- <th>INTERNAL REF.</th> -->
                                    <th width="8%">QTY</th>
                                    <th>PRICE €</th>
                                    <th width="8%">£ CP</th>
                                    <th width="8%">£ SP</th>
                                    <th >BIN No</th>
                                    <th>HS-CODE</th>
                                    <th>BARCODE</th>
                                    <th>ACTION</th>
                                </tr>
                            </table>
                        </div>
                        <!-- end of product block -->
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-sm-12" align="center">
                        <button type="button" id="addDelnoteProducts" class="btn btn-success btn-sm btn-icon-split" data-toggle="modal" data-target="#myModal2">
                            <span class="icon text-white-50"><i class="fas fa-plus"></i></span><span class="text">Add Products</span>
                        </button>
                        <?php echo form_error('delnote_product[]'); ?>
                    </div>
                </div>
                <br />
                <input type="hidden" name="id_delnote" value="<?php echo $id_delnote; ?>" />
                <div class="card-footer">
                    <a href="<?= admin_url('delnote') ?>" class="btn btn-danger btn-sm btn-icon-split">
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

<!-- Modal  Products -->
<div class="modal left fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
    <div class="modal-dialog" role="document" style="width: 425px !important;">
        <div class="modal-content">

            <div class="modal-header-product">
                <h4 class="modal-title" id="myModalLabel2">Add Product</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>

            <div class="modal-body">
                <div class="Add Attributes"><br />
                    <div class="row" style="margin-bottom: 15px">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="lable_dropdown">Single/Mix</label>
                                <select id="single_mix" class="form-control selectpicker custom-select">
                                    <option value="Select">Select </option>
                                    <option value="single">Single</option>
                                    <option value="mix">Mix</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="lable_dropdown">Category</label>
                                <select class="form-control selectpicker custom-select" name="product_category" id="product_category" data-show-subtext="true" data-live-search="true">
                                    <option value="0">Select Category</option>
                                    <?php foreach ($category_list as $key => $category) { ?>
                                        <option value="<?= $category->category_id ?>"><?= $category->name ?></option>
                                    <?php } ?>
                                </select>
                                <!-- <?php echo form_error('product_category') ?> -->
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group-product floating">
                                <input type="text" class="form-control floating text-uppercase" id="supplier_ref" name="supplier_ref">
                                <label for="usr" class="supplier_ref ">Supplier Ref</label>
                                <!-- <?php echo form_error('supplier_ref'); ?> -->
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group-product floating">
                                <input type="text" class="form-control floating text-uppercase" id="internal_ref" name="internal_ref">
                                <label for="usr" class="internal_ref">Internal Ref</label>
                                <!-- <?php echo form_error('internal_ref'); ?> -->
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group-product floating">
                                <input type="number" class="form-control floating" id="product_qty" name="product_qty" >
                                <label for="qty" class="product_qty">Quantity</label>
                                <!-- <?php echo form_error('product_qty'); ?> -->
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group-product floating">
                                <input type="text"  class="form-control floating" id="price_euro" name="price_euro" onkeyup="totalCpPound()">
                                <label for="price">Price (€)</label>
                                <!-- <?php echo form_error('price_euro'); ?> -->
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group-product floating">
                                <input type="text" class="form-control floating" id="cost_price" name="cost_price" readonly>
                                <label for="cp" class="cost_price disable_product">CP (£)</label>
                                <!-- <?php echo form_error('cost_price'); ?> -->
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group-product floating">
                                <input type="text" class="form-control floating" id="selling_price" name="selling_price" readonly>
                                <label for="sp" class="selling_price disable_product">SP (£)</label>
                                <!-- <?php echo form_error('selling_price'); ?> -->
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group-product floating">
                                <input type="text" class="form-control floating" id="bin_number" name="bin_number">
                                <label for="bin" class="bin_number text-uppercase">Bin Number</label>
                                <!-- <?php echo form_error('bin_number'); ?> -->
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group-product floating">
                                <input type="text" class="form-control floating" id="hs_code" name="hs_code">
                                <label for="hscode" class="hs_code text-uppercase">HS Code</label>
                                <!-- <?php echo form_error('hs_code'); ?> -->
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group-product floating">
                                <input type="text" class="form-control floating" id="product_barcode" name="product_barcode" readonly>
                                <input type="hidden" class="form-control floating" id="is_barcode_recall" name="is_barcode_recall" readonly>
                                <label for="brd" class="product_barcode disable_product">Barcode</label>
                                <!-- <?php echo form_error('product_barcode'); ?> -->
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 text-center">
                        <button type="button" id="btnAddNewProduct" class="btn btn-success btn-sm btn-icon-split">
                            <span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text"><?= $button; ?></span>
                        </button>
                    </div>
                </div>
            </div>

        </div><!-- modal-content -->
    </div><!-- modal-dialog -->
</div><!-- modal -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js" type="text/javascript"></script>
<script>
    $(function() {
        $('.selectpicker').selectpicker();
        $('#denote_date').datepicker({
            format: "dd/mm/yyyy"
        });
    });
</script>
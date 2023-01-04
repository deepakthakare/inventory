<link href="<?= base_url("assets/css/product_modal.css"); ?>" rel="stylesheet">

<div class="mb-4">
    <?= $this->breadcrumbs->show(); ?>
</div>

<div class="row">
    <div class="col-xl-12 col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary"><?= $button; ?> Delnote</h6>
            </div>
            <!-- Delnote Body -->
            <form action="<?= $action; ?>" method="post">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group has-float-label">
                                <select class="form-control selectpicker custom-select" name="product_supplier" id="product_supplier" data-show-subtext="true" data-live-search="true" readonly>
                                    <option value="">Select Supplier</option>
                                    <?php foreach ($supplier_list as $key => $supplier) { ?>
                                        <option <?php if ($supplier->id_supplier === $edit_data[0]->id_supplier) {
                                                    echo "selected";
                                                } ?> value="<?= $supplier->id_supplier ?>"><?= $supplier->name ?></option>
                                    <?php } ?>
                                </select>
                                <!-- <option value="<?= $supplier->id_supplier ?>"><?= $supplier->name ?></option> -->
                                <?php echo form_error('product_supplier') ?>
                            </div>
                        </div>
                        <div class="col-sm-2" id="country_details" style="margin-top: 5px;">
                            <a href="#" class="btn btn-primary btn-sm btn-icon-split supplier_details" id="supplier_details" data-id_supplier="2">
                                <span class="icon text-white-50"><i class="fa-solid fa-eye"></i></span></a>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group floating" data-date="<?php echo date('d-m-Y'); ?>" data-date-format="dd-mm-yyyy" data-date-viewmode="years">
                                <input type="text" id="landed_date" name="landed_date" class="form-control floating date-input" value="<?php echo $edit_data[0]->landed_date; ?>" />
                                <label for="landeddate">Landed Date</label>

                                <?php echo form_error('landed_date'); ?>
                            </div>
                        </div>


                        <div class="col-sm-2">
                            <div class="form-group floating">
                                <input type="text" class="form-control floating text-uppercase" id="cust_delno" name="cust_delno" value=" <?php echo $edit_data[0]->cust_delno; ?>">
                                <label for="usr">Cust Del No.</label>
                                <?php echo form_error('cust_delno'); ?>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group floating" data-date="<?php echo date('d-m-Y'); ?>" data-date-format="dd-mm-yyyy" data-date-viewmode="years">
                                <input type="text" id="denote_date" name="denote_date" class="form-control floating date-input" value="<?php echo $edit_data[0]->denote_date; ?>" />
                                <label for="datedate">Delnote Date</label>

                                <?php echo form_error('denote_date'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group floating">
                                <input type="text" class="form-control floating" id="customer_markup" name="customer_markup" value="<?php echo $edit_data[0]->customer_markup; ?>" readonly>
                                <label for="usr" class="disable">Cust Markup</label>
                                <?php echo form_error('customer_markup'); ?>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group floating">
                                <input type="text" class="form-control floating" id="exchange_rate" name="exchange_rate" value="<?php echo $edit_data[0]->exchange_rate; ?>" readonly>
                                <label for="usr" class="disable">Exchange Rate</label>
                                <?php echo form_error('exchange_rate'); ?>
                            </div>
                        </div>

                        <div class="col-sm-2"></div>


                        <div class="col-sm-2">
                            <div class="form-group floating">
                                <input type="text" class="form-control floating" id="ttl_sp" name="ttl_sp" value="<?php echo $edit_data[0]->ttl_sp; ?>" readonly>
                                <label for="usr" class="disable">Total SP(£)</label>
                                <!-- <?php echo form_error('ttl_sp'); ?> -->
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group floating">
                                <input type="text" class="form-control floating hide" id="ttl_qty" name="ttl_qty" value="<?php echo $edit_data[0]->ttl_qty; ?>" readonly>
                                <label for="usr" class="disable">Total QTY</label>
                                <!-- <?php echo form_error('ttl_qty'); ?> -->
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group floating">
                                <input type="hidden" class="form-control floating hide" id="ttl_cp" name="ttl_cp" value="<?php echo $edit_data[0]->ttl_cp; ?>" readonly>
                                <input type="text" class="form-control floating hide" id="profit_margin" name="profit_margin" value="<?php echo $edit_data[0]->profit_margin; ?>" readonly>
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
                                    <th>BIN No</th>
                                    <th>HS-CODE</th>
                                    <th>BARCODE</th>
                                    <th>ACTION</th>
                                </tr>
                                <?php foreach ($edit_data as $key => $value) {
                                    $rowQTY = $value->product_qty;
                                    $rowSP = $value->selling_price;
                                    $rowCP = $value->cost_price;
                                    $delid = $value->id_del_product;
                                ?>
                                    <tr id="details">
                                        <td>
                                            <input type="hidden" class="form-control" readonly name="id_del_product[]" value="<?= $value->id_del_product; ?>">
                                            <input type="hidden" class="form-control" readonly name="single_mix[]" id='single_mix-<?= $delid; ?>' value="<?= $value->single_mix; ?>">
                                            <input type="hidden" class="form-control " readonly name="product_category[]" id='product_category-<?= $delid; ?>' value="<?= $value->id_category; ?>">
                                            <input type="hidden" class="form-control" readonly name="is_barcode_recall[]" id='is_barcode_recall-<?= $delid; ?>' value="<?= $value->is_barcode_recall; ?>">
                                            <input type="hidden" class="form-control" readonly name="internal_ref[]" id='internal_ref-<?= $delid; ?>' value="<?= $value->internal_ref; ?>">
                                            <input type="text" class="form-control " readonly name="supplier_ref[]" id='supplier_ref-<?= $delid; ?>' value="<?= $value->supplier_ref; ?>">
                                        </td>
                                        <td><input type="text" class="form-control sup_ref" readonly name="supplier_ref[]" id='supplier_ref-<?= $delid; ?>' value="<?= $value->supplier_ref; ?>"></td>
                                        <td><input type="text" class="form-control prdqty" readonly name="product_qty[]" id='product_qty-<?= $delid; ?>' value="<?= $value->product_qty; ?>"></td>
                                        <td><input type="text" class="form-control p_euro" readonly name="price_euro[]" id='price_euro-<?= $delid; ?>' value="<?= $value->price_euro; ?>"></td>
                                        <td><input type="text" class="form-control costprice" readonly name="cost_price[]" id='cost_price-<?= $delid; ?>' value="<?= $value->cost_price; ?>"></td>
                                        <td><input type="text" class="form-control sprice" readonly name="selling_price[]" id='selling_price-<?= $delid; ?>' value="<?= $value->selling_price; ?>"></td>
                                        <td><input type="text" class="form-control" readonly name="bin_number[]" id='bin_number-<?= $delid; ?>' value="<?= $value->bin_number; ?>"></td>
                                        <td><input type="text" class="form-control" readonly name="hs_code[]" id='hs_code-<?= $delid; ?>' value="<?= $value->hs_code; ?>"></td>
                                        <td><input type="text" class="form-control" readonly name="product_barcode[]" id='product_barcode-<?= $delid; ?>' value="<?= $value->product_barcode; ?>"></td>
                                        <!-- <td><a href="#!" class="btn-danger btn-circle btn-sm text-white btn_remove_row" onclick="removeROW(this);"><i class="fas fa-trash-alt"></i></a>
                                            <a href="#!" class="btn-primary btn-circle btn-sm text-white btn_remove_row" title="Print Barcode" onclick="printBarcode(<?= $value->id_del_product; ?>,<?= $value->product_barcode; ?>);"><i class="fa fa-barcode"></i></a>
                                        </td> -->
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" data-display="static" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa fa-tasks" aria-hidden="true"></i> <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-lg-right" aria-labelledby="dropdownMenuButton1">
                                                    <li><a class="dropdown-item showSingle" target="2" href="#" onclick="editDenoteProduct(<?= $value->id_del_product; ?>)" data-toggle="modal" data-target="#myModal2"> <i class="fa fa-pencil-square" aria-hidden="true"></i> Edit</a></li>
                                                    <li><a class="dropdown-item" href="#"> <i class="fa fa-eye" aria-hidden="true"></i> View </a></li>
                                                    <li><a class="dropdown-item" href="#" onclick="displayVariantsPopup(<?= $value->id_del_product; ?>)" data-toggle="modal" data-target=".bd-example-modal-lg"> <i class="fa fa-bars" aria-hidden="true"></i> Add Variants </a></li>
                                                    <li><a class="dropdown-item" href="#"> <i class="fa fa-barcode" aria-hidden="true"></i> Print Barcode </a></li>
                                                    <li><a class="dropdown-item" href="#" onclick="removeROW(this, '<?php echo $rowQTY ?>', '<?php echo $rowSP ?>', '<?php echo $rowCP ?>')"> <i class="fas fa-trash-alt"></i> Delete </a></li>
                                                    <!-- <li><a class="dropdown-item" href="#" onclick="removeROW(currentRow);"></i> Delete </a></li> -->
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </div>
                        <!-- end of product block -->
                    </div>

                </div>
                <div class="row">
                    <div class="col-sm-12" align="center">
                        <button type="button" id="addDelnoteProducts" class="btn btn-success btn-sm btn-icon-split showSingle" target="1" data-toggle="modal" data-target="#myModal2" onclick="btnAddProduct()">
                            <span class="icon text-white-50 "><i class="fas fa-plus"></i></span><span class="text">Add Products</span>
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
            <!-- End  Delnote Body -->
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

<!-- Add Modal  Products -->
<div class="modal left fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
    <div class="modal-dialog" role="document" style="width: 425px !important;">
        <div class="modal-content">

            <div class="modal-header-product">
                <h4 class="modal-title targetHeading" id="myModalLabel1">Add Product</h4>
                <h4 class="modal-title targetHeading" id="myModalLabel2">Update Product</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>

            <div class="modal-body">
                <div class="Add Attributes"><br />
                    <div class="row" style="margin-bottom: 15px">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="single_mix" class="lable_dropdown">Single/Mix</label>
                                <select id="single_mix" name="single_mix" class="form-control custom-select">
                                    <option value="">Select </option>
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
                                <label for="usr" class="supplier_ref " id="disable">Supplier Ref</label>
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
                                <input type="number" class="form-control floating" id="product_qty" name="product_qty">
                                <label for="qty" class="product_qty">Quantity</label>
                                <!-- <?php echo form_error('product_qty'); ?> -->
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group-product floating">
                                <input type="text" class="form-control floating" id="price_euro" name="price_euro" onkeyup="totalCpPound()">
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
                                <input type="hidden" class="form-control floating" id="currentRow" name="currentRow" readonly>
                                <label for="brd" class="product_barcode disable_product">Barcode</label>
                                <!-- <?php echo form_error('product_barcode'); ?> -->
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 text-center">
                        <!-- add product -->
                        <button type="button" id="div1" onclick="btnAddNewProduct()" class="btn btn-success btn-sm btn-icon-split targetDiv">
                            <span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text">Add</span>
                        </button>

                        <!-- edit product -->
                        <button type="button" id="div2" onclick="btnUpdateProduct()" class="btn btn-success btn-sm btn-icon-split targetDiv">
                            <span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text">Update</span>
                        </button>



                    </div>
                </div>
            </div>

        </div><!-- modal-content -->
    </div><!-- modal-dialog -->
</div><!-- modal -->


<!-- Variant Modal  -->
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="myLargeModalLabel" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Variants</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="clearAllRows()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-3" style="display:none !important">
                            <div class="form-group">
                                <label for="color" class="lable_dropdown">Color:</label>
                                <select class="form-control attributes " id="color">
                                    <option value="">Please Select</option>
                                    <?php foreach ($color_list as $key => $colors) {
                                        $attVal = ($colors->values); ?>
                                        <option data-color_values='<?= $attVal; ?>' value="<?= $colors->attributes_id ?>"><?= $colors->values ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3" style="display:none !important">
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
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="lable_dropdown">Color</label>
                                <select class="form-control " id="colors_value" data-show-subtext="true" data-live-search="true" onchange="barcodeGeneratorAndCheckExist()">
                                    <option value="">Please Select</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="lable_dropdown">Size</label>
                                <select class="form-control " id="sizes_value" data-show-subtext="true" data-live-search="true">
                                    <option value="">Please Select</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="lable_dropdown">Quantity</label>
                                <input type="number" class="form-control " id="variant_quantity" name="variant_quantity">
                                <input type="hidden" class="form-control " id="id_variant" name="id_variant">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="lable_dropdown">Barcode</label>
                                <input type="text" class="form-control floating" id="variant_barcode" name="variant_barcode" readonly>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="lable_dropdown">Action </label>
                                <div>
                                    <input type="hidden" id="product_id" name="product_id" />
                                    <button type="button" class="form-control floating btn btn-success btn-size" id="saveVariant" onclick="addProductVariants()"><i class="fa fa-save"></i></button>
                                    <button type="button" class="form-control floating btn btn-primary btn-size" id="editVarinatBtn" onclick="updateProductVariants()"><i class="fa fa-save"></i></button>
                                    <button type="button" class="form-control floating btn btn-danger btn-size" onclick="clearFields()"><i class="fa fa-undo" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Product Variant block -->
                <div class="col-sm-12 table-wrapper-scroll-y my-custom-scrollbar">
                    <table class="table product_table" border="1" style="border-color: #CCC;">
                        <thead>
                            <tr style="background-color: #5d83d9;color: white;">
                                <th width="20%">COLOR</th>
                                <th width="20%">SIZE</th>
                                <th width="15%">QTY</th>
                                <th width="25%">BARCODE</th>
                                <th width="20%" style="display: none;">ID</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody id="tbl_variants"></tbody>
                    </table>
                </div>
                <!-- end of product Variant block -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="clearAllRows()">Close</button>
            </div>

        </div>

    </div>
</div>
<!-- End Variant modal -->
<!-- Barocde Modal -->
<div class="modal" id="myModaBarcode" data-backdrop="static">
    <div class="modal-dialog modal-style">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Print Barcode</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <div class="form-group floating" style="margin-bottom: -2rem !important;">
                    <input type="hidden" class="form-control floating" id="varinatid" name="varinatid" value="">
                    <input type="hidden" class="form-control floating" id="variantbrd" name="variantbrd" value="">
                    <input type="number" class="form-control floating" id="brdqty" name="brdqty" value="">
                    <label for="brdqty">Barcode QTY</label>
                    <!--  -->
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-primary" onclick="goToBarcodePage()">Submit</a>
            </div>
        </div>
    </div>
</div>
<!-- End barcode Modal-->




<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js" type="text/javascript"></script>
<script>
    $(function() {
        $('.selectpicker').selectpicker();
        $('#denote_date, #landed_date').datepicker({
            format: "dd-mm-yyyy"
        });

        $("#product_supplier").prop("disabled", true);
    });
</script>
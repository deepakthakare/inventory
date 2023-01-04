<div class="mb-4">
    <?= $this->breadcrumbs->show(); ?>
</div>
<div class="row">
    <div class="col-xl-12 col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary"><?= $button; ?> Supplier</h6>
            </div>
            <!-- Card Body -->
            <form action="<?= $action; ?>" method="post">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name">Name <span style="color: red;">*</span></label>
                                <input type="text" class="form-control" id="" name="name" value="<?php echo $name; ?>">
                                <?php echo form_error('name'); ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="company">Company <span style="color: red;">*</span></label>
                                <input type="text" class="form-control" id="" name="company" value="<?php echo $company; ?>">
                                <?php echo form_error('company'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="sup_code">Supplier Code <span style="color: red;">*</span></label>
                                <input type="text" class="form-control" id="" name="supplier_code" value="<?php echo $supplier_code; ?>">
                                <?php echo form_error('supplier_code'); ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="company">Email <span style="color: red;">*</span></label>
                                <input type="text" class="form-control" id="" name="email" value="<?php echo $email; ?>">
                                <?php echo form_error('email'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="sup_code">Description <span style="color: red;">*</span></label>
                                <input type="text" class="form-control" id="" name="description" value="<?php echo $description; ?>">
                                <?php echo form_error('description'); ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="company">Fax</label>
                                <input type="text" class="form-control" id="" name="fax" value="<?php echo $fax; ?>">
                                <!-- <?php echo form_error('fax'); ?> -->
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="sup_code">Phone</label>
                                <input type="text" class="form-control" id="" name="phone" value="<?php echo $phone; ?>">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="company">Mobile <span style="color: red;">*</span></label>
                                <input type="text" class="form-control" id="" name="mobile" value="<?php echo $mobile; ?>">
                                <?php echo form_error('mobile'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="sup_code">Address <span style="color: red;">*</span></label>
                                <input type="text" class="form-control" id="" name="address" value="<?php echo $address; ?>">
                                <?php echo form_error('address'); ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="company">Address 2</label>
                                <input type="text" class="form-control" id="" name="address_two" value="<?php echo $address_two; ?>">
                               
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="zip">ZipCode</label>
                                <input type="text" class="form-control" id="" name="zipcode" value="<?php echo $zipcode; ?>">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="city">City</label>
                                <input type="text" class="form-control" id="" name="city" value="<?php echo $city; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="country">Country <span style="color: red;">*</span></label>
                                <select class="form-control selectpicker" name="supplier_country" id="supplier_country" data-show-subtext="true" data-live-search="true">
                                    <option value="">Please Select</option>
                                    <?php foreach ($country_list as $key => $country) { ?>
                                        <option value="<?= $country->id ?>"><?= $country->name ?></option>
                                    <?php } ?>
                                </select>
                                <?php echo form_error('supplier_country') ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="markup">Customer Markup <span style="color: red;">*</span></label>
                                <input type="text" class="form-control" id="" name="customer_markup" value="<?php echo $customer_markup; ?>">
                                <?php echo form_error('customer_markup') ?>       
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="currency">Currency <span style="color: red;">*</span></label>
                                <select class="form-control selectpicker" name="supplier_currency" id="supplier_currency" data-show-subtext="true" data-live-search="true">
                                    <option value="">Please Select</option>
                                    <?php foreach ($currency_list as $key => $currency) { ?>
                                        <option value="<?= $currency->id ?>"><?= $currency->name ?></option>
                                    <?php } ?>
                                </select>
                                <?php echo form_error('supplier_currency') ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="markup_pn"> Markup PN</label>
                                <input type="text" class="form-control" id="" name="markup_pn" value="<?php echo $markup_pn; ?>">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="id_supplier" value="<?php echo $id_supplier; ?>" />
                </div>
                <div class="card-footer">
                    <a href="<?= admin_url('suppliers') ?>" class="btn btn-danger btn-sm btn-icon-split">
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


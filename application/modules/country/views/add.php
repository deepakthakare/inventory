<div class="mb-4">
    <?= $this->breadcrumbs->show(); ?>
</div>
<?php if (in_array('createBrand', $user_permission)) { ?>
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary"><?= $button; ?> Country</h6>
                </div>
                <!-- Card Body -->
                <form action="<?= $action; ?>" method="post">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="name">Name:</label>
                                    <input type="text" class="form-control" id="" name="name" value="<?php echo $name; ?>">
                                    <?php echo form_error('name'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group input-group">
                                    <label for="cust_markup">Customer Markup:</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" id="" name="cust_markup" value="<?php echo $cust_markup; ?>" aria-describedby="basic-percentage">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-percentage">%</span>
                                        </div>
                                        <?php echo form_error('cust_markup'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group input-group">
                                    <label for="usr">Markup PN:</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" id="" name="markup_pn" value="<?php echo $markup_pn; ?>" aria-describedby="basic-pound">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-pound">£</span>
                                        </div>
                                        <?php echo form_error('markup_pn'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="cop">COP:</label>
                                    <input type="text" class="form-control" id="" name="cop" value="<?php echo $cop; ?>">
                                    <?php echo form_error('cop'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group input-group">
                                    <label for="cop">COP:</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" id="" name="cop_percentage" value="<?php echo $cop_percentage; ?>" aria-describedby="basic-percentage">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-percentage">%</span>
                                        </div>
                                        <?php echo form_error('cop_percentage'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>" />

                    </div>
                    <div class="card-footer">
                        <a href="<?= admin_url('country') ?>" class="btn btn-danger btn-sm btn-icon-split">
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
<?php } else { ?>
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-danger">You don't have permission.</h6>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
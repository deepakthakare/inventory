<?php $readonly = ($button == 'Update') ? 'disabled' : ''; ?>
<div class="mb-4">
    <?= $this->breadcrumbs->show(); ?>
</div>
<div class="row">
    <div class="col-xl-12 col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary"><?= $button; ?> User</h6>
            </div>
            <!-- Card Body -->
            <form action="<?= $action; ?>" method="post">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Groups:</label>
                                <select class="form-control" name="group_name" id="group_name">
                                    <option value="">Please Select Group Name</option>
                                    <?php foreach ($groups_list as $key => $group) { ?>
                                        <?php if ($button != 'Update') { ?>
                                            <option value="<?= $group['id'] ?>"><?= $group["group_name"] ?></option> <?php } else { ?>
                                            <option value="<?= $group['id'] ?>" <?php if ($group_id === $group['id']) {
                                                                                                                            echo 'selected';
                                                                                                                        } ?>>
                                                <?= $group['group_name'] ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                                <?php echo form_error('group_name') ?>
                            </div>
                            <div class="form-group">
                                <label>Stores:</label>
                                <select class="form-control" name="store_name" id="store_name" <?= $readonly ?>>
                                    <option value="">Please Select Store Name</option>
                                    <?php foreach ($stores_list as $key => $store) { ?>
                                        <?php if ($button != 'Update') {; ?>
                                            <option value="<?= $store->id ?>"><?= $store->name ?></option> <?php } else { ?>
                                            <option value="<?= $store->id ?>" <?php if ($store_id === $store->id) {
                                                                                                                echo 'selected';
                                                                                                            } ?>>
                                                <?= $store->name ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                                <?php echo form_error('store_name') ?>
                            </div>
                            <div class="form-group">
                                <label for="usr">Username:</label>
                                <input type="text" class="form-control" id="" name="username" placeholder="Username" autocomplete="off" value="<?php echo $username; ?>" <?= $readonly ?>>
                                <?php echo form_error('username'); ?>
                            </div>
                            <div class="form-group">
                                <label for="fname">First Name:</label>
                                <input type="text" class="form-control" id="" name="fname" placeholder="First Name" autocomplete="off" value="<?php echo $fname; ?>">
                                <?php echo form_error('fname'); ?>
                            </div>
                            <div class="form-group">
                                <label for="lname">Last Name:</label>
                                <input type="text" class="form-control" id="" name="lname" placeholder="Last ame" autocomplete="off" value="<?php echo $lname; ?>">
                                <?php echo form_error('lname'); ?>
                            </div>


                            <?php if ($button == 'Update') {; ?>
                                <div class="form-group">
                                    <div class="alert alert-info alert-dismissible" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        Leave the password field empty if you don't want to change.
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="usr">Password:</label>
                                    <input type="password" class="form-control" id="" name="password" placeholder="Password" autocomplete="off">
                                    <?php echo form_error('password'); ?>
                                </div>
                            <?php } else { ?>
                                <div class="form-group">
                                    <label for="usr">Password:</label>
                                    <input type="password" class="form-control" id="" name="password" placeholder="Password" autocomplete="off">
                                    <?php echo form_error('password'); ?>
                                </div>

                            <?php }  ?>
                        </div>
                    </div>
                    <input type="hidden" name="login_id" value="<?php echo $login_id; ?>" />

                </div>
                <div class="card-footer">
                    <a href="<?= admin_url('users') ?>" class="btn btn-danger btn-sm btn-icon-split">
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
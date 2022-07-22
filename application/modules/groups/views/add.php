<div class="mb-4">
    <?= $this->breadcrumbs->show(); ?>
</div>
<div class="row">
    <?php if ($this->session->flashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo $this->session->flashdata('success'); ?>
        </div>
    <?php elseif ($this->session->flashdata('error')) : ?>
        <div class="alert alert-error alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>
    <div class="col-xl-12 col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary"><?= $button; ?> Groups</h6>
            </div>
            <form action="<?= $action; ?>" method="post">
                <div class="card-body">

                    <?php echo validation_errors(); ?>

                    <div class="form-group">
                        <label for="group_name">Group Name</label>
                        <input type="text" class="form-control" id="group_name" name="group_name" placeholder="Enter group name">
                    </div>
                    <div class="form-group">
                        <label for="permission">Permission</label>

                        <table class="table ">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="select_all" /></li>
                                    </th>
                                    <th>Create</th>
                                    <th>Update</th>
                                    <th>View</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Users</td>
                                    <td><input type="checkbox" name="permission[]" id="permission" value="createUser" class="minimal"></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" value="updateUser" class="minimal"></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" value="viewUser" class="minimal"></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" value="deleteUser" class="minimal"></td>
                                </tr>
                                <tr>
                                    <td>Groups</td>
                                    <td><input type="checkbox" name="permission[]" id="permission" value="createGroup" class="minimal"></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" value="updateGroup" class="minimal"></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" value="viewGroup" class="minimal"></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" value="deleteGroup" class="minimal"></td>
                                </tr>
                                <tr>
                                    <td>Brands</td>
                                    <td><input type="checkbox" name="permission[]" id="permission" value="createBrand" class="minimal"></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" value="updateBrand" class="minimal"></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" value="viewBrand" class="minimal"></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" value="deleteBrand" class="minimal"></td>
                                </tr>
                                <tr>
                                    <td>Category</td>
                                    <td><input type="checkbox" name="permission[]" id="permission" value="createCategory" class="minimal"></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" value="updateCategory" class="minimal"></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" value="viewCategory" class="minimal"></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" value="deleteCategory" class="minimal"></td>
                                </tr>
                                <tr>
                                    <td>Stores</td>
                                    <td><input type="checkbox" name="permission[]" id="permission" value="createStore" class="minimal"></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" value="updateStore" class="minimal"></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" value="viewStore" class="minimal"></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" value="deleteStore" class="minimal"></td>
                                </tr>
                                <tr>
                                    <td>Attributes</td>
                                    <td><input type="checkbox" name="permission[]" id="permission" value="createAttribute" class="minimal"></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" value="updateAttribute" class="minimal"></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" value="viewAttribute" class="minimal"></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" value="deleteAttribute" class="minimal"></td>
                                </tr>
                                <tr>
                                    <td>Products</td>
                                    <td><input type="checkbox" name="permission[]" id="permission" value="createProduct" class="minimal"></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" value="updateProduct" class="minimal"></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" value="viewProduct" class="minimal"></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" value="deleteProduct" class="minimal"></td>
                                </tr>
                                <tr>
                                    <td>Orders</td>
                                    <td><input type="checkbox" name="permission[]" id="permission" value="createOrder" class="minimal"></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" value="updateOrder" class="minimal"></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" value="viewOrder" class="minimal"></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" value="deleteOrder" class="minimal"></td>
                                </tr>
                                <tr>
                                    <td>Inventory</td>
                                    <td><input type="checkbox" name="permission[]" id="permission" value="createInventory" class="minimal"></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" value="updateInventory" class="minimal"></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" value="viewInventory" class="minimal"></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" value="deleteInventory" class="minimal"></td>
                                </tr>
                                <tr>
                                    <td>Reports</td>
                                    <td> - </td>
                                    <td> - </td>
                                    <td><input type="checkbox" name="permission[]" id="permission" value="viewReports" class="minimal"></td>
                                    <td> - </td>
                                </tr>
                                <tr>
                                    <td>Statistics</td>
                                    <td> - </td>
                                    <td> - </td>
                                    <td><input type="checkbox" name="permission[]" id="permission" value="viewStatistics" class="minimal"></td>
                                    <td> - </td>
                                </tr>
                                <!-- <tr>
                                    <td>Company</td>
                                    <td> - </td>
                                    <td><input type="checkbox" name="permission[]" id="permission" value="updateCompany" class="minimal"></td>
                                    <td> - </td>
                                    <td> - </td>
                                </tr>
                                <tr>
                                    <td>Profile</td>
                                    <td> - </td>
                                    <td> - </td>
                                    <td><input type="checkbox" name="permission[]" id="permission" value="viewProfile" class="minimal"></td>
                                    <td> - </td>
                                </tr>
                                <tr>
                                    <td>Setting</td>
                                    <td>-</td>
                                    <td><input type="checkbox" name="permission[]" id="permission" value="updateSetting" class="minimal"></td>
                                    <td> - </td>
                                    <td> - </td>
                                </tr> -->
                            </tbody>
                        </table>

                    </div>
                </div>
                <!-- /.box-body -->
                <div class="card-footer">
                    <a href="<?= admin_url('groups') ?>" class="btn btn-danger btn-sm btn-icon-split">
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#mainGroupNav").addClass('active');
        $("#addGroupNav").addClass('active');


        $('#select_all').on('click', function() {
            if (this.checked) {
                $('.minimal').each(function() {
                    this.checked = true;
                });
            } else {
                $('.minimal').each(function() {
                    this.checked = false;
                });
            }
        });

        $('.minimal').on('click', function() {
            if ($('.minimal:checked').length == $('.minimal').length) {
                $('#select_all').prop('checked', true);
            } else {
                $('#select_all').prop('checked', false);
            }
        });
    });
</script>
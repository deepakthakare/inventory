<div class="mb-4">
    <?= $this->breadcrumbs->show(); ?>
</div>

<div class="row">
    <div class="col-xl-12 col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Update Groups</h6>
            </div>

            <form role="form" action="<?php base_url('groups/edit') ?>" method="post">
                <div class="card-body">

                    <?php echo validation_errors(); ?>

                    <div class="form-group col-lg-6">
                        <label for="group_name"><b>Group Name</b></label>
                        <input type="text" class="form-control" id="group_name" name="group_name" placeholder="Enter group name" value="<?php echo $group_data['group_name']; ?>">
                    </div>
                    <div class="form-group col-lg-12">
                        <label for="permission"><b>Permission</b></label>

                        <?php $serialize_permission = unserialize($group_data['permission']); ?>

                        <table class="table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Create</th>
                                    <th>Update</th>
                                    <th>View</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Users</td>
                                    <td><input type="checkbox" class="minimal" name="permission[]" id="permission" class="minimal" value="createUser" <?php if ($serialize_permission) {
                                                                                                                                                            if (in_array('createUser', $serialize_permission)) {
                                                                                                                                                                echo "checked";
                                                                                                                                                            }
                                                                                                                                                        } ?>></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="updateUser" <?php
                                                                                                                                        if ($serialize_permission) {
                                                                                                                                            if (in_array('updateUser', $serialize_permission)) {
                                                                                                                                                echo "checked";
                                                                                                                                            }
                                                                                                                                        }
                                                                                                                                        ?>></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="viewUser" <?php
                                                                                                                                    if ($serialize_permission) {
                                                                                                                                        if (in_array('viewUser', $serialize_permission)) {
                                                                                                                                            echo "checked";
                                                                                                                                        }
                                                                                                                                    }
                                                                                                                                    ?>></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="deleteUser" <?php
                                                                                                                                        if ($serialize_permission) {
                                                                                                                                            if (in_array('deleteUser', $serialize_permission)) {
                                                                                                                                                echo "checked";
                                                                                                                                            }
                                                                                                                                        }
                                                                                                                                        ?>></td>
                                </tr>
                                <tr>
                                    <td>Groups</td>
                                    <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="createGroup" <?php
                                                                                                                                        if ($serialize_permission) {
                                                                                                                                            if (in_array('createGroup', $serialize_permission)) {
                                                                                                                                                echo "checked";
                                                                                                                                            }
                                                                                                                                        }
                                                                                                                                        ?>></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="updateGroup" <?php
                                                                                                                                        if ($serialize_permission) {
                                                                                                                                            if (in_array('updateGroup', $serialize_permission)) {
                                                                                                                                                echo "checked";
                                                                                                                                            }
                                                                                                                                        }
                                                                                                                                        ?>></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="viewGroup" <?php
                                                                                                                                        if ($serialize_permission) {
                                                                                                                                            if (in_array('viewGroup', $serialize_permission)) {
                                                                                                                                                echo "checked";
                                                                                                                                            }
                                                                                                                                        }
                                                                                                                                        ?>></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="deleteGroup" <?php
                                                                                                                                        if ($serialize_permission) {
                                                                                                                                            if (in_array('deleteGroup', $serialize_permission)) {
                                                                                                                                                echo "checked";
                                                                                                                                            }
                                                                                                                                        }
                                                                                                                                        ?>></td>
                                </tr>
                                <tr>
                                    <td>Brands</td>
                                    <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="createBrand" <?php if ($serialize_permission) {
                                                                                                                                            if (in_array('createBrand', $serialize_permission)) {
                                                                                                                                                echo "checked";
                                                                                                                                            }
                                                                                                                                        } ?>></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="updateBrand" <?php if ($serialize_permission) {
                                                                                                                                            if (in_array('updateBrand', $serialize_permission)) {
                                                                                                                                                echo "checked";
                                                                                                                                            }
                                                                                                                                        } ?>></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="viewBrand" <?php if ($serialize_permission) {
                                                                                                                                            if (in_array('viewBrand', $serialize_permission)) {
                                                                                                                                                echo "checked";
                                                                                                                                            }
                                                                                                                                        } ?>></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="deleteBrand" <?php if ($serialize_permission) {
                                                                                                                                            if (in_array('deleteBrand', $serialize_permission)) {
                                                                                                                                                echo "checked";
                                                                                                                                            }
                                                                                                                                        } ?>></td>
                                </tr>
                                <tr>
                                    <td>Vendors</td>
                                    <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="createVendors" <?php if ($serialize_permission) {
                                                                                                                                            if (in_array('createVendors', $serialize_permission)) {
                                                                                                                                                echo "checked";
                                                                                                                                            }
                                                                                                                                        } ?>></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="updateVendors" <?php if ($serialize_permission) {
                                                                                                                                            if (in_array('updateVendors', $serialize_permission)) {
                                                                                                                                                echo "checked";
                                                                                                                                            }
                                                                                                                                        } ?>></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="viewVendors" <?php if ($serialize_permission) {
                                                                                                                                            if (in_array('viewVendors', $serialize_permission)) {
                                                                                                                                                echo "checked";
                                                                                                                                            }
                                                                                                                                        } ?>></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="deleteVendors" <?php if ($serialize_permission) {
                                                                                                                                            if (in_array('deleteVendors', $serialize_permission)) {
                                                                                                                                                echo "checked";
                                                                                                                                            }
                                                                                                                                        } ?>></td>
                                </tr>
                                <tr>
                                    <td>Warehouse</td>
                                    <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="createWarehouse" <?php if ($serialize_permission) {
                                                                                                                                            if (in_array('createWarehouse', $serialize_permission)) {
                                                                                                                                                echo "checked";
                                                                                                                                            }
                                                                                                                                        } ?>></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="updateWarehouse" <?php if ($serialize_permission) {
                                                                                                                                            if (in_array('updateWarehouse', $serialize_permission)) {
                                                                                                                                                echo "checked";
                                                                                                                                            }
                                                                                                                                        } ?>></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="viewWarehouse" <?php if ($serialize_permission) {
                                                                                                                                            if (in_array('viewWarehouse', $serialize_permission)) {
                                                                                                                                                echo "checked";
                                                                                                                                            }
                                                                                                                                        } ?>></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="deleteWarehouse" <?php if ($serialize_permission) {
                                                                                                                                            if (in_array('deleteWarehouse', $serialize_permission)) {
                                                                                                                                                echo "checked";
                                                                                                                                            }
                                                                                                                                        } ?>></td>
                                </tr>
                                <tr>
                                    <td>Category</td>
                                    <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="createCategory" <?php if ($serialize_permission) {
                                                                                                                                                if (in_array('createCategory', $serialize_permission)) {
                                                                                                                                                    echo "checked";
                                                                                                                                                }
                                                                                                                                            } ?>></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="updateCategory" <?php if ($serialize_permission) {
                                                                                                                                                if (in_array('updateCategory', $serialize_permission)) {
                                                                                                                                                    echo "checked";
                                                                                                                                                }
                                                                                                                                            } ?>></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="viewCategory" <?php if ($serialize_permission) {
                                                                                                                                            if (in_array('viewCategory', $serialize_permission)) {
                                                                                                                                                echo "checked";
                                                                                                                                            }
                                                                                                                                        } ?>></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="deleteCategory" <?php if ($serialize_permission) {
                                                                                                                                                if (in_array('deleteCategory', $serialize_permission)) {
                                                                                                                                                    echo "checked";
                                                                                                                                                }
                                                                                                                                            } ?>></td>
                                </tr>
                                <tr>
                                    <td>Stores</td>
                                    <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="createStore" <?php if ($serialize_permission) {
                                                                                                                                            if (in_array('createStore', $serialize_permission)) {
                                                                                                                                                echo "checked";
                                                                                                                                            }
                                                                                                                                        } ?>></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="updateStore" <?php if ($serialize_permission) {
                                                                                                                                            if (in_array('updateStore', $serialize_permission)) {
                                                                                                                                                echo "checked";
                                                                                                                                            }
                                                                                                                                        } ?>></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="viewStore" <?php if ($serialize_permission) {
                                                                                                                                            if (in_array('viewStore', $serialize_permission)) {
                                                                                                                                                echo "checked";
                                                                                                                                            }
                                                                                                                                        } ?>></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="deleteStore" <?php if ($serialize_permission) {
                                                                                                                                            if (in_array('deleteStore', $serialize_permission)) {
                                                                                                                                                echo "checked";
                                                                                                                                            }
                                                                                                                                        } ?>></td>
                                </tr>
                                <tr>
                                    <td>Attributes</td>
                                    <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="createAttribute" <?php if ($serialize_permission) {
                                                                                                                                                if (in_array('createAttribute', $serialize_permission)) {
                                                                                                                                                    echo "checked";
                                                                                                                                                }
                                                                                                                                            } ?>></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="updateAttribute" <?php if ($serialize_permission) {
                                                                                                                                                if (in_array('updateAttribute', $serialize_permission)) {
                                                                                                                                                    echo "checked";
                                                                                                                                                }
                                                                                                                                            } ?>></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="viewAttribute" <?php if ($serialize_permission) {
                                                                                                                                                if (in_array('viewAttribute', $serialize_permission)) {
                                                                                                                                                    echo "checked";
                                                                                                                                                }
                                                                                                                                            } ?>></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="deleteAttribute" <?php if ($serialize_permission) {
                                                                                                                                                if (in_array('deleteAttribute', $serialize_permission)) {
                                                                                                                                                    echo "checked";
                                                                                                                                                }
                                                                                                                                            } ?>></td>
                                </tr>
                                <tr>
                                    <td>Products</td>
                                    <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="createProduct" <?php if ($serialize_permission) {
                                                                                                                                                if (in_array('createProduct', $serialize_permission)) {
                                                                                                                                                    echo "checked";
                                                                                                                                                }
                                                                                                                                            } ?>></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="updateProduct" <?php if ($serialize_permission) {
                                                                                                                                                if (in_array('updateProduct', $serialize_permission)) {
                                                                                                                                                    echo "checked";
                                                                                                                                                }
                                                                                                                                            } ?>></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="viewProduct" <?php if ($serialize_permission) {
                                                                                                                                            if (in_array('viewProduct', $serialize_permission)) {
                                                                                                                                                echo "checked";
                                                                                                                                            }
                                                                                                                                        } ?>></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="deleteProduct" <?php if ($serialize_permission) {
                                                                                                                                                if (in_array('deleteProduct', $serialize_permission)) {
                                                                                                                                                    echo "checked";
                                                                                                                                                }
                                                                                                                                            } ?>></td>
                                </tr>
                                <tr>
                                    <td>Tiktok Products</td>
                                    <td> - </td>
                                    <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="updateTiktok" <?php if ($serialize_permission) {
                                                                                                                                            if (in_array('updateTiktok', $serialize_permission)) {
                                                                                                                                                echo "checked";
                                                                                                                                            }
                                                                                                                                        } ?>></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="viewTiktok" <?php if ($serialize_permission) {
                                                                                                                                            if (in_array('viewTiktok', $serialize_permission)) {
                                                                                                                                                echo "checked";
                                                                                                                                            }
                                                                                                                                        } ?>></td>
                                    <td> - </td>
                                </tr>
                                <tr>
                                    <td>Orders</td>
                                    <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="createOrder" <?php if ($serialize_permission) {
                                                                                                                                            if (in_array('createOrder', $serialize_permission)) {
                                                                                                                                                echo "checked";
                                                                                                                                            }
                                                                                                                                        } ?>></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="updateOrder" <?php if ($serialize_permission) {
                                                                                                                                            if (in_array('updateOrder', $serialize_permission)) {
                                                                                                                                                echo "checked";
                                                                                                                                            }
                                                                                                                                        } ?>></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="viewOrder" <?php if ($serialize_permission) {
                                                                                                                                            if (in_array('viewOrder', $serialize_permission)) {
                                                                                                                                                echo "checked";
                                                                                                                                            }
                                                                                                                                        } ?>></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="deleteOrder" <?php if ($serialize_permission) {
                                                                                                                                            if (in_array('deleteOrder', $serialize_permission)) {
                                                                                                                                                echo "checked";
                                                                                                                                            }
                                                                                                                                        } ?>></td>
                                </tr>
                                <tr>
                                    <td>Inventory</td>
                                    <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="createInventory" <?php if ($serialize_permission) {
                                                                                                                                                if (in_array('createInventory', $serialize_permission)) {
                                                                                                                                                    echo "checked";
                                                                                                                                                }
                                                                                                                                            } ?>></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="updateInventory" <?php if ($serialize_permission) {
                                                                                                                                                if (in_array('updateInventory', $serialize_permission)) {
                                                                                                                                                    echo "checked";
                                                                                                                                                }
                                                                                                                                            } ?>></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="viewInventory" <?php if ($serialize_permission) {
                                                                                                                                                if (in_array('viewInventory', $serialize_permission)) {
                                                                                                                                                    echo "checked";
                                                                                                                                                }
                                                                                                                                            } ?>></td>
                                    <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="deleteInventory" <?php if ($serialize_permission) {
                                                                                                                                                if (in_array('deleteInventory', $serialize_permission)) {
                                                                                                                                                    echo "checked";
                                                                                                                                                }
                                                                                                                                            } ?>></td>
                                </tr>
                                <tr>
                                    <td>Reports</td>
                                    <td> - </td>
                                    <td> - </td>
                                    <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="viewReports" <?php if ($serialize_permission) {
                                                                                                                                            if (in_array('viewReports', $serialize_permission)) {
                                                                                                                                                echo "checked";
                                                                                                                                            }
                                                                                                                                        } ?>></td>
                                    <td> - </td>
                                </tr>
                                <tr>
                                    <td>Statistics</td>
                                    <td> - </td>
                                    <td> - </td>
                                    <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="viewStatistics" <?php if ($serialize_permission) {
                                                                                                                                                if (in_array('viewStatistics', $serialize_permission)) {
                                                                                                                                                    echo "checked";
                                                                                                                                                }
                                                                                                                                            } ?>></td>
                                    <td> - </td>
                                </tr>

                            </tbody>
                        </table>

                    </div>
                </div>
                <div class="card-footer">
                    <a href="<?php echo base_url('groups/') ?>" class="btn btn-danger btn-sm btn-icon-split"><span class="icon text-white-50"><i class="fas fa-arrow-left"></i></span><span class="text">Back</span></a>
                    <button type="submit" class="btn btn-success btn-sm btn-icon-split">
                        <span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text">Update</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
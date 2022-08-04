<?php
# @Author: Deepak

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Stocks extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        global $storeID;
        $this->load->model('stocks_model');
        $this->load->library('form_validation');
        $this->load->library('breadcrumbs');
        $this->load->model('users/users_model');
        // $this->getProducts();
        //$this->storeID = $this->users_model->getStoreID($this->login_id);
      $this->storeData = $this->users_model->getStoreData($this->login_id);
      $this->storeID = $this->storeData[0]['store_id'];
    }
    public function index()
    {
        $this->layout->set_title('Product List');
        $this->load_datatables();
        $this->layout->add_js('../datatables/stocks_table.js');
        $this->breadcrumbs->admin_push('Dashboard', 'dashboard');
        $this->breadcrumbs->admin_push('Product List', 'stocks');
        $this->layout->view_render('index');
    }

    public function edit()
    {
        $variant_id = $this->input->post('variant_id');
        $new_inventory = $this->input->post('new_inventory');
        //$variantID = $this->inventory_model->getVariantID($variant_id);
        $inventory_item_id = $this->stocks_model->getInentoryItemId($variant_id, $this->storeID);
        $inventory_reponse = $this->stocks_model->updateStockToShopify($inventory_item_id, $new_inventory, $this->storeID);

        //$result = $this->stocks_model->edit_row($variant_id, array("inventory" => $new_inventory, 'updated_at' => date("Y-m-d h:i:s")));
        if ($inventory_reponse) {
            echo json_encode(array('message' => 'Stock Updated Successfully', 'type' => 'success'));
        } else {
            echo json_encode(array('message' => 'Something went wrong', 'type' => 'warning'));
        }
    }

    public function get_products()
    {
        $columns = array(
            0 => "image",
            1 => 'name',
            2 => "product_id",
            3 => "title",
            4 => "price",
            5 => "barcode",
            6 => "inventory_quantity",
        );
        $storeData = $this->users_model->getStoreData($this->login_id);
        $totalData = $this->stocks_model->tot_rows($storeData[0]['store_id']);
        $products = $this->stocks_model->allProducts($storeData[0]['store_id']);
        $data = [];
        if (!empty($products)) {
            foreach ($products as $rows) {
                foreach ($rows as $row) {
                    $productID =  $row['product_id'];
                    $imageID = $row['image_id'];
                    //  $imageURL = $this->stocks_model->getImageURL($productID, $imageID, $storeID);
                    // $productName = $this->stocks_model->getproductName($productID);
                    $imageURL = base_url() . "assets/img/not-found.png";

                    if ($imageURL) {
                        //  $image = "<img src='$imageURL' width='50' height='50' id='btnImgpop' data-image_path='$imageURL' />";
                        $image = "<img src='" . $imageURL . "' width='50' height='50' id='btnImgpop' />";
                    } else {
                        $img_url = base_url() . "assets/img/not-found.png";
                        $image = "<img src='" . $img_url . "' width='50' height='50' id='btnImgpop' />";
                    };

                    $inventory = $row['inventory_quantity'];
                    $variant_id = $row['id'];
                    $nestedData["image"] = $imageID;
                    $nestedData["name"] = $productID;
                    $nestedData["product_id"] = $productID;
                    $nestedData["title"] = $row['title']; // variant Name
                    $nestedData["price"] = "£" . $row['price'];
                    $nestedData["barcode"] = $row['barcode'];
                    // $nestedData["inventory_quantity"] = $row['inventory_quantity'];
                    $nestedData["inventory_quantity"] = "<div id='input_container' ><input id='input' type='text' data-variant_id='$variant_id' class='form-control inventory_container' style='text-align:center'value='$inventory'></div>";

                    $data[] = $nestedData;
                }
            } // end foreach

        } // end if

        /*  echo "<pre>";
        print_r($data); */
        $resData = json_encode(array(
            "aaData" => $data,
            "iTotalDisplayRecords" => intval($totalData),
            "iTotalRecords" => intval($totalData),
            "sColumns" => $this->input->post('sColumns'),
            "sEcho" => $this->input->post('sEcho')
        ));

        echo $resData;
    }
}

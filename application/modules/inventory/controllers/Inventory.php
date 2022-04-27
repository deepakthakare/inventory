<?php
# @Author: Deepak


if (!defined('BASEPATH'))
  exit('No direct script access allowed');
class Inventory extends Admin_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->model('inventory_model');
    $this->load->library('form_validation');
    $this->load->library('breadcrumbs');
  }
  public function index()
  {
    $this->layout->set_title('Inventory Details');
    $this->load_datatables();
    $this->breadcrumbs->admin_push('Dashboard', 'dashboard');
    $this->breadcrumbs->admin_push('Inventory Datails', 'inventory');
    $this->layout->view_render('index');
  }
  public function add()
  {
  }
  public function edit()
  {
    $prod_price_id = $this->input->post('prod_price_id');
    $new_inventory = $this->input->post('new_inventory');
    $variantID = $this->inventory_model->getVariantID($prod_price_id);
    $inventory_item_id = $this->inventory_model->getInentoryItemId($variantID);
    $inventory_reponse = $this->inventory_model->updateStockToShopify($inventory_item_id, $new_inventory);

    $result = $this->inventory_model->edit_row($prod_price_id, array("inventory" => $new_inventory, 'updated_at' => date("Y-m-d h:i:s")));
    if ($result) {
      echo json_encode(array('message' => 'Inventory Updated Successfully', 'type' => 'success'));
    } else {
      echo json_encode(array('message' => 'Something went wrong', 'type' => 'warning'));
    }
  }
  public function _rules()
  {
    $this->form_validation->set_rules('name', 'Name', 'trim|required');
    $this->form_validation->set_rules('product_category', 'Product Category', 'trim|required');
    $this->form_validation->set_rules('brand', 'Product Brand', 'trim|required');
    $this->form_validation->set_rules('attributes[]', 'Product Attributes', 'trim|required');
    $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span><br/>');
  }

  public function getInventory()
  {

    $columns = array(
      0 => "prod_price_id",
      1 => "image_path",
      2 => "product_name",
      3 => "price",
      4 => "tax_rate",
      5 => "prd_barcode",
      6 => "inventory"
    );
    $limit = $this->input->post("length");
    $start = $this->input->post("start");
    $order = $columns[$this->input->post("order")[0]["column"]];
    $dir = $this->input->post("order")[0]["dir"];
    $totalData = $this->inventory_model->tot_rows();
    $totalFiltered = $totalData;

    if (empty($this->input->post("search")["value"])) {
      $records = $this->inventory_model->all_rows($limit, $start, $order, $dir);
    } else {
      $search = $this->input->post("search")["value"];
      $records = $this->inventory_model->search_rows($limit, $start, $search, $order, $dir);
      $totalFiltered = $this->inventory_model->tot_search_rows($search);
    } //End of if else
    $data = array();
    if (!empty($records)) {
      foreach ($records as $rows) {
        $readonly = ($rows->attributes_id == 1) ? 'readonly' : '';
        $sellPrice = $rows->p_price + $rows->p_price * ($rows->tax_rate / 100);
        $viewBtn = anchor(site_url('inventor/view/' . urlencode(base64_encode($rows->prod_price_id))), 'View', array('class' => 'btn btn-primary btn-sm')) . "&nbsp;";
        if ($rows->image_path) {
          $image = "<img src='$rows->image_path' width='50' height='50' id='btnImgpop' data-image_path='$rows->image_path' />";
        } else {
          $img_url = base_url() . "assets/img/not-found.png";
          $image = "<img src='" . $img_url . "' width='50' height='50' id='btnImgpop' />";
        };
        $nestedData["prod_price_id"] = $rows->prod_price_id;
        $nestedData["image"] = $image;
        $nestedData["product_name"] = $rows->product_name;
        $nestedData["p_price"] = "£" . $rows->p_price;
        $nestedData["tax_rate"] = $rows->tax_rate . " %";
        $nestedData["attributes"] = "<b>" . $rows->attributes_name . ":</b> " . $rows->attributes_value;
        $nestedData["selling_price"] = "£" . $sellPrice . " / " . $rows->sold_as;
        $nestedData["prd_barcode"] = $rows->prd_barcode;
        $nestedData["barcode"] = $rows->barcode;
        $nestedData["inventory"] = "<div id='input_container' ><input id='input' type='text' data-prod_price_id='$rows->prod_price_id' class='form-control inventory_container' style='text-align:center'value='$rows->inventory' $readonly></div>";
        $data[] = $nestedData;
      } //End of for
    } //End of if
    $json_data = array(
      "draw" => intval($this->input->post("draw")),
      "recordsTotal" => intval($totalData),
      "recordsFiltered" => intval($totalFiltered),
      "data" => $data
    );
    echo json_encode($json_data);
  }
}

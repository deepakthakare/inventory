<?php
# @Author: Deepak


if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Tiktok extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('tiktok_model');
        $this->load->library('form_validation');
        $this->load->library('breadcrumbs');
    }
    public function index()
    {
        $this->layout->set_title('Product Details');
        $this->load_datatables();
        //$this->layout->add_js('../datatables/tiktok_table.js');
        // $this->layout->add_js('../datatables/stocks_table.js');
        $this->breadcrumbs->admin_push('Dashboard', 'dashboard');
        $this->breadcrumbs->admin_push('Tiktok Product Datails', 'tiktok');
        $this->layout->view_render('index');
    }

    public function edit()
    {
        $prod_price_id = $this->input->post('prod_price_id');
        $new_selling_price = $this->input->post('new_selling_price');
        $prod_id = $this->input->post('prod_id');
        $attributes_value = $this->input->post('attributes_value');
        $colorValue = $this->tiktok_model->getColor($prod_id);
        $tiktokData = [
            "selling_price" => $new_selling_price,
            "id_product" => $prod_id,
            "prod_price_id" => $prod_price_id,
            "size" => $attributes_value,
        ];
        $tiktokData['color'] = $colorValue;
        $variantExits = $this->tiktok_model->attributeID_exists($prod_price_id);
        $result = ($variantExits === 1) ? $this->tiktok_model->edit_row($prod_price_id, array("selling_price" => $new_selling_price, 'updated_at' => date("Y-m-d h:i:s"))) : $this->tiktok_model->add($tiktokData);
        //$result = ($variantExits == 1) ? 'update' : 'add';
        //echo $result;
        //$result = $this->inventory_model->edit_row($prod_price_id, array("selling_price" => $new_selling_price, 'updated_at' => date("Y-m-d h:i:s")));
        if ($result) {
            echo json_encode(array('message' => 'Selling Price Updated Successfully', 'type' => 'success'));
        } else {
            echo json_encode(array('message' => 'Something went wrong', 'type' => 'warning'));
        }
    }

    public function get_tiktok_products()
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
        // $limit = $this->input->post("length");
        // $start = $this->input->post("start");
        // $order = $columns[$this->input->post("order")[0]["column"]];
        // $dir = $this->input->post("order")[0]["dir"];
        $totalData = $this->tiktok_model->tot_rows();
        $totalFiltered = $totalData;

        if (empty($this->input->post("search")["value"])) {
            //$records = $this->tiktok_model->all_rows($limit, $start, $order, $dir);
            $records = $this->tiktok_model->all_rows();
        } else {
            $search = $this->input->post("search")["value"];
            $records = $this->tiktok_model->search_rows($search);
            // $records = $this->tiktok_model->search_rows($limit, $start, $search, $order, $dir);
            $totalFiltered = $this->tiktok_model->tot_search_rows($search);
        } //End of if else
        $data = array();
        if (!empty($records)) {
            foreach ($records as $rows) {
                $readonly = ($rows->attributes_id == 1) ? 'readonly' : '';
                $sellPrice = $rows->p_price + $rows->p_price * ($rows->tax_rate / 100);
                //  $viewBtn = anchor(site_url('tiktok/view/' . urlencode(base64_encode($rows->prod_price_id))), 'View', array('class' => 'btn btn-primary btn-sm')) . "&nbsp;";
                if ($rows->image_path) {
                    $image = "<img src='$rows->image_path' width='100' height='100' id='btnImgpop' data-image_path='$rows->image_path' />";
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
                $nestedData["stylecode"] = $rows->stylecode;
                $nestedData["selling_price"] = "<div id='input_container' >
                <input id='input' type='text'
                 data-prod_price_id='$rows->prod_price_id' 
                 data-id_product = '$rows->prod_id' 
                 data-attributes_value = '$rows->attributes_value' 
                 class='form-control inventory_container' 
                 style='text-align:center'value='$rows->selling_price' $readonly>
                </div>";
                $data[] = $nestedData;
            } //End of for
        } //End of if
        $json_data = array(
            /* "draw" => intval($this->input->post("draw")),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data, */

            "data" => $data,
            "iTotalDisplayRecords" => intval($totalData),
            "iTotalRecords" => intval($totalData),
            "sColumns" => $this->input->post('sColumns'),
            "sEcho" => $this->input->post('sEcho')
        );
        echo json_encode($json_data);
    }
    public function getExportProducts()
    {
        $ids = $this->tiktok_model->exportProducts($this->input->post('product_ids'));
    }
    public function _rules()
    {
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('product_category', 'Product Category', 'trim|required');
        $this->form_validation->set_rules('brand', 'Product Brand', 'trim|required');
        $this->form_validation->set_rules('attributes[]', 'Product Attributes', 'trim|required');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span><br/>');
    }
}

<?php
# @Author: Deepak

if (!defined('BASEPATH'))
  exit('No direct script access allowed');
class Sales extends Admin_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->model('products/products_model');
    $this->load->model('sales_model');
    $this->load->model('category/category_model');
    $this->load->model('brands/brands_model');
    $this->load->model('attributes/attributes_model');
    $this->load->library('form_validation');
    $this->load->library('breadcrumbs');
    $this->load->helper('fileUpload');
    $this->layout->add_js('custom/sales.js');
    $this->config->load('config');
  }
  public function index()
  {
    $this->layout->set_title('Sales List');
    $this->load_datatables();
    $this->layout->add_js('../datatables/sales_table.js');
    $this->breadcrumbs->admin_push('Dashboard', 'dashboard');
    $this->breadcrumbs->admin_push('Sales List', 'sales');
    $this->layout->view_render('index');
  }
  public function get_sales()
  {
    echo  $this->sales_model->get_sales();
  }
  function get_product_by_filter()
  {
    $products = $this->sales_model->get_product_by_filter($this->input->post('category_id'), $this->input->post('brand_id'));
    //var_dump($products);
    if ($products) {
      echo '<option value="">Select Products</option>';
      foreach ($products as $row) {
        $prod = $row->product_name . '->' . $row->attributes_value . '->' . $row->price . '/' . $row->sold_as;
        echo '<option data-product_name="' . $row->product_name . '" data-prod_price_id="' . $row->prod_price_id . '" data-sold_as="' . $row->sold_as . '" data-attributes_value="' . $row->attributes_value . '" data-price="' . $row->price . '" data-tax_rate="' . $row->tax_rate . '" value="' . $row->prod_id . '">' . $prod . '</option>';
      }
    } else {
      echo '<option value="">No Records Found</option>';
    }
  }

  function getShopifyCustomers()
  {
    $customersShopi = $this->sales_model->getShopifyCustomers();
    echo "<pre>";
    if ($customersShopi) {
      echo '<option value="">Select Customers</option>';
      foreach ($customersShopi as $row) {
        foreach ($row as $child) {
          $customer_details = $child['first_name'] . ' ' . $child['last_name'] . '/' . $child['id'];
          $cust = $child['first_name'] . ' ' . $child['last_name'] . '->' . $child['email'];
          echo '<option value="' . $customer_details . '">' . $cust . '</option>';
        }
      }
    } else {
      echo '<option value="">No Records Found</option>';
    }
  }

  function getAllProducts()
  {
    $products = $this->sales_model->getAllProducts();
    if ($products) {
      echo '<option value="">Select Products</option>';
      foreach ($products as $row) {
        $prod = $row->product_name;
        echo '<option data-product_name="' . $row->product_name . '"  data-prd_barcode="' . $row->prd_barcode . '" data-image_path="' . $row->image_path . '" data-shopify_id="' . $row->shopify_id . '" data-p_price="' . $row->p_price . '" value="' . $row->prod_id . '">' . $prod . '</option>';
      }
    } else {
      echo '<option value="">No Records Found</option>';
    }
  }

  function getVariants()
  {
    $variants = $this->sales_model->getVariants($this->input->post('product_id'));
    $color = $this->sales_model->getVariantColor($this->input->post('product_id'));
    if ($variants) {
      echo '<option value="">Select Variants</option>';
      foreach ($variants as $row) {
        $vari = $color . ' - ' . $row->attributes_value;
        echo '<option data-variant_id="' . $row->variant_id . '" data-color="' . $color . '" data-tax_rate="' . $row->tax_rate . '" data-stylecode="' . $row->stylecode . '" data-attributes_value="' . $row->attributes_value . '" data-prod_price_id="' . $row->prod_price_id . '" data-barcode="' . $row->barcode . '" value="' . $row->prod_price_id . '">' . $vari . '</option>';
      }
    } else {
      echo '<option value="">No Records Found</option>';
    }
  }


  public function add()
  {
    $this->layout->set_title('Add Sales');
    $this->breadcrumbs->admin_push('Dashboard', 'dashboard');
    $this->breadcrumbs->admin_push('Sales List', 'sales');
    $this->breadcrumbs->admin_push('Add sales', 'sales/add');
    $data = array(
      'button' => 'Add',
      'action' => admin_url('sales/add_sales'),
      'sales_id' => set_value(''),
      'image_path' => set_value(''),
      'name' => set_value('name'),
    );
    $data['sold_as'] = $this->config->item('sold_as');
    $data['tax_rate'] = $this->config->item('tax_rate');
    $data['shipping'] = $this->config->item('shipping');
    $data['category_list'] = $this->category_model->get_all();
    $data['brand_list'] = $this->brands_model->get_all();
    $data['attributes_list'] = $this->attributes_model->get_all();
    $this->layout->view_render('add', $data);
  }
  public function add_sales()
  {   //var_dump($this->input->post());die;
    $this->_rules();
    if ($this->form_validation->run() == FALSE) {
      $this->add();
    } else {
      $next_order_id = $this->sales_model->next_order_id(); //var_dump($next_order_id);die;
      $prod_id = !empty($this->input->post('prod_id')) ? $this->input->post('prod_id') : "";
      $attributes_value = !empty($this->input->post('attributes_value')) ? $this->input->post('attributes_value') : "";
      $color = !empty($this->input->post('color')) ? $this->input->post('color') : "";
      $product_variants = !empty($this->input->post('product_variants')) ? $this->input->post('product_variants') : "";
      $prod_price_id = !empty($this->input->post('prod_price_id')) ? $this->input->post('prod_price_id') : "";
      $price = !empty($this->input->post('price')) ? $this->input->post('price') : "";
      $qty = !empty($this->input->post('qty')) ? $this->input->post('qty') : "";
      $total_amount = !empty($this->input->post('total_amount')) ? $this->input->post('total_amount') : "";
      $tax_rate = !empty($this->input->post('tax_rate')) ? $this->input->post('tax_rate') : "";
      $cust = $this->input->post('customer');
      $customer = explode("/", $cust);
      $result = null;
      if ($prod_id) {
        foreach ($prod_id as $key => $value) {
          $sales_data = array(
            'prod_id' => $prod_id[$key],
            'sales_date' => $this->input->post('sales_date'),
            'customer_name' => $customer[0],
            'customer' => $customer[1],
            'attributes_value' => $color[$key] . ' / ' . $attributes_value[$key],
            'product_variants' => $product_variants[$key],
            'prod_price_id' => $prod_price_id[$key],
            'price' => $price[$key],
            'qty' => $qty[$key],
            'total' => $total_amount[$key],
            'order_id' => $next_order_id,
            'tax_rate' => $tax_rate[$key],
            'shipping' => $this->input->post('shipping'),
          );
          $result = $this->sales_model->add($sales_data);
        }
      }

      if ($result) {
        $this->activity_model->add(array('login_id' => $this->login_id, 'activity' => ucfirst($this->username) . ' adde a sales at ' . date("M d, Y H:i")));
        $this->session->set_flashdata(array('message' => 'Sales Added Successfully', 'type' => 'success'));
      } else {
        $this->session->set_flashdata(array('message' => 'Something went wrong. Try again', 'type' => 'warning'));
      }
      redirectToAdmin('sales');
    }
  }

  public function delete()
  {
    $order_id = $this->input->post('order_id');
    $result = $this->sales_model->edit($order_id, array('is_deleted' => '1', 'updated_at' => date("Y-m-d h:i:s")));
    if ($result) {
      $this->activity_model->add(array('login_id' => $this->login_id, 'activity' => ucfirst($this->username) . ' deleted a sales at ' . date("M d, Y H:i")));
      echo json_encode(array('message' => 'Sales deleted Successfully', 'type' => 'success'));
    } else {
      echo json_encode(array('message' => 'Something went wrong', 'type' => 'warning'));
    }
  }

  public function pushOrderToShopify()
  {
    $key = SHOPIFY_API_KEY . '/admin/api/2022-04/draft_orders.json';
    $order_id = $this->input->post('order_id');
    $getArray = $this->sales_model->getOrderSHOPIFY($order_id);
    $result = $this->sales_model->json_change_key($getArray, $order_id, 'draft_order');
    $jsonData = json_encode($result);
    $ch = curl_init($key);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt(
      $ch,
      CURLOPT_HTTPHEADER,
      array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($jsonData)
      )
    );
    $response = curl_exec($ch);
    curl_close($ch);
    $json_array = json_decode($response, true);
    $draftOrderID = $json_array['draft_order']['id'];
    if ($draftOrderID !== '') {
      $this->sales_model->updateDraftOrderID($order_id, $draftOrderID);
    }
    echo $response;
  }

  function getOrderDetails()
  {
    $order_id = $this->input->post('order_id');
    $result = $this->sales_model->getOrderDetails($order_id);
    if ($result) {
      echo json_encode(array('message' => 'Order Details', 'type' => 'success', "data" => $result));
    } else {
      echo json_encode(array('message' => 'Something went wrong', 'type' => 'warning'));
    }
  }
  public function _rules()
  {

    // $this->form_validation->set_rules('product_category', 'Product Category', 'trim|required');
    $this->form_validation->set_rules('customer', 'Customer', 'trim|required');
    $this->form_validation->set_rules('shipping', 'Shipping Method', 'trim|required');
    // $this->form_validation->set_rules('brand', 'Product Brand', 'trim|required');
    $this->form_validation->set_rules('prod_id[]', 'Product', 'trim|required');
    $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span><br/>');
  }
}

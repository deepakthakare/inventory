<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');
class Products extends Admin_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->model('products_model');
    $this->load->model('attributes/attributes_model');
    $this->load->library('form_validation');
    $this->load->library('breadcrumbs');
    $this->load->helper('fileUpload');
    $this->layout->add_js('custom/product.js');
    // $this->config->load('config');
  }
  public function index()
  {
    $this->layout->set_title('products List');
    $this->load_datatables();
    $this->layout->add_js('../datatables/product_table.js');
    $this->breadcrumbs->admin_push('Dashboard', 'dashboard');
    $this->breadcrumbs->admin_push('products List', 'products');
    $this->layout->view_render('index');
  }
  public function get_products()
  {
    echo  $this->products_model->get_products();
  }
  public function get_product_inventory()
  {

    $result = $this->products_model->get_product_inventory($this->input->post('prod_id'));
    if ($result) {
      echo json_encode(array('message' => 'Inventory Data', 'type' => 'success', "data" => $result));
    } else {
      echo json_encode(array('message' => 'Something went wrong', 'type' => 'warning'));
    }
  }
  public function add()
  {
    $this->layout->set_title('Add Product');
    $this->layout->add_js('../public/pekeupload/js/pekeUpload.js');
    $this->breadcrumbs->admin_push('Dashboard', 'dashboard');
    $this->breadcrumbs->admin_push('products List', 'products');
    $this->breadcrumbs->admin_push('Add products', 'products/add');
    $data = array(
      'button' => 'Add',
      'action' => admin_url('products/add_products'),
      'prod_id' => set_value(''),
      'image_path' => set_value(''),
      'name' => set_value('name'),
      'p_price' => set_value('p_price'),
      'location' => set_value('location'),
      'weight' => set_value('weight'),
    );

    $data['attributes_list'] = $this->attributes_model->get_all();
    $data['prd_barcode'] = $this->barcodeGenerator();
    $this->layout->view_render('add', $data);
  }
  public function add_products()
  {
    $this->_rules();
    if ($this->form_validation->run() == FALSE) {
      $this->add();
    } else {
      $name =
        $data = array(
          'name' => $this->input->post('name', TRUE),
          'weight' => $this->input->post('weight', TRUE),
          'prd_barcode' => $this->input->post('prd_barcode', TRUE),
          'p_price' => $this->input->post('p_price', TRUE),
          'location' => $this->input->post('location', TRUE),
          'description' => $this->input->post('description', TRUE),
        );
      if ($this->input->post("upload_image")) {
        $image = moveFile(1, $this->input->post("upload_image"), "image");
        $data['image_path'] = $image[0];
      }

      $result = $this->products_model->add($data);

      $attributes = !empty($this->input->post('attributes')) ? $this->input->post('attributes') : "";
      $attributes_value = !empty($this->input->post('attributes_value')) ? $this->input->post('attributes_value') : "";
      $stylecode = !empty($this->input->post('stylecode')) ? $this->input->post('stylecode') : "";
      $inventory = !empty($this->input->post('inventory')) ? $this->input->post('inventory') : "";
      $barcode = !empty($this->input->post('barcode')) ? $this->input->post('barcode') : "";
      if ($attributes) {
        foreach ($attributes as $key => $value) {
          $attribute_data = array(
            'attributes_id' => $attributes[$key],
            'attributes_value' => $attributes_value[$key],
            'prod_id' => $result,
            'stylecode' => $stylecode[$key],
            'inventory' => $inventory[$key],
            'barcode' => $barcode[$key]
          );
          $this->products_model->add_price($attribute_data);
        }
      }

      if ($result) {
        $this->activity_model->add(array('login_id' => $this->login_id, 'activity' => ucfirst($this->username) . ' adde a product at ' . date("M d, Y H:i")));
        $this->session->set_flashdata(array('message' => 'Product Added Successfully', 'type' => 'success'));
      } else {
        $this->session->set_flashdata(array('message' => 'Something went wrong. Try again', 'type' => 'warning'));
      }
      redirectToAdmin('products');
    }
  }
  public function edit($prod_id)
  {
    $this->layout->add_js('../public/pekeupload/js/pekeUpload.js');
    $this->breadcrumbs->admin_push('Dashboard', 'dashboard');
    $this->breadcrumbs->admin_push('products List', 'products');
    $this->breadcrumbs->admin_push('Edit products', 'products/edit/' . $prod_id);
    $row = $this->products_model->get_by_id($prod_id);
    /*     echo "<pre>";
    var_dump($row);
    die; */
    if ($row) {
      $data = array(
        'button' => 'Update',
        'prod_id' => $prod_id,
        'action' => admin_url('products/edit_products/' . $prod_id)
      );

      $data['edit_data'] = $row;
      $data['attributes_list'] = $this->attributes_model->get_all();
      $this->layout->view_render('edit', $data);
    } else {
      $this->session->set_flashdata(array('message' => 'No Records Found', 'type' => 'warning'));
      redirectToAdmin('products');
    }
  }


  public function edit_products($prod_id)
  {
    $this->_rules();
    if ($this->form_validation->run() == FALSE) {
      redirectToAdmin('products/edit/' . $prod_id);
    }

    $data_to_update = array(
      'name' => $this->input->post('name', TRUE),
      'p_price' => $this->input->post('p_price', TRUE),
      'location' => $this->input->post('location', TRUE),
      'weight' => $this->input->post('weight', TRUE),
      'description' => $this->input->post('description', TRUE),
      'updated_at' => date("Y-m-d h:i:s")
    );
    if ($this->input->post("upload_image")) {
      $image = moveFile(0, $this->input->post("upload_image"), "image");
      $data_to_update['image_path'] = $image[0];
    }

    $result = $this->products_model->edit($prod_id, $data_to_update);

    $prod_price_ids = !empty($this->input->post('prod_price_ids')) ? $this->input->post('prod_price_ids') : "";
    $attributes = !empty($this->input->post('attributes')) ? $this->input->post('attributes') : "";
    $attributes_value = !empty($this->input->post('attributes_value')) ? $this->input->post('attributes_value') : "";
    $stylecode = !empty($this->input->post('stylecode')) ? $this->input->post('stylecode') : "";
    $inventory = !empty($this->input->post('inventory')) ? $this->input->post('inventory') : "";
    $barcode = !empty($this->input->post('barcode')) ? $this->input->post('barcode') : "";
    if ($attributes) {
      $this->products_model->update_price($prod_id);
      foreach ($attributes as $key => $value) {
        $attribute_data = array(
          'attributes_id' => $attributes[$key],
          'attributes_value' => $attributes_value[$key],
          'stylecode' => $stylecode[$key],
          'inventory' => $inventory[$key],
          'updated_at' => date("Y-m-d h:i:s"),
          'is_deleted' => '0',
          'barcode' => $barcode[$key]
        );

        if ($prod_price_ids[$key]) {
          $this->products_model->edit_price($prod_price_ids[$key], $attribute_data);
        } else {
          $attribute_data['prod_id'] = $prod_id;
          $this->products_model->add_price($attribute_data);
        }
      }
    }

    if ($result) {
      $this->session->set_flashdata(array('message' => 'Product updated Successfully', 'type' => 'success'));
    } else {
      $this->session->set_flashdata(array('message' => 'Something went wrong. Try again', 'type' => 'warning'));
    }
    redirectToAdmin('products');
  }

  public function delete()
  {
    $prod_id = $this->input->post('prod_id');
    $result = $this->products_model->edit($prod_id, array('is_deleted' => '1', 'updated_at' => date("Y-m-d h:i:s")));
    if ($result) {
      $this->activity_model->add(array('login_id' => $this->login_id, 'activity' => ucfirst($this->username) . ' deleted a brand at ' . date("M d, Y H:i")));
      echo json_encode(array('message' => 'Product deleted Successfully', 'type' => 'success'));
    } else {
      echo json_encode(array('message' => 'Something went wrong', 'type' => 'warning'));
    }
  }

  // Create/Push the  Products into the  Shopify Store
  public function push()
  {
    // echo SHOPIFY_API_KEY;
    $key = SHOPIFY_API_KEY . '/admin/products.json';
    //echo $key;
    $prod_id = $this->input->post('prod_id');
    $result = $this->products_model->createProductSHOPIFY($prod_id);
    $finalArray = $this->products_model->removeUselessArrays($result, 'variants');
    $jsonData = json_encode($this->json_change_key($finalArray, $prod_id, 'product'));
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
    $shopiID = $json_array['product']['id'];
    $arraVal = $json_array['product']['variants'];
    if ($shopiID !== '') {
      $this->products_model->updateShipifyPrdID($prod_id, $shopiID);
      $this->products_model->updateLocation($shopiID);
      $this->products_model->addImage($shopiID);
    }

    $variants = array_map(function ($ar) {
      return $ar['id'] . "/" . $ar['option2'];
    }, $arraVal);

    foreach ($variants as $key => $value) {
      $arrSplit = explode('/', $value);
      if ($arrSplit[0] !== '') {
        $this->products_model->updateVariantID($prod_id, $arrSplit[0], $arrSplit[1]);
      }
    }

    echo $response;
  }

  public function _rules()
  {
    $this->form_validation->set_rules('name', 'Name', 'trim|required');
    // $this->form_validation->set_rules('product_category', 'Product Category', 'trim|required');
    // $this->form_validation->set_rules('brand', 'Product Brand', 'trim|required');
    $this->form_validation->set_rules('attributes[]', 'Product Attributes', 'trim|required');
    $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span><br/>');
  }

  public function ean13_check_digit($digits)
  {
    $digits = (string)$digits;
    $even_sum = $digits[1] + $digits[3] + $digits[5] + $digits[7] + $digits[9] + $digits[11];
    $even_sum_three = $even_sum * 3;
    $odd_sum = $digits[0] + $digits[2] + $digits[4] + $digits[6] + $digits[8] + $digits[10];
    $total_sum = $even_sum_three + $odd_sum;
    $next_ten = (ceil($total_sum / 10)) * 10;
    $check_digit = $next_ten - $total_sum;
    return $digits . $check_digit;
  }
  public function barcodeGenerator($length = 12)
  {
    $number = '1234506789';
    $numberLength = strlen($number);
    $randomNumber = '';
    for ($i = 0; $i < $length; $i++) {
      $randomNumber .= $number[rand(0, $numberLength - 1)];
    }
    $barcodeEAN = $this->ean13_check_digit($randomNumber);
    return $barcodeEAN;
  }

  function json_change_key($arr, $oldkey, $newkey)
  {
    $json = str_replace('"' . $oldkey . '":', '"' . $newkey . '":', json_encode($arr));
    return json_decode($json);
  }
}

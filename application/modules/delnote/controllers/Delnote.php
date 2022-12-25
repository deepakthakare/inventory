<?php
# @Author: Deepak

if (!defined('BASEPATH'))
  exit('No direct script access allowed');
class Delnote extends Admin_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->model('delnote_model');
    $this->load->library('form_validation');
    $this->load->library('breadcrumbs');
    $this->load->model('suppliers/suppliers_model');
    $this->load->model('category/category_model');
    $this->load->helper('fileUpload');
    $this->layout->add_js('custom/delnote.js');
    $this->layout->add_js('custom/delnote_product.js');
  }
  public function index()
  {
    $this->layout->set_title('Delivery Inwards List');
    $this->load_datatables();
    $this->layout->add_js('../datatables/delnote_table.js');
    $this->breadcrumbs->admin_push('Dashboard', 'delnote');
    $this->breadcrumbs->admin_push('Delivery Inwards List', 'delnote');
    $this->layout->view_render('index');
  }

  public function get_country()
  {
    echo  $this->country_model->get_country();
  }

  public function get_delnotes()
  {
    echo $this->delnote_model->get_delnotes();
  }
  public function add()
  {
    $this->layout->set_title('Add Delnote');
    $this->breadcrumbs->admin_push('Dashboard', 'dashboard');
    $this->breadcrumbs->admin_push('Delnote List', 'delnote');
    $this->breadcrumbs->admin_push('Add Delnote', 'delnote/add');
    $data = array(
      'button' => 'Add',
      'action' => admin_url('delnote/add_delnote_product'),
      'id_delnote' => set_value(''),
      'cust_delno' => set_value(''),
      'customer_markup' => set_value(''),
      'exchange_rate' => set_value(''),
      'ttl_sp' => set_value(''),
      'ttl_qty' => set_value(''),
      'single_mix' => set_value(''),
      'denote_date' => set_value(''),
      'profit_margin' => set_value(''),
    );
    $data['category_list'] = $this->category_model->get_all();
    $data['supplier_list'] = $this->suppliers_model->get_all();
    $this->layout->view_render('add', $data);
  }

  public function add_delnote_product()
  {
    $this->_rules();
    if ($this->form_validation->run() == FALSE) {
      $this->add();
    } else {
      // $exchg = $this->input->post('exchange_rate', TRUE);
      // $exchangeRate = substr($exchg, 2);
      $suppID = $this->input->post('product_supplier', TRUE);
      $data = array(
        'id_supplier' => $this->input->post('product_supplier', TRUE),
        'cust_delno' => $this->input->post('cust_delno', TRUE),
        'denote_date' => $this->input->post('denote_date', TRUE),
        'customer_markup' => $this->input->post('customer_markup', TRUE),
        'exchange_rate' => $this->input->post('exchange_rate', TRUE),
        'ttl_sp' => $this->input->post('ttl_sp', TRUE),
        'ttl_qty' => $this->input->post('ttl_qty', TRUE),
        'ttl_cp' => $this->input->post('ttl_cp', TRUE),
        'profit_margin' => $this->input->post('profit_margin', TRUE),
      );
      $result = $this->delnote_model->add($data);

      // Product Variants
      $single_mix =  $this->input->post('single_mix');
      $product_category =  $this->input->post('product_category');
      $bin_number =  $this->input->post('bin_number');
      $hs_code =  $this->input->post('hs_code');
      $supplier_ref = !empty($this->input->post('supplier_ref')) ? $this->input->post('supplier_ref') : "";
      $internal_ref = !empty($this->input->post('internal_ref')) ? $this->input->post('internal_ref') : "";
      $product_qty = !empty($this->input->post('product_qty')) ? $this->input->post('product_qty') : "";
      $price_euro = !empty($this->input->post('price_euro')) ? $this->input->post('price_euro') : "";
      $cost_price = !empty($this->input->post('cost_price')) ? $this->input->post('cost_price') : "";
      $selling_price = !empty($this->input->post('selling_price')) ? $this->input->post('selling_price') : "";
      $product_barcode = !empty($this->input->post('product_barcode')) ? $this->input->post('product_barcode') : "";

      if ($product_barcode) {
        foreach ($product_barcode as $key => $value) {
          $product_data = array(
            'single_mix' => $single_mix[$key],
            'id_category' => $product_category[$key],
            'id_delnote' => $result,
            'supplier_ref' => $supplier_ref[$key],
            'internal_ref' => $internal_ref[$key],
            'product_qty' => $product_qty[$key],
            'price_euro' => $price_euro[$key],
            'cost_price' => $cost_price[$key],
            'selling_price' => $selling_price[$key],
            'bin_number' => $bin_number[$key],
            'hs_code' => $hs_code[$key],
            'product_barcode' => $product_barcode[$key],
          );

          $productBarcode = [
            'id_supplier' => $suppID,
            'supplier_ref' => $supplier_ref[$key],
            'price_euro' => $price_euro[$key],
            'barcode' => $product_barcode[$key],
          ];
          $this->delnote_model->add_products($product_data);
          $this->delnote_model->add_barcodes($productBarcode);
        }
      }


      if ($result) {
        $this->activity_model->add(array('login_id' => $this->login_id, 'activity' => ucfirst($this->username) . ' adde a delnote product at ' . date("M d, Y H:i")));
        $this->session->set_flashdata(array('message' => 'Delnote Product Added Successfully', 'type' => 'success'));
      } else {
        $this->session->set_flashdata(array('message' => 'Something went wrong. Try again', 'type' => 'warning'));
      }
      redirectToAdmin('delnote');
    }
  }

  public function edit($id_delnote)
  {
    $this->layout->add_js('../public/pekeupload/js/pekeUpload.js');
    $this->load->model('attributes/attributes_model');
    $this->layout->add_js('custom/delnote_variants.js');
    $this->breadcrumbs->admin_push('Dashboard', 'dashboard');
    $this->breadcrumbs->admin_push('delnote List', 'delnote');
    $this->breadcrumbs->admin_push('Edit delnote', 'delnote/edit/' . $id_delnote);
    $row = $this->delnote_model->get_by_id($id_delnote);

    if ($row) {
      $data = array(
        'button' => 'Update',
        'id_delnote' => $id_delnote,
        'action' => admin_url('delnote/edit_delnote_products/' . $id_delnote)
      );

      $data['edit_data'] = $row;
      $data['category_list'] = $this->category_model->get_all();
      $data['supplier_list'] = $this->suppliers_model->get_all();
      $data['color_list'] = $this->attributes_model->getColorList();
      $data['size_list'] = $this->attributes_model->getSizeList();
      //  echo "<pre>"; print_r($data); die;
      $this->layout->view_render('edit', $data);
    } else {
      $this->session->set_flashdata(array('message' => 'No Records Found', 'type' => 'warning'));
      redirectToAdmin('delnote');
    }
  }


  public function edit_delnote_products($id_delnote)
  {
    $this->_rules();
    if ($this->form_validation->run() == FALSE) {
      redirectToAdmin('delnote/edit/' . $id_delnote);
    }

    $data_to_update = array(
      'cust_delno' => $this->input->post('cust_delno', TRUE),
      'denote_date' => $this->input->post('denote_date', TRUE),
      'customer_markup' => $this->input->post('customer_markup', TRUE),
      'exchange_rate' => $this->input->post('exchange_rate', TRUE),
      'ttl_sp' => $this->input->post('ttl_sp', TRUE),
      'ttl_qty' => $this->input->post('ttl_qty', TRUE),
      'ttl_cp' => $this->input->post('ttl_cp', TRUE),
      'profit_margin' => $this->input->post('profit_margin', TRUE),
      'updated_at' => date("Y-m-d h:i:s")
    );

    echo "<pre>";
    // print_r($data_to_update); die;
    $result = $this->delnote_model->edit($id_delnote, $data_to_update);

    $id_del_product = !empty($this->input->post('id_del_product')) ? $this->input->post('id_del_product') : "";
    $single_mix_value = !empty($this->input->post('single_mix')) ? $this->input->post('single_mix') : "";
    $product_category_value = !empty($this->input->post('product_category')) ? $this->input->post('product_category') : "";
    $is_barcode_recall = $this->input->post('is_barcode_recall');
    $internal_ref = !empty($this->input->post('internal_ref')) ? $this->input->post('internal_ref') : "";
    $supplier_ref = !empty($this->input->post('supplier_ref')) ? $this->input->post('supplier_ref') : "";
    $product_qty = !empty($this->input->post('product_qty')) ? $this->input->post('product_qty') : "";
    $price_euro = !empty($this->input->post('price_euro')) ? $this->input->post('price_euro') : "";
    $cost_price = !empty($this->input->post('cost_price')) ? $this->input->post('cost_price') : "";
    $selling_price = !empty($this->input->post('selling_price')) ? $this->input->post('selling_price') : "";
    $bin_number = !empty($this->input->post('bin_number')) ? $this->input->post('bin_number') : "";
    $hs_code = !empty($this->input->post('hs_code')) ? $this->input->post('hs_code') : "";
    $product_barcode = !empty($this->input->post('product_barcode')) ? $this->input->post('product_barcode') : "";

    if ($product_barcode) {
      $this->delnote_model->update_productid($id_delnote);
      foreach ($product_barcode as $key => $value) {
        $attribute_data = array(
          'single_mix' => $single_mix_value[$key],
          'id_category' => $product_category_value[$key],
          'is_barcode_recall' => $is_barcode_recall[$key],
          'internal_ref' => $internal_ref[$key],
          'supplier_ref' => $supplier_ref[$key],
          'product_qty' => $product_qty[$key],
          'price_euro' => $price_euro[$key],
          'cost_price' => $cost_price[$key],
          'selling_price' => $selling_price[$key],
          'bin_number' => $bin_number[$key],
          'hs_code' => $hs_code[$key],
          'updated_at' => date("Y-m-d h:i:s"),
          'is_deleted' => '0',
          'product_barcode' => $product_barcode[$key]
        );
        // echo "<pre>";
        // print_r($attribute_data); 
        if ($id_del_product[$key]) {
          $this->delnote_model->edit_product_data($id_del_product[$key], $attribute_data);
        } else {
          $attribute_data['id_delnote'] = $id_delnote;
          $this->delnote_model->add_product_data($attribute_data);
        }
      }
      // die;
    }

    if ($result) {
      $this->session->set_flashdata(array('message' => 'Delnote updated Successfully', 'type' => 'success'));
    } else {
      $this->session->set_flashdata(array('message' => 'Something went wrong. Try again', 'type' => 'warning'));
    }
    redirectToAdmin('delnote');
  }

  public function checkBarcodeExist()
  {
    $product_supplier = $this->input->post('product_supplier');
    $supplier_ref = $this->input->post('supplier_ref');
    $res = $this->delnote_model->checkBarcodeExist($product_supplier, $supplier_ref);
    echo json_encode($res);
  }

  public function checkAndGenerateUniqueBarcode()
  {
    $barcode = $this->input->post('brd');
    echo $this->delnote_model->checkAndGenerateUniqueBarcode($barcode);
  }

  public function updateDelProduct()
  {
    $delPrdID = $this->input->post('id');
    //echo $delPrdID;
    $productData =  $this->delnote_model->update_del_product($delPrdID);
    echo json_encode($productData);
  }

  public function addProductVariants()
  {
    $data = [
      'id_product' => $this->input->post('id_product', TRUE),
      'color' => $this->input->post('colors_value', TRUE),
      'size' => $this->input->post('sizes_value', TRUE),
      'variant_quantity' => $this->input->post('variant_quantity', TRUE),
      'variant_barcode' => $this->input->post('variant_barcode', TRUE),
    ];

    $brdData = [
      'id_supplier' => '999',
      'supplier_ref' => '999',
      'price_euro' => '999',
      'barcode' => $this->input->post('variant_barcode', TRUE),
    ];

    /*  echo "<pre>";
    print_r($brdData); die; */

    $res = $this->delnote_model->addProductVariants($data);
    $this->delnote_model->add_barcodes($brdData);
    $result = $res[0]['id_variant'];

    if ($result != '') {
      $this->session->set_flashdata(array('message' => 'Product Variant Added Successfully', 'type' => 'success'));
    } else {
      $this->session->set_flashdata(array('message' => 'Something went wrong. Try again', 'type' => 'warning'));
    }
    echo json_encode($res);
  }

  public function updateProductVariant()
  {
    $id_variant = $this->input->post('id_variant', TRUE);
    $variant_quantity = $this->input->post('qty', TRUE);
    $res = $this->delnote_model->updateProductVariant($id_variant, $variant_quantity);
    echo json_encode($res);
  }

  public function getAllProductVariants()
  {
    $id_product = $this->input->post('id_product');
    $response = $this->delnote_model->getAllProductVariants($id_product);
    echo json_encode($response);
  }

  public function removeProductVariant()
  {
    $id_variant = $this->input->post('id');
    $resp = $this->delnote_model->removeProductVariant($id_variant);
    echo json_encode($resp);
  }

  public function delete()
  {
    $id_delnote = $this->input->post('id_delnote');
    $result = $this->delnote_model->edit($id_delnote, array('is_deleted' => '1', 'updated_at' => date("Y-m-d h:i:s")));
    if ($result) {
      $this->activity_model->add(array('login_id' => $this->login_id, 'activity' => ucfirst($this->username) . ' deleted a delnote at ' . date("M d, Y H:i")));
      echo json_encode(array('message' => 'Delnote deleted Successfully', 'type' => 'success'));
    } else {
      echo json_encode(array('message' => 'Something went wrong', 'type' => 'warning'));
    }
  }

  public function _rules()
  {
    // $this->form_validation->set_rules('cust_delno', 'Cust Del No.', 'trim|required');
    $this->form_validation->set_rules('product_supplier', 'Select Supplier', 'trim|required');
    // $this->form_validation->set_rules('product_barcode', 'Product barcode ', array('trim','required','min_length[1]','regex_match[/(^\d+|^\d+[.]\d+)+$/]'));
    // $this->form_validation->set_rules('markup_pn', 'Markup PN', array('trim','required','min_length[1]','regex_match[/(^\d+|^\d+[.]\d+)+$/]'));
    // $this->form_validation->set_rules('cop', 'COP', array('trim','required','min_length[1]','regex_match[/(^\d+|^\d+[.]\d+)+$/]'));
    // $this->form_validation->set_rules('cop_percentage', 'COP(%)', array('trim','required','min_length[1]','regex_match[/(^\d+|^\d+[.]\d+)+$/]'));
    // $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span><br/>');
    // $this->form_validation->set_rules('delnote_product[]', 'Please add Product', 'trim|required');
  }
}

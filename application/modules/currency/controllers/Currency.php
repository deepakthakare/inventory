<?php
# @Author: Deepak

if (!defined('BASEPATH'))
  exit('No direct script access allowed');
class Currency extends Admin_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->model('currency_model');
    $this->load->library('form_validation');
    $this->load->library('breadcrumbs');
  }
  public function index()
  {
    $this->layout->set_title('Currency List');
    $this->load_datatables();
    $this->layout->add_js('../datatables/currency_table.js');
    $this->breadcrumbs->admin_push('Dashboard', 'dashboard');
    $this->breadcrumbs->admin_push('Currency List', 'currency');
    $this->layout->view_render('index');
  }
  public function get_currency()
  {
    echo  $this->currency_model->get_currency();
  }
  public function add()
  {
    $this->layout->set_title('Add Currency');
    $this->breadcrumbs->admin_push('Dashboard', 'dashboard');
    $this->breadcrumbs->admin_push('Currency List', 'currency');
    $this->breadcrumbs->admin_push('Add Currency', 'currency/add');
    $data = array(
      'button' => 'Add',
      'action' => admin_url('currency/add_currency'),
      'id' => set_value(''),
      'name' => set_value('name'),
      'symbol' => set_value(''),
      'exchange_rate' => set_value(''),
    );
    $this->layout->view_render('add', $data);
  }

  public function add_currency()
  {
    /*  $userDetails = $this->username;
    print_r($userDetails); */
    $this->_rules();
    if ($this->form_validation->run() == FALSE) {
      $this->add();
    } else {
      $name = $this->input->post('name', TRUE);
      $symbol = $this->input->post('symbol', TRUE);
      $exchange_rate = $this->input->post('exchange_rate', TRUE);
      $data = array(
        'name' => $name,
        'symbol' => $symbol,
        'exchange_rate' => $exchange_rate,
      );
      $result = $this->currency_model->add($data);
      if ($result) {
        $this->activity_model->add(array('login_id' => $this->login_id, 'activity' => ucfirst($this->username) . ' adde a currency at ' . date("M d, Y H:i")));
        $this->session->set_flashdata(array('message' => 'Currency Added Successfully', 'type' => 'success'));
      } else {
        $this->session->set_flashdata(array('message' => 'Something went wrong. Try again', 'type' => 'warning'));
      }
      redirectToAdmin('currency');
    }
  }

  public function edit($id)
  {
    if (check_post()) {
      $this->_rules();
      if ($this->form_validation->run() == FALSE) {
        redirectToAdmin('currency/edit/' . $id);
      }
      $id = $this->input->post('id', TRUE);
      $name = $this->input->post('name', TRUE);
      $symbol = $this->input->post('symbol', TRUE);
      $exchange_rate = $this->input->post('exchange_rate', TRUE);
      $data_to_update = array('name' => $name, 'symbol' =>  $symbol, 'exchange_rate' => $exchange_rate, 'updated_at' => date("Y-m-d h:i:s"));

      $result = $this->currency_model->edit($id, $data_to_update);
      if ($result) {
        $this->session->set_flashdata(array('message' => 'Currency updated Successfully', 'type' => 'success'));
      } else {
        $this->session->set_flashdata(array('message' => 'Something went wrong. Try again', 'type' => 'warning'));
      }
      redirectToAdmin('currency');
    } else {
      $row = $this->currency_model->get_by_id($id);
      if ($row) {
        $data = array(
          'button' => 'Update',
          'action' => admin_url('currency/edit/' . $row->id),
          'id' => set_value('id', $row->id),
          'name' => set_value('name', $row->name),
          'exchange_rate' => set_value('exchange_rate', $row->exchange_rate),
          'symbol' => set_value('symbol', $row->symbol),
        );
        $this->layout->view_render('add', $data);
      } else {
        $this->session->set_flashdata(array('message' => 'No Records Found', 'type' => 'warning'));
        redirectToAdmin('currency');
      }
    }
  }

  public function delete()
  {
    $currency_id = $this->input->post('currency_id');
    $result = $this->currency_model->edit($currency_id, array('is_deleted' => '1', 'updated_at' => date("Y-m-d h:i:s")));
    if ($result) {
      $this->activity_model->add(array('login_id' => $this->login_id, 'activity' => ucfirst($this->username) . ' deleted a currency at ' . date("M d, Y H:i")));
      echo json_encode(array('message' => 'Currency deleted Successfully', 'type' => 'success'));
    } else {
      echo json_encode(array('message' => 'Something went wrong', 'type' => 'warning'));
    }
  }
  public function _rules()
  {
    $this->form_validation->set_rules('name', 'Name', 'trim|required');
    $this->form_validation->set_rules('symbol', 'Symbol', 'trim|required');
    $this->form_validation->set_rules('exchange_rate', 'Exchange Rate', array('trim', 'required', 'min_length[1]', 'regex_match[/(^\d+|^\d+[.]\d+)+$/]'));
    $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span><br/>');
  }
}

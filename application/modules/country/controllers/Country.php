<?php
# @Author: Deepak

if (!defined('BASEPATH'))
  exit('No direct script access allowed');
class Country extends Admin_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->model('country_model');
    $this->load->library('form_validation');
    $this->load->library('breadcrumbs');
  }
  public function index()
  {
    $this->layout->set_title('Country List');
    $this->load_datatables();
    $this->layout->add_js('../datatables/country_table.js');
    $this->breadcrumbs->admin_push('Dashboard', 'dashboard');
    $this->breadcrumbs->admin_push('Country List', 'country');
    $this->layout->view_render('index');
  }
  public function get_country()
  {
    echo  $this->country_model->get_country();
  }
  public function add()
  {
    $this->layout->set_title('Add Country');
    $this->breadcrumbs->admin_push('Dashboard', 'dashboard');
    $this->breadcrumbs->admin_push('Country List', 'country');
    $this->breadcrumbs->admin_push('Add Country', 'country/add');
    $data = array(
      'button' => 'Add',
      'action' => admin_url('country/add_country'),
      'id' => set_value(''),
      'name' => set_value('name'),
      'cust_markup' => set_value(''),
      'markup_pn' => set_value(''),
      'cop' => set_value(''),
      'cop_percentage' => set_value(''),
    );
    $this->layout->view_render('add', $data);
  }

  public function add_country()
  {
    $this->_rules();
    if ($this->form_validation->run() == FALSE) {
      $this->add();
    } else {
      $name = $this->input->post('name', TRUE);
      $cust_markup = $this->input->post('cust_markup', TRUE);
      $markup_pn = $this->input->post('markup_pn', TRUE);
      $cop = $this->input->post('cop', TRUE);
      $cop_percentage = $this->input->post('cop_percentage', TRUE);
      $data = array(
        'name' => $name,
        'cust_markup' => $cust_markup,
        'markup_pn' => $markup_pn,
        'cop' => $cop,
        'cop_percentage' => $cop_percentage,
      );
     // print_r($data); die;
      $result = $this->country_model->add($data);
      if ($result) {
        $this->activity_model->add(array('login_id' => $this->login_id, 'activity' => ucfirst($this->username) . ' adde a country at ' . date("M d, Y H:i")));
        $this->session->set_flashdata(array('message' => 'Country Added Successfully', 'type' => 'success'));
      } else {
        $this->session->set_flashdata(array('message' => 'Something went wrong. Try again', 'type' => 'warning'));
      }
      redirectToAdmin('country');
    }
  }

  public function edit($id)
  {
    if (check_post()) {
      $this->_rules();
      if ($this->form_validation->run() == FALSE) {
        redirectToAdmin('country/edit/' . $id);
      }
      $id = $this->input->post('id', TRUE);
      $name = $this->input->post('name', TRUE);
      $symbol = $this->input->post('symbol', TRUE);
      $exchange_rate = $this->input->post('exchange_rate', TRUE);
      $data_to_update = array('name' => $name, 'updated_at' => date("Y-m-d h:i:s"));

      $result = $this->country_model->edit($id, $data_to_update);
      if ($result) {
        $this->session->set_flashdata(array('message' => 'Country updated Successfully', 'type' => 'success'));
      } else {
        $this->session->set_flashdata(array('message' => 'Something went wrong. Try again', 'type' => 'warning'));
      }
      redirectToAdmin('country');
    } else {
      $row = $this->country_model->get_by_id($id);
      if ($row) {
        $data = array(
          'button' => 'Update',
          'action' => admin_url('country/edit/' . $row->id),
          'id' => set_value('id', $row->id),
          'name' => set_value('name', $row->name),
          'cust_markup' => set_value('cust_markup', $row->cust_markup),
          'markup_pn' => set_value('markup_pn', $row->markup_pn),
          'cop' => set_value('cop', $row->cop),
          'cop_percentage' => set_value('cop_percentage', $row->cop_percentage),
        );
        $this->layout->view_render('add', $data);
      } else {
        $this->session->set_flashdata(array('message' => 'No Records Found', 'type' => 'warning'));
        redirectToAdmin('country');
      }
    }
  }

  public function delete()
  {
    $country_id = $this->input->post('country_id');
    $result = $this->country_model->edit($country_id, array('is_deleted' => '1', 'updated_at' => date("Y-m-d h:i:s")));
    if ($result) {
      $this->activity_model->add(array('login_id' => $this->login_id, 'activity' => ucfirst($this->username) . ' deleted a country at ' . date("M d, Y H:i")));
      echo json_encode(array('message' => 'Country deleted Successfully', 'type' => 'success'));
    } else {
      echo json_encode(array('message' => 'Something went wrong', 'type' => 'warning'));
    }
  }
  public function _rules()
  {
    $this->form_validation->set_rules('name', 'Name', 'trim|required');
    $this->form_validation->set_rules('cust_markup', 'Customer Markup', array('trim','required','min_length[1]','regex_match[/(^\d+|^\d+[.]\d+)+$/]'));
    $this->form_validation->set_rules('markup_pn', 'Markup PN', array('trim','required','min_length[1]','regex_match[/(^\d+|^\d+[.]\d+)+$/]'));
    $this->form_validation->set_rules('cop', 'COP', array('trim','required','min_length[1]','regex_match[/(^\d+|^\d+[.]\d+)+$/]'));
    $this->form_validation->set_rules('cop_percentage', 'COP(%)', array('trim','required','min_length[1]','regex_match[/(^\d+|^\d+[.]\d+)+$/]'));
    $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span><br/>');
  }
}
?>
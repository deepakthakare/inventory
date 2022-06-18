<?php
# @Author: Deepak

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Stores extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('stores_model');
        $this->load->library('form_validation');
        $this->load->library('breadcrumbs');
        // $this->load->helper('fileUpload');
    }
    public function get_stores()
    {
        echo  $this->stores_model->get_stores();
    }
    public function index()
    {
        $this->layout->set_title('Store List');
        $this->load_datatables();
        $this->layout->add_js('../datatables/store_table.js');
        $this->breadcrumbs->admin_push('Dashboard', 'dashboard');
        $this->breadcrumbs->admin_push('Store List', 'stores');
        $this->layout->view_render('index');
    }

    public function add()
    {
        $this->layout->set_title('Add Store');
        $this->layout->add_js('../public/pekeupload/js/pekeUpload.js');
        $this->breadcrumbs->admin_push('Dashboard', 'dashboard');
        $this->breadcrumbs->admin_push('Stores List', 'stores');
        $this->breadcrumbs->admin_push('Add Stores', 'stores/add');
        $data = array(
            'button' => 'Add',
            'action' => admin_url('stores/add_stores'),
            'id' => set_value(''),
            'name' => set_value('name'),
        );
        $this->layout->view_render('add', $data);
    }

    public function add_stores()
    {
        $this->_rules();
        if ($this->form_validation->run() == FALSE) {
            $this->add();
        } else {
            $name = $this->input->post('name', TRUE);
            $data = array(
                'name' => $name,
            );

            $result = $this->stores_model->add($data);

            if ($result) {
                $this->activity_model->add(array('login_id' => $this->login_id, 'activity' => ucfirst($this->username) . ' added a store at ' . date("M d, Y H:i")));
                $this->session->set_flashdata(array('message' => 'Store Added Successfully', 'type' => 'success'));
            } else {
                $this->session->set_flashdata(array('message' => 'Something went wrong. Try again', 'type' => 'warning'));
            }
            redirectToAdmin('stores');
        }
    }


    public function _rules()
    {
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span><br/>');
    }
}

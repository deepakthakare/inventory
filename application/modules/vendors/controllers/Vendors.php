<?php
# @Author: Deepak

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Vendors extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('vendors_model');
        $this->load->library('form_validation');
        $this->load->library('breadcrumbs');
        $this->load->model('warehouse/warehouse_model');
        //$this->load->helper('fileUpload');
    }
    public function index()
    {
        $this->layout->set_title('Vendors List');
        $this->load_datatables();
        $this->layout->add_js('../datatables/vendor_table.js');
        $this->breadcrumbs->admin_push('Dashboard', 'dashboard');
        $this->breadcrumbs->admin_push('Vendors List', 'vendors');
        $this->layout->view_render('index');
    }

    public function get_vendors()
    {
        echo  $this->vendors_model->get_vendors();
    }

    public function add()
    {
        $this->layout->set_title('Add Vendor');
        $this->breadcrumbs->admin_push('Dashboard', 'dashboard');
        $this->breadcrumbs->admin_push('Vendors List', 'vendors');
        $this->breadcrumbs->admin_push('Add Vendor', 'vendors/add');
        $data = array(
            'button' => 'Add',
            'action' => admin_url('vendors/add_vendor'),
            'id' => set_value(''),
            'name' => set_value('name'),
            'code' => set_value('code'),
        );
        $data['warehouse_list'] = $this->warehouse_model->get_all();
        $this->layout->view_render('add', $data);
    }
    public function add_vendor()
    {
        $this->_rules();
        if ($this->form_validation->run() == FALSE) {
            $this->add();
        } else {
            $name = $this->input->post('name', TRUE);
            $code = $this->input->post('code', TRUE);
            $data = array(
                'name' => $name,
                'code' => $code,
                'warehouse_name' => json_encode($this->input->post('warehouse_value', TRUE)),
            );
            $result = $this->vendors_model->add($data);

            //$result = $this->vendors_model->add(array('name' => $name, 'updated_at' => date("Y-m-d h:i:s")));
            if ($result) {
                $this->activity_model->add(array('login_id' => $this->login_id, 'activity' => ucfirst($this->username) . ' created a category at ' . date("M d, Y H:i")));
                $this->session->set_flashdata(array('message' => 'Vendor Added Successfully', 'type' => 'success'));
            } else {
                $this->session->set_flashdata(array('message' => 'Something went wrong. Try again', 'type' => 'warning'));
            }
            redirectToAdmin('vendors');
        }
    }
    
    public function edit($id)
    {
        $this->breadcrumbs->admin_push('Dashboard', 'dashboard');
        $this->breadcrumbs->admin_push('Vendor List', 'vendors');
        $this->breadcrumbs->admin_push('Edit Vendor', 'vendors/edit/' . $id);
        $row = $this->vendors_model->get_by_id($id);
        if ($row) {
            $data = [
                'button' => 'Update',
                'id' => $id,
                'action' => admin_url('vendors/edit_vendor/' . $id)
            ];
            $data['warehouse_list'] = $this->warehouse_model->get_all();
            $data['edit_data'] = $row;
            $this->layout->view_render('edit', $data);
        } else {
            $this->session->set_flashdata(array('message' => 'No Records Found', 'type' => 'warning'));
            redirectToAdmin('vendors');
        }
    }

    public function edit_vendor($id)
    {
        $this->_rulesEdit();
        if ($this->form_validation->run() == FALSE) {
            redirectToAdmin('vendors/edit/' . $id);
        }
        $data = [
            'name' => $this->input->post('name', TRUE),
            'code' => $this->input->post('code', TRUE),
            'warehouse_name' => json_encode($this->input->post('warehouse_value', TRUE)),
            'updated_at' => date("Y-m-d h:i:s")
        ];
        $result = $this->vendors_model->edit($id, $data);
        if ($result) {
            $this->session->set_flashdata(array('message' => 'Vendor updated Successfully', 'type' => 'success'));
        } else {
            $this->session->set_flashdata(array('message' => 'Something went wrong. Try again', 'type' => 'warning'));
        }
        redirectToAdmin('vendors');
    }


    public function delete()
    {
        $vendor_id = $this->input->post('vendor_id');
        $result = $this->vendors_model->edit($vendor_id, array('is_deleted' => '1', 'updated_at' => date("Y-m-d h:i:s")));
        if ($result) {
            $this->activity_model->add(array('login_id' => $this->login_id, 'activity' => ucfirst($this->username) . ' deleted a brand at ' . date("M d, Y H:i")));
            echo json_encode(array('message' => 'Vendor deleted Successfully', 'type' => 'success'));
        } else {
            echo json_encode(array('message' => 'Something went wrong', 'type' => 'warning'));
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('code', 'Code', 'trim|required|is_unique[tbl_vendors.code]');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span><br/>');
    }
    public function _rulesEdit()
    {
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('code', 'Code', 'trim|required');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span><br/>');
    }
}

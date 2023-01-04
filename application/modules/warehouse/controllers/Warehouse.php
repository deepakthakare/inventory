<?php
# @Author: Deepak

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Warehouse extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('warehouse_model');
        $this->load->library('form_validation');
        $this->load->library('breadcrumbs');
        $this->load->model('places/places_model');
        //$this->load->helper('fileUpload');
    }
    public function index()
    {
        $this->layout->set_title('Warehouse List');
        $this->load_datatables();
        $this->layout->add_js('../datatables/warehouse_table.js');
        $this->breadcrumbs->admin_push('Dashboard', 'dashboard');
        $this->breadcrumbs->admin_push('Warehouse List', 'warehouse');
        $this->layout->view_render('index');
    }

    public function get_warehouse()
    {
        echo  $this->warehouse_model->get_warehouse();
    }

    public function add()
    {
        $this->layout->set_title('Add Warehouse');
        $this->breadcrumbs->admin_push('Dashboard', 'dashboard');
        $this->breadcrumbs->admin_push('Warehouse List', 'warehouse');
        $this->breadcrumbs->admin_push('Add Warehouse', 'Warehouse/add');
        $data = array(
            'button' => 'Add',
            'action' => admin_url('warehouse/add_warehouse'),
            'id' => set_value(''),
            'name' => set_value('name'),
            'code' => set_value(''),
            'address' => set_value(''),
        );
        $data['places_list'] = $this->places_model->get_all();
        $this->layout->view_render('add', $data);
    }
    public function add_warehouse()
    {
        $this->_rules();
        if ($this->form_validation->run() == FALSE) {
            $this->add();
        } else {
            $data = [
                'name'=>$this->input->post('name', TRUE),
                'code'=>$this->input->post('code', TRUE),
                'address'=>$this->input->post('address', TRUE),
                'place_name' => json_encode($this->input->post('place_value', TRUE)),
            ];
            $result = $this->warehouse_model->add($data);
            if ($result) {
                $this->activity_model->add(array('login_id' => $this->login_id, 'activity' => ucfirst($this->username) . ' created a category at ' . date("M d, Y H:i")));
                $this->session->set_flashdata(array('message' => 'Warehouse Added Successfully', 'type' => 'success'));
            } else {
                $this->session->set_flashdata(array('message' => 'Something went wrong. Try again', 'type' => 'warning'));
            }
            redirectToAdmin('warehouse');
        }
    }
    public function edit($id)
    {
        $this->breadcrumbs->admin_push('Dashboard', 'dashboard');
        $this->breadcrumbs->admin_push('Warehouse List', 'warehouse');
        $this->breadcrumbs->admin_push('Edit Warehouse', 'warehouse/edit/' . $id);
        $row = $this->warehouse_model->get_by_id($id);
        if ($row) {
            $data = [
                'button' => 'Update',
                'id' => $id,
                'action' => admin_url('warehouse/edit_warehouse/' . $id)
            ];
            $data['places_list'] = $this->places_model->get_all();
            $data['edit_data'] = $row;
            $this->layout->view_render('edit', $data);
        } else {
            $this->session->set_flashdata(array('message' => 'No Records Found', 'type' => 'warning'));
            redirectToAdmin('warehouse');
        }
    }

    public function edit_warehouse($id)
    {
        $this->_rulesEdit();
        if ($this->form_validation->run() == FALSE) {
            redirectToAdmin('warehouse/edit/' . $id);
        }
        $data = [
            'name' => $this->input->post('name', TRUE),
            'code' => $this->input->post('code', TRUE),
            'address' => $this->input->post('address', TRUE),
            'place_name' => json_encode($this->input->post('place_value', TRUE)),
            'updated_at' => date("Y-m-d h:i:s")
        ];
        $result = $this->warehouse_model->edit($id, $data);
        if ($result) {
            $this->session->set_flashdata(array('message' => 'Warehouse updated Successfully', 'type' => 'success'));
        } else {
            $this->session->set_flashdata(array('message' => 'Something went wrong. Try again', 'type' => 'warning'));
        }
        redirectToAdmin('warehouse');
    }
    public function delete()
    {
        $warehouse_id = $this->input->post('warehouse_id');
        $result = $this->vwarehouse_model->edit($warehouse_id, array('is_deleted' => '1', 'updated_at' => date("Y-m-d h:i:s")));
        if ($result) {
            $this->activity_model->add(array('login_id' => $this->login_id, 'activity' => ucfirst($this->username) . ' deleted a brand at ' . date("M d, Y H:i")));
            echo json_encode(array('message' => 'warehouse deleted Successfully', 'type' => 'success'));
        } else {
            echo json_encode(array('message' => 'Something went wrong', 'type' => 'warning'));
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('code', 'Code', 'trim|required|is_unique[tbl_warehouse.code]');
        // $this->form_validation->set_rules('place_value[]', 'Warehouse Place', 'trim|required');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span><br/>');
    }

    public function _rulesEdit()
    {
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('code', 'Code', 'trim|required');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span><br/>');
    }
}

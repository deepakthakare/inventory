<?php
# @Author: Deepak

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Aisle extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('aisle_model');
        $this->load->library('form_validation');
        $this->load->library('breadcrumbs');
        $this->load->model('sections/sections_model');
    }
    public function index()
    {
        $this->layout->set_title('Aisle List');
        $this->load_datatables();
        $this->layout->add_js('../datatables/aisle_table.js');
        $this->breadcrumbs->admin_push('Dashboard', 'dashboard');
        $this->breadcrumbs->admin_push('Aisle List', 'aisle');
        $this->layout->view_render('index');
    }

    public function get_aisle()
    {
        echo  $this->aisle_model->get_aisle();
    }

    public function add()
    {
        $this->layout->set_title('Add Warehouse Aisle');
        $this->breadcrumbs->admin_push('Dashboard', 'dashboard');
        $this->breadcrumbs->admin_push('Aisle List', 'aisle');
        $this->breadcrumbs->admin_push('Add Warehouse Aisle', 'aisle/add');
        $data = array(
            'button' => 'Add',
            'action' => admin_url('aisle/add_aisle'),
            'aisle_id' => set_value(''),
            'name' => set_value('name'),
            'code' => set_value('code'),
        );
        $data['sections_list'] = $this->sections_model->get_all();
        $this->layout->view_render('add', $data);
    }
    public function add_aisle()
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
                'section_id' => json_encode($this->input->post('sections_value', TRUE)),
            );

            $result = $this->aisle_model->add($data);

            if ($result) {
                $this->activity_model->add(array('login_id' => $this->login_id, 'activity' => ucfirst($this->username) . ' created a category at ' . date("M d, Y H:i")));
                $this->session->set_flashdata(array('message' => 'Aisle Added Successfully', 'type' => 'success'));
            } else {
                $this->session->set_flashdata(array('message' => 'Something went wrong. Try again', 'type' => 'warning'));
            }
            redirectToAdmin('aisle');
        }
    }

    public function edit($id)
    {
        $this->breadcrumbs->admin_push('Dashboard', 'dashboard');
        $this->breadcrumbs->admin_push('Warehouse Aisle List', 'aisle');
        $this->breadcrumbs->admin_push('Edit Warehouse Aisle', 'aisle/edit/' . $id);
        $row = $this->aisle_model->get_by_id($id);
        if ($row) {
            $data = [
                'button' => 'Update',
                'aisle_id' => $id,
                'action' => admin_url('aisle/edit_aisle/' . $id)
            ];
           $data['sections_list'] = $this->sections_model->get_all();
            $data['edit_data'] = $row;
            $this->layout->view_render('edit', $data);
        } else {
            $this->session->set_flashdata(array('message' => 'No Records Found', 'type' => 'warning'));
            redirectToAdmin('aisle');
        }
    }

    public function edit_aisle($id)
    {
        $this->_rulesEdit();
        if ($this->form_validation->run() == FALSE) {
            redirectToAdmin('aisle/edit/' . $id);
        }
        $data = [
            'name' => $this->input->post('name', TRUE),
            'code' => $this->input->post('code', TRUE),
            'section_id' => json_encode($this->input->post('sections_value', TRUE)),
            'updated_at' => date("Y-m-d h:i:s")
        ];
        $result = $this->aisle_model->edit($id, $data);
        if ($result) {
            $this->session->set_flashdata(array('message' => 'aisle updated Successfully', 'type' => 'success'));
        } else {
            $this->session->set_flashdata(array('message' => 'Something went wrong. Try again', 'type' => 'warning'));
        }
        redirectToAdmin('aisle');
    }

    public function delete()
    {
        $aisle_id = $this->input->post('aisle_id');
        $result = $this->aisle_model->edit($aisle_id, array('is_deleted' => '1', 'updated_at' => date("Y-m-d h:i:s")));
        if ($result) {
            $this->activity_model->add(array('login_id' => $this->login_id, 'activity' => ucfirst($this->username) . ' deleted a aisle at ' . date("M d, Y H:i")));
            echo json_encode(array('message' => 'Aisle deleted Successfully', 'type' => 'success'));
        } else {
            echo json_encode(array('message' => 'Something went wrong', 'type' => 'warning'));
        }
    }


    public function _rules()
    {
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
       // $this->form_validation->set_rules('warehouse_value', 'Warehouse', 'trim|required');
        $this->form_validation->set_rules('code', 'Code', 'trim|required|is_unique[tbl_warehouse_aisle.code]');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span><br/>');
    }

    public function _rulesEdit()
    {
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('code', 'Code', 'trim|required');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span><br/>');
    }
}
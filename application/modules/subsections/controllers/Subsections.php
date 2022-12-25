<?php
# @Author: Deepak

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Subsections extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('subsections_model');
        $this->load->library('form_validation');
        $this->load->library('breadcrumbs');
        // $this->load->model('warehouse/warehouse_model');
    }
    public function index()
    {
        $this->layout->set_title('Sub-Sections List');
        $this->load_datatables();
        $this->layout->add_js('../datatables/subsections_table.js');
        $this->breadcrumbs->admin_push('Dashboard', 'dashboard');
        $this->breadcrumbs->admin_push('Sub Sections List', 'subsections');
        $this->layout->view_render('index');
    }

    public function get_subsections()
    {
        echo  $this->subsections_model->get_subsections();
    }

    public function add()
    {
        $this->layout->set_title('Add Warehouse Sub-Section');
        $this->breadcrumbs->admin_push('Dashboard', 'dashboard');
        $this->breadcrumbs->admin_push('Sub-Sections List', 'subsections');
        $this->breadcrumbs->admin_push('Add Warehouse Sub-Section', 'subsections/add');
        $data = array(
            'button' => 'Add',
            'action' => admin_url('subsections/add_subsections'),
            'subsection_id' => set_value(''),
            'name' => set_value('name'),
            'code' => set_value('code'),
        );
        //$data['warehouse_list'] = $this->warehouse_model->get_all();
        $this->layout->view_render('add', $data);
    }
    public function add_subsections()
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
                //'warehouse_name' => json_encode($this->input->post('warehouse_value', TRUE)),
            );
            $result = $this->subsections_model->add($data);

            if ($result) {
                $this->activity_model->add(array('login_id' => $this->login_id, 'activity' => ucfirst($this->username) . ' created a category at ' . date("M d, Y H:i")));
                $this->session->set_flashdata(array('message' => 'SubSections Added Successfully', 'type' => 'success'));
            } else {
                $this->session->set_flashdata(array('message' => 'Something went wrong. Try again', 'type' => 'warning'));
            }
            redirectToAdmin('subsections');
        }
    }

    public function edit($id)
    {
        $this->breadcrumbs->admin_push('Dashboard', 'dashboard');
        $this->breadcrumbs->admin_push('Warehouse Sub-Sections List', 'subsections');
        $this->breadcrumbs->admin_push('Edit Warehouse Sub-Sections', 'subsections/edit/' . $id);
        $row = $this->subsections_model->get_by_id($id);
        if ($row) {
            $data = [
                'button' => 'Update',
                'subsection_id' => $id,
                'action' => admin_url('subsections/edit_subsections/' . $id)
            ];
           // $data['warehouse_list'] = $this->warehouse_model->get_all();
            $data['edit_data'] = $row;
            $this->layout->view_render('edit', $data);
        } else {
            $this->session->set_flashdata(array('message' => 'No Records Found', 'type' => 'warning'));
            redirectToAdmin('subsections');
        }
    }

    public function edit_subsections($id)
    {
        $this->_rulesEdit();
        if ($this->form_validation->run() == FALSE) {
            redirectToAdmin('subsections/edit/' . $id);
        }
        $data = [
            'name' => $this->input->post('name', TRUE),
            'code' => $this->input->post('code', TRUE),
            //'warehouse_name' => json_encode($this->input->post('warehouse_value', TRUE)),
            'updated_at' => date("Y-m-d h:i:s")
        ];
        $result = $this->subsections_model->edit($id, $data);
        if ($result) {
            $this->session->set_flashdata(array('message' => 'Sub-Sections updated Successfully', 'type' => 'success'));
        } else {
            $this->session->set_flashdata(array('message' => 'Something went wrong. Try again', 'type' => 'warning'));
        }
        redirectToAdmin('subsections');
    }

    public function delete()
    {
        $subsections_id = $this->input->post('subsection_id');
        $result = $this->subsections_model->edit($subsections_id, array('is_deleted' => '1', 'updated_at' => date("Y-m-d h:i:s")));
        if ($result) {
            $this->activity_model->add(array('login_id' => $this->login_id, 'activity' => ucfirst($this->username) . ' deleted a Sub-Sections at ' . date("M d, Y H:i")));
            echo json_encode(array('message' => 'Sub-Sections deleted Successfully', 'type' => 'success'));
        } else {
            echo json_encode(array('message' => 'Something went wrong', 'type' => 'warning'));
        }
    }


    public function _rules()
    {
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
       // $this->form_validation->set_rules('warehouse_value', 'Warehouse', 'trim|required');
        $this->form_validation->set_rules('code', 'Code', 'trim|required|is_unique[tbl_warehouse_subsections.code]');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span><br/>');
    }

    public function _rulesEdit()
    {
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('code', 'Code', 'trim|required');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span><br/>');
    }
}
<?php
# @Author: Deepak

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Sections extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('sections_model');
        $this->load->library('form_validation');
        $this->load->library('breadcrumbs');
        $this->load->model('subsections/subsections_model');
    }
    public function index()
    {
        $this->layout->set_title('Sections List');
        $this->load_datatables();
        $this->layout->add_js('../datatables/sections_table.js');
        $this->breadcrumbs->admin_push('Dashboard', 'dashboard');
        $this->breadcrumbs->admin_push('Sections List', 'sections');
        $this->layout->view_render('index');
    }

    public function get_sections()
    {
        echo  $this->sections_model->get_sections();
    }

    public function add()
    {
        $this->layout->set_title('Add Warehouse Section');
        $this->breadcrumbs->admin_push('Dashboard', 'dashboard');
        $this->breadcrumbs->admin_push('Sections List', 'sections');
        $this->breadcrumbs->admin_push('Add Warehouse Section', 'sections/add');
        $data = array(
            'button' => 'Add',
            'action' => admin_url('sections/add_sections'),
            'section_id' => set_value(''),
            'name' => set_value('name'),
            'code' => set_value('code'),
        );
        $data['subsections_list'] = $this->subsections_model->get_all();
        $this->layout->view_render('add', $data);
    }
    public function add_sections()
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
                'subsection_id' => json_encode($this->input->post('subsections_value', TRUE)),
            );
            $result = $this->sections_model->add($data);

            if ($result) {
                $this->activity_model->add(array('login_id' => $this->login_id, 'activity' => ucfirst($this->username) . ' created a category at ' . date("M d, Y H:i")));
                $this->session->set_flashdata(array('message' => 'Sections Added Successfully', 'type' => 'success'));
            } else {
                $this->session->set_flashdata(array('message' => 'Something went wrong. Try again', 'type' => 'warning'));
            }
            redirectToAdmin('sections');
        }
    }

    public function edit($id)
    {
        $this->breadcrumbs->admin_push('Dashboard', 'dashboard');
        $this->breadcrumbs->admin_push('Warehouse Sections List', 'sections');
        $this->breadcrumbs->admin_push('Edit Warehouse Sections', 'sections/edit/' . $id);
        $row = $this->sections_model->get_by_id($id);
        if ($row) {
            $data = [
                'button' => 'Update',
                'subsection_id' => $id,
                'action' => admin_url('sections/edit_sections/' . $id)
            ];
           $data['subsections_list'] = $this->subsections_model->get_all();
            $data['edit_data'] = $row;
            $this->layout->view_render('edit', $data);
        } else {
            $this->session->set_flashdata(array('message' => 'No Records Found', 'type' => 'warning'));
            redirectToAdmin('sections');
        }
    }

    public function edit_sections($id)
    {
        $this->_rulesEdit();
        if ($this->form_validation->run() == FALSE) {
            redirectToAdmin('sections/edit/' . $id);
        }
        $data = [
            'name' => $this->input->post('name', TRUE),
            'code' => $this->input->post('code', TRUE),
            'subsection_id' => json_encode($this->input->post('subsections_value', TRUE)),
            'updated_at' => date("Y-m-d h:i:s")
        ];
        $result = $this->sections_model->edit($id, $data);
        if ($result) {
            $this->session->set_flashdata(array('message' => 'Sections updated Successfully', 'type' => 'success'));
        } else {
            $this->session->set_flashdata(array('message' => 'Something went wrong. Try again', 'type' => 'warning'));
        }
        redirectToAdmin('sections');
    }

    public function delete()
    {
        $sections_id = $this->input->post('section_id');
        $result = $this->sections_model->edit($sections_id, array('is_deleted' => '1', 'updated_at' => date("Y-m-d h:i:s")));
        if ($result) {
            $this->activity_model->add(array('login_id' => $this->login_id, 'activity' => ucfirst($this->username) . ' deleted a Sections at ' . date("M d, Y H:i")));
            echo json_encode(array('message' => 'Sections deleted Successfully', 'type' => 'success'));
        } else {
            echo json_encode(array('message' => 'Something went wrong', 'type' => 'warning'));
        }
    }


    public function _rules()
    {
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
       // $this->form_validation->set_rules('warehouse_value', 'Warehouse', 'trim|required');
        $this->form_validation->set_rules('code', 'Code', 'trim|required|is_unique[tbl_warehouse_sections.code]');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span><br/>');
    }

    public function _rulesEdit()
    {
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('code', 'Code', 'trim|required');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span><br/>');
    }
}
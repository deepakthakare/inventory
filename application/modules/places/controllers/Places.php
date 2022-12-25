<?php
# @Author: Deepak

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Places extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('places_model');
        $this->load->library('form_validation');
        $this->load->library('breadcrumbs');
        $this->load->model('aisle/aisle_model');
    }
    public function index()
    {
        $this->layout->set_title('Places List');
        $this->load_datatables();
        $this->layout->add_js('../datatables/places_table.js');
        $this->breadcrumbs->admin_push('Dashboard', 'dashboard');
        $this->breadcrumbs->admin_push('Warehouse Places List', 'places');
        $this->layout->view_render('index');
    }

    public function get_places()
    {
        echo  $this->places_model->get_places();
    }

    public function add()
    {
        $this->layout->set_title('Add Warehouse Place');
        $this->breadcrumbs->admin_push('Dashboard', 'dashboard');
        $this->breadcrumbs->admin_push('Warehouse Places List', 'places');
        $this->breadcrumbs->admin_push('Add Warehouse Place', 'places/add');
        $data = array(
            'button' => 'Add',
            'action' => admin_url('places/add_place'),
            'place_id' => set_value(''),
            'name' => set_value('name'),
            'code' => set_value('code'),
        );
        $data['aisle_list'] = $this->aisle_model->get_all();
        $this->layout->view_render('add', $data);
    }
    public function add_place()
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
                'aisle_id' => json_encode($this->input->post('aisle_value', TRUE)),
            );
            // echo "<pre>";
            // print_r($data);
            // die;
            $result = $this->places_model->add($data);

            if ($result) {
                $this->activity_model->add(array('login_id' => $this->login_id, 'activity' => ucfirst($this->username) . ' created a category at ' . date("M d, Y H:i")));
                $this->session->set_flashdata(array('message' => 'Place Added Successfully', 'type' => 'success'));
            } else {
                $this->session->set_flashdata(array('message' => 'Something went wrong. Try again', 'type' => 'warning'));
            }
            redirectToAdmin('places');
        }
    }

    public function edit($id)
    {
        $this->breadcrumbs->admin_push('Dashboard', 'dashboard');
        $this->breadcrumbs->admin_push('Warehouse Places List', 'places');
        $this->breadcrumbs->admin_push('Edit Warehouse Places', 'places/edit/' . $id);
        $row = $this->places_model->get_by_id($id);
        if ($row) {
            $data = [
                'button' => 'Update',
                'place_id' => $id,
                'action' => admin_url('places/edit_place/' . $id)
            ];
            $data['aisle_list'] = $this->aisle_model->get_all();
            $data['edit_data'] = $row;
            $this->layout->view_render('edit', $data);
        } else {
            $this->session->set_flashdata(array('message' => 'No Records Found', 'type' => 'warning'));
            redirectToAdmin('places');
        }
    }

    public function edit_place($id)
    {
        $this->_rulesEdit();
        if ($this->form_validation->run() == FALSE) {
            redirectToAdmin('places/edit/' . $id);
        }
        $data = [
            'name' => $this->input->post('name', TRUE),
            'code' => $this->input->post('code', TRUE),
            'aisle_id' => json_encode($this->input->post('aisle_value', TRUE)),
            'updated_at' => date("Y-m-d h:i:s")
        ];
        $result = $this->places_model->edit($id, $data);
        if ($result) {
            $this->session->set_flashdata(array('message' => 'place updated Successfully', 'type' => 'success'));
        } else {
            $this->session->set_flashdata(array('message' => 'Something went wrong. Try again', 'type' => 'warning'));
        }
        redirectToAdmin('places');
    }

    public function delete()
    {
        $place_id = $this->input->post('places_id');
        $result = $this->places_model->edit($place_id, array('is_deleted' => '1', 'updated_at' => date("Y-m-d h:i:s")));
        if ($result) {
            $this->activity_model->add(array('login_id' => $this->login_id, 'activity' => ucfirst($this->username) . ' deleted a place at ' . date("M d, Y H:i")));
            echo json_encode(array('message' => 'Place deleted Successfully', 'type' => 'success'));
        } else {
            echo json_encode(array('message' => 'Something went wrong', 'type' => 'warning'));
        }
    }


    public function _rules()
    {
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
       // $this->form_validation->set_rules('warehouse_value', 'Warehouse', 'trim|required');
        $this->form_validation->set_rules('code', 'Code', 'trim|required|is_unique[tbl_warehouse_places.code]');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span><br/>');
    }

    public function _rulesEdit()
    {
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('code', 'Code', 'trim|required');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span><br/>');
    }
}
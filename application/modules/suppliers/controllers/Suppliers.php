<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Suppliers extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('suppliers_model');
        $this->load->model('country/country_model');
        $this->load->model('currency/currency_model');
        $this->load->library('form_validation');
        $this->load->library('breadcrumbs');
        // $this->layout->add_js('custom/supplier.js');
    }
    public function index()
    {
        $this->layout->set_title('Supplier List');
        $this->load_datatables();
        $this->layout->add_js('../datatables/supplier_table.js');
        $this->breadcrumbs->admin_push('Dashboard', 'dashboard');
        $this->breadcrumbs->admin_push('Supplier List', 'supplier');
        $this->layout->view_render('index');
    }

    public function get_supplier()
    {
        echo $this->suppliers_model->get_supplier();
    }

    public function get_by_id()
    {

        $result = $this->suppliers_model->get_by_id($this->input->post('id_supplier'));
        if ($result) {
            echo json_encode(array('message' => 'View Supplier Details', 'type' => 'success', "data" => $result));
        } else {
            echo json_encode(array('message' => 'Something went wrong', 'type' => 'warning'));
        }
    }

    public function add()
    {
        $this->layout->set_title('Add Supplier');
        $this->breadcrumbs->admin_push('Dashboard', 'dashboard');
        $this->breadcrumbs->admin_push('Supplier List', 'supplier');
        $this->breadcrumbs->admin_push('Add Supplier', 'suppliers/add');

        $data = array(
            'button' => 'Add',
            'action' => admin_url('suppliers/add_suppliers'),
            'id_supplier' => set_value(''),
            'name' => set_value('name'),
            'company' => set_value('company'),
            'supplier_code' => set_value('supplier_code'),
            'email' => set_value('email'),
            'description' => set_value('description'),
            'phone' => set_value('phone'),
            'mobile' => set_value('mobile'),
            'fax' => set_value('fax'),
            'address' => set_value('address'),
            'address_two' => set_value('address_two'),
            'zipcode' => set_value('zipcode'),
            'city' => set_value('city'),
            'customer_markup' => set_value('customer_markup'),
            'markup_pn' => set_value('markup_pn'),
        );

        $data['country_list'] = $this->country_model->get_all();
        $data['currency_list'] = $this->currency_model->get_all();
        $this->layout->view_render('add', $data);
    }

    public function add_suppliers()
    {
        $this->_rules();
        if ($this->form_validation->run() == FALSE) {
            $this->add();
        } else {
            $data = array(
                'name' => $this->input->post('name', TRUE),
                'company' => $this->input->post('company', TRUE),
                'supplier_code' => $this->input->post('supplier_code', TRUE),
                'email' => $this->input->post('email', TRUE),
                'description' => $this->input->post('description', TRUE),
                'phone' => $this->input->post('phone', TRUE),
                'mobile' => $this->input->post('mobile', TRUE),
                'fax' => $this->input->post('fax', TRUE),
                'address' => $this->input->post('address', TRUE),
                'address_two' => $this->input->post('address_two', TRUE),
                'zipcode' => $this->input->post('zipcode', TRUE),
                'city' => $this->input->post('city', TRUE),
                'customer_markup' => $this->input->post('customer_markup', TRUE),
                'markup_pn' => $this->input->post('markup_pn', TRUE),
                'city' => $this->input->post('city', TRUE),
                'id_country' => $this->input->post('supplier_country', TRUE),
                'id_currency' => $this->input->post('supplier_currency', TRUE),

            );
            $result = $this->suppliers_model->add($data);
            if ($result) {
                $this->activity_model->add(array('login_id' => $this->login_id, 'activity' => ucfirst($this->username) . ' added a supplier at ' . date("M d, Y H:i")));
                $this->session->set_flashdata(array('message' => 'Supplier Added Successfully', 'type' => 'success'));
            } else {
                $this->session->set_flashdata(array('message' => 'Something went wrong. Try again', 'type' => 'warning'));
            }
            redirectToAdmin('suppliers');
        }
    }

    public function edit($id_supplier)
    {
        $this->breadcrumbs->admin_push('Dashboard', 'dashboard');
        $this->breadcrumbs->admin_push('Supplier List', 'supplier');
        $this->breadcrumbs->admin_push('Edit Supplier', 'suppliers/edit/' . $id_supplier);
        $row = $this->suppliers_model->get_by_id($id_supplier);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'id_supplier' => $id_supplier,
                'action' => admin_url('suppliers/edit_suppliers/' . $id_supplier)
            );

            $data['edit_data'] = $row;
            $data['country_list'] = $this->country_model->get_all();
            $data['currency_list'] = $this->currency_model->get_all();
            // echo "<pre>"; print_r($row); die;

            $this->layout->view_render('edit', $data);
        } else {
            $this->session->set_flashdata(array('message' => 'No Records Found', 'type' => 'warning'));
            redirectToAdmin('suppliers');
        }
    }

    public function edit_suppliers($id_supplier)
    {
        $this->_rules();
        if ($this->form_validation->run() == FALSE) {
            redirectToAdmin('suppliers/edit/' . $id_supplier);
        }

        $data_to_update = array(
            'name' => $this->input->post('name', TRUE),
            'company' => $this->input->post('company', TRUE),
            'supplier_code' => $this->input->post('supplier_code', TRUE),
            'email' => $this->input->post('email', TRUE),
            'description' => $this->input->post('description', TRUE),
            'phone' => $this->input->post('phone', TRUE),
            'mobile' => $this->input->post('mobile', TRUE),
            'fax' => $this->input->post('fax', TRUE),
            'address' => $this->input->post('address', TRUE),
            'address_two' => $this->input->post('address_two', TRUE),
            'zipcode' => $this->input->post('zipcode', TRUE),
            'city' => $this->input->post('city', TRUE),
            'customer_markup' => $this->input->post('customer_markup', TRUE),
            'markup_pn' => $this->input->post('markup_pn', TRUE),
            'city' => $this->input->post('city', TRUE),
            'id_country' => $this->input->post('supplier_country', TRUE),
            'id_currency' => $this->input->post('supplier_currency', TRUE),
            'updated_at' => date("Y-m-d h:i:s")
        );

        $result = $this->suppliers_model->edit($id_supplier, $data_to_update);

        if ($result) {
            $this->session->set_flashdata(array('message' => 'Supplier updated Successfully', 'type' => 'success'));
        } else {
            $this->session->set_flashdata(array('message' => 'Something went wrong. Try again', 'type' => 'warning'));
        }
        redirectToAdmin('suppliers');
    }
    public function delete()
    {
        $id_supplier = $this->input->post('id_supplier');
        $result = $this->suppliers_model->edit($id_supplier, array('is_deleted' => '1', 'updated_at' => date("Y-m-d h:i:s")));
        if ($result) {
            $this->activity_model->add(array('login_id' => $this->login_id, 'activity' => ucfirst($this->username) . ' deleted a supplier at ' . date("M d, Y H:i")));
            echo json_encode(array('message' => 'Supplier deleted Successfully', 'type' => 'success'));
        } else {
            echo json_encode(array('message' => 'Something went wrong', 'type' => 'warning'));
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('company', 'Company', 'trim|required');
        $this->form_validation->set_rules('supplier_code', 'Supplier Code', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required');
        $this->form_validation->set_rules('address', 'Address', 'trim|required');
        $this->form_validation->set_rules('supplier_country', 'Country', 'trim|required');
        $this->form_validation->set_rules('supplier_currency', 'Currency', 'trim|required');
        $this->form_validation->set_rules('customer_markup', 'Customer Markup', array('trim', 'required', 'min_length[1]', 'regex_match[/(^\d+|^\d+[.]\d+)+$/]'));
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span><br/>');
    }
}

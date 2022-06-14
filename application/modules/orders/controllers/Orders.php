<?php
# @Author: Deepak

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Orders extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('orders_model');
        $this->load->library('form_validation');
        $this->load->library('breadcrumbs');
    }
    public function index()
    {
        $this->layout->set_title('Orders List');
        $this->load_datatables();
        $this->layout->add_js('../datatables/order_table.js');
        $this->breadcrumbs->admin_push('Dashboard', 'dashboard');
        $this->breadcrumbs->admin_push('IFIF Orders List', 'orders');
        $this->layout->view_render('index');
    }
    public function get_orders()
    {
        echo  $this->orders_model->get_orders();
    }
}

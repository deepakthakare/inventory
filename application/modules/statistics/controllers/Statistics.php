<?php
# @Author: Deepak

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Statistics extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('statistics_model');
        $this->load->library('breadcrumbs');
    }
    public function index()
    {
        $this->layout->set_title('Statistics');
        $this->breadcrumbs->admin_push('Dashboard', 'dashboard');
        $this->breadcrumbs->admin_push('Statistics', 'statistics');
        $this->layout->view_render('index');
    }

    public function product_list()
    {
        $this->layout->set_title('View Product Statistics');
        $this->load_datatables();
        $this->layout->add_js('../datatables/stat_table.js');
        $this->breadcrumbs->admin_push('Dashboard', 'dashboard');
        $this->breadcrumbs->admin_push('Statistics', 'statistics');
        $this->breadcrumbs->admin_push('View Product Statistics', 'statistics/product_list');
        $this->layout->view_render('product_list');
    }

    public function get_products()
    {
        echo  $this->statistics_model->get_products();
    }
}

<?php
# @Author: Deepak


if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Activity extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->load->library('breadcrumbs');
    }
    public function index()
    {
        $this->layout->set_title('Activities');
        $this->load_datatables();
        $this->layout->add_js('../datatables/activity_table.js');
        $this->breadcrumbs->admin_push('Dashboard', 'dashboard');
        $this->breadcrumbs->admin_push('Activity Log', 'activity');
        $this->layout->view_render('index');
    }
    public function get_activities()
    {
        echo  $this->activity_model->get_activities();
    }
}

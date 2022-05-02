<?php
# @Author: Deepak

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Error_page extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->layout->switch_layout('template/login_layout');
    }

    public function index()
    {
        $this->layout->view_render('error_404');
    }
}

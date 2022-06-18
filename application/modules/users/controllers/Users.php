<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Users extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('users_model');
        $this->load->library('form_validation');
        $this->load->library('breadcrumbs');
    }

    public function get_users()
    {
        echo  $this->users_model->get_users();
    }

    function index()
    {
        $this->layout->set_title('User List');
        $this->load_datatables();
        $this->layout->add_js('../datatables/user_table.js');
        $this->breadcrumbs->admin_push('Dashboard', 'dashboard');
        $this->breadcrumbs->admin_push('User List', 'users');
        $this->layout->view_render('index');
    }



    public function add()
    {
        $this->layout->set_title('Add User');
        $this->layout->add_js('../public/pekeupload/js/pekeUpload.js');
        $this->breadcrumbs->admin_push('Dashboard', 'dashboard');
        $this->breadcrumbs->admin_push('Users List', 'users');
        $this->breadcrumbs->admin_push('Add Users', 'users/add');
        $data = array(
            'button' => 'Add',
            'action' => admin_url('users/add_users'),
            'login_id' => set_value(''),
            'username' => set_value(''),
            'password' => set_value(''),
            'store_id' => set_value(''),
        );
        $this->layout->view_render('add', $data);
    }

    public function add_users()
    {
        $this->_rules();
        if ($this->form_validation->run() == FALSE) {
            $this->add();
        } else {
            $username = $this->input->post('username', TRUE);
            $password = $this->password_hash($this->input->post('password'));
            $data = array(
                'username' => $username,
                'password' => $password,
            );

            $result = $this->users_model->add($data);
            print_r($result);
            if ($result == true) {
                $this->activity_model->add(array('login_id' => $this->login_id, 'activity' => ucfirst($this->username) . ' added a store at ' . date("M d, Y H:i")));
                $this->session->set_flashdata(array('message' => 'User Added Successfully', 'type' => 'success'));
            } else {
                $this->session->set_flashdata(array('message' => 'Something went wrong. Try again', 'type' => 'warning'));
            }
            redirectToAdmin('users');
        }
    }

    public function password_hash($pass = '')
    {
        if ($pass) {
            $password = password_hash($pass, PASSWORD_DEFAULT);
            return $password;
        }
    }

    public function edit($id)
    {
        // $this->layout->add_js('../public/pekeupload/js/pekeUpload.js');
        if (check_post()) {
            $this->_rules();
            if ($this->form_validation->run() == FALSE) {
                redirectToAdmin('users/edit/' . $id);
            }
            $name = $this->input->post('username', TRUE);
            $login_id = $this->input->post('login_id', TRUE);
            $data_to_update = array('username' => $name, 'updated_at' => date("Y-m-d h:i:s"));

            $result = $this->users_model->edit($login_id, $data_to_update);
            if ($result) {
                $this->session->set_flashdata(array('message' => 'Users updated Successfully', 'type' => 'success'));
            } else {
                $this->session->set_flashdata(array('message' => 'Something went wrong. Try again', 'type' => 'warning'));
            }
            redirectToAdmin('users');
        } else {
            $row = $this->users_model->get_by_id($id);
            if ($row) {
                $data = array(
                    'button' => 'Update',
                    'action' => admin_url('users/edit/' . $row->login_id),
                    'login_id' => set_value('login_id', $row->login_id),
                    // 'image_path' => set_value('image_path', $row->image_path),
                    'username' => set_value('username', $row->username)
                );
                $this->layout->view_render('add', $data);
            } else {
                $this->session->set_flashdata(array('message' => 'No Records Found', 'type' => 'warning'));
                redirectToAdmin('users');
            }
        }
    }


    public function _rules()
    {
        $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[5]|max_length[12]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]|max_length[12]');
        //$this->form_validation->set_rules('stores', 'Store', 'required');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span><br/>');
    }
}

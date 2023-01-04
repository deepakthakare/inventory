<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Users extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('stores/stores_model');
        $this->load->model('groups/groups_model');
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
            'username' => set_value('username'),
            'fname' => set_value('fname'),
            'lname' => set_value('lname'),
            'password' => set_value(''),

        );
        $data['stores_list'] = $this->stores_model->get_all();
        $data['groups_list'] = $this->groups_model->getGroupData();
        $this->layout->view_render('add', $data);
        // echo "<pre>";
        // print_r($data);
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
                'store_id' => $this->input->post('store_name', TRUE),
                'group_id' => $this->input->post('group_name', TRUE),
                'fname' => $this->input->post('fname', TRUE),
                'lname' => $this->input->post('lname', TRUE),
            );

            $result = $this->users_model->add($data);

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
        if (check_post()) {
            $this->_rulesEdit();
            if ($this->form_validation->run() == FALSE) {
                redirectToAdmin('users/edit/' . $id);
            }
            $name = $this->input->post('username', TRUE);
            $password = $this->password_hash($this->input->post('password', TRUE));
            $login_id = $this->input->post('login_id', TRUE);
            $group_name = $this->input->post('group_name', TRUE);
            $firstname = $this->input->post('fname', TRUE);
            $lastname = $this->input->post('lname', TRUE);
            if (empty($this->input->post('password'))) {
                $data_to_update =
                    array(
                        'fname' => $firstname,
                        'lname' => $lastname,
                        'group_id' => $group_name,
                        'updated_at' => date("Y-m-d h:i:s")
                    );
            } else {
                $data_to_update =
                    array(

                        'username' => $name,
                        'fname' => $firstname,
                        'lname' => $lastname,
                        'password' => $password,
                        'group_id' => $group_name,
                        'updated_at' => date("Y-m-d h:i:s")
                    );
            }
            /* print_r($data_to_update);
            die; */
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
                    'store_id' => set_value('store_id', $row->store_id),
                    'group_id' => set_value('group_id', $row->group_id),
                    'fname' => set_value('fname', $row->fname),
                    'lname' => set_value('lname', $row->lname),
                    'username' => set_value('username', $row->username),
                    'password' => $this->password_hash(set_value('password', $row->password)),
                );
                $data['stores_list'] = $this->stores_model->get_all();
                $data['groups_list'] = $this->groups_model->getGroupData();
                $this->layout->view_render('add', $data);
            } else {
                $this->session->set_flashdata(array('message' => 'No Records Found', 'type' => 'warning'));
                redirectToAdmin('users');
            }
        }
    }

    public function delete()
    {
        $login_id = $this->input->post('login_id');
        $result = $this->users_model->edit($login_id, array('is_deleted' => '1', 'updated_at' => date("Y-m-d h:i:s")));
        if ($result) {
            $this->activity_model->add(array('login_id' => $this->login_id, 'activity' => ucfirst($this->username) . ' deleted a user at ' . date("M d, Y H:i")));
            echo json_encode(array('message' => 'User deleted Successfully', 'type' => 'success'));
        } else {
            echo json_encode(array('message' => 'Something went wrong', 'type' => 'warning'));
        }
    }


    // For add User
    public function _rules()
    {
        $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[4]|max_length[12]|is_unique[tbl_login.username]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]|max_length[12]');
        $this->form_validation->set_rules('store_name', 'Store', 'required');
        $this->form_validation->set_rules('group_name', 'Group', 'required');
        $this->form_validation->set_rules('fname', 'First Name', 'required');
        $this->form_validation->set_rules('lname', 'Last Name', 'required');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span><br/>');
    }

    //for edit User
    public function _rulesEdit()
    {
        $this->form_validation->set_rules('fname', 'First Name', 'required');
        $this->form_validation->set_rules('lname', 'Last Name', 'required');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span><br/>');
    }
}

<?php
class Groups extends Admin_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('groups_model');
        $this->load->library('form_validation');
        $this->load->library('breadcrumbs');
    }
    public function index()
    {
        $this->layout->set_title('Groups List');
        $this->load_datatables();
        $this->layout->add_js('../datatables/groups_table.js');
        $this->breadcrumbs->admin_push('Dashboard', 'dashboard');
        $this->breadcrumbs->admin_push('Groups List', 'groups');
        $this->layout->view_render('index');
    }

    public function get_groups()
    {
        echo  $this->groups_model->get_groups();
    }

    public function add()
    {
        $this->layout->set_title('Manage Group');
        //  $this->layout->add_js('../public/pekeupload/js/pekeUpload.js');
        $this->breadcrumbs->admin_push('Dashboard', 'dashboard');
        $this->breadcrumbs->admin_push('Manage Groups', 'groups');
        $this->breadcrumbs->admin_push('Add Groups', 'groups/add');
        $data = array(
            'button' => 'Add',
            'action' => admin_url('groups/add_groups'),
            'id' => set_value(''),
            'group_name' => set_value(''),
            'permission' => set_value(''),

        );
        $this->layout->view_render('add', $data);
    }
    public function add_groups()
    {
        $this->_rules();
        if ($this->form_validation->run() == FALSE) {
            $this->add();
        } else {
            $permission = serialize($this->input->post('permission'));
            $data = array(
                'group_name' => $this->input->post('group_name'),
                'permission' => $permission
            );
            /* echo "<pre>";
            print_r($data); */

            $result = $this->groups_model->add($data);
            if ($result == true) {
                $this->session->set_flashdata('success', 'Successfully created');
                redirectToAdmin('groups');
            } else {
                $this->session->set_flashdata('errors', 'Error occurred!!');
                redirect('groups/add', 'refresh');
            }
        }
    }
    public function edit($id = null)
    {
        if ($id) {
            $this->breadcrumbs->admin_push('Dashboard', 'dashboard');
            $this->breadcrumbs->admin_push('Groups List', 'groups');
            $this->breadcrumbs->admin_push('Edit groups', 'groups/edit/' . $id);
            $this->form_validation->set_rules('group_name', 'Group name', 'required');

            // $row = $this->products_model->get_by_id($id);
            if ($this->form_validation->run() == TRUE) {
                // true case
                $permission = serialize($this->input->post('permission'));

                $data = array(
                    'group_name' => $this->input->post('group_name'),
                    'permission' => $permission
                );
                /* echo "<pre>";
                print_r($data); */

                $update = $this->groups_model->edit($data, $id);
                if ($update) {
                    $this->session->set_flashdata('success', 'Successfully updated');
                    redirect('groups/', 'refresh');
                } else {
                    $this->session->set_flashdata('errors', 'Error occurred!!');
                    redirect('groups/edit/' . $id, 'refresh');
                }
            } else {
                // false case
                $group_data = $this->groups_model->getGroupData($id);
                $data['group_data'] = $group_data;
                $this->layout->view_render('edit', $data);
                // $this->render_template('groups/edit', $this->data);
                //$this->layout->view_render('edit', $this->data);
                // echo "<pre>";
                // print_r($group_data);
            }
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('group_name', 'Group Name', 'trim|required');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span><br/>');
    }
}

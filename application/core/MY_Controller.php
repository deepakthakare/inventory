<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class MY_Controller extends MX_Controller
{

  function __construct()
  {
    parent::__construct();
  }

  public function check_loggedin()
  {
    $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $this->session->set_userdata('redirectToCurrent', $url);
    $user = $this->session->all_userdata();
    if (isset($user['admin_logged_in'])) return true;
    else return false;
  }
}
/**
 *
 */
class Controller extends MX_Controller
{
  function __construct()
  {
    parent::__construct();
  }
}

class Admin_Controller extends MY_Controller
{
  var $permission = array();

  public function __construct()
  {
    parent::__construct();
    $group_data = array();
    if (!$this->check_loggedin()) redirectToAdmin('admin/login');
    $this->load->model('activity/activity_model');
    $user = $this->session->all_userdata();
    // $aaa = $this->session->userdata('login_id');
    // print_r($aaa);
    if (empty($this->session->userdata('login_id'))) {
      $session_data = array('logged_in' => FALSE);
      $this->session->set_userdata($session_data);
    } else {
      $data = array();
      $this->username = $user['username'];
      $this->login_id = $user['login_id'];
      $this->store_id = $user['store_id'];
      $user_id = $this->session->userdata('login_id');
      $this->load->model('groups/groups_model');
      $group_data = $this->groups_model->getUserGroupByUserId($user_id);
      $data['user_permission'] = unserialize($group_data['permission']);
      $permission = unserialize($group_data['permission']);
      $this->layout->switch_layout_data('template/admin_layout', $data);
    }
  }
  public function load_datatables($page = null, $data = array())
  {
    $this->layout->add_css('../public/datatables/css/loading.css');
    $this->layout->add_css('../vendor/datatables/dataTables.bootstrap4.min.css');
    $this->layout->add_js('../public/datatables/js/jquery.dataTables.min.js');
    $this->layout->add_js('../public/datatables/js/dataTables.bootstrap4.min.js');
  }
}

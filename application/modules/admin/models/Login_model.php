<?php
# @Author: Deepak




if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Login_model extends CI_Model
{
    public $table = 'tbl_login';
    public $id = 'login_id';
    public $order = 'DESC';
    function __construct()
    {
        parent::__construct();
    }
    function process($username, $password)
    {
        $this->db->select('*');
        $this->db->from('tbl_login');
        $this->db->where("username", $username);
        $this->db->where("password", MD5($password));
        $this->db->where("is_deleted", 0);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            $row = $query->row();
            $id = $row->login_id;
            $username = $row->username;
            $password = $row->password;
            $data = array(
                "username" => $username,
                "login_id" => $id,
                "admin_logged_in" => true,
                "isadmin" => true
            );
            $this->session->set_userdata($data);
            return true;
        } else {
            return false;
        }
    } // End of process()

    function get_by_id($id)
    {
        $this->db->where($this->id, $id);
        return $this->db->get("tbl_login")->row();
    }
    function update($id, $data)
    {
        $this->db->where($this->id, $id);
        $this->db->update("tbl_login", $data);
    }

    // Dashboard Cart Statistics

    function getChartStat()
    {
        /* $query = "SELECT COUNT(order_id) as count,MONTHNAME(created_at) as month_name 
      FROM tbl_sales 
      WHERE YEAR(created_at) = '" . date('Y') . "'
      GROUP BY YEAR(created_at), MONTH(created_at)";
    return $this->db->query($query)->result(); */
        $hi = "fffffff";
        return $hi;
    }
}
/* End of file Login_model.php */
// SELECT COUNT(id) as count,MONTHNAME(created_at) as month_name FROM users WHERE YEAR(created_at) = '" . date('Y') . "'
 // GROUP BY YEAR(created_at),MONTH(created_at)
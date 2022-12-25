<?php
# @Author: Deepak

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Users_model extends MY_Model
{
    protected $tbl;
    protected $primary_key;
    function __construct()
    {
        parent::__construct();
        $this->tbl = "tbl_login";
        $this->primary_key = "login_id ";
    }
    function get_by_id($id)
    {
        $this->db->where($this->primary_key, $id);
        return $this->db->get($this->tbl)->row();
    }
    function get_all()
    {
        $this->db->select("login_id, store_id, username, fname, lname")
            ->from($this->tbl)
            ->where('is_deleted', '0')
            ->order_by('username');
        return $this->db->get()->result();
    }

    function add($data)
    {
        $create = $this->db->insert($this->tbl, $data);
        $this->db->insert_id();
        return ($create == true) ? true : false;
    }

    function edit($id, $data)
    {
        $this->db->where($this->primary_key, $id);
        $this->db->update($this->tbl, $data);
        return $this->db->affected_rows();
    }

    function get_users()
    {
        $query = "SELECT
                  lg.login_id,
                  lg.username,
                  lg.fname,
                  lg.lname,
                  st.name as store_name,
                  g.group_name
                 FROM tbl_login as lg
                 LEFT JOIN tbl_stores st  ON st.id = lg.store_id
                 LEFT JOIN tbl_groups g ON g.id = lg.group_id
                 WHERE lg.is_deleted='0' ";

        $totalCol = $this->input->post('iColumns');
        $search = $this->input->post('sSearch');
        $columns = explode(',', $this->input->post('columns'));
        $start = $this->input->post('iDisplayStart');
        $page_length = $this->input->post('iDisplayLength');

        $query .= " AND (lg.username like '%$search%' )";
        $query .= " GROUP BY lg.login_id";
        $totalRecords = count($this->db->query($query)->result());

        for ($i = 0; $i < $this->input->post('iSortingCols'); $i++) {
            $sortcol = $this->input->post('iSortCol_' . $i);
            if ($this->input->post('bSortable_' . $sortcol)) {
                $query .= " ORDER BY ($columns[$sortcol])" . $this->input->post('sSortDir_' . $i);
            }
        }

        $this->db->limit($page_length, $start);

        $query .= " LIMIT $start,$page_length";
        $result = $this->db->query($query);
        $data = $result->result();
        $resData = json_encode(array(
            "aaData" => $data,
            "iTotalDisplayRecords" => $totalRecords,
            "iTotalRecords" => $totalRecords,
            "sColumns" => $this->input->post('sColumns'),
            "sEcho" => $this->input->post('sEcho')
        ));

        return $resData;
    }

    public function getStoreData($userID)
    {
        $query = "SELECT ur.store_id, ur.group_id 
        FROM tbl_login ur 
        WHERE login_id = $userID";
        $result = $this->db->query($query);
        $storeData = [];
        foreach ($result->result_array() as $row) {
            $storeData[] = $row;
        }
        return $storeData;
    }
}

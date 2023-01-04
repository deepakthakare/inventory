<?php
# @Author: Deepak

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Groups_model extends MY_Model
{
    protected $tbl;
    protected $primary_key;
    function __construct()
    {
        parent::__construct();
        $this->tbl = "tbl_groups";
        $this->primary_key = "id";
    }
    // get data by id
    function get_by_id($id)
    {
        $this->db->where($this->primary_key, $id);
        return $this->db->get($this->tbl)->row();
    }
    function get_all()
    {
        $this->db->select("name,brand_id")
            ->from($this->tbl)
            ->where('is_deleted', '0')
            ->order_by('name');
        return $this->db->get()->result();
    }
    function add($data)
    {
        $this->db->insert($this->tbl, $data);
        $create = $this->db->insert_id();
        return ($create == true) ? true : false;
    }
    function edit($data, $id)
    {
        $this->db->where($this->primary_key, $id);
        $this->db->update($this->tbl, $data);
        return $this->db->affected_rows();
    }

    function getGroupData($groupId = null)
    {
        if ($groupId) {
            $sql = "SELECT * FROM $this->tbl WHERE id = ?";
            $query = $this->db->query($sql, array($groupId));
            return $query->row_array();
        }

        $sql = "SELECT * FROM $this->tbl WHERE id != ?";
        $query = $this->db->query($sql, array(1));
        return $query->result_array();
    }
    function get_groups()
    {
        $query = "SELECT
                  tb.id,
                  tb.group_name
                 FROM $this->tbl as tb
                 WHERE tb.is_deleted='0' ";

        $totalCol = $this->input->post('iColumns');
        $search = $this->input->post('sSearch');
        $columns = explode(',', $this->input->post('columns'));
        $start = $this->input->post('iDisplayStart');
        $page_length = $this->input->post('iDisplayLength');

        $query .= " AND (tb.group_name like '%$search%' )";
        $query .= " GROUP BY tb.id";
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
    public function getUserGroupByUserId($user_id)
    {
        $sql = "SELECT lg.login_id,lg.store_id,lg.group_id,grp.id,grp.group_name,grp.permission FROM tbl_login lg 
		INNER JOIN tbl_groups grp ON grp.id = lg.group_id 
		WHERE grp.is_deleted = 0 AND lg.login_id = ?";
        $query = $this->db->query($sql, array($user_id));
        $result = $query->row_array();

        return $result;
    }
}

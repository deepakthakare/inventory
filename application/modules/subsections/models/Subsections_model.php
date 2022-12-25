<?php
# @Author: Deepak

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Subsections_model extends MY_Model
{
    protected $tbl;
    protected $primary_key;
    function __construct()
    {
        parent::__construct();
        $this->tbl = "tbl_warehouse_subsections";
        $this->primary_key = "subsection_id";
    }
    function get_by_id($id)
    {
        $this->db->where($this->primary_key, $id);
        return $this->db->get($this->tbl)->row();
    }

    function get_all()
    {
        $this->db->select("*")
            ->from($this->tbl)
            ->where('is_deleted', '0')
            ->order_by('name');
        return $this->db->get()->result();
    }

    function add($data)
    {
        $this->db->insert($this->tbl, $data);
        return $this->db->insert_id();;
    }

    function edit($id, $data)
    {
        $this->db->where($this->primary_key, $id);
        $this->db->update($this->tbl, $data);
        return $this->db->affected_rows();
    }

    function get_subsections()
    {
        $query = "SELECT
                  tb.subsection_id,
                  tb.name,
                  tb.code
                 FROM tbl_warehouse_subsections as tb
                 WHERE tb.is_deleted='0' ";

        $totalCol = $this->input->post('iColumns');
        $search = $this->input->post('sSearch');
        $columns = explode(',', $this->input->post('columns'));
        $start = $this->input->post('iDisplayStart');
        $page_length = $this->input->post('iDisplayLength');

        $query .= " AND (tb.name like '%$search%' )";
        $query .= " GROUP BY tb.subsection_id";
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
}
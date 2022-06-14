<?php
# @Author: Deepak

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Orders_model extends MY_Model
{
    protected $tbl;
    protected $primary_key;
    function __construct()
    {
        parent::__construct();
        $this->tbl = "tbl_shopi_order_details";
        $this->primary_key = "order_id";
    }

    function get_by_id($id)
    {
        $this->db->where($this->primary_key, $id);
        return $this->db->get($this->tbl)->row();
    }

    function get_orders()
    {
        $query = "SELECT
                  tb.order_id,
                  CONCAT(tb.first_name, ' ', tb.last_name) as cust_name,
                  tb.email,
                  sum(tb.quantity) as quantity
                 FROM tbl_shopi_order_details as tb
                 WHERE tb.is_deleted='0' ";

        $totalCol = $this->input->post('iColumns');
        $search = $this->input->post('sSearch');
        $columns = explode(',', $this->input->post('columns'));
        $start = $this->input->post('iDisplayStart');
        $page_length = $this->input->post('iDisplayLength');

        $query .= " AND (tb.order_id like '%$search%' )";
        $query .= " GROUP BY tb.order_id";
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

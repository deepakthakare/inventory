<?php
# @Author: Deepak

if (!defined('BASEPATH'))
  exit('No direct script access allowed');
class Suppliers_model extends MY_Model
{
  protected $tbl;
  protected $primary_key;
  function __construct()
  {
    parent::__construct();
    $this->tbl = "tbl_suppliers";
    $this->primary_key = "id_supplier ";
  }

  // function get_by_id($id)
  // {
  //   $this->db->where($this->primary_key, $id);
  //   return $this->db->get($this->tbl)->row();
  // }

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

  function get_by_id($id)
  {
    $this->db->select("s.*, c.name as country, cu.name as currency, cu.exchange_rate, cu.symbol")
      ->from('tbl_suppliers as s')
      ->join('tbl_country as c', 's.id_country  = c.id', 'left')
      ->join('tbl_currency as cu', 's.id_currency  = cu.id', 'left')
      ->where(array("s.id_supplier" => $id, "s.is_deleted" => "0"));
    return $this->db->get()->result();
  }

  

  function get_supplier()
  {
    $query = "SELECT tb.*, c.name as country FROM `tbl_suppliers` tb
    LEFT JOIN tbl_country c ON c.id = tb.id_country
    WHERE tb.is_deleted='0' ";
    $totalCol = $this->input->post('iColumns');
    $search = $this->input->post('sSearch');
    $columns = explode(',', $this->input->post('columns'));
    $start = $this->input->post('iDisplayStart');
    $page_length = $this->input->post('iDisplayLength');

    $query .= " AND (tb.name like '%$search%' OR tb.supplier_code like '%$search%' OR c.name like '%$search%' OR tb.mobile like '%$search%' OR tb.customer_markup like '%$search%' OR tb.email like '%$search%')";
    $query .= " GROUP BY tb.id_supplier";
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
  /* function get_supplier_country($id) {
    $this->db->select("s.id_supplier,s.customer_markup, c.name as country, cu.name as currency")
      ->from('tbl_suppliers as s')
      ->join('tbl_country as c', 's.id_country  = c.id', 'left')
      ->join('tbl_currency as cu', 's.id_currency  = cu.id', 'left')
      ->where(array("s.id_supplier" => $id, "s.is_deleted" => "0"));
    return $this->db->get()->result();

  } */

}

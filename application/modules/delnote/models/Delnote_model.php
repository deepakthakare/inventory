<?php
# @Author: Deepak

if (!defined('BASEPATH'))
  exit('No direct script access allowed');
class Delnote_model extends MY_Model
{
  protected $tbl;
  protected $primary_key;
  function __construct()
  {
    parent::__construct();
    $this->tbl = "tbl_delnote";
    $this->primary_key = "id_delnote";
  }
  // get data by id
  function get_by_id($id)
  {
    // $this->db->where($this->primary_key, $id);
    // return $this->db->get($this->tbl)->row();
    $this->db->select("d.*, dp.*")
      ->from('tbl_delnote as d')
      ->join('tbl_delnote_products as dp', 'dp.id_delnote  = d.id_delnote', 'left')
      ->where(array("d.id_delnote" => $id, "d.is_deleted" => "0", "dp.is_deleted" => "0"));
    return $this->db->get()->result();
  }

  function get_all()
  {
    $this->db->select("*")
      ->from($this->tbl)
      ->where('is_deleted', '0')
      ->order_by('id_delnote');
    return $this->db->get()->result();
  }

  function add($data)
  {
    $this->db->insert($this->tbl, $data);
    return $this->db->insert_id();;
  }

  function add_products($data)
  {
    $this->db->insert("tbl_delnote_products", $data);
    return $this->db->insert_id();
  }

  function add_barcodes($data)
  {
    $this->db->insert("tbl_barcodes", $data);
    return $this->db->insert_id();
  }
  function addProductVariants($data)
  {
    $this->db->insert("tbl_delnote_product_variants", $data);
    $id =  $this->db->insert_id();

    $this->db->select("*")
      ->from('tbl_delnote_product_variants')
      ->where(array("id_variant" => $id, "is_deleted" => "0"));
    $obj =  $this->db->get()->result();
    return json_decode(json_encode($obj), true);
  }

  function edit($id, $data)
  {
    $this->db->where($this->primary_key, $id);
    $this->db->update($this->tbl, $data);
    return $this->db->affected_rows();
  }

  function updateProductVariant($id_variant, $variant_quantity) {
    $this->db->where('id_variant', $id_variant);
    $this->db->update("tbl_delnote_product_variants", array('variant_quantity' => $variant_quantity));
    $row = $this->db->affected_rows();
    $obj = array('row' => $row, 'qty'=> $variant_quantity,'id_variant' => $id_variant);
    return json_decode(json_encode($obj), true);
  }

  function update_productid($id_delnote)
  {
    $this->db->where('id_delnote', $id_delnote);
    $this->db->update("tbl_delnote_products", array('is_deleted' => "1"));
  }
  function removeProductVariant($id_variant)
  {
    $this->db->where('id_variant', $id_variant);
    $sql = $this->db->update("tbl_delnote_product_variants", array('is_deleted' => "1"));
    if ($sql) { return TRUE; }
  }

  function edit_product_data($id_del_product, $data)
  {
    $this->db->where('id_del_product', $id_del_product);
    $this->db->update("tbl_delnote_products", $data);
  }

  function add_product_data($data)
  {
    $this->db->insert("tbl_delnote_products", $data);
    return $this->db->insert_id();
  }

  function checkBarcodeExist($product_supplier, $supplier_ref)
  {
    $this->db->select("br.supplier_ref, br.barcode, br.price_euro")
      ->from('tbl_barcodes as br')
      ->where(array("br.id_supplier" => $product_supplier, "br.supplier_ref" => $supplier_ref, "br.is_deleted" => "0"));
    $obj =  $this->db->get()->result();
    return json_decode(json_encode($obj), true);
  }

  function getAllProductVariants($id_product)
  {
    $this->db->select("*")
      ->from('tbl_delnote_product_variants')
      ->where(array("id_product" => $id_product, "is_deleted" => "0"));
    $obj =  $this->db->get()->result();
    return json_decode(json_encode($obj), true);
  }

  function update_del_product($id)
  {
    $query = "SELECT * FROM tbl_delnote_products where id_del_product = $id";
    //return $this->db->last_query();
    return $this->db->query($query)->result();
    //return "hello";
  }

  function checkAndGenerateUniqueBarcode($barcode)
  {
    $exists = $this->db->get_where('tbl_barcodes', array('barcode' => $barcode));
    if ($exists->num_rows() > 0) {
      echo "YES";
      return false;
    } else {
      echo "No";
      return false;
    }
  }

  function get_delnotes()
  {
    $query = "SELECT
                  de.id_delnote,
                  DATE_FORMAT(de.created_at, '%d-%m-%Y') as created_at,
                  DATE_FORMAT(de.landed_date, '%d-%m-%Y') as landed_date,
                  de.cust_delno,
                  de.ttl_qty,
                  su.name as supplier_name,
                  c.name as country_name
                 FROM tbl_delnote as de
                 LEFT JOIN tbl_suppliers su ON  su.id_supplier = de.id_supplier
                 LEFT JOIN  tbl_country c ON su.id_country = c.id
                 WHERE de.is_deleted='0' ";

    $totalCol = $this->input->post('iColumns');
    $search = $this->input->post('sSearch');
    $columns = explode(',', $this->input->post('columns'));
    $start = $this->input->post('iDisplayStart');
    $page_length = $this->input->post('iDisplayLength');

    $query .= " AND (su.name like '%$search%' OR de.cust_delno like '%$search%' OR c.name like '%$search%')";
    $query .= " GROUP BY de.id_delnote";
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

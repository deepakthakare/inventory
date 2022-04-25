<?php
# @Author: Deepak


if (!defined('BASEPATH'))
  exit('No direct script access allowed');
class Sales_model extends MY_Model
{
  protected $tbl;
  protected $primary_key;
  function __construct()
  {
    parent::__construct();
    $this->tbl = "tbl_sales";
    $this->primary_key = "order_id";
  }
  function get_total_sales()
  {
    $this->db->select("sum(total) as total_sale")
      ->from($this->tbl)
      ->where('is_deleted', '0');
    return $this->db->get()->row();
  }
  // get data by id
  function get_product_by_filter($category_id, $brand_id)
  {
    $this->db->select("tp.name as product_name,
                          pp.prod_id,
                          pp.prod_price_id,
                          pp.sold_as,
                          pp.attributes_value,
                          pp.price,
                          pp.tax_rate")
      ->from('tbl_products as tp')
      ->join('tbl_product_price as pp', 'pp.prod_id=tp.prod_id', 'left')
      ->join('tbl_product_attributes as tpa', 'tpa.attributes_id=pp.attributes_id', 'left')
      ->where(array("tp.category_id" => 5, "tp.is_deleted" => "1", "tp.brand_id" => 5));
    return $this->db->get()->result();
  }

  //get All products
  function getAllProducts()
  {
    $this->db->select("p.prod_id,
                        p.name as product_name,
                        p.p_price,
                        p.prd_barcode,
                        p.image_path,
                        p.shopify_id")
      ->from('tbl_products as p')
      ->where('p.is_deleted', '0');
    return $this->db->get()->result();
  }

  //get product variants based on product
  function getVariants($id)
  {
    $this->db->select("pp.prod_price_id,
                        pp.prod_id,
                        pp.variant_id,
                        pp.attributes_value,
                        pp.stylecode,
                        pp.inventory,
                        pp.tax_rate,
                        pp.barcode")
      ->from('tbl_product_price as pp')
      ->where(array("pp.attributes_id" => 4, "pp.is_deleted" => "0", "pp.prod_id" => $id, "pp.variant_id !=" => '0'));
    return $this->db->get()->result();
  }

  function getVariantColor($id)
  {
    $query = "SELECT 
    pa.attributes_value
   FROM `tbl_product_price` pa 
   where pa.prod_id = '" . $id . "' and pa.attributes_id = 1";
    $result = $this->db->query($query);
    $color = [];
    foreach ($result->result_array() as $row) {
      $color[] = $row;
    }
    return $color[0]['attributes_value'];
  }

  function get_by_id($id)
  {
    $this->db->select("p.*,pp.*,tpa.name as attributes_name")
      ->from('tbl_sales as p')
      ->join('tbl_product_price as pp', 'pp.prod_id=p.prod_id', 'left')
      ->join('tbl_product_attributes as tpa', 'tpa.attributes_id=pp.attributes_id', 'left')
      ->where(array("p.prod_id" => $id, "p.is_deleted" => "0", "pp.is_deleted" => "0"));
    return $this->db->get()->result();
  }
  function add($data)
  {
    /* echo "<pre>";
    var_dump($data); */
    $this->db->insert($this->tbl, $data);
    return $this->db->insert_id();
  }
  function next_order_id()
  {
    $this->db->select('order_id')
      ->from($this->tbl)
      ->order_by('sales_id', 'DESC')
      ->limit(1);
    $query = $this->db->get();
    if ($query->num_rows() > 0) {
      return $query->row()->order_id + 1;
    } else {
      return 1;
    }
  }
  function edit($id, $data)
  {
    $this->db->where($this->primary_key, $id);
    $this->db->update($this->tbl, $data);
    return $this->db->affected_rows();
  }


  function get_sales()
  {
    $query = "SELECT
                  ts.order_id,
                  ts.customer_name,
                  FORMAT(sum(ts.price),2) as price,
                  sum(ts.qty) as qty,
                  FORMAT(sum(ts.total),2) as total,
                  DATE_FORMAT(ts.created_at, '%d/%m/%Y %r') as created_date 
                 FROM tbl_sales as ts
                 LEFT JOIN tbl_products as tp on tp.prod_id = ts.prod_id
                 WHERE ts.is_deleted='0' ";

    $totalCol = $this->input->post('iColumns');
    $search = $this->input->post('sSearch');
    $columns = explode(',', $this->input->post('columns'));
    $start = $this->input->post('iDisplayStart');
    $page_length = $this->input->post('iDisplayLength');
    $query .= " AND (tp.name like '%$search%')";
    // $query .= " AND (tp.name like '%$search%' OR ts.attributes_value like '%$search%' )";
    $query .= " GROUP BY ts.order_id";
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

  function getShopifyCustomers()
  {
    $url = SHOPIFY_API_KEY . '/admin/api/2022-04/customers.json';
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    $json_array = json_decode($response, true);
    return $json_array;
  }

  // get order Line Items
  function getOrderSHOPIFY($id)
  {
    $query = "SELECT order_id, 
    product_variants as variant_id,
    customer as cust_id,
    qty as quantity,
    shipping
    FROM `tbl_sales` 
    WHERE order_id = $id and product_variants <> 0 and is_deleted = 0";
    $result = $this->db->query($query);
    $orderItems = [];
    $customer = 'customer';
    $draft_order = 'draft_order';
    foreach ($result->result_array() as $row) {
      $ord_id = $row['order_id'];
      if (!isset($orderItems[$row['order_id']])) {
        $orderItems[$row['order_id']] = [
          'line_items' => [],
          'customer' => [
            'id' => $row['cust_id']
          ],
          'shipping_line' => [
            'custom' => true,
            'title' => 'Standard',
            'price' => $row['shipping']
          ],
          "use_customer_default_address" => true,
        ];
      }
      $orderItems[$row['order_id']]['line_items'][] = [
        'variant_id' => $row['variant_id'],
        'quantity' => $row['quantity'],
      ];
    }
    // array_push($orderItems[$ord_id]['customer'], $this->getCustomerID($id));
    // array_push($orderItems[$ord_id]['shipping_line'], $this->getShippingLine($id));
    return $orderItems;
  }

  /* function getCustomerID($id)
  {
    $query = "SELECT customer as cust_id
    FROM `tbl_sales` 
    WHERE order_id = '" . $id . "' and product_variants <> 0 and is_deleted = '0'
    GROUP BY order_id";
    $result = $this->db->query($query);
    $data = [];
    foreach ($result->result_array() as $row) {
      $data[] = [
        'id' => $row['cust_id']
      ];
    }
    return $data;
  }

  function getShippingLine($id)
  {
    $query = "SELECT shipping
    FROM `tbl_sales` 
    WHERE order_id = '" . $id . "' and product_variants <> 0 and is_deleted = '0'
    GROUP BY order_id";
    $result = $this->db->query($query);
    $data = [];
    foreach ($result->result_array() as $row) {

      $data[] = [
        'custom' => true,
        'title' => 'Standard',
        'price' => $row['shipping']
      ];
    }
    return $data;
  } */
  function updateDraftOrderID($ordID, $draftID)
  {
    $this->db->where('order_id', $ordID);
    $this->db->update("tbl_sales", array('draft_order_id' => $draftID));
  }

  function json_change_key($arr, $oldkey, $newkey)
  {
    $json = str_replace('"' . $oldkey . '":', '"' . $newkey . '":', json_encode($arr));
    return json_decode($json);
  }
}

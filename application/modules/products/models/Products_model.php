<?php
# @Author: Deepak

if (!defined('BASEPATH'))
  exit('No direct script access allowed');
class Products_model extends MY_Model
{
  protected $tbl;
  protected $primary_key;
  function __construct()
  {
    parent::__construct();
    $this->tbl = "tbl_products";
    $this->primary_key = "prod_id";
  }
  // get data by id
  function get_by_id($id)
  {
    $this->db->select("p.*,pp.*,tpa.name as attributes_name")
      ->from('tbl_products as p')
      ->join('tbl_product_price as pp', 'pp.prod_id=p.prod_id', 'left')
      ->join('tbl_product_attributes as tpa', 'tpa.attributes_id=pp.attributes_id', 'left')
      ->where(array("p.prod_id" => $id, "p.is_deleted" => "0", "pp.is_deleted" => "0"));
    return $this->db->get()->result();
  }
  function get_all()
  {
    $this->db->select("name")
      ->from($this->tbl)
      ->where('is_deleted', '0')
      ->order_by('name');
    return $this->db->get()->result();
  }
  function add($data)
  {
    /* echo "<pre>";
    var_dump($this->input->post()); */
    $this->db->insert($this->tbl, $data);
    return $this->db->insert_id();
  }
  function add_price($data)
  {
    $this->db->insert("tbl_product_price", $data);
    return $this->db->insert_id();
  }
  function edit_price($prod_price_id, $data)
  {
    $this->db->where('prod_price_id', $prod_price_id);
    $this->db->update("tbl_product_price", $data);
  }
  function update_price($prod_id)
  {
    $this->db->where('prod_id', $prod_id);
    $this->db->update("tbl_product_price", array('is_deleted' => "1"));
  }
  function updateShipifyPrdID($prod_id, $shopiID)
  {
    $this->db->where('prod_id', $prod_id);
    $this->db->update("tbl_products", array('shopify_id' => $shopiID));
  }

  function updateVariantID($prod_id, $var_id, $size)
  {
    $where = array('prod_id' => $prod_id, 'attributes_value ' => $size, 'attributes_id' => 4);
    $this->db->where($where);
    $this->db->update("tbl_product_price", array('variant_id' => $var_id));
  }

  function edit($id, $data)
  {
    $this->db->where($this->primary_key, $id);
    $this->db->update($this->tbl, $data);
    return $this->db->affected_rows();
  }

  function get_product_inventory($prod_id)
  {
    $query = "SELECT
                  tp.name as product_name,
                  tp.image_path,
                  tp.p_price,
                  tpp.prod_id,
                  tpp.prod_price_id,
                  tpp.attributes_id,
                  tpp.attributes_value,
                  tpp.sold_as,
                  tpp.price,
                  tpp.tax_rate,
                  tpp.inventory,
                  tap.name as attributes_name,
                  tpp.barcode
                FROM tbl_product_price as tpp
                 LEFT JOIN tbl_product_attributes as tap on tap.attributes_id=tpp.attributes_id
                 LEFT JOIN tbl_products as tp on tp.prod_id=tpp.prod_id
                 WHERE  tpp.is_deleted='0' AND tpp.prod_id=$prod_id group by tpp.prod_price_id";
    return $this->db->query($query)->result();
  }
  function get_products()
  {
    $query = "SELECT
                  tp.prod_id,
                  tp.image_path,
                  tp.name,
                  tc.name as category_name,
                  tb.name as brand_name,
                  tp.prd_barcode,
                  (select sum(pa.inventory) FROM tbl_product_price pa where pa.attributes_id = 4 and pa.is_deleted='0' and tp.prod_id = pa.prod_id) as inventory,
                  tp.shopify_id,
                  (CASE WHEN tp.shopify_id <> 0 THEN 'Pushed'
                     WHEN tp.shopify_id = 0 THEN 'Pending'
                  END) AS shopi_status
                 FROM tbl_products as tp
                 LEFT JOIN tbl_category as tc on tp.category_id=tc.category_id
                 LEFT JOIN tbl_brand as tb on tp.brand_id=tb.brand_id
                 WHERE tp.is_deleted='0' ";

    $totalCol = $this->input->post('iColumns');
    $search = $this->input->post('sSearch');
    $columns = explode(',', $this->input->post('columns'));
    $start = $this->input->post('iDisplayStart');
    $page_length = $this->input->post('iDisplayLength');

    $query .= " AND (tp.name like '%$search%' OR tp.prd_barcode like '%$search%' OR tb.name like '%$search%' )";
    $query .= " GROUP BY tp.prod_id";
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

  // Get products data for shopify

  function getProductSHOPIFY($id)
  {
    $query = "SELECT p.prod_id,
                  p.name,
                  p.`description`,
                  pa.price,
                  pa.attributes_id,
                  Group_concat(pa.attributes_value SEPARATOR ',') AS attributes_value,
                  Group_concat(pa.price SEPARATOR ',') AS qty,
                  pa.inventory
              FROM `tbl_products` p
                  LEFT JOIN tbl_product_price pa ON pa.prod_id = p.prod_id
              WHERE p.prod_id = $id
              GROUP BY p.prod_id, pa.attributes_id";

    $result = $this->db->query($query);
    $emparray = [];
    $attributes = ["Color", "Size"];
    foreach ($result->result_array() as $row) {
      $arrrayAtri = explode(",", $row['attributes_value']);
      $values = '"' . implode('", "', $arrrayAtri) . '"';
      $product_id = $row['prod_id'];
      if (!isset($emparray[$row['prod_id']])) {
        $emparray[$row['prod_id']] = [
          'title' => $row['name'],
          'body_html' => $row['description'],
          "vendor" => "BGF",
          "published" => "0",
          "published_at" => "2022-03-28T19:00:00-05:00", // date("Y-m-d h:i:s")
          "published_scope" => "global",
          'options' => [],
          'variants' => [],
        ];
      }
      $emparray[$row['prod_id']]['options'][] =
        [
          "name" => $attributes[sizeof($emparray[$row['prod_id']]['options'])],
          "position" => sizeof($emparray[$row['prod_id']]['options']) + 1,
          'values' => [
            $values
          ],

        ];
    }
    array_push($emparray[$product_id]['variants'], $this->getVariantGroupBy($id));
    return $emparray;
  }


  // Get product variants for shopify
  function getVariantGroupBy($id)
  {

    $query = "SELECT 
                  pa.prod_id,
                  p.name,
                  p.p_price as price,
                  pa.attributes_value,
                  pa.inventory as qty,
                  pa.stylecode,
                  pa.barcode,
                  p.weight
              FROM `tbl_product_price` pa 
              LEFT JOIN tbl_products p ON pa.prod_id = p.prod_id
              where pa.prod_id = '" . $id . "' and pa.attributes_id = 4";
    $result = $this->db->query($query);
    $data = [];
    foreach ($result->result_array() as $row) {
      $colorValue = $this->getColor($row['prod_id']);
      $data[] = [
        "sku" => $row['stylecode'],
        'title' => $row['name'],
        'price' => $row['price'],
        "taxable" => true,
        'barcode' => $row['barcode'],
        "requires_shipping" => true,
        "inventory_quantity" => $row['qty'],
        "inventory_management" => "shopify",
        "presentment_prices" => [
          "compare_at_price" => [
            'amount' => $row['price'],
            "currency_code" => "GBP",
          ]
        ],
        "weight" => $row['weight'],
        "weight_unit" => "kg",
        "option1" =>  $colorValue,
        "option2" =>  $row['attributes_value'],


      ];
    }
    return $data;
  }

  function getColor($id)
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


  function removeUselessArrays($array, $name)
  {
    $newArray = [];
    foreach ($array as $key => $value) {
      if (is_array($value)) {
        $isFound = $this->multiKeyExists($array, $name);
        if (array_keys($value) === [0] && $isFound === "found") {
          $newArray[$key] = $this->removeUselessArrays($value[0], $name);
        } else {
          $newArray[$key] = $this->removeUselessArrays($value, $name);
        }
      } else {
        $newArray[$key] = $value;
      }
    }
    return $newArray;
  }

  function multiKeyExists(array $arr, $key)
  {
    if (array_key_exists($key, $arr)) {
      return "found";
    }
    foreach ($arr as $element) {
      if (is_array($element)) {
        if ($this->multiKeyExists($element, $key)) {
          return "found";
        }
      }
    }
    return false;
  }

  function updateLocation($id)
  {
    $key =  SHOPIFY_API_KEY . '/admin/api/2022-04/products/' . $id . '/metafields.json';
    $query = "SELECT 
      p.location
    FROM `tbl_products` p 
    where p.shopify_id = '" . $id . "' and p.is_deleted = 0";
    $result = $this->db->query($query);
    $locations = [];
    foreach ($result->result_array() as $row) {
      $locations[] = $row;
    }
    $location =  $locations[0]['location'];
    $data_json = json_encode(
      array(
        "metafield" => array(
          "namespace" => 'product',
          "key" => 'internal',
          "value" => $location,
          "type" => 'single_line_text_field'
        )
      )

    );
    $ch = curl_init($key);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt(
      $ch,
      CURLOPT_HTTPHEADER,
      array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data_json)
      )
    );
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
  }
}

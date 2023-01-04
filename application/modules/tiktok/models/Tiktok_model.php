<?php
# @Author: Deepak


if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Tiktok_model extends MY_Model
{
    protected $tbl;
    protected $primary_key;
    function __construct()
    {
        parent::__construct();
        $this->tbl = "tbl_tiktok_products";
        $this->primary_key = "id";
    }

    function add($data)
    {
        $this->db->insert($this->tbl, $data);
        return $this->db->insert_id();
    }

    function edit_row($id, $data)
    {
        // $this->db->where($this->primary_key, $id);
        $this->db->where('prod_price_id', $id);
        $this->db->update($this->tbl, $data);
        return $this->db->affected_rows();
    }

    function tot_rows()
    {
        $this->db->select("tp.name as product_name,
        tp.image_path,
        tpp.prod_id,
        tpp.prod_price_id,
        tpp.attributes_id,
        tpp.attributes_value,
        tpp.color,
        tpp.size,
        tpp.sold_as,
        tp.p_price,
        tpp.tax_rate,
        tpp.inventory,
        tp.prd_barcode,
        tpp.barcode,
        IFNULL(`tk`.`selling_price`,'0.00') as selling_price,
        tpp.stylecode,
        tap.name as attributes_name");
        $this->db->from("tbl_product_price as tpp");
        $this->db->join('tbl_product_attributes as tap', 'tap.attributes_id=tpp.attributes_id', 'left');
        $this->db->join('tbl_products tp', 'tp.prod_id=tpp.prod_id', 'left');
        $this->db->join('tbl_tiktok_products tk', 'tpp.prod_price_id = tk.prod_price_id', 'left outer');
        $this->db->where("tpp.is_deleted", "0");
        $this->db->group_by('tpp.prod_price_id');
        $query = $this->db->get();
        return $query->num_rows();
    } //End of tot_rows()

    // function all_rows($limit, $start, $col, $dir)
    function all_rows($storeID, $groupID)
    {
    $query = "SELECT 
        tp.name as product_name,
        tp.image_path,
        tpp.prod_id,
        tpp.prod_price_id,
        tpp.attributes_id,
        tpp.attributes_value,
        tpp.color,
        tpp.size,
        tpp.sold_as,
        tp.p_price,
        tpp.tax_rate,
        tpp.inventory,
        tp.prd_barcode,
        tpp.barcode,
        IFNULL(`tk`.`selling_price`,'0.00') as selling_price,
        tpp.stylecode,
        tap.name as attributes_name,
        $storeID as store_id,
        $groupID as group_id
    FROM tbl_product_price as tpp
    LEFT JOIN tbl_product_attributes as tap ON tap.attributes_id = tpp.attributes_id
    LEFT JOIN  tbl_products tp ON tp.prod_id = tpp.prod_id 
    LEFT OUTER JOIN tbl_tiktok_products tk ON tpp.prod_price_id = tk.prod_price_id
    WHERE IF (NOT EXISTS (select group_id from tbl_products where group_id = $groupID), tp.store_id IN( $storeID, 3), tp.group_id = 3) 
    AND tp.is_deleted = '0'
    GROUP BY tpp.prod_price_id;";
        $result = $this->db->query($query);
        if ($result->num_rows() == 0) {
            return NULL;
        } else {
            return $result->result();
        }
    } //End of all_rows()

    // function search_rows($limit, $start, $keyword, $col, $dir)
    function search_rows($keyword)
    {
        $this->db->select("tp.name as product_name,
        tp.image_path,
        tpp.prod_id,
        tpp.prod_price_id,
        tpp.attributes_id,
        tpp.attributes_value,
        tpp.color,
        tpp.size,
        tpp.sold_as,
        tp.p_price,
        tpp.tax_rate,
        tpp.inventory,
        tp.prd_barcode,
        tpp.barcode,
        IFNULL(`tk`.`selling_price`,'0.00') as selling_price,
        tpp.stylecode,
        tap.name as attributes_name");
        $this->db->from("tbl_product_price as tpp");
        $this->db->join('tbl_product_attributes as tap', 'tap.attributes_id=tpp.attributes_id', 'left');
        $this->db->join('tbl_products tp', 'tp.prod_id=tpp.prod_id', 'left');
        $this->db->join('tbl_tiktok_products tk', 'tpp.prod_price_id = tk.prod_price_id', 'left outer');
        $this->db->where("tpp.is_deleted", "0");
        $this->db->like('tp.name', $keyword);
        $this->db->or_like('tp.prd_barcode', $keyword);
        $this->db->or_like(' tpp.barcode', $keyword);
        // $this->db->or_like('c.name', $keyword);
        // $this->db->limit($limit, $start);
        $this->db->group_by('tpp.prod_price_id');
        // $this->db->order_by($col, $dir);


        $query = $this->db->get(); //var_dump($this->db->last_query());die;
        if ($query->num_rows() == 0) {
            return NULL;
        } else {
            return $query->result();
        }
    } //End of search_rows()

    function tot_search_rows($keyword)
    {
        $this->db->select("tp.name as product_name,
        tp.image_path,
        tpp.prod_id,
        tpp.prod_price_id,
        tpp.attributes_id,
        tpp.attributes_value,
        tpp.color,
        tpp.size,
        tpp.sold_as,
        tp.p_price,
        tpp.tax_rate,
        tpp.inventory,
        tp.prd_barcode,
        tpp.barcode,
        IFNULL(`tk`.`selling_price`,'0.00') as selling_price,
        tpp.stylecode,
        tap.name as attributes_name");
        $this->db->from("tbl_product_price as tpp");
        $this->db->join('tbl_product_attributes as tap', 'tap.attributes_id=tpp.attributes_id', 'left');
        $this->db->join('tbl_products tp', 'tp.prod_id=tpp.prod_id', 'left');
        $this->db->join('tbl_tiktok_products tk', 'tpp.prod_price_id = tk.prod_price_id', 'left outer');
        $this->db->where("tpp.is_deleted", "0");
        $this->db->group_by('tpp.prod_price_id');
        $query = $this->db->get();
        return $query->num_rows();
    } //End of tot_search_rows()


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

    function attributeID_exists($id)
    {
        $this->db->where('prod_price_id', $id);
        $query = $this->db->get('tbl_tiktok_products');
        if ($query->num_rows() > 0) {
            return 1;
        } else {
            return 0;
        }
    }
    function exportProducts($ids)
    {

        $query = "SELECT
         c.name as category,
        '' as brand,
        p.name,
        p.description,
        p.weight,
        '' as length,
        '' as width,
        '' as height,
        '' as delivery_option,
        tpp.stylecode as identifier_code_type,
        '' as identifier_code,
        '' as identifier_code,
        tk.color,
        p.image_path,
        tk.size,
        tk.selling_price,
        tpp.inventory as quantity,
        tpp.barcode as sku,
        '' as warranty_period,
        p.image_path as image,
        '' as image2,
        '' as image3,
        '' as image4,
        '' as image5,
        '' as image6,
        '' as image7,
        '' as image8,
        '' as image9
        FROM `tbl_tiktok_products` tk
        LEFT JOIN tbl_products p ON tk.id_product = p.prod_id
        LEFT JOIN tbl_product_price tpp ON tpp.prod_price_id = tk.prod_price_id
        LEFT JOIN tbl_category c ON c.category_id = p.category_id
        Where tpp.is_deleted = 0 AND tk.prod_price_id IN ($ids)";
        return $this->db->query($query)->result('array');
    }
}

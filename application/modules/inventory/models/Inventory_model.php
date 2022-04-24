<?php
# @Author: Sahebul
# @Date:   2019-06-03T11:18:52+05:30
# @Last modified by:   Sahebul
# @Last modified time: 2019-06-03T11:18:55+05:30

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Inventory_model extends MY_Model
{
    protected $tbl;
    protected $primary_key;
    function __construct()
    {
        parent::__construct();
        $this->tbl = "tbl_product_price";
        $this->primary_key = "prod_price_id";
    }

    function edit_row($id, $data)
    {
        $this->db->where($this->primary_key, $id);
        $this->db->update($this->tbl, $data);
        return $this->db->affected_rows();
    }

    //For datatable
    function tot_rows()
    {
        $this->db->select("tp.name as product_name,
        tp.image_path,
        tpp.prod_id,
        tpp.prod_price_id,
        tpp.attributes_id,
        tpp.attributes_value,
        tpp.sold_as,
        tp.p_price,
        tpp.tax_rate,
        tpp.inventory,
        tp.prd_barcode,
        tpp.barcode,
        tap.name as attributes_name");
        $this->db->from("tbl_product_price as tpp");
        $this->db->join('tbl_product_attributes as tap', 'tap.attributes_id=tpp.attributes_id', 'left');
        $this->db->join('tbl_products tp', 'tp.prod_id=tpp.prod_id', 'left');
        $this->db->where("tpp.is_deleted", "0");
        $this->db->group_by('tpp.prod_price_id');
        $query = $this->db->get();
        return $query->num_rows();
    } //End of tot_rows()

    function all_rows($limit, $start, $col, $dir)
    {
        $this->db->select("tp.name as product_name,
        tp.image_path,
        tpp.prod_id,
        tpp.prod_price_id,
        tpp.attributes_id,
        tpp.attributes_value,
        tpp.sold_as,
        tp.p_price,
        tpp.tax_rate,
        tpp.inventory,
        tp.prd_barcode,
        tpp.barcode,
        tap.name as attributes_name");
        $this->db->from("tbl_product_price as tpp");
        $this->db->join('tbl_product_attributes as tap', 'tap.attributes_id=tpp.attributes_id', 'left');
        $this->db->join('tbl_products tp', 'tp.prod_id=tpp.prod_id', 'left');
        $this->db->where("tpp.is_deleted", "0");
        $this->db->group_by('tpp.prod_price_id');
        $this->db->limit($limit, $start);
        $this->db->order_by($col, $dir);
        $query = $this->db->get();
        if ($query->num_rows() == 0) {
            return NULL;
        } else {
            return $query->result();
        }
    } //End of all_rows()

    function search_rows($limit, $start, $keyword, $col, $dir)
    {
        $this->db->select("tp.name as product_name,
        tp.image_path,
        tpp.prod_id,
        tpp.prod_price_id,
        tpp.attributes_id,
        tpp.attributes_value,
        tpp.sold_as,
        tp.p_price,
        tpp.tax_rate,
        tpp.inventory,
        tp.prd_barcode,
        tpp.barcode,
        tap.name as attributes_name");
        $this->db->from("tbl_product_price as tpp");
        $this->db->join('tbl_product_attributes as tap', 'tap.attributes_id=tpp.attributes_id', 'left');
        $this->db->join('tbl_products tp', 'tp.prod_id=tpp.prod_id', 'left');
        $this->db->where("tpp.is_deleted", "0");
        $this->db->like('tp.name', $keyword);
        $this->db->or_like('tp.prd_barcode', $keyword);
        $this->db->or_like(' tpp.barcode', $keyword);
        // $this->db->or_like('c.name', $keyword);
        $this->db->limit($limit, $start);
        $this->db->group_by('tpp.prod_price_id');
        $this->db->order_by($col, $dir);


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
        tpp.sold_as,
        tp.p_price,
        tpp.tax_rate,
        tpp.inventory,
        tp.prd_barcode,
        tpp.barcode,
        tap.name as attributes_name");
        $this->db->from("tbl_product_price as tpp");
        $this->db->join('tbl_product_attributes as tap', 'tap.attributes_id=tpp.attributes_id', 'left');
        $this->db->join('tbl_products tp', 'tp.prod_id=tpp.prod_id', 'left');
        $this->db->where("tpp.is_deleted", "0");
        $this->db->group_by('tpp.prod_price_id');
        $query = $this->db->get();
        return $query->num_rows();
    } //End of tot_search_rows()

    // get attribute Color 
    /* function getColorName($limit, $start, $search, $order, $dir, $id)
    {
        $where = array('av.prod_id' => $id, 'av.attributes_id ' => 1);
        $this->db->select("av.attributes_value");
        $this->db->from("tbl_product_price av");
        $this->db->where($where);
        $this->db->limit($limit, $start);
        $this->db->order_by($dir);
        $query = $this->db->get();
        if ($query->num_rows() == 0) {
            return NULL;
        } else {
            return $query->result();
        }

    } */

    function getVariantID($prod_price_id)
    {
        $query = "SELECT 
                tpp.variant_id
                FROM `tbl_product_price` tpp 
                where tpp.prod_price_id = '" . $prod_price_id . "' and tpp.is_deleted = 0 ";
        $result = $this->db->query($query);
        $variantid = [];
        foreach ($result->result_array() as $row) {
            $variantid[] = $row;
        }
        return $variantid[0]['variant_id'];
    }

    function getInentoryItemId($variantID)
    {
        $url = SHOPIFY_API_KEY . '/admin/api/2022-04/variants/' . $variantID . '.json';
        // $url = "https://912040110b75e3b1ce4fe721c626ff6d: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@isuf-una.myshopify.com/admin/api/2022-04/variants/" . $variantID . ".json";
        //echo $url;
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
        $inventoryID = $json_array['variant']['inventory_item_id'];
        return $inventoryID;
    }


    function updateStockToShopify($inventory_item_id, $qty)
    {
        $url = SHOPIFY_API_KEY . '/admin/api/2022-04/inventory_levels/set.json';
        $data_json = json_encode(
            array(
                "location_id" => '68225958113',
                "inventory_item_id" => $inventory_item_id,
                "available" => $qty
            )
        );
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8', 'Content-Length: ' . strlen($data_json)));
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}

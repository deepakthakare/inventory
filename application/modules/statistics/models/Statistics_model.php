<?php
# @Author: Deepak

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Statistics_model extends MY_Model
{
    protected $tbl;
    protected $primary_key;
    function __construct()
    {
        parent::__construct();
        // $this->tbl = "tbl_brand";
        // $this->primary_key = "brand_id";
    }

    function get_products()
    {
        $query = "SELECT tp.prod_id,
            tp.image_path,
            tp.name,
            tp.prd_barcode,
            st.name as store_name,
            (SELECT SUM(pa.inventory)
                    FROM   tbl_product_price pa
                    WHERE  pa.attributes_id = 4
                            AND pa.is_deleted = '0'
                            AND tp.prod_id = pa.prod_id) AS inventory,
            (SELECT IF(( spde.shopi_product_id IS NOT NULL
                        OR spde.shopi_product_id <> '' )
                    AND spde.store_id = '1', '1', '0')
            FROM   tbl_shopify_product_details spde
            WHERE  spde.prod_id = tp.prod_id
                    AND spde.store_id = '1'
            GROUP  BY spde.shopi_product_id) as ifif,
            (SELECT IF(( spde.shopi_product_id IS NOT NULL
                        OR spde.shopi_product_id <> '' )
                    AND spde.store_id = 2, '1', '0')
            FROM   tbl_shopify_product_details spde
            WHERE  spde.prod_id = tp.prod_id 
                    AND spde.store_id = 2
            GROUP  BY spde.shopi_product_id) as redhot
        FROM   tbl_products AS tp
        LEFT JOIN tbl_shopify_product_details spd
            ON spd.prod_id = tp.prod_id
        LEFT JOIN tbl_stores st 
              ON st.id = spd.store_id      
        WHERE  tp.is_deleted = '0'";

        $totalCol = $this->input->post('iColumns');
        $search = $this->input->post('sSearch');
        $columns = explode(',', $this->input->post('columns'));
        $start = $this->input->post('iDisplayStart');
        $page_length = $this->input->post('iDisplayLength');

        $query .= " AND (tp.name like '%$search%' OR tp.prd_barcode like '%$search%' OR tp.name like '%$search%' )";
        $query .= " GROUP BY tp.prod_id";
        /* echo $query;
    die; */
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

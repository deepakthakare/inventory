<?php
# @Author: Deepak

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Stocks_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
        // $this->tbl = "tbl_shopi_order_details";
        // $this->primary_key = "order_id";
    }

    function allProducts($storeID)
    {
        if ($storeID == 1) {
            $url = SHOPIFY_API_KEY . '/admin/api/2022-07/variants.json';
        } elseif ($storeID == 2) {
            $url = SHOPIFY_API_KEY_BGF . '/admin/api/2022-07/variants.json';
        } else {
            // $url = SHOPIFY_API_KEY_BGF . '/admin/api/2022-04/variants.json';
        }
        $fields = '';
        // $fields = '&fields=id,created_at,handle,tags,variants,images';
        $endpoints = '';
        //$endpoints = '&published_status=draft';

        // $productArray = $this->curlGetProducts($url, $fields, $endpoints, 250);
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
        $productArray = json_decode($response, true);

        return $productArray;
    }

    function curlGetProducts($request, $fields = '', $endpoints = '', $limit = 250, $no_pagination = false)
    {
        $merged = array();
        $page_info = '';
        $last_page = false;
        $limit = '?limit=' . $limit;
        $debug = 0;

        while (!$last_page) {
            $url = $request . $limit . $fields . $endpoints . $page_info;
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HEADERFUNCTION, function ($curl, $header) use (&$headers) {
                $len = strlen($header);
                $header = explode(':', $header, 2);
                if (count($header) >= 2) {
                    $headers[strtolower(trim($header[0]))] = trim($header[1]);
                }
                return $len;
            });
            $result = curl_exec($curl);
            curl_close($curl);

            if (isset($headers['link'])) {
                $links = explode(',', $headers['link']);

                foreach ($links as $link) {
                    $next_page = false;
                    if (strpos($link, 'rel="next"')) {
                        $next_page = $link;
                    }
                }

                if ($next_page) {
                    preg_match('~<(.*?)>~', $next_page, $next);
                    $url_components = parse_url($next[1]);
                    parse_str($url_components['query'], $params);
                    $page_info = '&page_info=' . $params['page_info'];
                    $endpoints = ''; // Link pagination does not support endpoints on pages 2 and up
                } else {
                    $last_page = true; // There's no next page, we're at the last page
                }
            } else {
                $last_page = true; // Couldn't find parameter link in headers, stop loop
            }
            $source_array = json_decode($result, true);
            $merged = array_merge_recursive($merged, $source_array);

            if ($no_pagination) {
                $last_page = true;
            }

            // Used for debugging to prevent infinite loops, comment to disable
            // if($debug >= 150) {
            // 	break;
            // }
            // $debug++;

            sleep(1); // Limit calls to 1 per second
        }

        return $merged;
    }




    function tot_rows($storeID)
    {
        if ($storeID == 1) {
            $url = SHOPIFY_API_KEY . '/admin/api/2022-04/variants/count.json';
        } elseif ($storeID == 2) {
            $url = SHOPIFY_API_KEY_BGF . '/admin/api/2022-04/variants/count.json';
        }
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
        $jsonData = json_decode($response, true);
        $allRows = $jsonData['count'];
        return $allRows;
    }
    function getImageURL($productID, $storeID)
    {
        if ($storeID == 1) {
            // $url = SHOPIFY_API_KEY . '/admin/api/2022-04/products/' . $productID . '/images/' . $imageID . '.json';
        } elseif ($storeID == 2) {
            // $url = SHOPIFY_API_KEY_BGF . '/admin/api/2022-04/products/' . $productID . '/images/' . $imageID . '.json';
            $url = SHOPIFY_API_KEY_BGF . '/admin/api/2022-04/products/' . $productID . '/images.json';
        }
        // return $url;
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
        $jsonData = json_decode($response, true);
        echo "<pre>";
        //   print_r($productID . "-" . $storeID . "-" . $imageID);
        die;
        $img_url = base_url() . "assets/img/not-found.png";
        $imgurl = $jsonData['image']['src'];
        if (isset($imgurl)  && !($imgurl == NULL)) {
            error_reporting(0);
            return $imgurl;
            error_reporting(E_ALL);
        } else {
            return  $img_url;
        }
        // return $imgurl;
    }

    function getproductName($productID)
    {
        $url = SHOPIFY_API_KEY . '/admin/api/2022-04/products/' . $productID . '.json';
        // return $url;
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
        $jsonData = json_decode($response, true);

        $name = $jsonData['product']['title'];
        return $name;
    }

    function getInentoryItemId($variantID, $storeID)
    {
        if ($storeID == 1) {
            $url = SHOPIFY_API_KEY . '/admin/api/2022-04/variants/' . $variantID . '.json';
        } elseif ($storeID == 2) {
            $url = SHOPIFY_API_KEY_BGF . '/admin/api/2022-04/variants/' . $variantID . '.json';
        }
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

    function updateStockToShopify($inventory_item_id, $qty, $storeID)
    {
        if ($storeID == 1) {
            $url =  SHOPIFY_API_KEY . '/admin/api/2022-04/inventory_levels/set.json';
            $locationID = '68225958113';
        } elseif ($storeID == 2) {
            $url =  SHOPIFY_API_KEY_BGF . '/admin/api/2022-04/inventory_levels/set.json';
            $locationID = '65952907445';
        }
        $data_json = json_encode(
            array(
                "location_id" => $locationID,
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

    function getProductDetails($variantID)
    {
        $query = "SELECT p.image_path,p.name FROM tbl_shopify_product_details shp
        LEFT JOIN tbl_products p ON p.prod_id = shp.prod_id
        WHERE shp.shopi_variant_id = $variantID";
        $result = $this->db->query($query);
        $prdData = [];
        foreach ($result->result_array() as $row) {
            $prdData[] = $row;
        }
        return $prdData;
        //return array(null) + $prdData;
    }
}

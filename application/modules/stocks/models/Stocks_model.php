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
            $url = SHOPIFY_API_KEY . '/admin/api/2022-04/variants.json';
        } elseif ($storeID == 2) {
            $url = SHOPIFY_API_KEY_BGF . '/admin/api/2022-04/variants.json';
        } else {
           // $url = SHOPIFY_API_KEY_BGF . '/admin/api/2022-04/variants.json';
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
        $productArray = json_decode($response, true);
        return $productArray;
    }

    function tot_rows($storeID)
    {
        if ($storeID == 1) {
        $url = SHOPIFY_API_KEY . '/admin/api/2022-04/variants/count.json';
        } elseif ($storeID == 2){
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
    function getImageURL($productID, $imageID, $storeID)
    {
        if ($storeID == 1) {
            $url = SHOPIFY_API_KEY . '/admin/api/2022-04/products/' . $productID . '/images/' . $imageID . '.json';
        } elseif ($storeID == 2) {
            $url = SHOPIFY_API_KEY_BGF . '/admin/api/2022-04/products/' . $productID . '/images/' . $imageID . '.json';
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
        print_r($productID . "-" . $storeID . "-" . $imageID);
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
            $locationID = '68586537178';
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
}

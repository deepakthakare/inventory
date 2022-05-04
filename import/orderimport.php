<?php
header("Access-Control-Allow-Origin: *");
require_once 'classes/Product.php';

// if (isset($_REQUEST['order'])) {
// For testing use only
$orderJSON = '{"order_id":1012,"customer_id":6137190580442,"email":"deepakthakare14@gmail.com","first_name":"Deepak","last_name":"Thakare","order_subtotal":"20.25","total_tax":"4.05","total":"24.30","line_items":[{"variant_id":42616604033242,"title":"Round Neck, Two in One, Burn Out Top","sku":"18045","price":"6.75","variant_title":"Black / One Size","quantity":2},{"variant_id":42684334473434,"title":"product 2","sku":"PRD2SKU","price":"6.75","variant_title":"Black / One Size","quantity":1}]}';
$order = json_decode($orderJSON);
$sec = 1;
foreach ($order->line_items as $row) {
    if ($row->variant_id != "") {
        $product = new Product();
        $product->order_id  = $order->order_id;
        $product->variant_id  = $row->variant_id;
        $product->customer_id  = $order->customer_id;
        $product->first_name  = $order->first_name;
        $product->last_name  = $order->last_name;
        $product->email  = $order->email;
        $product->product_name  = $row->title;
        $product->styelcode  = $row->sku;
        $product->attribute  = $row->variant_title;
        $product->quantity  = $row->quantity;
        $product->price  = $row->price;
        $product->order_subtotal  = $order->order_subtotal;
        $product->total_tax  = $order->total_tax;
        $product->total  = $order->total;
        print_r($product->create());
        $sec = $sec + 1;
    }
}

<?php
require_once 'Database.php';

class Product
{
    public  $id;
    public  $order_id;
    public  $variant_id;
    public  $customer_id;
    public  $first_name;
    public  $last_name;
    public  $email;
    public  $product_name;
    public  $styelcode;
    public  $attribute;
    public  $quantity;
    public  $price;
    public  $order_subtotal;
    public  $total_tax;
    public  $total;
    public  $created_at;
    public  $updated_at;
    public  $is_deleted = 0;

    public function create()
    {
        $product = $this->getFields();
        $sql = "INSERT INTO tbl_shopi_order_details (" . implode(', ', array_keys($product)) . ") VALUES (:" . implode(', :', array_keys($product)) . ")";
        $DB = $this->getDB();
        $statement = $DB->prepare($sql);
        $statement->execute($product);

        return $statement->errorInfo();
    }

    public function getFields()
    {
        $product = json_decode(json_encode($this), true);
        $unwated = array('id');
        foreach ($unwated  as $key) {
            unset($product[$key]);
        }
        return $product;
    }

    public function getDB()
    {
        $DB =  Database::getInstance();
        return $DB->_connection;
    }
}

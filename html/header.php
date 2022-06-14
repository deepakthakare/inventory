<link rel="stylesheet" href="barcode.css" type="text/css">
<?php
require_once '../import/classes/Database.php';
require_once '../barcode/barcode.class.php';
require('function.php');
$quantity = $_REQUEST['quantity'];
$id = $_REQUEST['id'];
$barcode = $_REQUEST['barcode'];
$bar = new BARCODE();

if (!defined('IN_CB')) die('You are not allowed to access to this page.');
if (version_compare(phpversion(), '5.0.0', '>=') != 1)
    exit('Sorry, but you have to run this script with PHP5... You currently have the version <b>' . phpversion() . '</b>.');
echo '<?xml version="1.0" encoding="iso-8859-1"?>' . "\n";
error_reporting(0);



function getDB()
{
    $DB =  Database::getInstance();
    return $DB->_connection;
}

function getCategory()
{
    $DB = getDB();
    $query_check = 'SELECT * FROM `tbl_products` WHERE is_deleted = 0 ';
    $res_check = $DB->query($query_check)->fetch();
    // print_r($res_check);
    $category = $res_check['name'];
    return  $category;
}
echo "<pre>";
print_r(getCategory());
?>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.7.2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js "></script>
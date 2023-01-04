<link rel="stylesheet" href="barcode.css" type="text/css">
<div style="width:5.5in; height:auto; background:#FFFFFF;">
    <div style="text-align:center;" id="printpagebutton">
        <button onClick="printBarcode()">Print</button>
    </div>
    <?php
    require_once '../import/classes/Database.php';
    require_once '../barcode/barcode.class.php';
    require('function.php');
    $quantity = $_REQUEST['quantity'];
    $id = $_REQUEST['id'];
    $brd = $_REQUEST['barcode'];
    $barcode = (strlen($brd) === 13) ? substr($brd, 0, -1) : $brd;
   // echo $barcode; die;
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
    $quantity = $_REQUEST['quantity'];
    $id = $_REQUEST['id'];
    $barcode = $_REQUEST['barcode'];
    $DB = getDB();
    /* $query_check = 'SELECT p.p_price, FROM `tbl_products` 
LEFT JOIN tbl_product_price pp ON pp.prod_id = p.prod_id
WHERE p.is_deleted = 0 and pp.prod_price_id =' . $id; */
    $query_check = 'SELECT pp.prod_price_id,
                        pp.prod_id,
                        pp.stylecode,
                        pp.barcode,
                        pp.color,
                        pp.size,
                        pp.ratio,
                        pp.updated_at,
                        p.p_price
                FROM tbl_product_price pp        
                LEFT JOIN tbl_products p
                ON        p.prod_id = pp.prod_id
                WHERE     p.is_deleted = 0
                AND       pp.prod_price_id =' . $id;
    $row = $DB->query($query_check)->fetch();
    // print_r($row); die;
    for ($i = 1; $i <= $quantity; $i++) { ?>
        <table class="label_table">
            <div style="margin-top:10px; margin-bottom:50px;">
                <tbody>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td width="50%" style="text-align: left;padding-left: 18px;padding-bottom:10px;"><?php echo  $row['stylecode']; ?></td>
                        <td width="50%" style="text-align: right;padding-bottom:10px;">
                            <?php
                            $p_value = explode(".", $row['p_price']);
                            if (strlen($p_value[1]) < 2) {
                                echo '0';
                            }
                            print_r($p_value[1]);

                            if (strlen($p_value[0]) < 2) {
                                echo '0';
                            }
                            print_r($p_value[0]);
                            ?></td>
                    </tr>
                    <tr>
                        <td colspan="3" align="center">
                            <img src="image.php?code=ean13&o=1&t=40&r=2&text=<?php echo substr($barcode, 0, -1); ?>&f=5&a1=&a2=" alt="Error? Can\'t display image!" />
                        </td>
                    </tr>
                    <tr>
                        <td width="50%" style="text-align: left;padding-left: 10px;padding-top:10px;"><?php echo date('d-m-Y', strtotime($row['updated_at'])); ?></td>
                        <td width="50%" style="text-align: right;padding-left: 10px;padding-top:10px;"><?php echo strtoupper($row['size']); ?></td>
                        <!--<td>&nbsp;</td>-->
                    </tr>
            </div>
        </table>
        <!-- $category = $res_check['name']."<br>";
    echo $category; -->
    <?php }

    function getCategory()
    {
    }
    echo "<pre>";
    print_r(getCategory());
    ?>
</div>
<script>
    const printBarcode = () => {
        let printButton = document.getElementById("printpagebutton");
        //let pagination = document.getElementById("paginationblock");
        printButton.style.visibility = 'hidden';
        // pagination.style.visibility = 'hidden';
        window.print();
        printButton.style.visibility = 'visible';
        // pagination.style.visibility = 'visible';
    }
</script>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.7.2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js "></script>
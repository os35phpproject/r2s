<?php

    require_once("ORM.php");
    $date = $_GET['date'];
    $user = $_GET['usid'];
    $obj = ORM::getInstance(); 
    $obj->setTable('orders');
    $num = "";
	$name = "";
    $cost = "";
    $image = "";
    $result = $obj->select_extra(array("orprid","ornumber"),array("orusid"=>"= $user", "ordate"=>"= '$date'"));
    //var_dump($result);
    $num_results = mysqli_num_rows($result);
    for ($i=0; $i <$num_results; $i++) {
        $row = mysqli_fetch_assoc($result); 
        $num .= $row['ornumber'].",";
        $obj->setTable('product');
        $product = $obj->select_extra("all",array("id"=>"=".$row["orprid"]));
        $num_product = mysqli_num_rows($product);
        for ($j=0; $j <$num_product; $j++) {
            $row_product = mysqli_fetch_assoc($product);
            $name .= $row_product["prname"].",";
            $cost .= $row_product["prprice"].",";
            $image .= $row_product["primage"].",";
        }
    }
            

    echo $name."|".$image."|".$num."|".$cost;
?>

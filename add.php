<?php

   require_once("ORM.php");
$originalDate=$_GET['datefrom'];
$originalDate1=$_GET['dateto'];
   // $originalDate = "03/03/2015";
   // $originalDate1 = "03/07/2015";

    $new = $originalDate.' 00:00:00';
   $new1 = $originalDate1.' 00:00:00';
//echo $new,$new1;


    $obj = ORM::getInstance(); 
    $obj->setTable('orders');
    $date = "";
  $status = "";
  $amount = "";

    $result = $obj->select_extra(array("ordate","status","sum(orcost)","orid"),array('orusid' => "=1","ordate"=>">= '$new'","ordate"=>"<= '$new1'"),"group by ordate");
    $num_results = mysqli_num_rows($result);
        for ($i=0; $i <$num_results; $i++) {
        $row = mysqli_fetch_assoc($result); 
        $date .= $row['ordate'].",";
        $status .= $row['status'].",";
        $amount .= $row['sum(orcost)'].",";
        }
            

      echo $date."|".$status."|".$amount;
?>

<?php

    require_once("ORM.php");
    $id = $_GET['id'];
	$dateFrom = $_GET['dateFrom'].' 00:00:00';
	$dateTo = $_GET['dateTo'].' 23:59:59';
   
    $obj = ORM::getInstance(); 
    $obj->setTable('orders');
    $date = "";
	$amount = "";
    $usid = "";
    $result = $obj->select_extra(array("ordate","sum(orcost*ornumber)","orusid"),array('orusid' => "=$id","ordate"=>" BETWEEN '$dateFrom' AND '$dateTo'", "status" => "= 'Done'"),"group by ordate");
    //var_dump($result);
    $num_results = mysqli_num_rows($result);
    for ($i=0; $i <$num_results; $i++) {
        $row = mysqli_fetch_assoc($result); 
        $date .= $row['ordate'].",";
        $amount .= $row['sum(orcost*ornumber)'].",";
        $usid .= $row["orusid"].",";
    }
            

     echo $date."|".$amount."|".$usid;
?>

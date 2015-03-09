<?php

require_once("ORM.php");

session_start();
$dateFrom = $_GET['dateFrom'];
$dateTo = $_GET['dateTo'];

$dateFrom .= ' 00:00:00';
$dateTo .= ' 23:59:59';


$obj = ORM::getInstance(); 
$obj->setTable('orders');
$date = "";
$status = "";
$amount = "";
$total = "";
    $result = $obj->select_extra(array("ordate","status","sum(orcost*ornumber)","orid"),array('orusid' => " = ".$_SESSION['usrid'],"ordate"=>" BETWEEN '$dateFrom' AND '$dateTo'"),"group by ordate");
    $num_results = mysqli_num_rows($result);
        for ($i=0; $i <$num_results; $i++) {
        $row = mysqli_fetch_assoc($result); 
        $date .= $row['ordate'].",";
        $status .= $row['status'].",";
        $amount .= $row["sum(orcost*ornumber)"].",";
        }
        $result = $obj->select_extra(array("sum(orcost*ornumber)"),array('orusid' => " = ".$_SESSION['usrid'],"ordate"=>" BETWEEN '$dateFrom' AND '$dateTo'"));
            $num_results = mysqli_num_rows($result);
            for ($i=0; $i <$num_results; $i++) {
                $row = mysqli_fetch_assoc($result);
                $total = $row["sum(orcost*ornumber)"];
            }
            

      echo $date."|".$status."|".$amount."|".$total;
?>

<?php
require_once("ORM.php");

if(isset($_GET['proid'])){

$obj=ORM::getInstance();
$obj->setTable("product");
$data=["prprice",];
$cond = array("prname"=>$_GET['proid'],);

$res=$obj->select($data,$cond);
echo (mysqli_fetch_assoc($res)["prprice"]);


}






?>

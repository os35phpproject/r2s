<?php
require_once("ORM.php");
//if(isset($_GET['theusrid'])){
$obj=ORM::getInstance();
$obj->setTable("orders");
$obj->delete(["orusid"=>$_GET['theusrid'],"ordate"=>$_GET['orderdate'],]);

//}


?>
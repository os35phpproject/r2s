<?php

if(isset($_GET['q'])){
	
require_once("ORM.php");
$obj=ORM::getInstance();
$obj->setTable("category");
$data=["ctid"=>'',"ctname"=>$_GET['q'],];
$res=$obj->insert($data);

}





?>

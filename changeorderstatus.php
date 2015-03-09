<?php
require_once("ORM.php");

var_dump($_GET);
if(isset($_GET['status'])){
echo "hamada"."<br/>";
$obj=ORM::getInstance();

$obj->setTable("users");
$res=$obj->select("all",["usname"=>$_GET['clinm']]);



for($i=0;$i<mysqli_num_rows($res);$i++){
	$therec=mysqli_fetch_assoc($res);
	$usnum=$therec['usid'];
	echo "unum= ".$usnum."<br/>";

$obj->setTable("orders");
if($_GET['status']=="processing"){
	echo "process"."<br/>";
	$data=["status"=>"processing"];
}else if($_GET['status']=="out for delivery"){
	echo "out"."<br/>";
	$data=["status"=>"out for delivery"];
}else if($_GET['status']=="Done"){
	echo "done"."<br/>";
	$data=["status"=>"Done"];
}

$cond=["orusid"=>(int)$usnum,"ordate"=>$_GET['ordate'],];
var_dump($data);
var_dump($cond);
$x=$obj->update($data,$cond);
var_dump($x);
}
}

?>

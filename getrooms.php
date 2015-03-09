<?php
require_once("ORM.php");

if(isset($_GET['romid'])){

$obj=ORM::getInstance();
$obj->setTable("room");
$data=["rmno",];

$res=$obj->select($data);

for($j=0;$j<mysqli_num_rows($res);$j++){
	$theroms=mysqli_fetch_assoc($res);
	$roms[]=$theroms["rmno"];
}
$roms=implode(",", $roms);
echo $roms;


}






?>

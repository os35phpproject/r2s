<?php

require_once("ORM.php");

if(isset($_GET['prods'])){

$obj=ORM::getInstance();
$obj->setTable("product");
$theimages=explode(" ",$_GET['prods']);

for($i=0;$i<count($theimages);$i++){
	$cond=["prname"=>$theimages[$i],];
	$res=$obj->select("all",$cond);
	for($j=0;$j<mysqli_num_rows($res);$j++){
	$theimges=mysqli_fetch_assoc($res);
	$imgs[]=$theimges["primage"];
}
}


$imgs=implode(",", $imgs);
echo $imgs;


}

?>

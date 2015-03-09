<?php 
require_once("ORM.php");
$obj=ORM::getInstance();




if(isset($_POST['ordpct'])){
	var_dump($_POST);
$ordsnames=$_POST['ordpct'];
$ordsnames=explode(" ",$ordsnames);
	
	for($i=0;$i<count($ordsnames)-1;$i++){

		$obj->setTable("product");
		$data=["prname"=>$ordsnames[$i]];
		$res=$obj->select("all",$data);
		for($j=0;$j<mysqli_num_rows($res);$j++){
 		$therec=mysqli_fetch_assoc($res);
 		$ordsnames[$i]= $therec['id'];

	}}

	$obj->setTable("users");
	$theresal=$obj->select("all",["usname"=>$_POST['client']]);
	for($j=0;$j<mysqli_num_rows($theresal);$j++){
 		$therect=mysqli_fetch_assoc($theresal);
 		$therecto[]= $therect['usid'];
 	
}
	$obj->setTable("orders");
	$ordsnum=$_POST['orditnum'];
	$itemcost=$_POST['thitemco'];
	$rommnum=$_POST['romnumer'];
	$notes=$_POST['notes'];

	
	$ordsnum=explode(" ",$ordsnum);
	$itemcost=explode(" ",$itemcost);
	$thedate=$_POST['datetime']/1000;

	for($i=0;$i<count($ordsnames)-1;$i++){
		$data=["orid"=>'',"orusid"=>$therecto[0],"orprid"=>$ordsnames[$i],"ornumber"=>$ordsnum[$i],"orcost"=>$itemcost[$i],"ornote"=>$notes,"orroom"=>$rommnum,"ordate"=>date("Y-m-d H:i:s",$thedate)];
		var_dump($data) ;
		$obj->insert($data);
	}


}
?>

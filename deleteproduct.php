<?php
 include_once('ORM.php');
	session_start();
// var_dump($_SESSION) ;
// exit;
if(isset($_SESSION['logged'])){
	if($_SESSION['logged'] == 'true'){
		if($_SESSION['usrid'] == "1"){


		}else{
			echo "hamada";
			header("Location:http://localhost/prologin.php");
			exit;
		}
		
	}else{
		echo "hamada2";
		header("Location:http://localhost/prologin.php");
		exit;
	}
}else{
	echo "hamada3";
	header("Location:http://localhost/prologin.php");
	exit;
}
 
$id=$_GET['id'];
	$obj = ORM::getInstance();
	$obj->setTable('product');
	$result = $obj->select(array("primage"),array("id"=>$id));
	 $num_results = mysqli_num_rows($result);
	  for ($i=0; $i <$num_results; $i++) {
	   $row = mysqli_fetch_assoc($result);
	    unlink("./".$row["primage"]);
	   
	     }
       $query = $obj->delete(array("id"=>$id));
       header("Location:showproduct.php");

?>
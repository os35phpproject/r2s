
<?php
if(isset($_GET['id'])){
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
	require_once("ORM.php");
	$obj = ORM::getInstance(); 
    $obj->setTable('product');
    $result=$obj->select("all",["id"=>$_GET['id']]);
    $num_results = mysqli_num_rows($result);
	for ($i=0; $i <$num_results; $i++) {
		$row = mysqli_fetch_assoc($result);
		if($row['statues']==1){
			$obj->update(array('statues'=>0),["id"=>$_GET['id']]);
		}else{
			$obj->update(array('statues'=>1),["id"=>$_GET['id']]);
		}
		header( 'Location: showproduct.php' ) ;
	}
	
}
?>
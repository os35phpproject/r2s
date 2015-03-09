<html>
<body>
<?php
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
$id =  $_GET["id"];
require_once('ORM.php');
$obj = ORM::getInstance();
$obj->setTable('users');
$result = $obj->select(array("usimage"),array("usid"=>$id));
$num_results = mysqli_num_rows($result);
for ($i=0; $i <$num_results; $i++) {
    $row = mysqli_fetch_assoc($result);
	unlink("./".$row["usimage"]);
}
$obj->delete(array("usid"=>$id));

header( 'Location: allUsers.php' ) ;
?>

</body>
</html>
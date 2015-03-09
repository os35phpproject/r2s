<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
 <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

	<style>
		.error {
			color: #FF0000;
			margin-left: 50px;
		}
		.header {
			width: 140px;
			float: left;
			padding-left: 15px;
			margin-left: 70px;
		}
		.input {
			width: 300px;
			height: 30px;
		}

		h1{
			margin-left: 200px;
		}
					#container{
				width: 600px;
				margin-left: 200px;
			}
			 #nav{
            float: left;
            width: 800px;
            margin-left: 30px;
            margin-top: 10px;

        }
        .name{
                float: right;
                margin-right: 25px; 
                margin-top: 20px;
            }

	</style>
</head>
<body>
<div id="nav">
<ul class="nav nav-tabs">
  <li role="presentation"><a href="admin.php">Home</a></li>
  <li role="presentation"><a href="showproduct.php">Products</a></li>
  <li role="presentation" ><a href="allUsers.php">Users</a></li>
  <li role="presentation"><a href="manualOrder.php">Manual Orders</a></li>
  <li role="presentation"><a href="checks.php">Checks</a></li>
</ul>
</div>

	<?php
	if(isset($_GET['id'])){
		session_start();
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
require_once('ORM.php');
$obj = ORM::getInstance();
if(isset($_SESSION['usrid'])){
    $obj->setTable("users");
$condi=["usid"=>$_SESSION['usrid'],];
$usrdata=$obj->select("all",$condi);

for($j=0;$j<mysqli_num_rows($usrdata);$j++){
    $therecord=mysqli_fetch_assoc($usrdata);
    $usrnm= $therecord['usname'];
    $usrext= $therecord['usext'];
    $usrimg=$therecord['usimage'];
 }
 
 echo "<div style='overflow:hidden'>";
 echo "<div id='namediv' style='margin-right:30px;' align='right'>";
echo "<img src=/".$usrimg." width=70px height=70px class='name'>";
echo "</img>"."<br/>";
echo  "<div class='name'>Hello, ".$usrnm."<br><a href=http://localhost/logout.php>"."LogOut"."</a></div>";
echo "</div>";
echo "</div>";

}
	require_once('validation.php');
	require_once('ORM.php');
	$obj = ORM::getInstance();
	$obj->setTable('users');
	$result = $obj->select(array("uspassword"),array("usid"=>$_GET["id"]));
	$num_results = mysqli_num_rows($result);
	for ($i=0; $i <$num_results; $i++) {
    	$row = mysqli_fetch_assoc($result);
    	$oldPassword = $row["uspassword"];
	
	}
	$data = array("uspassword"=>"");
	$errors = array("olduspassword"=>"",				   
					"uspassword"=>"",
				    "confirmuspassword"=>""
				    );
	if ($_POST){	
		$validation = new Validation();
		$rules = array("olduspassword"=>"required",				   
					   "uspassword"=>"required",
					   "confirmuspassword"=>"required|confirm"
					   );

		
		$valid_data = $validation -> validate($_POST, $rules, $data, $errors);
		

		if($valid_data == TRUE){
			unset($data["confirmuspassword"]);
			unset($data["olduspassword"]);
			if( md5($_POST["olduspassword"]) == $oldPassword){
				$data["uspassword"] = md5($data["uspassword"]);
				$obj->update($data, array("usid"=>$_GET["id"]));
				header( 'Location: editUser.php?id='.$_GET["id"] ) ;
			}else{
				$errors["olduspassword"] = "password is incorrect";
			}
		}	
	}
}
	?>

	<form method="post" action="changePassword.php?id=<?php echo $_GET["id"]; ?>" enctype="multipart/form-data">
		<div id="container">
		<h1> Change Password </h1><br>

		<div class="form-group">
	    	<label for="inputEmail3" class="col-sm-2 control-label">Old Password </label>
	    	<div class="col-sm-10">
			<input type="password" class="form-control" id="inputPassword3" placeholder="OldPassword" name="olduspassword" >
			<span class="error"> <?php echo $errors["olduspassword"];?></span><br/><br/>
			</div>
		</div>

		<div class="form-group">
	    	<label for="inputEmail3" class="col-sm-2 control-label">New Password </label>
	    	<div class="col-sm-10">
			<input type="password" class="form-control" id="inputPassword3" placeholder="NewPassword" name="uspassword" >
			<span class="error"> <?php echo $errors["uspassword"];?></span><br/><br/>
			</div>
		</div>

		<div class="form-group">
	    	<label for="inputEmail3" class="col-sm-2 control-label">Confirm Password</label>
			<div class="col-sm-10">
			<input type="password" class="form-control" id="inputPassword3" placeholder="ConfirmPassword" name="confirmuspassword" >
			<span class="error"> <?php echo $errors["confirmuspassword"];?></span><br/><br/>
			</div>
		</div>
		
		<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">	
		<input class="btn btn-default" type="submit" name="submit">
		</div>
		</div>

	<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
		<input class="btn btn-default" type="reset" name="reset">
		</div>
		</div>
		</div>
	</form>

</body>
</html>
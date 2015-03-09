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
				width: 500px;
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


	session_start();
//var_dump("$_SESSION") ;
if(isset($_SESSION['logged'])){
	if($_SESSION['logged'] == 'true'){
		if($_SESSION['usrid'] == 1){

		}else{
			header("Location:http://localhost/prologin.php");
		}
		
	}else{
		header("Location:http://localhost/prologin.php");
	}
}else{
	header("Location:http://localhost/prologin.php");
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
	$result = $obj->select("all",array("usid"=>$_GET["id"]));
	$num_results = mysqli_num_rows($result);
	for ($i=0; $i <$num_results; $i++) {
    	$row = mysqli_fetch_assoc($result);
    	$data = array("usname"=>$row["usname"],
					  "usemail"=>$row["usemail"],			  
					  "usroomno"=>$row["usroomno"], 
					  "usext"=>$row["usext"],				  
					  "usimage" => $row["usimage"]
					  );
	
	}
	$errors = array("usname"=>"",
				    "usemail"=>"",			  
				    "usroomno"=>"", 
				    "usext"=>"",
				    "usimage" => ""
				    );
	if ($_POST){	
		$validation = new Validation();
		$rules = array("usname"=>"required",
					   "usemail"=>"required|email|unique",			  
					   "usroomno"=>"required", 
					   "usext"=>"required|unique"
					   );

		
		$valid_data = $validation -> validate($_POST, $rules, $data, $errors);
		

		if($valid_data == TRUE){
			if (!empty($_FILES["usimage"]["name"])) {
				if(!(($_FILES["usimage"]["type"] == "image/png") || ($_FILES["usimage"]["type"] == "image/jpg") || ($_FILES["usimage"]["type"] == "image/jpeg"))){
					$errors["usimage"] = "it is not an image you have to apload only images";
				}else{
					$data["usimage"] = $data["usext"].$_FILES["usimage"]["name"]; 
					if (is_uploaded_file($_FILES['usimage']['tmp_name'])){
						$upfile = '/var/www/html/'.$data["usimage"];
						if (move_uploaded_file($_FILES['usimage']['tmp_name'], $upfile)){
							unlink("./".$data["usimage"]);
							$obj->update($data, array("usid"=>$_GET["id"]));
							header( 'Location: allUsers.php' ) ;
						}else{		
							$errors["usimage"] = 'error occured while uploading image, try again';							
						}	
					}
				}
			}else{
				$obj->update($data, array("usid"=>$_GET["id"]));
				header( 'Location: allUsers.php' ) ;
			}	
		}
	}
	?>

	<form method="post" action="editUser.php?id=<?php echo $_GET["id"]; ?>" enctype="multipart/form-data" class="form-horizontal" >
		<div id="container">
		<h1> Edit User </h1><br>
		
		<div class="form-group">
	    	<label for="inputEmail3" class="col-sm-2 control-label">Name</label>
	    	<div class="col-sm-10">
			<input type="text" class="form-control" id="inputText3" placeholder="Username" name="usname" value="<?php if(isset($data["usname"])){echo $data["usname"];} ?>" >
			<span class="error"> <?php echo $errors["usname"];?></span><br>
			</div>
		</div>

		<div class="form-group">
    		<label for="inputEmail3" class="col-sm-2 control-label">Email</label>
			<div class="col-sm-10">
			<input type="email" class="form-control" id="inputEmail3" placeholder="Email" name="usemail" value="<?php if(isset($data["usemail"])){echo $data["usemail"];} ?>">
			<span class="error"> <?php echo $errors["usemail"];?></span><br>
		</div>
		</div>

		<div class="form-group">
    	<label for="inputEmail3" class="col-sm-2 control-label">Room No</label>
    	<div class="col-sm-10">
		<input type="text" class="form-control" id="inputText3" placeholder="RoomNo" type="text" name="usroomno" value="<?php if(isset($data["usroomno"])){echo $data["usroomno"];} ?>" >
		<span class="error"> <?php echo $errors["usroomno"];?></span><br>
			</div>
		</div>
		<div class="form-group">
    	<label for="inputEmail3" class="col-sm-2 control-label">EXT</label>
    	<div class="col-sm-10">
		<input type="text" class="form-control" id="inputText3" placeholder="Ext" type="text" name="usext" value="<?php if(isset($data["usext"])){echo $data["usext"];} ?>" >
		<span class="error"> <?php echo $errors["usext"];?></span><br>	
			</div>
		</div>

		<div class="form-group">
    	<label for="exampleInputFile" class="col-sm-2 control-label">Profile Picture</label>
    	<div class="col-sm-10">
		<input type="file" id="exampleInputFile" name="usimage"/>
		<span class="error"> <?php echo $errors["usimage"];?></span><br>
				</div>
		</div>

<div class = "header"><a href="changePassword.php?id=<?php echo $_GET["id"];?>">Change Password</a></div><br><br>
		
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
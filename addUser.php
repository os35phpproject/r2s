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
             img.name{
                width:  100px;
                height: 100px;
                border-style: solid;
                border-width: 1px;
            
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
<h1 style="margin-left:300px;"> Add User </h1><br>
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


	$data = array("usname"=>"",
				  "usemail"=>"",
				  "uspassword"=>"",			  
				  "usroomno"=>"", 
				  "usext"=>"",				  
				  "usimage" => "",
				  "usquestion" => "",
				  "usanswer" => "",
				  );

	$errors = array("usname"=>"",
				    "usemail"=>"",
				    "uspassword"=>"",
				    "confirmuspassword"=>"",				  
				    "usroomno"=>"", 
				    "usext"=>"",				  
				    "usimage" => "",
				    "usquestion" => "",
				  "usanswer" => "",
				    );
	if ($_POST){	
		$validation = new Validation();
		$rules = array("usname"=>"required|unique",
					   "usemail"=>"required|email|unique",
					   "uspassword"=>"required",
					   "confirmuspassword"=>"required|confirm",				  
					   "usroomno"=>"required", 
					   "usext"=>"required|unique",
					   "usquestion" => "required",
				  "usanswer" => "required",
					   );

		
		$valid_data = $validation -> validate($_POST, $rules, $data, $errors);
		$valid_image = $validation -> validateImage($_FILES["usimage"], $data, $errors["usimage"]);

		if($valid_data == TRUE && $valid_image == TRUE){
			unset($data["confirmuspassword"]);
			$data["uspassword"] = md5($data["uspassword"]);
			$data["usanswer"] = md5($data["usanswer"]);
			if (is_uploaded_file($_FILES['usimage']['tmp_name'])){
				$upfile = '/var/www/html/'.$data["usimage"];
					if (move_uploaded_file($_FILES['usimage']['tmp_name'], $upfile)){
						$obj = ORM::getInstance();
						$obj->setTable('room');
						$result =  $obj->select(array("rmno"),array("rmno"=>$data["usroomno"]));
						 $num_results = mysqli_num_rows($result);
						if($num_results == 0){
							 $data["usroomno"];
							$obj->insert(array("rmno"=>$data["usroomno"]));	
				    	}
						$obj->setTable('users');
						$obj->insert($data);
						header("Location:http://localhost/allUsers.php");	

					}else{		
						$errors["usimage"] = 'error occured while uploading image, try again';
							
					}	
			}
		}



	}
	?>

	<form method="post" action="addUser.php" class="form-horizontal" enctype="multipart/form-data">
		<div id="container">
		
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
	    	<label for="inputEmail3" class="col-sm-2 control-label">Password</label>
	    	<div class="col-sm-10">
			<input type="password" class="form-control" id="inputPassword3" placeholder="Password" name="uspassword" >
			<span class="error"> <?php echo $errors["uspassword"];?></span><br>
			</div>
		</div>

		<div class="form-group">
	    	<label for="inputEmail3" class="col-sm-2 control-label">Confirm Password</label>
			<div class="col-sm-10">
			<input type="password" class="form-control" id="inputPassword3" placeholder="ConfirmPassword" name="confirmuspassword" >
			<span class="error"> <?php echo $errors["confirmuspassword"];?></span><br>
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
<div class="form-group">
	    	<label for="inputEmail3" class="col-sm-2 control-label">Security Question</label>
	    	<div class="col-sm-10">
			<input type="text" class="form-control" id="inputText4" placeholder="Security Question" name="usquestion" value="<?php if(isset($data["usquestion"])){echo $data["usquestion"];} ?>" >
			<span class="error"> <?php echo $errors["usquestion"];?></span><br>
			</div>
		</div>
		<div class="form-group">
	    	<label for="inputEmail3" class="col-sm-2 control-label">Your Answer</label>
	    	<div class="col-sm-10">
			<input type="text" class="form-control" id="inputText5" placeholder="Your Answer" name="usanswer" value="<?php if(isset($data["usanswer"])){echo $data["usanswer"];} ?>" >
			<span class="error"> <?php echo $errors["usanswer"];?></span><br>
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
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
 <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	<style>
            table, th, td {
                border-style: solid;
                border-width: 1px;
            }
            td{
                width: 150px;
                padding-left: 15px;
            }
            img{
                width: 200px;
                height: 100px;
            }
            h1{
                margin-left: 70px;
            }
              #container{
                width: 600px;
                margin-left: 100px;
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
            #add{
                float: right;
                font-size: 20px;
            }
            h1{
                margin-left: 70px;
            }
	</style>
</head>
<body>
 <div id="nav">
<ul class="nav nav-tabs">
  <li role="presentation"><a href="admin.php">Home</a></li>
  <li role="presentation" class="active"><a href="showproduct.php">Products</a></li>
  <li role="presentation"><a href="allUsers.php">Users</a></li>
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
	$obj->setTable('product');
	$result = $obj->select("all",array("id"=>$_GET["id"]));
	$num_results = mysqli_num_rows($result);
	for ($i=0; $i <$num_results; $i++) {
    	$row = mysqli_fetch_assoc($result);
    	$data = array("prname"=>$row["prname"],
					  "prprice"=>$row["prprice"],			  
					  "prcategory"=>$row["prcategory"], 			  
					  "primage" => $row["primage"]
					  );
		$oldimage = $row["primage"];
	}
	$errors = array("prname"=>"",
				    "prprice"=>"",			  
				    "prcategory"=>"", 
				    "primage" => ""
				    );
	if ($_POST){	
		$validation = new Validation();
		$rules = array("prname"=>"required",
					   "prprice"=>"required"
					   );

		
		$valid_data = $validation -> validate($_POST, $rules, $data, $errors);

		if($valid_data == TRUE){
		if (!empty($_FILES["primage"]["name"])) {
				if(!(($_FILES["primage"]["type"] == "image/png") || ($_FILES["primage"]["type"] == "image/jpg") || ($_FILES["primage"]["type"] == "image/jpeg"))){
					$errors["primage"] = "it is not an image you have to apload only images";
				}else{
					$data["primage"] = $data["prname"].$_FILES["primage"]["name"]; 
					if (is_uploaded_file($_FILES['primage']['tmp_name'])){
						$upfile = '/var/www/html/'.$data["primage"];
						if (move_uploaded_file($_FILES['primage']['tmp_name'], $upfile)){
							var_dump($data);
							unlink("./image/".$oldimage);
							 $obj->update($data, array("id"=>$_GET["id"]));
							 header( 'Location: showproduct.php' ) ;
						}else{		
							$errors["primage"] = 'error occured while uploading image, try again';							
						}	
					}
				}
			}else{
				 $obj->update($data, array("id"=>$_GET["id"]));
				 header( 'Location: showproduct.php' ) ;
			}

		}
	}
	?>

	<form method="post" action="editproduct.php?id=<?php echo $_GET["id"]; ?>" enctype="multipart/form-data" class="form-horizontal" >
		<div id="container">
		<h1> Edit Product </h1><br>
		
		<div class="form-group">
	    	<label for="inputEmail3" class="col-sm-2 control-label">Product name</label>
	    	<div class="col-sm-10">
			<input type="text" class="form-control" id="inputText3" placeholder="productName" name="prname" value="<?php if(isset($data["prname"])){echo $data["prname"];} ?>" >
			<span class="error"> <?php echo $errors["prname"];?></span><br>
						</div>
		</div>

		<div class="form-group">
    		<label for="inputEmail3" class="col-sm-2 control-label">Price</label>
			<div class="col-sm-10">
			<input class="input" type="number" name="prprice" min="1" max="100" step='1' class="form-control" id="inputEmail3" placeholder="price" value="<?php if(isset($data["prprice"])){echo $data["prprice"];} ?>">
			<span class="error"> <?php echo $errors["prprice"];?></span><br>
		</div>
		</div>

		<div class="form-group">
    	<label for="exampleInputFile" class="col-sm-2 control-label">Product Picture</label>
    	<div class="col-sm-10">
		<input type="file" id="exampleInputFile" name="primage"/>
		<span class="error"> <?php echo $errors["primage"];?></span><br>

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

<html>
<head>
<title> ADD PRODUCT </title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
 <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	<style>
	 select{
                width: 150px;
                height: 30px;
                font-size: 15px;
            }
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
            img{
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

require_once ("insert.php");

$obj=ORM::getInstance();
$obj->setTable("product");

if(isset($_POST['submit']))
{//var_dump($_POST);
	if (is_uploaded_file($_FILES['userfile']['tmp_name']))
{
    $upfile = '/var/www/html/'.$_POST["prname"].$_FILES['userfile']['name'] ;
    if (!move_uploaded_file($_FILES['userfile']['tmp_name'], $upfile))
    {
     echo 'Problem: Could not move file to destination directory';
    }
}
// if ($_FILES['userfile']['type']!= 'image/jpeg' ){
// $imgreq= 'you must upload an image';
// }
$pro=$_POST["prname"];
$pathimage=$_FILES['userfile']['name'];
$pathimage=$pro.$pathimage;
$arr = $_POST;
$arr['primage'] = $pathimage;
unset($arr["submit"]);
$reqflag=1;

if(!preg_match ("/^[a-zA-Z0-9|_|-]+$/",$pro)){
$pnamreq="please Enter a valid product name";
$reqflag=0;

}
if(!preg_match ("/^[0-9]+$/",$_POST["prprice"])){
$prcreq="please Enter a valid product cost";
$reqflag=0;
}
if(empty($_FILES['userfile']['name'])){
$imgreq="please Enter the product image";
$reqflag=0;
}
if($reqflag){
	//var_dump($arr);
	$selcond=["ctname"=>$arr["prcategory"],];
	$obj->setTable("category");
	$res=$obj->select("all",$selcond);
	//echo $res;
	$nus=mysqli_num_rows($res);
	for($j=0;$j<$nus;$j++){
			$therec=mysqli_fetch_assoc($res);
			//echo $therec['ctid'];
			$arr["prcategory"]=$therec['ctid'];
			$obj->setTable("product");
			// var_dump($arr);
			$res=$obj->insert($arr);
		}

	

}

	    
}





$obj->setTable("category");
$res=$obj->select();




 ?>

 <form method="POST" enctype="multipart/form-data" class="form-horizontal">
 <div id="container">
    <h1>Add Product</h1>
    <br/>

<div class="form-group">
	    	<label for="inputEmail3" class="col-sm-2 control-label">Product</label>
	    	<div class="col-sm-10">
			<input type="text" name="prname" class="form-control" id="inputText3" placeholder="Product" >
			 <span><?php if(isset($pnamreq)){echo $pnamreq;} ?><br>
			</div>
		</div>

<div class="form-group">
	    	<label for="inputEmail3" class="col-sm-2 control-label">Price</label>
	    	<div class="col-sm-10">
			<input type="number" style="width:100px;" name="prprice" min="1" max="100" step='1' class="form-control" id="inputText3" placeholder="Price" >
			<span><?php if(isset($prcreq)){echo $prcreq;} ?></span><br>
			</div>
		</div>

<div class="form-group">
	    	<label for="inputEmail3" class="col-sm-2 control-label">Category</label>
	    	<div class="col-sm-10">
	    	<select id="selnode" name="prcategory">
	<?php
		for($j=0;$j<mysqli_num_rows($res);$j++){
			$therec=mysqli_fetch_assoc($res);
			echo "<option>";
			echo $therec['ctname'];
			echo "</option>";
				}
				echo "</select>";
	?>
	<div id='catDiv'>
		<a href="#" onclick="add_category()"   >add category</a><br/>
		</div>
			</div>
		</div>

<div class="form-group">
    	<label for="exampleInputFile" class="col-sm-2 control-label">Product Picture</label>
    	<div class="col-sm-10">
		<input type="file" id="exampleInputFile" name="userfile"/>
		<span><?php if(isset($imgreq)){echo $imgreq;} ?></span><br>
				</div>
		</div>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">	
		<input class="btn btn-default" type="submit" value="Submit" name="submit">
		</div>
		</div>
	<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
		<input class="btn btn-default" value="Reset">
		</div>
		</div>
		</div>
</div>
</form>


<script language="javascript" type="text/javascript">

flag=false;

function add_category() {
	if(!flag){
condiv=document.getElementById("catDiv");
txtcat=document.createElement("input");
txtcat.setAttribute("id","cat");
addbtn=document.createElement("input");
addbtn.setAttribute("type","button");
addbtn.setAttribute("id","theaddbtn");
addbtn.setAttribute("value","Add");
addbtn.setAttribute("onclick","add()");

condiv.appendChild(txtcat);
condiv.appendChild(addbtn);
flag=true;
}
}

function add(){

cat=document.getElementById("cat");
if(cat.value!=""){
query=cat.value;
if(window.XMLHttpRequest){

ajaxRequest = new XMLHttpRequest();

}else{

	ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
}

ajaxRequest.open("GET","addcatc.php?q="+query,true);

ajaxRequest.send();



ajaxRequest.onreadystatechange=function(){


	if(ajaxRequest.readyState==4 && ajaxRequest.status==200){
		//console.log("hamada");
		console.log(ajaxRequest.responseText);
	thesel=document.getElementById("selnode");
	newopt=document.createElement("option");
	newopt.setAttribute("value",cat.value);
	newopt.innerHTML=cat.value;
	thesel.appendChild(newopt);
	document.getElementById("cat").remove();
	document.getElementById("theaddbtn").remove();
	flag=false;
	}
}


}	
	//console.log(cat.value);
	

}






</script>

  </body>
  </html>
  
 


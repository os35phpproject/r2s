

<html>
<head>
		<style>

			h1{
				margin-left: 200px;
			}
			#container{
				width: 500px;
				margin-left: 200px;
			}
			body {
    background-image: url("13983462371122812644.jpg");
    /*background-image: url("coffee_small.jpg");*/
    background-repeat: no-repeat;
     background-position: right top;
       background-size: round auto; /* Height: auto is to keep aspect ratio */
 background-size: cover;
}
		</style>
	<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
 <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</head>
<body>

<?php 
session_start();
//var_dump("$_SESSION") ;
if(isset($_SESSION['logged'])){
	if($_SESSION['logged'] == 'true'){
		echo $_SESSION['usrid'];
		if($_SESSION['usrid'] == 1){
			header("Location:http://localhost/admin.php");
		}else{
			header("Location:http://localhost/client_page.php");
		}
		
	}
}

require_once("ORM.php");
$obj=ORM::getInstance();

// if(isset($_COOKIE['myappcookie'])){
// 	$res=$obj->getmycookie($_COOKIE['myappcookie']);
// 	if(mysqli_num_rows($res)!=0){
// 		//echo "case one";
// 		header("Location:http://localhost/show.php");
// 	}
// }


if(isset($_POST["submit"])){
if($_POST["submit"]=="login" ){
	
	

	$obj->setTable('users');
	$usrname=$_POST["username"];
	$usrpass=$_POST["password"];
	
	$uscheck=['usemail'=>$usrname,];

	

	$res=$obj->select("all",$uscheck);
	if(mysqli_num_rows($res)==0){
		//echo "case one";
		header("Location:http://localhost/prologin.php");
	}else{

		for($j=0;$j<mysqli_num_rows($res);$j++){
			$therec=mysqli_fetch_assoc($res);
			//echo $therec['uspassword'];
			if(md5($usrpass)==$therec['uspassword']){
				//echo "case two";
				$theid=$therec['usid'];
				$_SESSION['logged'] = 'true';
				$_SESSION['usrid'] = $therec['usid'];
				//echo $_SESSION['logged'];
				if(isset($_POST["rememberme"])){
				if($_POST["rememberme"]=="on"){
					$thecook=md5($_POST["username"].$_POST["password"]);
					$obj->insertcookie($thecook,$_POST["username"]);
					setcookie ('myappcookie',$thecook);

				}
				}
				//header("Location:http://localhost/client_page.php");
				if($_SESSION['usrid'] == 1){
			header("Location:http://localhost/admin.php");
		}else{
			header("Location:http://localhost/client_page.php");
		}
			}
		}

	}

	// echo $usrname;
	// echo $usrpass;

}

}


?>


<form method="post" class="form-horizontal">
<div id="container">
	<h1> Cafeteria </h1><br>
		<div class="form-group">
    		<label for="inputEmail3" class="col-sm-2 control-label">Email</label>
			<div class="col-sm-10">
			<input type="email" class="form-control" id="inputEmail3" placeholder="Email" name="username" value="<?php if(isset($_POST['username'])){echo $_POST['username'] ;} ?>">
		</div>
		</div>
<br/>

		<div class="form-group">
	    	<label for="inputEmail3" class="col-sm-2 control-label">Password</label>
	    	<div class="col-sm-10">
			<input type="password" class="form-control" id="inputPassword3" placeholder="Password" name="password" >
			</div>
		</div>

<br/>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">	
<input type="checkbox" name="rememberme">Remember Me</input>
</div>
</div>

	<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">	
<input type="submit" name="submit" value="login"/>
</div>
</div>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
<a href='selectforgetpasswordhandler.php'>forget password </a>
</div>
</div>
</div>
</form>
</body>
</html>
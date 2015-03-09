<html>

<body>

<?php
require_once("ORM.php");
if(isset($_POST['submit'])){
if($_POST['submit']=='submit'){
	
$obj=ORM::getInstance();
$obj->setTable("users");

$resu=$obj->select("all",['usemail'=>$_POST['theforgetemail'],]);
if(mysqli_num_rows($resu)!=0){
	for($j=0;$j<mysqli_num_rows($resu);$j++){
	$therec=mysqli_fetch_assoc($resu);
	$therec=$therec['usid'];
	header("Location:http://localhost/questionpage.php"."/?id=".$therec);
}

}else{
	$error='This User is not exist';
}
}
}
//$mymail=smtpmailer("rako90_ramy@yahoo.com","ramymalak15@gmail.com", "ramy malak", "my app mail", "that my first php mail");
//echo $error;

?>	


<form method="post">
Enter your Email:
<input type="text" name="theforgetemail"></input><span><?php if(isset($error)){echo $error;} ?></span>
<br/><br/>
<input type="submit" name="submit" value="submit"></input>
</form>








</body>


</html>

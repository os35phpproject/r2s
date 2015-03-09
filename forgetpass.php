<html>

<body>
<?php
require_once('phpmailer/class.phpmailer.php');
require_once("ORM.php");
if(isset($_POST['submit'])){
if($_POST['submit']=='submit'){
	
$obj=ORM::getInstance();
$obj->setTable("users");

$resu=$obj->select("all",['usemail'=>$_POST['theforgetemail'],]);
if(mysqli_num_rows($resu)!=0){
	for($j=0;$j<mysqli_num_rows($resu);$j++){
	$therec=mysqli_fetch_assoc($resu);
	$therec=$therec['usemail'];
	$rand=rand ( 1000 , 10000 );
	$newpass=$therec.$rand;
	$ret=smtpmailer($_POST['theforgetemail'],"r2sphpos35@gmail.com", "R2S", "new Password","you have a new password = $newpass");
	if($ret){
		$data=['uspassword'=>md5($newpass),];
		$cond=['usemail'=>$_POST['theforgetemail'],];
		$obj->update($data,$cond);
	}
	echo $error;
	

}

}else{
	$error='This Username is not exist';
}
}
}
//$mymail=smtpmailer("rako90_ramy@yahoo.com","ramymalak15@gmail.com", "ramy malak", "my app mail", "that my first php mail");
//echo $error;





function smtpmailer($to, $from, $from_name, $subject, $body) {
global $error;
$mail = new PHPMailer();  // create a new object
$mail->IsSMTP(); // enable SMTP
$mail->SMTPDebug = 1;  // debugging: 1 = errors and messages, 2 = messages only
$mail->SMTPAuth = true;  // authentication enabled
$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
$mail->Host = 'smtp.gmail.com';
$mail->Port = 465;
$mail->Username = ' r2sphpos35@gmail.com';  
$mail->Password = 'ramyrihamsamah';          
$mail->SetFrom($from, $from_name);
$mail->Subject = $subject;
$mail->Body = $body;
$mail->AddAddress($to);
if(!$mail->Send()) {
$error = 'Mail error: '.$mail->ErrorInfo;

//exit();
return false;
} else {
$error = 'Password Message sent!';
return true;
}
}
?>	


<form method="post">
Enter your Email:
<input type="text" name="theforgetemail"></input><span><?php if(isset($error)){echo $error;} ?></span>
<br/><br/>
<input type="submit" name="submit" value="submit"></input>
</form>








</body>


</html>

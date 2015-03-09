<?php
require_once("ORM.php");
$obj=ORM::getInstance();
$obj->setTable("users");

if(isset($_POST['answer'])){
	//var_dump($_POST);
$result=$obj->select("all",['usid'=>$_POST['usrname'],]);
for($j=0;$j<mysqli_num_rows($result);$j++){
		$thereclt=mysqli_fetch_assoc($result);
		$thereclt=$thereclt['usanswer'];
		if($thereclt==$_POST['answer']){
			$rand=rand ( 1000 , 10000 );
			$newpass=$thereclt.$rand;
			$data=['uspassword'=>md5($newpass),];
			$cond=['usid'=>$_POST['usrname'],];
			$obj->update($data,$cond);
			echo "Your new password is:".$newpass;
			$state="hidden";
			exit;
		}else{
			$error="Wrong Answer";
		}
	}
}




if(isset($_GET['id'])){
$resu=$obj->select("all",['usid'=>$_GET['id'],]);
if(mysqli_num_rows($resu)!=0){
	for($j=0;$j<mysqli_num_rows($resu);$j++){
		$therec=mysqli_fetch_assoc($resu);
		$therec=$therec['usquestion'];
		echo $therec;
	}

}

	
}
?>

<form method="post" <?php if(isset($state)){echo $state; } ?> >
Enter your Answer:
<input type="text" name="answer"></input><span><?php if(isset($error)){echo $error;} ?></span>
<br/><br/>
<input type="text" value=<?php echo $_GET['id']; ?> name="usrname" hidden> </input>
<input type="submit" name="submit" value="submit"></input>
</form>

<?php
session_start();
if(isset($_SESSION['logged'])){
session_destroy();
header("Location:http://localhost/prologin.php");
}else{
header("Location:http://localhost/prologin.php");	
}


?>
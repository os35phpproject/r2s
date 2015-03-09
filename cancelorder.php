
<?php
 require_once('ORM.php');
$id=$_GET['id'];
	$obj = ORM::getInstance();
	$obj->setTable('orders');
	$result = $obj->select(array("status"),array("orid"=>$id));
	 $num_results = mysqli_num_rows($result);
	  for ($i=0; $i <$num_results; $i++) {
	   $row = mysqli_fetch_assoc($result);
	   
	   	if($row['status']=='processing')
	   	{
           echo 'yes';
           $obj = ORM::getInstance();
	      $obj->setTable('orders');
	      $result = $obj->update(array("status"=>'cancel'),array("orid"=>$id));
	      echo $result;
	   	}
	    else
	    {
          echo 'no';
         
	    }


	     }
       
      header("Location:myorders1.php");

?>


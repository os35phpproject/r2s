<?php
 require_once('ORM.php');
$id=$_GET['id'];
	$obj = ORM::getInstance();
	$obj->setTable('product');
	$result = $obj->select(array("statues"),array("id"=>$id));
	 $num_results = mysqli_num_rows($result);
	  for ($i=0; $i <$num_results; $i++) {
	   $row = mysqli_fetch_assoc($result);
	   
	   	if($row['statues']==1)
	   	{
           echo 'yes';
           $obj = ORM::getInstance();
	      $obj->setTable('product');
	      $result = $obj->update(array("statues"=>0),array("id"=>$id));
	      echo $result;
	      
	   
	   	}
	    else
	    {
          echo 'no';
          $obj = ORM::getInstance();
	      $obj->setTable('product');
	      $result = $obj->update(array("statues"=>1),array("id"=>$id));
	      echo $result;
	    }


	     }
       
       header("Location:showproduct.php");

?>

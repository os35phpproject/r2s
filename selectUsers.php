<?php 
	$q=$_GET['q'];
	$dateFrom = $_GET['dateFrom'].' 00:00:00';
	$dateTo = $_GET['dateTo'].' 23:59:59';
// echo $q;
	require_once('ORM.php');
	$obj = ORM::getInstance();
	$obj->setTable('users');
	$names = "";
	$costs = "";
	$ids = "";
	if($_GET['q'] !== "all users"){
    	$result = $obj->select(array("usid","usname"),array("usname"=>$q));
    }else{
    	$result = $obj->select(array("usid","usname"));
    }
             $num_results = mysqli_num_rows($result);
             for ($i=0; $i <$num_results; $i++) {
             	$row = mysqli_fetch_assoc($result);	
				$names .= $row['usname'].",";
				$ids .= $row['usid'].",";
				$obj->setTable('orders');
				$cost = $obj->select_extra(array("sum(orcost)"),array("orusid"=>"=".$row['usid'],"ordate"=>" BETWEEN '$dateFrom' AND '$dateTo'"));
	            $num_costs = mysqli_num_rows($cost);
	            for ($j=0; $j <$num_costs; $j++) {
	                $row_cost = mysqli_fetch_assoc($cost);
               		$costs .= $row_cost["sum(orcost)"].",";
            	}
            
			}
			echo $names."|".$costs."|".$ids;
	

?>

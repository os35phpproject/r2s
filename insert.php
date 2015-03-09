<?php 

	/**
	* class to check validation of the form 
	*/

require_once 'ORM.php';


class validation
{
function validate($data)
	{
		$count=0;
		$str="";
		foreach ($data as $fields =>$value)
		 {
       if(empty($value))
       {
         $str= $fields." field is required";
         $count++; 
       }
       if($count>1)
       {
       	$str= "all field are required";
       }
       }
       return $str;
}}
class DB_Operations{
        function addProduct($data){
        	$obj = ORM::getInstance(); 
        	$obj->setTable('product');
        	 $obj->insert($data);
        	 var_dump($data);
			return "add correctly";
        }
        
         }

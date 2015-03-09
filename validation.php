<?php
	require_once('ORM.php');
	class Validation{

		public function validate($inData, $rules, &$validData, &$errors){
			$valid = TRUE;
			foreach($rules as $fieldName => $rule){
				$callBacks = explode('|', $rule);
				foreach ($callBacks as $callBack) {
					$value = isset($inData[$fieldName]) ? $inData[$fieldName] : NULL;
					if($callBack == "confirm"){
						$confirm = explode('confirm', $fieldName)[1];
						if($this -> $callBack($value, $inData[$confirm],$fieldName, $errors) == FALSE){
							$valid = false;
							break;
						}	
					}else{
						if($this -> $callBack($value, $fieldName, $errors) == FALSE){
							$valid = false;
							break;
						}else{
							$validData[$fieldName] = $inData[$fieldName];
						}
					}
				}
			}
			return $valid;
		}
		public function validateImage($inData, &$validData, &$errors){
			$valid = TRUE;
			if (empty($inData["name"])) {
				$errors = "your image is required";
				$valid = false;
			}elseif(!(($inData["type"] == "image/png") || ($inData["type"] == "image/jpg") || ($inData["type"] == "image/jpeg"))){
				$errors = "it is not an image you have to apload only images";
				$valid = false;
			}else{
				$inData["name"] = $validData["usext"].$inData["name"]; 
				$validData["usimage"] = $inData["name"];
			}
			return $valid;
		}
		private function required($value, $fieldName, &$errors){
			$valid = !empty($value);
			if($valid == FALSE){
				$errors[$fieldName] = "the $fieldName is required";
			}
			return $valid;
		}
		private function email($value, $fieldName, &$errors){
			$valid = filter_var($value, FILTER_VALIDATE_EMAIL);
			if($valid == FALSE){
				$errors[$fieldName] = "the $fieldName you entered is invalid";
			}
			return $valid;
		}
		private function confirm($value, $confirm, $fieldName, &$errors){
			$valid = ($confirm === $value);
			if($valid == FALSE){
				$errors[$fieldName] = "the $fieldName value is not the same as the first one";
			}
			return $valid;
		}
		private function unique($value, $fieldName, &$errors){
			$obj = ORM::getInstance();
			$obj->setTable('users');
			if(!empty($_GET["id"])){
				$result =  $obj->select(array("usid","$fieldName"),array("$fieldName"=>"$value"));	
				$num_results = mysqli_num_rows($result);
				if($num_results == 1){
					for ($i=0; $i <$num_results; $i++) {
				    	$row = mysqli_fetch_assoc($result);
				    	if ($row["usid"] == $_GET["id"]){
				    		$valid = TRUE;	
				    	}else{
				    		$valid = FALSE;
				    	}
					}	
				}elseif($num_results == 0){
					$valid = TRUE;	
				}else{
					$valid = FALSE;
				}
				
			}else{
				$result =  $obj->select(array("$fieldName"),array("$fieldName"=>"$value"));	
				$valid = ($num_results = mysqli_num_rows($result) == 0);
			}		
		 	

			if($valid == FALSE){
				$errors[$fieldName] = "the $fieldName you entered is already registered";
			}
			return $valid;

		}

	}

?>
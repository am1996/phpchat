<?php
	include("protectionsys.php");
	class User{
		public $fullname;
		public $email;
		public $password;
		public $errors = [];
		public $token;
		function __construct($fullname,$email,$password){
			$this->token = substr(bin2hex(random_bytes(64)),0,64);
			if($this->checkFullname($fullname)){
				$this->fullname = $fullname;
			}else{
				$this->errors[] = "Fullname is empty."; 
			}
			if($this->checkEmail($email)){
				$this->email = $email;
			}else{
				$this->errors[] = "Email is not correct.";
			}
			if($this->checkPassword($password)){
				$hashed_password = md5($password);
				$this->password = $hashed_password;
			}else{
				$this->errors[] = "Password is empty or shorter than 8 characters long.";
			}
		}
		function checkFullname($fullname){
			$fullname = ucwords($fullname);
			if(!empty($fullname)){
				return true;
			}else{
				return false;
			}
		}
		function checkEmail($email){
			$pattern = '/(.*)(@)(.*)\.(.*)/';
			if(!empty($email) && preg_match($pattern,$email)){
				return true;
			}else{
				return false;
			}
		}
		function checkPassword($password){
			if(strlen($password) >= 8){
				return true;
			}else{
				return false;
			}
		}
		function hasErrors(){
			if($this->errors > 0)
				return true;
			return false;
		}
	}
?>
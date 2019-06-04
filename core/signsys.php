<?php
	include("protectionsys.php");
	class SignSys{
		static function login($email,$password){
			if(self::checkLoggedIn()) die("You are already logged in.");
			global $userdb;
			$user = $userdb->getUser($email);
			if($user && $user["password"] === md5($password)){
				$uid = $user["id"];
				$token = substr(bin2hex(random_bytes(64)),0,64);
				$userdb->query("UPDATE users SET conftoken='$token' WHERE id='$uid'");
				setcookie("token",$token,time() + (86400 * 365),"/","",false,true);
				setcookie("uid",$uid,time() + (86400 * 365),"/","",false,true);
				return true;
			}else{
				return false;
			}
		}
		static function register($fullname,$email,$password){
			if(self::checkLoggedIn()) 
				die("You are already logged in.");
			$user = new User($fullname,$email,$password);
			global $userdb;
			if($user->hasErrors()){
				foreach($user->errors as $val){
					echo $val . "<br>";
					die();
				}
			}
			if($userdb->addUser($user)){
				$email = strtolower($email);
				$fullname = strtolower($fullname);
				$msg = "Confirmation email: http://{$_SERVER['HTTP_HOST']}/" . $user->token;
				$mail = self::sendEmail($user->email,"PHP Chat",$msg);
				if($mail)
					echo("Email was sent Successfully!<br>");
				else
					echo("Problem occured while sending email!<br>");
				die("Successfully added User.");
			}else{
				die("User already exists.");
			};
		}
		static function checkLoggedIn(){
			@$uid = $_COOKIE["uid"];
			@$token = $_COOKIE["token"];
			global $userdb;
			$user = $userdb->getUserById($uid);
			if(isset($_SESSION["user"])){
				return true;
			}else if($token === $user["conftoken"] && isset($token)){
				unset($user["password"]);
				$_SESSION["user"] = $user;
				return true;
			}
			return false;
		}
		static function logout(){
			setcookie("token",NULL,time() - 100,"/","",false,true);
			setcookie("uid",NULL,time() - 100,"/","",false,true);
			unset($_SESSION['user']);
			return true;
		}
		static function sendEmail($email,$title,$msg){
			$headers =  'MIME-Version: 1.0' . "\r\n"; 
			$headers .= 'From: admin@phpchat.com' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n"; 
			$mail = mail($email,$title,$msg,$headers);
			if($mail) return true;
			else 	  return false;
		}
		static function confirmUser($id,$token){
			global $userdb;
			$user = $userdb->getUserById($id);
			if($user){
				if($token === $user["conftoken"]){
					$userdb->query("UPDATE users SET confirm = 1 WHERE id ='$id';");
					$user["confirm"] = 1;
					$userjson = json_encode($user);
					setcookie("user",$userjson,time() + (86400 * 365),"/","",false,true);
					return "Successfully confirmed your account.";
				}else{
					return "Wrong confirmation token";
				}
			}else{
				return "Wrong email.";
			}
		}
		static function editUser($id,$fields){
			global $userdb;
			//check email
			$userdb->updateUser($id,$fields);
		}
	}
?>
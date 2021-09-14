<?php
	/*
	*add xss protection here;
	*/
	if(!defined('SECRET')){
		http_response_code(404);
		die("404 Page not found!");
	}
	class UserDb{
		public $conn;
		function __construct($host,$db_user,$db_password,$db_name){
			$this->conn = new mysqli($host,$db_user,$db_password,$db_name);
			$this->conn->set_charset("utf8mb4");
			if($this->conn->connect_error){
				die("Connection Failed");
			}
		}
		function getAllUsers($uid){
			$sql = "SELECT id,fullname,email FROM users WHERE id != '$uid'";
			try{
				$arr = [];
				$result = $this->conn->query($sql);
				while($arr[] = $result->fetch_assoc());
				return array_filter($arr);
			}catch(Exception $e){
				return "No Friends";
			}
		}
		function addUser($user){
			$fullname = $user->fullname;
			$email = $user->email;
			$password = $user->password;
			$token = $user->token;
			if($this->getUser($email))
				return false;
			$stmt = $this->conn->prepare("INSERT INTO users(fullname,email,password,conftoken) VALUES(?,?,?,?)");
			$stmt->bind_param("ssss",$fullname,$email,$password,$token);
			$stmt->execute();
			$stmt->close();
			return true;
		}
		function updateUser($id,$fields){
			foreach($fields as $k => $v){
				$v = strtolower($v);
				$v = mysqli_real_escape_string($this->conn,$v);
				switch($k){
					case "email":
						$pattern = '/(.*)(@)(.*)\.(.*)/';
						if(preg_match($pattern,$v)){
							$this->query("UPDATE users SET email='$v' WHERE id=$id");
						}else{
							die("Wrong email address.");
						}
						break;
					case "fullname":
						$v = ucwords($v);
						$this->query("UPDATE users SET fullname='$v' WHERE id=$id");
						break;
					case "password":
						if(strlen($v) >= 8){
							$v = md5($v);
							$this->query("UPDATE users SET password='$v' WHERE id=$id");
						}else{
							die("The password must be 8 characters long.");
						}
						break;
				}
			}
		}
		function query($sql){
			if($this->conn->query($sql)){
				return 1;
			}else{
				return $this->conn->error;
			}
		}
		function getUser($email){
			$user = NULL;
			$result = $this->conn->query("SELECT * FROM users WHERE email = '$email'");
			if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()){
					$user = $row;
				}
			}
			return $user;
		}
		function getUserById($id){
			$id = htmlspecialchars($id, ENT_QUOTES, 'UTF-8');
			$user = NULL;
			$result = $this->conn->query("SELECT * FROM users WHERE id = '$id'");
			if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()){
					$user = $row;
				}
			}
			return $user;
		}
		function deleteUser($email){
			$this->conn->query("DELETE FROM users WHERE email='$email'");
			return true;
		}
		function __destruct(){
			$this->conn->close();
		}
	}

	class FriendDb{
		public $conn;
		function __construct($host,$db_user,$db_password,$db_name){
			$this->conn = new mysqli($host,$db_user,$db_password,$db_name);
			$this->conn->set_charset("utf8mb4");
			if($this->conn->connect_error){
				die("Connection Failed");
			}
		}
		//list people who didn't send a friend request
		function getNoRequests($id){
			$sql = "SELECT adder FROM friends WHERE added = $id 
					UNION SELECT added FROM friends WHERE adder = $id";
			$per_sql = "SELECT id,fullname,email FROM users";

			$suggested = [];
			$ppladded = [];
			$resadded = $this->conn->query($sql)->fetch_all();

			foreach($resadded as $key => $value){
				$ppladded[] = $value[0];
			}
			$allusers = $this->conn->query($per_sql)->fetch_all();
			foreach($allusers as $key => $user){
				if(!in_array($user[0],$ppladded) && $user[0] !== $id)  
					$suggested[] = $user;
			}
			return $suggested;
		}
		function getRequestsForUser($uid){
			$sql = "SELECT * FROM friends WHERE added = '$uid' AND confirm = 0";
			$per_sql = "SELECT id,fullname,email FROM users WHERE id = ";
			$requests = [];
			try{
				$arr = [];
				$result = $this->conn->query($sql);
				while($arr[] = $result->fetch_assoc()["adder"]);
				$arr = array_filter($arr);
				foreach($arr as $index => $id){
					$res = $this->conn->query($per_sql.$id);
					$requests[] = $res->fetch_assoc();
				};
				return $requests;
			}catch(Exception $e){
				return "No Friends";
			}
		}
		//edit this function it doesn't work right
		function getFriends($uid){
			$sql = "SELECT id,fullname,email FROM users WHERE id != '$uid'";
			$fdsql = "SELECT added FROM friends WHERE confirm = 1 AND adder = $uid
					  UNION SELECT adder FROM friends WHERE confirm = 1 AND added = $uid";
			try{
				$fd_arr = [];
				$fd_res = $this->conn->query($fdsql);
				$res = $fd_res->fetch_all($mode= MYSQLI_ASSOC);
				foreach($res as $item){
					$fd_arr[] = $item["added"];
				}
				$fd_arr = array_filter($fd_arr); // remove null

				$arr = [];
				$result = $this->conn->query($sql);
				while($res = $result->fetch_assoc()){
					if( in_array($res["id"],$fd_arr) ) $arr[] = $res;
				};
				return array_filter($arr);
			}catch(Exception $e){
				return "No Friends";
			}
		}
		function getFriend($id){
			$list = NULL;
			$result = $this->conn->query("SELECT * FROM friends WHERE adder = '$id' OR added= '$id'");
			if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()){
					$list += $row;
				}
			}
			return $list;
		}
		function getFriendRequest($adder,$added){
			$request = NULL;
			$adder = mysqli_real_escape_string($this->conn,$adder);
			$added = mysqli_real_escape_string($this->conn,$added);
			$result = $this->conn->query("SELECT * FROM friends WHERE adder = '$adder' AND added= '$added'");
			$request = $result->fetch_assoc();
			return $request;
		}
		function removeFriendRequest($adder,$added){
			$request = NULL;
			$adder = mysqli_real_escape_string($this->conn,$adder);
			$added = mysqli_real_escape_string($this->conn,$added);
			$query = $this->conn->query("DELETE FROM friends WHERE adder = '$adder' AND added= '$added'");
			if($query) return 1;
			else return 0;
		}
		function acceptFriend($id){
			$id = mysqli_real_escape_string($this->conn,$id); //sanitize input
			$sql = "UPDATE friends SET confirm = 1 WHERE adder = '$id'";
			$this->conn->query($sql);
			if(mysqli_connect_errno()) return 0;
			else return 1;
		}
		function checkFriends($adder,$added){
			$request = NULL;
			$sql ="SELECT * FROM friends WHERE 
				(adder = '$adder' AND added= '$added') OR 
				(adder = '$added' AND added= '$adder') AND 
				(confirm = 1)";
			$result = $this->conn->query($sql);
			$request = $result->fetch_assoc();
			return $request;
		}
		function addFriend($adder,$added){
			$adder = mysqli_real_escape_string($this->conn,$adder);
			$added = mysqli_real_escape_string($this->conn,$added);
			$query = "INSERT INTO friends(adder,added) VALUES($adder,$added)";
			if($this->getFriendRequest($adder,$added)){
				return 0;
			}else if($this->checkFriends($adder,$added)){
				return 2;
			}else if($stmt = $this->conn->query($query)){
				return 1;
			}else{
				echo "Something went wrong.";
			}
		}
		function __destruct(){
			$this->conn->close();
		}
	}
	
	class MsgDb{
		public $conn;
		function __construct($host,$db_user,$db_password,$db_name){
			$this->conn = new mysqli($host,$db_user,$db_password,$db_name);
			$this->conn->set_charset("utf8mb4");
			if($this->conn->connect_error){
				die("Connection Failed");
			}
		}
		function saveMsg($sender,$reciever,$msg,$id){
			$msg = htmlspecialchars($msg, ENT_QUOTES, 'UTF-8');
			try{
				if(!isset($sender) || !isset($reciever) || !isset($msg)) 
					throw new Exception("msg is empty");
				$stmt = $this->conn->prepare("INSERT INTO msgs(sndr,rcvr,msg,id) VALUES(?,?,?,?);");
				$stmt->bind_param("iiss", $sender,$reciever,$msg,$id);
				$stmt->execute();
					 
			}catch(Exception $e){
				return false;
			}
			return true;

		}
		function getMsgs($sender,$reciever){
			$sql = "SELECT * from msgs WHERE 
					(sndr=$reciever AND rcvr=$sender) OR
					(sndr=$sender AND rcvr=$reciever) ORDER BY at ASC";
			$stat = $this->conn->query($sql);
			$result = [];
			while($result[] = $stat->fetch_assoc());
			return array_filter($result);
		}
	}
	$userdb = new UserDb($host,$db_user,$db_password,$db_name);
	$frienddb = new FriendDb($host,$db_user,$db_password,$db_name);
	$msgdb = new MsgDb($host,$db_user,$db_password,$db_name);
?>
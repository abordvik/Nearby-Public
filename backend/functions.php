<?php
	class nearby{
		private static $mysqlServer = 'localhost';
		private static $mysqlUser = 'dev1';
		private static $mysqlPass = '123123';
		private static $mysqlDb = 'dev1';
		private static $mysqli;
		private static $u;
		
		/** This constructor sets up the DB connection
		 *
		 */
		function nearby(){
			self::$mysqli = new mysqli(self::$mysqlServer,self::$mysqlUser,self::$mysqlPass,self::$mysqlDb);
		}
		
		/** This methode returns an array of nearby friends to a given user in a given range
		 * 	@param $userToQuery(The user calling the service), $range(The range looking in)
		 * 	@return an array of all nearby freinds, if no nearby friends are found nearby[0] = -1 is returned
		 */
		function nearbyMe($range){
			//First, querys the callings users location (A methode for updating location needs to be implemented)
			$query = 'SELECT x, y FROM users WHERE id = ' . self::$u;
			$row = self::$mysqli->query($query) -> fetch_assoc() ; //Fetches the data
			$myX = $row['x']; //Contains the calling users current x-coord
			$myY = $row['y']; //Contains the calling users current y-coord
		
			//Finds all the users who are friends with the user and query their information
			$query = 'SELECT id,user,x,y,telephone,imageurl FROM users INNER JOIN connections ON users.id = connections.user2 WHERE connections.user1 = ' . self::$u; //Magic was just applied
			$result = self::$mysqli->query($query);

			//The working array
			$nearby; 

			//Running through the results and saving those who are close to you
			for($i = 0, $h = 0; $row = $result->fetch_assoc(); $i++){
				if(self::distanceToFriend($myX, $myY, $row['x'], $row['y']) < $range){ //If the friends are in range this will be true
					
					$nearby[$h] = array('id' => $row['id'], 'user' => $row['user'], 'distance' => self::distanceToFriend($myX, $myY, $row['x'], $row['y']), 'telephone' => $row['telephone'], 'imageurl' => $row['imageurl'], 'bearing' => self::bearingToFreind($myX, $myY, $row['x'], $row['y']) );
					//$nearby[$h] = $row['id'] . "," . $row['user'] . "," . self::distanceToFriend($myX, $myY, $row['x'], $row['y']) . "," . $row['telephone'] . "," . $row['imageurl'] . "," . self::bearingToFreind($myX, $myY, $row['x'], $row['y']); //Saves the nearby friend in the working array
					$h++;
					
					if ($query = self::$mysqli -> prepare("INSERT INTO log VALUES (?, ?, ?, ?)")) { 
						$query -> bind_param('ssss', self::$u, $row['id'], time() , intval(self::distanceToFriend($myX, $myY, $row['x'], $row['y']) * 1000)); 
						$query -> execute();
					}
				}
			}
			return isset($nearby) ? json_encode($nearby) : -1; //Returns the working array
		}
		
		/** This methode will update a users coords. Will return 1 if operation succesed, otherwise return 0
		 * @param $userToUpdate, $x, $y
		 * @return $i = 1 if operation was successful, $i = 0 if not
		 */
		function updateCoords($x, $y){
			//$query = 'UPDATE users SET x = '.$x.', y = '.$y.' WHERE id = '. self::$u;
			//return self::$mysqli->query($query);
			if ($query = self::$mysqli -> prepare("UPDATE users SET x = ?, y = ?, lastUpdate = ? WHERE id = ?")) { 
				$query -> bind_param('ssss', $x, $y , time(), self::$u); 
				$query -> execute();
			}
			return 1;
			
		}
		
		static function bearingToFreind($x1, $y1, $x2, $y2){
			$lat1 = deg2rad($y2);
			$lat2 = deg2rad($y1);
			
			$dLon = deg2rad($x2-$x1);
			
			$y = sin($dLon) * cos($lat2);
			$x = cos($lat1)*sin($lat2) - sin($lat1) * cos($lat2) * cos($dLon);
			$b = (rad2deg(atan2($y, $x)) + 180) % 360;
			
			
			if(315 < $b || $b < 45)
				return "N";
			else if(45 < $b && $b < 135)
				return "E";
			else if(135 < $b && $b < 225)
				return "S";
			else if(225 < $b && $b < 315)
				return "W";
			return "-";
			
			
		}


		
		/** This methode calculates the distance in km between two sets of lon / lat coordinats 
		 * @param $x1, $x2, $y1, $y2
		 * @return distance
		 */
		static function distanceToFriend($x1, $y1, $x2, $y2){
			$dLat = deg2rad($y2-$y1);
			$dLon = deg2rad($x2-$x1);
			$lat1 = deg2rad($y2);
			$lat2 = deg2rad($y1);
			$a = sin($dLat/2) * sin($dLat/2) + sin($dLon/2) * sin($dLon/2) * cos($lat1) * cos($lat2); 
			
			return 6371* (2 * atan2(sqrt($a), sqrt(1-$a)));
		}
		
		function close(){
			mysqli_close(self::$mysqli);
		}
		
		
		function login($username, $password){
			$time = time();
			$token;
			
			if ($query = self::$mysqli->prepare("SELECT id, password, salt FROM users WHERE user = ? LIMIT 1")) { 
				$query -> bind_param('s', $username); 
				$query -> execute();
				$query->store_result();
				$query->bind_result($id, $db_password, $salt); // get variables from result.
				$query->fetch();
				$password = hash('sha512', $password.$salt);
					if($db_password == $password){
						$token = hash('sha512', $username . $time . $salt);
						if ($query = self::$mysqli -> prepare("INSERT INTO sessions (token, expire , uid) VALUES (?, ?, ?)")) { 
							$query -> bind_param('sss', $token, $time , $id); 
							$query -> execute();
						}
						$token = hash('sha512', $token); //. $_SERVER['HTTP_USER_AGENT']);
						
						self::$u = $id;
						setcookie("uid", self::$u, (time()+60*60*24*30));
						echo self::$u . "," . $token . "," . $username;
					}
				}
		}
		
		function check(){
			$id = isset($_POST['uid']) ? $_POST['uid'] : $_POST['uid'];
			if($query = self::$mysqli -> prepare("SELECT token FROM sessions WHERE uid = ?")){
				$query -> bind_param('s', $id); 
				$query -> execute();
				$query->bind_result($db_token);
				
				while($query -> fetch()) { 
					$db_token = hash('sha512', $db_token ); //. $_SERVER['HTTP_USER_AGENT']);
					if($db_token != $_POST['token'] && $db_token != $_GET['token']){ self::$u = null; continue; }
					self::$u = $id;
					break;
				}
				
				if(self::$u == null){ echo "not logged in"; echo $id; echo $_POST['token']; echo "hi"; exit;}
				}
		}
		function logout(){
			if ($query = self::$mysqli -> prepare("DELETE FROM sessions WHERE uid = ?")) { 
				$query -> bind_param('s', $_COOKIE['uid']); 
				$query -> execute();
			}

		}
		
		
		function linkUsers($username){
			if ($query = self::$mysqli -> prepare("SELECT id FROM users WHERE user = ?")) { 
				$query -> bind_param('s',$username); 
				$query -> execute();
				$query->store_result();
				$query->bind_result($id); // get variables from result.
				$query->fetch();
				if($id > 0 && ($query = self::$mysqli -> prepare("INSERT INTO connections VALUES (?, ?, ?)"))){
						$stat = 0;
						$query -> bind_param('sss', $id, self::$u, $stat);
						$query -> execute();

							
					
				
				}
				if ($id == "0") {
					echo "User does not exist";
				}
				else{
					echo "success";
				}

			}

		}
		
		function newUser($username, $password, $telephone, $imageurl){
			$random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
			$password = hash('sha512', $password . $random_salt);
			if($query = self::$mysqli -> prepare("SELECT id FROM users WHERE user = ?")){
				$query -> bind_param('s', $username); 
				$query -> execute();
				$query -> store_result();
				
				if($query -> num_rows > 0) { echo "user already exsists"; }
				else if($query = self::$mysqli -> prepare("INSERT INTO users (user, password, salt, telephone, imageurl) VALUES (?, ?, ?, ?, ?)")){
					$query -> bind_param('sssss', $username, $password, $random_salt, $telephone, $imageurl);
					$query -> execute();
				}
			}
		}

		function friendList($username){
			//First we find all the users which are connected with $username, then we grab their info
			$query = 'SELECT id,user,telephone FROM users INNER JOIN connections ON users.id = connections.user2 WHERE connections.user1 = ' . self::$u; //Magic was just applied
			$result = self::$mysqli->query($query);

			//The working array
			$friendList; 

			for($i = 0, $h = 0; $row = $result->fetch_assoc(); $i++)
					$friendList[$h] = $row['id'] . "," . $row['user'] . "," . $row['telephone']; //Saves the nearby friend in the working array
					$h++;
			return isset($friendList) ? $friendList : -1; //Returns the working array

		}
		function getUser($user){
			if ($query = self::$mysqli -> prepare("SELECT id FROM users WHERE user = ?")) { 
				$query -> bind_param('s',$user); 
				$query -> execute();
				$query->store_result();
				$query->bind_result($id); // get variables from result.
				$query->fetch();

				if ($query = self::$mysqli->prepare("SELECT user,telephone,imageurl FROM users WHERE id = ?")) { 
					$query -> bind_param('s', $id); 
					$query -> execute();
					$query->store_result();
					$query->bind_result($username, $telephone, $imageurl); // get variables from result.
					$query->fetch();
					echo $username . "," . $telephone . "," . $imageurl;
					
				}
			}
		}
	}
?>
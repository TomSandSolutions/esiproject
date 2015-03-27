<?php
	require_once 'classes/user.class.php';
	require_once 'db.class.php';
	
	
	class UserTools{
		//Log the user in. First checks to see if the
		//username and password match a row in the database.
		//If it is successful, set the session variables
		//and store the user object within.
		public function login($username, $password){
			$hashedPassword = md5($password);
			$result = mysqli_query("SELECT * FROM users WHERE username = '$username' AND password = '$hashedPassword'");
			if (mysqli_num_rows($result) == 1){
				$_SESSION["user"] = serialize(new User(mysqli_fetch_assoc($result)));
				$_SESSION["login_time"] = time();
				$_SESSION["logged_in"] = 1 ;
				return true ; 
			} else {
				return false;
			}
		}
		// log the user out. Destroy the session variables
		public  function logout(){
			unset($_SESSION["user"]);
			unset($_SESSION["login_time"]);
			unset($_SESSION["logged_in"]);
			session_destroy();
		}
		
		// Check to see wether a username exist
		// This is called during registration to make sure that every registered user is unique
		public function checkUsernameExist($username) {
			$result = mysqli_query("select id from users where username = '$username'");
			if (mysql_num_rows($result) == 0) {
				return false;
			} else {
				return true;
			}
		}
		
		//get a user
		// return a user object. Takes the users id as an input
		public function get($id) {
			$db = new DB();
			$result = $db->select('users', "id = $id");
			return new User($result);
		}
		
		// Modifier son mot de passe. cette action doit être exécutée par l'utilisateur
		// Le resultat est un booleen pour signifier que la modification a été bien effectué
	}
?>
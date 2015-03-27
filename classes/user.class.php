<?php
require_once 'Models/db.class.php';
class user{
	private $id;
	private $username;
	private $hashedPassword;
	private  $email;
	private $position;
	//Le constructeur est  appelé quand un nouvel objet est crée
	// il prend en entrée un array contenant les variables de la classe.

	public function _construct($data){
		$this->id = (isset($data['id'])) ? $data['id'] : "" ;
		$this->username = (isset($data['username'])) ? $data['username'] : "" ;
		$this->hashedPassword = (isset($data['password'])) ? $data['password'] : "" ;
		$this->email = (isset($data['email'])) ? $data['email'] : "" ;
		$this->position = (isset($data['position'])) ? $data['position'] : "" ;
	}
	// Ici on modifie l'adresse email d'un utilisateur existant
	public function setEmail($newEmail){
		
		if (filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
			$this->email = $newEmail;
		} else {
			trigger_error("e-mail invalide", E_USER_WARNING);
		}
	}
	
	// cette function modifie le mot de passe d'un utilisateur. Elle prend en argument deux arguments
	// et retourne false quand la modification n'as pas eu lieu
	public function setPassword($lastPassword, $password){
		if(md5($lastPassword) == $db->get_result("SELECT password FROM Employe WHERE CodeEmp = '$this->id'")){
			$this->hashedPassword = md5($password);
		}else {
			return false; 
		}
			
	} 
	public function setJob($newposition){
		if (!(is_null($newposition)) && (is_string($newposition))) {
			$this->position = $newposition;
		} else {
			trigger_error("La variable passé ne peut être prise en compte", E_USER_WARNING);
		}
	}
	// getters
	public function getUserName(){
		return $this->username;
	}
	
	public function getEmail(){
		return $this->email;
	}
	
	public function getPosition(){
		return $this->position;
	}
	
	public function save(){
		// create a new database object
		$data = array(
				"username" => "'$this->username",
				"password" => "'$this->hashedPassword",
				"email" => "'$this->email",
				"position" => "'$this->positon"
		);
		$db = new DB();
		$temp = $db->get_results($query);
		
		if(in_array($this->id, $temp)){
			// update the row in the database
			$db->update($data, 'employe', '$id= '.$this->id);
		} else {
		
			$db->insert('employe' ,$data);
		}
		return true;
	}
}
?>
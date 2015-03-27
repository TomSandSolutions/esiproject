<?php
include_once 'Models/db.class.php';
class product{
	private $id;      // identifiant du produit
	private $libelle;      // libellé du produit
	private $price; // prix disponible
	private $type   ;  // type de produit (Jus ananas, Pièces rechanges, Matières première)
	

	public function __construct($data){
			$this->id = (isset($data['id'])) ? $data['id'] : "" ;
			$this->libelle = (isset($data['libelle'])) ? $data['libelle'] : "" ;
			$this->price = (isset($data['price'])) ? $data['price'] : "" ;
			$this->type = (isset($data['type'])) ? $data['type'] : "" ;
		}	
		

	public function  prod_exist(){
		$check_product = array(
				'prod_libelle' => "'$this->libelle",
				'prod_type' => "'$this->type"
				);
		
		if ($database->exists( 'produit', $this->libelle, $check_product ) == true){
			return true ;
		} else {
			return false;
		}
	}
	
	// getters
	public function getProductName() {
		$n = $this->name;
		return $n;
	}
	
	public static  function getStock() {
		$n = $this->stock;
		return $n;
	}
	
	public function getType() {
		$n = $this->type;
		return $n ;
	}
	
	
	// setters
	public function setProductName($art) {
		 $this->name = $art;
		
	}
	
	public function setStock($art) {
		$this->stock = $art;
	
	}
	
	public function setType($art) {
		$this->type = $art;
	
	}
	public function save(){
		// create a new database object
		$data = array(
				"libelle" => "'$this->username",
				"price" => "'$this->hashedPassword",
				"type" => "'$this->email"
		);
		$db = new DB();
		$temp = $db->get_results($query);
	
		if(in_array($this->id, $temp)){
			// update the row in the database
			$db->update($data, 'produit', '$id= '.$this->id);
		} else {
	
			$db->insert('produit', $data);
		}
		return true;
	}
}
<?php

require_once 'Models/db.class.php';
require_once 'products.php';
class Entrepot {
	private $id_entrepot;
	private $libellé;
	private $adresse;
	private $nomResponsable;
	
	
	public function __construct($libelle, $addr, $name) {
			$this->libellé= $libelle;
			$this->adresse = $addr;
			$this->nomResponsable = $name;	
	}
	
	public function get_stockDispo(product $prod) {
		if (!is_null($prod->libelle) && is_object($prod)){
		$query = "SELECT * FROM PRODUIT WHERE ID= '$prod->id' "; // requête pas bonne juste provisoire en  attendant les modifs
		$db = new DB();
		$db->get_results($query);
			$res = array(
				"produit" => "'$prod->libelle",
				"stock" => "'$prod->getStock()"		
			);
		return $res;
		} else {
			trigger_error("Produit non definie", E_USER_WARNING);
		}
	}
	
	public function addStock($param) {
		;
	}
}
?>
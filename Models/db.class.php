<?php

class DB
{
private $link = null;
public $filter;
static $inst = null;
public static $counter = 0;

public function log_db_errors( $error, $query )
{
	$message = '<p>Erreur à '. date('Y-m-d H:i:s').':</p>';
	$message .= '<p>Requete: '. htmlentities( $query ).'<br />';
	$message .= 'Erreur: ' . $error;
	$message .= '</p>';

	
		echo $message;
	}

public function __construct()
{

try {
$this->link = new mysqli( DB_HOST, DB_USER, DB_PASS, DB_NAME );
} catch ( Exception $e ) {
die( 'impossible de se connecter à la base de données' );
}
}
public function __destruct()
{
if( $this->link)
{
$this->disconnect();
}
}

/**
* Fonction de filtrage pour eviter l'injection (SQL injection)
*/
public function escape( $data )
{
if( !is_array( $data ) )
{
$data = $this->link->real_escape_string( $data );
}
else
{
//Self call function to sanitize array data
$data = array_map( array( $this, 'escape' ), $data );
}
return $data;
}

/**
* Effectuer des requetes
*
*/
public function query( $query )
{
$full_query = $this->link->query( $query );
if( $this->link->error )
{
$this->log_db_errors( $this->link->error, $query );
return false;
}
else
{
return true;
}
}

/**
* retourne le nombre de lignes correspondand à une a 
* recherche spécifique dans la BD
*
*/
public function num_rows( $query )
{
self::$counter++;
$num_rows = $this->link->query( $query );
if( $this->link->error )
{
$this->log_db_errors( $this->link->error, $query );
return $this->link->error;
}
else
{
return $num_rows->num_rows;
}
}
/**
* Fonction qui verifie l'existance d'une valeur 
* retourne true ou false
*
*/
public function exists( $table = '', $check_val = '', $params = array() )
{
self::$counter++;
if( empty($table) || empty($check_val) || empty($params) )
{
return false;
}
$check = array();
foreach( $params as $field => $value )
{
if( !empty( $field ) && !empty( $value ) )
{
//Check for frequently used mysql commands and prevent encapsulation of them
if( $this->db_common( $value ) )
{
$check[] = "$field = $value";
}
else
{
$check[] = "$field = '$value'";
}
}
}
$check = implode(' AND ', $check);
$rs_check = "SELECT $check_val FROM ".$table." WHERE $check";
$number = $this->num_rows( $rs_check );
if( $number === 0 )
{
return false;
}
else
{
return true;
}
}

/**
* Perform query to retrieve array of associated results
*
*/
public function get_results( $query, $object = false )
{
self::$counter++;
//Overwrite the $row var to null
$row = null;
$results = $this->link->query( $query );
if( $this->link->error )
{
$this->log_db_errors( $this->link->error, $query );
return false;
}
else
{
$row = array();
while( $r = ( !$object ) ? $results->fetch_assoc() : $results->fetch_object() )
{
$row[] = $r;
}
return $row;
}
}

 // Insertion de données dans une table de la BD


public function insert( $table, $variables = array() )
{
self::$counter++;
//s'assurer que le tableau est vide
if( empty( $variables ) )
{
return false;
}
$sql = "INSERT INTO ". $table;
$fields = array();
$values = array();
foreach( $variables as $field => $value )
{
$fields[] = $field;
$values[] = "'".$value."'";
}
$fields = ' (' . implode(', ', $fields) . ')';
$values = '('. implode(', ', $values) .')';
$sql .= $fields .' VALUES '. $values;
$query = $this->link->query( $sql );
if( $this->link->error )
{
//return false;
$this->log_db_errors( $this->link->error, $sql );
return false;
}
else
{
return true;
}
}

/**
* Mettre à jour données dans une table de la BD
*
*/
public function update( $table, $variables = array(), $where = array(), $limit = '' )
{
self::$counter++;
//S'assurer que les donnée requises sont passées en parametre avant de continuer

if( empty( $variables ) )
{
return false;
}
$sql = "UPDATE ". $table ." SET ";
foreach( $variables as $field => $value )
{
$updates[] = "`$field` = '$value'";
}
$sql .= implode(', ', $updates);
//Add the $where clauses as needed
if( !empty( $where ) )
{
foreach( $where as $field => $value )
{
$value = $value;
$clause[] = "$field = '$value'";
}
$sql .= ' WHERE '. implode(' AND ', $clause);
}
if( !empty( $limit ) )
{
$sql .= ' LIMIT '. $limit;
}
$query = $this->link->query( $sql );
if( $this->link->error )
{
$this->log_db_errors( $this->link->error, $sql );
return false;
}
else
{
return true;
}
}
/**
* Suppression de données dans une table
*
*/
public function delete( $table, $where = array(), $limit = '' )
{
self::$counter++;
//Delete clauses require a where param, otherwise use "truncate"
if( empty( $where ) )
{
return false;
}
$sql = "DELETE FROM ". $table;
foreach( $where as $field => $value )
{
$value = $value;
$clause[] = "$field = '$value'";
}
$sql .= " WHERE ". implode(' AND ', $clause);
if( !empty( $limit ) )
{
$sql .= " LIMIT ". $limit;
}
$query = $this->link->query( $sql );
if( $this->link->error )
{
//return false; //
$this->log_db_errors( $this->link->error, $sql );
return false;
}
else
{
return true;
}
}

/**
* Se deconnecter de la BD
*/
public function disconnect()
{
$this->link->close();
}
} //end class DB
?>
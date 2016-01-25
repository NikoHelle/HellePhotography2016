<?php
class DB
{
    // property declaration
	public static $version = "0.1";
	private $_connection = false;
	public $error = false;
	public $fetchMode = false;
	
    
	

    // method declaration
   function __construct($dbUser,$dbPass,$dbHost,$dbName) {
	  
	 	
	
		try {  
		  $this->_connection = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);  
	
		}  
		catch(PDOException $e) {  
			$this->_connection = false;
			$this->error = $e->getMessage();  
			return;
		}  
		
		$this->fetchMode = PDO::FETCH_ASSOC;
		$this->_connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION ); 
	
	  
   }
   
   public function isConnected(){
		return $this->_connection !==false;   
   }
   
    public function select($sql,$fetchMode=false){
		
		$q = $this->query($sql,false);
		if(!$q) return false;
		return $q->fetchAll($fetchMode);
		/*if(!$this->_connection) {
			$this->error = "no connection";
			return false;
		}
		//$this->_connection->closeCursor();
		if(!$fetchMode) $fetchMode = $this->fetchMode;
		
		try {  
		   $q = $this->_connection->query($sql);
			//$q->setFetchMode($this->fetchMode);
			return $q->fetchAll($fetchMode);
		}  
		catch(PDOException $e) {  
			$this->error = $e->getMessage();
			return false;
		}*/
   }
   
   public function getRow(&$resultSet,$fetchMode=false){
	   if(!$resultSet) return false;
	   if(!$fetchMode) $fetchMode = $this->fetchMode;
	   if($fetchMode === PDO::FETCH_ASSOC && !is_array($resultSet)) return false;
	   if($fetchMode === PDO::FETCH_ASSOC && count($resultSet)<1) return false;
	   return array_shift($resultSet); //??? other fetchmodes?
   }
   
    public function insert($sql,$prepare=false){
		
		$q = $this->query($sql,false);
		if(!$q) return false;
		return $this->_connection->lastInsertId(); 
		
		/*if(!$this->_connection) {
			$this->error = "no connection";
			return false;
		}
		
		try {  
		   $q = $this->_connection->prepare($sql);
			//$q->setFetchMode($this->fetchMode);
			if($prepare === false || !$prepare) $prepare = array();
			$ret = $q->execute($prepare);
			if($ret) return $this->_connection->lastInsertId(); 
			return  false;
		}  
		catch(PDOException $e) {  
			$this->error = $e->getMessage();
			return false;
		}*/
   }
   
   public function update($sql,$prepare=false){
		if(!$this->_connection) {
			$this->error = "no connection";
			return false;
		}
		
		try {  
		   $q = $this->_connection->prepare($sql);
			if($prepare === false || !$prepare) $prepare = array();
			$ret = $q->execute($prepare);
			if($ret) return $q->rowCount();
			return  0;
		}  
		catch(PDOException $e) {  
			$this->error = $e->getMessage();
			return false;
		}
   }
   
   public function delete($sql,$prepare=false){
		return $this->update($sql,$prepare);
   }
   
   public function quote($var){
   		return $this->_connection->quote($var); 
   }
   
   public function query($sql,$prepare=false){
	  
	   if(!$this->_connection) {
			$this->error = "no connection";
			return false;
		}
		
		try {  
		   $q = $this->_connection->prepare($sql);
			if($prepare === false || !$prepare) $prepare = array();
			$ret = $q->execute($prepare);
			if(!$ret) return false;
			return $q;
			
		}  
		catch(PDOException $e) {  
			$this->error = $e->getMessage();
			return false;
		}
   }
   
   /*
   $DBH->lastInsertId(); 
   
   $rows_affected = $STH->rowCount();  
   
   PDO::exec() - Execute an SQL statement and return the number of affected rows
PDO::prepare() - Prepares a statement for execution and returns a statement object
PDOStatement::execute() - Executes a prepared statement

*/
   
}
?>
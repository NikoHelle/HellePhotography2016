<?php
//http://net.tutsplus.com/tutorials/php/why-you-should-be-using-phps-pdo-for-database-access/
class DB
{
    // property declaration
	public static $version = "0.1";
	private $_connection = false;
	public $error = false;
	public $fetchMode = false;
	public $lastSQL = false;
    
	

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
		
		$this->fetchMode = PDO::FETCH_OBJ;
		$this->_connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION ); 
	
	  
   }
   
 
   
    public function query($sql,$prepare=false){
	  
	   if(!$this->_connection) {
			$this->error = "no connection";
			return false;
		}
		
		try {  
			$this->error = false;
			$this->lastSQL = $sql;
			if($prepare && is_array($prepare))  $this->lastSQL .= "::prepared params:".implode(",",$prepare);
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
   
    public function select($sql,$fetchMode=false){
		
		$q = $this->query($sql,false);
		if(!$q) return false;
		if(!$fetchMode) $fetchMode = PDO::FETCH_OBJ;
		return $q->fetchAll($fetchMode);
   }
   
   
    public function insert($sql,$prepare=false){
		
		$q = $this->query($sql,$prepare);
		if(!$q) return false;
		return $this->_connection->lastInsertId(); 
		
   }
   
   public function update($sql,$prepare=false){
		$q = $this->query($sql,$prepare);
		if(!$q) return false;
		return $q->rowCount();
		
   }
   
   public function delete($sql,$prepare=false){
		return $this->update($sql,$prepare);
   }
   
   public function quote($var){
	   if(!$this->_connection) throw new ErrorException("no connection");
   		return $this->_connection->quote($var); 
   }
   
    public function getRow(&$resultSet,$fetchMode=false){
	   if(!$resultSet) return false;
	   if(!$fetchMode) $fetchMode = $this->fetchMode;
	   if($fetchMode === PDO::FETCH_OBJ && !is_array($resultSet)) return false;
	   if($fetchMode === PDO::FETCH_OBJ && count($resultSet)<1) return false;
	   return array_shift($resultSet); //??? other fetchmodes?
   }
   
   public function isConnected(){
		return $this->_connection !==false;   
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
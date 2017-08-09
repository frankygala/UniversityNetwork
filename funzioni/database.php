<?php
class DatabaseConnection{
	public $pdo = null;

	public function __construct() {
		$this -> connect();
	}

	protected function connect(){
		try{
			$this -> pdo = new PDO("mysql:host=localhost;dbname=unibo_project","root","root");
		}catch(PDOException $e){
			echo "Connessione fallita";
			exit(); 
		}
	}

	public function getPdo(){
		return $this->pdo;
	}
}
$databaseConnection = new DatabaseConnection();
?>
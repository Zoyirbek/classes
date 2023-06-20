<?php

namespace RzPack;

use PDO;
use PDOException;
use PDOStatement;

final class Database
{
	private $connect;
	private $stmt;
	private static $instance = null;

	private $db_config = [
		'host' => 'localhost',
		'dbname' => 'rzfrm',
		'username' => 'root',
		'password' => '',
		'charset' => 'utf8mb4', // utf8mb4
		'options' => [
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		]
	];

	private function __construct()
	{
		// 
	}

	private function __clone()
	{
		// 
	}

	public function __wakeup()
	{
		// php v8.2 support olny public
	}

	public static function getInstance()
	{
		if(self::$instance == null) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function getConnection(array $db_config = [])
	{
		$db_config = $this->db_config;

		if($this->connect instanceof PDO) {
			return $this;
		}
		$dsn = "mysql:host={$db_config['host']};dbname={$db_config['dbname']};charset={$db_config['charset']}";
		try {
			$this->connect = new PDO($dsn, $db_config['username'], $db_config['password'], $db_config['options']);
			return $this;
		} catch (PDOException $e) {
			Helpers::pr($e->getMessage(), 1);
		}
	}

	public function query(string $sql=null, array $args=[])
	{
	    if(!is_null($sql)) {
	         if (empty($args)) {
	         	try {
					$this->stmt = $this->connect->query($sql);
		            return $this;	
	         	} catch (PDOException $e) {
	        		Helpers::pr([
	        			'msg' => $e->getMessage(),
	        			'file' => $e->getFile(),
	        			'line' => $e->getLine(),
	        			'trace' => $e->getTrace()[1]
	        		], 1);
	         	}
	        } else {
	        	try {
		            $this->stmt = $this->connect->prepare($sql);
		            $this->stmt->execute($args);
		            return $this;        		
	        	} catch (PDOException $e) {
	        		Helpers::pr([
	        			'msg' => $e->getMessage(),
	        			'file' => $e->getFile(),
	        			'line' => $e->getLine(),
	        			'trace' => $e->getTrace()[1]
	        		], 1);
	        	}

	        }     
	    }
	    return null;
	}

	public function findAll()
	{
		return $this->stmt->fetchAll();
	}

	public function find()
	{
		return $this->stmt->fetch();
	}

	public function findOrFail()
	{
		$res = $this->find();
		if(!$res) {
			// change
			die('404');
		}
		return $res;
	}

	public function rowCount()
	{
		return $this->stmt->rowCount() > 0;
	}

	public function insertId()
	{
		return $this->connect->lastInsertId();
	}


	// return PDOStatement
	public function query2(string $sql=null, array $args=[])
	{
	    if(!is_null($sql)) {
	         if (empty($args)) {
	         	try {
	         		return $this->connect->query($sql);
	         	} catch (PDOException $e) {
	        		Helpers::pr([
	        			'msg' => $e->getMessage(),
	        			'file' => $e->getFile(),
	        			'line' => $e->getLine(),
	        			'trace' => $e->getTrace()[1]
	        		], 1);
	         	}
	            
	        } else {
	        	try {
			 		$stmt = $this->connect->prepare($sql);
		            $stmt->execute($args);
		            return $stmt;  		
	        	} catch (PDOException $e) {
	        		Helpers::pr([
	        			'msg' => $e->getMessage(),
	        			'file' => $e->getFile(),
	        			'line' => $e->getLine(),
	        			'trace' => $e->getTrace()[1]
	        		], 1);
	        	}
	           
	        }     
	    }
	    return null;
	    // query("select * from users");
	    // query("select * from users where id = ? and name = ? ", [$id, $name]);
	    // query("select * from users where id = :id and name = :name ", [":id" => $id, ":name" => $name]);
	}
}
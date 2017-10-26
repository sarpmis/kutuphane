<?php
$server = 'localhost';
$username = 'root';
$password = '';
$database = 'test';


try{
	$conn = new PDO("mysql:host=$server; dbname=$database;", $username, $password);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}	catch(PDOException $e) {
	die ("Connection failed: " . $e->getMessage());
}

?>
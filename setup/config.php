<?php
	
	print("held");
	// database baglantisi giris bilgileri
	$db_host = 'localhost';
	$db_username = 'root';
	$db_password = '';
	$db_name = 'test';

	// database baglantisi kurulumu
	$conn = NULL; 
	try{
		$conn = new PDO("mysql:host=$db_host; dbname=$db_name;", $db_username, $db_password);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch(PDOException $e) {
		die ("Database connection failed: " . $e->getMessage());
	}


	$path_sozluk = (__DIR__.'/../tr/sozluk/sozluk_ana.txt');


	$db_table_name_sozluk = 'sozluk';

	$db_table_name_frekanslar = 'frekanslar';
	
	$db_error_log_file = (__DIR__.'database_error_log.log');


?>
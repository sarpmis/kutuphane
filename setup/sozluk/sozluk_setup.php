<?php
	require_once (__DIR__."/../../tr/sozluk/sozluk.php");
	require_once (__DIR__.'/../config.php');

	// @pre 
	//		Bu script kullanilmadan once ../database/db_setup.php
	//		cagirilmalidir

	// Sozluk objesini yarat 
	$sozluk = new Sozluk($db_table_name_sozluk, $conn,
						$db_error_log_file);

	// Sozlugu dosyadan oku ve database'e at
	$sozluk->sozlukOku($path_sozluk);
	$sozluk->wordsToDB();

?>
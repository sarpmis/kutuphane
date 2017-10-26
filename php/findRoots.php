<?php
	require_once (__DIR__.'\..\tr\kok_agaci\kok_agaci_isleyici.php');
	require_once (__DIR__.'\..\tr\database_handler.php'); 

	

	if(isset($_POST['word'])){

		$isleyici = new KokAgaciIsleyici(new DatabaseHandler());

		$word = $_POST['word'];

	  	print json_encode($isleyici->kokBul($word), JSON_UNESCAPED_UNICODE);
	} else {
		echo 0;
	}
?>
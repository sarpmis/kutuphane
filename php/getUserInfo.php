<?php
	session_start();
	require 'database.php';

	$records = $conn->prepare('SELECT id,email,isim FROM kullanicilar WHERE id = :id');
 	$records->bindParam(':id', $_SESSION['user_id']);
 	$records->execute();
 	$results = $records->fetch(PDO::FETCH_ASSOC);

 	print json_encode($results);


//print $_SESSION['user_id'];

?>
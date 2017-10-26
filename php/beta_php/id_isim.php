<?php
	require_once (__DIR__.'/../database.php');

	if(isset($_POST['id'])){
		$id  = $_POST['id'];
		try{
			$sql = "SELECT * FROM ogrenciler WHERE id = $id";
			$records = $conn->prepare($sql);
			$records->execute();
			$results = $records->fetch(PDO::FETCH_ASSOC);
		} catch (PDOException $e){
			print ($sql."<br>".$e->getMessage());
		}
		$ogrenciIsmi = $results['isim'];
		print ($ogrenciIsmi);
	} else {
		print ("<HATA>");
	}

?>
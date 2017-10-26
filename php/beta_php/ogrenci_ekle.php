<?php
	require (__DIR__.'\..\database.php');

	try{
		if(isset($_POST['isim']) && isset($_POST['okul']) && isset($_POST['sinif'])){
			$sql = "INSERT INTO ogrenciler (isim, okul, sinif) VALUES ('".$_POST['isim']."','"
			.$_POST['okul']."','".$_POST['sinif']."')";
			$conn->exec($sql);
			echo 1;
	    } else {
			echo 0;
		}
	} catch(PDOException $e){
	    echo $sql . "<br>" . $e->getMessage();
	}
?>
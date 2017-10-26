<?php
	require (__DIR__.'\..\database.php');

	try{
		if(isset($_POST['id'])){
			$id = $_POST['id'];
			$sql = "DELETE FROM ogrenciler where id = $id";
			$records = $conn->prepare($sql);
			$records->execute();

			$sql = "DELETE FROM sorular where sahip = $id";
			$records = $conn->prepare($sql);
			$records->execute();

			echo 1;
	    } else {
			echo 0;
		}
	} catch(PDOException $e){
	    echo $sql . "<br>" . $e->getMessage();
	}
?>
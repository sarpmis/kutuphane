<?php
	require 'database.php';

	try{
		if(isset($_POST['questionID'])){
			$sql = "UPDATE sorular SET showq=0 WHERE id=".($_POST['questionID']);
			$conn->exec($sql);
			echo 1;
		} else {
			echo $_POST['questionID'];
		}
	} catch(PDOException $e){
		echo $e->getMessage();
	}
?>
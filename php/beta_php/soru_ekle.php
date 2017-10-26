<?php
	require (__DIR__.'\..\database.php');

	try{
		if(isset($_POST['soru']) && isset($_POST['sahip'])){
			$sql = "INSERT INTO sorular (sahip, soru) VALUES (".$_POST['sahip'].",'"
			.$_POST['soru']."')";
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
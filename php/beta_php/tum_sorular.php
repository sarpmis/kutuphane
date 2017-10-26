<?php
	require (__DIR__.'\..\database.php');

	try{
		$array = array();
		$sql = "SELECT * FROM sorular;";
		$records = $conn->prepare($sql);
		$records->execute();
		while($results = $records->fetch(PDO::FETCH_ASSOC)){
			array_push($array, $results);
		}
		print json_encode($array, JSON_UNESCAPED_UNICODE);
	} catch(PDOException $e){
	    echo $sql . "<br>" . $e->getMessage();
	}
?>
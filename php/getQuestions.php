<?php
	require_once 'database.php';

	if(isset($_POST['userid'])){
		$userid = $_POST['userid'];

		$sql = 'SELECT * FROM sorular WHERE owner = :id';
		$records = $conn->prepare($sql);
	  	$records->bindParam(':id', $userid);
	  	$records->execute();

	  	$array = array();

	 	while($results = $records->fetch(PDO::FETCH_ASSOC)){
	 		array_push($array, $results);
	 	}

	  	print json_encode($array);
	} else {
		echo 0;
	}
?>
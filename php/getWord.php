<?php
	require_once 'database.php';

	if(isset($_POST['word'])){
		$word = $_POST['word'];

		$sql = "SELECT * FROM sozluk WHERE kelime = '$word'";
		$records = $conn->prepare($sql);
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
<?php

	require_once (__DIR__.'/../database.php');
	require_once (__DIR__.'/../../tr/query/query_handler.php');

	try{
		$sql = "SELECT * FROM sorular;";
		$records = $conn->prepare($sql);
		$records->execute();

		$sorular = array();

	 	while($results = $records->fetch(PDO::FETCH_ASSOC)){
	 		array_push($sorular, $results);
	 	}
	} catch (PDOException $e){
		print ($sql."<br>".$e->getMessage());
	}


	$optimizerInput = array();
	foreach ($sorular as $soru){
		$key = $soru['id'].",".$soru['sahip'];
		$optimizerInput[$key] = $soru['soru'];
	}


	// file_put_contents('eslestirmeler/input.txt', json_encode($optimizerInput));

	$dbh = new DatabaseHandler();
	$train = new Egitici($dbh, new KokAgaciIsleyici($dbh));
	$qh = new QueryHandler($dbh, $train);

	$optimalMatches = $qh->listOptimizer($optimizerInput);

	file_put_contents("eslestirmeler/temp", json_encode($optimalMatches));
?>
<?php

	function IDtoQuestion($id, $conn) {
		try{
				$sql = "SELECT * FROM sorular WHERE id = $id";
				$records = $conn->prepare($sql);
				$records->execute();
				$results = $records->fetch(PDO::FETCH_ASSOC);
			} catch (PDOException $e){
				die ($sql."<br>".$e->getMessage());
			}
		$queryString = $results['soru'];
		return $queryString;
	}

	function IDtoStudent($id, $conn) {
		try{
				$sql = "SELECT * FROM ogrenciler WHERE id = $id";
				$records = $conn->prepare($sql);
				$records->execute();
				$results = $records->fetch(PDO::FETCH_ASSOC);
			} catch (PDOException $e){
				die ($sql."<br>".$e->getMessage());
			}
		$ogrenciIsmi = $results['isim'];
		return $ogrenciIsmi;
	}
?>
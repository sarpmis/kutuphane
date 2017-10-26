<?php
	require_once (__DIR__.'/conversions.php');
	require_once (__DIR__.'/../database.php');


		//READING FROM FILE TO BE ADDED HERE
		$arr = json_decode(file_get_contents('eslestirmeler/temp'));
		
		$output = array();
		foreach ($arr as $key => $value) {
			$row = array();
			$values = explode(",", $key);

			$question1 = IDtoQuestion($values[0],$conn);
			$student1_id = $values[1];
			$student1_name = IDtoStudent($student1_id,$conn);

			$question2 = IDtoQuestion($values[2],$conn);
			$student2_id = $values[3];
			$student2_name = IDtoStudent($student2_id,$conn);


			array_push($row, $question1, 
							 $question2, 
							 $student1_name, 
							 $student2_name, 
							 $student1_id, 
							 $student2_id);
			array_push($output, $row);
		}
		date_default_timezone_set("Turkey");
		$path = 'eslestirmeler/'.date("d-m-Y")."_".date("h-i").".match";
		file_put_contents($path, json_encode($output, JSON_UNESCAPED_UNICODE));
?>
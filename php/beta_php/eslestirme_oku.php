<?php

	if(isset($_POST['filename'])){
		//READING FROM FILE TO BE ADDED HERE
		$arr = json_decode(file_get_contents('eslestirmeler/'.$_POST["filename"].'.match'));
		print(json_encode($arr, JSON_UNESCAPED_UNICODE));
	}


?>
<?php

	// eslestirmeler directory'sindeki dosyalar
	$files_in_directory = scandir(__DIR__.'/eslestirmeler');


	$bulunanlar = array();

	// tum eslestirmeleri bul
	for($i = 0; $i < sizeof($files_in_directory); $i++){
		$file_name = $files_in_directory[$i];
		if(strpos($file_name, '.match') !== false){
			array_push($bulunanlar, mb_substr($file_name, 0, strpos($file_name, '.match')));
		}
	}

	print(json_encode($bulunanlar));

?>
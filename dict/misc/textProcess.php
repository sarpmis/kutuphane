<?php
	$text = file_get_contents("yardimci.txt");
	$array = explode("\r\n", $text);
	print(json_encode($array));	
	$str = " ";
	foreach($array as $e){
		$str = $str.$e." \r\n ";
	}
	file_put_contents("yardimci2.txt", $str);
	print($str);
?>
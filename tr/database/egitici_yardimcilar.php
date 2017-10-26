<?php

	// NOT: 
	// bu dosyayi egitici.php'de include etmiyoruz cunku bu functionlar
	// zaten kok_agaci'nin require ettigi yardimcilar.php'de var..
	// ama eger ki egitici kok_agaci olmadan kullanilirsa diye burada da dursun
	//
	// removes duplicate values in a sorted array
	function removeDuplicates(&$array) {
		$length = sizeof($array);
		$new = array();
		$previousToken = NULL;
		for($i = 0; $i < $length; $i++){
			if($array[$i] != $previousToken){
				array_push($new, $array[$i]);
			}
			$previousToken = $array[$i];
		}
		$array = $new;
	}

	// function to sort tokens using strcmp
	function sortTokens(&$tokens){
		$func = function($a, $b){
			$cmp = strcmp($a, $b);
			return $cmp;
		};
		usort($tokens, $func);
	}
?>
<?php

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


	function mastarEkle($kelime){
		$sonUnlu = lastCharOf($kelime, UNLULER);
		if (in_array($sonUnlu,KALIN_UNLULER)) {
			return ($kelime."mak");
		} elseif (in_array($sonUnlu,INCE_UNLULER)) {
			return ($kelime."mek");
		}
	}

?>

<?php

	// Byte-order-mark isaretini kaldirir
	function remove_utf8_bom($text){
	    $bom = pack('H*','EFBBBF');
	    $text = preg_replace("/^$bom/", '', $text);
	    return $text;
	}
?>
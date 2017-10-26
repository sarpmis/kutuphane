<?php
	require_once 'database.php';


	function utf8_to_extended_ascii($str, &$map)
	{
	    // find all multibyte characters (cf. utf-8 encoding specs)
	    $matches = array();
	    if (!preg_match_all('/[\xC0-\xF7][\x80-\xBF]+/', $str, $matches))
	        return $str; // plain ascii string
	    
	    // update the encoding map with the characters not already met
	    foreach ($matches[0] as $mbc)
	        if (!isset($map[$mbc]))
	            $map[$mbc] = chr(128 + count($map));
	    
	    // finally remap non-ascii characters
	    return strtr($str, $map);
	}

	function levenshtein_utf8($s1, $s2)
	{
	    $charMap = array();
	    $s1 = utf8_to_extended_ascii($s1, $charMap);
	    $s2 = utf8_to_extended_ascii($s2, $charMap);
	    
	    return levenshtein($s1, $s2);
	}

	$sql = "SELECT * FROM sozluk";
	$records = $conn->prepare($sql);
	$records->execute();

	$dict = array();

 	while($results = $records->fetch(PDO::FETCH_ASSOC)){
		array_push($dict, $results);
	}


	$testKelime = 'zurafa';


	$highestPercentage = 123123123;
	$bestWord = NULL;
	foreach($dict as $kelime){
		$score = levenshtein_utf8($testKelime, $kelime['kelime']);
		if($score <= $highestPercentage) {
			$highestPercentage = $score;
			$bestWord = $kelime['kelime'];
		}
	}

	print($bestWord." ".$highestPercentage);

?>
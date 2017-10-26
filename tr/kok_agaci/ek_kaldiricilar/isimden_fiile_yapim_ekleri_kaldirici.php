<?php
	require_once (__DIR__.'/../../sozluk/alfabe.php');
	require_once (__DIR__.'/../../sozluk/ekler/isimden_fiile_yapim_ekleri.php');
	require_once (__DIR__.'/../../yardimcilar.php');


	function yapim_isimden_fiile_kaldir($kelime){
		//print("kelime = ".$kelime);
		$cevap = array("eksiz"=>NULL, "ek"=>NULL);
		
		foreach(YAPIM_ISIMDEN_FIIL as $ek){
			//eki deniyoruz
			$eksiz = endsWith($kelime,$ek);
			//ek varsa ve govde hece iceriyorsa devam
			if ($eksiz !== -1) {
				if(mb_strlen($eksiz) >= 2 && hasVowel($eksiz)) {
					//eksiz ve eki return'e ekliyoruz
					$cevap['eksiz'] = $eksiz;
					$cevap['ek'] = $ek;
				}
			}
		}
		return $cevap;
	}
?>
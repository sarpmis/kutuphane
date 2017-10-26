<?php

	require_once (__DIR__.'/../../sozluk/alfabe.php');
	require_once (__DIR__.'/../../sozluk/ekler/ek_fiiller.php');
	require_once (__DIR__.'/../../yardimcilar.php');



	function ek_fiil_kaldir($kelime){

		// print("kelime = ".$kelime);
		// kelime bir iyelik ekiyle bitiyor mu?
		$cevap = array("unluBitisEksiz"=>NULL, "unluBitisEk"=> NULL, "unsuzBitisEksiz"=>NULL, "unsuzBitisEk"=> NULL);

		// once kelime unsuzle bitiyormus gibi bakiyoruz
		foreach(EK_FIILLER_UNSUZLE_BITEN as $ekUnsuz){
			$eksiz = endsWith($kelime, $ekUnsuz);
			// eger eksiz 2 harften kucukse veya sesli harfi yoksa cikardigimiz bir ek degildi
			// yani devam etmeye gerek yok
			if($eksiz !== -1) { 
				if(mb_strlen($eksiz) >= 2 && hasVowel($eksiz)) {
					// bu eklerden biriyle bitiyorsa unlu uyumunu kontrol ediyoruz
					// eger ekin son unlusu ve eksiz kelimenin son unlusu birbirine
					// uymuyorsa bu bir ek degildir. 
					if(ekBuyukUnluUyumu($eksiz,$ekUnsuz) || in_array($ekUnsuz, EK_FIIL_UNLU_UYUMU_UYMAYANLAR) ||
						unluUyumuIstisnasi($eksiz)){
						// gercekten unsuzle bitiyor mu
						$eksizinSonHarfi = mb_substr($eksiz,-1);
						if(in_array($eksizinSonHarfi, UNSUZLER)){
							$cevap['unsuzBitisEksiz'] = $eksiz;
							$cevap['unsuzBitisEk'] = $ekUnsuz;
						}
					} else {
						$eksizinSonHarfi = mb_substr($eksiz,-1);
						if(in_array($eksizinSonHarfi, UNSUZLER)){
							$cevap['unsuzBitisEksiz'] = $eksiz;
							$cevap['unsuzBitisEk'] = $ekUnsuz;
						}
					}
				} 
			}
		}

		// simdi eksiz kelime unluyle bitiyormus gibi bakiyoruz
		foreach(EK_FIILLER_UNLUYLE_BITEN as $ekUnlu){
			$eksiz = endsWith($kelime, $ekUnlu);
			// eger eksiz 2 harften kucukse veya sesli harfi yoksa cikardigimiz bir ek degildi
			// yani devam etmeye gerek yok
			if($eksiz !== -1) { 
				if(mb_strlen($eksiz) >= 2 && hasVowel($eksiz)) {
					if(ekBuyukUnluUyumu($eksiz,$ekUnlu) || in_array($ekUnlu, EK_FIIL_UNLU_UYUMU_UYMAYANLAR) ||
						unluUyumuIstisnasi($eksiz)){
						// eger unlu uyumuna uyuyorsa ve eksiz gercekten unluyle bitiyorsa
						// eksizi cevaba ekleyebiliriz
						$eksizinSonHarfi = mb_substr($eksiz, -1);
						if(in_array($eksizinSonHarfi, UNLULER)) {
							$cevap['unluBitisEksiz'] = $eksiz;
							$cevap['unluBitisEk'] = $ekUnlu;
						}
					}	
				} 
			}
		} 
		return $cevap;	
	}

	// print(json_encode(ek_fiil("koyuydu"), JSON_UNESCAPED_UNICODE)."<br>");
	// print(json_encode(ek_fiil("yapmışlardır"), JSON_UNESCAPED_UNICODE)."<br>");
	// print(json_encode(ek_fiil("giderken"), JSON_UNESCAPED_UNICODE)."<br>");
	// print(json_encode(ek_fiil("sokarlarken"), JSON_UNESCAPED_UNICODE)."<br>");
	// print(json_encode(ek_fiil("sarıdır"), JSON_UNESCAPED_UNICODE)."<br>");
	// print(json_encode(ek_fiil("kapatıyorlardı"), JSON_UNESCAPED_UNICODE)."<br>");
	// print(json_encode(ek_fiil("geldiydi"), JSON_UNESCAPED_UNICODE)."<br>");
	// print(json_encode(ek_fiil_kaldir("doluyken"), JSON_UNESCAPED_UNICODE)."<br>");
?>
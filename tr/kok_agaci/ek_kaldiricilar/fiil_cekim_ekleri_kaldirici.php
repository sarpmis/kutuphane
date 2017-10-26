<?php
	require_once (__DIR__.'/../../sozluk/alfabe.php');
	require_once (__DIR__.'/../../sozluk/ekler/fiil_cekim_ekleri.php');
	require_once (__DIR__.'/../../yardimcilar.php');

	/*
	 * 
	 *
	 * @return - -dunuz ve -unuz ekleri ve varyasyonlari -unuz ekini barindirdigi icin 
	 * 			unsuzBitisEksiz'in 2 kopyasi var. Sadece bu durumda ikincisi de dolu olacak
	 *			ama ele alinmasi gerekli
	 */
	function fiil_cekim_kaldir($kelime){

	// $FIIL_CEKIM_UNLUYLE_BITEN_KOK = array_merge_recursive(GOR_GEC_ZAM, OGR_GEC_ZAM, 
	// 	GENIS_ZAM_UNLUYLE_BITEN_KOK, SIMDIKI_ZAM_UNLUYLE_BITEN_KOK, GELECEK_ZAM_UNLUYLE_BITEN_KOK, 
	// 	GEREKLILIK_KIP, DILEK_SART_KIP, ISTEK_KIP_UNLUYLE_BITEN, EMIR_KIPI_UNLUYLE_BITEN, OLUMSUZLUK);

	// $FIIL_CEKIM_UNSUZLE_BITEN_KOK = array_merge_recursive(GOR_GEC_ZAM, OGR_GEC_ZAM, 
	// 	GENIS_ZAM_UNSUZLE_BITEN_KOK, SIMDIKI_ZAM_UNSUZLE_BITEN_KOK, GELECEK_ZAM_UNSUZLE_BITEN_KOK, 
	// 	GEREKLILIK_KIP, DILEK_SART_KIP, ISTEK_KIP_UNSUZLE_BITEN, EMIR_KIPI_UNSUZLE_BITEN, OLUMSUZLUK);

		// print("kelime = ".$kelime);
		// kelime bir fiil cekim ekiyle bitiyor mu?
		$cevap = array("unluBitisEksiz"=>NULL, "unluBitisEk"=> NULL, "unsuzBitisEksiz"=>NULL, "unsuzBitisEk"=> NULL, 
			"unluDaralmasi"=>NULL, "yumusama"=>NULL, "unsuzBitisEksiz2"=>NULL, "unsuzBitisEk2"=> NULL, 
			"unluDaralmasi2"=>NULL, "yumusama2"=>NULL);

		//print("<br>"."<br>"."<br>"."<br>".json_encode($FIIL_CEKIM_UNSUZLE_BITEN_KOK, JSON_UNESCAPED_UNICODE)."<br>");
		// once kelime unsuzle bitiyormus gibi bakiyoruz
		foreach(FIIL_CEKIM_UNSUZLE_BITEN_KOK as $ekUnsuz){
			$eksiz = endsWith($kelime, $ekUnsuz);
			// eger eksiz 2 harften kucukse veya sesli harfi yoksa cikardigimiz bir ek degildi
			// yani devam etmeye gerek yok
			if($eksiz !== -1) { 
				if(mb_strlen($eksiz) == 1){
					$unluDaralmasi = unluDaralmasi($eksiz, $ekUnsuz);
					if($unluDaralmasi !== -1){
						if($cevap['unsuzBitisEksiz'] != NULL) {
							$cevap['unluDaralmasi2'] = $unluDaralmasi;
							$cevap['unsuzBitisEksiz2'] = $eksiz;
							$cevap['unsuzBitisEk2'] = $ekUnsuz;
						} else {
							$cevap['unluDaralmasi'] = $unluDaralmasi;
							$cevap['unsuzBitisEksiz'] = $eksiz;
							$cevap['unsuzBitisEk'] = $ekUnsuz;
						}
					}
				} else if(mb_strlen($eksiz) >= 2 && hasVowel($eksiz)) {
					// bu eklerden biriyle bitiyorsa unlu uyumunu kontrol ediyoruz
					// eger ekin son unlusu ve eksiz kelimenin son unlusu birbirine
					// uymuyorsa bu bir ek degildir. 
					if(ekBuyukUnluUyumu($eksiz,$ekUnsuz) || endsWith($ekUnsuz, "yor")
						|| unluUyumuIstisnasi($eksiz)){
						// gercekten unsuzle bitiyor mu
						$eksizinSonHarfi = mb_substr($eksiz,-1);
						if(in_array($eksizinSonHarfi, UNSUZLER)){
							if($cevap['unsuzBitisEksiz'] != NULL) {
								$cevap['unsuzBitisEksiz2'] = $eksiz;
								$cevap['unsuzBitisEk2'] = $ekUnsuz;
							} else {
								$cevap['unsuzBitisEksiz'] = $eksiz;
								$cevap['unsuzBitisEk'] = $ekUnsuz;
							}
						}
						$unluDaralmasi = unluDaralmasi($eksiz, $ekUnsuz);
						if($unluDaralmasi !== -1){
							if($cevap['unluDaralmasi'] != NULL) {
								$cevap['unluDaralmasi2'] = $unluDaralmasi;
							} else {
								$cevap['unluDaralmasi'] = $unluDaralmasi;
							}
						}
						$yumusama = yumusamaCheck($eksiz);
						if($yumusama !== -1) {
							if($cevap['yumusama'] != NULL) {
								$cevap['yumusama2'] = $yumusama;
							} else {
								$cevap['yumusama'] = $yumusama;
							}
						}
					} 
				} 
			}
		}

		// simdi eksiz kelime unluyle bitiyormus gibi bakiyoruz
		foreach(FIIL_CEKIM_UNLUYLE_BITEN_KOK as $ekUnlu){
			$eksiz = endsWith($kelime, $ekUnlu);
			// eger eksiz 2 harften kucukse veya sesli harfi yoksa cikardigimiz bir ek degildi
			// yani devam etmeye gerek yok
			if($eksiz !== -1) { 
				if(mb_strlen($eksiz) >= 2 && hasVowel($eksiz)) {
					if(ekBuyukUnluUyumu($eksiz,$ekUnlu) || endsWith($ekUnsuz, "yor") ||
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

	// print(json_encode(fiil_cekim_kaldir("kesiyor"), JSON_UNESCAPED_UNICODE)."<br>");
	// print(json_encode(fiil_cekim_kaldir("gelecek"), JSON_UNESCAPED_UNICODE)."<br>");
	// print(json_encode(fiil_cekim_kaldir("gider"), JSON_UNESCAPED_UNICODE)."<br>");
	// print(json_encode(fiil_cekim_kaldir("doymuyor"), JSON_UNESCAPED_UNICODE)."<br>");	
	// print(json_encode(fiil_cekim_kaldir("geziyordunuz"), JSON_UNESCAPED_UNICODE)."<br>");
	// print(json_encode(fiil_cekim_kaldir("yonttunuz"), JSON_UNESCAPED_UNICODE)."<br>");
	// print(json_encode(fiil_cekim_kaldir("sevmiyor"), JSON_UNESCAPED_UNICODE)."<br>");
	// print(json_encode(fiil_cekim_kaldir("sevme"), JSON_UNESCAPED_UNICODE)."<br>");
	// print(json_encode(fiil_cekim_kaldir("arama"), JSON_UNESCAPED_UNICODE)."<br>");
	// print(json_encode(fiil_cekim_kaldir("yiyor"), JSON_UNESCAPED_UNICODE)."<br>");
	// print(json_encode(fiil_cekim_kaldir("koyunuz"), JSON_UNESCAPED_UNICODE)."<br>");
	// print(json_encode(fiil_cekim_kaldir("dikkatiyorum"), JSON_UNESCAPED_UNICODE)."<br>");
?>
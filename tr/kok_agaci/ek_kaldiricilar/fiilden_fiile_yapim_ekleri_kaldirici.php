<?php
	require_once (__DIR__.'/../../sozluk/alfabe.php');
	require_once (__DIR__.'/../../sozluk/ekler/fiilden_fiile_yapim_ekleri.php');
	require_once (__DIR__.'/../../yardimcilar.php');


	function yapim_fiilden_fiile_kaldir($kelime){

		// print("kelime = ".$kelime);
		// kelime bir iyelik ekiyle bitiyor mu?
		$cevap = array("unluBitisEksiz"=>NULL, "unluBitisEk"=> NULL, "unsuzBitisEksiz"=>NULL, "unsuzBitisEk"=> NULL, "yumusama"=>NULL,
			"unluDaralmasi"=>NULL);

		// once kelime unsuzle bitiyormus gibi bakiyoruz
		foreach(YAPIM_FIILDEN_FIIL_UNSUZLE_BITEN as $ekUnsuz){
			$eksiz = endsWith($kelime, $ekUnsuz);
			// eger eksiz 2 harften kucukse veya sesli harfi yoksa cikardigimiz bir ek degildi
			// yani devam etmeye gerek yok
			if($eksiz !== -1) { 
				$unluDaralmasi = unluDaralmasi($eksiz, $ekUnsuz);
				if($unluDaralmasi !== -1){
					$cevap['unluDaralmasi'] = $unluDaralmasi;
					$cevap['unsuzBitisEksiz'] = $eksiz;
					$cevap['unsuzBitisEk'] = $ekUnsuz;
				}

				if(mb_strlen($eksiz) >= 2 && hasVowel($eksiz)) {
					// bu eklerden biriyle bitiyorsa unlu uyumunu kontrol ediyoruz
					// eger ekin son unlusu ve eksiz kelimenin son unlusu birbirine
					// uymuyorsa bu bir ek degildir. 
					if(ekBuyukUnluUyumu($eksiz,$ekUnsuz)){
						// gercekten unsuzle bitiyor mu
						$eksizinSonHarfi = mb_substr($eksiz,-1);
						if(in_array($eksizinSonHarfi, UNSUZLER)){
							$cevap['unsuzBitisEksiz'] = $eksiz;
							$cevap['unsuzBitisEk'] = $ekUnsuz;
							// yumusama
							$yumusama = yumusamaCheck($eksiz);
							if($yumusama != -1) {
								$cevap['yumusama'] = $yumusama;
							}
						} 
					}
				} 
			}
		}

		// simdi eksiz kelime unluyle bitiyormus gibi bakiyoruz
		foreach(YAPIM_FIILDEN_FIIL_UNLUYLE_BITEN as $ekUnlu){
			$eksiz = endsWith($kelime, $ekUnlu);
			// eger eksiz 2 harften kucukse veya sesli harfi yoksa cikardigimiz bir ek degildi
			// yani devam etmeye gerek yok
			if($eksiz !== -1) { 
				if(mb_strlen($eksiz) >= 2 && hasVowel($eksiz)) {
					if(ekBuyukUnluUyumu($eksiz,$ekUnlu)){
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
?>
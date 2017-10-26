<?php
	// Isimden fiil yapan bu yapim ekleri 
	// 	» -laş: şakalaş-, dertleş-, çocuklaş–
	// 	» -se: önemse-, garipse–

	//  -siz: evsiz, huysuz, akılsız, işsiz, parasız
	// » -lik: zeytinlik, şekerlik, suluk, insanlık, kardeşlik
	// » -li: köylü, nişanlı, renkli, mavili, bilgili, görgülü
	// » -msı: acımsı, ekşimsi
	// » -tı: horultu cıvıltı

	require_once (__DIR__.'/../../sozluk/alfabe.php');
	require_once (__DIR__.'/../../sozluk/ekler/isimden_isime_yapim_ekleri.php');
	require_once (__DIR__.'/../../yardimcilar.php');


	function yapim_isimden_isime_kaldir($kelime){

		// print("kelime = ".$kelime);
		// kelime bir iyelik ekiyle bitiyor mu?
		$cevap = array("unluBitisEksiz"=>NULL, "unluBitisEk"=> NULL, "unsuzBitisEksiz"=>NULL, "unsuzBitisEk"=> NULL, "yumusama"=>NULL);

		// once kelime unsuzle bitiyormus gibi bakiyoruz
		foreach(YAPIM_ISIMDEN_ISIM_UNSUZLE_BITEN as $ekUnsuz){
			$eksiz = endsWith($kelime, $ekUnsuz);
			// eger eksiz 2 harften kucukse veya sesli harfi yoksa cikardigimiz bir ek degildi
			// yani devam etmeye gerek yok
			if($eksiz !== -1) { 
				if(mb_strlen($eksiz) >= 2 && hasVowel($eksiz)) {
					// bu eklerden biriyle bitiyorsa unlu uyumunu kontrol ediyoruz
					// eger ekin son unlusu ve eksiz kelimenin son unlusu birbirine
					// uymuyorsa bu bir ek degildir. 
					if(ekBuyukUnluUyumu($eksiz,$ekUnsuz) ||
						unluUyumuIstisnasi($eksiz)){
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
		foreach(YAPIM_ISIMDEN_ISIM_UNLUYLE_BITEN as $ekUnlu){
			$eksiz = endsWith($kelime, $ekUnlu);
			// eger eksiz 2 harften kucukse veya sesli harfi yoksa cikardigimiz bir ek degildi
			// yani devam etmeye gerek yok
			if($eksiz !== -1) { 
				if(mb_strlen($eksiz) >= 2 && hasVowel($eksiz)) {
					if(ekBuyukUnluUyumu($eksiz,$ekUnlu)||
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

	// print(json_encode(yapim_isimden_isim("şakalaş"), JSON_UNESCAPED_UNICODE)."<br>");
	// print(json_encode(yapim_isimden_isim("dertleş"), JSON_UNESCAPED_UNICODE)."<br>");
	// print(json_encode(yapim_isimden_isim("evsiz"), JSON_UNESCAPED_UNICODE)."<br>");
	// print(json_encode(yapim_isimden_isim("akılsız"), JSON_UNESCAPED_UNICODE)."<br>");
	// print(json_encode(yapim_isimden_isim("şekerlik"), JSON_UNESCAPED_UNICODE)."<br>");
	// print(json_encode(yapim_isimden_isim("suluk"), JSON_UNESCAPED_UNICODE)."<br>");
	// print(json_encode(yapim_isimden_isim("nişanlı"), JSON_UNESCAPED_UNICODE)."<br>");
	// print(json_encode(yapim_isimden_isim("görgülü"), JSON_UNESCAPED_UNICODE)."<br>");
	// print(json_encode(yapim_isimden_isim("ekşimsi"), JSON_UNESCAPED_UNICODE)."<br>");
	// print(json_encode(yapim_isimden_isim("kavağımsı"), JSON_UNESCAPED_UNICODE)."<br>");
	// print(json_encode(yapim_isimden_isim("kolonumsu"), JSON_UNESCAPED_UNICODE)."<br>");
?>
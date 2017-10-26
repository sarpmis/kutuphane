<?php
	// Fiilden isim yapan yapim ekleri 

	// gi: sev—gi, çal—gı, as—kı, sil-gi, iç-ki (?)
	// ir: gel—ir, ok—ur  (Kip ekleri yaparken bunu yapabiliriz)
	// er: gid-er, yaz-ar (Kip ekleri yaparken bunu yapabiliriz)
	// ici: yap—ıcı, gör—ücü, al—ıcı, sat—ıcı
	// ik: kes—ik, aç—ık, göç—ük
	// im: say—ım, seç—im, öl—üm, ölç—üm
	// (Bu ikisini ayri mi yapalim?)  ti: belir—ti, kızar—tı ////// inti: kes—inti, çık—ıntı, dök—üntü
	// iş: otur—uş, yürü—y-üş
	// ma: konuş-ma, dondur-ma
	// mek: ye-mek, çak-mak, git-mek, duy-mak


	require_once (__DIR__.'/../../sozluk/alfabe.php');
	require_once (__DIR__.'/../../sozluk/ekler/fiilden_isime_yapim_ekleri.php');
	require_once (__DIR__.'/../../yardimcilar.php');

	function yapim_fiilden_isime_kaldir($kelime){

		// print("kelime = ".$kelime);
		$cevap = array("unluBitisEksiz"=>NULL, "unluBitisEk"=> NULL, "unsuzBitisEksiz"=>NULL, "unsuzBitisEk"=> NULL, "yumusama"=>NULL);

		// once kelime unsuzle bitiyormus gibi bakiyoruz
		foreach(YAPIM_FIILDEN_ISIM_UNSUZLE_BITEN as $ekUnsuz){
			$eksiz = endsWith($kelime, $ekUnsuz);
			// eger eksiz 2 harften kucukse veya sesli harfi yoksa cikardigimiz bir ek degildi
			// yani devam etmeye gerek yok
			if($eksiz !== -1) { 
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
		foreach(YAPIM_FIILDEN_ISIM_UNLUYLE_BITEN as $ekUnlu){
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

	// print(json_encode(yapim_fiilden_isim("gezinti"), JSON_UNESCAPED_UNICODE)."<br>");
	// print(json_encode(yapim_fiilden_isim("koruyucu"), JSON_UNESCAPED_UNICODE)."<br>");
	// print(json_encode(yapim_fiilden_isim("açık"), JSON_UNESCAPED_UNICODE)."<br>");
	// print(json_encode(yapim_fiilden_isim("kapanık"), JSON_UNESCAPED_UNICODE)."<br>");
	// print(json_encode(yapim_fiilden_isim("yönetim"), JSON_UNESCAPED_UNICODE)."<br>");
	// print(json_encode(yapim_fiilden_isim("korunum"), JSON_UNESCAPED_UNICODE)."<br>");
	// print(json_encode(yapim_fiilden_isim("kapayış"), JSON_UNESCAPED_UNICODE)."<br>");
	// print(json_encode(yapim_fiilden_isim("kapanış"), JSON_UNESCAPED_UNICODE)."<br>");
	
?>
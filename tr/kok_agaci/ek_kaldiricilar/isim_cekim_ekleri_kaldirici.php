<?php
	require_once (__DIR__.'/../../sozluk/alfabe.php');
	require_once (__DIR__.'/../../sozluk/ekler/isim_cekim_ekleri.php');
	require_once (__DIR__.'/../../yardimcilar.php');


	/* kelimenin sonunda iyelik ve ilgi (tamlama) eklerini bulur
	 *
	 * @return - bir assoaciative array dondurur
	 * 		     unluBitisEksiz -> kok unluyle bitiyorsa ek cikarilinca kalan, kok
	 *			 unluBitisEk -> kok unluyle bitiyorsa kelimeden cikarilan ek
	 * 			 unsuzBitisEksiz -> kok unsuzle bitiyorsa ek cikarilinca kalan, kok
	 *			 unsuzBitisEk -> kok unsuzle bitiyorsa kelimeden cikarilan ek
	 * 			 yumusama -> eger kok unsuzle bitiyorsa yumusama olabilir. yumusamanin
	 *						 tersine cevrilmis / "sertlestirilmis" kok
	 *			 unluDusmesi -> unlu dusmesi ihtimali varsa unlu dusmesinin tersine cevrilmis hali
	 *				
	 *
	 * @dikkat - unlu dusmesi olabilir!
	 * @dikkat - unsuz yumusamasi olabilir!
	 * @dikkat - durum ekiyle karisabilir!
	*/
	function tamlamaCheck($kelime){
		print("kelime = ".$kelime);
		// kelime bir iyelik ekiyle bitiyor mu?
		$cevap = array("unluBitisEksiz"=>NULL, "unluBitisEk"=> NULL, "unsuzBitisEksiz"=>NULL, 
			"unsuzBitisEk"=> NULL, "yumusama"=>NULL, "unluDusmesi"=>NULL);

		// once kelime unsuzle bitiyormus gibi bakiyoruz
		foreach(TAMLAMA_UNSUZLE_BITEN_KOK as $ekUnsuz){
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
							// unsuzle bitiyorsa yumusama ve unlu dusmesini de kontrol etmeliyiz
							// unlu dusmesi
							$unluDusmesi = iyelikUnluDusmesiCheck($eksiz, $ekUnsuz);
							if($unluDusmesi != -1){
								$cevap['unluDusmesi'] = $unluDusmesi;
							}
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
		foreach(TAMLAMA_UNLUYLE_BITEN_KOK as $ekUnlu){
			$eksiz = endsWith($kelime, $ekUnlu);
			// eger eksiz 2 harften kucukse veya sesli harfi yoksa cikardigimiz bir ek degildi
			// yani devam etmeye gerek yok
			if($eksiz !== -1) { 
				if(($eksiz == "o") || (mb_strlen($eksiz) >= 2 && hasVowel($eksiz))) {
					if(ekBuyukUnluUyumu($eksiz,$ekUnlu) ||
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

	
	/* kelimenin sonunda hal eklerini bulur
	 *
	 * @return - bir associative array dondurur
	 * 		     unluBitisEksiz -> kok unluyle bitiyorsa ek cikarilinca kalan, kok
	 *			 unluBitisEk -> kok unluyle bitiyorsa kelimeden cikarilan ek
	 * 			 unsuzBitisEksiz -> kok unsuzle bitiyorsa ek cikarilinca kalan, kok
	 *			 unsuzBitisEk -> kok unsuzle bitiyorsa kelimeden cikarilan ek
	 * 			 yumusama -> eger kok unsuzle bitiyorsa yumusama olabilir. yumusamanin
	 *						 tersine cevrilmis / "sertlestirilmis" kok
	 *			 unluDusmesi -> unlu dusmesi ihtimali varsa unlu dusmesinin tersine cevrilmis hali
	 *				
	 *
	 * @dikkat - unlu dusmesi olabilir!
	 * @dikkat - unsuz yumusamasi olabilir!
	 */
	function halCheck($kelime){

		print("kelime = ".$kelime);
		// kelime bir iyelik ekiyle bitiyor mu?
		$cevap = array("unluBitisEksiz"=>NULL, "unluBitisEk"=> NULL, "unsuzBitisEksiz"=>NULL, "unsuzBitisEk"=> NULL, "yumusama"=>NULL, "unluDusmesi"=>NULL);

		// once kelime unsuzle bitiyormus gibi bakiyoruz
		foreach(HAL_UNSUZLE_BITEN_KOK as $ekUnsuz){
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
							// unsuzle bitiyorsa yumusama ve unlu dusmesini de kontrol etmeliyiz
							// unlu dusmesi
							$unluDusmesi = iyelikUnluDusmesiCheck($eksiz, $ekUnsuz);
							if($unluDusmesi != -1){
								$cevap['unluDusmesi'] = $unluDusmesi;
							}
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
		foreach(HAL_UNLUYLE_BITEN_KOK as $ekUnlu){
			$eksiz = endsWith($kelime, $ekUnlu);
			// eger eksiz 2 harften kucukse veya sesli harfi yoksa cikardigimiz bir ek degildi
			// yani devam etmeye gerek yok
			if($eksiz !== -1) { 
				if(($eksiz == "o") || (mb_strlen($eksiz) >= 2 && hasVowel($eksiz))) {
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

	/* 
	 * Kelimenin sonundaki cogul eklerini bulur.
	 * 
	 * @return - associative array w/:
	 * 		     bitisEksiz -> ek cikarilinca kalan govde
	 *			 bitisEk 	-> govdeden cikarilan ek
	 *
	 * Ses olayi yok.
	 *
	 */
	function cogulCheck($kelime){
		print("kelime = ".$kelime);
		$cevap = array("bitisEksiz"=>NULL, "bitisEk"=>NULL);
		
		foreach(COGUL_EKLERI as $ek){
			//eki deniyoruz
			$eksiz = endsWith($kelime,$ek);
			//ek varsa ve govde hece iceriyorsa devam
			if ($eksiz !== -1) {
				if(mb_strlen($eksiz) >= 2 && hasVowel($eksiz)) {
					//unlu uyumu saglanmissa eksiz ve eki return'e ekliyoruz
					if(ekBuyukUnluUyumu($eksiz,$ek)){
						$cevap['bitisEksiz'] = $eksiz;
						$cevap['bitisEk'] = $ek;
					}
				}
			}
		}
		return $cevap;
	}

	/* Kelimenin sonunda eklesmis "ile" baglacini bulur.
	 *
	 * @return - bir associative array dondurur
	 * 		     unluBitisEksiz -> kok unluyle bitiyorsa ek cikarilinca kalan, kok
	 *			 unluBitisEk -> kok unluyle bitiyorsa kelimeden cikarilan ek
	 * 			 unsuzBitisEksiz -> kok unsuzle bitiyorsa ek cikarilinca kalan, kok
	 *			 unsuzBitisEk -> kok unsuzle bitiyorsa kelimeden cikarilan ek
	 *				
	 * Ses olayi yok.
	 */
	// function ileCheck($kelime){
	// 	print("kelime = ".$kelime);
	// 	// kelime bir bitismis baglacla bitiyor mu?
	// 	$cevap = array("unluBitisEksiz"=>NULL, "unluBitisEk"=> NULL, "unsuzBitisEksiz"=>NULL, "unsuzBitisEk"=> NULL, "yumusama"=>NULL, "unluDusmesi"=>NULL);
	// 	foreach(LE_UNSUZLE_BITEN_KOK as $ekUnsuz){
	// 		$eksiz = endsWith($kelime, $ekUnsuz);
	// 		// eger eksiz 2 harften kucukse veya sesli harfi yoksa cikardigimiz bir ek degildi
	// 		// yani devam etmeye gerek yok
	// 		if($eksiz !== -1) { 
	// 			if(mb_strlen($eksiz) >= 2 && hasVowel($eksiz)) {
	// 				// bu eklerden biriyle bitiyorsa unlu uyumunu kontrol ediyoruz
	// 				// eger ekin son unlusu ve eksiz kelimenin son unlusu birbirine
	// 				// uymuyorsa bu bir ek degildir. 
	// 				if(ekBuyukUnluUyumu($eksiz,$ekUnsuz)){
	// 					// gercekten unsuzle bitiyor mu
	// 					$eksizinSonHarfi = mb_substr($eksiz,-1);
	// 					if(in_array($eksizinSonHarfi, UNSUZLER)){
	// 						$cevap['unsuzBitisEksiz'] = $eksiz;
	// 						$cevap['unsuzBitisEk'] = $ekUnsuz;
	// 					} 
	// 				}
	// 			} 
	// 		}
	// 	}

		// simdi eksiz kelime unluyle bitiyormus gibi bakiyoruz
	// 	foreach(LE_UNLUYLE_BITEN_KOK as $ekUnlu){
	// 		$eksiz = endsWith($kelime, $ekUnlu);
	// 		// eger eksiz 2 harften kucukse veya sesli harfi yoksa cikardigimiz bir ek degildi
	// 		// yani devam etmeye gerek yok
	// 		if($eksiz !== -1) { 
	// 			if(($eksiz == "o") || (mb_strlen($eksiz) >= 2 && hasVowel($eksiz))) {
	// 				if(ekBuyukUnluUyumu($eksiz,$ekUnlu)){
	// 					// eger unlu uyumuna uyuyorsa ve eksiz gercekten unluyle bitiyorsa
	// 					// eksizi cevaba ekleyebiliriz
	// 					$eksizinSonHarfi = mb_substr($eksiz, -1);
	// 					if(in_array($eksizinSonHarfi, UNLULER)) {
	// 						$cevap['unluBitisEksiz'] = $eksiz;
	// 						$cevap['unluBitisEk'] = $ekUnlu;
	// 					}
	// 				}
	// 			} 
	// 		}
	// 	} 
	// 	return $cevap;
	// }

	/* 
	 * Kelimenin sonundaki "-ki" edatini (?) bulur.
	 * 
	 * @return - associative array w/:
	 * 		     bitisEksiz -> ek cikarilinca kalan govde
	 *			 bitisEk 	-> govdeden cikarilan ek
	 *
	 * Ses olayi yok.
	 *
	 */
	function kiCheck($kelime){
		print("kelime = ".$kelime);
		$cevap = array("bitisEksiz"=>NULL, "bitisEk"=>NULL);
		
		foreach(ISIM_KI_EKLERI as $ek){
			//eki deniyoruz
			$eksiz = endsWith($kelime,$ek);
			//ek varsa ve govde hece iceriyorsa devam
			if ($eksiz !== -1) {
				if(mb_strlen($eksiz) >= 2 && hasVowel($eksiz)) {
					//eksiz ve eki return'e ekliyoruz
					$cevap['bitisEksiz'] = $eksiz;
					$cevap['bitisEk'] = $ek;
				}
			}
		}
		return $cevap;
	}

	function isim_cekim_kaldir($kelime){

		$cevap = array("unluBitisEksiz"=>NULL, "unluBitisEk"=> NULL, "unsuzBitisEksiz"=>NULL, 
			"unsuzBitisEk"=> NULL, "yumusama"=>NULL, "unluDusmesi"=>NULL);

		// once kelime unsuzle bitiyormus gibi bakiyoruz
		foreach(ISIM_CEKIM_EKLERI_UNSUZLE_BITEN as $ekUnsuz){
			$eksiz = endsWith($kelime, $ekUnsuz);
			// eger eksiz 2 harften kucukse veya sesli harfi yoksa cikardigimiz bir ek degildi
			// yani devam etmeye gerek yok
			if($eksiz !== -1) { 
				if(mb_strlen($eksiz) >= 2 && hasVowel($eksiz)) {
					// gercekten unsuzle bitiyor mu
					$eksizinSonHarfi = mb_substr($eksiz,-1);
					if(in_array($eksizinSonHarfi, UNSUZLER)){
						$unluDusmesi = iyelikUnluDusmesiCheck($eksiz, $ekUnsuz);
						if($unluDusmesi != -1){
							$cevap['unluDusmesi'] = $unluDusmesi;
						}
						if(ekBuyukUnluUyumu($eksiz,$ekUnsuz) || $ekUnsuz === "ki" ||
							unluUyumuIstisnasi($eksiz)){
							$cevap['unsuzBitisEksiz'] = $eksiz;
							$cevap['unsuzBitisEk'] = $ekUnsuz;
							// unsuzle bitiyorsa yumusama ve unlu dusmesini de kontrol etmeliyiz
							// unlu dusmesi

							// yumusama
							$yumusama = yumusamaCheck($eksiz);
							if($yumusama !== -1) {
								$cevap['yumusama'] = $yumusama;
							}
						} 
					} 	
				} 
			}
		}

		// simdi eksiz kelime unluyle bitiyormus gibi bakiyoruz
		foreach(ISIM_CEKIM_EKLERI_UNLUYLE_BITEN as $ekUnlu){
			$eksiz = endsWith($kelime, $ekUnlu);
			// eger eksiz 2 harften kucukse veya sesli harfi yoksa cikardigimiz bir ek degildi
			// yani devam etmeye gerek yok
			if($eksiz !== -1) { 
				if(($eksiz == "o") || (mb_strlen($eksiz) >= 2 && hasVowel($eksiz))) {
					if(ekBuyukUnluUyumu($eksiz,$ekUnlu) || $ekUnlu === "ki" ||
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


	// print(json_encode(cogulCheck("anneler"),JSON_UNESCAPED_UNICODE)."<br><br>");
	// print(json_encode(cogulCheck("babaler"),JSON_UNESCAPED_UNICODE)."<br><br>");
	// print(json_encode(cogulCheck("babalar"),JSON_UNESCAPED_UNICODE)."<br><br>");


	// print(json_encode(tamlamaCheck("babam"),JSON_UNESCAPED_UNICODE)."<br><br>");
	// print(json_encode(tamlamaCheck("kayığı"),JSON_UNESCAPED_UNICODE)."<br><br>");
	// print(json_encode(tamlamaCheck("annesi"),JSON_UNESCAPED_UNICODE)."<br><br>");
	// print(json_encode(tamlamaCheck("elleri"),JSON_UNESCAPED_UNICODE)."<br><br>");
	// print(json_encode(tamlamaCheck("arabanız"),JSON_UNESCAPED_UNICODE)."<br><br>");
	// print(json_encode(tamlamaCheck("troleybüsçüleştiremediklerimin"),JSON_UNESCAPED_UNICODE)."<br><br>");
	// print(json_encode(tamlamaCheck("kedim"),JSON_UNESCAPED_UNICODE)."<br><br>");
	// print(json_encode(tamlamaCheck("beyni"),JSON_UNESCAPED_UNICODE)."<br><br>");

	// print(json_encode(halCheck("dolapta"),JSON_UNESCAPED_UNICODE)."<br><br>");
	// print(json_encode(halCheck("tavaya"),JSON_UNESCAPED_UNICODE)."<br><br>");
	// print(json_encode(halCheck("beyne"),JSON_UNESCAPED_UNICODE)."<br><br>");

	// print(json_encode(ileCheck("kaşıkla"),JSON_UNESCAPED_UNICODE)."<br><br>");
	// print(json_encode(ileCheck("ebeyle"),JSON_UNESCAPED_UNICODE)."<br><br>");
	// print(json_encode(ileCheck("suyla"),JSON_UNESCAPED_UNICODE)."<br><br>");
	
	// print(json_encode(kiCheck("oradaki"),JSON_UNESCAPED_UNICODE)."<br><br>");
	// print(json_encode(kiCheck("çünkü"),JSON_UNESCAPED_UNICODE)."<br><br>");
	// print(json_encode(kiCheck("dünkü"),JSON_UNESCAPED_UNICODE)."<br><br>");
	// print(json_encode(kiCheck("yarınki"),JSON_UNESCAPED_UNICODE)."<br><br>");

	// print(json_encode(tamlamaCheck("onun"),JSON_UNESCAPED_UNICODE)."<br><br>");
	// print(json_encode(tamlamaCheck("suyun"),JSON_UNESCAPED_UNICODE)."<br><br>");
	// print(json_encode(tamlamaCheck("ebenin"),JSON_UNESCAPED_UNICODE)."<br><br>");
	// print(json_encode(tamlamaCheck("beynin"),JSON_UNESCAPED_UNICODE)."<br><br>");
	// print(json_encode(tamlamaCheck("balığın"),JSON_UNESCAPED_UNICODE)."<br><br>");


	// print(json_encode(isim_cekim_kaldir("hacmi"),JSON_UNESCAPED_UNICODE)."<br><br>");

?>
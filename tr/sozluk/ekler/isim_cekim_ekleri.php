<?php
		// unsuz biten kelimenin alabilecegi tum iyelik ekleri
		const TAMLAMA_UNSUZLE_BITEN_KOK = array("im", "ım", "um", "üm", 
												"in", "ın", "un", "ün", 
												"i", "ı", "u", "ü", 
												"imiz", "ımız", "umuz", "ümüz", 
												"iniz", "ınız", "unuz", "ünüz");

		// unlu biten kelimenin alabilecegi tum iyelik ekleri
		const TAMLAMA_UNLUYLE_BITEN_KOK = array("m", 
												"n", "nin", "nın", "nun", "nün", "yun", 
												"si", "sı", "su", "yu", "sü", 
												"miz", "mız", "muz", "müz", 
												"niz", "nız", "nuz", "nüz");

		const COGUL_EKLERI = array("ler", "lar");

		const HAL_UNSUZLE_BITEN_KOK = array("i","ı","u","ü",
											"e","a",
											"de","te","da","ta",
											"den","ten","dan","tan");

		const HAL_UNLUYLE_BITEN_KOK = array("yi","yı","yu","yü","ni","nı","nu","nü",
											"ye","ne","ya","na",
											"de","nde","te","da","nda","ta",
											"den","nden","ten","dan","ndan","tan");

		// const LE_UNSUZLE_BITEN_KOK = array("le","la");
		
		// const LE_UNLUYLE_BITEN_KOK = array("yle","yla");

		const ISIM_KI_EKLERI = array("ki","kü");

		// hepsini icerenler
		const ISIM_CEKIM_EKLERI_UNSUZLE_BITEN = array(
			// tamlama
			"im", "ım", "um", "üm", 
			"in", "ın", "un", "ün", 
			"i", "ı", "u", "ü", 
			"imiz", "ımız", "umuz", "ümüz", 
			"iniz", "ınız", "unuz", "ünüz",
			// cogul
			"ler", "lar",
			// hal
			"i","ı","u","ü",
			"e","a",
			"de","te","da","ta",
			"den","ten","dan","tan",
			// ki
			"ki","kü"
			);

		const ISIM_CEKIM_EKLERI_UNLUYLE_BITEN = array(
			// tamlama
			"m", 
			"n", "nin", "nın", "nun", "nün", "yun", 
			"si", "sı", "su", "yu", "sü", 
			"miz", "mız", "muz", "müz", 
			"niz", "nız", "nuz", "nüz",
			// cogul
			"ler", "lar",
			// hal
			"yi","yı","yu","yü","ni","nı","nu","nü",
			"ye","ne","ya","na",
			"de","nde","te","da","nda","ta",
			"den","nden","ten","dan","ndan","tan",
			// ki
			"ki","kü"
			);
?>
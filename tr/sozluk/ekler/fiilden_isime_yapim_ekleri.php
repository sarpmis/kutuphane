<?php
	// ir: gel—ir, ok—ur  (Kip ekleri yaparken bunu yapabiliriz)
	// er: gid-er, yaz-ar (Kip ekleri yaparken bunu yapabiliriz)
	// ici: yap—ıcı, gör—ücü, al—ıcı, sat—ıcı
	// ik: kes—ik, aç—ık, göç—ük
	// im: say—ım, seç—im, öl—üm, ölç—üm
	// (Bu ikisini ayri mi yapalim?)  ti: belir—ti, kızar—tı ////// inti: kes—inti, çık—ıntı, dök—üntü
	// iş: otur—uş, yürü—y-üş
	// ma: konuş-ma, dondur-ma
	// mek: ye-mek, çak-mak, git-mek, duy-mak	
	const YAPIM_FIILDEN_ISIM_UNSUZLE_BITEN = array("ici", "ıcı", "ucu", "ücü", 
												   "ik", "ık", "uk", "ük", 
												   "im", "ım", "um", "üm", 
												   "iş", "ış", "uş", "üş", 
												   "inti", "ıntı", "untu", "üntü", 
												   "ti", "tı", "tu", "tü", 
												   "ar", "er", "ır", "ir", "ur", "ür", 
												   "ma", "me", "mak", "mek",
												   "acak","ecek",
												   "an", "en", 
												   "diği", "dığı", "duğu", "düğü", "dikleri", "dıkları", "dukları", "dükleri", 
												   "tiği", "tığı", "tuğu", "tüğü", "tikleri", "tıkları", "tukları", "tükleri", 
												   "ip", "ıp", "up", "üp",
												   "ince","ınca","unca","ünce",
												   "arak", "erek",
												   "alı","eli",
												   "madan", "meden",
												   "dikçe", "dıkça", "dukça", "dükçe", "tikçe", "tıkça", "tukça", "tükçe",
												   "meksizin", "maksızın",
												   "mez", "maz",
												   "emez", "amaz",
												   "dikten","dıktan","duktan","dükten","tikten","tıktan","tuktan","tükten");

	const YAPIM_FIILDEN_ISIM_UNLUYLE_BITEN = array("yici", "yıcı", "yucu", "yücü", 
												   "nik", "nık", "nuk", "nük", 
												   "nim", "nım", "num", "nüm", 
												   "yiş", "yış", "yuş", "yüş", 
												   "nti", "ntı", "ntu", "ntü", 
												   "ti", "tı", "tu", "tü",  
												   "ma", "me", "mak", "mek",
												   "yacak","yecek", 
												   "yan", "yen", 
												   "diği", "dığı", "duğu", "düğü", "dikleri", "dıkları", "dukları", "dükleri", 
												   "tiği", "tığı", "tuğu", "tüğü", "tikleri", "tıkları", "tukları", "tükleri",
												   "yip", "yıp", "yup", "yüp",
												   "yince","yınca","yunca","yünce",
												   "yarak","yerek",
												   "yalı","yeli",
												   "madan","meden",
												   "dikçe", "dıkça", "dukça", "dükçe", "tikçe", "tıkça", "tukça", "tükçe",
												   "meksizin","maksızın",
												   "mez", "maz",
												   "emez", "amaz",
												   "dikten","dıktan","duktan","dükten","tikten","tıktan","tuktan","tükten");
?>
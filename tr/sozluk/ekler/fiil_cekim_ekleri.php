<?php


	// Burada fiillerin alabildigi tum cekim ekleri, ve bunlarin da tum kisiler icin 
	// cekimlenmis hali var. Yine unluyle_biten_kok/unsuzle_biten_kok ayrimi var cunku
	// bazi zaman cekimlerinde araya -y- girebiliyor..
	// Mesela ---- gel-eyim / ara-yayım
	// DIKKAT! burada bazi exception'lar olacak
	// demek ve yemek fiilleri... 
	const GOR_GEC_ZAM = array("di", "dı", "du", "dü", "ti", "tı", "tu", "tü", "dim", "dım", 
		"dum", "düm", "tim", "tım", "tum", "tüm", "din", "dın", "dun", "dün", "tin", "tın", 
		"tun", "tün", "dik", "dık", "duk", "dük", "tik", "tık", "tuk", "tük", "diniz", 
		"dınız", "dunuz", "dünüz", "tiniz", "tınız", "tunuz", "tünüz", "diler", "dılar", 
		"dular", "düler", "tiler", "tılar", "tular", "tüler");

	const OGR_GEC_ZAM = array("mışım", "mişim", "muşum", "müşüm", "mışsın", "mişsin", "muşsun", "müşsün", 
		"mış", "miş", "muş", "müş", "mışız", "mişiz", "muşuz", "müşüz", "mışsınız", "mişsiniz", "muşsunuz", 
		"müşsünüz", "mışlar", "mişler", "muşlar", "müşler");

	const SIMDIKI_ZAM_UNSUZLE_BITEN_KOK = array("ıyorum", "iyorum", "uyorum", "üyorum", "ıyorsun", 
		"iyorsun", "uyorsun", "üyorsun", "ıyor", "iyor", "uyor", "üyor", "ıyoruz", "iyoruz", "uyoruz", 
		"üyoruz", "ıyorsunuz", "iyorsunuz", "uyorsunuz", "üyorsunuz", "ıyorlar", "iyorlar", "uyorlar", 
		"üyorlar");

	const SIMDIKI_ZAM_UNLUYLE_BITEN_KOK = array("yor", "yorum", "yorsun", "yoruz", "yorsunuz", "yorlar");

	const GELECEK_ZAM_UNSUZLE_BITEN_KOK = array("eceğim", "eceksin", "ecek",
		"eceğiz", "eceksiniz", "ecekler", "acağım", "acaksın", "acak", 
		"acağız", "acaklar");

	const GELECEK_ZAM_UNLUYLE_BITEN_KOK = array("yeceğim", "yeceksin", 
		"yecek", "yeceğiz", "yeceksiniz", "yecekler", "yacağım", "yacaksın", 
		"yacak", "yacağız", "yacaklar");

	const GENIS_ZAM_UNSUZLE_BITEN_KOK = array("ırım", "ırsın", "ır", "ırız", "ırsınız", "ırlar",
	 	"irim", "irsin", "ir", "iriz", "irsiniz", "irler", "urum", "ursun", "ur", "uruz", "ursunuz",
	 	"urlar", "ürüm", "ürsün", "ür", "ürüz", "ürsünüz", "ürler", "arım", "arsın", "ar",
	 	"arız", "arsınız", "arlar", "erim", "ersin", "er", "eriz", "ersiniz", "erler");

	const GENIS_ZAM_UNLUYLE_BITEN_KOK = array("rım", "rsın", "r", "rız", "rsınız", "rlar",
	 	"rim", "rsin", "riz", "rsiniz", "rler", "rum", "rsun", "ruz", "rsunuz",
	 	"rlar", "rüm", "rsün", "rüz", "rsünüz");

	const GEREKLILIK_KIP = array("meliyim", "melisin", "meli", "meliyiz", "melisiniz", "meliler", 
		"malıyım", "malısın", "malı", "malıyız", "malısınız", "malılar");

	const DILEK_SART_KIP = array("sem", "sen", "se", "sek", "seniz", "seler", "sam", "san", "sa", 
		"sak", "sanız", "salar");

	const ISTEK_KIP_UNSUZLE_BITEN = array("eyim", "esin", "e", "elim", "esiniz", "eler", "ayım", "asın", "a", "alım", "asın", "alar");

	const ISTEK_KIP_UNLUYLE_BITEN = array("yeyim", "yesin", "ye", "yelim", "yesiniz", "yeler", "yayım", 
		"yasın", "ya", "yalım", "yasın", "yalar");

	const EMIR_KIPI_UNSUZLE_BITEN = array("sin", "iniz", "in", "sinler", "sın", "ın", "ınız", "sınlar", 
		"sun", "un", "unuz", "sunlar", "sün", "ün", "ünüz", "sünler");

	const EMIR_KIPI_UNLUYLE_BITEN = array("sin", "yiniz", "yin", "sinler", "sın", "yın", "yınız", 
		"sınlar", "sun", "yun", "yunuz", "sunlar", "sün", "yün", "yünüz", "sünler");

	const OLUMSUZLUK = array("ma", "me");


	// hepsini iceren arrayler
	const FIIL_CEKIM_UNLUYLE_BITEN_KOK = array(
		// gor gec zam
		"di", "dı", "du", "dü", "ti", "tı", "tu", "tü", "dim", "dım", 
		"dum", "düm", "tim", "tım", "tum", "tüm", "din", "dın", "dun", "dün", "tin", "tın", 
		"tun", "tün", "dik", "dık", "duk", "dük", "tik", "tık", "tuk", "tük", "diniz", 
		"dınız", "dunuz", "dünüz", "tiniz", "tınız", "tunuz", "tünüz", "diler", "dılar", 
		"dular", "düler", "tiler", "tılar", "tular", "tüler",
		// ogr gec zam
		"mışım", "mişim", "muşum", "müşüm", "mışsın", "mişsin", "muşsun", "müşsün", 
		"mış", "miş", "muş", "müş", "mışız", "mişiz", "muşuz", "müşüz", "mışsınız", "mişsiniz", "muşsunuz", 
		"müşsünüz", "mışlar", "mişler", "muşlar", "müşler",
		// genis zam
		"rım", "rsın", "r", "rız", "rsınız", "rlar",
	 	"rim", "rsin", "riz", "rsiniz", "rler", "rum", "rsun", "ruz", "rsunuz",
	 	"rlar", "rüm", "rsün", "rüz", "rsünüz",
		// simdiki zam
		"yor", "yorum", "yorsun", "yoruz", "yorsunuz", "yorlar",
		// gelecek zam
		"yeceğim", "yeceksin", 
		"yecek", "yeceğiz", "yeceksiniz", "yecekler", "yacağım", "yacaksın", 
		"yacak", "yacağız", "yacaklar",
	 	// gereklilik
	 	"meliyim", "melisin", "meli", "meliyiz", "melisiniz", "meliler", 
		"malıyım", "malısın", "malı", "malıyız", "malısınız", "malılar",
		// dilek sart
		"sem", "sen", "se", "sek", "seniz", "seler", "sam", "san", "sa", 
		"sak", "sanız", "salar",
		// istek
		"yeyim", "yesin", "ye", "yelim", "yesiniz", "yeler", "yayım", 
		"yasın", "ya", "yalım", "yasın", "yalar",
		// emir
		"sin", "yiniz", "yin", "sinler", "sın", "yın", "yınız", 
		"sınlar", "sun", "yun", "yunuz", "sunlar", "sün", "yün", "yünüz", "sünler",
		// olumsuzluk
		"ma", "me");

	const FIIL_CEKIM_UNSUZLE_BITEN_KOK = array(
		// gor gec zam
		"di", "dı", "du", "dü", "ti", "tı", "tu", "tü", "dim", "dım", 
		"dum", "düm", "tim", "tım", "tum", "tüm", "din", "dın", "dun", "dün", "tin", "tın", 
		"tun", "tün", "dik", "dık", "duk", "dük", "tik", "tık", "tuk", "tük", "diniz", 
		"dınız", "dunuz", "dünüz", "tiniz", "tınız", "tunuz", "tünüz", "diler", "dılar", 
		"dular", "düler", "tiler", "tılar", "tular", "tüler",
		// ogr gec zam
		"mışım", "mişim", "muşum", "müşüm", "mışsın", "mişsin", "muşsun", "müşsün", 
		"mış", "miş", "muş", "müş", "mışız", "mişiz", "muşuz", "müşüz", "mışsınız", "mişsiniz", "muşsunuz", 
		"müşsünüz", "mışlar", "mişler", "muşlar", "müşler",
		// genis zam
		"ırım", "ırsın", "ır", "ırız", "ırsınız", "ırlar",
	 	"irim", "irsin", "ir", "iriz", "irsiniz", "irler", "urum", "ursun", "ur", "uruz", "ursunuz",
	 	"urlar", "ürüm", "ürsün", "ür", "ürüz", "ürsünüz", "ürler", "arım", "arsın", "ar",
	 	"arız", "arsınız", "arlar", "erim", "ersin", "er", "eriz", "ersiniz", "erler",
		// simdiki zam
		"ıyorum", "iyorum", "uyorum", "üyorum", "ıyorsun", 
		"iyorsun", "uyorsun", "üyorsun", "ıyor", "iyor", "uyor", "üyor", "ıyoruz", "iyoruz", "uyoruz", 
		"üyoruz", "ıyorsunuz", "iyorsunuz", "uyorsunuz", "üyorsunuz", "ıyorlar", "iyorlar", "uyorlar", 
		"üyorlar",
		// gelecek zam
		"eceğim", "eceksin", "ecek",
		"eceğiz", "eceksiniz", "ecekler", "acağım", "acaksın", "acak", 
		"acağız", "acaklar",
	 	// gereklilik
	 	"meliyim", "melisin", "meli", "meliyiz", "melisiniz", "meliler", 
		"malıyım", "malısın", "malı", "malıyız", "malısınız", "malılar",
		// dilek sart
		"sem", "sen", "se", "sek", "seniz", "seler", "sam", "san", "sa", 
		"sak", "sanız", "salar",
		// istek
		"eyim", "esin", "e", "elim", "esiniz", "eler", "ayım", "asın", "a", "alım", "asın", "alar",
		// emir
		"sin", "iniz", "in", "sinler", "sın", "ın", "ınız", "sınlar", 
		"sun", "un", "unuz", "sunlar", "sün", "ün", "ünüz", "sünler",
		// olumsuzluk
		"ma", "me");
?>
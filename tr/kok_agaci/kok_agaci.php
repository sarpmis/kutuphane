<?php

	// EK KALDIRICILAR
	require_once (__DIR__.'/ek_kaldiricilar/fiil_cekim_ekleri_kaldirici.php');
	require_once (__DIR__.'/ek_kaldiricilar/isim_cekim_ekleri_kaldirici.php');
	require_once (__DIR__.'/ek_kaldiricilar/fiilden_fiile_yapim_ekleri_kaldirici.php');
	require_once (__DIR__.'/ek_kaldiricilar/isimden_fiile_yapim_ekleri_kaldirici.php');
	require_once (__DIR__.'/ek_kaldiricilar/fiilden_isime_yapim_ekleri_kaldirici.php');
	require_once (__DIR__.'/ek_kaldiricilar/isimden_isime_yapim_ekleri_kaldirici.php');
	require_once (__DIR__.'/ek_kaldiricilar/ek_fiil_kaldirici.php');


	// ARGUMANLAR
	const START = 0; // HENUZ EK KALDIRILMAMIS, AGACIN KOKU ICIN
					 // KULLANILIR
	const IIY = 1; // ISIMDEN ISIME YAPIM
	const FIY = 2; // FIILDEN ISIME YAPIM
	const FFY = 3; // FIILDEN FIILE YAPIM
	const IFY = 4; // ISIMDEN FIILE YAPIM
	const IC = 5; // ISIM CEKIM
	const FC = 6; // FIIL CEKIM


	class KokAgaci {
		public $kelime;
		public $children = array();
		public $parent;
		public $argument;
		public $ek;

		// KokAgaci icin constructor. 
		// @param $kelime - Ebeveynden $ek'in cikarilmis hali
		//		  $parent - Ebeveyn
		// 		  $argument - agacin bu dalinin cocuklarini nasil yaratacagini belirler. 
		//					Turkce'nin bir ek hiyerarsisi var. Ornegin yapim ekinden once 
		//					cekim eki gelemez, yani yapim eki ciktiysa cekim eklerine 
		//					bakmamiza gerek yok. Bu hiyerarsiyi kullanarak hem agaci 
		//					daha kucultmus oluyoruz hem de hata payini dusuruyoruz.
		// 		  $ek - Ebeveynin kelimesinden cikarilan ek
		//
		// @post Recursive bir sekilde kelimenin tum kok agacini cikarir.
		//
		// @pre KokAgaci her yaratildiginda $argument START, $parent ve $ek
		//		ise NULL olmalidir.
		//
		// @ornek 
		//			print (new KokAgaci("troleybüsçüleştiremediklerimin", NULL, START));
		//
		function __construct($kelime, $parent, $argument, $ek){
			$this->argument = $argument;
			$this->kelime = $kelime;
			$this->parent = $parent;
			$this->ek = $ek;

			// Argumana gore farkli ekleri cikartmayi dener
			switch ($argument){
				case START:
					$this->fiil_cekim_kontrol();
					$this->isim_cekim_kontrol();
					$this->ek_fiil_kontrol();
					$this->fiilden_fiile_yapim_kontrol();
					$this->fiilden_isime_yapim_kontrol();
					$this->isimden_isime_yapim_kontrol();
					$this->isimden_fiile_yapim_kontrol();
					break;

				case IC:
					$this->isim_cekim_kontrol();
					$this->fiilden_isime_yapim_kontrol();
					$this->isimden_isime_yapim_kontrol();
					break;

				case FC:
					$this->fiil_cekim_kontrol();
					$this->fiilden_fiile_yapim_kontrol();
					$this->isimden_fiile_yapim_kontrol();
					break; 

				case IIY:
					$this->fiilden_isime_yapim_kontrol();
					$this->isimden_isime_yapim_kontrol();
					break; 

				case IFY:
					$this->isimden_isime_yapim_kontrol();
					$this->fiilden_isime_yapim_kontrol();
					break; 

				case FIY:
					$this->fiilden_fiile_yapim_kontrol();
					$this->isimden_fiile_yapim_kontrol();
					break; 

				case FFY:
					$this->fiilden_fiile_yapim_kontrol();
					$this->isimden_fiile_yapim_kontrol();
					break; 
			}
		}


		// *************** KOK AGACI ICIN YARDIMCI METODLAR *************** \\


		// Bu metodlar kaldiricilari kullanarak farkli ek turlerini kaldirmayi dener. 
		// Sonuca gore yeni KokAgaclari yaratir.

		function fiil_cekim_kontrol(){
			$fc = fiil_cekim_kaldir($this->kelime);
			// FC'nin cevabina gore yeni agaclar yaratiyoruz
			// unlu bitimi var mi
			if($fc['unluBitisEksiz'] != NULL) {
				array_push($this->children, new KokAgaci($fc['unluBitisEksiz'], $this, FC, $fc['unluBitisEk']));
			} 
			// unsuz bitimi var mi
			// ses olaylari icin de ayri dallar ayirmamiz gerekli
			if($fc['unsuzBitisEksiz'] != NULL) {
				array_push($this->children, new KokAgaci($fc['unsuzBitisEksiz'], $this, FC, $fc['unsuzBitisEk']));
				if($fc['unluDaralmasi'] != NULL){
					array_push($this->children, new KokAgaci($fc['unluDaralmasi'], $this, FC, $fc['unsuzBitisEk']));
				}
				if($fc['yumusama'] != NULL){
					array_push($this->children, new KokAgaci($fc['yumusama'], $this, FC, $fc['unsuzBitisEk']));
				}
				// 2'ler
				if($fc['unsuzBitisEksiz2'] != NULL){
					array_push($this->children, new KokAgaci($fc['unsuzBitisEksiz2'], $this, FC, $fc['unsuzBitisEk2']));
					if($fc['unluDaralmasi2'] != NULL){
						array_push($this->children, new KokAgaci($fc['unluDaralmasi2'], $this, FC, $fc['unsuzBitisEk2']));
					}
					if($fc['yumusama2'] != NULL){
						array_push($this->children, new KokAgaci($fc['yumusama2'], $this, FC, $fc['unsuzBitisEk2']));
					}						
				}
			}
		}

		function isim_cekim_kontrol(){
			$ic = isim_cekim_kaldir($this->kelime);	
			if($ic['unluBitisEksiz'] != NULL) {
				array_push($this->children, new KokAgaci($ic['unluBitisEksiz'], $this, IC, $ic['unluBitisEk']));
			} 
			if($ic['unsuzBitisEksiz'] != NULL) {
				array_push($this->children, new KokAgaci($ic['unsuzBitisEksiz'], $this, IC, $ic['unsuzBitisEk']));
				if($ic['yumusama'] != NULL){
					array_push($this->children, new KokAgaci($ic['yumusama'], $this, IC, $ic['unsuzBitisEk']));
				}
			}
			if($ic['unluDusmesi'] != NULL){
				array_push($this->children, new KokAgaci($ic['unluDusmesi'], $this, IC, $ic['unsuzBitisEk']));
			}
		}

		function fiilden_fiile_yapim_kontrol(){
			$ffy = yapim_fiilden_fiile_kaldir($this->kelime);
			if($ffy['unluBitisEksiz'] != NULL) {
				array_push($this->children, new KokAgaci($ffy['unluBitisEksiz'], $this, FFY, $ffy['unluBitisEk']));
			}
			if($ffy['unsuzBitisEksiz'] != NULL){
				array_push($this->children, new KokAgaci($ffy['unsuzBitisEksiz'], $this, FFY, $ffy['unsuzBitisEk']));
				if($ffy['unluDaralmasi'] != NULL){
					array_push($this->children, new KokAgaci($ffy['unluDaralmasi'], $this, FC, $ffy['unsuzBitisEk']));
				}
				if($ffy['yumusama'] != NULL){
					array_push($this->children, new KokAgaci($ffy['yumusama'], $this, FFY, $ffy['unsuzBitisEk']));
				}
			}
		}

		function fiilden_isime_yapim_kontrol(){
			$fiy = yapim_fiilden_isime_kaldir($this->kelime);
			if($fiy['unluBitisEksiz'] != NULL) {
				array_push($this->children, new KokAgaci($fiy['unluBitisEksiz'], $this, FIY, $fiy['unluBitisEk']));
			}
			if($fiy['unsuzBitisEksiz'] != NULL){
				array_push($this->children, new KokAgaci($fiy['unsuzBitisEksiz'], $this, FIY, $fiy['unsuzBitisEk']));
				if($fiy['yumusama'] != NULL){
					array_push($this->children, new KokAgaci($fiy['yumusama'], $this, FIY, $fiy['unsuzBitisEk']));
				}
			}
		}

		function isimden_isime_yapim_kontrol(){
			$iiy = yapim_isimden_isime_kaldir($this->kelime);
			if($iiy['unluBitisEksiz'] != NULL) {
				array_push($this->children, new KokAgaci($iiy['unluBitisEksiz'], $this, IIY, $iiy['unluBitisEk']));
			}
			if($iiy['unsuzBitisEksiz'] != NULL){
				array_push($this->children, new KokAgaci($iiy['unsuzBitisEksiz'], $this, IIY, $iiy['unsuzBitisEk']));
				if($iiy['yumusama'] != NULL){
					array_push($this->children, new KokAgaci($iiy['yumusama'], $this, IIY, $iiy['unsuzBitisEk']));
				}
			}
		}

		function isimden_fiile_yapim_kontrol(){
			$ify = yapim_isimden_fiile_kaldir($this->kelime);
			if($ify['eksiz'] != NULL) {
				array_push($this->children, new KokAgaci($ify['eksiz'], $this, IFY, $ify['ek']));
			}
		}

		function ek_fiil_kontrol(){
			$ef = ek_fiil_kaldir($this->kelime);
			if($ef['unluBitisEksiz'] != NULL) {
				array_push($this->children, new KokAgaci($ef['unluBitisEksiz'], $this, START, $ef['unluBitisEk']));
			}
			if($ef['unsuzBitisEksiz'] != NULL){
				array_push($this->children, new KokAgaci($ef['unsuzBitisEksiz'], $this, START, $ef['unsuzBitisEk']));
			}
		}

		// TEST AMACLI METODLAR. kok agacini gormek icin string haline getirir. 
		// Agaci preorder formatinda yazdirir. Agactaki her node 
		// (ek kaldirilmis kelime)(kaldirilan ekin turu) -(kaldirilan ek)
		// formatinda yazilir. Kaldirilan ekin turu bu sayfanin en ustunde
		// tanimlanan 
		// Ornek: 
		// 			print (new KokAgaci("kovalamak", NULL, START, NULL));
		// Sonuc: 
		// 			kovalamak0 -
		// 			kovala2 -mak
		// 			kova4 -la
		function __toString() {
			$str = $this->kelime.$this->argument." -".$this->ek."<br>";
			foreach($this->children as $child){
				$str = $str.$child;
			}
			return $str;
		}

		// ***************BUNU TUTMAK LAZIM MI BILMIYORUM ***************\\
		// function stringify(){
		// 	$str = $this->kelime."\n";
		// 	foreach($this->children as $child){
		// 		$str = $str.($child->stringify());
		// 	}
		// 	return $str;
		// }
	}


	// *************** TESTLER *************** \\ 
	// print (new KokAgaci("troleybüsçüleştiremediklerimin", NULL, START, NULL));
	// print (new KokAgaci("kovalamak", NULL, START, NULL));
	// print (new KokAgaci("muvaffakiyetsizleştiricileştiriveremeyebileceklerimizdenmişsinizcesine", NULL, START, NULL));
	// print(new KokAgaci("hacmi", NULL, START, NULL));
?>
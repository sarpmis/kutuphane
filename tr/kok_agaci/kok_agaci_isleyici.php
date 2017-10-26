<?php
	
	require_once (__DIR__.'/../sozluk/ekler/fiilden_fiile_yapim_ekleri.php');
	require_once (__DIR__.'/../sozluk/ekler/isimden_fiile_yapim_ekleri.php');
	require_once (__DIR__.'/../sozluk/ekler/fiilden_isime_yapim_ekleri.php');
	require_once (__DIR__.'/../sozluk/ekler/isimden_isime_yapim_ekleri.php');

	require_once (__DIR__.'/kok_agaci.php');
	require_once (__DIR__.'/kok_agaci_yardimcilar.php');

	require_once (__DIR__.'/../database_handler.php');

	// tipler
	const IS = 0;
	const FI = 1;
	const X = 2;

	class KokAgaciIsleyici{
		public $tipli = array();
		private $dbHandler;
		private $ISIMDEN_ISIME_YAPIM_EKLERI;
		private $FIILDEN_ISIME_YAPIM_EKLERI;
		private $FIILDEN_FIILE_YAPIM_EKLERI;
		private $ISIMDEN_FIILE_YAPIM_EKLERI;

		function __construct($dbhandler){
			$this->dbHandler = $dbhandler;
			$this->ISIMDEN_ISIME_YAPIM_EKLERI = array_merge(YAPIM_ISIMDEN_ISIM_UNLUYLE_BITEN, YAPIM_ISIMDEN_ISIM_UNSUZLE_BITEN);
			$this->FIILDEN_ISIME_YAPIM_EKLERI = array_merge(YAPIM_FIILDEN_ISIM_UNLUYLE_BITEN, YAPIM_FIILDEN_ISIM_UNSUZLE_BITEN);
			$this->FIILDEN_FIILE_YAPIM_EKLERI = array_merge(YAPIM_FIILDEN_FIIL_UNSUZLE_BITEN, YAPIM_FIILDEN_FIIL_UNLUYLE_BITEN);
			$this->ISIMDEN_FIILE_YAPIM_EKLERI = YAPIM_ISIMDEN_FIIL;
		}

		function tipleriBul($agac, &$tipli) {


			
			if($agac->argument === IC){
				array_push($tipli, $agac->kelime.IS);
			} else if ($agac->argument === FC){
				array_push($tipli, $agac->kelime.FI);
			} else if ($agac->argument === START){
				array_push($tipli, $agac->kelime.X);
			} else if ($agac->argument === IIY){
				array_push($tipli, $agac->kelime.IS);
			} else if ($agac->argument === IFY){
				array_push($tipli, $agac->kelime.IS);
			} else if ($agac->argument === FIY){
				array_push($tipli, $agac->kelime.FI);
			} else if ($agac->argument === FFY){
				array_push($tipli, $agac->kelime.FI);
			}
			foreach ($agac->children as $child){
				$this->tipleriBul($child, $tipli);
			}
		}

		function sozluktenBak(){

			$legaller = array();

			sortTokens($this->tipli);
			removeDuplicates($this->tipli);
			
			foreach($this->tipli as $token){
				$tip = mb_substr($token, -1);
				if($tip == FI) {
					$DBcevap = $this->dbHandler->wordLookupByWord(mastarEkle(mb_substr($token, 0, -1)));
					if(!empty($DBcevap)){
						foreach ($DBcevap as $entry){
							// print(json_encode($entry));
							if($entry['fiil'] == 1 && !in_array($entry['kelime'], $legaller)){
								array_push($legaller, $entry['num']);
								// print('pushing - '.$entry['kelime']." from ".$token);
							}
						}
					}
				} else if($tip == IS){
					$DBcevap = $this->dbHandler->wordLookupByWord(mb_substr($token, 0, -1));
					if(!empty($DBcevap)){
						foreach ($DBcevap as $entry){
							if(($entry['isim'] == 1 || $entry['sayi'] == 1 || $entry['sifat'] == 1 || 
								$entry['ozel'] == 1 || $entry['zamir'] == 1) && !in_array($entry['kelime'], $legaller)){
								array_push($legaller, $entry['num']);
								// print('pushing - '.$entry['kelime']." from ".$token);
							}
						}
					}
				} else if($tip == X){
					$DBcevap = $this->dbHandler->wordLookupByWord(mastarEkle(mb_substr($token, 0, -1)));
					// print(mastarEkle(mb_substr($token, 0, -1)));
					if(!empty($DBcevap)){
						foreach ($DBcevap as $entry){
							// print(json_encode($entry));
							if($entry['fiil'] == 1 && !in_array($entry['kelime'], $legaller)){
								array_push($legaller, $entry['num']);
								// print('pushing - '.$entry['kelime']." from ".$token);
							}
						}
					}
					$DBcevap = $this->dbHandler->wordLookupByWord(mb_substr($token, 0, -1));
					if(!empty($DBcevap)){
						foreach ($DBcevap as $entry){
							if(($entry['isim'] == 1 || $entry['sayi'] == 1 || $entry['sifat'] == 1 || 
								$entry['ozel'] == 1 || $entry['zamir'] == 1) && !in_array($entry['kelime'], $legaller)){
								array_push($legaller, $entry['num']);
								// print('pushing - '.$entry['kelime']." from ".$token);
							}
						}
					}
				}
			}
			return $legaller;
		}

		function kokBul($kelime){
			$agac = new KokAgaci($kelime, NULL, START, NULL);
			$this->tipli = array();
			$this->tipleriBul($agac, $this->tipli);
			return($this->sozluktenBak());
		}

	}
	// $agac = new KokAgaci("kurt", NULL, START, NULL);

	// print ($agac->argument); 
	// print($agac);

	// $isleyici = new KokAgaciIsleyici(new DatabaseHandler());

	// $okuyucu->tipleriBul($agac, $okuyucu->tipli);

	
	// print(json_encode($isleyici->kokBul("aradakilerinse"), JSON_UNESCAPED_UNICODE));

	// print(json_encode($tipli));
	// print(json_encode($tipli, JSON_UNESCAPED_UNICODE)."<br>");
	// print(json_encode($okuyucu->sozluktenBak(), JSON_UNESCAPED_UNICODE));

?>
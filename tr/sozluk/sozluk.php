<?php
	require_once (__DIR__.'/../database_handler.php');
	require_once (__DIR__.'/../../setup/config.php');
	require_once (__DIR__.'/sozluk_yardimcilar.php');

	class Sozluk {

		/*
		 * Yeni bir sozluk objesi yaratir.
		 *
		 * 
		 * @param 
		 * 		$db_table_name_sozluk
		 * 				database'de yaratilacak sozluk tablosunun ismi
		 *		$conn
		 *				database baglantisi (PDO connection)
		 *		$db_error_log_file
		 *				error log dosyasinin adresi (PATH)
		 *
		 */
		function __construct($table_name, $conn, $error_log_file){
			$this->table_name = $table_name;
			$this->conn = $conn;
			$this->error_log_file = $error_log_file;
		}

		private $kelimeler = array();

		function sozlukOku($path){
			$text = file_get_contents($path);

			// temizlik
			$text = iconv("UTF-8", "UTF-8//IGNORE", $text);
			$text = remove_utf8_bom($text);

			$array = explode("\n", $text);

			// print(json_encode($array,JSON_UNESCAPED_UNICODE));

			print("sozluk boyutu : ".sizeof($array)."<br>");

			foreach($array as $token){
				// ' karakteri mysql'i bozuyor, icinde ' olan kelimeler var ama
				// bid'at mesela... sozlukte boyle 64 kelime var.
				// bu karakterin yerine mesela % karakteri koyup sonra sozlukten okudugumuzda
				// yine degistirebiliriz. ugrasmaya deger mi biliyorum...
				if(mb_substr($token, 0, 1) != "#" && mb_strpos($token, "'") === false){

					array_push($this->kelimeler, $token);
				}
			}
			print("yorumsuz sozluk boyutu : ".sizeof($this->kelimeler)."<br>");
		}

		/*
		 * Database'teki sozluk tablosuna yeni bir kelime ekler. 
		 * 
		 *
		 * @param $array=(isim, fiil, sifat, sayi, ozel, zamir, yum, ters, dus, yal, gen, cift, zaman)
		 * array'in elemanlari 1 veya 0 oluyor kelime ozelligine gore. mesela array 
		 * (1,0,0,0,0,0,1,0,0,0,0,0,0) ise bu yumusama geciren bir isimdir
		 */
		function addToDict($word, $array){
			$error_log = "";
			try{
				$sql = "INSERT INTO $this->table_name (kelime, isim, fiil, sifat, sayi, ozel, zamir, yumusama, ters, dusme, yalin, genis, cift)
						VALUES ('$word', $array[0], $array[1], $array[2], $array[3], $array[4], $array[5], $array[6], $array[7], $array[8], $array[9], $array[10], $array[11])";
				$this->conn->exec($sql);
			} catch (PDOException $e){
				$error_log = $error_log.$sql."\n".$e->getMessage()."\n\n";
				file_put_contents($this->error_log_file, $error_log);
			}
		}


		/*
		 * Okunan kelimelerin database'e ekler. isim-sifat vb. ayrimi
		 * burada yapiliyor.
		 *
		 *
		 */
		function wordsToDB(){
			foreach($this->kelimeler as $kel){
				$kel = $kel." ";
				$ozellikler = array(0,0,0,0,0,0,0,0,0,0,0,0,0);
				if(mb_strpos($kel, ' IS ') !== false) $ozellikler[0] = 1;
				if(mb_strpos($kel, ' FI ') !== false) $ozellikler[1] = 1;
				if(mb_strpos($kel, ' SI ') !== false) $ozellikler[2] = 1;
				if(mb_strpos($kel, ' SA ') !== false) $ozellikler[3] = 1;
				if(mb_strpos($kel, ' OZ ') !== false) $ozellikler[4] = 1;
				if(mb_strpos($kel, ' ZA ') !== false) $ozellikler[5] = 1;
				if(mb_strpos($kel, ' YUM ') !== false) $ozellikler[6] = 1;
				if(mb_strpos($kel, ' TERS ') !== false) $ozellikler[7] = 1;
				if(mb_strpos($kel, ' DUS ') !== false) $ozellikler[8] = 1;
				if(mb_strpos($kel, ' YAL ') !== false) $ozellikler[9] = 1;
				if(mb_strpos($kel, ' GEN ') !== false) $ozellikler[10] = 1;
				if(mb_strpos($kel, ' CIFT ') !== false) $ozellikler[11] = 1;
				if(mb_strpos($kel, ' ZAMAN ') !== false) $ozellikler[12] = 1;
				$arr = explode(" ", $kel);
				$this->addToDict(mb_strtolower($arr[0]), $ozellikler);
				if($ozellikler == array(0,0,0,0,0,0,0,0,0,0,0,0,0)) print ("error word : ".$kel."<br>");
			}
		}
	}

	// sozluge ekleme yapmak icin
	// **************************************************************************
	// $sozluk = new Sozluk();
	// $sozluk->sozlukOku('corpus_eklenecek.txt');
	// $handler = new DatabaseHandler();
	// $sozluk->wordsToDB($handler);

	// sapkali harfler icin
	// **************************************************************************
	// $sozluk = new Sozluk();
	// $sozluk->sozlukOku('corpus_kisi_adlari.txt');
	// $str = '';
	// foreach($sozluk->kelimeler as $kel){
	// 	$yeni = '';
	// 	$pos = mb_strpos($kel, 'î');
	// 	if($pos !== false){
	// 		$yeni = mb_substr($kel, 0, $pos)."i".mb_substr($kel, $pos+1);
	// 		$pos2 = mb_strpos($yeni, 'î');
	// 		if($pos2 !== false){
	// 			$yeni = mb_substr($yeni, 0, $pos2)."i".mb_substr($yeni, $pos2+1);
	// 		}
	// 		print($kel);
	// 	}
	// 	$pos = mb_strpos($kel, 'â');
	// 	if($pos !== false){
	// 		$yeni = mb_substr($kel, 0, $pos)."a".mb_substr($kel, $pos+1);
	// 		$pos2 = mb_strpos($yeni, 'â');
	// 		if($pos2 !== false){
	// 			$yeni = mb_substr($yeni, 0, $pos2)."a".mb_substr($yeni, $pos2+1);
	// 		}
	// 		print($kel);
	// 	}
	// 	$pos = mb_strpos($kel, 'û');
	// 	if($pos !== false){
	// 		$yeni = mb_substr($kel, 0, $pos)."u".mb_substr($kel, $pos+1);
	// 		$pos2 = mb_strpos($yeni, 'û');
	// 		if($pos2 !== false){
	// 			$yeni = mb_substr($yeni, 0, $pos2)."u".mb_substr($yeni, $pos2+1);
	// 		}
	// 		print($kel);
	// 	}
	// 	$pos = mb_strpos($kel, 'Î');
	// 	if($pos !== false){
	// 		$yeni = mb_substr($kel, 0, $pos)."İ".mb_substr($kel, $pos+1);
	// 		$pos2 = mb_strpos($yeni, 'Î');
	// 		if($pos2 !== false){
	// 			$yeni = mb_substr($yeni, 0, $pos2)."İ".mb_substr($yeni, $pos2+1);
	// 		}
	// 		print($kel);
	// 	}
	// 	$pos = mb_strpos($kel, 'Â');
	// 	if($pos !== false){
	// 		$yeni = mb_substr($kel, 0, $pos)."A".mb_substr($kel, $pos+1);
	// 		$pos2 = mb_strpos($yeni, 'Â');
	// 		if($pos2 !== false){
	// 			$yeni = mb_substr($yeni, 0, $pos2)."A".mb_substr($yeni, $pos2+1);
	// 		}
	// 		print($kel);
	// 	}
	// 	$pos = mb_strpos($kel, 'Û');
	// 	if($pos !== false){
	// 		$yeni = mb_substr($kel, 0, $pos)."U".mb_substr($kel, $pos+1);
	// 		$pos2 = mb_strpos($yeni, 'Û');
	// 		if($pos2 !== false){
	// 			$yeni = mb_substr($yeni, 0, $pos2)."U".mb_substr($yeni, $pos2+1);
	// 		}
	// 		print($kel);
	// 	}
	// 	if($yeni !== ''){
	// 		$str = $str.$yeni."\n";
	// 	}
	// }


	// ters kelimeler icin
	// **************************************************************************
	// $sozluk = new Sozluk();
	// $sozluk->sozlukOku('corpus_ekstra.txt');
	// $str = '';
	// $terslerStr = file_get_contents('tersler.txt');
	// $tersler = json_decode($terslerStr);
	// print(count($tersler)."<br>");
	// foreach($sozluk->kelimeler as $kel){
	// 	$yeni = '';
	// 	$pos = mb_strpos($kel, 'TERS');
	// 	if($pos !== false){
	// 		$fiil = mb_strpos($kel, "FI");
	// 		if($fiil !== false){
	// 			$mekMak = mb_strpos($kel, "mak ");
	// 			if($mekMak !== false){
	// 				$mekMak = mb_strpos($kel, "mek ");
	// 			}
	// 			if($mekMak === false){
	// 				print($kel." bu bozuk amk<br>");
	// 			} else {
	// 				array_push($tersler, mb_substr($kel, 0, $mekMak));
	// 			}
	// 		} else {
	// 			$array = explode(" ", $kel);
	// 			array_push($tersler, $array[0]);
	// 		}
	// 	}
	// }
	// print(count($tersler)."<br>");
	// file_put_contents((__DIR__."/tersler.txt"), json_encode($tersler, JSON_UNESCAPED_UNICODE));
?>
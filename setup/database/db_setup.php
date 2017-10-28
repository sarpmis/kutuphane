<?php
	require_once (__DIR__.'/../config.php');

	// Baglanti kontrolu
	if($conn == NULL) die("Connection is not set!");

	// Hatalarin eklenecegi string
	$error_log = "";

	// *********** SOZLUK TABLOSU OLUSTURMA *********** // 

	try{
		$sql = "CREATE TABLE $db_table_name_sozluk (
		  `kelime` varchar(40) COLLATE utf8_bin NOT NULL,
		  `num` int(11) NOT NULL,
		  `isim` int(11) DEFAULT '0',
		  `fiil` int(11) DEFAULT '0',
		  `sifat` int(11) DEFAULT '0',
		  `sayi` int(11) DEFAULT '0',
		  `ozel` int(11) DEFAULT '0',
		  `zamir` int(11) DEFAULT '0',
		  `yumusama` int(11) DEFAULT '0',
		  `ters` int(11) NOT NULL DEFAULT '0',
		  `dusme` int(11) NOT NULL DEFAULT '0',
		  `yalin` int(11) NOT NULL DEFAULT '0',
		  `genis` int(11) NOT NULL DEFAULT '0',
		  `cift` int(11) NOT NULL DEFAULT '0'
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

		ALTER TABLE $db_table_name_sozluk 
	  		ADD PRIMARY KEY (`num`),
	  		ADD UNIQUE KEY `kelime` (`kelime`,`num`),
	  		MODIFY `num` int(11) NOT NULL AUTO_INCREMENT;

  		";

		$conn->exec($sql);
		echo('Sozluk tablosu olusturuldu! <br>');
	} catch (PDOException $e){
		$error_log = $error_log.$sql."\n".$e->getMessage()."\n\n";
		file_put_contents($db_error_log_file, $error_log);
	}

	// *********** FREKANSLAR TABLOSU OLUSTURMA *********** // 

	try{
		$sql = "CREATE TABLE $db_table_name_frekanslar (
		  `key1` int(11) NOT NULL,
		  `key2` int(11) NOT NULL,
		  `frequency` int(11) NOT NULL
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;

		ALTER TABLE $db_table_name_frekanslar
  			ADD PRIMARY KEY (`key1`,`key2`);
  		";

		$conn->exec($sql);
		echo('Frekans tablosu olusturuldu! <br>');
	} catch (PDOException $e){
		$error_log = $error_log.$sql."\n".$e->getMessage()."\n\n";
		file_put_contents($db_error_log_file, $error_log);
	}
?>
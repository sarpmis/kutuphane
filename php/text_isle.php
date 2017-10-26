<?php
	require_once (__DIR__.'/../tr/database/egitici.php');

	if(isset($_POST['text'])){
		$text = $_POST['text'];

	    function convert($size)
	    {
	        $unit=array('b','kb','mb','gb','tb','pb');
	        return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
	    }

		$db = new DatabaseHandler();
		$egitici = new Egitici($db, new KokAgaciIsleyici($db));
		

		print("Kelime sayisi = ".sizeof(explode(" ", $text))."	");

		// koklestirme
	    $memory_pre = memory_get_usage();
	    $time_pre = microtime(true);
	    // execute code here
		$kokler  = $egitici->koklestir($text);
	    // 
	    $time_post = microtime(true);
	    $memory_post = memory_get_usage();
	    $exec_time = $time_post - $time_pre;
	    $extra_mem = $memory_post - $memory_pre;
	    print("Koklestirme zamani : ".$exec_time."	");
	    print("Koklestirmeden eksta hafiza : ".convert($extra_mem)."	");

	    // db'ye atma
	    $memory_pre = memory_get_usage();
	    $time_pre = microtime(true);
	    // execute code here
		$egitici->toDB($kokler);
	    // 
	    $time_post = microtime(true);
	    $memory_post = memory_get_usage();
	    $exec_time = $time_post - $time_pre;
	    $extra_mem = $memory_post - $memory_pre;
	    print("Database'e atma zamani : ".$exec_time."	");
	    print("Ekstra hafiza : ".convert($extra_mem)."	");
	} else {
		echo 0;
	}

?>
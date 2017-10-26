<?php
	// require_once 'sozluk/alfabe.php';
	// require_once 'sozluk/ekler.php';
	// require_once 'yardimcilar.php';
	// require_once 'isim.php';
	// require_once 'sozluk/sozluk.php';
	// require_once 'database_handler.php';


	function convert($size)
    {
        $unit=array('b','kb','mb','gb','tb','pb');
        return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
    }


	// $ekci = new ekKaldirici();


	// print(json_encode($ekci->iyelikCheck("babam"),JSON_UNESCAPED_UNICODE)."<br><br>");
	// print(json_encode($ekci->iyelikCheck("kayığı"),JSON_UNESCAPED_UNICODE)."<br><br>");
	// print(json_encode($ekci->iyelikCheck("annesi"),JSON_UNESCAPED_UNICODE)."<br><br>");
	// print(json_encode($ekci->iyelikCheck("elleri"),JSON_UNESCAPED_UNICODE)."<br><br>");
	// print(json_encode($ekci->iyelikCheck("arabanız"),JSON_UNESCAPED_UNICODE)."<br><br>");
	// print(json_encode($ekci->iyelikCheck("troleybüsçüleştiremediklerimin"),JSON_UNESCAPED_UNICODE)."<br><br>");
	// print(json_encode($ekci->iyelikCheck("kedim"),JSON_UNESCAPED_UNICODE)."<br><br>");
	// print(json_encode($ekci->iyelikCheck("beyni"),JSON_UNESCAPED_UNICODE)."<br><br>");


 //    $memory_pre = memory_get_usage();
 //    $time_pre = microtime(true);
 //    // execute code here
	// $sozluk = new Sozluk();

	// $sozluk->sozlukOku("corpus.txt");
 //    // 
 //    $time_post = microtime(true);
 //    $memory_post = memory_get_usage();
 //    $exec_time = $time_post - $time_pre;
 //    $extra_mem = $memory_post - $memory_pre;
 //    print("Time : ".$exec_time."<br>");
 //    print("Extra Memory :  ".convert($extra_mem)."<br><br>");

	// $dbHandler = new DatabaseHandler();


    // $memory_pre = memory_get_usage();
    // $time_pre = microtime(true);
    // // execute code here
    // $sozluk->wordsToDB($dbHandler);
    // // 
    // $time_post = microtime(true);
    // $memory_post = memory_get_usage();
    // $exec_time = $time_post - $time_pre;
    // $extra_mem = $memory_post - $memory_pre;
    // print("Time : ".$exec_time."<br>");
    // print("Extra Memory :  ".convert($extra_mem)."<br><br>");


    // $memory_pre = memory_get_usage();
    // $time_pre = microtime(true);
    // // execute code here

    // print(json_encode($dbHandler->wordLookupByWord('muvaffak'),JSON_UNESCAPED_UNICODE)."<br><br>");
    // // 
    // $time_post = microtime(true);
    // $memory_post = memory_get_usage();
    // $exec_time = $time_post - $time_pre;
    // $extra_mem = $memory_post - $memory_pre;
    // print("Time : ".$exec_time."<br>");
    // print("Extra Memory :  ".convert($extra_mem)."<br><br>");


    // $memory_pre = memory_get_usage();
    // $time_pre = microtime(true);
    // // execute code here

    // print(json_encode($dbHandler->wordLookupByWord('su'),JSON_UNESCAPED_UNICODE)."<br><br>");
    // // 
    // $time_post = microtime(true);
    // $memory_post = memory_get_usage();
    // $exec_time = $time_post - $time_pre;
    // $extra_mem = $memory_post - $memory_pre;
    // print("Time : ".$exec_time."<br>");
    // print("Extra Memory :  ".convert($extra_mem)."<br><br>");



    // print(json_encode(mb_strpos("bidat OZEL_IC_KARAKTER", "OZEL")))	;

    print("EGER BUNU GORUYORSAN CALISIYOR DEMEKTIR");

?>

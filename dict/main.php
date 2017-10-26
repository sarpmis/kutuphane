 <?php
    require 'dictionary.php';
    require 'queryHandler.php';



    function convert($size)
    {
        $unit=array('b','kb','mb','gb','tb','pb');
        return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
    }


    // print("TEST RESULTS FOR fatihSultanMehmet.txt <br> Time in seconds<br><br>");

    // // create dictionary
    // $memory_pre = memory_get_usage();
    // $time_pre = microtime(true);
    // // execute code here
    // $dict = new Dictionary("samples/largeText4.txt");
    // // 
    // $time_post = microtime(true);
    // $memory_post = memory_get_usage();
    // $exec_time = $time_post - $time_pre;
    // $extra_mem = $memory_post - $memory_pre;
    // print("Dictionary Create Time : ".$exec_time."<br>");
    // print("Extra Memory From New Dictionary: ".convert($extra_mem)."<br><br>");


    // // add WFPs 
    // $memory_pre = memory_get_usage();
    // $time_pre = microtime(true);
    // // execute code here
    // $dict->addWFP2Contexts();
    // // 
    // $time_post = microtime(true);
    // $memory_post = memory_get_usage();
    // $exec_time = $time_post - $time_pre;
    // $extra_mem = $memory_post - $memory_pre;
    // print('addWFP2Contexts Time : '.$exec_time."<br>");
    // print('Extra Memory From addWFP2Contexts : '.convert($extra_mem)."<br><br>");


    // // // sort by frequency
    // $memory_pre = memory_get_usage();
    // $time_pre = microtime(true);
    // // execute code here
    // $dict->sortContextsFreq();
    // // 
    // $time_post = microtime(true);
    // $memory_post = memory_get_usage();
    // $exec_time = $time_post - $time_pre;
    // $extra_mem = $memory_post - $memory_pre;
    // print("Frequency Sort Time : ".$exec_time."<br>");
    // print("Extra Memory From Sort : ".convert($extra_mem)."<br><br>");


    // // sort alphabetical
    // $memory_pre = memory_get_usage();
    // $time_pre = microtime(true);
    // // execute code here
    // $dict->sortContextsAlphabetical();
    // // 
    // $time_post = microtime(true);
    // $memory_post = memory_get_usage();
    // $exec_time = $time_post - $time_pre;
    // $extra_mem = $memory_post - $memory_pre;
    // print("Alphabetical Sort Time : ".$exec_time."<br>");
    // print("Extra Memory From Sort : ".convert($extra_mem)."<br><br>");
   



    // // write ditionary to file
    // $memory_pre = memory_get_usage();
    // $time_pre = microtime(true);
    // // execute code here
    // file_put_contents('dictionaries/large2.dict', $dict);
    // // 
    // $time_post = microtime(true);
    // $memory_post = memory_get_usage();
    // $exec_time = $time_post - $time_pre;
    // $extra_mem = $memory_post - $memory_pre;
    // print("Write Dictionary to File Time : ".$exec_time."<br>");
    // print("Extra Memory From Write : ".convert($extra_mem)."<br><br>");

   

    // read the dictionary from file
    $memory_pre = memory_get_usage();
    $time_pre = microtime(true);
    // execute code here
    $dict2 = readDictionary('dictionaries/large.dict');
    // 
    $time_post = microtime(true);
    $memory_post = memory_get_usage();
    $exec_time = $time_post - $time_pre;
    $extra_mem = $memory_post - $memory_pre;
    print("Read Time : ".$exec_time."<br>");
    print("Extra Memory From Read: ".convert($extra_mem)."<br><br>");

    // read the dictionary from file
    $memory_pre = memory_get_usage();
    $time_pre = microtime(true);
    // execute code here
    $dict = readDictionary('dictionaries/large2.dict');
    // 
    $time_post = microtime(true);
    $memory_post = memory_get_usage();
    $exec_time = $time_post - $time_pre;
    $extra_mem = $memory_post - $memory_pre;
    print("Read Time : ".$exec_time."<br>");
    print("Extra Memory From Read: ".convert($extra_mem)."<br><br>");

    // merge dictionaries
    $memory_pre = memory_get_usage();
    $time_pre = microtime(true);
    // execute code here
    $dict->merge($dict2);
    // 
    $time_post = microtime(true);
    $memory_post = memory_get_usage();
    $exec_time = $time_post - $time_pre;
    $extra_mem = $memory_post - $memory_pre;
    print("Merge Time : ".$exec_time."<br>");
    print("Extra Memory From Merge: ".convert($extra_mem)."<br><br>");



    print("Dictionary Length = ".$dict->conSize."<br>");
    print("Total Words Processed = ".$dict->totalSize."<br>");
    print("Total memory usage = ".convert(memory_get_usage())."<br>");
    print("Peak memory usage = ".convert(memory_get_peak_usage())."<br>");



    // $qh = new QueryHandler($dict, 'filters/queryFilter.txt', 'filters/suffixes.txt');
    // print($qh->tokenScore("king", "macbeth")."<br><br>");

    // print($dict);

    // print($dict->totalSize);

    // $qh->query("fatih sultan mehmed kim","istanbul'u kim fethetti");
    // print("<br><br>".$dict);

    // $queries = array("fatih sultan mehmed kim?", "istanbul'u kim fethetti?", "neden istanbul'da yemek var?");

    // $qh->queryTable($queries);
?>
<?php
	// removes duplicate values in a sorted array
	function removeDuplicates(&$array) {
		$length = sizeof($array);
		$new = array();
		$previousToken = NULL;
		for($i = 0; $i < $length; $i++){
			if($array[$i] != $previousToken){
				array_push($new, $array[$i]);
			}
			$previousToken = $array[$i];
		}
		$array = $new;
	}

	// general array stringifier
    function stringifyArrayGen($array){
    	$string = "";
    	$i = sizeof($array);
    	for($k = 0; $k < $i; $k++){
    		$string = $string.$array[$k]." <br>\n";
    	}
    	return $string;
    }

    /*
     * reads a dictionary from a file.
     * @param $path the file that contains the dictionary
     *
     */
    function readDictionary($path){
        // create empty dictionary
        $dict = new Dictionary(NULL);

        // clear the dictionary text to parse it easily
        $badchars = array(',', '<br>', ' ', ')');
        $text = file_get_contents($path);
        $text = str_replace($badchars, '', $text);
        $contexts = explode("\n", $text);

        // loop every context line
        // loop ends at sizeof(contexts) - 1 because last element is always empty
        for($i = 0; $i < sizeof($contexts) - 1; $i++){
            $conStr = $contexts[$i];
            // format for printing is :
            // context::WFPs
            $conAndWFPs = explode('::', $conStr);

            //create the context
            $con = new Context($conAndWFPs[0]);
            
            // add WFPs to the context
            $WFPs = explode('(', $conAndWFPs[1]);
            for($k = 1; $k < sizeof($WFPs); $k++){ // loop starts at 1 because there's
                                                   // always an empty string at 0
                $wfp = explode('-', $WFPs[$k]);
                array_push($con->array, new WordFrequencyPair($wfp[0], $wfp[1]));
            }
            //add context to dictionary
            array_push($dict->contexts, $con);
        }
        $dict->setSize();
        return $dict;
    }


/************************* TEXT PROCESSING METHODS *********************/


    /*
     * General-purpose text-cleaning method. Takes a string and
     * makes it UTF-8, lowercase, and free of "bad" byte sequences.
     *
     * @param $textToClean - string to be cleaned
     *
     */
    function cleanText(&$textToClean) {
        $textToClean = trim($textToClean);
        // drops all non utf-8 characters
        $textToClean = iconv("UTF-8", "UTF-8//IGNORE", $textToClean);
        $textToClean = mb_strtolower($textToClean);


        $textToClean = preg_replace('/\s+/', ' ', $textToClean);
        return $textToClean;
    }

    /*
     * Removes characters after the given character on all tokens
     *
     * @param $tokens - tokens to clear apostrophes
     *        $char - character to remove after
     *
     */
    function removeAfterCharacter(&$tokens, $char) {
        $new = array();
        foreach($tokens as $tok){
            $temp = substr($tok, 0, strpos($tok, $char));
            if($temp != $tok && $temp != "") {
                    array_push($new, $temp);
            } else {
                array_push($new, $tok);
            }
        }
        return $new;
    }

    /*
     * @param $s - the string
     * @param $filterPath - file path of the filter
     *
     * @pre - the input string MUST be "clean"!!!
     * @pre - every word in the filter has to be between spaces
     * @pre - punctuation marks must NOT be between spaces
     *
     * Lines starting with "//" in the filter file will not be interpreted
     * (for comments)
     *
     */
    function filterText(&$s,$filterPath){
        $filter = file_get_contents($filterPath);   //reads filter from filepath
        $filter = mb_strtolower($filter);           // not necessary but good to be safe
        $filter = explode("\r\n", $filter);         //explodes by newline

        // Removes comments off filter file
        $new = array();
        foreach($filter as $f){
            if(substr($f, 0, 2) != "//") array_push($new, $f);
        }
        $filter = $new;

        $s = " ".($s)." ";                          // adds whitespace to beginning & end for processing
        $s = mb_strtolower($s);

        $s = str_replace($filter, " ", $s);         //applies filter
        
        $s = preg_replace('/\s+/', " ", $s);        //removes excess whitespace
        $s = trim($s);

        return $s;
    }

    // calls all of the other methods to get a final, processed text
    function processText(&$text){
        $text = cleanText($text);
        $text = filterText($text, "filters/textFilter");
        $tokens = explode(" ", $text);
        // there are multiple characters used as apostrophes
        $tokens = removeAfterCharacter($tokens, "'");
        $tokens = removeAfterCharacter($tokens, "’");
        return $tokens;
    }
?>
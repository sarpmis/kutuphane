<?php
    /*
     * General-purpose text-cleaning method. Takes a string and
     * makes it UTF-8, lowercase, and free of "bad" byte sequences.
     *
     * @param $textToClean - string to be cleaned
     *
     */
    function cleanText($textToClean) {
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
    function removeAfterCharacter($tokens, $char) {
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
    function filterText($s,$filterPath){
        $filter = file_get_contents((__DIR__.'/'.$filterPath));   //reads filter from filepath
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

        $s = str_replace("¬", '', $s);
        
        $s = str_replace($filter, " ", $s);         //applies filter

        
        $s = preg_replace('/\s+/', " ", $s);        //removes excess whitespace
        $s = trim($s);

        return $s;
    }

    // calls all of the other methods to get a final, processed text
    function processText($text){
        $text = cleanText($text);
        $text = filterText($text, "text_temizleme_filtre.txt");
        // sapkalilari degistir
        $text = str_replace("î", 'i', $text);
        $text = str_replace("â", 'a', $text);
        $text = str_replace("û", 'u', $text);

        $tokens = explode(" ", $text);

        // there are multiple characters used as apostrophes
        $tokens = removeAfterCharacter($tokens, "'");
        $tokens = removeAfterCharacter($tokens, "’");
        return $tokens;
    }

?>
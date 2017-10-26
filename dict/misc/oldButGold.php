<?php
	// this is where we dump methods we don't like anymore
	

    function clearText($s){
		$badchars = array(
		'[', ']', '.', ',', '!', '?', '(', ')', '+', '-', '=', ';', ':', '\n', '^', '@',
		'*', '&', '%', '>', '<', '/', '`', '~', '}', '{', '"', '#', '“', '”', '–', chr(0x27),
		' the ', ' a ', ' as ', ' and ', ' for ', ' an ', ' to ', ' when ', ' then ', ' than ',
		' in ', ' on ', ' at ', ' with ', ' of ', ' is ', ' when ', ' would ', ' was ', ' this ', 
		' they ', ' these ', ' them ', ' their ', ' that ', ' such ', ' so ', ' some ', ' since ');


		$s = trim($s);
		$s = iconv("UTF-8", "UTF-8//IGNORE", $s); // drop all non utf-8 characters

		// this is some bad utf-8 byte sequence that makes mysql complain - control and formatting i think
		$s = preg_replace('/(?>[\x00-\x1F]|\xC2[\x80-\x9F]|\xE2[\x80-\x8F]{2}|\xE2\x80[\xA4-\xA8]|\xE2\x81[\x9F-\xAF])/', ' ', $s);
		$s = str_replace($badchars, ' ', $s);

		$s = preg_replace('/\s+/', ' ', $s); // reduce all multiple whitespace to a single space

		// $string = preg_replace('/[\x00-\x1F\x7F]/u', '', $string);
		// print($string);
		// $string = str_replace($badchars, '', $string);
		// print("<br>".$s."<br>");
	}

?>
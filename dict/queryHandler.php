<?php

	class QueryHandler {
		public $dict;
		public $filter;
		public $suffixes;

		function __construct(&$dictionary, $filterPath, $suffixesPath){
	    
	    	$this->dict = $dictionary;
	    	

	    	$filter = file_get_contents($filterPath); //read filter from filepath
			// $filter = mb_strtolower($filter); // gerekli degil gibi
			$filter = str_replace("\r", '',$filter);
			$this->filter = explode("\n", $filter);


	    	$suffixes = file_get_contents($suffixesPath); 
			$suffixes = str_replace("\r", '',$suffixes);
			$this->suffixes = explode("\n", $suffixes);
	    }
	    
	    /*
	     * 	Returns the relevancy score of two tokens in the
	     * 	dictionary passed to the handler.
	     * 	@param $tok1 - first token
	     *	@param $tok2 - second token
	     *
	     */
	    function tokenScore($tok1, $tok2){
	    	$con = $this->dict->findContext($tok1);
	    	if (!is_double($con)){
	    		$wfp = $con->findRelationBinary($tok2);
	    		if (!is_double($wfp)) {
	    			return $wfp->frequency;
	    		} else {
	    			return 0;
	    		}
	    	} else {
	    		return 0;
	    	}
	    }

	    /*
		 *	Takes two "questions" (i.e. token arrays) to
		 *	return the sum of scores in all possible unordered pairs.
		 *	@param $q1 - first question
		 *	@param $q2 - second question
		 *
	     */
	    function queryScore($q1,$q2) {
	    	$scoreSoFar = 0;
	    	//$first = clearText($q1);
	    	//$second = clearText($q2);
	    	for ($i=0; $i < count($q1); $i++) {
	    		for ($j=0; $j < count($q2); $j++) {
	    			$pairScore = $this->tokenScore($q1[$i],$q2[$j]);
	    			$scoreSoFar = $scoreSoFar + $pairScore;
	    			$this->pairPrint($q1[$i],$q2[$j],$pairScore);
	    		}
	    	}
	    	print ($scoreSoFar);
	    	return $scoreSoFar;
	    }
		
		// ~beautifully~ prints a token pair along with its relevancy score
	    function pairPrint($arg1,$arg2,$num) {
	    	print('Score of ('.$arg1.', '.$arg2.') is '.$num." |---| ");
	    }


		/*
		 * text-cleaning method. Takes a string and
		 * makes it UTF-8, lowercase, and filtered by a list of "bad"
		 * words/string sequences. 
		 * clears a query string
		 * @param $s - string to be processed
		 * @param $filter - array of bad words list
		 *
		 */
		function clearQuery($s, $filter) {
			$s = trim($s);
			$s = " ".$s." "; // put spaces in the end because query might start with or
							 // end with a bad string

			$s = iconv("UTF-8", "UTF-8//IGNORE", $s); // drop all non utf-8 characters
			$s = mb_strtolower($s);

			$s = str_replace($filter, ' ', $s);


			$s = preg_replace('/\s+/', ' ', $s); // reduce all multiple whitespace to a single space
			
			$s=trim($s);
			
			return $s;		
		}


		/*
		 * processes 2 strings and runs a query on them.
		 *
		 * @return query result
		 *
		 */
		function query($s1, $s2){
			// clear texts
			$tokens1 = $this->clearQuery($s1, $this->filter);
			$tokens2 = $this->clearQuery($s2, $this->filter);
			// tokenize texts
			$tokens1 = explode(" ", $tokens1);
			$tokens2 = explode(" ", $tokens2);
			// expand token lists
			$tokens1 = $this->expandTokens($tokens1);
			$tokens2 = $this->expandTokens($tokens2);
			// do the query on expanded lists
			return $this->queryScore($tokens1, $tokens2);
		}


		/*
		 * Expands an array of tokens by adding or removing
		 * suffixes from the original token
		 *
		 * @param $tokens - must be an array of tokens
		 * @param $suffixes - array of suffixes to add
		 */
		function expandTokens($tokens){
			$length = sizeof($tokens);
			for($i = 0; $i < $length; $i++){
				$token = $tokens[$i];
				$beforeApos = NULL;

				// check for apostrophe
				$temp = substr($token, 0, strpos($token, "'"));
				if($temp != $token && $temp != "") {
					array_push($tokens, $temp);
					$beforeApos = $temp;
				}
				// add the suffixes
				foreach($this->suffixes as $suff){
					array_push($tokens, $token.$suff);
					if($beforeApos != NULL){ // if there was an apostrophe we
											 // want the root expanded as well
						array_push($tokens, $beforeApos.$suff);
					}
				} 
			}
			return $tokens;
		}

		/*
		 * Takes an array of queries and constructs a table 
		 * from all of its relevancy scores
		 *
		 *
		 */
		function queryTable($queries){
			print("<br>");
			foreach($queries as $i){
				foreach($queries as $j){
					$this->query($i, $j);
				}
				print("<br>");
			}
		}

	}

?>
<?php

	require_once (__DIR__.'/../database_handler.php');
	require_once (__DIR__.'/../database/egitici.php');
	
	class QueryHandler {
		
		public $handler;
		public $egitici;

		function __construct($dbhandler, $trainer){
	    
	    	$this->handler = $dbhandler;
	    	$this->egitici = $trainer;
	    
	    }
	    
	    /*
	     * 	Returns the relevancy score (based on Mutual Information) of two tokens in the
	     * 	dictionaryr.
	     * 	@param $tok1 - first token
	     *	@param $tok2 - second token
	     *
	     */
	    function tokenScoreMI($tok1, $tok2){
	    	$db = $this->handler;
	    	
	    	$size = $db->corpusSize;
	    	
	    	$freq1 = $db->getFrequency($tok1,$tok1);
	    	$freq2 = $db->getFrequency($tok2,$tok2);
	    	
	    	$bigram = $db->getFrequency($tok1,$tok2);
	    	
	    	// print("freq1 = ".$freq1." freq2 = ".$freq2." bigram = ".$bigram."<br>");
	    	if(($freq1 == 0) || ($freq2 == 0)) {
	    		return 0;
	    	} else {
	    		$mi = ($size*$bigram)/($freq1*$freq2);
	    		// $mi = log($mi,2);
	    		return $mi;
	    	}
	    	
	    }

	    function queryToIndices($queryString) {
	    	//filters, cleans, erases suffixes, and encodes the roots into a string of indices
	    	$toks1 = $this->egitici->koklestir($queryString);

	    	//turns the index strings into index arrays
	    	$toks1 = explode("\n",$toks1);
	    	$q1 = array();
	    	foreach ($toks1 as $arr) {
	    		$subarray = explode("&",$arr);
	    		foreach ($subarray as $item) {
	    			array_push($q1, $item);
	    		}
	    	}
	    	$q1 = array_values(array_unique($q1));
	    	//print(json_encode($q1, JSON_UNESCAPED_UNICODE)."<br>");
	    	return $q1;
	    }

	    /*
		 *	Takes two "questions" to
		 *	return the sum of scores in all possible unordered pairs.
		 *  
		 *	@pre - query MUST be converted from a string to an array of dictionary indices (via queryToIndices) !!!
		 *	
		 *	@param $query1 - first question
		 *	@param $query2 - second question
		 *
	     */
	    function queryScore($query1,$query2) {	    	
	    	//calculates the score in a running sum for all pairs
	    	$score = 0;
	    	for ($i=0; $i < count($query1); $i++) { 
	    		for ($j=0; $j < count($query2); $j++) { 
	    			$pairScore = $this->tokenScoreMI($query1[$i],$query2[$j]);
	    			// print($pairScore."<br>");
	    			$score = $score + $pairScore;
	    		}
	    	}
	    	return $score;
	    }

	    /*
	     *	Helper for listOptimizer below. Converts values to index arrays,
	     *	scores all possible query pairs, and returns them in descending order of scores along 
	     *	with the question indices as the keys.
	     *	
	     *	@pre - EACH entry in the assoc. array must be formatted as follows:
	     *			"questionIndex,studentName" => "Is this a question in string form?"
	     *
	     *	@param $questions - associative array of ID+Student, and the questions in string form
	     */
	    function listScore($questions) {
	    	$assocOutput = array();
	    	// $indexedQuestions = array('0,ali' => array(56,345,324453), 
	    	// 						  '5,ahmetcan' => array(3,23423,5435435), 
	    	// 						  '18,zeynep' => array(234,234234,2434), 
	    	// 						  '6,ummetcan' => array(23234,2134234,24354)
	    	// 						  );
	    	
	    	//converts question string into array of word indices
	    	$indexedQuestions = array();
	    	foreach ($questions as $key => $val) {
	    		$indexed = $this->queryToIndices($val);
	    		$indexedQuestions[$key] = $indexed;
	    	}

	    	//iterate through the list to match&score every possible query
	    	$keys = array_keys($indexedQuestions);
	    	for ($i=0; $i < count($indexedQuestions); $i++) {
	    		
	    		$currentQuestion = $indexedQuestions[$keys[$i]];

	    		for ($j=($i+1); $j < count($indexedQuestions); $j++) { 
	    			
	    			$toCompare = $indexedQuestions[$keys[$j]];
	    			$key = $keys[$i].",".$keys[$j];
	    			//$questionPairScore = rand();
	    			$questionPairScore = $this->queryScore($currentQuestion,$toCompare);
	    			$assocOutput[$key] = $questionPairScore;
	    		}
	    	}

	    	//sorts according to values in descending order
	    	arsort($assocOutput);
	    	
	    	return $assocOutput;
	    }


	    /*
	     *	Takes an assoc. array where the keys are question ID and student names (RESPECTIVELY)
	     *	and the values are questions (in string form). Returns optimal matches for this array.
	     *	
	     *	@pre - EACH entry in the assoc. array must be formatted as follows (as in listScore):
	     *			"questionIndex,studentName" => "Is this a question in string form?"
	     *	@param $list - associative array of ID+Student, and the questions in string form
	     */
	    function listOptimizer($list) {
	    	$scoreList = $this->listScore($list);
	    	//eventual output array, array of the questions that have been matched, and array of all indices
	    	$optimalMatches = array();
	    	$matched = array();
	    	$allIndices = array();
	    	
	    	//if neither the index of the first nor second question is in "matched", matches them
	    	//indices[0] and [2] are question indices, [1] and [3] are owners of these questions
	    	foreach ($scoreList as $key => $value) {
	    		$indices = explode(",",$key);
	    		array_push($allIndices, $indices[0]);
	    		array_push($allIndices, $indices[2]);
	    		//print($indices[0].",");
	    		//print($indices[2]."<br>");
	    		if ((!in_array($indices[0],$matched)) && (!in_array($indices[2],$matched)) && !($indices[1] === $indices[3])) {
	    			array_push($matched, $indices[0]);
	    			array_push($matched, $indices[2]);
	    			$optimalMatches[$key] = $value;
	    		}
	    	}
	    	
	    	$allIndices = array_values(array_unique($allIndices));
	    	//print(json_encode($allIndices, JSON_UNESCAPED_UNICODE)."<br>");
	    	
	    	//matches the remaining question to its highest match in odd-numbered scenarios
	    	if (!(count($allIndices) % 2 == 0)) {
	    		$sumAll = 0;
	    		$sumMatched = 0;
	    		for($i = 0; $i < count($allIndices); $i++) {
    				$sumAll += $allIndices[$i];
    				
    				if($i < count($matched)) {
        				$sumMatched += $matched[$i];
					}
				}
				$missing = $sumAll - $sumMatched;
				foreach ($scoreList as $key => $value) {
					$indices = explode(",",$key);
					if (($indices[0] == $missing) || ($indices[2] == $missing) ) {
						$optimalMatches[$key] = $value;
						
						print(json_encode($optimalMatches, JSON_UNESCAPED_UNICODE)."<br>");
						
						return $optimalMatches;
						
					}
				}
	    	}

	    	// print(json_encode($optimalMatches, JSON_UNESCAPED_UNICODE)."<br>");
	    	return $optimalMatches;
	    	
	    }
		
	}


	// $dbh = new DatabaseHandler();
	// $train = new Egitici($dbh, new KokAgaciIsleyici($dbh));
	// $qh = new QueryHandler($dbh, $train);

	//$word1= 6627;
	//$word2= 23343;
	
	//print(json_encode($dbh->wordLookupByNum($word1), JSON_UNESCAPED_UNICODE)."<br>");
	//print(json_encode($dbh->wordLookupByNum($word2), JSON_UNESCAPED_UNICODE)."<br>");
	
	//$score = $qh->tokenScoreMI($word1,$word2);

	//print('Score of ('.$word1.', '.$word2.') is '.$score.'.');

	// $question1 = "Fatih Sultan Mehmet İstanbul'u nasıl fethetti?";
	// $question2 = "1453 yılında Osmanlı ordusu ne kadar güçlüydü?";

	// $question3 = "Bitki hücresinde neler vardır?";
	// $question4 = "Hücre zarı hangi canlı hücrelerinde vardır?";

	// $question5 = "İnsanın temel ihtiyaçları nelerdir?";
	// $question6 = "Beslenme, barınma, giyinme haklarını devlet karşılamalı mıdır?";
	
	//$qscore = $qh->queryScore($question5,$question3);

	// $arr = $qh->listScore(array());
	// print(json_encode($arr, JSON_UNESCAPED_UNICODE)."<br>");
	// $qh->listOptimizer($arr);
	
	//print('RELEVANCY SCORE OF TWO QUESTIONS IS '.$qscore.'!!!');

	// $assoc = array('0,kerem' => $question1,
	// 			   '2,kutay' => $question2,
	// 			   '15,sarp' => $question3,
	// 			   '22,berkay' => $question6,
	// 			   '3,anil' => $question4,
	// 			   '848,hazal' => $question5);
	// $qh->listOptimizer($assoc);
?>
<?php

	require 'wfp.php';

	class Context {
	    public $array = array(); // array of relations (WFPs)
	    public $token; // the word
	    public $count; // total number of occurence of this token
	    
	    function __construct($tok){
	        $this->token = $tok;
	    }
	    
	    function getToken(){
	        return $this->token;
	    }
	    
	    // if an entry with the word doesn't exist, create new WordFrequencyPair, 
	    // else increment the frequency of it by 1
	    function addRelation($wordToAdd){
	    	$relation = $this->findRelation($wordToAdd);

	    	if($relation == NULL) {
	    		array_push($this->array, new WordFrequencyPair($wordToAdd, 1));
	    	} else {
	    		$relation->incrFreq(1);
	    	}
	    }

	    // adds a given WordFrequencyPair to the array.
	    // increments the existing one's frequency if already exists.
	    // ARRAY MUST BE SORTED
	    function addWFPBinary($wfp){
	    	$rel = $this->findRelationBinary($wfp->word);
	    	if(is_int($rel) || is_double($rel)) {
	    		array_splice($this->array, $rel, 0, array($wfp));
	    	} else {
	    		$rel->incrFreq($wfp->frequency);
	    	}
	    }

	    /* 
	     * merges with another context by adding all of
	     * its contents to our array
	     * 
		 * @pre - $other must have the same token
	     */
	    function merge($other){
	    	if($other->token != $this->token){
	    		print("ERROR, TRYING TO MERGE TWO CONTEXTS WITH DIFFERENT TOKENS! <br>\n");
	    		return;
	    	}
	    	foreach($other->array as $wfp){
	    		$this->addWFPBinary($wfp);
	    	}
	    	$this->count += $other->count;
	    }

	    
	    // return the WordFrequencyPair with the given word
	    // search is Linear 
	    // NULL if it doesn't exist
	    // THIS SHOULD ONLY BE USED WHEN BUILDING THE CONTEXT FOR THE
	    // FIRST TIME AND IF THE WFPs ARE NOT SORTED!!! 
	    function findRelation($wordToGet){
	        foreach ($this->array as $pair){
	        	if ($pair->word == $wordToGet){
	        		return $pair;
	        	} 
	        }
	        return NULL;
	    }

	    /* 
	     * finds the relation with the parameter word inside the WFP array
	     * with binary search
	     *
	     * @return the WFP with the given word if exists
	     *			the index of where it should be if it doesn't exist
		 *
		 * @pre ARRAY MUST BE SORTED
	     */ 
	    function findRelationBinary($wordToFind){
	    	$size = sizeof($this->array);
	    	$top = $size;
	    	$bot = 0;
	        while($top >= $bot){
	        	$mid = floor(($top + $bot) / 2);
	        	if ($mid >= $size) return $mid;
	        	$comp = strcmp($wordToFind, $this->array[$mid]->word);
	        	if($comp < 0) $top = $mid - 1;
	        	elseif($comp > 0) $bot = $mid + 1;
	        	else return $this->array[$mid];
	        }
	        if($comp > 0) return $mid+1;
	        else return $mid;
	    }

		function sortWFPsAlphabetical(){
			$func = function($a, $b){
				$cmp = strcmp($a->word, $b->word);
				return $cmp;
			};
			usort($this->array, $func);
		}

		function sortWFPsFreq(){
			$func = function($a, $b){
				if($a->frequency == $b->frequency) return 0;
				else if($a->frequency > $b->frequency) return -1;
				else return 1;
			};
			usort($this->array, $func);
		}



	    //STRING FUNCTIONS
	    function stringifyWFPArray(){
	    	$string = "";
	    	$i = sizeof($this->array);
	    	for($k = 0; $k < $i; $k++){
	    		$string = $string.$this->array[$k].",";
	    	}
	    	return $string;
	    }
	    function __toString(){
	    	return $this->token." :: ".$this->stringifyWFPArray();
	    }
	}


/************************* TESTING SPACE BELOW *********************/

	// $cont = new Context("a");

 //    $cont-> addRelation("b");
 //    $cont-> addRelation("b");
 //    $cont-> addRelation("c");
 //    $cont-> addRelation("asgasg");
 //    $cont-> addRelation("btwet");
 //    $cont-> addRelation("bmm");
 //    $cont-> addRelation("z");

 //    $cont->bubbleWFP();


 //    print($cont."<br>");

 //    $cont->addWFPBinary(new WordFrequencyPair("a", 2));
 //    $cont->addWFPBinary(new WordFrequencyPair("a", 1));
 //    $cont->addWFPBinary(new WordFrequencyPair("ş", 2));
 //    $cont->addWFPBinary(new WordFrequencyPair("ğ", 2));


 //    print($cont."<br>");

?>
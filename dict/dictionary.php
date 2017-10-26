<?php

	require 'context.php';
	require 'helpers.php';

	class Dictionary {
	    public $contexts = array(); // array of contexts
	    public $conSize = 0; 		// size of the array of contexts
	   	public $range = 5;			// context range
	   	public $totalSize = 0;


		public $tokens = array();   // this is deleted once WFPs are added

	    function __construct($path){
	    	if($path != NULL){
		    	$text = file_get_contents($path);

				$text = strtolower($text);
				$this->tokens = processText($text);


				$this->tokensLength = sizeof($this->tokens);

		    	$ordered = $this->tokens;
		    	$this->sortTokens($ordered);
		    	removeDuplicates($ordered);

		    	$this->totalSize += $this->tokensLength;

		    	foreach($ordered as $token) {
		            array_push($this->contexts, new Context($token));
			        }
				$this->setSize();	
			}
	    }

	    function __toString(){
	    	return stringifyArrayGen($this->contexts)."<br>";
	    }
	    

	    function setSize(){
	        $this->conSize = sizeof($this->contexts);
	    }

	    // finds a context via binary search
	    function findContext($token){
	        $top = $this->conSize - 1; 
	        $bot = 0;
	        while($top >= $bot){
	        	$mid = floor(($top + $bot) / 2);
	        	if ($mid >= $this->conSize) return $mid;
	        	$comp = strcmp($token, $this->contexts[$mid]->token);
	        	// print("comparing ".$token." & ".$this->contexts[$mid]->getToken()."    got ".$comp."<br>");
	        	if($comp < 0) $top = $mid - 1;
	        	elseif($comp > 0) $bot = $mid + 1;
	        	else return $this->contexts[$mid];
	        }
	        if($comp > 0) return $mid+1;
	        else return $mid;	    
	    }

	    // adds WFPs to contexts from corpus
	    function addWFP2Contexts(){
	    	for ($i = 0; $i< sizeof($this->tokens); $i++) {
	    		$context = $this->findContext($this->tokens[$i]);
	    		$context->count ++;
	    		$arr = $this->getContext($i, $this->range);
	    		if(is_double($context)){ // THIS SHOULD NEVER HAPPEN, CAN REMOVE THIS IN THE FINAL VERSION FOR EFFICIENCY
	    		// print("Warning, error for token ".$this->tokens[$i]." should go to = ".$context."<br><br>");
	    		} else{
		    		foreach($arr as $word) {
		    				if($word !== '') $context->addRelation($word);
		    		}
	    		}
	    	}
	    	$this->tokens = NULL; // to save memory
	    }

	    /*
	     * adds a given context to the array.
	     * if it already exists, adds the contents of it to the
	     * existing one.
	     * @param $context = context to add
	     * @pre ARRAY MUST BE SORTED!
	     */
	    function addContextBinary($context){
	    	$con = $this->findContext($context->token);
	    	if(is_int($con) || is_double($con)) {
	    		array_splice($this->contexts, $con, 0, array($context));
	    		$this->conSize++;
	    	} else {
	    		$con->merge($context);
	    	}	    	
	    }

	    // returns the tokens within the $range of the token at $index
		function getContext($index, $range){
			$low = $index - $range;
			$high = $index + $range;
			if($low < 0) $low = 0;
			if($high >= $this->tokensLength) $high = $this->tokensLength - 1;

			$array = array();

			while($low <= $high){
				if($this->tokens[$low] != $this->tokens[$index]) array_push($array, $this->tokens[$low]);
				$low ++;
			}
			return $array;
		}

		// sort the WFP arrays of all contexts in the dictionary in alphabetical order
		function sortContextsAlphabetical(){
			foreach($this->contexts as $con){
				$con->sortWFPsAlphabetical();
			}
		}

		// sort the WFP arrays of all contexts in order of decreasing frequency
		function sortContextsFreq(){
			foreach($this->contexts as $con){
				$con->sortWFPsFreq();
			}
		}

		// function to sort tokens using strcmp
		function sortTokens(&$tokens){
			$func = function($a, $b){
				$cmp = strcmp($a, $b);
				return $cmp;
			};
			usort($tokens, $func);
		}

		/*
		 * merges with another dictionary
		 * @pre this dictionary must be in order
		 * @post keeps dictionary ordered
		 *
		 */
		function merge(&$other){
			foreach($other->contexts as $con){
				$this->addContextBinary($con);
			}
			$this->setSize();
			$this->totalSize += $other->totalSize;
			$other=NULL;
		}
	}   
?>
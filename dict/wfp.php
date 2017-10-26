<?php

	class WordFrequencyPair {
	    public $word;
	    public $frequency;
	    
	    function __construct($token, $freq) {
	        $this->word = $token;
	        $this->frequency = $freq;
	    }
	    //WORD GETTER-SETTER
	    function setWord($newWord) {
	        $this->word = $newWord;
	    }
	    function getWord() {
	        return $this->word;
	    }
	   //FREQUENCY GETTER-SETTER
	    function setFreq($newFreq) {
	        $this->frequency = $newFreq;
	    }
	    function incrFreq($by){ //increments frequency by parameter
	    	$this->frequency += $by;
	    }
	    function getFreq() {
	        return $this->frequency;
	    }
	    // STRING FUNCTION
	    function __toString() {
        	return " (".$this->word."-".$this->frequency.")";
        }
	}
	
?>
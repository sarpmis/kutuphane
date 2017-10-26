<?php

	require_once (__DIR__.'/sozluk/alfabe.php');

	// returns true if str1 ends with str2
	function endsWith($str1,$str2){
    	if(mb_substr($str1, -mb_strlen($str2)) == $str2){
    		return mb_substr($str1, 0, mb_strlen($str1)- mb_strlen($str2));
    	} else {
    		return -1;
    	}
	}

	// finds the last character in s1 that is a member of chars
	// -1 if no member of chars exists in s1
	function lastCharOf($s1, $chars){
		$array = preg_split('//u', $s1, -1, PREG_SPLIT_NO_EMPTY);
		$array = array_reverse($array);
		foreach($array as $ch){
			if(in_array($ch, $chars)){
				return $ch;
			}
		}
		return -1;
	}

	// finds the last character in s1 that is a member of chars
	// -1 if no member of chars exists in s1
	function firstCharOf($s1, $chars){
		$array = preg_split('//u', $s1, -1, PREG_SPLIT_NO_EMPTY);
		foreach($array as $ch){
			if(in_array($ch, $chars)){
				return $ch;
			}
		}
		return -1;
	}

	// unlu harf bulundurursa 1 bulundurmazsa 0
	function hasVowel($str){
		if(lastCharOf($str, UNLULER) != -1) return 1;
		else return 0;
	}

	// str'deki unluleri sayar
	function countVowels($str){
		$array = preg_split('//u', $str, -1, PREG_SPLIT_NO_EMPTY);
		$count = 0;
		foreach($array as $char){
			if(in_array($char, UNLULER)) $count++;
		}
		return $count;
	}


	// /*
 //     * Removes characters after the given character on all tokens
 //     *
 //     * @param $tokens - tokens to clear apostrophes
 //     *        $char - character to remove after
 //     *
 //     */
 //    function removeAfterCharacter(&$tokens, $char) {
 //        $new = array();
 //        foreach($tokens as $tok){
 //            $temp = substr($tok, 0, strpos($tok, $char));
 //            if($temp != $tok && $temp != "") {
 //                    array_push($new, $temp);
 //            } else {
 //                array_push($new, $tok);
 //            }
 //        }
 //        return $new;
 //    }


	// verilen kelimenin son unlusu ve ekin ilk unlusunun unlu uyumunu
	// kontrol eder
	// uyuyorsa 1, uymuyorsa 0
	function ekBuyukUnluUyumu($kelime, $ek){
		// bu eklerden biriyle bitiyorsa unlu uyumunu kontrol ediyoruz
		$ekinSonUnlusu = firstCharOf($ek, UNLULER);
		$kelimeninSonUnlusu = lastCharOf($kelime, UNLULER);
		// eger ekte veya kelimede unlu yoksa uyuma uyuyor demektir
		if($ekinSonUnlusu == -1 || $kelimeninSonUnlusu == -1) return 1;
		if(in_array($ekinSonUnlusu, KALIN_UNLULER)){
			if(!in_array($kelimeninSonUnlusu, KALIN_UNLULER)) {
				return 0;
			}
		} else {
			if(!in_array($kelimeninSonUnlusu, INCE_UNLULER)){
				return 0;
			}
		}
		return 1;
	}

	function unluDaralmasi($kok, $ek){
		$ekinIlkHarfi = mb_substr($ek, 0, 1);
		if($ekinIlkHarfi == "u" || $ekinIlkHarfi == "ı"){
			return $kok."a";
		} else if($ekinIlkHarfi == "ü" || $ekinIlkHarfi == "i"){
			return $kok."e";
		}
		return -1;
	}

	// kelimede yumusama olup olamayacagini tespit eder.
	// eger olabilirse sertlestirip dondurur.
	// yumusama yoksa -1
	// @param - aldigi kelimeden son ekin cikarilmis olmasi gerekmektedir
	function yumusamaCheck($kelime){
		// $sondanIkinci = mb_substr($kelime, -2, -1);
		// // eger sondan ikinci unlu degilse yumusama olamaz
		// if(!in_array($sondanIkinci, UNLULER)) return -1;
		$sonHarf = mb_substr($kelime, -1);
		if($sonHarf === "b") {
			return mb_substr($kelime, 0 ,mb_strlen($kelime)-1)."p";
		}
		if($sonHarf === "c") {
			return mb_substr($kelime, 0 ,mb_strlen($kelime)-1)."ç";
		}
		if($sonHarf === "d") {
			return mb_substr($kelime, 0 ,mb_strlen($kelime)-1)."t";
		}
		if($sonHarf === "g") {
			return mb_substr($kelime, 0 ,mb_strlen($kelime)-1)."k";
		}
		if($sonHarf === "ğ") {
			return mb_substr($kelime, 0 ,mb_strlen($kelime)-1)."k";
		}
		return -1;
	}

	
	// iyelik eklerinde eger unlu dusmesi olduysa
	// ekin ilk unlusu dusen unluyle aynidir(???)
	// @param - aldigi kelimeden son ekin cikarilmis olmasi gerekmektedir
	function iyelikUnluDusmesiCheck($kelime, $ek){
		// eger eksiz kelime tek heceli degilse unlu dusmesi yok
		if(countVowels($kelime) != 1) return -1;
		$kelimeninSonHarfi = mb_substr($kelime, -1);
		// eksiz kelime unsuzle bitmiyorsa unlu dusmesi yok
		if(!in_array($kelimeninSonHarfi, UNSUZLER)) return -1;
		// eksiz kelimenin sondan ikinci harfi de unsuz olmali
		if(!in_array(mb_substr($kelime, -2, -1), UNSUZLER)) return -1;

		$ekinIlkUnlusu = firstCharOf($ek, UNLULER);
		return(mb_substr($kelime, 0, -1).$ekinIlkUnlusu.$kelimeninSonHarfi);
	}

	require_once (__DIR__.'/sozluk/istisnalar/unlu_uyumuna_ters_kelimeler.php');
	function unluUyumuIstisnasi($kelime) {
		if(in_array($kelime, UNLU_UYUMUNA_TERS_KELIMELER)) return true;
		else return false;
	}
?>
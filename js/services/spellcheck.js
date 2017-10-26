"use strict";

/*
* Javascript ile yapacagimiz spell check veya sozluk iceren olaylari burada 
* yapalim. 
*
*
*/

myApp.service('spellcheck', ['$http', function($http){

	this.getWord = function(word){
		var data = 'word=' + word;
		return $http({
			method: 'POST',
			url: './php/getWord.php',
			data: data,
			headers:{'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'}
		});
	}

	this.findRoots = function(word){
		var data = 'word=' + word;
		return $http({
			method: 'POST',
			url: './php/findRoots.php',
			data: data,
			headers:{'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'}
		});
	}

}]);
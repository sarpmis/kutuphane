
"use strict";

var myApp = angular.module('giris', ['ngAnimate']);

myApp.controller('girisCtrl', ['$window','$scope', '$http', '$timeout',
							    function($window, $scope, $http, $timeout) {

/*************************** LOGIN FUNCTIONS ******************************/

	$scope.tryLogin = function(){
	    var inputs = 'userData=' + JSON.stringify($scope.user);
	    $http({
	        method:'POST',
	        url:'./php/tryLogin.php',
	        data: inputs,
	        headers:{'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'}
	    }).then(function(res){
	        if(res.data == 1){
	             $window.location.href = "index.html";
	        }
	        else {
	        	$scope.error = true;
	        }
	    });
	};

	$scope.checkLogin = function(){
	  	$http.get("php/checkLogin.php")
	        .then(function(res){
	            if(res.data == 1) {
	            	$window.location.href = "index.html";
	            }
	        });
	};


/***************** STYLE AND ANIMATIONS ************************/

	// SHOW BOTTOM AND TOP PARTS OF PAGE
	$scope.showTopBottom = function(){
		$timeout($scope.showBottom, 600);
		$timeout($scope.showBottomContentsFunct, 600);
		$timeout($scope.showTop, 600);
		$timeout($scope.showTopContentsFunct, 600);
	};

	// ALT DIV ANIMASYON
	$scope.bottom = {
  		  "height" : "0",
  		  "top" : "100vh"
	}

	$scope.showBottom = function() {
		$scope.bottom = {
		  "background-color" : "Transparent",
  		  "height" : "100vh",
  		  "width" : "100vw",
  		  "transition" : "top linear 0.8s",
  		  "position" : "absolute",
  		  "top" : "300px"
		}
	};

	$scope.showBottomContents = false;
	$scope.showBottomContentsFunct = function(){
		$scope.showBottomContents = true;
	}

	$scope.delayedShowBottom = function(){
		$timeout($scope.showBottom, 500);
		$timeout($scope.showBottomContentsFunct, 500);
	};

	//UST DIV ANIMASYON
	$scope.top = {
 		  "height" : "0",
  		  "top" : "-300px"
	}
	$scope.showTop = function() {
		$scope.top = {
		  "background-color" : "#ffb83f",
  		  "height" : "300px",
  		  "width" : "100vw",
  		  "transition" : "top linear 0.8s",
  		  "position" : "absolute",
  		  "top" : "0",
		}
	};

	$scope.showTopContents = false;
	$scope.showTopContentsFunct = function(){
		$scope.showTopContents = true;
	};

	$scope.delayedShowTop = function(){
		$timeout($scope.showTop, 500);
		$timeout($scope.showTopContentsFunct, 500);
	};

	// test counter
    $scope.counter = 0;
    $scope.onTimeout = function(){
        $scope.counter++;
        mytimeout = $timeout($scope.onTimeout,1000);
    }
    var mytimeout = $timeout($scope.onTimeout,1000);

    $scope.stop = function(){
        $timeout.cancel(mytimeout);
    }

}]);
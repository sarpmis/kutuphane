"use strict";

var myApp = angular.module('main', ['ngRoute', 'ngSanitize']);

// route and views
myApp.config(['$routeProvider', function($routeProvider){

    $routeProvider
        .when('/anasayfa', {
            templateUrl: './views/anasayfa.html',
            controller: 'anaCtrl'
        })
        .when('/sorularim', {
            templateUrl: './views/sorularim.html',
            controller: 'sorularCtrl'
        })
        .when('/beta', {
            templateUrl: './views/beta/beta_ana.html',
            controller: 'betaCtrl'
        })
        .otherwise({
            redirectTo: '/beta'
        });
}]);




myApp.controller('anaCtrl', ['$window','$scope', '$http', '$timeout', '$q', '$rootScope', 'spellcheck',
				function($window, $scope, $http, $timeout, $q, $rootScope, spellcheck) {
	
    $scope.buttonPressed = function(){
        var promise = spellcheck.findRoots($scope.soru);
        promise.then(
            function(result){
                $scope.soruSuccess = result.data;
            },
            function(err){
                console.log(err);
            }
        )
    }

    $scope.processText = function(){
        var text = 'text=' + $scope.text;
        $scope.cevap = "baslatiliyor";
        $http({
            method:'POST',
            url:'php/text_isle.php',
            data: text,
            headers:{'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'}
        })
        .then(function(response){
            $scope.cevap = response.data;
        }, function() {
            $scope.cevap = "error";
        });
    }


    $scope.$watch('soru', function() {
        var promise = spellcheck.getWord($scope.soru);
        promise.then(
            function(result){
                $scope.test = result.data;
            },
            function(err){
                console.log(err);
            }
        )
        // $scope.test = spellcheck.getWord($scope.soru);
        // console.log($scope.soru);
    });

	$scope.init = function(){
		$scope.checkLogin();
		var promise = $scope.getUserInfo();
	    promise.then(
				function(v){
					$rootScope.info = v.data;
					$scope.getQuestions();
				},
				function(err){
					$scope.abov = 'error';
				}
			)
	};

/*************************************** USER INFO **************************************/

	$rootScope.checkLogin = function(){
	  	$http.get("php/checkLogin.php")
	        .then(function(res){
	            if(res.data == 0) {
	            	$window.location.href = "giris.html";
	            }
	        });
	};

    $scope.getUserInfo = function () {
		return $http.get("php/getUserInfo.php");
    };


/*************************************** QUERIES ***************************************/

    $scope.getQuestions = function(){
    	var userid = 'userid=' + $scope.info.id;
    	$http({
	        method:'POST',
	        url:'php/getQuestions.php',
	        data: userid,
	        headers:{'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'}
	    })
	    .then(function(response){
            $rootScope.sorular = response.data;
        }, function() {
            $rootScope.sorular = "error fetching data";
        });
    };

    $scope.enterQuestion = function(){
    	var data = 'question=' + $scope.soru + ' &owner=' + $scope.info.id;
        $scope.test = $scope.soru;
    	return $http({
    		method:'POST',
    		url:'php/enterQuestion.php',
    		data: data,
    		headers:{'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'}
    	});
    };

    $scope.enterAndGetQ = function(){
    	var promise = $scope.enterQuestion();
	    promise.then(
				function(v){
					$scope.soruSuccess = 'success';
					$scope.getQuestions();
				},
				function(err){
					$scope.soruSuccess = err.data;
				}
			)
    };

    $scope.removeQuestion = function(questionID){
    	var data = 'questionID=' + questionID;
    	return $http({
    		method: 'POST',
    		url: 'php/removeQuestion.php',
    		data: data,
    		headers:{'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'}
    	});
    }

    $scope.removeAndGetQ = function(questionID){
    	var promise = $scope.removeQuestion(questionID);
    	promise.then(
    			function(v){
    				if(v.data == 1){
    				$scope.soruSuccess = 'basarilisin genc dostum';
    			}else if(v.data == 0){
    				$scope.soruSuccess = 'nabiyon haci';	
    			} else{
    				$scope.soruSuccess = v.data;
    				$scope.test = 'ataturk vs rey mysteroi';
    			}
    			$scope.getQuestions();
    			},
    			function(err){
    				$scope.soruSuccess = err.data;
    			}
    		)
    }
}]);
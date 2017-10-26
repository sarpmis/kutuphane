"use strict";

// this is the directive that we will build the html for

myApp.directive('compileHtml', compileHtml);

function compileHtml($compile) {
    return {
        restrict: 'A',
        link: function (scope, element, attrs) {
            scope.$watch(function () {
                return scope.$eval(attrs.compileHtml);
            }, function (value) {
                element.html(value);
                $compile(element.contents())(scope);
            });
        }
    };
}

myApp.directive('sorular', function() {
    return {
        restrict: 'E',
        template: '<div ng-bind-html="sorularHTML"></div>'
    };
});

angular.module('main').controller('sorularCtrl', ['$scope', '$rootScope',   
								function($scope, $rootScope){

	$scope.css = {
		"background-color" : "blue"

	};

	$scope.testy = '12312';

	var sorular = $rootScope.sorular;
	var sorularHTML = '<h1> testing </h1> <div class="abc">';

	for (var i = 0; i<2; i++) {
		var tekSoru = '<div class="tekSoru"> <div class="soru"> <p>' + sorular[i].question +
			'</p> </div> <div class="sil">sil</div> {{testy}}</div>';
		sorularHTML = sorularHTML + tekSoru;
	}

	sorularHTML = sorularHTML + '</div>';

	$rootScope.sorularHTML = sorularHTML;
	console.log(sorularHTML);

}]);
"use strict";

	myApp.config(['$routeProvider', function($routeProvider){

	    $routeProvider
	        .when('/beta_ogrenci_ekleme', {
	            templateUrl: './views/beta/beta_ogrenci_ekleme.html',
	            controller: 'betaCtrl'
	        })
	        .when('/beta_ogrenciler', {
	            templateUrl: './views/beta/beta_ogrenciler.html',
	            controller: 'betaCtrl'
	        })
	        .when('/beta_ogrenci_sayfasi', {
	            templateUrl: './views/beta/beta_ogrenci_sayfasi.html',
	            controller: 'betaCtrl'
	        })
	        .when('/beta_tum_sorular', {
	            templateUrl: './views/beta/beta_tum_sorular.html',
	            controller: 'betaCtrl'
	        })
	        .when('/beta_eslestirmeler', {
	            templateUrl: './views/beta/beta_eslestirmeler.html',
	            controller: 'betaCtrl'
	        })
	        .when('/beta_eslestirme_goruntuleme', {
	            templateUrl: './views/beta/beta_eslestirme_goruntuleme.html',
	            controller: 'betaCtrl'
	        })


	        // .otherwise({
	        //     redirectTo: '/anasayfa'
	        // });
	}]);

angular.module('main').controller('betaCtrl', ['$scope', '$http', '$rootScope', '$location', '$window', '$q',
							  function ($scope, $http, $rootScope, $location, $window, $q){

    $scope.init = function(){
	    $scope.isim = '';
		$scope.okul = '';
		$scope.sinif = '';
    }


	//************************ TUM OGRENCILER SAYFASI ***********************//
   
    $scope.$watch('ogrenci_giris_basari', function() {
    	if($scope.ogrenci_giris_basari == 1){
    		$scope.giris_cevap = "Ogrenci Basariyla Eklendi!";
    		$scope.isim = '';
    		$scope.okul = '';
    		$scope.sinif = '';
    	} else if ($scope.ogrenci_giris_basari == 3 || 
    		$scope.ogrenci_giris_basari == 0){
    		$scope.giris_cevap = "Ogrenci eklenirken bir hata olustu!";
    	} else {
    		$scope.giris_cevap = '';
    		// console.log($scope.ogrenci_giris_basari);
    	}
	});

    $scope.ogrenciEkle = function(){
    	if($scope.isim === '' || $scope.okul === '' || $scope.sinif === ''){
    		$scope.giris_cevap = 'Tum alanlari doldurmaniz gerekmektedir';
    	} else {
	        var data = 'isim=' + $scope.isim + '&okul=' + $scope.okul + '&sinif=' + $scope.sinif;
	        $scope.cevap = "baslatiliyor";
	        $http({
	            method:'POST',
	            url:'php/beta_php/ogrenci_ekle.php',
	            data: data,
	            headers:{'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'}
	        })
	        .then(function(response){
	            $scope.ogrenci_giris_basari = response.data;
	        }, function() {
	            $scope.ogrenci_giris_basari = 3;
	        });
	    }
	}

	$scope.tumOgrencileriGetir = function(){
        $http({
            method:'POST',
            url:'php/beta_php/tum_ogrenciler.php',
            headers:{'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'}
        })
        .then(function(response){
            $scope.tumOgrenciler = response.data;  
        }, function() {

        });
	}


	$scope.ogrenciSilTiklandi = function(id, isim){
		$scope.silinecekOgrenci = id;
		$scope.silinecekOgrencininIsmi = isim;
		$scope.ogrenciSilmekIstiyorMusunuz = true;
	}

	$scope.evetOgrenciSil = function(){
		$scope.ogrenciyiSil();
		$scope.silinecekOgrenci = null;
		$scope.ogrenciSilmekIstiyorMusunuz = false;
	}

	$scope.hayirOgrenciSilme = function(){
		$scope.silinecekOgrenci = null;
		$scope.ogrenciSilmekIstiyorMusunuz = false;
	}

	$scope.ogrenciyiSil = function(){
		var data = 'id=' + $scope.silinecekOgrenci;
		$http({
            method:'POST',
            url:'php/beta_php/ogrenci_sil.php',
            data: data,
            headers:{'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'}
        })
        .then(function(response){
        	if(response.data == 1){
        		$scope.ogrenci_silme_basari = 'Ogrenci basariyla silindi!';
        		$scope.tumOgrencileriGetir();
        	} else {
        		$scope.ogrenci_silme_basari = 'Ogrenci silinemedi!';
        	}
        }, function() {
        	$scope.ogrenci_silme_basari = "Ogrenci silinemedi!";
        });
	}

	//************************ OGRENCI SAYFASI ***********************//

	$scope.ogrenciSayfasinaGit = function(id){
		$location.path('/beta_ogrenci_sayfasi');
		$window.sessionStorage.currentId = id;
	}

	$scope.ogrenciSorulariGetir = function(){
		$scope.currentId = $window.sessionStorage.currentId;
		var id = 'id='+$scope.currentId;
        $http({
            method:'POST',
            url:'php/beta_php/ogrenci_sorulari_al.php',
            data: id,
            headers:{'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'}
        })
        .then(function(response){
            $scope.sorular = response.data;  
        }, function() {

        });

        $http({
            method:'POST',
            url:'php/beta_php/id_isim.php',
            data: id,
            headers:{'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'}
        })
        .then(function(response){
            $scope.ogrenciIsmi = response.data;  
            // console.log(response.data);
        }, function() {

        });
	}

	// $scope.ogrenciSayfasiRefresh = function(){
	// 	$scope.currentId = $window.sessionStorage.currentId;
	// }

	$scope.soruEkle = function(){
		if($scope.soru.length > 0) {
			$scope.soru = $scope.soru.replace("'", "’");
			var data = 'soru='+ $scope.soru + '&sahip=' + $scope.currentId;
	        $http({
	            method:'POST',
	            url:'php/beta_php/soru_ekle.php',
	            data: data,
	            headers:{'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'}
	        })
	        .then(function(response){
	            $scope.soru_ekleme_basari = response.data; 
	            $scope.ogrenciSorulariGetir();
	        	$scope.soru = ''; 
	        }, function() {
	 			$scope.soru_ekleme_basari = 3;  
	        });
			// console.log($scope.sorular);
		}
	}

	$scope.soruSilTiklandi = function(id){
		$scope.silinecekSoru = id;
		$scope.soruSilmekIstiyorMusunuz = true;
		// console.log(id);
	}

	$scope.evetSoruyuSil = function(){
		$scope.soruyuSil();
		$scope.silinecekSoru = null;
		$scope.soruSilmekIstiyorMusunuz = false;
	}

	$scope.hayirSoruyuSilme = function(){
		$scope.silinecekSoru = null;
		$scope.soruSilmekIstiyorMusunuz = false;
	}

	$scope.soruyuSil = function(){
		var data = 'id=' + $scope.silinecekSoru;
		$http({
            method:'POST',
            url:'php/beta_php/soru_sil.php',
            data: data,
            headers:{'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'}
        })
        .then(function(response){
        	if(response.data == 1){
        		$scope.soru_silme_basari = 'Soru basariyla silindi!';
        		$scope.ogrenciSorulariGetir();
        		$scope.tumSorulariGetir();
        	} else {
        		$scope.soru_silme_basari = 'Soru silinemedi!';
        	}
        }, function() {
        	$scope.soru_silme_basari = "Soru silinemedi!";
        });
	}

	//************************ TUM SORULAR SAYFASI ***********************//

	$scope.tumSorulariGetir = function(){
		$scope.isimler = new Array();
		$http({
            method:'POST',
            url:'php/beta_php/tum_sorular.php',
            headers:{'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'}
        })
        .then(function(response){
        	if(response.data != 0){
        		$scope.tumSorular = response.data;



       //  		var defer = $q.defer();
   				// var promises = [];

   				// for (var i = 0; i < $scope.tumSorular.length; i++){
       //  			console.log("id="+$scope.tumSorular[i].sahip);
       //  			var id = "id=" + $scope.tumSorular[i].sahip;
   				// 	promises.push($http({
			    //         method:'POST',
			    //         url:'php/beta_php/id_isim.php',
			    //         data: id,
			    //         headers:{'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'}
			    //     })
			    //     .then(function(response){
			    //     	// console.log(i);
			    //         $scope.isimler[$scope.tumSorular[i].sahip] = response.data;  
			    //         console.log($scope.isimler[$scope.tumSorular[i].sahip]);
			    //         // console.log(response.data);
			    //     }, function() {

			    //     }));
   				// }

   				// $q.all(promises).then(defer.resolve());
				// var chain = $q.when();
				// for(var i = 0; i < 5; i++) {
				// 	chain = chain.then(function() {
				// 		$scope.isimler[$scope.tumSorular[i].sahip] = response.data;  
				// 		var id = "id=" + $scope.tumSorular[i].sahip;
				// 		return $http({
			 //            method:'POST',
			 //            url:'php/beta_php/id_isim.php',
			 //            data: id,
			 //            headers:{'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'}
			 //        	});
				// 	});
				// }





     //    		for (var i = 0; i < $scope.tumSorular.length; i++){
        			// console.log("id="+$scope.tumSorular[i].sahip);
        			// var id = "id=" + $scope.tumSorular[i].sahip;
					// $http({
			  //           method:'POST',
			  //           url:'php/beta_php/id_isim.php',
			  //           data: id,
			  //           headers:{'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'}
			  //       })
			  //       .then(function(response){
			  //       	// console.log(i);
			  //           $scope.isimler[$scope.tumSorular[i].sahip] = response.data;  
			  //           console.log($scope.isimler[$scope.tumSorular[i].sahip]);
			  //           // console.log(response.data);
			  //       }, function() {

			  //       });
     //    		}
        	} else {

        	}

        }, function() {

        });
	}


	//************************ ESLESTIRME (ANASAYFA) ***********************//

	$scope.eslestirTiklandi = function(){
		$scope.eslestirmeIstiyorMusunuz = true;
	}

	$scope.evetEslestir = function(){
		$scope.eslestirmeIstiyorMusunuz = false;
		$scope.eslestir();
	}

	$scope.hayirEslestirme = function(){
		$scope.eslestirmeIstiyorMusunuz = false;
	}

	$scope.eslestir = function(){
		$http({
            method:'POST',
            url:'php/beta_php/eslestir.php',
            headers:{'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'}
        })
        .then(function(response){
			$http({
	            method:'POST',
	            url:'php/beta_php/eslestirme_isle.php',
	            headers:{'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'}
	        })
	        .then(function(response){
	        	console.log("isleme bitti");
	        }, function() {

	        });
        	console.log("eslestirme bitti");
        }, function() {

        });
	}

	//*********************** ESLESTIRMELER SAYFASI *************************//

	$scope.eslestirmeleriGetir = function(){
		$http({
            method:'POST',
            url:'php/beta_php/eslestirmeleri_getir.php',
            headers:{'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'}
        })
        .then(function(response){
        	$scope.tumEslestirmeler = response.data;
        }, function() {
        	console.log("Eslestirme getirirken hata!");
        });
	}

	$scope.eslestirmeGoruntule = function(eslestirme){
		$window.sessionStorage.secilenEslestirme = eslestirme;
		$window.location = "#!/beta_eslestirme_goruntuleme";
	}



	//******************* ESLESTIRME GORUNTULEME SAYFASI ********************//


	$scope.eslestirmeyiGetir = function(){
		$scope.secilenEslestirme = $window.sessionStorage.secilenEslestirme;
		var data = "filename=" + $scope.secilenEslestirme;
		$http({
            method:'POST',
            url:'php/beta_php/eslestirme_oku.php',
            data: data,
            headers:{'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'}
        })
        .then(function(response){
        	$scope.eslestirmeData = response.data;
        	// console.log($scope.eslestirmeData);
        }, function() {
        	console.log("Eslestirme getirirken hata!");
        });
	}

}]);
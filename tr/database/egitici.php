<?php

	require_once (__DIR__.'/text_temizleme.php');
	require_once (__DIR__.'/../kok_agaci/kok_agaci_isleyici.php');
	require_once (__DIR__.'/../database_handler.php');

	// require_once (__DIR__.'/egitici_yardimcilar.php');

	const RANGE = 100;
	const TOKEN_ARA_SEMBOL = "\n"; 

	class Egitici {

		private $dbHandler;
		private $agacIsleyici;

		function __construct($dbhandler, $agacIsleyici){
			$this->dbHandler = $dbhandler;
			$this->agacIsleyici = $agacIsleyici; 
		}

		function koklestir($text){
			$str = '';
			$bulunamayanlar = file_get_contents((__DIR__."/bulunamayanlar.txt"));
			// $bulunamayanlar = '';
			$tokens = processText($text);
			foreach($tokens as $tok){
				$response = $this->agacIsleyici->kokBul($tok);
				$count = 0;
				if(empty($response)){
					if($tok != '') $bulunamayanlar = $bulunamayanlar.TOKEN_ARA_SEMBOL.$tok;
				} else {
					foreach($response as $res){
						if ($count == 0){
							$str = $str.TOKEN_ARA_SEMBOL.$res;
						} else {
							$str = $str."&".$res;
						}
						$count++;
					}
				}
			}
			file_put_contents((__DIR__."/bulunamayanlar.txt"), $bulunamayanlar);
			$this->bulunamayanlari_isle();
			return mb_substr($str, 1);
		}

		// koklestirilmis bir string'i database'e ekler
		// koklestir bize soyle bir string veriyor : 
		// (yeni satirlar yerine TOKEN_ARA_SEMBOL olacak,
		// bu ornekte o sembol "\n" olmus oluyor)
		// 
		// sınıf 18999
		// okul 16058
		// yer 23273
		// tarif 20722
		// kul 12680&kullanmak 12692
		// edinmek 5745&et 6360&etmek 6394
		//
		function toDB($str){
			// $counter = 0;
			$array = explode(TOKEN_ARA_SEMBOL, $str);
			$size = sizeof($array);
			$this->dbHandler->incrementCorpusSize($size);
			// print(json_encode($array, JSON_UNESCAPED_UNICODE));
			for($i = 0; $i < $size; $i++){
				$ekleYecekler = explode("&", $array[$i]);
				for($k = 1; $k < RANGE; $k++){
					if($i+$k < $size){
						$ekleNecekler = explode("&", $array[$i+$k]);
						foreach($ekleYecekler as $ekleYecek){
							foreach($ekleNecekler as $ekleNecek){
								if($ekleYecek !== $ekleNecek){
									$this->dbHandler->updateFrequency($ekleYecek, $ekleNecek, 1);
									// $counter ++;
								}
							}
						}
					}
				}
				// her kelime kendini ekliyor
				foreach($ekleYecekler as $ekleYecek){
					// print($kelime_num_ekleYecek[0]." ve ".$kelime_num_ekleYecek[0]."<br>");
					$this->dbHandler->updateFrequency($ekleYecek, $ekleYecek, 1);
					// $counter ++;
				}
			}
			// print($counter. " adet veri girildi <br>");
		}	

		function bulunamayanlari_isle(){
			$bulunamayanlar = file_get_contents((__DIR__."/bulunamayanlar.txt"));
			$array = explode(TOKEN_ARA_SEMBOL, $bulunamayanlar);
			sortTokens($array);
			removeDuplicates($array);
			$str = '';
			foreach ($array as $bulunamayan){
				$str = $str."\n".$bulunamayan;
			}
			$str = mb_substr($str, 0, -1);
			file_put_contents((__DIR__."/bulunamayanlar_sirali_unique.txt"), $str);
			return $str;
		}
	}

// $str = "Sınıfınızın okuldaki";;

	// $str = "Sınıfınızın okuldaki yerini tarif ediniz.Bunun için altında, üstünde, sağında, solunda, önünde gibi kavramları kullanınız. Sınıfta nerede oturuyorsunuz? Aşağıdaki resmi inceleyiniz. Sınıfta bulunan araç ve gereçlerin neler olduğunu söyleyiniz. Sınıfımızda çeşitli eğitim-öğretim araç ve gereçleri bulunur. Ayrıca şeref köşesi yer alır. Şeref köşesi; Türk bayrağı, İstiklâl Marşı, Atatürk resmi ve Atatürk’ün Gençliğe Hitabesi’nden oluşur. Oturak Sıra Öğretmen bilgisayarı Yazı tahtası Projeksiyon Projeksiyon perdesi Çöp kovası Pano Askılık Öğretmen Dolap masası ve sandalyesi Sınıfınızda hangi araç ve gereçler var? Bu araç ve gereçleri göstererek adlarını söyleyiniz. Şeref köşesi Okuldaki “İlköğretim Haftası” ile ilgili afişler niçin asılmış olabilir? Okulların açıldığı ilk hafta, “İlköğretim Haftası” etkinlikleri yapılır. Bu haftada, çeşitli etkinliklerle okumanın önemi anlatılır. Şiirler okunur. Şarkılar söylenir. Panolarda özlü sözler sergilenir. Okulunuzda,“İlköğretim Haftası” ile ilgili neler yapıldığını ailenize anlatınız. Okul, sınıfların yanı sıra çeşitli bölümlerden oluşur. Bu bölümleri ve buralarda çalışanların görevlerinin neler olduğunu öğrenelim. Evinizin bölümleri nelerdir? Ben, okulun müdürüyüm. Yardımcılarımla birlikte okulu yönetirim. Çocuklar, burası müdür odası. İçeri girip ziyaret edelim. Çocuklar, burası müdür yardımcısı odası. Ben, okul ve öğrenci işlerini yürütmekle görevliyim. Müdür odası Müdür yardımcısı odası Okulda müzik, resim ve bilim sınıfları ile kütüphane, spor salonu gibi birimler de bulunur. Okulda öğretmenler odası ve rehberlik servisi de bulunur. Öğretmenler odası Rehberlik servisi Resim sınıfı Biz, sizin eğitim ve öğretiminizden sorumluyuz. Ben rehber öğretmenim. Öğrencilere, duygusal sorunlarında yardımcı olurum. Kütüphane Spor salonu";

	// $str = "Aşağıdaki resmi inceleyiniz. Gördüklerinizi anlatınız. İlk kez karşılaşan insanlar birbiriyle tanışır. Herkes adını ve soyadını söyleyerek kendini tanıtır. Benim adım, Sevgi Çiçek. Sizin öğretmeninizim. Benim adım, Doruk Kaya. Sıra arkadaşınıza adını ve soyadını sorunuz. 1. Siz de sınıfta kendinizi arkadaşlarınıza ve öğretmeninize tanıtınız. 2. Ailenize, sınıftaki tanışmanızı anlatınız. Okulun hangi şubesinde öğrenim görüyorsunuz? Aşağıdaki resmi inceleyiniz. Deniz’in sınıfının hangisi olduğunu resimde gösteriniz. Sınıfımız, merdivenlerden çıkınca sağ taraftadır. Solunda 1A, sağında 2C sınıfı vardır. Kütüphane sınıfımızın karşısındadır. Sınıfınızın okuldaki yerini tarif ediniz. Bunun için altında, üstünde, sağında, solunda, önünde gibi kavramları kullanınız. Sınıfta nerede oturuyorsunuz? Aşağıdaki resmi inceleyiniz. Sınıfta bulunan araç ve gereçlerin neler olduğunu söyleyiniz. Sınıfımızda çeşitli eğitim-öğretim araç ve gereçleri bulunur. Ayrıca şeref köşesi yer alır. Şeref köşesi; Türk bayrağı, İstiklâl Marşı, Atatürk resmi ve Atatürk’ün Gençliğe Hitabesi’nden oluşur. Oturak Sıra Öğretmen bilgisayarı Yazı tahtası Projeksiyon Projeksiyon perdesi Çöp kovası Pano Askılık Öğretmen Dolap masası ve sandalyesi Sınıfınızda hangi araç ve gereçler var? Bu araç ve gereçleri göstererek adlarını söyleyiniz. Şeref köşesi Okuldaki “İlköğretim Haftası” ile ilgili afişler niçin asılmış olabilir? Okulların açıldığı ilk hafta, “İlköğretim Haftası” etkinlikleri yapılır. Bu haftada, çeşitli etkinliklerle okumanın önemi anlatılır. Şiirler okunur. Şarkılar söylenir. Panolarda özlü sözler sergilenir. Okulunuzda,“İlköğretim Haftası” ile ilgili neler yapıldığını ailenize anlatınız. Okul, sınıfların yanı sıra çeşitli bölümlerden oluşur. Bu bölümleri ve buralarda çalışanların görevlerinin neler olduğunu öğrenelim. Evinizin bölümleri nelerdir? Ben, okulun müdürüyüm. Yardımcılarımla birlikte okulu yönetirim. Çocuklar, burası müdür odası. İçeri girip ziyaret edelim. Çocuklar, burası müdür yardımcısı odası. Ben, okul ve öğrenci işlerini yürütmekle görevliyim. Müdür odası Müdür yardımcısı odası Okulda müzik, resim ve bilim sınıfları ile kütüphane, spor salonu gibi birimler de bulunur. Okulda öğretmenler odası ve rehberlik servisi de bulunur. Öğretmenler odası Rehberlik servisi Resim sınıfı Biz, sizin eğitim ve öğretiminizden sorumluyuz. Ben rehber öğretmenim. Öğrencilere, duygusal sorunlarında yardımcı olurum. Kütüphane Spor salonu TEMİZLİK ODASI 1. Öğretmeninizle birlikte okulunuzu geziniz. Okulunuzda hangi birimler olduğunu belirleyiniz. Bu kısımlarda kimlerin görevli olduğunu ve onların görevlerini öğreniniz. 2. Okulunuzun birimlerini gezerken kimleri tanıdığınızı ailenize anlatınız. Onlara okulun hangi birimlerinden nasıl yararlanacağınızı açıklayınız. Ben okulda hizmetli olarak çalışıyorum. Okulun temizliği ve kışın ısıtılmasıyla ilgileniyorum. Ben okulun kantin görevlisiyim. İşim, size sağlıklı yiyecek ve içecekler satmaktır. Temizlik odası Kantin Teneffüste öğrencilerden nöbetçi öğretmenler sorumludur. Gerektiğinde onlara yardımcı olurlar. 1A sınıfının öğrencileri sınıf kurallarını belirlemeye çalışıyorlar. Bu kuralların neler olabileceğini söyleyiniz. Derste, söz almadan konuşursak ne olur? Tartışınız. Derste, öğretmenimizden söz alarak konuşmalıyız. Sınıfta uyulması gereken bazı kuralların olması neden önemlidir? Tartışınız. 1. Siz de sınıfınızda hep birlikte kendi kurallarınızı belirleyiniz. Bunun için kendi düşüncelerinizi sınıfa öneriniz. Önerilen kuralları öğretmeniniz tahtaya yazsın. Çoğunluğun benimsediği önerileri seçerek sınıfınızın kurallarını oluşturunuz. 2. Sınıfınızda uymanız gereken kuralların neler olduğunu ailenize anlatınız. Sınıf eşyalarını özenli kullanmalıyız. Sınıfımızı temiz tutmalıyız. Arkadaşlarımıza, onların eşyalarına zarar vermemeliyiz. 4 Ekim, “Hayvanları Koruma Günü”dür. Yapılan çeşitli etkinliklerle hayvanların niçin korunması gerektiği anlatılır. Bunun için neler yapılması gerektiği tartışılır. Hayvan konulu şiirler okunur, şarkılar söylenir. Okul açıldığında hangi haftayı kutladınız? 1. En çok sevdiğiniz hayvan hangisidir? Bu hayvanla ilgili duygu ve düşüncelerinizi arkadaşlarınıza anlatınız. 2. Hayvanlarla ilgili bir şiir, şarkı veya özlü söz ezberleyiniz. Sınıfta arkadaşlarınıza okuyunuz. 3. Sıcak yaz günlerinde sokak hayvanları susuz kalabilmektedir. Bu hayvanlar için ne yapabilirsiniz? Tartışınız. İpek ve Çınar okula servis aracıyla gidip okuldan bu araçla geliyorlar. Güvenliğimiz için ne yapmamız gerektiğini onlardan dinleyelim. Bazılarımız evden okula, okuldan eve yürürüz. Sokak ve caddelerden geçeriz. Yolda, tanıdığımız ve tanımadığımız kimselerle karşılaşırız. Servis aracına sırayla binmeliyiz. Emniyet kemerimizi bağlamalıyız. Sürücünün dikkatini dağıtmamak için gürültü yapmamalıyız. Araç durduktan sonra emniyet kemerimizi açıp evimizin önünde inmeliyiz. Ailemizden biri gelinceye kadar servis görevlisinin yanından ayrılmamalıyız. Okula gelirken ve okuldan dönerken uymamız gereken trafik kuralları vardır. Sizce bunlar neler olabilir? Sokakta tanımadığımız kişilerin verdiği hediye, yiyecek gibi şeyleri geri çevirmeliyiz. Tekliflerini kabul etmemeliyiz. Okul yolunda karşıdan karşıya geçerken nereleri kullanmalıyız? Aşağıdaki resimleri inceleyerek açıklayınız. Kaldırımları kullanırken dikkatli olmalıyız. Kaldırımın taşıt trafiğine uzak tarafından yürümeliyiz. Kaldırımda koşmamalı ve oyun oynamamalıyız. Buralarda ağaç, çukur ve levhalara dikkat etmeliyiz. Okul yolunda karşıdan karşıya geçerken neden yaya geçitlerini kullanmamız gerekiyor? Ailenize anlatınız. Resimleri ve ilgili açıklamaları inceleyiniz. Okul kurallarının neler olduğunu belirleyiniz. Merdivenlerde sağ taraftan inip çıkmalıyız. Hayır. Prizlere ve kablolara dokunmak tehlikeli olabilir. Fişi, prize takalım mı? Okul eşyalarını özenli kullanmalıyız. Can güvenliğimiz için okulda da uymamız gereken kurallar vardır. Sizce bunlar neler olabilir? Bahçede böyle hızlı koşmamalıyız. Çarpma, düşme ve yaralanmalara neden olabiliriz. Can güvenliğimiz için pencereden sarkmamalıyız. 3-4 kişilik gruplar oluşturunuz. Okulda uymanız gereken kuralları belirleyiniz. Her grup okul kurallarından birini seçsin. Seçtikleri kuralın neden gerekli olduğunu, bu kurala uyulmadığında yaşanabilecek sorunları tartışsın. Bu sorunları sınıfta diğer gruplara açıklasınlar. Okulun içi ve bahçesi, her yer bizim çevremizdir. Sağlığımız için çevremizi temiz tutmamız gerekir. Çöplerimizi her yerde çöp kutularına atmalıyız. Her bağımsız ülkenin bir bayrağı vardır. Bayraklar ülkeleri ve milletleri temsil eder. Bayrağımızın adı, Türk bayrağıdır. Okul, hastane gibi devlet binalarında bayrağımız devamlı dalgalanır. Her zaman her yerde bayrağımıza saygı gösteririz. Çünkü o, devletimizin simgesidir. Ulusumuzun bağımsız ve özgür olduğunun bir göstergesidir. Bayrak törenlerinde, İstiklâl Marşı söylenirken insanlar nasıl davranıyor? Gözlemlerinizi açıklayınız. Bayrağımızı, törenlerde hazır ol durumunda ayakta selamlarız. Törenlerde millî marşımız olan İstiklâl Marşı’nı da söyleriz. İstiklâl Marşı söylenirken ayakta, hazır ol durumuna geçeriz. Bu, ona olan saygımızı ve verdiğimiz önemi gösterir. Çünkü o, birlik ve beraberliğimizi sağlayan ortak bir değerdir. Uluslararası spor yarışmalarında sporcularımız ödül aldığında bayrağımız göndere çekilir. Millî marşımız okunur. Böyle bir durumda heyecanlanır ve gururlanırız. 1. Bayrak törenlerinde, İstiklâl Marşı söylenirken nasıl davranmamız gerekir? Açıklayınız. 2. Bayrak töreninde aşağıdaki öğrencilerden hangisinin davranışı yanlıştır? Öğrencinin yanındaki kutucuğa “X” işareti çizerek gösteriniz. Okulda resimlerdeki gibi durumlarla karşılaşabiliriz. Böyle durumlarda okul çalışanlarından yardım istemeliyiz. Öğretmenimizin karşılaması mümkün olmayan bir isteğimiz olabilir. Bu durumda öğretmenimizle birlikte müdür yardımcısına veya müdüre gidebiliriz. Hemen ailene haber verelim. Beslenme çantanızı evde unuttuğunuzu düşününüz. Bu durumda kimden yardım istersiniz? Öğretmenim karnım çok ağrıyor. Eve gidebilir miyim? Öğretmenim, sınıfımıza projeksiyon istiyoruz. Müdür Servisi kaçırdığınızı ve öğretmeninizin gittiğini düşününüz. Bu durumda okulda kimden yardım istemelisiniz? Arkadaşlarınızla tartışınız. Nöbetçi öğretmene haber vermeliyim. Aşağıdaki diyalogları ve resimleri inceleyiniz. Hangi nezaket sözcüklerinin kullanıldığını belirleyiniz. Günaydın! Özür dilerim öğretmenim. Geç kaldım. İzin verirseniz derse girebilir miyim? Lütfen baba. Çizgi film kanalını açalım. Teşekkür ederim babacığım. Birinden bir istekte bulunurken hangi nezaket sözcüklerini kullanıyorsunuz? Tamam. Fatma, çıkıp oyun oynayalım mı? Üzgünüm Doruk. Cevabım hayır olacak. Çünkü önce bu resmi bitirmem gerekiyor. Çevremizdekilerle iyi iletişim kurmamız gerekir. Böylece isteklerimizi, düşüncelerimizi daha iyi ifade edebiliriz. Bunun için “Lütfen.”, “Teşekkür ederim.”, “Özür dilerim.”, “Üzgünüm.”, “Elinize sağlık.”, “İzninizle.” gibi nezaket sözcüklerinden yararlanırız. Bunlara siz neler ekleyebilirsiniz? Hoşumuza gitmeyen veya istemediğimiz bir durum karşısında “hayır” sözcüğünü rahatça kullanmalıyız. Ancak “Hayır.” derken bile yine nazik bir dil kullanmalıyız. Aile içi iletişimde en çok kullandığınız nezaket sözcükleri hangileridir? Bu sözcükleri hangi durumda kullanıyorsunuz? Canlandırma yaparak arkadaşlarınıza anlatınız. Sağlıklı bir iletişim için bazı dinleme kurallarına uymak gerekir. Örneğin konuşmacının sözü kesilmemeli, dikkati dağıtılmamalıdır. ";




 //    function convert($size)
 //    {
 //        $unit=array('b','kb','mb','gb','tb','pb');
 //        return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
 //    }

	// $db = new DatabaseHandler();
	// $egitici = new Egitici($db, new KokAgaciIsleyici($db));
	// // $egitici->bulunamayanlari_isle();
	// print($egitici->koklestir("ve de bu burada"));

	// // print($str."<br><br>");
	// // print($kokler."<br><br>");

	// print("Kelime sayisi = ".sizeof(explode(" ", $str))."<br>");

	// // koklestirme
 //    $memory_pre = memory_get_usage();
 //    $time_pre = microtime(true);
 //    // execute code here
	// $kokler  = $egitici->koklestir($str);
 //    // 
 //    $time_post = microtime(true);
 //    $memory_post = memory_get_usage();
 //    $exec_time = $time_post - $time_pre;
 //    $extra_mem = $memory_post - $memory_pre;
 //    print("Koklestirme zamani : ".$exec_time."<br>");
 //    print("Koklestirmeden eksta hafiza : ".convert($extra_mem)."<br><br>");

 //    // print($kokler);
 //    // db'ye atma
 //    $memory_pre = memory_get_usage();
 //    $time_pre = microtime(true);
 //    // execute code here
	// $egitici->toDB($kokler);
 //    // 
 //    $time_post = microtime(true);
 //    $memory_post = memory_get_usage();
 //    $exec_time = $time_post - $time_pre;
 //    $extra_mem = $memory_post - $memory_pre;
 //    print("Database'e atma zamani : ".$exec_time."<br>");
 //    print("Ekstra hafiza : ".convert($extra_mem)."<br><br>");


?>
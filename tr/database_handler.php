<?php
	class DatabaseHandler{
		public $server = 'localhost';
		public $username = 'root';
		public $password = '';
		public $database = 'test';
		public $tableName = 'smolBoy';
		public $conn = NULL;

		public $corpusSize;

		public $error_log;

		function __construct(){
			try{
				$this->conn = new PDO("mysql:host=$this->server; dbname=$this->database;", $this->username, $this->password);
				$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			} catch(PDOException $e) {
				die ("Database connection failed: " . $e->getMessage());
			}

			$this->corpusSize = file_get_contents(__DIR__."/corpus_size.num");

			$this->error_log = "database_handler error log \n\n";



			// try{
			// 	$sql = "ALTER TABLE sozluk CHARACTER SET utf8 COLLATE utf8_bin;";
			// 	$records = $this->conn->prepare($sql);
			// 	$records->execute();
			// 	print(json_encode($records->fetch(PDO::FETCH_ASSOC)));
			// 	} catch (PDOException $e){
			// 	die ($sql."<br>".$e->getMessage());
			// }

			

			// try{
			// 	$sql = "SELECT default_character_set_name FROM information_schema.SCHEMATA S WHERE schema_name = 'test';";
			// 	$records = $this->conn->prepare($sql);
			// 	$records->execute();
			// 	print(json_encode($records->fetch(PDO::FETCH_ASSOC)));
			// 	} catch (PDOException $e){
			// 	die ($sql."<br>".$e->getMessage());
			// }




			// try{
			// 	$sql = "SELECT CCSA.character_set_name FROM information_schema.`TABLES` T, information_schema.`COLLATION_CHARACTER_SET_APPLICABILITY` CCSA WHERE CCSA.collation_name = T.table_collation AND T.table_schema = 'test' AND T.table_name = 'sozluk';";
			// 	$records = $this->conn->prepare($sql);
			// 	$records->execute();
			// 	print(json_encode($records->fetch(PDO::FETCH_ASSOC)));
			// 	} catch (PDOException $e){
			// 	die ($sql."<br>".$e->getMessage());
			// }
		}

		// 
		function createTable(){
			if($this->conn == NULL) echo("Connection is not set!");
			try{
				$sql = "CREATE TABLE smolBoy (
				key1 INT UNSIGNED,
				key2 INT UNSIGNED,
				PRIMARY KEY (key1, key2),
				frequency INT)";

				$this->conn->exec($sql);
				echo('Table created successfully');
			} catch (PDOException $e){
				$this->error_log = $this->error_log.$sql."\n".$e->getMessage()."\n\n";
				file_put_contents('database_handler_error_log', $this->error_log);
			}
		}

		function addElements($howMany){
			for($i = 101; $i<$howMany; $i++){
				for($j = 101; $j<$howMany; $j++){
					try{
						$sql = "INSERT INTO BigBoy (key1, key2, frequency)
								VALUES (".$i.",".$j.",0)";
						$this->conn->exec($sql);
					} catch (PDOException $e){
						$this->error_log = $this->error_log.$sql."\n".$e->getMessage()."\n\n";
						file_put_contents('database_handler_error_log', $this->error_log);
					}
				}
			}
		
		}

/************************ RELATIONS TABLE OPERATIONS ************************/
		/*
		 * increases the value of the frequency 
		 * at the position given by $i and $j by $value
		 *
		 * creates a new DB item if it doesn't exist
		 *
		 */
		function updateFrequency($a, $b, $value){
			if($a >= $b){
				$i = $b;
				$j = $a;
			} else {
				$i = $a;
				$j = $b;
			}
			try{
				$sql = "INSERT INTO frekanslar (key1, key2, frequency)
						VALUES ($i, $j, frequency + $value)
						ON DUPLICATE KEY UPDATE frequency = frequency + $value";
				$this->conn->exec($sql);
			} catch (PDOException $e){
				$this->error_log = $this->error_log.$sql."\n".$e->getMessage()."\n\n";
				file_put_contents('database_handler_error_log', $this->error_log);
			}
		}

		function getFrequency($a, $b){
			if($a >= $b){
				$i = $b;
				$j = $a;
			} else {
				$i = $a;
				$j = $b;
			}
			try{
				$sql = "SELECT * FROM frekanslar
						WHERE key1 = $i AND key2 = $j";
				$records = $this->conn->prepare($sql);
				$records->execute();
				$results = $records->fetch(PDO::FETCH_ASSOC);
			} catch (PDOException $e){
				die ($sql."<br>".$e->getMessage());
			}
			if(empty($results)) {
				// print('getFrequency could not find any results for '.$a.' and '.$b."<br>");
				return 0;
			}
			else {
				// print('getFrequency returning '.$results['frequency'].' for '.$a.' and '.$b."<br>");
				return $results['frequency'];

			}
		}

		function incrementCorpusSize($increment){
			$this->corpusSize = $this->corpusSize + $increment;
			file_put_contents((__DIR__."/corpus_size.num"), $this->corpusSize);
		}



/************************** DICTIONARY OPERATIONS **************************/		
		
		/*
		 * Database'teki sozluk tablosuna yeni bir kelime ekler. 
		 * 
		 *
		 * @param $array=(isim, fiil, sifat, sayi, ozel, zamir, yum, ters, dus, yal, gen, cift, zaman)
		 * array'in elemanlari 1 veya 0 oluyor kelime ozelligine gore. mesela array 
		 * (1,0,0,0,0,0,1,0,0,0,0,0,0) ise bu yumusama geciren bir isimdir
		 */
		function addToDict($word, $array){
			try{
				$sql = "INSERT INTO sozluk (kelime, isim, fiil, sifat, sayi, ozel, zamir, yumusama, ters, dusme, yalin, genis, cift)
						VALUES ('$word', $array[0], $array[1], $array[2], $array[3], $array[4], $array[5], $array[6], $array[7], $array[8], $array[9], $array[10], $array[11])";
				$this->conn->exec($sql);
			} catch (PDOException $e){
				$this->error_log = $this->error_log.$sql."\n".$e->getMessage()."\n\n";
				file_put_contents('database_handler_error_log', $this->error_log);
			}
		}

		/* 
		 * Returns the row associated with the given word index
		 * 
		 * @param - $num : index of word to look for
		 * @return = false : if word is not in database
		 */
		function wordLookupByNum($num){
			try{
				$sql = "SELECT * FROM sozluk WHERE num = $num";
				$records = $this->conn->prepare($sql);
				$records->execute();
				$results = $records->fetch(PDO::FETCH_ASSOC);
	 		} catch (PDOException $e){
				$this->error_log = $this->error_log.$sql."\n".$e->getMessage()."\n\n";
				file_put_contents('database_handler_error_log', $this->error_log);
			}
			return $results;
		}

		/* 
		 * Returns the row associated with the given word
		 * 
		 * @param - $word : (string) word to look for
		 * @return = false : if word is not in database
		 */
		function wordLookupByWord($word){
			$array = array();
			try{
				$sql = "SELECT * FROM sozluk WHERE '$word'  = kelime";
				$records = $this->conn->prepare($sql);
				$records->execute();
				while($results = $records->fetch(PDO::FETCH_ASSOC)){
					array_push($array, $results);
				}		
	 		} catch (PDOException $e){
				$this->error_log = $this->error_log.$sql."\n".$e->getMessage()."\n\n";
				file_put_contents(__DIR__.'/database_handler_error_log.txt', $this->error_log);
			}
			return $array;
		}
	}

	// $db = new DatabaseHandler();
	// print($db->getFrequency(16915,22622));
?>
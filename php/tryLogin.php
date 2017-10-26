<?php
	session_start();
	require 'database.php';

	if(isset($_POST['userData'])){
		$user = json_decode($_POST['userData']);
		$email = $user->email;
		$password = $user->password;

		if(!empty($email) && !empty($password)){

		 	$records = $conn->prepare('SELECT id,email,password FROM kullanicilar WHERE email = :email');
		 	$records->bindParam(':email', $email);
		 	$records->execute();
		 	$results = $records->fetch(PDO::FETCH_ASSOC);

		 	if(count($results) > 0 && $password == $results['password']) {
		 		$_SESSION['user_id'] = $results['id'];
		 		echo 1;
		 	} else{
		 		echo 0;
		 	}
		 	
		} else {
		echo 2;
		}
	}
?>
<?php
	require 'database.php';

	try{
		if(isset($_POST['question']) && isset($_POST['owner'])){
			$sql = "INSERT INTO sorular (id, owner, question, category, showq) VALUES (NULL,".$_POST['owner'].",'".$_POST['question']."' , 'b', 1)";
			$conn->exec($sql);
			    echo 1;
	    } else {
			echo 0;
		}
	} catch(PDOException $e){
	    echo $sql . "<br>" . $e->getMessage();
	}
?>
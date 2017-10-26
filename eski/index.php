<!DOCTYPE html>
<html>
<?php
	//error_reporting(E_ALL ^ E_DEPRECATED);

	//mysql_connect('localhost', 'root', '');
	
	//mysql_select_db('test');

	// $con = mysqli_connect('localhost', 'root', '', 'test');
	
	// $sql= "SELECT * FROM categories";

	// $records = mysqli_query($con, "SELECT * FROM categories");
	// mysqli_close($con);

	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "test";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	} 

	$sql = "SELECT * FROM categories";
	$result = $conn->query($sql);
?>
<head>
	<link rel="stylesheet" href="main_style.css">
	<link rel="shortcut icon" href="favicon.ico" />
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
</head>

<body>
	<header>
		<div id="head">
			<a href="http://www.egitimdedigerleri.com/" ><img src="http://egitimdedigerleri.com/logo.png" id="logo-image"></a>
			<p>Merak Kutuphanesi</p>
		</div>
		<nav>
			<a href="#" id="menu-icon" ></a>
			<ul>
				<li><a href="#" class="current">Ana Sayfa</a></li>
				<li><a href="#">Sorularım</a></li>
				<li><a href="#">Ne boksa iste</a></li>
			</ul>
		</nav>
	</header>		

	<input type="text" class="inputText" ng-model="inp">
	<button type="button" class="oval-button">Sorunu sor</button>


	<select>

		<?php
			//while ($category=mysql_fetch_assoc($records)) {
			while($category = $result->fetch_assoc()){
				echo "<option value='".$category['name']."'>".$category['name']."</option>";
			}
		?>
	</select>

</body>
</html>
<!DOCTYPE html>
<html>
	<head>
		<title>View assignment</title>
		<link rel = "stylesheet" href = "style.css">
	</head>
	<body>
		<h1>Select Assignment</h1>
		<form action = "view.php" method = "POST">
			<input type = "text" name = "assignmentid" placeholder = "Enter assignment id" required><br>
			<input type = "password" name = "privatepassword" placeholder = "Enter private password" required><br>
			<input type = "submit" name = "submit" value = "Submit">
		</form>
	</body>
</html>

<?php

	session_start();
	require "vendor/autoload.php";

	if (isset($_POST["submit"])){
		$query = ["assignmentid" => $_POST["assignmentid"]];
		$client = new MongoDB\Client("mongodb://localhost:27017");
		$db = $client->selectDatabase("Assignment0");
		$collection = $db->selectCollection("Forms");
		$document = $collection->findOne($query);
		if ($document){
			if (password_verify($_POST["privatepassword"], $document["privatepswd"])){
				$_SESSION["v_assignment"] = $_POST["assignmentid"];
				header("Location: http://" . $_SERVER["HTTP_HOST"] . "/assignment0/view_page.php");
			}
			else {
				echo("<script>alert(\"Password does not match!\");</script>");
			}
		}
		else {
			echo("<script>alert(\"Assignemnt does not exist!\");</script>");
		}
	}

?>
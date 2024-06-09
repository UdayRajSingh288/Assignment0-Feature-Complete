<!DOCTYPE html>
<html>
	<head>
		<title>Submit assignment</title>
		<link rel = "stylesheet" href = "style.css">
	</head>
	<body>
		<h1>Select Assignment</h1>
		<form action = "submit.php" method = "POST">
			<input type = "text" name = "assignmentid" placeholder = "Enter assignment id" required><br>
			<input type = "password" name = "publicpassword" placeholder = "Enter public password" required><br>
			<input type = "submit" name = "submit" value = "Submit">
		</form>
	</body>
</html>

<?php

	session_start();
	require "vendor/autoload.php";

	if (isset($_POST["submit"])){
		$client = new MongoDB\Client("mongodb://localhost:27017");
		$db = $client->selectDatabase("Assignment0");
		$collection = $db->selectCollection("Forms");
		$query = ["assignmentid" => $_POST["assignmentid"]];
		$document = $collection->findOne($query);
		if ($document){
			echo($document["publicpswd"]);
			echo("<br>" . $_POST["publicpassword"]);
			if (password_verify($_POST["publicpassword"], $document["publicpswd"])){
				$_SESSION["s_assignment"] = $document["assignmentid"];
				header("Location: http://" . $_SERVER["HTTP_HOST"] . "/assignment0/fill_form.php");
			}
			else {
				echo("<script>alert(\"Password incorrect!\");</script>");
			}
		}
		else {
			echo("<script>alert(\"Assignemnt does not exist!\");</script>");
		}
	}

?>
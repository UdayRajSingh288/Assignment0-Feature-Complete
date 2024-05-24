<?php
	session_start();
	require "vendor/autoload.php";

	$query = ["assignmentid" => $_SESSION["s_assignment"]];
	$client = new MongoDB\Client("mongodb://localhost:27017");
	$db = $client->selectDatabase("Assignment0");
	$collection = $db->selectCollection("Forms");
	$document = $collection->findOne($query);
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Fill form <?php echo($_SESSION["s_assignment"]); ?></title>
	</head>
	<body>
		<h1>
			<?php
				echo($document["assignmentname"]);;
			?>
		</h1>
		<a href = <?php echo("data/" . $document["assignmentid"] . "/" . $document["assignmentpdf"]); ?> download>
			Assignment document
		</a>
		<form action = "fill_form.php" method = "POST" enctype = "multipart/form-data">
			<?php
				$names = $document["fieldname"];
				$types = $document["fieldtype"];
				for ($i = 0; $i < count($document["fieldname"]); $i++){
					echo("<label>" . $names[$i] . ": </label>");
					echo("<input type = " . $types[$i] . " name = " . $names[$i] . " required><br>");
				}
			?>
			<input type = "submit" name = "submit" value = "Submit">
		</form>
	</body>
</html>

<?php

	if (isset($_POST["submit"])){
		$names = $document["fieldname"];
		$types = $document["fieldtype"];
		$collection = $db->selectCollection($document["assignmentid"]);
		$data = array();
		$data["submissionid"] = uniqid();
		$data["pathtosubmission"] = "data/" . $document["assignmentid"] . "/" . $data["submissionid"];
		mkdir($data["pathtosubmission"], 0777, true);
		for ($i = 0; $i < count($names); $i++){
			if ($types[$i] == "file"){
				$data[$names[$i]] = $_FILES[$names[$i]]["name"];
				if (is_uploaded_file($_FILES[$names[$i]]["tmp_name"])){
					$p = $data["pathtosubmission"]."/".$data[$names[$i]];
					move_uploaded_file($_FILES[$names[$i]]["tmp_name"], $p);
				}
			}
			else {
				$data[$names[$i]] = $_POST[$names[$i]];
			}
		}
		$collection->insertOne($data);
		header("Location: http://" . $_SERVER["HTTP_HOST"] . "/assignment0/index.php");
	}

?>
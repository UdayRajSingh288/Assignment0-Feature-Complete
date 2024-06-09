<?php
	session_start();
	require "vendor/autoload.php";

	$query = ["assignmentid" => $_SESSION["v_assignment"]];
	$client = new MongoDB\Client("mongodb://localhost:27017");
	$db = $client->selectDatabase("Assignment0");
	$collection = $db->selectCollection("Forms");
	$document = $collection->findOne($query);
	$names = $document["fieldname"];
	$types = $document["fieldtype"];
	$collection = $db->selectCollection($document["assignmentid"]);
	$cursor = $collection->find();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>View <?php echo($document["assignmentid"]); ?></title>
		<link rel = "stylesheet" href = "style.css">
	</head>
	<body>
		<h1><?php echo($document["assignmentname"]); ?></h1>
		<table>
			<tr>
				<?php
					foreach($names as $name){
						echo("<th>" . $name . "</th>");
					}
				?>
			</tr>
			<?php
				foreach ($cursor as $d){
					echo("<tr>");
					for ($i = 0; $i < count($types); $i++){
						if ($types[$i] == "file"){
							echo("<td><a href = " . $d["pathtosubmission"] . "/" . $d[$names[$i]] . " download>Download file</a></td>");
						}
						else {
							echo("<td>" . $d[$names[$i]] . "</td>");
						}
					}
					echo("</tr>");
				}
			?>
		</table>
	</body>
</html>
<!DOCTYPE html>
<html>
	<head>
		<title>Create assignment</title>
		<link rel = "stylesheet" href = "style.css">
	</head>
	<body>
		<h1>Create Assignment</h1>
		<form action = "create.php" method = "POST" id = "assignmentfieldsform" onSubmit = "validateandsubmit();" enctype = "multipart/form-data">
			<input type = "test" name = "assignmentname" placeholder = "Enter assignment name" required><br>
			<label>Select assignment file: </label>
			<input type = "file" name = "assignmentpdf" accept = "application/pdf" required><br>
			<label>Create field/s</label><br>
			<div id = "fields">
				<p>Note: Field names should  have no spaces</p>
				<input type = "text" name = "fieldname[]" placeholder = "Enter field name" >
				<select name = "fieldtype[]">
					<option value = "text">Text</option>
					<option value = "number">Number</option>
					<option value = "file">File</option>
				</select><br>
			</div>
			<input type = "password" name = "privatepassword1" placeholder = "Enter private password" required>
			<input type = "password" name = "privatepassword2" placeholder = "Confirm private password" required><br>
			<input type = "password" name = "publicpassword1" placeholder = "Enter public password" required>
			<input type = "password" name = "publicpassword2" placeholder = "Confirm public password" required><br>
			<input type = "button" value = "Add field" onClick = "addfield()">
			<input type = "submit"  name = "create" value = "Create">
		</form>
	</body>
	<script>

		function addfield(){
			var fields = document.getElementById("fields");
			var fieldname = document.createElement("input");
			fieldname.setAttribute("type", "text");
			fieldname.setAttribute("name", "fieldname[]");
			fieldname.setAttribute("placeholder", "Enter field name");
			fieldname.required = true;
			var fieldtype = document.createElement("select");
			fieldtype.setAttribute("name", "fieldtype[]");
			var option1 = document.createElement("option");
			option1.setAttribute("value", "text");
			option1.innerHTML = "Text";
			var option2 = document.createElement("option");
			option2.setAttribute("value", "number");
			option2.innerHTML = "Number";
			var option3 = document.createElement("option");
			option3.setAttribute("value", "file");
			option3.innerHTML = "File";
			fieldtype.appendChild(option1);
			fieldtype.appendChild(option2);
			fieldtype.appendChild(option3);
			fields.appendChild(fieldname);
			fields.appendChild(fieldtype);
			fields.appendChild(document.createElement("br"));
		}

		function validateandsubmit(){
			var form = document.getElementById("assignmentfieldsform");
			var privatepswd1 = form.privatepassword1.value;
			var privatepswd2 = form.privatepassword2.value;
			var publicpswd1 = form.publicpassword1.value;
			var publicpswd2 = form.publicpassword2.value;
			if (privatepswd1 == "" || privatepswd1 != privatepswd2 || publicpswd1 == "" || publicpswd1 != publicpswd2){
				alert("Passwords do not match!");
			}
			else {
				form.submit();
			}
		}

	</script>
</html>

<?php

	session_start();
	require "vendor/autoload.php";

	if (isset($_POST["create"])){
		$client = new MongoDB\Client("mongodb://localhost:27017");
		$db = $client->selectDatabase("Assignment0");
		$collection = $db->selectCollection("Forms");
		$data = array();
		$data["assignmentid"] = uniqid();
		$_SESSION["newassignmentid"] = $data["assignmentid"];
		$data["assignmentname"] = $_POST["assignmentname"];
		$data["assignmentpdf"] = $_FILES["assignmentpdf"]["name"];
		$data["fieldname"] = $_POST["fieldname"];
		$data["fieldtype"] = $_POST["fieldtype"];
		$data["privatepswd"] = password_hash($_POST["privatepassword1"], PASSWORD_DEFAULT);
		$data["publicpswd"] = password_hash($_POST["publicpassword1"], PASSWORD_DEFAULT);
		$collection->insertOne($data);
		$db->createCollection($data["assignmentid"]);
		$dest = "data/".$data["assignmentid"];
		mkdir($dest, 0777, true);
		$dest = $dest."/".$_FILES["assignmentpdf"]["name"];
		if (is_uploaded_file($_FILES["assignmentpdf"]["tmp_name"])){
			echo($dest);
			move_uploaded_file($_FILES["assignmentpdf"]["tmp_name"], $dest);
		}
		header("Location: http://" . $_SERVER["HTTP_HOST"] . "/assignment0/create_success.php");
	}

?>
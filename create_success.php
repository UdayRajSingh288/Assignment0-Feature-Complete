<!DOCTYPE html>
<html>
	<head>
		<title>Create Success</title>
		<link rel = "stylesheet" href = "style.css">
	</head>
	<body>
		<h1>
			Your Assignment id is:
			<?php
				session_start();
				echo($_SESSION["newassignmentid"]);
			?>
		</h1>
	</body>
</html>
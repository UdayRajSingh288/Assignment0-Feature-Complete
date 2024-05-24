<!DOCTYPE html>
<html>
	<head>
		<title>Create Success</title>
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
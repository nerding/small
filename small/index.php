<?php
	
	require_once( dirname(__FILE__) . '/small.php' );

	if (!User::isLoggedIn()) {
		header("Location: login.php");
	}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Small Administration</title>
</head>
<body>

</body>
</html>
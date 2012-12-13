<?php
	
	require_once( dirname(__FILE__) . '/branch.php' );

	if (!User::isLoggedIn()) {
		header("Location: login.php");
	}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Branch Administration</title>
</head>
<body>

</body>
</html>
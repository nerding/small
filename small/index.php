<?php
	
	require_once( __dir__ . '/small.php' );

	if (!User::isLoggedIn()) {
		header("Location: login.php");
	}

  include(__dir__ . '/../theme/head.php');
?>

<!DOCTYPE html>
<html>
<head>
	<title>Small Administration</title>
</head>
<body>
  <section class="admin">
    <header>
      <h1>Small Administration</h1>
      <a id="logout" href="#">Logout</a>
    </header>


  </section>
  
  <script>
    $("#logout").click(function(event) {
      event.preventDefault();
        $.get("../ajax.php?action=logout", function(data) { location.reload(); });
    });
  </script>
</body>
</html>
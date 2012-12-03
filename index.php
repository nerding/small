<?php

  ob_start('gzheader');

  error_reporting(E_ALL);
  ini_set('display_errors','On');

  require_once('includes/session.php');
  require_once('includes/config.php');
  require_once('includes/branch_db.php');
  require_once('includes/user.php');  
  require_once('includes/post.php');  
?>

<!DOCTYPE html>
<html>
<head>
  <title>Branch Index</title>

  <link rel="stylesheet" href="theme/css/branch.css">
</head>
<body>

  <div class="wrap">
    <header>
      <h1>Branch</h1>
    </header>

    <footer>
      <p>Copyright &copy; 2012 Don Kuntz</p>
      <p>Licensed under the MIT License</p>
    </footer>
  </div>

</body>
</html>

<?php
  ob_end_flush();
?>
<?php

  ob_start('gzheader');

  error_reporting(E_ALL);
  ini_set('display_errors','On');

  require_once('includes/session.php');
  require_once('includes/config.php');
  require_once('includes/branch_db.php');
  require_once('includes/user.php');


  $title = "This is my fancy title and such... Hello World!";
  $title = preg_replace('/[^\w]/', '-', strtolower($title));
  echo $title;
  
?>

<?php
  ob_end_flush();
?>
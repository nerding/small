<?php

  ob_start('gzheader');

  error_reporting(E_ALL);
  ini_set('display_errors','On');

  require_once('includes/session.php');
  require_once('includes/config.php');
  require_once('includes/branch_db.php');
  require_once('includes/user.php');


  Session::start();
  Session::set('username', 'admin');
  Session::end();
  echo Session::get('username') ? Session::get('username') : "false" ;
  
?>

<?php
  ob_end_flush();
?>
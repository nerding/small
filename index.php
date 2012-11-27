<?php

  error_reporting(E_ALL);
  ini_set('display_errors','On');

  require_once('includes/config.php');
  require_once('includes/branch_db.php');
  require_once('includes/user.php');

  $config = new Config();

  $db = new BranchDB($config);
  //echo $db->query('insert into users (username, password, name, email) values ("admin", "helloworld", "Don Kuntz", "don@dkuntz2.com");');

?>
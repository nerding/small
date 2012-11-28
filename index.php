<?php

  ob_start('gzheader');

  error_reporting(E_ALL);
  ini_set('display_errors','On');

  require_once('includes/config.php');
  require_once('includes/branch_db.php');
  require_once('includes/user.php');

  $config = new Config();

  $db = new BranchDB($config);


  /*
    This is what I've got right now. You can create a user.

    Users::create takes 3-5 arguments:
      Users::create($username, $password, $email [, $name [, $biography]])

    The first four are varchar(255) in the database.
    The last one is text.

    Uncomment to make it work.

    Passwords *are* salted and such. It's quite nice.
  */
  //Users::create("admin", "helloworld", "don@dkuntz2.com", "Don Kuntz");
  
?>

Hi there guys.

<?php
  ob_end_flush();
?>
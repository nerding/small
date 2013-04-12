<?php
  
  // turn on error reporting
  error_reporting(E_ALL);
  ini_set('display_errors','On');

  // stop some annoyances
  // eventually, this should be grabbed from the config file...
  date_default_timezone_set('UTC');


  // require all of the branch classes
  require_once(__dir__ . '/includes/config.php');
  require_once(__dir__ . '/includes/branch_db.php');
  require_once(__dir__ . '/includes/session.php');
  require_once(__dir__ . '/includes/user.php');
  require_once(__dir__ . '/includes/post.php');
  require_once(__dir__ . '/includes/page.php');
  require_once(__dir__ . '/includes/functions.php');
  
?>
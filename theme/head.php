<?php 
  require_once(__dir__ . '../../small/small.php'); 

  $title = (isset($title) ? $title . " | " : "") . Config::get('site.name');
?>

<!DOCTYPE html>
<html>
<head>
  <title><?php echo $title ?></title>

  <link rel="stylesheet" href="<?php echo Config::get('site.url'); ?>/theme/css/branch.css">

  <?php // libraries ?>
  <script src="<?php echo Config::get('site.url'); ?>/theme/js/sha256.js"></script>
  <script src="<?php echo Config::get('site.url'); ?>/theme/js/md5.min.js"></script>
  <script src="<?php echo Config::get('site.url'); ?>/theme/js/jquery-1.8.3.min.js"></script>
  <script src="<?php echo Config::get('site.url'); ?>/theme/js/jqui/js/jquery-ui-1.9.2.custom.min.js"></script>
  
  <?php // our stuff ?>
  <script src="<?php echo Config::get('site.url'); ?>/theme/js/small.js.php"></script>
</head>
<body>
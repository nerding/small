<?php 
  require_once(__dir__ . '../../branch/branch.php'); 

  $title = (isset($title) ? $title . "|" : "") . Config::get('site.name');
  $header = (isset($header) ? $header : Config::get('site.name'));

  if (!isset($showLogin)) {$showLogin = false;}
?>

<!DOCTYPE html>
<html>
<head>
  <title><?php echo $title ?></title>

  <link rel="stylesheet" href="<?php echo Config::get('site.url'); ?>/theme/css/branch.css">

  <script src="<?php echo Config::get('site.url'); ?>/theme/js/jquery-1.8.3.min.js"></script>
  <script src="<?php echo Config::get('site.url'); ?>/theme/js/jqui/js/jquery-ui-1.9.2.custom.min.js"></script>
</head>
<body>

  <div class="wrap">
    <header>
      <h1><?php echo $header ?></h1>

    <?php if ($showLogin) :?>
      <?php if(User::isLoggedIn()):?>
        <div class="user">
          <img class="right" src="<?php User::current()->gravatarURL(); ?>">
          <p>
            Hello <strong><?php User::current()->printName(); ?></strong><br>
            <a id="logout" href="#">Logout</a>
          </p>
        </div>
      <?php else: ?>
        <div class="login">
          <form id="loginForm">
            <input type="text" id="loginUsername" placeholder="username"><br>
            <input type="password" id="loginPassword" placeholder="password">
            <input type="submit" style="visibility:hidden;">
          </form>
        </div>
      <?php endif; ?>
    <?php endif; ?>
    </header>
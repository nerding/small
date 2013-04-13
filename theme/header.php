<?php 
  require_once(__dir__ . '../../small/small.php'); 

  $title = (isset($title) ? $title . " | " : "") . Config::get('site.name');
  $header = (isset($header) ? $header : Config::get('site.name'));

  if (!isset($showLogin)) {$showLogin = false;}
?>

<!DOCTYPE html>
<html>
<head>
  <title><?php echo $title ?></title>

  <link rel="stylesheet" href="<?php echo Config::get('site.url'); ?>/theme/css/branch.css">

  <script src="<?php echo Config::get('site.url'); ?>/theme/js/sha256.js"></script>
  <script src="<?php echo Config::get('site.url'); ?>/theme/js/jquery-1.8.3.min.js"></script>
  <script src="<?php echo Config::get('site.url'); ?>/theme/js/jqui/js/jquery-ui-1.9.2.custom.min.js"></script>

  <script>
    $(document).ready(function() {
    <?php if($showLogin) :?>
    <?php if (User::isLoggedIn()): ?>
      $("#logout").click(function(event) {
        event.preventDefault();
          $.get("ajax.php?action=logout", function(data) { location.reload(); });
      });

    <?php else: ?>
      $("#loginForm").submit(function(event) {
        event.preventDefault();
        // get nonce
        var nonce;
        var json;
        $.get("ajax.php?action=get_nonce", function(data) {
          console.log(data);
          json = $.parseJSON(data);
          nonce = json.nonce;

          console.log(json);
          console.log(nonce);
          
          if (nonce === undefined) {
            $("#error").text("Something horribly wrong has occured...");
            $("#error").show('fast');

            clearLogin();
          }
          else {
            var outData = {};
            outData['username'] = $("#loginUsername").val();
            outData['password'] = CryptoJS.SHA256($("#loginPassword").val());
            outData['password'] += CryptoJS.SHA256(nonce);

            $.post("ajax.php?action=login", outData, function(data) {
              json = $.parseJSON(data);
              if (json.error == null) {
                location.reload();
              }
              else {
                $("#error").text("Incorrect username/password combination");
                $("#error").show();
                clearLogin();
              }
            })
          }
        });
      });
    <?php endif; ?>
    <?php endif; ?>
    });

    function clearLogin() {
      $("#loginUsername").val("");
      $("#loginPassword").val("");
      $("#loginForm").effect('shake', {times: 2, distance:10}, 500);
    }
  </script>
</head>
<body>

  <div class="wrap">
    <div id="error" style="display:none;"></div>

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
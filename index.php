<?php
  require_once('branch/branch.php');
?>

<!DOCTYPE html>
<html>
<head>
  <title>Index | <?php echo Config::get('site.name'); ?></title>

  <link rel="stylesheet" href="theme/css/branch.css">

  <script src="theme/js/jquery-1.8.3.min.js"></script>
  <script src="theme/js/jqui/js/jquery-ui-1.9.2.custom.min.js"></script>
</head>
<body>

  <div class="wrap">
    <header>
      <h1><?php echo Config::get('site.name'); ?></h1>

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
    </header>

    <section>
      <?php //Page::index(); ?>
    </section>

    <footer>
      <p>Copyright &copy; 2012 Don Kuntz</p>
      <p>Licensed under the MIT License</p>
    </footer>
  </div>

  <script>
    $(document).ready(function() {

    <?php if (User::isLoggedIn()): ?>
      $("#logout").click(function(event) {
        event.preventDefault();
          $.get("ajax.php?action=logout", function(data) { location.reload(); });
      });

    <?php else: ?>
      $("#loginForm").submit(function(event) {
        event.preventDefault();
        var outData = {};
        outData['username'] = $("#loginUsername").val();
        outData['password'] = $("#loginPassword").val();

        $.post("ajax.php?action=login", outData, function(data) {
          json = $.parseJSON(data);
          if (json.error == null) {
            location.reload();
          }
          else {
            $("#loginUsername").val("");
            $("#loginPassword").val("");
            $("#loginForm").effect('shake', {times: 2, distance:10}, 500);
          }
        })
      });
    <?php endif; ?>
    });    
  </script>

</body>
</html>

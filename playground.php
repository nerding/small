<?php
	  ob_start('gzheader');

  error_reporting(E_ALL);
  ini_set('display_errors','On');

  require_once('includes/config.php');
  require_once('includes/branch_db.php');
  require_once('includes/session.php');
  require_once('includes/user.php');
  require_once('includes/post.php');

  $config = new Config();


?>
<!DOCTYPE html>
<html>
<head>
	<title>Branch Playground</title>

  <style>
    .left {float:left; margin-right:2%;}
    .right {float:right; margin-left:2%;}
    .half {width:48%;}
  </style>

  <script src="js/jquery-1.8.3.min.js"></script>
</head>

<body>
  <?php if (User::isLoggedIn()):?>
  <h1>Hello <?php echo Session::get('username'); ?></h1>
  <button id="logout">Logout</button>

  <br clear="both">

  <script>
    $(document).ready(function() {
      $("#logout").click(function(event) {
        event.preventDefault();

        $.get("ajax.php?action=logout", function(data) {
          location.reload();
        })
      })
    })
  </script>

  <?php endif; ?>

  <div class="left half">
    <?php if (User::isLoggedIn()): ?>
    <script>
      $(document).ready(function() {
        $("#createUser").submit(function(event) {
          event.preventDefault();

          var outData = {}

          // is usernam
          outData['username'] = $("#createUsername").val();
          outData['password'] = $("#createPassword").val();
          outData['email'] = $("#createEmail").val();
          outData['name'] = $("#createName").val();
          outData['bio'] = $("#createBio").val();

          $.post("ajax.php?action=create_user", outData, function(data) {
            alert(data);
          });
        })
      })
    </script>

    <h2>Create User</h2>
    <form id="createUser">
      <label for="createUsername">Username*</label><br>
      <input type="text" name="createUsername" id="createUsername" placeholder="username" required />

      <br>
      <label for="createPassword">Password*</label><br>
      <input type="password" name="createPassword" id="createPassword" required />

      <br>
      <label for="createEmail">Email Address*</label><br>
      <input type="email" name="createEmail" id="createEmail" placeholder="you@example.com" required />

      <br>
      <label for="createName">Real Name</label><br>
      <input type="text" name="createName" id="createName" placeholder="You" />

      <br>
      <label for="createBio">Biography</label><br>
      <textarea name="createBio" id="createBio" placeholder="Tell us about yourself"></textarea>

      <br>
      <input type="submit" id="makeUser" value="Make User" />
    </form>
    <?php endif; ?>

    <h2>Try Logging In</h2>
    <form id="loginUser">
      <div id="loginError" class="notice" style="display:none"></div>

      <label for="loginUsername">Username</label><br>
      <input type="text" name="loginUsername" id="loginUsername" />

      <br>
      <label for="loginPassword">Password</label><br>
      <input type="password" name="loginPassword" id="loginPassword" />

      <br>
      <input type="submit" id="tryLogin" value="Login" />
    </form>

    <script>
      $(document).ready(function() {
        $("#loginUser").submit(function(event) {
          event.preventDefault();

          $("#tryLogin").val("Working...");
          $("#tryLogin").attr("disabled", "disabled");

          var outData = {};
          outData['username'] = $("#loginUsername").val();
          outData['password'] = $("#loginPassword").val();

          $.post("ajax.php?action=login", outData, function(data) {
            json = $.parseJSON(data);
            $("#tryLogin").removeAttr("disabled");

            <?php if (User::isLoggedIn()): ?>
              if (json.error == null) {
                $("#tryLogin").val("Login");
                $("#loginError").text("Login works - you will be that user...");
                $("#loginError").show()
              }
            <?php else: ?>
              if (json.error == null) {
                $("#tryLogin").val("Logging In");
                location.reload();
              }
              else {
                $("#tryLogin").val("Login");
                $("#loginError").text(json.error);
                $("#loginError").show();
                console.log("error");
                console.log(json);
              }
            <?php endif; ?>
          });

        })
      })
    </script>
	</div>

	<div class="right half">
    <?php if (User::isLoggedIn()) :?>
    <h3>Posts</h3>


    <?php foreach(Post::all() as $post): ?>
    <div class="post post-<?php echo $post->id ?>">
      <h4><?php echo $post->title ?></h4>

      <?php echo $post->contents() ?>
    </div>
    <?php endforeach; ?>

    <hr>
    <h3>Write A Post</h3>

    <form>

    </form>
    <?php endif; ?>
	</div>

</body>
</html>
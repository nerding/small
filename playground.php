<?php
	  ob_start('gzheader');

  error_reporting(E_ALL);
  ini_set('display_errors','On');

  require_once('includes/config.php');
  require_once('includes/branch_db.php');
  require_once('includes/user.php');

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
</head>

<body>
	<div class="left half">
    <h1>Create User</h1>
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

    <h1>Try Logging In</h1>
    <form id="loginUser">
      <label for="loginUsername">Username</label><br>
      <input type="text" name="loginUsername" id="loginUsername" />

      <br>
      <label for="loginPassword">Password</label><br>
      <input type="password" name="loginPassword" id="loginPassword" />

      <br>
      <input type="submit" id="tryLogin" value="Login" />
    </form>
	</div>

	<div class="right half">
    <h1>Write a post</h1>
    <p><strong>You need to be logged in first</strong></p>
	</div>
</body>
</html>
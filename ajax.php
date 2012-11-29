<?php
  ob_start('gzheader');

  error_reporting(E_ALL);
  ini_set('display_errors','On');

  require_once('includes/config.php');
  require_once('includes/branch_db.php');
  require_once('includes/session.php');
  require_once('includes/user.php');

	if (isset($_GET['action'])) {
		if ($_GET['action'] == 'create_user') {
			if (
				isset($_POST['username']) && 
				isset($_POST['password']) && 
				isset($_POST['email'])
			) {
        if (!isset($_POST['name'])) { $_POST['name'] = ""; }
        if (!isset($_POST['bio'])) { $_POST['bio'] = ""; }
        Users::create(
            filter_input(INPUT_POST, 'username'), 
            filter_input(INPUT_POST, 'password'), 
            filter_input(INPUT_POST, 'email'), 
            filter_input(INPUT_POST, 'name'), 
            filter_input(INPUT_POST, 'bio')
        );

        echo '{"notice" : "SUCCESS", "error": null}';
        return;
			}
      else {
        echo '{"error" : "required fields not supplied"}';
        return;
      }
		}
    else if ($_GET['action'] == 'login') {
      if (isset($_POST['username']) && isset($_POST['password'])) {
        if (Users::login(
            filter_input(INPUT_POST, 'username'),
            filter_input(INPUT_POST, 'password')
        )) {
          echo '{"error": null, "notice": "You are being logged in"}';
          return;
        }
        else {
          echo '{"error": "Incorrect username/password combo"}';
          return;
        }
      }
      else {
        echo '{"error" : "No username or password"}';
        return;
      }

      echo '{"error": "function not implmented yet"}';
      return;
    }
    else if ($_GET['action'] == 'logout') {
      Users::logout();
    }
	}
	else {
		echo "{'error': 'No action'}";
	}
?>

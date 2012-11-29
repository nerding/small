<?php
  ob_start('gzheader');

  error_reporting(E_ALL);
  ini_set('display_errors','On');

  require_once('includes/config.php');
  require_once('includes/branch_db.php');
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

        echo "{'notice' : 'SUCCESS'}";
			}
      else {
        echo "{'error' : 'required fields not supplied'}";
      }
		}
	}
	else {
		echo "{'error': 'No action'}";
	}
?>

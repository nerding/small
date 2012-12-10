<?php
  ob_start('gzheader');
  require_once('branch/branch.php');
  
	if (isset($_GET['action'])) {
		// create a user
    if ($_GET['action'] == 'create_user') {
			if (
				isset($_POST['username']) && 
				isset($_POST['password']) && 
				isset($_POST['email']) &&
        isset($_SESSION['nonce'])
			) {

        // check nonce
        $passIn = filter_input(INPUT_POST, 'password');
        $pass = substr($passIn, 0, 64);
        $nonce = substr($passIn, 64, 128);

        if ($nonce != hash('sha256', $_SESSION['nonce'])) {
          echo '{"error": "incorrect nonce"}';
          return;
        }

        if (!isset($_POST['name'])) { $_POST['name'] = ""; }
        if (!isset($_POST['bio'])) { $_POST['bio'] = ""; }
        User::create(
            filter_input(INPUT_POST, 'username'), 
            $pass, 
            filter_input(INPUT_POST, 'email'), 
            filter_input(INPUT_POST, 'name'), 
            filter_input(INPUT_POST, 'bio')
        );

        echo '{"notice" : "SUCCESS", "error": null}';
        return;
			}
      else {
        if (!isset($_SESSION['nonce'])) {
          echo '{"error" : "no nonce on server"}';
          return;
        }

        echo '{"error" : "required fields not supplied"}';
        return;
      }
		}

    // logging in
    else if ($_GET['action'] == 'login') {
      if (isset($_POST['username']) && isset($_POST['password'])) {
        if (!isset($_SESSION['nonce'])) {
          echo '{"error": "no nonce on server"}';
          return;
        }
        /*else */
        else {
          $passIn = filter_input(INPUT_POST, 'password');
          $pass = substr($passIn, 0, 64);
          $nonce = substr($passIn, 64, 128);

          if ($nonce != hash('sha256', $_SESSION['nonce'])) {
            echo '{"error" : "incorrect nonce"}';
            return;
          }

          if (User::login(
            filter_input(INPUT_POST, 'username'),
            $pass
          )) {
            echo '{"error": null, "notice": "You are being logged in"}';
            return;
          }

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
    
    // logging out
    else if ($_GET['action'] == 'logout') {
      User::logout();
    }

    // create post
    else if ($_GET['action'] == 'create_post') {
      if (isset($_POST['title']) && isset($_POST['content']) && isset($_POST['author'])) {
        $post_id = Post::create(
          filter_input(INPUT_POST, 'title'),
          filter_input(INPUT_POST, 'content'),
          filter_input(INPUT_POST, 'author')
        );
        
        if ($post_id) {
          echo "{'error' null, 'notice': 'post created', 'post_id': $post_id}";
          return;
        }
        else {
          echo '{"error": "unknown issues occured..."}';
          return;
        }
      }
      else {
        echo '{"error" : "No title, content, or author id"}' . "\n\n";
        var_dump($_POST);
        return;
      }


      echo '{"error": "function not implemented yet"}';
      return;
    }

    // delete post
    else if ($_GET['action'] == 'delete_post') {
      if (isset($_POST['id'])) {
        Post::delete($_POST['id']);
        echo '{"error": null, "notice": "successfully deleted"}';
        return;
      }
      else {
        echo '{"error" : "no post id specified"}';
        return;
      }


      echo '{"error" : "function not implemented yet"}';
      return;
    }

    // get markdown content
    else if ($_GET['action'] == 'post_markdown') {

      echo '{"error" : "function not implemented yet"}';
      return;
    }

    // get nonce
    else if ($_GET['action'] == 'get_nonce') {
      $_SESSION['nonce'] = randString(32);
      echo '{"error": null, "nonce": "' . $_SESSION['nonce'] . '"}';
      return;
    }

    // change a password
    else if ($_GET['action'] == 'change_password') {

      if (!isset($_SESSION['nonce'])) {
        echo '{"error": "no nonce on server"}';
        return;
      }
      else if (isset($_POST['username']) && isset($_POST['password'])) {
        $passIn = filter_input(INPUT_POST, 'password');
        $nonce = substr($passIn, 64, 128);
        $pass = substr($passIn, 0, 64);

        if ($nonce != hash('sha256', $_SESSION['nonce'])) {
          echo '{"error" : "incorrect nonce"}';
          return;
        }

        User::changePassword(filter_input(INPUT_POST, 'username'), $pass);

        echo '{"error": null, "notice": "password changed"}';
        return;
      }

      echo '{"error": "function not implemented yet"}';
      return;
    }
	}
	else {
		echo "{'error': 'No action'}";
	}

  if (isset($_SESSION['nonce'])) { unset($_SESSION['nonce']); }
?>

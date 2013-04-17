<?php
  //ob_start('gzheader');
  require_once('small/small.php');
  
  error_reporting(E_ALL);
  ini_set('display_errors','On');
  
	if (isset($_GET['action'])) {
		// create a user
    if ($_GET['action'] == 'create_user') {
			if (
				isset($_POST['email']) &&
        isset($_POST['password']) && 
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
            filter_input(INPUT_POST, 'email'), 
            $pass, 
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
      if (isset($_POST['email']) && isset($_POST['password'])) {
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
            filter_input(INPUT_POST, 'email'),
            $pass
          )) {
            echo '{"error": null, "notice": "You are being logged in"}';
            return;
          }

          echo '{"error": "Incorrect email/password combo"}';
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
          echo '{' . "\n\t\"error\" : \"incorrect nonce\",";
          echo "\n\t\"client\" : \"$nonce\", \n\t\"server\" : \"";
          echo hash('sha256', $_SESSION['nonce']);
          echo '"\n}';
          //echo '{"error" : "incorrect nonce", "cNonce" : "' . $nonce '", "sNonce": "' . hash('sha256', $_SESSION['nonce']) . '"}';
          return;
        }

        User::changePassword(filter_input(INPUT_POST, 'username'), $pass);

        echo '{"error": null, "notice": "password changed"}';
        return;
      }

      echo '{"error": "function not implemented yet"}';
      return;
    }

    // get users in json string
    else if ($_GET['action'] == 'get_users') {
      echo '{';

      $i = 0;
      foreach (User::all() as $user) {
        $i++;
        echo "\n\t\"{$user->username}\": {";
        echo "\n\t\t\"id\": \"{$user->id}\", ";
        echo "\n\t\t\"username\": \"{$user->username}\", ";
        echo "\n\t\t\"email\": \"{$user->email}\", ";
        echo "\n\t\t\"name\": \"{$user->name}\", ";
        echo "\n\t\t\"biography\": \"{$user->biography}\"";
        echo "\n\t}";

        if ($i != count(User::all())) {
          echo ',';
        }

        echo "\n";
      }

      echo '}';
      return;
    }
  }
	else {
		echo "{'error': 'No action'}";
	}

  if (isset($_SESSION['nonce'])) { unset($_SESSION['nonce']); }
?>

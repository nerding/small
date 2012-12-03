<?php
  ob_start('gzheader');

  error_reporting(E_ALL);
  ini_set('display_errors','On');

  require_once('includes/config.php');
  require_once('includes/branch_db.php');
  require_once('includes/session.php');
  require_once('includes/user.php');
  require_once('includes/post.php');

	if (isset($_GET['action'])) {
		// create a user
    if ($_GET['action'] == 'create_user') {
			if (
				isset($_POST['username']) && 
				isset($_POST['password']) && 
				isset($_POST['email'])
			) {
        if (!isset($_POST['name'])) { $_POST['name'] = ""; }
        if (!isset($_POST['bio'])) { $_POST['bio'] = ""; }
        User::create(
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

    // logging in
    else if ($_GET['action'] == 'login') {
      if (isset($_POST['username']) && isset($_POST['password'])) {
        if (User::login(
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
	}
	else {
		echo "{'error': 'No action'}";
	}
?>

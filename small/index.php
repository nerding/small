<?php
	
	require_once( __dir__ . '/small.php' );

	if (!User::isLoggedIn()) {
		header("Location: login.php");
	}

  $title = "Small Administration";
  include(__dir__ . '/../theme/head.php');
?>

<script src="js/tabs.js"></script>
<script src="js/write-post.js"></script>

<body class="admin">
  <div class="header-bar">
    <ul id="tabs-links">
      <li><a href="#home">Small</a></li>
      <li><a href="#write">Write</a></li>
      <li><a href="#revise">Revise</a></li>
      <li><a href="#manage">Manage</a></li>
      <li><span id="logout">Logout</span></li>
    </ul>
  </div>

  <div class="wrap">
    <div id="tabs">
      <section id="home">
        Home
      </section>

      <section id="write">
        Write
        <form id="writePost">
          <input type="text" placeholder="Title" id="postTitle" name="postTitle" />          
          <textarea id="postContents" name="postContents" placeholder="Just Start Writing"></textarea>
          <input type="hidden" id="postAuthor" value="<?php echo Session::get('id'); ?>" />

          <input type="submit" value="Post that post!" />
        </form>
      </section>

      <section id="revise">
        Revise

        <ul>
        <?php
          foreach(Post::all() as $post) {
            echo "<li>{$post->title}</li>";
          }
        ?>
        </ul>
      </section>

      <section id="manage">
        Manage
      </section>
    </div>
  </div>
</body>
</html>
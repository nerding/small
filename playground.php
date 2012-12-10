<?php
	ob_start('gzheader');
  require_once('branch/branch.php');

  $title = "playground";
  $header = Config::get('site.name') . "::playground";

  include_once('theme/header.php');
?>


<?php /*
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
      <button class="deletePost" post-id="<?php echo $post->id?>">Delete Post</button>

      <div id="postContent-<?php echo $post->id ?>">
        <?php echo $post->contents() ?>
      </div>
    </div>
    <?php endforeach; ?>

    <script>
      $(".deletePost").click(function(event) {
        event.preventDefault();
        var id = $(this).attr("post-id");

        var doit = confirm("Really Delete?");

        if (doit) {
          $.post("ajax.php?action=delete_post", {"id": id},
          function(data) {
            //var json = $.parseJSON(data);

            alert(data);
          });
        }
      })
    </script>

    <hr>
    <h3>Write A Post</h3>

    <form id="writePost">
      <div id="postNotice" class="notice" style="display:none;"></div>

      <label for="postTitle">Title</label><br>
      <input type="text" placeholder="title" id="postTitle" name="postTitle" >

      <br>
      <label for="postAuthor">Author</label><br>
      <select id="postAuthor" name="postAuthor">
      <?php foreach(User::all() as $user) : ?>
        <option value="<?php echo $user->id; ?>"><?php echo $user->getName(); ?></option>
      <?php endforeach; ?>
      </select>

      <br>
      <label for="postContents">Content (in markdown)</label><br>
      <textarea id="postContents" name="postContents"></textarea>

      <br>
      <input type="submit" id="createPost" value="Create Post">
    </form>

    <script>
      $(document).ready(function() {
        $("#writePost").submit(function(event) {
          event.preventDefault();

          $("#createPost").val("working...");
          $("#createPost").attr("disabled", "disabled");

          var outData = {};

          outData['title'] = $("#postTitle").val();
          outData['content'] = $("#postContents").val();
          outData['author'] = $("#postAuthor").val();

          $.post("ajax.php?action=create_post", outData, function(json) {
            alert(json);

            if (json == null) {
              alert("There's been a server error...");
            }
            else if (json.error == null) {
              $("#createPost").removeAttr("disabled");
              $("#createPost").val("Post Created");

              location.reload();
            }
            else {
              $("#createPost").removeAttr("disabled");
              $("#createPost").val("Create Post");

              $("#postNotice").text(json.error);
              $("#postNotice").show();
            }
          });
        });
      });
    </script>
    <?php endif; ?>
	</div>
*/ ?>
</body>
</html>
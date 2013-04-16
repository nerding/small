<?php
	//ob_start('gzheader');
  require_once('small/small.php');

  $title = "playground";
  $header = Config::get('site.name') . "::playground";
  $showLogin = true;

  include('theme/header.php');
?>

<section>

  <div id="playground_tabs">
    <ul>
      <li><a href="#createUser">Create User</a></li>
      <li><a href="#showUsers">View Users</a></li>
      <li><a href="#changePass">Change Password</a></li>
      <li><a href="#createPost">Create Post</a></li>
      <li><a href="#showPosts">Show Posts</a></li>
    </ul>

    <div id="createUser">
      <div id="createUserNotice" style="display:none;"></div>
      <form id="createUserForm">
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

      <script>
        $(document).ready(function() {

          $("#createUserForm").submit(function(event) {
            event.preventDefault();
            
            $.get('ajax.php?action=get_nonce', function(data) {
              json = $.parseJSON(data);
              nonce = json.nonce;

              if (nonce === undefined) {
                $("#createUserNotice").text("Couldn't get nonce");
                $("#createUserNotice").show();
              }
              else {
                var outData = {}

                // is usernam
                outData['username'] = $("#createUsername").val();
                outData['password'] = CryptoJS.SHA256($("#createPassword").val());
                outData['password'] += CryptoJS.SHA256(nonce);
                outData['email'] = $("#createEmail").val();
                outData['name'] = $("#createName").val();
                outData['bio'] = $("#createBio").val();

                $.post("ajax.php?action=create_user", outData, function(data) {
                  alert(data);
                });
              }
            })

          })
        })
      </script>
    </div>
    
    <div id="showUsers">
      <table>
        <tr>
          <th>ID</th>
          <th>Username</th>
          <th>Name</th>
          <th>Email Address</th>
          <th></th>
        </tr>
      <?php foreach (User::all() as $user) :?>
        <tr>
          <td><?php echo $user->id; ?></td>
          <td><?php echo $user->username; ?></td>
          <td><?php echo $user->name; ?></td>
          <td><?php echo $user->email; ?></td>
          <td>
            <button class="delete-user" data-user="<?php echo $user->id; ?>" data-name="<?php echo $user->name; ?>">
              Delete
            </button>
          </td>
        </tr>
      <?php endforeach; ?>
      </table>

      <script>
          $(".delete-user").click(function(event) {
            event.preventDefault();

            confDelete = confirm("Are you sure you want to delete " + $(this).attr('data-name') + "?");
            console.log(confDelete)
          });
      </script>
    </div>
    
    <div id="changePass">
      <form id="changePassword">
        <label for="changePassUser">Username</label><br>
        <select id="changePassUser" name="changePassUser">
        <?php foreach(User::all() as $user) : ?>
          <option value="<?php echo $user->id; ?>"><?php echo $user->getName() ?></option>
        <?php endforeach; ?>
        </select>
        <br><br>
        <label for="changePassPassword">Password</label><br>
        <input type="password" id="changePassPassword" name="changePassPassword" placeholder="password" />
        <br><br>
        <input type="submit" value="Change! That! Password!" />
      </form> 

      <script>
        $(document).ready(function() {
          $("#changePassword").submit(function(event) {
            event.preventDefault();

            $.get('ajax.php?action=get_nonce', function(data) {
              json = $.parseJSON(data);
              nonce = json.nonce;
              console.log(json);
              console.log(nonce);

              if (nonce === undefined) {
                $("#createUserNotice").text("Couldn't get nonce");
                $("#createUserNotice").show();
              }
              else {
                var outData = {}

                // is usernam
                outData['username'] = $("#changePassUser").val();
                outData['password'] = CryptoJS.SHA256($("#changePassPassword").val());
                outData['password'] += CryptoJS.SHA256(nonce);
                
                console.log(outData);

                $.post("ajax.php?action=change_password", outData, function(data) {
                  alert(data);
                });
              }
            })
          });
        })
      </script>
    </div>
    
    <div id="createPost">
      create post
    </div>
    
    <div id="showPosts">show posts</div>
  </div>

</section>

<script>
  $(function() {
    $("#playground_tabs").tabs();
  })
</script>

<?php include('theme/footer.php'); ?>

<?php /*
  

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
</body>
</html>
*/ ?>

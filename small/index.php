<?php
	
	require_once( __dir__ . '/small.php' );

	if (!User::isLoggedIn()) {
		header("Location: login.php");
	}

  $title = "Small Administration";
  include(__dir__ . '/../theme/head.php');
?>

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
        </form>
      </section>

      <section id="revise">
        Revise
      </section>

      <section id="manage">
        Manage
      </section>
    </div>
  </div>
  
  <script>
    function showTab(section) {
      $("#tabs").find('section').hide();
      $(section).show();
    }

    window.onpopstate = function() {
      if (window.location.hash)
        showTab(window.location.hash);
      else
        showTab('#home');
    }
    
    $(document).ready(function() {
      $("#logout").click(function(event) {
        event.preventDefault();
        $.get("../ajax.php?action=logout", function(data) { location.reload(); });
      });

      // tabs
      $('#tabs-links').find('a').click(function(event) {
        event.preventDefault();
        showTab($(this).attr('href'));
        history.pushState(null, $(this).attr('href'), $(this).attr('href'));
      });

      showTab('#home');
      if (window.location.hash)
        showTab(window.location.hash);
    })
 
  </script>
</body>
</html>
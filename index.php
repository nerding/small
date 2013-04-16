<?php
  require_once('small/small.php');
  
  //$showLogin = true;

  $out = "";
  if (isset($_GET['post'])) {
    $post = Post::find('where title = "' . $_GET['post'] . '"')[0];
    //echo $_GET['post'];
    //var_dump($post);

    $out .= "<h1>" . $post->title . "</h1>";
    $out .= $post->contents();
  }
?>

<?php include(__dir__ . "/theme/header.php"); ?>

<section>
  <?php echo $out; ?>
</section>

<?php include(__dir__ . "/theme/footer.php"); ?>
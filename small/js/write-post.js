$(document).ready(function() {
  $("#writePost").submit(function(event) {
    event.preventDefault();

    var post = {
      title: $("#postTitle").val(),
      content: $("#postContents").val(),
      author: $("#postAuthor").val()
    }

    $.post('../ajax.php?action=create_post', post, function(data) {
      console.log(data);
    })
  })
})
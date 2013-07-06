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
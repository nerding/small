<?php require_once(__dir__ . '/../../small/small.php'); ?>

/*
  Collection of javascript tasks used all over Small.

  Really, anything that is done more than once (and even some things that are
  done only once) go here for reuse later.
*/

var site = "<?php echo Config::get('site.url'); ?>";

/*  Attempt Login:
      Takes in an email address and password and checks to see if they're
      valid. If so, it logs the user in.
*/
function attemptLogin(email, password) {
  // get nounce first
  var nonce;
  var json;

  return $.get(site + "/ajax.php?action=get_nonce", function(data) {
    console.log(data); // debugging purposes
    json = $.parseJSON(data); // so we can play with it outside this scope
    nonce = json.nonce;

    if (nonce === undefined) {
      displayError("Something horribly wrong has occured while getting a nounce");
      clearLogin();
      return false;
    }
    else {
      // put together the data we're sending for logging in
      var outData = {}
      outData['email'] = email;
      outData['password'] = CryptoJS.SHA256(password);
      outData['password'] += CryptoJS.SHA256(nonce);

      // send the data
      return $.post(site + "/ajax.php?action=login", outData, function(data) {
        json = $.parseJSON(data);
        console.log(json); // debug

        if (json.error == null) {
          return true;
        }
        else {
          displayError("Incorrect email/password combination");
          clearLogin();
          return false;
        }
      });
    }
  });

  // this shouldn't happen, but it might (in the case that the $.get doesn't
  // work). Anyways, if that doesn't work, the login didn't occur.
  /*displayError("Couldn't get nonce");
  clearLogin();
  return false;*/
}


/*  Display Error:
      Displays an error to the user, provided there's a way to display it.
*/
function displayError(message) {
  // does the dom currently have an #error element
  if ($("#error").length > 0) {
    $("#error").text(message);
    $("#error").show();
  }
}


/* Clear Login:
    Clear the login form, provided there is a form called #login
*/
function clearLogin() {
  // check for #login element
  if ($("#login").length > 0) {
    // clear each element inside of #login
    $("#login").children().each(function() {
      $(this).val("").change();
    });

    // also, lets make it shake!
    $("#login").effect('shake', {times: 2, distance:10}, 500);
  }
}
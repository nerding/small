<?php
	require_once( __dir__ . '/small.php');

	include( __dir__ . '/../theme/head.php');
?>

<section class="login-page">
	<h1>Login</h1>

	<img id="gravatar" src="http://www.gravatar.com/avatar/d41d8cd98f00b204e9800998ecf8427e?d=identicon&s=150">

	<form id="login">
		<input type="text" id="loginEmail" placeholder="email address"><br>
		<input type="password" id="loginPassword" placeholder="password">
		<input type="submit" style="visibility:hidden;">
	</form>
</section>

<script>
	$gravurl = "http://www.gravatar.com/avatar/";
	$gravflags = "?d=identicon&s=150";

	// whenever the loginEmail input gets changed, change the gravatar to match
	// this requires the md5 library from crypto.
	$("#loginEmail").change(function(event) {
		$grav = $gravurl + md5($(this).val()) + $gravflags;

		// provided it's not the same thing right now...
		if ($grav != $("#gravatar").attr('src')) {
			$("#gravatar").attr('src', $grav);
		}
	})


	// hijack the login form so that we can do some fancy stuff.
	// because fancy is cool.
	$("#login").submit(function(event) {
		event.preventDefault();

		var tryLogin = attemptLogin($("#loginEmail").val(), $("#loginPassword").val());
		//alert(tryLogin);
		if (tryLogin) {
			location.href = "<?php echo Config::get('site.url'); ?>/small/";
		}
	})
</script>
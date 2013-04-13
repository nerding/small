<?php

	require_once( dirname(__FILE__) . '/branch.php');

?>
<!DOCTYPE html>
<html>
<head>
	<title>Login | <?php echo Config::get('site.name'); ?></title>

	<link rel="stylesheet" href="<?php echo Config::get('site.url'); ?>/theme/css/branch.css" />
</head>
<body class="admin">
	<div class="wrap">
		<header>
			<h1><?php echo Config::get('site.name'); ?> :: login</h1>
		</header>

		<section>
			<form id="loggy-inner">
				<img src="http://placehold.it/200x200"><br>

				<input id="username" name="username" placeholder="username"><br>
				<input id="password" name="password" placeholder="password">
			</form>
		</section>

<?php
	include ( dirname(__FILE__) . '/../theme/footer.php' );
?>
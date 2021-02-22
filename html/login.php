<?php
require_once("header.php");

if (isset($_POST['email']) && isset($_POST['password']))
{
	if (loginUser($_POST['email'], $_POST['password']))
	{
		header("Location: /");
	}
	else
	{
		print "Doh! Error logging in.<br />";
	}
}

?>

<form method="post">
	<b>Email address:</b>
	<input type="text" name="email">
	<br />

	<b>Password:</b>
	<input type="password" name="password">
	<br />
	<input type="submit" name="submit" value="Login">
</form>


<?php
require_once("footer.php");

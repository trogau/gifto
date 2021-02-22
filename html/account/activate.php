<?php
require_once("header.php");

if (isset($sess_uid))
	die("invalid (why are you logged in already?)");

$bits = explode("/",$_SERVER['PHP_SELF']);

if (!isset($bits[3]))
	die("invalid request");
else
{
	$act_uid = intval($bits[3]);

	// Check to see if its valid
	$sql = "SELECT * FROM users WHERE id = $act_uid AND active = 0";
	$rs = dbquery($sql);

	if (!$rs) die("invalid userid");

	$email = $rs[0]['email'];
	$id = $rs[0]['id'];

	if (isset($_SAFEPOST['confirmadd']))
	{
		if (isset($_SAFEPOST['name']) && $_SAFEPOST['name'] != "" 
			&& (isset($_SAFEPOST['password']) && $_SAFEPOST['password'] != "") )
		{
			$sql = "UPDATE users SET name = '".$_SAFEPOST['name']."', 
				password = PASSWORD('".$_SAFEPOST['password']."'),
				active = 1,
				modified = $timenow
				WHERE id = $id";

			if (!dbquery($sql))
				die("Doh! Error creating account.<br />\n");
			else
			{
		?>
			<b>That's it! Your account has been made.</b> <br /><br />
			We've sent the details to your email address (<?=$email?>), but you're now logged
			in and ready to go.
			<br /><br />
			You can now <a href="/">go back to the main page</a> to create your wishlist or 
			view the wishlists of others. 		
		<?

				if (!loginUser($email, $_SAFEPOST['password']))
					die("Doh, weird - an unexpected error occured!<br />");
			}
		}
		else
		{
			print "ERROR: Need to supply your name and a password for your account!<br />";
			$error = 1;
		}
	}

	if (isset($error) || !isset($_SAFEPOST['confirmadd']))
	{
	?>
		<form method="post">
		Name: <input type="text" name="name" value="<?=isset($_SAFEPOST['name']) ? $_SAFEPOST['name'] : ''?>"><br />
		Email: <input type="text" name="email" value="<?=$email?>" disabled><br />
		Password: <input type="password" name="password"><br />
		<input type="submit" name="confirmadd" value="That's it, create and activate my account!"><br />
		</form>
	<?
	}

}



require_once("footer.php");

<?php
require_once("header.php");

if (!isset($sess_uid))
	die('Login required');

if (isset($_SAFEPOST['inviteemail']) && $_SAFEPOST['inviteemail'] != "")
{
	// check to see if the target person already has an account
	$sql = "SELECT u.id FROM users u, useraccess a
		WHERE u.email = '".$_SAFEPOST['inviteemail']."' 
		AND u.id = a.reader
		AND a.owner = $sess_uid";

	$rs = dbquery($sql);

	if ($rs)
	{
		print "This user already has access to your wishlist!";
	}
	else
	{
		print "We've sent an email to ".$_SAFEPOST['inviteemail']." with instructions on how to view your wishlist!<br />";

		// Check to see if there's a user entry at all
		$readerid = 0;

		$sql = "SELECT id FROM users WHERE email = '".$_SAFEPOST['inviteemail']."'";

		$rs = dbquery($sql);

		if(!$rs)
		{
			$sql = "INSERT INTO users (email,active,timestamp,modified)
				VALUES ('".$_SAFEPOST['inviteemail']."', 0, $timenow, $timenow)";
			dbquery($sql);

			$readerid = dblastid();
			$e_body = "Hi!

$sess_name has suggested you might like to check out their Gifto wishlist!

If you wish to do this, simply click the link below to be taken to the Gifto website where you will be able to quickly create and account to access this wishlist.

http://gifto.com.au/account/activate.php/$readerid
";

		}
		else
		{
			$readerid = $rs[0]['id'];

			$e_body = "Hi!

$sess_name has suggested you might like to check out their Gifto wishlist on gifto.com.au!

All you need to do is click the link below to see their wishlist:

http://gifto.com.au/wishlist/index.php/$sess_uid";

		}

		$e_body .= "\n\n--\nRegards,\nThe Gifto Team";


		$sql = "INSERT INTO useraccess (owner, reader, timestamp)
			VALUES ($sess_uid, $readerid, $timenow)";
		dbquery($sql);


		$e_subject = "$sess_name has invited you to see their wishlist on Gifto!";
		mail($_SAFEPOST['inviteemail'], $e_subject, $e_body, "From: Gifto <noreply@gifto.com.au>");
	}
}

?>

<div>If you want to invite people to check out your wishlist, simply add their email into this form. </div>

<div></div>

<br />


<form method="post">

Email address: <input type="text" name="inviteemail"><br />
<input type="submit" name="invite" value="Invite this person">

</form>

<?
require_once("footer.php");

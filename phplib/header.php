<?php
error_reporting(E_ALL);
ob_start();
require_once("config.php");
require_once("sanitise.php");
require_once("db.php");
require_once("funcs.php");

// Initialise some global variables
$timenow = time();

if (isset($_COOKIE['sessid']))
{
	// Get user information
	$sql = "SELECT s.id,u.id as userid,u.name FROM users u, sessions s 
			WHERE s.userid = u.id
			AND s.id = '".$_COOKIE['sessid']."'";

	$rs = dbquery($sql);

	// If they have a cookie, but no matching session, lets blow away their cookie
	if (!$rs)
	{
		logoutUser();
	}

	$sess_name = $rs[0]['name'];
	$sess_uid = $rs[0]['userid'];

	print "<div style='float:right'>Logged in as $sess_name | <a href='/wishlist/index.php/".$sess_uid."'>My Wishlist</a> | <a href='/logout.php'>logout</a></div>";

}
else
	print "<div style='float:right'><a href='/login.php'>Login</a></div>";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
<link href="/res/css/styles.css" rel="stylesheet" type="text/css"/>
<link href="/res/css/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script src="/res/js/jquery.min.js"></script>
<script src="/res/js/jquery-ui.min.js"></script>
<script src="/res/js/jquery.expander.js"></script>
</head>
<body>
<h1><a href='/'>Gifto</a>!</h1>

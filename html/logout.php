<?php
require_once("header.php");


if (isset($_COOKIE['sessid']))
{
	dbquery("DELETE FROM sessions WHERE id = '".$_COOKIE['sessid']."'");

	logoutUser();
}

header("Location: /");

require_once("footer.php");

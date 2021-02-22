<?php
/*
 * Sanitise all incoming inputs.
 */
if (isset($_REQUEST))
{
	foreach($_REQUEST as $var=>$val)
	{
		$_SAFEREQUEST[$var] = mysql_escape_string(strip_tags(trim($val)));
	}
}

if (isset($_GET))
{
	foreach($_GET as $var=>$val)
	{
		$_SAFEGET[$var] = mysql_escape_string(strip_tags(trim($val)));
	}
}

if (isset($_POST))
{
	foreach($_POST as $var=>$val)
	{
		$_SAFEPOST[$var] = mysql_escape_string(strip_tags(trim($val)));
	}
}
?>

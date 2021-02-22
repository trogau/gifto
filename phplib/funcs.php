<?php
require_once("db.php");

function makeSafe($string, $noSqlEscape=0)
{
	if (trim($string) == "" || !$string)
		return false;

	if ($noSqlEscape==0)
		return mysql_escape_string(strip_tags(trim($string)));
	else
		return strip_tags(trim($string));

}

function logoutUser()
{
	print "Logout";
	$sql = "DELETE FROM sessions WHERE id = '".$_COOKIE['sessid']."'";
	setcookie('sessid', false, time(), "/");
	if (dbquery($sql))
		return true;
	else
		return false;

}

function loginUser($username, $password)
{
	$sql = "SELECT * FROM users
		WHERE email = '$username'
		AND password = PASSWORD('$password')";

	$rs = dbquery($sql);

	if ($rs)
	{
		$rand = rand(100,999);

		$sess_rand = md5($rs[0]['name']."-".$rand);
		$sql = "INSERT INTO sessions VALUES ('".$sess_rand."', ".$rs[0]['id'].", ".time().")";
		dbquery($sql);
		setcookie('sessid', "$sess_rand", (time()+36000), "/");

		return true;
	}
	else
		return false;
}


?>

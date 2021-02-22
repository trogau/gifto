<?
require_once("config.php");

/*
 * Globals
 */
$dbh = false;

$debugsql = false;
if (isset($_GET['debugsql']))
	$debugsql = true;

/*
 * dbconnect()
 * Connect to a database
 */
function dbconnect()
{
	global $dbh;

	if (!$dbh)
	{
		$dbh = mysql_connect(DBHOST, DBUSER, DBPASS);
		if (!$dbh)
			die("ERROR: Can't connect to database.");

		mysql_select_db("gifto", $dbh);

	}
}

function dbquery($sql)
{
	global $dbh, $debugsql;

	if (!$dbh)
		dbconnect();

	if ($debugsql)
		print $sql."<br />";

	$q = mysql_query($sql, $dbh);

	if ($q === TRUE)
		return $q;

	$rs = array();

	if ($q)
	{
		for ($i = 0, $j = mysql_num_rows($q); $i<$j;$i++)
		{
			$rs[$i] = mysql_fetch_array($q, MYSQL_BOTH);
		}
	}
	else
	{
		if ($debugsql)
			print mysql_error()."<br />";
		return false;
	}

	return $rs;
}

function dblastid()
{
	global $dbh;

	return mysql_insert_id($dbh);
}


?>

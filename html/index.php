<?php
require_once("header.php");

if (isset($sess_uid))
{
?>

<h3>Things you can do:</h3>

<ul>
	<li><a href="/wishlist/index.php/<?=$sess_uid?>">Set up your wishlist</a></li>
	<li><a href="/invite/">Invite Users</a></li>
</ul>


<h3>Other people's wishlists:</h3>

<?php

	$sql = "SELECT u.id,u.name FROM useraccess a, users u
		WHERE a.reader = $sess_uid
		AND a.owner = u.id";

	$rs = dbquery($sql);

	if ($rs)
		print "<ul>\n";

	for ($i = 0; $i < sizeof($rs); $i++)
	{
		$l_name = $rs[$i]['name'];
		$l_id = $rs[$i]['id'];

		?>
		 <li><a href="/wishlist/index.php/<?=$l_id?>"><?=$l_name?></li>
		<?

	}

	if ($rs)
		print "</ul>\n";



}



require_once("footer.php");

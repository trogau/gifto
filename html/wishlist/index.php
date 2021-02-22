<?php
require_once("header.php");

if (!isset($_COOKIE['sessid']))
{
	header("Location: /login.php");
	die();
}

$bits = explode("/",$_SERVER['PHP_SELF']);

if (isset($_SAFEGET['delgift']) && isset($sess_uid))
{
	if (isset($bits[4]))
	{
		$occid = intval($bits[4]);

		// Delete the gift
		$sql = "DELETE FROM gifts
			WHERE userid = $sess_uid
			AND id = ".$_SAFEGET['delgift'];

		if (!dbquery($sql))
			print "Error dropping gift<br />";

	}

	header("Location: /wishlist/index.php/".$bits[3]."/".$bits[4]);
}

if (isset($_SAFEGET['selgift']) && isset($sess_uid))
{
	if (isset($bits[4]))
	{
		$occid = intval($bits[4]);

		$sql = "UPDATE gifts SET selectedbyid = ".$sess_uid."
			WHERE id = ".$_SAFEGET['selgift'];

		if (!dbquery($sql))
			print "Error Selecting gift<br />";

	}

	header("Location: /wishlist/index.php/".$bits[3]."/".$bits[4]);
}
if (isset($_SAFEGET['unselgift']) && isset($sess_uid))
{
	if (isset($bits[4]))
	{
		$occid = intval($bits[4]);

		$sql = "UPDATE gifts SET selectedbyid = 0
			WHERE id = ".$_SAFEGET['unselgift']."
			AND selectedbyid = $sess_uid";

		if (!dbquery($sql))
			print "Error Selecting gift<br />";

	}

	header("Location: /wishlist/index.php/".$bits[3]."/".$bits[4]);
}



if (isset($_SAFEPOST['addgift']) && isset($_SAFEPOST['newocc']) && isset($sess_uid))
{
	$sql = "INSERT INTO gifts (userid, occasionid, summary, details, url, price, timestamp) 
		VALUES ('".$sess_uid."', '".$_SAFEPOST['newocc']."', '".$_SAFEPOST['newgift']."','".$_SAFEPOST['newdetails']."', '".$_SAFEPOST['newurl']."', '".$_SAFEPOST['newprice']."', '".time()."')";

	if (!dbquery($sql))
		die("ERROR ADDING GIFT");

	/*
	$sql = "INSERT INTO usergifts (occasionid, giftid, timestamp)
		VALUES (".$_SAFEPOST['newocc'].", ".dblastid().", ".time().")";
	dbquery($sql);
	*/
}

if (isset($_SAFEPOST['occname']) && isset($sess_uid))
{
	if ($_SAFEPOST['occname'] != "Occasion Name" && $_SAFEPOST['occname'] != "")
	{
		$occtime = strtotime($_SAFEPOST['occdate']);
		$sql = "INSERT INTO occasions (userid,name,eventtime,timestamp)
			VALUES ('".$sess_uid."', '".$_SAFEPOST['occname']."', '".$occtime."', '".time()."')";

		dbquery($sql);
	}
	else
	{
		print "ERROR: Invalid Occasion name!<br />";
	}
}

if (isset($bits[3]))
{
	$userid = intval($bits[3]);

	// Load the user information if specified
	$sql = "SELECT * FROM users
		WHERE id = $userid
		LIMIT 1";

	$urs = dbquery($sql);

	if (!$urs)
		die('invalid');

	// First, let's see if the viewing user has permission to look at this giftlist
	$sql = "SELECT * FROM useraccess 
		WHERE reader = $sess_uid
		AND owner = $userid";

	$rs = dbquery($sql);

	// If we get a result from the above query, then we have permission (OR we are that user)
	if ($rs || ($sess_uid == $userid))
	{
		// Get the list of occasions
		$sql = "SELECT o.name,o.id
				FROM occasions o
				WHERE o.userid = $userid
				AND o.eventtime > UNIX_TIMESTAMP()
				ORDER BY o.eventtime ASC";

		$rs = dbquery($sql);

		// Get a list of public occasions
		$sql = "SELECT o.name,o.id
			FROM occasions o
			WHERE o.userid = 0
			ORDER BY o.eventtime ASC";
		$rs2 = dbquery($sql);

		if (sizeof($rs) > 0)
			$rs = array_merge($rs, $rs2);
		else
		{
			$rs = $rs2;
		}


		// FIXME - needs another query to get the wishlist name if there
		// are no occasions yet
		if ($urs)
		{
			if (isset($urs[0]['name']))
			{
				$u_name = $urs[0]['name'];

				print "You are viewing the wishlist of: <b>$u_name</b><br />\n";
			}
		}

		print "<div style='float:left;width:150px; height:100%; border: solid 1px black'>";

		if ($userid == $sess_uid)
		{
		?>
		<script>
			$(function() {
				$("#datepicker").datepicker({dateFormat: 'dd-mm-yy'});
		});
		</script>
		<b>Add Occasion:</b><br />
		<form method="post">
		<input type="text" name="occname" value="Occasion Name">
		<input type="text" name="occdate" id="datepicker" value="Date">
		<input type="submit" name="addocc" value="Add Occasion">
		</form>

		<?php
		}

		print "<b>Occasions:</b><br /><br />\n";

		for ($i = 0; $i < sizeof($rs); $i++)
		{
			$oc_name = $rs[$i]['name'];
			$oc_id = $rs[$i]['id'];

			if (isset($bits[4]))
				if ($oc_id == $bits[4])
					$selected_event = $oc_name;

			print "<a href='/wishlist/index.php/$userid/$oc_id'>$oc_name</a><br />\n";
		}

		print "</div>\n";
	}
}
else
	die("Nope.");


if (isset($bits[4]))
{
	$occid = intval($bits[4]);
?>
<script type="text/javascript">
	$(document).ready(function() 
	{
		$('div.expandable form').expander({
		slicePoint:       0,  // default is 100
		expandText:         'Add new gift', // default is 'read more...'
		userCollapseText: '(close)'  // default is '[collapse expanded text]'
		});
	});

	$(document).ready(function() 
	{
		$('div.expandableDeets').expander({
		slicePoint: 25,
		expandText: 'more',
		userCollapseText: '(close)' 
		});
	});
</script>
<?
	// if we're this user, allow them to add something
	if ($userid == $sess_uid)
	{
	?>

	<div class="expandable" style="margin-left:160px;border:1px solid green;padding:20px;">
	<form method="post">
	Gift name: <input type="text" name="newgift"><br />
	Estimated value (optional): 
	$<input type="text" name="newprice"><br />

	URL (optional):
	<input type="text" name="newurl"><br />

	Details, like size, colour preferences, etc: (optional)<br />
	<textarea name="newdetails"></textarea>
	<input type="hidden" name="newocc" value="<?=$occid?>">
	<input type="submit" name="addgift" value="Add gift">
	</form>
	</div>
	<?php
	}

	$rs = dbquery("SELECT * FROM gifts g 
			WHERE occasionid = ".$occid);

	print "<div style='top:200px;margin-left:160px;border: solid 1px red;'>\n";

	print "<b>Gifts for: $selected_event</b><br /><br />\n";

	for ($i = 0; $i < sizeof($rs); $i++)
	{
		$gift_summary = $rs[$i]['summary'];
		$gift_id = $rs[$i]['id'];
		$gift_price = $rs[$i]['price'];
		$gift_url = $rs[$i]['url'];
		$gift_details = $rs[$i]['details'];
		$gift_selected = $rs[$i]['selectedbyid'];

		print "<div style='height:100px;min-width:900px;'>\n";
		print "<div style='width:200px;float:left'>$gift_summary</div>\n";
		print "<div style='width:100px;float:left'>$$gift_price</div>\n";

		if ($gift_url != "")
			print "<div style='width:50px;float:left'><a href='$gift_url'>link</a></div>\n";
		else
			print "<div style='width:50px;float:left'>-</div>\n";

		if ($gift_details != "")
			print "<div class='expandableDeets' style='width:300px;float:left'>$gift_details</div>\n";
		else
			print "<div style='width:300px;float:left'>-</div>\n";

		if ($sess_uid != $userid)
		{
			if ($gift_selected == 0)
				print "<div style='width:200px;float:left'><a href='?selgift=$gift_id'>I'll get it!</a></div>\n";
			else if ($gift_selected == $sess_uid)
				print "<div style='width:200px;float:left'>You're buying this! <a href='?unselgift=$gift_id'>undo</a></div>\n";
			else
				print "<div style='width:200px;float:left'>Someone else is buying this!</div>\n";
		}

		if ($sess_uid == $userid)
			print "<div style='width:100px;float:left'><a href='?delgift=$gift_id'>delete</a></div>\n";
		print "</div>";
	}
	

	print "</div>\n";
}




require_once("footer.php");

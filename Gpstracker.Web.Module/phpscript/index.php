<?php
session_start();

$logged = $_SESSION['logged'];

if (!$logged) {
	header("location:/login.html");
} else {

	$mysql_host = "mysql6.000webhost.com";
	$mysql_database = "a8399093_gps";
	$mysql_user = "a8399093_anoj";
	$mysql_password = "anoj123";
	
	
	$con = mysql_connect($mysql_host, $mysql_user, $mysql_password);

	if (!$con) {
		die('Could not connect: ' . mysql_error());
	}

	mysql_select_db($mysql_database, $con);
	$user_id = $_SESSION['user'];
	$sql = "SELECT * FROM location WHERE user_id=$user_id";

	$result = mysql_query($sql);
	// Mysql_num_row is counting table row
	$count = mysql_num_rows($result);

	// If result matched $myusername and $mypassword, table row must be 1 row
	if ($count != 0) {
		$i = 0;
		$num = mysql_numrows($result);

		$map_arr = array();

		$map = "";

		while ($i < $num) {

			$f1 = mysql_result($result, $i, "atti");
			$f2 = mysql_result($result, $i, "lon");
			$f3 = mysql_result($result, $i, "time");
			$i++;
			$json_array = array();
			$json_array[0] = $f1;
			$json_array[1] = $f2;
			$json_array[2] = $f3;
			$json_array[3] = $i;

			$map_arr[$i] = $map_arr;

			$map .= "'$f3',$f1,$f2,$i // ";

		}

		$map = substr_replace($map, "", -1);
		$map .= "";

		echo $map;
	}

}

//echo "'2012-08-18 23:02:24', 6.7065, 80.0689, 1 / '2012-08-18 23:52:55', 6.7065, 80.069, 2 / '2012-08-18 23:52:54', 6.7065, 80.069, 3 / '2012-08-18 23:08:31', 6.92983500598709, 79.8501777648926, 4";
?>
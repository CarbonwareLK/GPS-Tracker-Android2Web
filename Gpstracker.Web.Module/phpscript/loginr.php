<?php
$mysql_host = "mysql6.000webhost.com";
$mysql_database = "a8399093_gps";
$mysql_user = "a8399093_anoj";
$mysql_password = "anoj123";

$email = $_POST['email'];
$password = $_POST['password'];

$con = mysql_connect($mysql_host, $mysql_user, $mysql_password);

if (!$con) {
	die('Could not connect: ' . mysql_error());
}

mysql_select_db($mysql_database, $con);

$sql = "SELECT * FROM user WHERE email='$email' and password='$password'";
echo $sql;
$result = mysql_query($sql);
// Mysql_num_row is counting table row
$count = mysql_num_rows($result);

echo $count;

// If result matched $myusername and $mypassword, table row must be 1 row
if ($count == 1) {
	//session_destroy();
	session_start();
	$row = mysql_fetch_array($result, MYSQL_ASSOC);

	// Register $myusername, $mypassword and redirect to file "login_success.php"
	session_register("userName");
	$userName = $row['id'];
	$_SESSION['user'] = $userName;
	$_SESSION['fname'] = $row['fname'];
	$_SESSION['logged'] = TRUE;
	echo "Pageviews = " . $_SESSION['fname'];
	header("location:/");

} else {
	echo "Wrong Username or Password";
}
?>
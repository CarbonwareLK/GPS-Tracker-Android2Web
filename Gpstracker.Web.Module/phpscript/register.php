<?php
$mysql_host = "mysql6.000webhost.com";
$mysql_database = "a8399093_gps";
$mysql_user = "a8399093_anoj";
$mysql_password = "anoj123";

$email = $_POST['email'];
$fname = $_POST['fname'];
$password = $_POST['password'];

$con = mysql_connect($mysql_host, $mysql_user, $mysql_password);

if (!$con) {
	die('Could not connect: ' . mysql_error());
}

mysql_select_db($mysql_database, $con);

$sql = "INSERT INTO user (id, email, fname, password) VALUES (NULL,'$email' ,'$fname','$password')";

mysql_query($sql);
mysql_close($con);

echo "Thankx For Register with us";

header("location:/");
?>
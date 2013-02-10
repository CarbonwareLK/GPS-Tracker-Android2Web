<?php

function getStatusCodeMessage($status) {
	$codes = Array(100 => 'Continue', 101 => 'Switching Protocols', 200 => 'OK', 201 => 'Created', 202 => 'Accepted', 203 => 'Non-Authoritative Information', 204 => 'No Content', 205 => 'Reset Content', 206 => 'Partial Content', 300 => 'Multiple Choices', 301 => 'Moved Permanently', 302 => 'Found', 303 => 'See Other', 304 => 'Not Modified', 305 => 'Use Proxy', 306 => '(Unused)', 307 => 'Temporary Redirect', 400 => 'Bad Request', 401 => 'Unauthorized', 402 => 'Payment Required', 403 => 'Forbidden', 404 => 'Not Found', 405 => 'Method Not Allowed', 406 => 'Not Acceptable', 407 => 'Proxy Authentication Required', 408 => 'Request Timeout', 409 => 'Conflict', 410 => 'Gone', 411 => 'Length Required', 412 => 'Precondition Failed', 413 => 'Request Entity Too Large', 414 => 'Request-URI Too Long', 415 => 'Unsupported Media Type', 416 => 'Requested Range Not Satisfiable', 417 => 'Expectation Failed', 500 => 'Internal Server Error', 501 => 'Not Implemented', 502 => 'Bad Gateway', 503 => 'Service Unavailable', 504 => 'Gateway Timeout', 505 => 'HTTP Version Not Supported');

	return (isset($codes[$status])) ? $codes[$status] : '';
}

function sendResponse($status = 200, $body = '', $content_type = 'text/html') {
	$status_header = 'HTTP/1.1 ' . $status . ' ' . getStatusCodeMessage($status);
	header($status_header);
	header('Content-type: ' . $content_type);
	echo $body;
}

class Gpstrackr {

	function __construct() {

	}

	function __destruct() {

	}

	function gps_tracker_update() {

		$mysql_host = "mysql6.000webhost.com";
		$mysql_database = "a8399093_gps";
		$mysql_user = "a8399093_anoj";
		$mysql_password = "anoj123";

		if (isset($_POST["atti"]) && isset($_POST["lon"]) && isset($_POST['user'])) {
			try {

				$ati = $_POST["atti"];
				$lon = $_POST["lon"];

				$meUserid = $_POST['user'];
				$meAtti = sprintf("%01.4f", $ati);
				$meLon = sprintf("%01.4f", $lon);

				$mysqli = new mysqli($mysql_host, $mysql_user, $mysql_password, $mysql_database);

				if (mysqli_connect_errno()) {
					echo "Connection Failed: " . mysqli_connect_errno();
					exit();
				}

				/* Create a prepared statement */

				$sql = "INSERT INTO location (time , user_id ,atti, lon) VALUES (NOW(),?,?,?)";
				$message = "";
				$stmt = $mysqli -> prepare($sql);
				//$message .= $stmt . "  ";
				if ($stmt) {

					/* Bind parameters
					 s - string, b - boolean, i - int, etc */

					$stmt -> bind_param('idd', $meUserid, $meAtti, $meLon);
					/* Execute it */
					$stmt -> execute();

					$message .= "updated";

					/* Close statement */
					$stmt -> close();
				} else {
					/* Error */
					printf("Prepared Statement Error: %s\n", $mysqli -> error);
				}

				/* Close connection */
				$mysqli -> close();

				//mysql_close($con);

				$message .= $meAtti . " " . $meLon . " " . $meUserid;

				//mail('dewmalnilanka@gmail.com', '$subject', $message);
				sendResponse(200, $message);
				return true;
			} catch (Exception $e) {
				$err = 'Caught exception: ' . $e -> getMessage() . "\n";
				sendResponse(200, $err);
				return false;
			}
		}

		sendResponse(400, 'Invalid request');
		return false;

	}

}

$api = new Gpstrackr;
$api -> gps_tracker_update();
?>
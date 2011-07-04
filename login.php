<?php
require_once('includes/config.inc.php');

$username = $_POST['username'];
$password = $_POST['password'];

if(!isset($_SESSION['id'])){
	$query = "SELECT ID,Username 
				FROM Users 
				WHERE Username='".mysql_real_escape_string($username)."' 
					AND Password='".mysql_real_escape_string(sha1($password.$appconf['salt']))."' 
				LIMIT 1";
	$result = mysql_query($query, $dbconn);
	
	if(!$result || mysql_num_rows($result) == 0){ 
		echo json_encode(array('error'=>"Invalid username or password.",'return'=>1));
		exit; 
	}
	$userinfo = mysql_fetch_assoc($result);
	$_SESSION['id'] = $userinfo['ID'];
	$_SESSION['username'] = $userinfo['Username'];
	echo json_encode(array('result'=>$userinfo['ID']." - ".$userinfo['Username'],'return'=>0));
}
?>
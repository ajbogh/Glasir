<?php
require_once('includes/config.inc.php');

if(isset($_GET['code'])){
	$query = "UPDATE Users 
			  SET (PasswordResetCode,
			  	PasswordResetTime)
			  VALUES (
			  	NULL,
			    NULL
			  )
			  WHERE PasswordResetCode='".mysql_real_escape_string($_GET['code'])."'";
	$result = mysql_query($query);
	echo "Your account has been activated. 
			Please return to <a href=\"http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['REQUEST_URI'])."\">Glasir</a> to log in.";
	exit;
}

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$messageCode = sha1($email);

//check values of variables first
if($username == '' || $email == '' || $password == ''){
	echo json_encode(array('error'=>"All fields are required.",'return'=>1));
	exit; 
}

if(strlen($username) < 4){
	echo json_encode(array('error'=>"Username must be at least 4 characters.",'return'=>1));
	exit; 
}
if(strlen($password) < 4){
	echo json_encode(array('error'=>"Password must be at least 4 characters.",'return'=>1));
	exit; 
}
//query for existing user
$query = "SELECT * FROM Users
		  WHERE Email='".mysql_real_escape_string($email)."'";
$result = mysql_query($query);
if(mysql_num_rows($result) > 0){ //update
	$query = "UPDATE Users 
			  SET Username='".mysql_real_escape_string($username)."',
			  	Password='".mysql_real_escape_string(sha1($password.$appconf['salt']))."',
			  	PasswordResetCode='".mysql_real_escape_string($messageCode)."',
			  	PasswordResetTime=NOW()
			  WHERE Email='".mysql_real_escape_string($email)."'";
	$result = mysql_query($query);
}else{ //insert
	$query = "INSERT INTO Users
			(Username, 
			 Password, 
			 Email, 
			 PasswordResetCode, 
			 PasswordResetTime)
		  VALUES (
		     '".mysql_real_escape_string($username)."',
		     '".mysql_real_escape_string(sha1($password.$appconf['salt']))."',
		     '".mysql_real_escape_string($email)."',
		     '".mysql_real_escape_string($messageCode)."',
		     NOW()
		  )";
	$result = mysql_query($query);
}

$message = "Please click the link below to verify your Glasir account:\n\n
			"."http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['REQUEST_URI']).DIRECTORY_SEPARATOR."register.php?code=".$messageCode;
$headers = 'From: opencp@'.$appconf['maildomain']."\r\n" .
    'Reply-To: '.$appconf['adminreplyto']."\r\n" .
    'X-Mailer: PHP/' . phpversion();

$success = true;
if($appconf['allowemail']){
	$success = mail($email,"Glasir - Verify your account.",$message,$headers);
}
if(!$success){
	echo json_encode(array('error'=>'There was an error sending the message','return'=>1));
}else{
	if($appconf['allowemail']){
		echo json_encode(array('result'=>'Please check your email to verify the account.','return'=>0));
	}else{
		echo json_encode(array('result'=>'Please click <a href="'."http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['REQUEST_URI']).DIRECTORY_SEPARATOR."register.php?code=".$messageCode.'">this link</a> to verify your account.','return'=>0));
	}
}


?>
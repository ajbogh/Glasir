<?php
	ini_set("display_errors",1);
	ini_set("error_reporting",E_ALL);
	error_reporting(E_ALL);
	session_start();
	
	header('Content-Type: text/html; charset=utf-8');


	$appconf['mediaFolder'] = '/media/music';
	$appconf['salt'] = '87584f7e2bc94097b614d00a672c8833';
	$appconf['database'] = 'glasir';
	$appconf['dbuser'] = 'opencp';
	$appconf['dbpassword'] = 'Edsel1958';
	$appconf['dbserver'] = 'localhost';
	$appconf['maildomain'] = 'freeacademicpapers.com';
	$appconf['adminreplyto'] = 'ajbogh@allanbogh.com';
	$appconf['allowemail'] = false;
	
	$allowedExtensions = array('mp3','ogg','wma');

	//MySQL Stuff - Do Not Edit Below
	$dbconn = mysql_connect($appconf['dbserver'],$appconf['dbuser'],$appconf['dbpassword']) or die("Database connection error.");
	$selected = mysql_select_db($appconf['database'],$dbconn);
	if(!$selected){
		 die("Database selection error.");
	}
?>
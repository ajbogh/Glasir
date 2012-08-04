<?php

if(isset($_GET['myaction'])){
	try{
		require_once '../includes/config.inc.php';
		
		$dbfunc = new DBFunctions($dbconn);
		echo json_encode($dbfunc->$_GET['myaction']($_GET));
	}catch(Exception $e){
		echo $e;
	}
}


if(isset($_POST['action'])){
	try{
		require_once '../includes/config.inc.php';
		
		$dbfunc = new DBFunctions($dbconn);
		echo json_encode($dbfunc->$_POST['action']($_POST));
	}catch(Exception $e){
		echo $e;
	}
}


class DBFunctions{
	private $dbconn;
	
	function __construct($dbconn){
		$this->dbconn = $dbconn;
	}
	
	//$args['keyword']
	function searchDB($args){
		$query = "SELECT ID, Title, Filename 
				  FROM Songs
				  WHERE Filename LIKE '%".mysql_real_escape_string($args['keyword'])."%'";

		$result = mysql_query($query,$this->dbconn);

		if(mysql_num_rows($result) != 0){
			$resultarr = array();
			while ($row = mysql_fetch_assoc($result)) {
			    $resultarr[] = $row;
			}
			return $resultarr;
		}else{
			return array();
		}
	}
}

?>
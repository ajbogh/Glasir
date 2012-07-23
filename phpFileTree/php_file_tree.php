<?php
//TODO: make this namespaced and autoloaded later.
include('PHPFileTree.php');

$seconds_to_cache = 604800;
$ts = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache) . " GMT";
header("Expires: $ts");
header("Pragma: cache");
header("Cache-Control: max-age=$seconds_to_cache");

if(isset($_GET['dir'])){
	ini_set('display_errors', 1);
	 error_reporting(E_ALL);
 
	$pft = new PHPFileTree($_GET);
	$pft->fixDirectory(); //makes sure directory string matches what is expected.
	$fileArr = $pft->getFileArray(null,//url_decode($_GET['return_link'])
		(isset($_GET['extensions'])?$_GET['extensions']:array())
	);
	
	if(isset($_GET['output']) && isset($_GET['output']) == 'json'){
		$code = $pft->getJSON($fileArr);
		echo json_encode($code);
	}else{
		$code = $pft->getHTML($fileArr);
		echo $code;
	}
}
?>
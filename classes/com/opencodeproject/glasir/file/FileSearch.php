<?php
namespace com\opencodeproject\glasir\file;
	
if(isset($_GET['action'])){
	$fs = new FileSearch();
	try{
		echo json_encode($fs->$_GET['action']($_GET));
	}catch(Exception $e){
		echo $e;
	}
}


class FileSearch{
	public function __construct(){}
	
	
	public function rglob($args){
		$pattern = (isset($args['pattern'])?$args['pattern']:'*');
		$flags = (isset($args['flags'])?$args['flags']:0);
		$path = (isset($args['path'])?$args['path']:'');
		
	    $paths=glob($path.'*', GLOB_MARK);//|GLOB_ONLYDIR|GLOB_NOSORT
	    
	    echo $path.'/'.$pattern;
	    $files=glob($path.'/'.$pattern, $flags);
	     foreach ($paths as $path) { $files=array_merge($files,rglob($pattern, $flags, $path)); }
	     return $files;
	}
	
}

?>
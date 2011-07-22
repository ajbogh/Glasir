<?php
/*
PHP File List
	@author Allan Bogh - http://www.opencodeproject.com
	@version 0.1
	@description - PHP file list returns an array file paths and names within a direcotry which match the extensions provided.
	@copyright - Redistribution and use in source and binary forms, with or without modification, 
						are permitted provided that the following conditions are met:

						1. Redistributions of source code must retain the above copyright notice, this list 
						of conditions and the following disclaimer.
						2. Redistributions in binary form must reproduce the above copyright notice, this list of 
						conditions and the following disclaimer in the documentation and/or other materials 
						provided with the distribution.
						
						THIS SOFTWARE IS PROVIDED BY THE OPEN CODE PROJECT ``AS IS'' AND ANY EXPRESS OR IMPLIED 
						WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY 
						AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE FREEBSD 
						PROJECT OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, 
						OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS 
						OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND 
						ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE 
						OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE 
						POSSIBILITY OF SUCH DAMAGE.

						The views and conclusions contained in the software and documentation are those of the authors 
						and should not be interpreted as representing official policies, either expressed or 
						implied, of the Open Code Project.
	
	Based on:
		== PHP FILE TREE ==
		version 1?
		== AUTHOR ==
		Cory S.N. LaViska
		http://abeautifulsite.net/	
*/


function php_file_list($directory, $return_link, $extensions = array()) {
	// Remove trailing slash
	if( substr($directory, -1) == "/" ) $directory = substr($directory, 0, strlen($directory) - 1);
	return  php_file_list_dir($directory, $return_link, $extensions);
}


function php_file_list_dir($directory, $return_link, $extensions = array()) {
	// Recursive function called by php_file_tree() to list directories/files
	// Get and sort directories/files
	$ext = ".*\\(";
	$ext .= implode('\\|',$extensions);
	$ext .= "\\)";
	$output = shell_exec("find \"".$directory."\" -iregex '{$ext}' -printf '%p\n'");
	$files = explode("\n",$output);
	if($files[count($files)-1] == ''){
		unset($files[count($files)-1]);
	}
	return $files;
}

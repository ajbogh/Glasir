<?php
/*
	JQuery File Tree
	@author Allan Bogh - http://www.opencodeproject.com
	@version 0.1
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

if(isset($_GET['dir'])){ 
	echo php_file_tree($_GET['dir'],
		null,//url_decode($_GET['return_link'])
		(isset($_GET['extensions'])?$_GET['extensions']:array())
	);
}


function php_file_tree($directory, $return_link, $extensions = array()) {
	// Generates a valid XHTML list of all directories, sub-directories, and files in $directory
	// Remove trailing slash
	$code = '';
	if( substr($directory, -1) == "/" ) $directory = substr($directory, 0, strlen($directory) - 1);
	$code .= php_file_tree_dir($directory, $return_link, $extensions);
	return $code;
}


function php_file_tree_dir($directory, $return_link, $extensions = array()) {
	// Recursive function called by php_file_tree() to list directories/files
	// Get and sort directories/files
	$file;
	if($file = @scandir($directory)){
		natcasesort($file);
	}else{
		$directory = mb_convert_encoding($directory, 'CP850', 'UTF-8');
		if($file = @scandir($directory)){
			natcasesort($file);
		}else{
			echo "<li>can't open dir ".$directory."<br />";
			foreach(mb_list_encodings() as $chr){
    			echo mb_convert_encoding($directory, 'UTF-8', $chr)." : ".$chr."<br>";
			} 
			echo "</li>";
			$file = array($directory.' - Could not open. Check permissions.');
		}
	}
	// Make directories first
	$files = $dirs = array();
	foreach($file as $this_file) {
		if(is_dir("$directory/$this_file")){
			$dirs[] = $this_file;
		}else{
			$files[] = $this_file;	
		}
	}
	$file = array_merge($dirs, $files);
	
	// Filter unwanted extensions
	if( !empty($extensions) ) {
		foreach( array_keys($file) as $key ) {
			if( !is_dir("$directory/$file[$key]") ) {
				$ext = strtolower(substr($file[$key], strrpos($file[$key], ".") + 1));
				if( !in_array($ext, $extensions) ) unset($file[$key]);
			}
		}
	}
	
	
	$php_file_tree = "";
	if( $file != ".." && $file != "." ) { // Use 2 instead of 0 to account for . and .. "directories"
		$php_file_tree = "<ul>";
		foreach( $file as $this_file ) {
			if( $this_file != "." && $this_file != ".." ) {
				if( is_dir("$directory/$this_file") ) {
					// Directory
					$directory = mb_convert_encoding($directory,'UTF-8', 'CP850');
					$this_file = mb_convert_encoding($this_file, 'UTF-8', 'CP850');
					$php_file_tree .= "<li class=\"pft-directory\" onmousedown=\"MoveLi(this);\" onmouseup=\"DropLi(this);\">
									<span onclick=\"getFileList('".addslashes($directory)."/".addslashes($this_file)."',this.parentNode);\">" . 
										htmlspecialchars($this_file). 
									"</span>";
										
					$php_file_tree .= "</li>";
					
					
				} else {
					$cdirectory = mb_convert_encoding($directory,'CP850', 'UTF-8');
					$cthis_file = mb_convert_encoding($this_file, 'CP850', 'UTF-8');
					if( is_dir("$cdirectory/$cthis_file") ) {
						$php_file_tree .= "<li class=\"pft-directory\" onmousedown=\"MoveLi(this);\" onmouseup=\"\">
										<span onclick=\"getFileList('".addslashes($directory)."/".addslashes($this_file)."',this.parentNode);\">" . 
											htmlspecialchars($this_file). 
										"</span>";
						$php_file_tree .= "</li>";
					}else{
						// File
						// Get extension (prepend 'ext-' to prevent invalid classes from extensions that begin with numbers)
						$ext = "ext-" . substr($this_file, strrpos($this_file, ".") + 1); 
						$php_file_tree .= "<li class=\"pft-file " . strtolower($ext) . "\" onmousedown=\"MoveLi(this);\" onmouseup=\"DropLi(this);\"><a href=\"javascript:void(0);\" onclick=\"queue('".addslashes($directory)."/".addslashes($this_file)."');\">" . htmlspecialchars($this_file) . "</a></li>";
					}
				}					
			}
		}
		$php_file_tree .= "</ul>";
	}
	return $php_file_tree;
}

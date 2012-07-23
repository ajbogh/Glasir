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

class PHPFileTree{
	private $directory = "";
	private $output;
	private $extensions = array();
	
	public function __construct($args){
		$this->directory = $args['dir'];
		if(isset($args['extensions'])){
			if(is_string($args['extensions'])){
				$this->extensions = explode(',',$args['extensions']);
			}else{
				$this->extensions = $args['extensions'];
			}
		}
	}
	
	public function fixDirectory() {
		// Remove trailing slash
		if( substr($this->directory, -1) == "/" ) $this->directory = substr($this->directory, 0, strlen($this->directory) - 1);
	}
	
	
	public function getFileArray($return_link, $extensions = array()) {
		$directory = $this->directory;
		// Get and sort directories/files
		$file;
		if($file = @scandir($directory)){
			natcasesort($file);
		}else{
			$directory = mb_convert_encoding($directory, 'CP850', 'UTF-8');
			if($file = @scandir($directory)){
				natcasesort($file);
			}else{
				/*foreach(mb_list_encodings() as $chr){
	    			echo mb_convert_encoding($directory, 'UTF-8', $chr)." : ".$chr."<br>";
				} */
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
		if( !empty($this->extensions) ) {
			foreach( array_keys($file) as $key ) {
				if( !is_dir("$directory/$file[$key]") ) {
					$ext = strtolower(substr($file[$key], strrpos($file[$key], ".") + 1));
					if( !in_array($ext, $this->extensions) ) unset($file[$key]);
				}
			}
		}
		
		return $file;
	}

	public function getJSON($file){
		$directory = $this->directory;
		$directory = mb_convert_encoding($directory, 'HTML-ENTITIES', mb_detect_encoding($directory));
		$php_file_tree = array();
		//don't think this next line is needed anymore
		if( $file != ".." && $file != "." ) { // Use 2 instead of 0 to account for . and .. "directories"
			foreach( $file as $this_file ) {
				if( $this_file != "." && $this_file != ".." ) {
						
					$this_file = mb_convert_encoding($this_file, 'HTML-ENTITIES', mb_detect_encoding($this_file));
					
					if(strrpos($this_file,"Could not open. Check permissions.") !== FALSE){
						$php_file_tree[] = (Object)array(
							"entry"=>$this_file,
							"type"=>"error"
						);
					}else if( is_dir("$directory/$this_file") ) {
						// Directory
						$php_file_tree[] = (Object)array(
							"entry"=>$this_file,
							"type"=>"directory",
							"fullpath"=>$directory."/".$this_file
						);
					}else {
						// File
						// Get extension (prepend 'ext-' to prevent invalid classes from extensions that begin with numbers)
						//$ext = "ext-" . substr($this_file, strrpos($this_file, ".") + 1); 
						$php_file_tree[] = (Object)array(
							"entry"=>$this_file,
							"type"=>"file",
							"fullpath"=>$directory."/".$this_file
						);
					
					}					
				}
			}
		}
		return $php_file_tree;
	}
	
	public function getHTML($file){
		$directory = $this->directory;
		$php_file_tree = "";
		if( $file != ".." && $file != "." ) { // Use 2 instead of 0 to account for . and .. "directories"
			$php_file_tree = "<ul>";
			foreach( $file as $this_file ) {
				if( $this_file != "." && $this_file != ".." ) {
					if(strrpos($this_file,"Could not open. Check permissions.") !== FALSE){
						$php_file_tree .= "<li>Can't open dir ".$directory."<br /></li>";
					}else if( is_dir("$directory/$this_file") ) {
						// Directory
						$directory = mb_convert_encoding($directory,'UTF-8', 'CP850');
						$this_file = mb_convert_encoding($this_file, 'UTF-8', 'CP850');
						$php_file_tree .= "<li class=\"pft-directory\" onmousedown=\"MoveLi(this);\" onmouseup=\"DropLi(this);\">
										<span onclick=\"getFileList('".addslashes($directory)."/".addslashes($this_file)."',this.parentNode);\">" . 
											htmlspecialchars($this_file). 
										"</span>";
											
						$php_file_tree .= "</li>";
						
						
					}else {
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
}

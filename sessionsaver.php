<?php
	//this webpage is called to keep a session alive
	require("includes/config.inc.php");

	//it can also be used for various processing tasks
	//such as indexing audio files in the database
	include("phpFileTree/php_file_list.php");
	
	//get the array of files
	$files = php_file_list($appconf['mediaFolder'], null, $allowedExtensions);

	//add only non-indexed files to the database.
	/*echo "<pre>";
	print_r($files);
	echo "</pre>";*/
	
	$updatecount = 0;
	$count = 0;
	foreach($files as $file){
		//select from database
		$query = "SELECT ID, Title, LastUpdated 
				  FROM Songs
				  WHERE Filename='".mysql_real_escape_string($file)."'";
		$result = mysql_query($query,$dbconn);
		if(mysql_num_rows($result) == 0){
			$query = "INSERT INTO Songs
					  (Filename, Filesize, LastUpdated) 
					  VALUES ('".mysql_real_escape_string($file)."',
					  		  '".filesize($file)."',
					  		  NOW())";
			$result = mysql_query($query,$dbconn);
		}else{
			//get the title information from the database to see if it's even set.
			//title is always set as either the ID3 info or the filename
			$row = mysql_fetch_assoc($result); 
			if(is_null($row['Title']) || $row['Title'] == ''){ //has never been inserted
				if($count == 10) continue; //perform initial updates on up to 10 songs
				
				require_once('includes/getid3/getid3.php');
			
				$thisMP3info = array();
				$thisMP3info = GetAllMP3info($file);
				//getid3_lib::CopyTagsToComments($thisMP3info);
				$info = array('artist'=>'','track'=>'','title'=>'','comment'=>'','genre'=>'','year'=>'','album'=>'');
				if(isset($thisMP3info['id3'])){
					if(isset($thisMP3info['id3']['id3v1']['artist'])) $info['artist'] = $thisMP3info['id3']['id3v1']['artist']; // artist from any/all available tag formats
					if(isset($thisMP3info['id3']['id3v1']['track'])) $info['track'] = $thisMP3info['id3']['id3v1']['track']; // artist from any/all available tag formats
					if(isset($thisMP3info['id3']['id3v1']['title'])) $info['title'] = $thisMP3info['id3']['id3v1']['title']; // title from ID3v2
					if(isset($thisMP3info['id3']['id3v1']['comment'])) $info['comment'] = $thisMP3info['id3']['id3v1']['comment'];
					if(isset($thisMP3info['id3']['id3v1']['genre'])) $info['genre'] = $thisMP3info['id3']['id3v1']['genre'];
					if(isset($thisMP3info['id3']['id3v1']['year'])) $info['year'] = $thisMP3info['id3']['id3v1']['year'];
					if(isset($thisMP3info['id3']['id3v1']['album'])) $info['album'] = $thisMP3info['id3']['id3v1']['album'];
				}else{
					$info['title'] = basename($file);
				}
				if($info['title'] == '') $info['title'] = basename($file); //fix title if it can't be set by broken ID3 info
				if(isset($thisMP3info['bitrate'])) $info['bitrate'] = $thisMP3info['bitrate']; // audio bitrate
				if(isset($thisMP3info['playtime_seconds'])) $info['duration'] = $thisMP3info['playtime_seconds']; // playtime in seconds
				
				$query = "UPDATE Songs
						  SET Title='".mysql_real_escape_string($info['title'])."',
						      Artist='".mysql_real_escape_string($info['artist'])."',
						      Bitrate='".mysql_real_escape_string($info['bitrate'])."',
						      Duration='".mysql_real_escape_string($info['duration'])."',
						      Track='".mysql_real_escape_string($info['track'])."',
						      Comment='".mysql_real_escape_string($info['comment'])."',
						      Genre='".mysql_real_escape_string($info['genre'])."',
						      Year='".mysql_real_escape_string($info['year'])."',
						      Album='".mysql_real_escape_string($info['album'])."',
						      LastUpdated=NOW()
						  WHERE Filename='".mysql_real_escape_string($file)."'";
				$result = mysql_query($query,$dbconn);
				$count++;
			}else{
				if(is_null($row['LastUpdated']) || (time() - strtotime($row['LastUpdated'])) > 604800){ //number of seconds in a week
					//perform an update on up to 5 files
					if($updatecount == 5) continue;
					
					require_once('includes/getid3/getid3.php');
				
					$thisMP3info = GetAllMP3info($file);
					//getid3_lib::CopyTagsToComments($thisMP3info);
					$info = array('artist'=>'','track'=>'','title'=>'','comment'=>'','genre'=>'','year'=>'','album'=>'');
					if(isset($thisMP3info['id3'])){
						if(isset($thisMP3info['id3']['id3v1']['artist'])) $info['artist'] = $thisMP3info['id3']['id3v1']['artist']; // artist from any/all available tag formats
						if(isset($thisMP3info['id3']['id3v1']['track'])) $info['track'] = $thisMP3info['id3']['id3v1']['track']; // artist from any/all available tag formats
						if(isset($thisMP3info['id3']['id3v1']['title'])) $info['title'] = $thisMP3info['id3']['id3v1']['title']; // title from ID3v2
						if(isset($thisMP3info['id3']['id3v1']['comment'])) $info['comment'] = $thisMP3info['id3']['id3v1']['comment'];
						if(isset($thisMP3info['id3']['id3v1']['genre'])) $info['genre'] = $thisMP3info['id3']['id3v1']['genre'];
						if(isset($thisMP3info['id3']['id3v1']['year'])) $info['year'] = $thisMP3info['id3']['id3v1']['year'];
						if(isset($thisMP3info['id3']['id3v1']['album'])) $info['album'] = $thisMP3info['id3']['id3v1']['album'];
					}else{
						$info['title'] = basename($file);
					}
					if($info['title'] == '') $info['title'] = basename($file); //fix title if it can't be set by broken ID3 info
					if(isset($thisMP3info['bitrate'])) $info['bitrate'] = $thisMP3info['bitrate']; // audio bitrate
					if(isset($thisMP3info['playtime_seconds'])) $info['duration'] = $thisMP3info['playtime_seconds']; // playtime in seconds
					
					$query = "UPDATE Songs
							  SET Title='".mysql_real_escape_string($info['title'])."',
							      Artist='".mysql_real_escape_string($info['artist'])."',
							      Bitrate='".mysql_real_escape_string($info['bitrate'])."',
							      Duration='".mysql_real_escape_string($info['duration'])."',
							      Track='".mysql_real_escape_string($info['track'])."',
							      Comment='".mysql_real_escape_string($info['comment'])."',
							      Genre='".mysql_real_escape_string($info['genre'])."',
							      Year='".mysql_real_escape_string($info['year'])."',
							      Album='".mysql_real_escape_string($info['album'])."'
							      LastUpdated=NOW()
							  WHERE Filename='".mysql_real_escape_string($file)."'";
					$result = mysql_query($query,$dbconn);
					$updatecount++;
				}
			}
		}
	
	}
?>

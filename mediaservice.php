<?php
require_once 'includes/config.inc.php';

$track = htmlspecialchars_decode($_GET['file']);
$type = null;
if(isset($_GET['type'])) $type = $_GET['type'];
$duration = false;
if(isset($_GET['duration'])) $duration = true;

//prevent disallowed files.
if(strpos($track,$appconf['mediaFolder']) === false){
	echo $track." is not within the allowed media folder: ".$appconf['mediaFolder'];
	exit;
}
$ext = substr($track,strrpos($track,'.')+1);
if(!in_array($ext,$allowedExtensions)){
	echo $ext." not allowed in allowedExtensions.";
	exit;
}

if(file_exists($track)){
	if(!$duration){
		//load the information into the database
		$query = "SELECT ID, Title, LastUpdated 
				  FROM Songs
				  WHERE Filename='".mysql_real_escape_string($track)."'";
		$result = mysql_query($query,$dbconn);
		if(mysql_num_rows($result) == 0){
			$query = "INSERT INTO Songs
					  (Filename, Filesize, LastUpdated) 
					  VALUES ('".mysql_real_escape_string($track)."',
					  		  '".filesize($track)."',
					  		  NOW())";
			$result = mysql_query($query,$dbconn);
		}
		
		header("Content-Transfer-Encoding: binary"); 
		switch($type){
			case "ogg":
				header("Content-Type: audio/ogg");
				header('Content-Disposition: filename="'.basename($track).'.ogg"');
				break;
			case "mp3":
				header("Content-Type: audio/mpeg, audio/x-mpeg, audio/x-mpeg-3, audio/mpeg3");
				header('Content-Disposition: filename="'.basename($track).'"');
				break;
			default:
				header("Content-Type: audio/mpeg, audio/x-mpeg, audio/x-mpeg-3, audio/mpeg3");
				header('Content-Disposition: filename="'.basename($track).'"');
		}
		header("X-Pad: avoid browser bug");
		
		ob_clean();
	    flush();
	    //check if requested type should be ogg
	    if($type == 'ogg' && $ext == 'mp3'){
	    	//convert to ogg
	    	//copy to a temp directory (777 or php owner) in the web directory 
	    	//php safe mode only allows exec within a safe dir
	    	if(!file_exists('/tmp/'.basename($track).'.ogg')){
		    	copy($track,'/tmp/'.basename($track));
		    	//set up the command to convert the file
		    	$command = 'mpg321 "'.'/tmp/'.basename($track).'" -w "'.'/tmp/'.basename($track).'.raw" && oggenc "'.'/tmp/'.basename($track).'.raw" -o "'.'/tmp/'.basename($track).'.ogg" && rm -f "'.'/tmp/'.basename($track).'.raw"';
		    	$out = shell_exec($command);
		    	//clean up copied mp3
		    	unlink('/tmp/'.basename($track));
	    	}
	    	$track = '/tmp/'.basename($track).'.ogg';
	    }
	    header('Content-length: ' . filesize($track));
		readfile($track);
		
		
		exit;
	}else{  //get the duration
		//query the dataabase to determine if it has the duration 
		//return value from database unless it's 0

		$query = "SELECT ID, Title, Track, Artist, Comment, Genre, Year, Album, Bitrate, Duration, LastUpdated 
				  FROM Songs
				  WHERE Filename='".mysql_real_escape_string($track)."'";
		$result = mysql_query($query,$dbconn);
		if(mysql_num_rows($result) != 0){
			$row = mysql_fetch_assoc($result);
			if(!is_null($row['Duration']) && $row['Duration'] > 0 && !is_null($row['Bitrate']) && $row['Bitrate'] > 0){
				$info = array('artist'=>'','track'=>'','title'=>'','comment'=>'','genre'=>'','year'=>'','album'=>'','bitrate'=>'','duration'=>'');
				$info['artist'] = $row['Artist'];
				$info['track'] = $row['Track'];
				$info['title'] = $row['Title'];
				$info['comment'] = $row['Comment'];
				$info['genre'] = $row['Genre'];
				$info['year'] = $row['Year'];
				$info['album'] = $row['Album'];
				$info['bitrate'] = $row['Bitrate'];
				$info['duration'] = $row['Duration'];

				echo json_encode($info);
			}else{ //get the duration and put it in the database
				require_once('includes/getid3/getid3.php');

				$thisMP3info = GetAllMP3info($track);
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
					$info['title'] = basename($track);
				}
				if($info['title'] == '') $info['title'] = basename($track); //fix title if it can't be set by broken ID3 info
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
						  WHERE Filename='".mysql_real_escape_string($track)."'";
				$result = mysql_query($query,$dbconn);
		
				echo json_encode($info);
			}
		}
	}
}else{
    echo "No file: ".$track;
}

?>

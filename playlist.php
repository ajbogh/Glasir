<?php
	require_once 'includes/config.inc.php';
	
	if(!isset($_SESSION['id'])) exit;
	
	$userid = $_SESSION['id'];
	
	if(isset($_POST['action'])){
		switch($_POST['action']){
			case 'queue':
				enqueue($userid,$dbconn);
				break;
			case 'remove':
				remove($userid,$dbconn);
				break;
			default:
				break;
		}	
	}else{ //no action set, just show playlist
		showPlaylist($userid,$dbconn);
	}
	
	function updateTrackInfo($track,$dbconn){
		require_once('includes/getid3/getid3.php');

		$thisMP3info = GetAllMP3info($track);
		//getid3_lib::CopyTagsToComments($thisMP3info);
		$info = array('artist'=>'','track'=>'','title'=>'','comment'=>'','genre'=>'','year'=>'','album'=>'');
		if(isset($thisMP3info['id3'])){
			if(isset($thisMP3info['id3']['id3v1'])){
				if(isset($thisMP3info['id3']['id3v1']['artist'])) $info['artist'] = $thisMP3info['id3']['id3v1']['artist']; // artist from any/all available tag formats
				if(isset($thisMP3info['id3']['id3v1']['track'])) $info['track'] = $thisMP3info['id3']['id3v1']['track']; // artist from any/all available tag formats
				if(isset($thisMP3info['id3']['id3v1']['title'])) $info['title'] = $thisMP3info['id3']['id3v1']['title']; // title from ID3v2
				if(isset($thisMP3info['id3']['id3v1']['comment'])) $info['comment'] = $thisMP3info['id3']['id3v1']['comment'];
				if(isset($thisMP3info['id3']['id3v1']['genre'])) $info['genre'] = $thisMP3info['id3']['id3v1']['genre'];
				if(isset($thisMP3info['id3']['id3v1']['year'])) $info['year'] = $thisMP3info['id3']['id3v1']['year'];
				if(isset($thisMP3info['id3']['id3v1']['album'])) $info['album'] = $thisMP3info['id3']['id3v1']['album'];
			}
		}else{
			$info['title'] = basename($track);
		}
		if($info['title'] == '') $info['title'] = basename($track); //fix title if it can't be set by broken ID3 info
		if(isset($info['bitrate'])) $info['bitrate'] = $thisMP3info['bitrate']; // audio bitrate
		else $info['bitrate']=0;
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
	}
	
	function showPlaylist($userid,$dbconn){
		$query = "SELECT Songs.*
				  FROM Users_Songs
				  		JOIN Songs ON Users_Songs.SongID=Songs.ID 
				  WHERE Users_Songs.UserID='".mysql_real_escape_string($userid)."'
				  	AND Users_Songs.InPlaylist=1 
				  	AND (Users_Songs.Disliked IS NULL OR Users_Songs.Disliked != 1)
				  ORDER BY Users_Songs.Order";
		$result = mysql_query($query,$dbconn);
		
		if($result){
			$numrows = mysql_num_rows($result);
			if($numrows > 0){
				//echo json_encode(array('error'=>print_r(mysql_fetch_assoc($result)),'return'=>1));				
				$rows = array();
				while ($row = mysql_fetch_assoc($result)){
					if(is_null($row['Title']) || $row['Title'] == ''){
						updateTrackInfo($row['Filename'], $dbconn);
						$row = mysql_query($query);
						$row = mysql_fetch_assoc($row);
					}
					$rows[] = $row;
				}
				echo json_encode(array('result'=>$rows,'return'=>0));

			}else{
				echo json_encode(array('error'=>'You do not have a playlist yet.','return'=>1));
			}
		}else{
			echo json_encode(array('error'=>'An error occurred getting your playlist. '.mysql_error($dbconn),'return'=>1));
		}	
	}
	
	function enqueue($userid,$dbconn){
		$query = "SELECT ID, Title
			  FROM Songs 
			  WHERE Filename='".mysql_real_escape_string(htmlspecialchars_decode($_POST['filename']))."'";
		$result = mysql_query($query,$dbconn);
		$songID = null;
		if(mysql_num_rows($result) == 0){
			//insert song
			$query = "INSERT INTO Songs
			  (Filename, LastUpdated) 
			  VALUES ('".mysql_real_escape_string(htmlspecialchars_decode($_POST['filename']))."', NOW())";
			$result = mysql_query($query,$dbconn);
			$songID = mysql_insert_id($dbconn);
			
			updateTrackInfo(htmlspecialchars_decode($_POST['filename']),$dbconn);
		}else{
			//get ID
			$songID = mysql_fetch_assoc($result);
			$songID = $songID['ID'];
			//this may take some time, but in Chrome it works fast enough.
			updateTrackInfo(htmlspecialchars_decode($_POST['filename']),$dbconn);
		}
		
		//get order so it's at the bottom of the list
		$query = "SELECT MAX(Users_Songs.Order) AS 'Order' 
			FROM Users_Songs
			WHERE UserID='".mysql_real_escape_string($userid)."'";
		$result = mysql_query($query,$dbconn);
		$order = mysql_fetch_assoc($result);
		$order = $order['Order'];
		if($order === '') $order = 0; 
		$order++;
		
		$query = "SELECT Songs.*
				  FROM Users_Songs
				      JOIN Songs ON Users_Songs.SongID=Songs.ID
			      WHERE SongID='".$songID."'
				      AND UserID='".mysql_real_escape_string($userid)."'";
		$result = mysql_query($query,$dbconn);
		
		
		if(mysql_num_rows($result) == 0){
			//insert into playlist
			$query = "INSERT INTO Users_Songs (
					SongID,
					UserID,
					InPlaylist,
					Users_Songs.Order
				) VALUES (
					'".$songID."',
					'".mysql_real_escape_string($userid)."',
					1,
					$order
				)";
			mysql_query($query,$dbconn);
			
		}else{ //update
			$query = "UPDATE Users_Songs
					SET InPlaylist=1,
						Liked=NULL,
						Disliked=NULL
					WHERE SongID='".$songID."'
					    AND UserID='".mysql_real_escape_string($userid)."'";
			mysql_query($query,$dbconn);
		}
		
		//select and return the newly added song
		$query = "SELECT Songs.*
				  FROM Users_Songs
				  		JOIN Songs ON Users_Songs.SongID=Songs.ID 
				  WHERE SongID='".$songID."'
				      AND UserID='".mysql_real_escape_string($userid)."'";
		$result = mysql_query($query,$dbconn);
		if($result){
			$numrows = mysql_num_rows($result);
			if($numrows > 0){
				$row = mysql_fetch_assoc($result);
				echo json_encode(array('result'=>array($row),'return'=>0));
			}
		}	
	}
	
	function remove($userid,$dbconn){
		$query = "SELECT ID
			  FROM Songs 
			  WHERE Filename='".mysql_real_escape_string(htmlspecialchars_decode($_POST['filename']))."'";
		$result = mysql_query($query,$dbconn);
		$songID = null;
		if(mysql_num_rows($result) != 0){
			//get ID
			$songID = mysql_fetch_assoc($result);
			$songID = $songID['ID'];
			
			$query = "DELETE 
				FROM Users_Songs
				WHERE SongID='".$songID."'
					AND UserID='".$userid."'";
			$result = mysql_query($query,$dbconn);
		}
	}
?>

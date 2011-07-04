<?php
require_once('includes/getid3/getid3.php');
$track = "/media/music/Aerosmith/Aerosmith - Dream On (Live With Orchestra).mp3";

		$thisMP3info = GetAllMP3info($track);
		//getid3_lib::CopyTagsToComments($thisMP3info);
		$info = array('artist'=>'','track'=>'','title'=>'','comment'=>'','genre'=>'','year'=>'','album'=>'');
		if(isset($thisMP3info['id3'])){
			$info['artist'] = $thisMP3info['id3']['id3v1']['artist']; // artist from any/all available tag formats
			if(isset($thisMP3info['id3']['id3v1']['track'])) $info['track'] = $thisMP3info['id3']['id3v1']['track']; // artist from any/all available tag formats
			$info['title'] = $thisMP3info['id3']['id3v1']['title']; // title from ID3v2
			$info['comment'] = $thisMP3info['id3']['id3v1']['comment'];
			$info['genre'] = $thisMP3info['id3']['id3v1']['genre'];
			$info['year'] = $thisMP3info['id3']['id3v1']['year'];
			$info['album'] = $thisMP3info['id3']['id3v1']['album'];
		}else{
			$info['title'] = basename($track);
		}
		if($info['title'] == '') $info['title'] = basename($track); //fix title if it can't be set by broken ID3 info
		$info['bitrate'] = $thisMP3info['bitrate']; // audio bitrate
		$info['duration'] = $thisMP3info['playtime_seconds']; // playtime in seconds
		
		echo "<pre>";
		print_r($thisMP3info);
		echo "</pre>";
?>
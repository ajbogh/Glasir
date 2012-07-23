<?php
//require_once('includes/getid3/getid3.php');
//http://localhost/Glasir/mediaservice.php?type=mp3&file=/media/Apollo/Music/Lenny%20Kravitz/%5BLenny%20Kravitz%5DLittle%20Girls%20Eyes.mp3
$track = "/media/Apollo/Music/Aerosmith/Aerosmith - Dream On (Live With Orchestra).mp3";

/*$thisMP3info = GetAllMP3info($track);
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
*/
//echo "<pre>";
//print_r($thisMP3info);
//echo "</pre>";

header("Content-Type: audio/mpeg");
//Content-Range:bytes 1460-1460/6613914
header('Content-Disposition: inline; filename="'.basename($track).'"');
header("Content-Transfer-Encoding: binary"); 
header("X-Pad: avoid browser bug");
header('Content-length: ' . (filesize($track)*1.10)); //Content-Length:1
header('Cache-Control: no-cache');
header('accept-ranges: bytes');
ob_clean();
flush();  
//readfile($track); //tried this too, same results
$fpOrigin=fopen($track, 'rb', false); //open a binary compatible stream
while(!feof($fpOrigin)){
  $buffer=fread($fpOrigin, 4096); //we read chunks of 4096 bytes
  echo $buffer; //And we send them back to the current user
  flush();
}
fclose($fpOrigin);
?>

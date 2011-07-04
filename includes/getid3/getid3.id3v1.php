<?php
////////////////////////////////////////////////////////////
/// getID3() by James Heinrich <getid3@silisoftware.com>  //
//       available at http://www.silisoftware.com        ///
////////////////////////////////////////////////////////////
//                                                        //
// getid3.id3v1.php - part of getID3()                    //
// See getid3.readme.txt for more details                 //
//                                                        //
////////////////////////////////////////////////////////////

function getID3v1Filepointer($fd) {
	$offset = 0;
	fseek($fd, -128, SEEK_END);
	$id3v1tag = fread($fd, 128);

	$id3v1info['title']   = trim(substr($id3v1tag,  3, 30));
	$id3v1info['artist']  = trim(substr($id3v1tag, 33, 30));
	$id3v1info['album']   = trim(substr($id3v1tag, 63, 30));
	$id3v1info['year']    = trim(substr($id3v1tag, 93,  4));
	$id3v1info['comment'] = substr($id3v1tag, 97, 30); // can't remove NULLs yet, track detection depends on them
	$id3v1info['genreid'] = ord(substr($id3v1tag, 127, 1));

	if ((substr($id3v1info['comment'], 28, 1) === chr(0)) && (substr($id3v1info['comment'], 29, 1) !== chr(0))) {
		$id3v1info['track'] = ord(substr($id3v1info['comment'], 29, 1));
		$id3v1info['comment'] = substr($id3v1info['comment'], 0, 28);
	}
	$id3v1info['comment'] = trim($id3v1info['comment']);
	$id3v1info['genre'] = LookupGenre($id3v1info['genreid']);

	return $id3v1info;
}
?>
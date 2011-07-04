<?php
////////////////////////////////////////////////////////////
/// getID3() by James Heinrich <getid3@silisoftware.com>  //
//       available at http://www.silisoftware.com        ///
////////////////////////////////////////////////////////////
//                                                        //
// write.php - part of getID3()                           //
// sample script for demonstrating writing ID3v1 and      //
// ID3v2 tags                                             //
// See getid3.readme.txt for more details                 //
//                                                        //
////////////////////////////////////////////////////////////

include_once('getid3.php');
include_once(GETID3_INCLUDEPATH.'getid3.putid3.php');
include_once(GETID3_INCLUDEPATH.'getid3.functions.php'); // Function library

if ($WriteID3v2TagNow) {
	echo 'starting to write tag<BR>';

	if ($EditorTitle) {
		$data['id3']['id3v2']['TIT2']['encodingid'] = 0;
		$data['id3']['id3v2']['TIT2']['data'] = stripslashes($EditorTitle);
	}
	if ($EditorArtist) {
		$data['id3']['id3v2']['TPE1']['encodingid'] = 0;
		$data['id3']['id3v2']['TPE1']['data'] = stripslashes($EditorArtist);
	}
	if ($EditorAlbum) {
		$data['id3']['id3v2']['TALB']['encodingid'] = 0;
		$data['id3']['id3v2']['TALB']['data'] = stripslashes($EditorAlbum);
	}
	if ($EditorYear) {
		$data['id3']['id3v2']['TYER']['encodingid'] = 0;
		$data['id3']['id3v2']['TYER']['data'] = (int) stripslashes($EditorYear);
	}
	if ($EditorTrack) {
		$data['id3']['id3v2']['TRCK']['encodingid'] = 0;
		$data['id3']['id3v2']['TRCK']['data'] = (int) stripslashes($EditorTrack);
	}
	if ($EditorGenre) {
		$data['id3']['id3v2']['TCON']['encodingid'] = 0;
		$data['id3']['id3v2']['TCON']['data'] = '('.$EditorGenre.')';
	}
	if ($EditorComment) {
		$data['id3']['id3v2']['COMM'][0]['encodingid'] = 0;
		$data['id3']['id3v2']['COMM'][0]['language'] = 'eng';
		$data['id3']['id3v2']['COMM'][0]['data'] = stripslashes($EditorComment);
	}

	if (is_uploaded_file($userfile)) {
		if ($fd = @fopen($userfile, 'rb')) {
			$data['id3']['id3v2']['APIC'][0]['data']          = fread($fd, filesize($userfile));
			fclose ($fd);

			$data['id3']['id3v2']['APIC'][0]['encodingid']    = (isset($EditorAPICencodingID)  ? $EditorAPICencodingID : 0);
			$data['id3']['id3v2']['APIC'][0]['picturetypeid'] = (isset($EditorAPICpictypeID)   ? $EditorAPICpictypeID  : 0);
			$data['id3']['id3v2']['APIC'][0]['description']   = (isset($EditorAPICdescription) ? $EditorAPICdescription : '');

			include_once(GETID3_INCLUDEPATH.'getid3.getimagesize.php');
			$imageinfo = GetDataImageSize($data['id3']['id3v2']['APIC'][0]['data']);
			$imagetypes = array(1=>'gif', 2=>'jpeg', 3=>'png');
			if (isset($imageinfo[2]) && ($imageinfo[2] >= 1) && ($imageinfo[2] <= 3)) {
				$data['id3']['id3v2']['APIC'][0]['mime']      = 'image/'.$imagetypes[$imageinfo[2]];
			} else {
    			echo '<B>invalid image format</B><BR>';
			}
    	} else {
    		echo '<B>cannot open $userfile</B><BR>';
    	}
	} else {
   		echo '<B>$userfile != is_uploaded_file()</B><BR>';
	}

	$data['id3']['id3v2']['TXXX'][0]['encodingid']  = 0;
	$data['id3']['id3v2']['TXXX'][0]['description'] = 'ID3v2-tagged by';
	$data['id3']['id3v2']['TXXX'][0]['data']        = 'getID3() v'.GETID3VERSION.' (www.silisoftware.com)';


	if ($WriteOrDelete == 'W') { // write tags
		if ($VersionToEdit1 == '1') {
			if (!is_numeric($EditorGenre)) {
				$EditorGenre = 255; // ID3v1 only supports predefined numeric genres (255 = unknown)
			}
			echo 'ID3v1 changes'.(WriteID3v1($EditorFilename, $EditorTitle, $EditorArtist, $EditorAlbum, $EditorYear, $EditorComment, $EditorGenre, $EditorTrack, TRUE) ? '' : ' NOT').' written successfully<BR>';
		}
		if ($VersionToEdit2 == '2') {
			echo 'ID3v2 changes'.(WriteID3v2($EditorFilename, $data, 3, 0, TRUE, 0, TRUE) ? '' : ' NOT').' written successfully<BR>';
		}
	} else { // delete tags
		if ($VersionToEdit1 == '1') {
			echo 'ID3v1 tag'.(RemoveID3v1($EditorFilename, TRUE) ? '' : ' NOT').' successfully deleted<BR>';
		}
		if ($VersionToEdit2 == '2') {
			echo 'ID3v2 tag'.(RemoveID3v2($EditorFilename, TRUE) ? '' : ' NOT').' successfully deleted<BR>';
		}
	}
}

echo '<A HREF="'.$PHP_SELF.'">Start Over</A><BR>';
echo '<TABLE BORDER="0"><FORM ACTION="'.$PHP_SELF.'" METHOD="POST" ENCTYPE="multipart/form-data">';
echo '<TR><TD ALIGN="CENTER" COLSPAN="2"><B>Sample ID3v2 editor</B></TD></TR>';
echo '<TR><TD ALIGN="RIGHT"><B>Filename</B></TD><TD><INPUT TYPE="TEXT" SIZE="40" NAME="EditorFilename" VALUE="'.FixTextFields($EditorFilename).'"></TD></TR>';
if ($EditorFilename) {
	if (file_exists($EditorFilename)) {
		$OldMP3fileInfo = GetAllMP3info($EditorFilename);
		$EditorTitle   = $OldMP3fileInfo['title'];
		$EditorArtist  = $OldMP3fileInfo['artist'];
		$EditorAlbum   = $OldMP3fileInfo['album'];
		$EditorYear    = $OldMP3fileInfo['year'];
		$EditorTrack   = $OldMP3fileInfo['track'];
		$EditorComment = $OldMP3fileInfo['comment'];
		if (isset($OldMP3fileInfo['genre'])) {
			$EditorGenre   = LookupGenre($OldMP3fileInfo['genre'], TRUE);
		} else {
			$EditorGenre   = 255;
		}
		echo '<TR><TD ALIGN="RIGHT"><B>Title</B></TD><TD><INPUT TYPE="TEXT" SIZE="40" NAME="EditorTitle" VALUE="'.FixTextFields($EditorTitle).'"></TD></TR>';
		echo '<TR><TD ALIGN="RIGHT"><B>Artist</B></TD><TD><INPUT TYPE="TEXT" SIZE="40" NAME="EditorArtist" VALUE="'.FixTextFields($EditorArtist).'"></TD></TR>';
		echo '<TR><TD ALIGN="RIGHT"><B>Album</B></TD><TD><INPUT TYPE="TEXT" SIZE="40" NAME="EditorAlbum" VALUE="'.FixTextFields($EditorAlbum).'"></TD></TR>';
		echo '<TR><TD ALIGN="RIGHT"><B>Year</B></TD><TD><INPUT TYPE="TEXT" SIZE="4" NAME="EditorYear" VALUE="'.FixTextFields($EditorYear).'"></TD></TR>';
		echo '<TR><TD ALIGN="RIGHT"><B>Track</B></TD><TD><INPUT TYPE="TEXT" SIZE="2" NAME="EditorTrack" VALUE="'.FixTextFields($EditorTrack).'"></TD></TR>';
		echo '<TR><TD ALIGN="RIGHT"><B>Genre</B></TD><TD><SELECT NAME="EditorGenre">';

		$ArrayOfGenres = ArrayOfGenres();   // get the array of genres
		unset($ArrayOfGenres['CR']);        // take off these special cases
		unset($ArrayOfGenres['RX']);
		unset($ArrayOfGenres[255]);
		asort($ArrayOfGenres);              // sort into alphabetical order
		$ArrayOfGenres[255]  = '-Unknown-'; // and put the special cases back on the end
		$ArrayOfGenres['CR'] = '-Cover-';
		$ArrayOfGenres['RX'] = '-Remix-';
		foreach ($ArrayOfGenres as $key => $value) {
			echo '<OPTION VALUE="'.$key.'"'.(($EditorGenre == $key) ? ' SELECTED' : '').'>'.$value.'</OPTION>';
		}
		echo '</SELECT></TD></TR>';

		echo '<TR><TD ALIGN="RIGHT"><B>Comment</B></TD><TD><TEXTAREA COLS="30" ROWS="3" NAME="EditorComment" WRAP="VIRTUAL">'.$EditorComment.'</TEXTAREA></TD></TR>';
		echo '<TR><TD ALIGN="RIGHT"><B>Picture</B></TD><TD><INPUT TYPE="FILE" NAME="userfile" ACCEPT="image/jpeg, image/gif, image/png"></TD></TR>';
		echo '<INPUT TYPE="HIDDEN" NAME="WriteID3v2TagNow" VALUE="1">';
		echo '<TR><TD ALIGN="CENTER" COLSPAN="2"><INPUT TYPE="RADIO" NAME="WriteOrDelete" VALUE="W" CHECKED> Write <INPUT TYPE="RADIO" NAME="WriteOrDelete" VALUE="D"> Delete</TD></TR>';
		echo '<TR><TD ALIGN="CENTER" COLSPAN="2"><INPUT TYPE="CHECKBOX" NAME="VersionToEdit1" VALUE="1"> ID3v1 <INPUT TYPE="CHECKBOX" NAME="VersionToEdit2" VALUE="2" CHECKED> ID3v2</TD></TR>';
		echo '<TR><TD ALIGN="CENTER" COLSPAN="2"><INPUT TYPE="SUBMIT" VALUE="Save Changes"> <INPUT TYPE="RESET" VALUE="Reset"></TD></TR>';
	} else {
		echo '<TR><TD ALIGN="RIGHT"><B>Error</B></TD><TD>'.FixTextFields($EditorFilename).' does not exist</TD></TR>';
		echo '<TR><TD ALIGN="CENTER" COLSPAN="2"><INPUT TYPE="SUBMIT" VALUE="Find File"></TD></TR>';
	}
} else {
	echo '<TR><TD ALIGN="CENTER" COLSPAN="2"><INPUT TYPE="SUBMIT" VALUE="Find File"></TD></TR>';
}
echo '</FORM></TABLE>';

?>
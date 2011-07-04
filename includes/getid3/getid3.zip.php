<?php
////////////////////////////////////////////////////////////
/// getID3() by James Heinrich <getid3@silisoftware.com>  //
//       available at http://www.silisoftware.com        ///
////////////////////////////////////////////////////////////
//                                                        //
// getid3.zip.php - part of getID3()                      //
// See getid3.readme.txt for more details                 //
//                                                        //
////////////////////////////////////////////////////////////

function getZipHeaderFilepointer($filename, &$MP3fileInfo) {
	if (!function_exists('zip_open')) {
		$MP3fileInfo['error'] = "\n".'Zip functions not available (requires at least PHP 4.0.7RC1 and ZZipLib (http://zziplib.sourceforge.net/) - see http://www.php.net/manual/en/ref.zip.php)';
		return FALSE;
	} else if ($zip = zip_open($filename)) {
		$zipentrycounter = 0;
		while ($zip_entry = zip_read($zip)) {
			$MP3fileInfo['zip']['entries']["$zipentrycounter"]['name']              = zip_entry_name($zip_entry);
			$MP3fileInfo['zip']['entries']["$zipentrycounter"]['filesize']          = zip_entry_filesize($zip_entry);
			$MP3fileInfo['zip']['entries']["$zipentrycounter"]['compressedsize']    = zip_entry_compressedsize($zip_entry);
			$MP3fileInfo['zip']['entries']["$zipentrycounter"]['compressionmethod'] = zip_entry_compressionmethod($zip_entry);
			//if (zip_entry_open($zip, $zip_entry, "r")) {
			//	$MP3fileInfo['zip']['entries']["$zipentrycounter"]['contents'] = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
			//	zip_entry_close($zip_entry);
			//}
			$zipentrycounter++;
		}
		zip_close($zip);
		return TRUE;
	} else {
		$MP3fileInfo['error'] = "\n".'Could not open file';
		return FALSE;
	}
}
?>
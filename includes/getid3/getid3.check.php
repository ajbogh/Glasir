<?php
////////////////////////////////////////////////////////////
/// getID3() by James Heinrich <getid3@silisoftware.com>  //
//       available at http://www.silisoftware.com        ///
////////////////////////////////////////////////////////////
//                                                        //
// check.php - part of getID3()                           //
// sample script for checking remote and local files      //
// See getid3.readme.txt for more details                 //
//                                                        //
////////////////////////////////////////////////////////////

include_once('getid3.php');
include_once(GETID3_INCLUDEPATH.'getid3.functions.php'); // Function library

$old_error_reporting = error_reporting(E_ALL);
// $old_error_reporting = error_reporting(E_ALL & ~(E_NOTICE));

if (isset($_GET['filename'])) {
	// support for running with register globals turned off
	// while maintaing pre-PHP4.1.0 compatibility
	// thanks to reel_taz@users.sourceforge.net
	$filename = $_GET['filename'];
}
if (isset($_GET['listdirectory'])) {
	$listdirectory = $_GET['listdirectory'];
}
if (isset($_GET['deletefile'])) {
	$deletefile = $_GET['deletefile'];
}

echo '<HTML><HEAD><TITLE>getID3() - getid3.check.php (sample script)</TITLE></HEAD><BODY>';

if (isset($deletefile)) {
	if (file_exists($deletefile)) {
		if (unlink($deletefile)) {
			echo '<SCRIPT LANGUAGE="JavaScript">alert("Successfully deleted '.addslashes($deletefile).'");</SCRIPT>';
		} else {
			echo '<SCRIPT LANGUAGE="JavaScript">alert("FAILED to delete '.addslashes($deletefile).'");</SCRIPT>';
		}
	} else {
		echo '<SCRIPT LANGUAGE="JavaScript">alert("'.addslashes($deletefile).' does not exist - cannot delete");</SCRIPT>';
	}
}

if (isset($filename)) {
	$starttime = getmicrotime();
	if (isset($assumeFormat)) {
		$MP3fileInfo = GetAllMP3info($filename, $assumeFormat);
	} else {
		$MP3fileInfo = GetAllMP3info($filename, '');
		if (!isset($MP3fileInfo['fileformat']) || ($MP3fileInfo['fileformat'] == '')) {
			$formatExtensions = array('mp3'=>'mp3', 'ogg'=>'ogg', 'zip'=>'zip', 'wav'=>'riff', 'avi'=>'riff', 'mid'=>'midi', 'mpg'=>'mpeg', 'jpg'=>'image', 'gif'=>'image', 'png'=>'image');
			if (isset($formatExtensions[fileextension($filename)])) {
				$MP3fileInfo = GetAllMP3info($filename, $formatExtensions[fileextension($filename)]);
			}
		}
	}

	$listdirectory = dirname($filename);
	if (!is_dir($listdirectory)) {
		// Directory names with single quotes or double quotes in them will likely come out addslashes()'d
		// so this will replace \' with ' (can't use stripslashes(), that would get rid of all slashes!)
		$listdirectory = str_replace(chr(92).chr(92), chr(92), $listdirectory); // \\ -> \
		$listdirectory = str_replace(chr(92).chr(39), chr(39), $listdirectory); // \' -> '
		$listdirectory = str_replace(chr(92).chr(34), chr(34), $listdirectory); // \" -> "
	}
	$listdirectory = realpath($listdirectory); // get rid of /../../ references
	if (is_dir(str_replace('\\', '/', $listdirectory))) {
		// this mostly just gives a consistant look to Windows and *nix filesystems
		// (windows uses \ as directory seperator, *nix uses /)
		$listdirectory = str_replace('\\', '/', $listdirectory.'/');
	}
	echo 'Browse: <A HREF="'.$PHP_SELF.'?listdirectory='.urlencode($listdirectory).'">'.$listdirectory.'</A><BR>';

	echo 'Parse this file as: ';
	$allowedFormats = array('zip', 'ogg', 'riff', 'mpeg', 'midi', 'image', 'mp3');
	foreach ($allowedFormats as $possibleFormat) {
		if (isset($assumeFormat) && ($assumeFormat == $possibleFormat)) {
			echo '<B>'.$possibleFormat.'</B> | ';
		} else {
			echo '<A HREF="'.$PHP_SELF.'?filename='.urlencode($filename).'&assumeFormat='.$possibleFormat.'">'.$possibleFormat.'</A> | ';
		}
	}
	if (isset($assumeFormat)) {
		echo '<A HREF="'.$PHP_SELF.'?filename='.urlencode($filename).'">default</A><BR>';
	} else {
		echo '<B>default</B><BR>';
	}

	echo table_var_dump($MP3fileInfo);
	$endtime = getmicrotime();
	echo 'File parsed in '.number_format($endtime - $starttime, 3).' seconds.<BR>';

} else {

	if (!isset($listdirectory)) {
		$listdirectory = '.';
	}
	// if (!is_dir($listdirectory) && is_dir(str_replace(chr(92).chr(39), chr(39), $listdirectory))) {
	if (!is_dir($listdirectory)) {
		// Directory names with single quotes or double quotes in them will likely come out addslashes()'d
		// so this will replace \' with ' (can't use stripslashes(), that would get rid of all slashes!)
		$listdirectory = str_replace(chr(92).chr(92), chr(92), $listdirectory); // \\ -> \
		$listdirectory = str_replace(chr(92).chr(39), chr(39), $listdirectory); // \' -> '
		$listdirectory = str_replace(chr(92).chr(34), chr(34), $listdirectory); // \" -> "
	}
	$listdirectory = realpath($listdirectory); // get rid of /../../ references
	$currentfulldir = $listdirectory.'/';
	if (is_dir(str_replace('\\', '/', $listdirectory))) {
		// this mostly just gives a consistant look to Windows and *nix filesystems
		// (windows uses \ as directory seperator, *nix uses /)
		$currentfulldir = str_replace('\\', '/', $listdirectory.'/');
	}
	if ($handle = @opendir($listdirectory)) {

		echo str_repeat(' ', 300); // IE buffers the first 300 or so chars, making this progressive display useless - fill the buffer with spaces
		echo 'Processing';

		$starttime = getmicrotime();
		//while (($file = readdir($handle)) !== FALSE) {
		while ($file = readdir($handle)) {
			set_time_limit(10); // allocate another 10 seconds (to the usual 30-second script execution time limit) to process this file
			echo '.'; // progress indicator dot
// echo '<HR>'.$file.'<br>';
			flush();  // make sure the dot is show, otherwise it's useless
			if (is_dir(str_replace('//', '/', $currentfulldir))) {
				// if the directory name contains "weird" things like
				// " or ' or \" or \' etc then this might cause problems
				// in which case just use un-manipulated $listdirectory
				$currentfilename = str_replace('//', '/', $currentfulldir).$file;
			} else {
				$currentfilename = $listdirectory.'/'.$file;
			}

			// symbolic-link-resolution enhancements by davidbullock@tech-center.com
			$TargetObject     = realpath($currentfilename);  // Find actual file path, resolve if it's a symbolic link
			$TargetObjectType = filetype($TargetObject); // Check file type without examining extension

			if($TargetObjectType == 'dir') {
				$DirectoryContents["$currentfulldir"]['dir']["$file"]['filesize'] = '-';
				$DirectoryContents["$currentfulldir"]['dir']["$file"]['playtime_string'] = '-';
			} else if ($TargetObjectType == 'file') {
				$fileinformation = GetAllMP3info($currentfilename, FALSE);


				if (!isset($fileinformation['fileformat']) || ($fileinformation['fileformat'] == '')) {
					// auto-detect couldn't find the file format (probably corrupt header?), re-scan based on extension, if applicable
					$formatExtensions = array('mp3'=>'mp3', 'ogg'=>'ogg', 'zip'=>'zip', 'wav'=>'riff', 'avi'=>'riff', 'mid'=>'midi', 'mpg'=>'mpeg', 'jpg'=>'image', 'gif'=>'image', 'png'=>'image');
					if (isset($formatExtensions[fileextension($currentfilename)])) {
						$fileinformation = GetAllMP3info($currentfilename, $formatExtensions[fileextension($currentfilename)]);
					}
				}




				if (isset($fileinformation['fileformat']) && $fileinformation['fileformat']) {
					$DirectoryContents["$currentfulldir"]['known']["$file"] = $fileinformation;
				} else {
					$DirectoryContents["$currentfulldir"]['other']["$file"]['filesize'] = filesize($currentfilename);
					$DirectoryContents["$currentfulldir"]['other']["$file"]['playtime_string'] = '-';
				}
			}	
		}
		$endtime = getmicrotime();
		closedir($handle);
		echo 'done<BR>';
		echo 'Directory scanned in '.number_format($endtime - $starttime, 2).' seconds.<BR>';
		flush();

		echo '<TABLE BORDER="1" CELLSPACING="0" CELLPADDING="3">';
		echo '<TR BGCOLOR="#CCCCDD"><TH COLSPAN="11">Files in '.$currentfulldir.'</TH></TR>';
		echo '<TR BGCOLOR="#CCCCEE"><TH>Filename</TH><TH>File Size</TH><TH>Format</TH><TH>Playtime</TH><TH>Artist</TH><TH>Title</TH><TH>ID3v1</TH><TH>ID3v2</TH><TH>Lyrics3</TH><TH>Edit</TH><TH>Delete</TH></TR>';
		$rowcounter = 0;
		foreach ($DirectoryContents as $dirname => $val) {
			if (is_array($DirectoryContents["$dirname"]['dir'])) {
				// ksort($DirectoryContents["$dirname"]['dir']);
				uksort($DirectoryContents["$dirname"]['dir'], 'MoreNaturalSort');
				foreach ($DirectoryContents["$dirname"]['dir'] as $filename => $fileinfo) {
					echo '<TR BGCOLOR="#'.(($rowcounter++ % 2) ? 'FFCCCC' : 'EEBBBB').'">';
					echo '<TD><A HREF="'.$PHP_SELF.'?listdirectory='.urlencode($dirname.$filename).'"><B>'.$filename.'</B></A></TD>';
					echo '<TD ALIGN="CENTER">&nbsp;'.$fileinfo['filesize'].'</TD>';
					echo '<TD ALIGN="RIGHT">&nbsp;'.(isset($fileinfo['fileformat']) ? $fileinfo['fileformat'] : '').'</TD>';
					echo '<TD ALIGN="RIGHT">&nbsp;'.$fileinfo['playtime_string'].'</TD>';
					echo '<TD ALIGN="LEFT">&nbsp;</TD>';
					echo '<TD ALIGN="LEFT">&nbsp;</TD>';
					echo '<TD ALIGN="LEFT">&nbsp;</TD>';
					echo '<TD ALIGN="LEFT">&nbsp;</TD>';
					echo '<TD ALIGN="LEFT">&nbsp;</TD>';
					echo '<TD ALIGN="LEFT">&nbsp;</TD>';
					echo '<TD ALIGN="LEFT">&nbsp;</TD></TR>';
				}
			}
			if (isset($DirectoryContents["$dirname"]['known']) && is_array($DirectoryContents["$dirname"]['known'])) {
				// ksort($DirectoryContents["$dirname"]['known']);
				uksort($DirectoryContents["$dirname"]['known'], 'MoreNaturalSort');
				foreach ($DirectoryContents["$dirname"]['known'] as $filename => $fileinfo) {
					echo '<TR BGCOLOR="#'.(($rowcounter++ % 2) ? 'DDDDDD' : 'EEEEEE').'">';
					echo '<TD><A HREF="'.$PHP_SELF.'?filename='.urlencode($dirname.$filename).'" TITLE="View detailed analysis">'.$filename.'</A></TD>';
					echo '<TD ALIGN="RIGHT">&nbsp;'.number_format($fileinfo['filesize']).'</TD>';
					echo '<TD ALIGN="RIGHT">&nbsp;'.$fileinfo['fileformat'].'</TD>';
					echo '<TD ALIGN="RIGHT">&nbsp;'.(isset($fileinfo['playtime_string']) ? $fileinfo['playtime_string'] : '').'</TD>';
					echo '<TD ALIGN="LEFT">&nbsp;'.(isset($fileinfo['artist']) ? $fileinfo['artist'] : '').'</TD>';
					echo '<TD ALIGN="LEFT">&nbsp;'.(isset($fileinfo['title']) ? $fileinfo['title'] : '').'</TD>';
					echo '<TD ALIGN="LEFT">&nbsp;'.(isset($fileinfo['id3']['id3v1']) ? 'Y' : '').'</TD>';
					echo '<TD ALIGN="LEFT">&nbsp;'.(isset($fileinfo['id3']['id3v2']) ? 'Y' : '').'</TD>';
					echo '<TD ALIGN="LEFT">&nbsp;'.(isset($fileinfo['lyrics3']) ? 'Y' : '').'</TD>';
					echo '<TD ALIGN="LEFT">&nbsp;';
					if ($fileinfo['fileformat'] == 'mp3') {
						echo '<A HREF="getid3.write.php?EditorFilename='.urlencode($dirname.$filename).'" TITLE="Edit ID3 tag">edit&nbsp;ID3';
					}
					echo '</TD>';
					echo '<TD ALIGN="LEFT">&nbsp;<A HREF="'.$PHP_SELF.'?listdirectory='.urlencode($listdirectory).'&deletefile='.urlencode($dirname.$filename).'" onClick="return confirm(\'Are you sure you want to delete '.addslashes($dirname.$filename).'? \n(this action cannot be un-done)\');" TITLE="Permanently delete '."\n".FixTextFields($filename)."\n".' from'."\n".' '.FixTextFields($dirname).'">delete</A></TD>';
					echo '</TR>';
				}
			}
			if (isset($DirectoryContents["$dirname"]['other']) && is_array($DirectoryContents["$dirname"]['other'])) {
				// ksort($DirectoryContents["$dirname"]['other']);
				uksort($DirectoryContents["$dirname"]['other'], 'MoreNaturalSort');
				foreach ($DirectoryContents["$dirname"]['other'] as $filename => $fileinfo) {
					echo '<TR BGCOLOR="#'.(($rowcounter++ % 2) ? 'BBBBDD' : 'CCCCFF').'">';
					echo '<TD><A HREF="'.$PHP_SELF.'?filename='.urlencode($dirname.$filename).'"><I>'.$filename.'</I></A></TD>';
					echo '<TD ALIGN="RIGHT">&nbsp;'.number_format($fileinfo['filesize']).'</TD>';
					echo '<TD ALIGN="RIGHT">&nbsp;'.(isset($fileinfo['fileformat']) ? $fileinfo['fileformat'] : '').'</TD>';
					echo '<TD ALIGN="RIGHT">&nbsp;'.(isset($fileinfo['playtime_string']) ? $fileinfo['playtime_string'] : '').'</TD>';
					echo '<TD ALIGN="LEFT">&nbsp;</TD>'; // Artist
					echo '<TD ALIGN="LEFT">&nbsp;</TD>'; // Title
					echo '<TD ALIGN="LEFT">&nbsp;</TD>'; // ID3v1
					echo '<TD ALIGN="LEFT">&nbsp;</TD>'; // ID3v2
					echo '<TD ALIGN="LEFT">&nbsp;</TD>'; // Lyrics3
					echo '<TD ALIGN="LEFT">&nbsp;</TD>'; // Edit
					echo '<TD ALIGN="LEFT">&nbsp;<A HREF="'.$PHP_SELF.'?listdirectory='.urlencode($listdirectory).'&deletefile='.urlencode($dirname.$filename).'" onClick="return confirm(\'Are you sure you want to delete '.addslashes($dirname.$filename).'? \n(this action cannot be un-done)\');" TITLE="Permanently delete '.addslashes($dirname.$filename).'">delete</A></TD>';
					echo '</TR>';
				}
			}
		}
		echo '</TABLE>';
	} else {
		echo '<B>ERROR: Could not open directory: <U>'.$currentfulldir.'</U></B><BR>';
	}
}
echo '</BODY></HTML>';
error_reporting($old_error_reporting);
?>

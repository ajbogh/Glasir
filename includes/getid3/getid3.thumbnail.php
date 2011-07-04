<?php

include_once('getid3.php');
include_once(GETID3_INCLUDEPATH.'getid3.functions.php');
include_once(GETID3_INCLUDEPATH.'getid3.lookup.php');
include_once(GETID3_INCLUDEPATH.'getid3.frames.php');

function gd_version() {
	ob_start();
	phpinfo();
	$buffer = ob_get_contents();
	ob_end_clean();
	preg_match('|<B>GD Version</B></td><TD ALIGN="left">([^<]*)</td>|i', $buffer, $matches);
	// return $matches[1]; // '1.6.2 or higher'
	return substr($matches[1], 0, 3); // '1.6'
}

function errorimage($text) {
	header('Content-type: image/png');
	$im = @ImageCreate(100, 100) or die('Cannot Initialize new GD image stream');
	$background_color = ImageColorAllocate($im, 255, 255, 255);
	$text_color       = ImageColorAllocate($im, 233,  14,  91);
	$errortextarray = explode(' ', $text);
	$vertical = 0;
	foreach ($errortextarray as $textline) {
		ImageString($im, 1,  0,  $vertical, $textline, $text_color);
		$vertical += 10;
	}
	ImagePNG($im);
}

if (isset($_GET['serializeddata'])) {
	$serializeddata = $_GET['serializeddata'];
}
if (isset($filename) && isset($frameoffset)) {
	if ($fd = @fopen($filename, 'rb')) {
		$id3v2header = fread($fd, 14);

		if (substr($id3v2header, 0, 3) == 'ID3') {
			$id3info['id3']['id3v2']['header'] = TRUE;
			$id3info['id3']['id3v2']['majorversion'] = ord($id3v2header{3});
			$id3info['id3']['id3v2']['minorversion'] = ord($id3v2header{4});
		}
	
		if (isset($id3info['id3']['id3v2']['header']) && ($id3info['id3']['id3v2']['majorversion'] <= 4)) { // this script probably won't correctly parse ID3v2.5.x and above.
	
			$id3_flags = BigEndian2Bin($id3v2header{5});
			if ($id3info['id3']['id3v2']['majorversion'] == 2) {
				// %ab000000 in v2.2
				$id3info['id3']['id3v2']['flags']['unsynch']     = $id3_flags{0}; // a - Unsynchronisation
				$id3info['id3']['id3v2']['flags']['compression'] = $id3_flags{1}; // b - Compression
			} else if ($id3info['id3']['id3v2']['majorversion'] == 3) {
				// %abc00000 in v2.3
				$id3info['id3']['id3v2']['flags']['unsynch']     = $id3_flags{0}; // a - Unsynchronisation
				$id3info['id3']['id3v2']['flags']['exthead']     = $id3_flags{1}; // b - Extended header
				$id3info['id3']['id3v2']['flags']['experim']     = $id3_flags{2}; // c - Experimental indicator
			} else if ($id3info['id3']['id3v2']['majorversion'] == 4) {
				// %abcd0000 in v2.4
				$id3info['id3']['id3v2']['flags']['unsynch']     = $id3_flags{0}; // a - Unsynchronisation
				$id3info['id3']['id3v2']['flags']['exthead']     = $id3_flags{1}; // b - Extended header
				$id3info['id3']['id3v2']['flags']['experim']     = $id3_flags{2}; // c - Experimental indicator
				$id3info['id3']['id3v2']['flags']['isfooter']    = $id3_flags{3}; // d - Footer present
			}
	
			$id3info['id3']['id3v2']['headerlength'] = BigEndian2Int(substr($id3v2header, 6, 4), 1) + ID3v2HeaderLength($id3info['id3']['id3v2']['majorversion']);
			
			$id3v2dataoffset = 10;
			if (isset($id3info['id3']['id3v2']['flags']['exthead']) && $id3info['id3']['id3v2']['flags']['exthead']) {
				$id3v2dataoffset += BigEndian2Int(substr($id3v2header, 10, 4), 1);
			}
			fseek($fd, $id3v2dataoffset, SEEK_SET);

			$sizeofframes = $id3info['id3']['id3v2']['headerlength'] - ID3v2HeaderLength($id3info['id3']['id3v2']['majorversion']);
			if (isset($id3info['id3']['id3v2']['extheaderlength'])) {
				$sizeofframes -= $id3info['id3']['id3v2']['extheaderlength'];
			}
			if (isset($id3info['id3']['id3v2']['flags']['isfooter']) && $id3info['id3']['id3v2']['flags']['isfooter']) {
				$sizeofframes -= 10; // footer takes last 10 bytes of ID3v2 header, after frame data, before audio
			}
			if ($sizeofframes > 0) {
				$framedata = fread($fd, $sizeofframes); // read all frames from file into $framedata variable
				//	if entire frame data is unsynched, de-unsynch it now (ID3v2.3.x)
				if (isset($id3info['id3']['id3v2']['flags']['unsynch']) && $id3info['id3']['id3v2']['flags']['unsynch'] && ($id3info['id3']['id3v2']['majorversion'] <= 3)) {
					$framedata = DeUnSynchronise($framedata);
				}
				$framedata = substr($framedata, $frameoffset - 10); // minus length of 10-byte ID3v2 header
				if ($id3info['id3']['id3v2']['majorversion'] == 2) {
					$frame_header = substr($framedata, 0, 6); // take next 6 bytes for header
					$framedata    = substr($framedata, 6);    // and leave the rest in $framedata
					$frame_name   = substr($frame_header, 0, 3);
					$frame_size   = BigEndian2Int(substr($frame_header, 3, 3), 0);
					$frame_flags  = ''; // not used for anything, just to avoid E_NOTICEs
				} else if ($id3info['id3']['id3v2']['majorversion'] > 2) {
					$frame_header = substr($framedata, 0, 10); // take next 10 bytes for header
					$framedata    = substr($framedata, 10);    // and leave the rest in $framedata
					$frame_name = substr($frame_header, 0, 4);
					if ($id3info['id3']['id3v2']['majorversion'] == 3) {
						$frame_size = BigEndian2Int(substr($frame_header, 4, 4), 0); // 32-bit integer
					} else { // ID3v2.4+
						$frame_size = BigEndian2Int(substr($frame_header, 4, 4), 1); // 32-bit synchsafe integer (28-bit value)
					}
					$frame_flags = BigEndian2Bin(substr($frame_header, 8, 2));
				}
				if (($frame_size <= strlen($framedata)) && (IsValidID3v2FrameName($frame_name, $id3info['id3']['id3v2']['majorversion']))) {
					$id3info['id3']['id3v2']["$frame_name"]['data']       = substr($framedata, 0, $frame_size);
					// in getid3.frames.php - this function does all the FrameID-level parsing
					ID3v2FrameProcessing($frame_name, $frame_flags, $id3info);
					if (isset($id3info['id3']['id3v2']['APIC'][0]['data']) && (strlen($id3info['id3']['id3v2']['APIC'][0]['data']) > 0)) {
						if (isset($rawdata)) {
							echo $id3info['id3']['id3v2']['APIC'][0]['data'];
						} else {
							include_once(GETID3_INCLUDEPATH.'getid3.getimagesize.php');
							$imagechunkcheck = GetDataImageSize($id3info['id3']['id3v2']['APIC'][0]['data']);
							switch ($imagechunkcheck[2]) {
								case 1:
									header('Content-type: image/gif');
									echo $id3info['id3']['id3v2']['APIC'][0]['data'];
									break;
								case 2:
									header('Content-type: image/jpeg');
									echo $id3info['id3']['id3v2']['APIC'][0]['data'];
									break;
								case 3:
									header('Content-type: image/png');
									echo $id3info['id3']['id3v2']['APIC'][0]['data'];
									break;
								default:
									errorimage('Invalid image type (only GIF, PNG, JPEG)');
									break;
							}
						}
					} else {
						errorimage('Couldn\'t locate image data');
					}
				} else { // invalid frame length or FrameID
					errorimage('Invalid length or FrameID');
				}

			} else {
				errorimage('No frame data');
			}			
			
		} else {
			errorimage('Cannot parse ID3v2.'.$id3info['id3']['id3v2']['majorversion'].'.'.$id3info['id3']['id3v2']['minorversion']);
		}
		fclose($fd);
	} else {
		errorimage('Cannot open file');
	}
} else {
	errorimage('No image data specified');
}

?>
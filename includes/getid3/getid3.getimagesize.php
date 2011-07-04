<?php
/////////////////////////////////////////////////////////////////////
// GetURLImageSize( $urlpic ) determines the                       //
// dimensions of local/remote URL pictures.                        //
// returns array with ($width, $height, $type)                     //
//                                                                 //
// Thanks to: Oyvind Hallsteinsen aka Gosub / ELq - gosub@elq.org  //
// for the original size determining code                          //
//                                                                 //
// PHP Hack by Filipe Laborde-Basto Oct 21/2000                    //
// FREELY DISTRIBUTABLE -- use at your sole discretion! :) Enjoy.  //
// (Not to be sold in commercial packages though, keep it free!)   //
// Feel free to contact me at fil@rezox.com (http://www.rezox.com) //
//                                                                 //
// Modified by James Heinrich <james@jamesheinrich.com>            //
// June 1, 2001 - created GetDataImageSize($imgData) by seperating //
// the fopen() stuff to GetURLImageSize($urlpic) which then calls  //
// GetDataImageSize($imgData). The idea being you can call         //
// GetDataImageSize($imgData) with image data from a database etc. //
/////////////////////////////////////////////////////////////////////

define('GIF_SIG',   chr(0x47).chr(0x49).chr(0x46));  // 'GIF'
define('JPG_SIG',   chr(0xFF).chr(0xD8).chr(0xFF));
define('PNG_SIG',   chr(0x89).chr(0x50).chr(0x4E).chr(0x47).chr(0x0D).chr(0x0A).chr(0x1A).chr(0x0A));
define('JPG_SOF0',  chr(0xC0));      // Start Of Frame N
define('JPG_SOF1',  chr(0xC1));      // N indicates which compression process
define('JPG_SOF2',  chr(0xC2));      // Only SOF0-SOF2 are now in common use
define('JPG_SOF3',  chr(0xC3));
define('JPG_SOF5',  chr(0xC5));      // NB: codes C4 and CC are NOT SOF markers
define('JPG_SOF6',  chr(0xC6));
define('JPG_SOF7',  chr(0xC7));
define('JPG_SOF9',  chr(0xC9));
define('JPG_SOF10', chr(0xCA));
define('JPG_SOF11', chr(0xCB));
define('JPG_SOF13', chr(0xCD));
define('JPG_SOF14', chr(0xCE));
define('JPG_SOF15', chr(0xCF));
define('JPG_EOI',   chr(0xD9));       // End Of Image (end of datastream)
define('JPG_SOS',   chr(0xDA));       // Start Of Scan - image data start
define('RD_BUF',    10240);           // amount of data to initially read


function GetURLImageSize($urlpic) {
	$fd = @fopen($urlpic, 'rb');
	if ($fd){
		// read in 10k, enough for GIF, PNG, hopefully enough for JPG too.
		$imgData = fread($fd, RD_BUF);
		fclose ($fd);
		return GetDataImageSize($imgData);
	} else {
		return array('', '', '');
	}; // endif valid file pointer chk
}


function GetDataImageSize($imgData) {
	$height = NULL;
	$width  = NULL;
	$type   = NULL;
	if (substr($imgData, 0, 3) == GIF_SIG) {
		$dim = unpack('v2dim', substr($imgData, 6, 4));
		$width  = $dim['dim1'];
		$height = $dim['dim2'];
		$type = 1;
	} else if (substr($imgData, 0, 8) == PNG_SIG) {
		$dim = unpack('N2dim', substr($imgData, 16, 8));
		$width  = $dim['dim1'];
		$height = $dim['dim2'];
		$type = 3;
	} else if (substr($imgData, 0, 3) == JPG_SIG) {
		///////////////// JPG CHUNK SCAN ////////////////////
		$imgPos = 2;
		$type = 2;
		$buffer = RD_BUF-2;
		while ($imgPos < strlen($imgData)) {
			// synchronize to the marker 0xFF
			$imgPos = strpos($imgData, 0xFF, $imgPos) + 1;
			$marker = $imgData[$imgPos];
			do {
				$marker = ord($imgData[$imgPos++]);
			} while ($marker == 255);
			// find dimensions of block
			switch (chr($marker)) {
				// Grab width/height from SOF segment (these are acceptable chunk types)
				case JPG_SOF0:
				case JPG_SOF1:
				case JPG_SOF2:
				case JPG_SOF3:
				case JPG_SOF5:
				case JPG_SOF6:
				case JPG_SOF7:
				case JPG_SOF9:
				case JPG_SOF10:
				case JPG_SOF11:
				//case JPG_SOF12:
				case JPG_SOF13:
				case JPG_SOF14:
				case JPG_SOF15:
					$dim = unpack('n2dim', substr($imgData, $imgPos + 3, 4));
					$height = $dim['dim1'];
					$width  = $dim['dim2'];
					break 2; // found it so exit
				case JPG_EOI:
				case JPG_SOS:
					return FALSE; 	  // End loop in case we find one of these markers
				default:            // We're not interested in other markers
					$skiplen = (ord($imgData[$imgPos++]) << 8) + ord($imgData[$imgPos++]) - 2;
					// if the skip is more than what we've read in, read more
					$buffer -= $skiplen;
					if ($buffer < 512) { // if the buffer of data is too low, read more file.
						// $imgData .= fread( $fd,$skiplen+1024 );
						// $buffer += $skiplen + 1024;
						return FALSE; // End loop in case we find run out of data
					};
					$imgPos += $skiplen;
					break;
			}; // endswitch check marker type
		}; // endif loop through JPG chunks
	}; // endif chk for valid file types

	return array($width, $height, $type);
}; // end function

?>

<?php
////////////////////////////////////////////////////////////
/// getID3() by James Heinrich <getid3@silisoftware.com>  //
//       available at http://www.silisoftware.com        ///
////////////////////////////////////////////////////////////
//                                                        //
// getid3.mpeg.php - part of getID3()                     //
// See getid3.readme.txt for more details                 //
//                                                        //
////////////////////////////////////////////////////////////

function getMPEGHeaderFilepointer(&$fd, &$MP3fileInfo) {
	// Start code                       32 bits
	// horizontal frame size            12 bits
	// vertical frame size              12 bits
	// pixel aspect ratio                4 bits
	// frame rate                        4 bits
	// bitrate                          18 bits
	// marker bit                        1 bit
	// VBV buffer size                  10 bits
	// constrained parameter flag        1 bit
	// intra quant. matrix flag          1 bit
	// intra quant. matrix values      512 bits (present if matrix flag == 1)
	// non-intra quant. matrix flag      1 bit
	// non-intra quant. matrix values  512 bits (present if matrix flag == 1)

	if (!$fd) {
		$MP3fileInfo['error'] .= "\n".'Could not open file';
		return FALSE;
	} else {
		// MPEG video information is found as $00 $00 $01 $B3
		$matching_pattern = chr(0x00).chr(0x00).chr(0x01).chr(0xB3);
		
		rewind($fd);
		$MPEGvideoHeader = fread($fd, FREAD_BUFFER_SIZE);
		$offset = 0;
		while (substr($MPEGvideoHeader, $offset++, 4) !== $matching_pattern) {
			if ($offset >= (strlen($MPEGvideoHeader) - 12)) {
				$MPEGvideoHeader .= fread($fd, FREAD_BUFFER_SIZE);
				$MPEGvideoHeader = substr($MPEGvideoHeader, $offset);
				$offset = 0;
				if (strlen($MPEGvideoHeader) < 12) {
					$MP3fileInfo['error'] = "\n".'Could not find start of video block before end of file';
					return FALSE;
					// return array('error'=>'Could not find start of video block before end of file');
				} else if (ftell($fd) >= 100000) {
					$MP3fileInfo['error'] = "\n".'Could not find start of video block in the first 100,000 bytes (this might not be an MPEG-video file?)';
					unset($MP3fileInfo['fileformat']);
					return FALSE;
					// return array('error'=>"\n".'Could not find start of video block in the first 100,000 bytes (this might not be an MPEG-video file?)', 'fileformat'=>'');
				}
			}
		}
		$offset += strlen($matching_pattern) - 1;
		$framesizes = BigEndian2Bin(substr($MPEGvideoHeader, $offset, 3));
		$offset += 3;
		$aspect_framerate = BigEndian2Bin(substr($MPEGvideoHeader, $offset, 1));
		$offset += 1;
		$assortedinformation = BigEndian2Bin(substr($MPEGvideoHeader, $offset, 4));
		$offset += 4;
		
		$MP3fileInfo['mpeg']['video']['raw']['framesize_horizontal'] = bindec(substr($framesizes,  0, 12)); // 12 bits for horizontal frame size
		$MP3fileInfo['mpeg']['video']['raw']['framesize_vertical']   = bindec(substr($framesizes, 12, 12)); // 12 bits for vertical frame size
		$MP3fileInfo['mpeg']['video']['framesize_horizontal'] = $MP3fileInfo['mpeg']['video']['raw']['framesize_horizontal'];
		$MP3fileInfo['mpeg']['video']['framesize_vertical']   = $MP3fileInfo['mpeg']['video']['raw']['framesize_vertical'];

		$MP3fileInfo['mpeg']['video']['raw']['pixel_aspect_ratio'] = bindec(substr($aspect_framerate,  0, 4));
		$MP3fileInfo['mpeg']['video']['raw']['frame_rate']         = bindec(substr($aspect_framerate,  4, 4));
		$MP3fileInfo['mpeg']['video']['pixel_aspect_ratio']        = MPEGvideoAspectRatioLookup($MP3fileInfo['mpeg']['video']['raw']['pixel_aspect_ratio']);
		$MP3fileInfo['mpeg']['video']['pixel_aspect_ratio_text']   = MPEGvideoAspectRatioTextLookup($MP3fileInfo['mpeg']['video']['raw']['pixel_aspect_ratio']);
		$MP3fileInfo['mpeg']['video']['frame_rate']                = MPEGvideoFramerateLookup($MP3fileInfo['mpeg']['video']['raw']['frame_rate']);

		$MP3fileInfo['mpeg']['video']['raw']['bitrate']                = bindec(substr($assortedinformation,  0, 18));
		if ($MP3fileInfo['mpeg']['video']['raw']['bitrate'] == 0x3FFFF) { // 18 set bits
			$MP3fileInfo['mpeg']['video']['bitrate_type'] = 'variable';
		} else {
			$MP3fileInfo['mpeg']['video']['bitrate_type']   = 'constant';
			$MP3fileInfo['mpeg']['video']['bitrate_bps']    = $MP3fileInfo['mpeg']['video']['raw']['bitrate'] * 400;
			//$MP3fileInfo['mpeg']['video']['bitrate_kbytes'] = $MP3fileInfo['mpeg']['video']['bitrate_bps'] / 8192;
		}
		$MP3fileInfo['mpeg']['video']['raw']['marker_bit']             = bindec(substr($assortedinformation, 18,  1));
		$MP3fileInfo['mpeg']['video']['raw']['vbv_buffer_size']        = bindec(substr($assortedinformation, 19, 10));
		$MP3fileInfo['mpeg']['video']['raw']['constrained_param_flag'] = bindec(substr($assortedinformation, 29,  1));
		$MP3fileInfo['mpeg']['video']['raw']['intra_quant_flag']       = bindec(substr($assortedinformation, 30,  1));
		
		return TRUE;
	}
}
?>
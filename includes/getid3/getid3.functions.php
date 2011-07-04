<?php
////////////////////////////////////////////////////////////
/// getID3() by James Heinrich <getid3@silisoftware.com>  //
//       available at http://www.silisoftware.com        ///
////////////////////////////////////////////////////////////
//                                                        //
// getid3.functions.php - part of getID3()                //
// See getid3.readme.txt for more details                 //
//                                                        //
////////////////////////////////////////////////////////////

function PrintHexBytes($string) {
	$returnstring = '';
	for ($i=0;$i<strlen($string);$i++) {
		$returnstring .= str_pad(dechex(ord(substr($string, $i, 1))), 2, '0', STR_PAD_LEFT).' ';
	}
	return $returnstring;
}

function PrintTextBytes($string) {
	$returnstring = '';
	for ($i=0;$i<strlen($string);$i++) {
		if (ord(substr($string, $i, 1)) <= 31) {
			$returnstring .= '   ';
		} else {
			$returnstring .= ' '.substr($string, $i, 1).' ';
		}
	}
	return $returnstring;
}

function FixTextFields($text) {
	$text = stripslashes($text);
	$text = str_replace('\'', '&#39;', $text);
	$text = str_replace('"', '&quot;', $text);
	return $text;
}

function table_var_dump($variable) {
	global $filename;

	$returnstring = '';
	if (is_array($variable)) {
		$returnstring .= '<TABLE BORDER="1" CELLSPACING="0" CELLPADDING="2">';
		foreach ($variable as $key => $value) {
			$returnstring .= '<TR><TD VALIGN="TOP"><B>'.str_replace(chr(0), ' ', $key).'</B></TD>';
			$returnstring .= '<TD VALIGN="TOP">'.gettype($value);
			if (is_array($value)) {
				$returnstring .= '&nbsp;('.count($value).')';
			} else if (is_string($value)) {
				$returnstring .= '&nbsp;('.strlen($value).')';
			}
			if (($key == 'data') && isset($variable['image_mime']) && isset($variable['dataoffset'])) {
				$returnstring .= '</TD><TD><IMG SRC="getid3.thumbnail.php?filename='.rawurlencode($filename).'&frameoffset='.$variable['dataoffset'].'"></TD>';
			} else {
				$returnstring .= '</TD><TD>'.table_var_dump($value).'</TD>';
			}
		}
		$returnstring .= '</TABLE>';
	} else {
		if (gettype($variable) == 'boolean') {
			if ($variable) {
				$returnstring .= 'TRUE</TR>';
			} else {
				$returnstring .= 'FALSE</TR>';
			}
		} else {
			include_once(GETID3_INCLUDEPATH.'getid3.getimagesize.php');
			$imagechunkcheck = GetDataImageSize(substr($variable, 0, FREAD_BUFFER_SIZE));

			if (($imagechunkcheck[2] >= 1) && ($imagechunkcheck[2] <= 3)) {
				$imagetypes = array(1=>'image/gif', 2=>'image/jpeg', 3=>'image/png');
				$returnstring .= '<TABLE BORDER="1" CELLSPACING="0" CELLPADDING="2">';
				$returnstring .= '<TR><TD><B>type</B></TD><TD>'.$imagetypes["{$imagechunkcheck[2]}"].'</TD></TR>';
				$returnstring .= '<TR><TD><B>width</B></TD><TD>'.number_format($imagechunkcheck[0]).' px</TD></TR>';
				$returnstring .= '<TR><TD><B>height</B></TD><TD>'.number_format($imagechunkcheck[1]).' px</TD></TR>';
				$returnstring .= '<TR><TD><B>size</B></TD><TD>'.number_format(strlen($variable)).' bytes</TD></TR></TABLE></TR>';
			} else {
				$returnstring .= htmlspecialchars(str_replace(chr(0), ' ', $variable)).'</TR>';
			}
		}
	}
	return $returnstring;
}

function string_var_dump($variable) {
	ob_start();
	var_dump($variable);
	$dumpedvariable = ob_get_contents();
	ob_end_clean();
	return $dumpedvariable;
}

function fileextension($filename) {
	if (strstr($filename, '.')) {
		return substr(basename($filename), strrpos(basename($filename), '.') + 1);
	}
	return '';
}

function RemoveAccents($string) {
	return strtr($string, "äåéöúûü•µ¿¡¬√ƒ≈∆«»… ÀÃÕŒœ–—“”‘’÷ÿŸ⁄€‹›ﬂ‡·‚„‰ÂÊÁËÈÍÎÏÌÓÔÒÚÛÙıˆ¯˘˙˚¸˝ˇ", "SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy");
}

function MoreNaturalSort($ar1, $ar2) {
	if ($ar1 === $ar2) {
		return 0;
	}
	$len1 = strlen($ar1);
	$len2 = strlen($ar2);
	if (substr($ar1, 0, min($len1, $len2)) === substr($ar2, 0, min($len1, $len2))) {
		// the shorter argument is the beginning of the longer one, like "str" and "string"
		if ($len1 < $len2) {
			return -1;
		} else if ($len1 > $len2) {
			return 1;
		}
		return 0;
	}
	$ar1 = RemoveAccents(strtolower(trim($ar1)));
	$ar2 = RemoveAccents(strtolower(trim($ar2)));
	$translatearray = array('\''=>'', '"'=>'', '_'=>' ', '('=>'', ')'=>'', '-'=>' ', '  '=>' ', '.'=>'', ','=>'');
	foreach ($translatearray as $key => $val) {
		$ar1 = str_replace($key, $val, $ar1);
		$ar2 = str_replace($key, $val, $ar2);
	}

	if ($ar1 < $ar2) {
		return -1;
	} else if ($ar1 > $ar2) {
		return 1;
	}
	return 0;
}

function trunc($floatnumber) {
	// truncates a floating-point number at the decimal point
	// returns int (if possible, otherwise double)
	if ($floatnumber >= 1) {
		$truncatednumber = floor($floatnumber);
	} else if ($floatnumber <= -1) {
		$truncatednumber = ceil($floatnumber);
	} else {
		$truncatednumber = 0;
	}
	if ($truncatednumber <= pow(2, 30)) {
		$truncatednumber = (int) $truncatednumber;
	}
	return $truncatednumber;
}

function CastAsInt($doublenum) {
	// convert a double to type int, only if possible
	if (trunc($doublenum) == $doublenum) {
		// it's not floating point
		if ($doublenum <= pow(2, 30)) {
			// it's within int range
			$doublenum = (int) $doublenum;
		}
	}
	return $doublenum;
}

function getmicrotime() {
	list($usec, $sec) = explode(' ', microtime()); 
	return ((float)$usec + (float)$sec); 
}

function DecimalBinary2Float($binarynumerator) {
	$numerator   = Bin2Dec($binarynumerator);
	$denominator = Bin2Dec(str_repeat('1', strlen($binarynumerator)));
	return ($numerator / $denominator);
}

function NormalizeBinaryPoint($binarypointnumber, $maxbits=52) {
	// http://www.scri.fsu.edu/~jac/MAD3401/Backgrnd/binary.html
	if (strpos($binarypointnumber, '.') === FALSE) {
		$binarypointnumber = '0.'.$binarypointnumber;
	} else if ($binarypointnumber{0} == '.') {
		$binarypointnumber = '0'.$binarypointnumber;
	}
	$exponent = 0;
	while (($binarypointnumber{0} != '1') || (substr($binarypointnumber, 1, 1) != '.')) {
		if (substr($binarypointnumber, 1, 1) == '.') {
			$exponent--;
			$binarypointnumber = substr($binarypointnumber, 2, 1).'.'.substr($binarypointnumber, 3);
		} else {
			$pointpos = strpos($binarypointnumber, '.');
			$exponent += ($pointpos - 1);
			$binarypointnumber = str_replace('.', '', $binarypointnumber);
			$binarypointnumber = $binarypointnumber{0}.'.'.substr($binarypointnumber, 1);
		}
	}
	$binarypointnumber = str_pad(substr($binarypointnumber, 0, $maxbits + 2), $maxbits + 2, '0', STR_PAD_RIGHT);
	return array('normalized'=>$binarypointnumber, 'exponent'=>(int) $exponent);
}

function Float2BinaryDecimal($floatvalue) {
	// http://www.scri.fsu.edu/~jac/MAD3401/Backgrnd/binary.html
	$maxbits=128; // to how many bits of precision should the calculations be taken?
	$intpart   = trunc($floatvalue);
	$floatpart = abs($floatvalue - $intpart);
	$pointbitstring = '';
	while (($floatpart != 0) && (strlen($pointbitstring) < $maxbits)) {
		$floatpart *= 2;
		$pointbitstring .= (string) trunc($floatpart);
		$floatpart -= trunc($floatpart);
	}
	$binarypointnumber = decbin($intpart).'.'.$pointbitstring;
	return $binarypointnumber;
}

function Float2String($floatvalue, $bits) {
	// http://www.scri.fsu.edu/~jac/MAD3401/Backgrnd/ieee-expl.html
	if (($bits != 32) && ($bits != 64)) {
		return FALSE;
	} else if ($bits == 32) {
		$exponentbits = 8;
		$fractionbits = 23;
	} else if ($bits == 64) {
		$exponentbits = 11;
		$fractionbits = 52;
	}
	if ($floatvalue >= 0) {
		$signbit = '0';
	} else {
		$signbit = '1';
	}
	$normalizedbinary = NormalizeBinaryPoint(Float2BinaryDecimal($floatvalue), $fractionbits);
	$biasedexponent = pow(2, $exponentbits - 1) - 1 + $normalizedbinary['exponent']; // (127 or 1023) +/- exponent
	$exponentbitstring = str_pad(decbin($biasedexponent), $exponentbits, '0', STR_PAD_LEFT);
	$fractionbitstring = str_pad(substr($normalizedbinary['normalized'], 2), $fractionbits, '0', STR_PAD_RIGHT);

	return BigEndian2String(Bin2Dec($signbit.$exponentbitstring.$fractionbitstring), $bits % 8, FALSE);
}

function LittleEndian2Float($byteword) {
	return BigEndian2Float(strrev($byteword));
}

function BigEndian2Float($byteword) {
	// ANSI/IEEE Standard 754-1985, Standard for Binary Floating Point Arithmetic
	// http://www.psc.edu/general/software/packages/ieee/ieee.html
	// http://www.scri.fsu.edu/~jac/MAD3401/Backgrnd/ieee.html
	
	$bitword = BigEndian2Bin($byteword);
	$signbit = $bitword{0};
	if (strlen($byteword) == 4) { // 32-bit DWORD
		$exponentbits = 8;
		$fractionbits = 23;
	} else if (strlen($byteword) == 8) { // 64-bit QWORD
		$exponentbits = 11;
		$fractionbits = 52;
	} else {
		return FALSE;
	}
	$exponentstring = substr($bitword, 1, $exponentbits);
	$fractionstring = substr($bitword, 9, $fractionbits);
	$exponent = Bin2Dec($exponentstring);
	$fraction = Bin2Dec($fractionstring);
	if (($exponent == (pow(2, $exponentbits) - 1)) && ($fraction != 0)) {
		// Not a Number
		$floatvalue = FALSE;
	} else if (($exponent == (pow(2, $exponentbits) - 1)) && ($fraction == 0)) {
		if ($signbit == '1') {
			$floatvalue = '-infinity';
		} else {
			$floatvalue = '+infinity';
		}
	} else if (($exponent == 0) && ($fraction == 0)) {
		if ($signbit == '1') {
			$floatvalue = -0;
		} else {
			$floatvalue = 0;
		}
		$floatvalue = ($signbit ? 0 : -0);
	} else if (($exponent == 0) && ($fraction != 0)) {
		// These are 'unnormalized' values
		$floatvalue = pow(2, (-1 * (pow(2, $exponentbits - 1) - 2))) * DecimalBinary2Float($fractionstring);
		if ($signbit == '1') {
			$floatvalue *= -1;
		}
	} else if ($exponent != 0) {
		$floatvalue = pow(2, ($exponent - (pow(2, $exponentbits - 1) - 1))) * (1 + DecimalBinary2Float($fractionstring));
		if ($signbit == '1') {
			$floatvalue *= -1;
		}
	}
	return (float) $floatvalue;
}

function BigEndian2Int($byteword, $synchsafe=0) {
	$intvalue = 0;
	$bytewordlen = strlen($byteword);
	for ($i=0;$i<$bytewordlen;$i++) {
		if ($synchsafe) { // disregard MSB, effectively 7-bit bytes
			$intvalue = $intvalue | ((ord($byteword{$i})) & bindec('01111111')) << (($bytewordlen - 1 - $i) * 7);
		} else {
			$intvalue += ord($byteword{$i}) * pow(256, ($bytewordlen - 1 - $i));
		}
	}
	return $intvalue;
}

function LittleEndian2Int($byteword) {
	$intvalue = 0;
	for ($i=(strlen($byteword)-1);$i>=0;$i--) {
		$intvalue += ord($byteword{$i}) * pow(256, $i);
	}
	return CastAsInt($intvalue);
}

function BigEndian2Bin($byteword) {
	$binvalue = '';
	$bytewordlen = strlen($byteword);
	for ($i=0;$i<$bytewordlen;$i++) {
		$binvalue .= str_pad(decbin(ord($byteword{$i})), 8, '0', STR_PAD_LEFT);
	}
	return $binvalue;
}

function BigEndian2String($number, $minbytes=1, $synchsafe=FALSE) {
	if ($number < 0) {
		return FALSE;
	}
	if ($synchsafe) {
		$maskbyte = 127;
	} else {
		$maskbyte = 255;
	}
	while ($number != 0) {
		$quotient = ($number / ($maskbyte + 1));
		$intstring = chr(ceil(($quotient - floor($quotient)) * $maskbyte)).$intstring;
		$number = floor($quotient);
	}
	return str_pad($intstring, $minbytes, chr(0), STR_PAD_LEFT);
}

function Dec2Bin($number) {
	while ($number >= 256) {
		$bytes[] = (($number / 256) - (floor($number / 256))) * 256;
		$number = floor($number / 256);
	}
	$bytes[] = $number;
	$binstring = '';
	for ($i=0;$i<count($bytes);$i++) {
		$binstring = (($i == count($bytes) - 1) ? decbin($bytes["$i"]) : str_pad(decbin($bytes["$i"]), 8, '0', STR_PAD_LEFT)).$binstring;
	}
	return $binstring;
}

function Bin2Dec($binstring) {
	$decvalue = 0;
	for ($i=0;$i<strlen($binstring);$i++) {
		$decvalue += ((int) substr($binstring, strlen($binstring) - $i - 1, 1)) * pow(2, $i);
	}
	return CastAsInt($decvalue);
}

function LittleEndian2String($number, $minbytes=1, $synchsafe=FALSE) {
	while ($number > 0) {
		if ($synchsafe) {
			$intstring = $intstring.chr($number & 127);
			$number >>= 7;
		} else {
			$intstring = $intstring.chr($number & 255);
			$number >>= 8;
		}
	}
	return $intstring;
}

function Bool2IntString($intvalue) {
	if ($intvalue) {
		return '1';
	} else {
		return '0';
	}
}

function IntString2Bool($char) {
	if ($char == '1') {
		return TRUE;
	} else if ($char == '0') {
		return FALSE;
	}
}

function DeUnSynchronise($data) {
	return str_replace(chr(0xFF).chr(0x00), chr(0xFF), $data);
}

function Unsynchronise($data) {
	// Whenever a false synchronisation is found within the tag, one zeroed
	// byte is inserted after the first false synchronisation byte. The
	// format of a correct sync that should be altered by ID3 encoders is as
	// follows:
	// 	 %11111111 111xxxxx
	// And should be replaced with:
	// 	 %11111111 00000000 111xxxxx
	// This has the side effect that all $FF 00 combinations have to be
	// altered, so they won't be affected by the decoding process. Therefore
	// all the $FF 00 combinations have to be replaced with the $FF 00 00
	// combination during the unsynchronisation.

	$data = str_replace(chr(0xFF).chr(0x00), chr(0xFF).chr(0x00).chr(0x00), $data);
	$unsyncheddata = '';
	for ($i = 0; $i < strlen($data); $i++) {
		$thischar = $data{$i};
		$unsyncheddata .= $thischar;
		if ($thischar == chr(255)) {
			$nextchar = ord(substr($data, $i + 1, 1));
			if (($nextchar | bindec('00011111')) == 255) {
				// previous byte = 11111111, this byte = 111?????
				$unsyncheddata .= chr(0);
			}
		}
	}
	return $unsyncheddata;
}

function is_hash($var) {
	// written by dev-null@christophe.vg
	// taken from http://www.php.net/manual/en/function.array-merge-recursive.php
	if (is_array($var)) {
		$keys = array_keys($var);
		$all_num = true;
		for ($i=0;$i<count($keys);$i++) {
			if (is_string($keys["$i"])) {
				return true;
			}
		}
	}
	return false;
}

function array_join_merge($arr1, $arr2) {
	// written by dev-null@christophe.vg
	// taken from http://www.php.net/manual/en/function.array-merge-recursive.php
	if (is_array($arr1) && is_array($arr2)) {
		// the same -> merge
		$new_array = array();

		if (is_hash($arr1) && is_hash($arr2)) {
			// hashes -> merge based on keys
			$keys = array_merge(array_keys($arr1), array_keys($arr2));
			foreach ($keys as $key) {
				$new_array["$key"] = array_join_merge($arr1["$key"], $arr2["$key"]);
			}
		} else {
			// two real arrays -> merge
			$new_array = array_reverse(array_unique(array_reverse(array_merge($arr1,$arr2))));
		}
		return $new_array;
 	} else {
		// not the same ... take new one if defined, else the old one stays
		return $arr2 ? $arr2 : $arr1;
	}
}

function RoughTranslateUnicodeToASCII($rawdata, $frame_textencoding) {
	// rough translation of data for application that can't handle Unicode data

	$asciidata  = '';
	$tempstring = '';
	switch ($frame_textencoding) {
		case 0: // ISO-8859-1. Terminated with $00.
			$asciidata .= $rawdata;
			break;
		case 1: // UTF-16 encoded Unicode with BOM. Terminated with $00 00.
			if (substr($rawdata, 0, 2) == chr(0xFF).chr(0xFE)) {
				$asciidata = substr($rawdata, 2);                       // remove BOM, only if present (it should be, but...)
			}
			if (substr($rawdata, strlen($rawdata) - 2, 2) == chr(0).chr(0)) {
				$asciidata = substr($rawdata, 0, strlen($rawdata) - 2); // remove terminator, only if present (it should be, but...)
			}
			for ($i=0;$i<strlen($asciidata);$i+=2) {
				if ((ord($asciidata{$i}) <= 0x7F) || (ord($asciidata{$i}) >= 0xA0)) {
					$tempstring .= $asciidata{$i};
				} else {
					$tempstring .= '?';
				}
			}
			$asciidata = $tempstring;
			break;
		case 2: // UTF-16BE encoded Unicode without BOM. Terminated with $00 00.
			if (substr($rawdata, strlen($rawdata) - 2, 2) == chr(0).chr(0)) {
				$asciidata = substr($rawdata, 0, strlen($rawdata) - 2); // remove terminator, only if present (it should be, but...)
			}
			for ($i=0;$i<strlen($asciidata);$i+=2) {
				if ((ord($asciidata{$i}) <= 0x7F) || (ord($asciidata{$i}) >= 0xA0)) {
					$tempstring .= $asciidata{$i};
				} else {
					$tempstring .= '?';
				}
			}
			$asciidata = $tempstring;
			break;
		case 3: // UTF-8 encoded Unicode. Terminated with $00.
			$asciidata = utf8_decode($rawdata);
			break;
		default:
			// shouldn't happen, but in case $frame_textencoding is not 1 <= $frame_textencoding <= 4
			// just pass the data through unchanged.
			$asciidata .= $rawdata;
			break;
	}
	return str_replace(chr(0), '', $asciidata); // just in case any nulls slipped through
}

?>
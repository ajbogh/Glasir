<?php
////////////////////////////////////////////////////////////
/// getID3() by James Heinrich <getid3@silisoftware.com>  //
//       available at http://www.silisoftware.com        ///
////////////////////////////////////////////////////////////
//                                                        //
// getid3.lookup.php - part of getID3()                   //
// See getid3.readme.txt for more details                 //
//                                                        //
////////////////////////////////////////////////////////////

function ArrayOfGenres() {
	static $GenreLookup = array();
	if (count($GenreLookup) < 1) {
		$GenreLookup[0]    = 'Blues';
		$GenreLookup[1]    = 'Classic Rock';
		$GenreLookup[2]    = 'Country';
		$GenreLookup[3]    = 'Dance';
		$GenreLookup[4]    = 'Disco';
		$GenreLookup[5]    = 'Funk';
		$GenreLookup[6]    = 'Grunge';
		$GenreLookup[7]    = 'Hip-Hop';
		$GenreLookup[8]    = 'Jazz';
		$GenreLookup[9]    = 'Metal';
		$GenreLookup[10]   = 'New Age';
		$GenreLookup[11]   = 'Oldies';
		$GenreLookup[12]   = 'Other';
		$GenreLookup[13]   = 'Pop';
		$GenreLookup[14]   = 'R&B';
		$GenreLookup[15]   = 'Rap';
		$GenreLookup[16]   = 'Reggae';
		$GenreLookup[17]   = 'Rock';
		$GenreLookup[18]   = 'Techno';
		$GenreLookup[19]   = 'Industrial';
		$GenreLookup[20]   = 'Alternative';
		$GenreLookup[21]   = 'Ska';
		$GenreLookup[22]   = 'Death Metal';
		$GenreLookup[23]   = 'Pranks';
		$GenreLookup[24]   = 'Soundtrack';
		$GenreLookup[25]   = 'Euro-Techno';
		$GenreLookup[26]   = 'Ambient';
		$GenreLookup[27]   = 'Trip-Hop';
		$GenreLookup[28]   = 'Vocal';
		$GenreLookup[29]   = 'Jazz+Funk';
		$GenreLookup[30]   = 'Fusion';
		$GenreLookup[31]   = 'Trance';
		$GenreLookup[32]   = 'Classical';
		$GenreLookup[33]   = 'Instrumental';
		$GenreLookup[34]   = 'Acid';
		$GenreLookup[35]   = 'House';
		$GenreLookup[36]   = 'Game';
		$GenreLookup[37]   = 'Sound Clip';
		$GenreLookup[38]   = 'Gospel';
		$GenreLookup[39]   = 'Noise';
		$GenreLookup[40]   = 'Alt. Rock';
		$GenreLookup[41]   = 'Bass';
		$GenreLookup[42]   = 'Soul';
		$GenreLookup[43]   = 'Punk';
		$GenreLookup[44]   = 'Space';
		$GenreLookup[45]   = 'Meditative';
		$GenreLookup[46]   = 'Instrumental Pop';
		$GenreLookup[47]   = 'Instrumental Rock';
		$GenreLookup[48]   = 'Ethnic';
		$GenreLookup[49]   = 'Gothic';
		$GenreLookup[50]   = 'Darkwave';
		$GenreLookup[51]   = 'Techno-Industrial';
		$GenreLookup[52]   = 'Electronic';
		$GenreLookup[53]   = 'Folk/Pop';
		$GenreLookup[54]   = 'Eurodance';
		$GenreLookup[55]   = 'Dream';
		$GenreLookup[56]   = 'Southern Rock';
		$GenreLookup[57]   = 'Comedy';
		$GenreLookup[58]   = 'Cult';
		$GenreLookup[59]   = 'Gangsta';
		$GenreLookup[60]   = 'Top 40';
		$GenreLookup[61]   = 'Christian Rap';
		$GenreLookup[62]   = 'Pop/Funk';
		$GenreLookup[63]   = 'Jungle';
		$GenreLookup[64]   = 'Native American';
		$GenreLookup[65]   = 'Cabaret';
		$GenreLookup[66]   = 'New Wave';
		$GenreLookup[67]   = 'Psychadelic';
		$GenreLookup[68]   = 'Rave';
		$GenreLookup[69]   = 'Showtunes';
		$GenreLookup[70]   = 'Trailer';
		$GenreLookup[71]   = 'Lo-Fi';
		$GenreLookup[72]   = 'Tribal';
		$GenreLookup[73]   = 'Acid Punk';
		$GenreLookup[74]   = 'Acid Jazz';
		$GenreLookup[75]   = 'Polka';
		$GenreLookup[76]   = 'Retro';
		$GenreLookup[77]   = 'Musical';
		$GenreLookup[78]   = 'Rock & Roll';
		$GenreLookup[79]   = 'Hard Rock';
		$GenreLookup[80]   = 'Folk';
		$GenreLookup[81]   = 'Folk/Rock';
		$GenreLookup[82]   = 'National Folk';
		$GenreLookup[83]   = 'Swing';
		$GenreLookup[84]   = 'Fast-Fusion';
		$GenreLookup[85]   = 'Bebob';
		$GenreLookup[86]   = 'Latin';
		$GenreLookup[87]   = 'Revival';
		$GenreLookup[88]   = 'Celtic';
		$GenreLookup[89]   = 'Bluegrass';
		$GenreLookup[90]   = 'Avantgarde';
		$GenreLookup[91]   = 'Gothic Rock';
		$GenreLookup[92]   = 'Progressive Rock';
		$GenreLookup[93]   = 'Psychedelic Rock';
		$GenreLookup[94]   = 'Symphonic Rock';
		$GenreLookup[95]   = 'Slow Rock';
		$GenreLookup[96]   = 'Big Band';
		$GenreLookup[97]   = 'Chorus';
		$GenreLookup[98]   = 'Easy Listening';
		$GenreLookup[99]   = 'Acoustic';
		$GenreLookup[100]  = 'Humour';
		$GenreLookup[101]  = 'Speech';
		$GenreLookup[102]  = 'Chanson';
		$GenreLookup[103]  = 'Opera';
		$GenreLookup[104]  = 'Chamber Music';
		$GenreLookup[105]  = 'Sonata';
		$GenreLookup[106]  = 'Symphony';
		$GenreLookup[107]  = 'Booty Bass';
		$GenreLookup[108]  = 'Primus';
		$GenreLookup[109]  = 'Porn Groove';
		$GenreLookup[110]  = 'Satire';
		$GenreLookup[111]  = 'Slow Jam';
		$GenreLookup[112]  = 'Club';
		$GenreLookup[113]  = 'Tango';
		$GenreLookup[114]  = 'Samba';
		$GenreLookup[115]  = 'Folklore';
		$GenreLookup[116]  = 'Ballad';
		$GenreLookup[117]  = 'Power Ballad';
		$GenreLookup[118]  = 'Rhythmic Soul';
		$GenreLookup[119]  = 'Freestyle';
		$GenreLookup[120]  = 'Duet';
		$GenreLookup[121]  = 'Punk Rock';
		$GenreLookup[122]  = 'Drum Solo';
		$GenreLookup[123]  = 'A Cappella';
		$GenreLookup[124]  = 'Euro-House';
		$GenreLookup[125]  = 'Dance Hall';
		$GenreLookup[126]  = 'Goa';
		$GenreLookup[127]  = 'Drum & Bass';
		$GenreLookup[128]  = 'Club-House';
		$GenreLookup[129]  = 'Hardcore';
		$GenreLookup[130]  = 'Terror';
		$GenreLookup[131]  = 'Indie';
		$GenreLookup[132]  = 'BritPop';
		$GenreLookup[133]  = 'Negerpunk';
		$GenreLookup[134]  = 'Polsk Punk';
		$GenreLookup[135]  = 'Beat';
		$GenreLookup[136]  = 'Christian Gangsta Rap';
		$GenreLookup[137]  = 'Heavy Metal';
		$GenreLookup[138]  = 'Black Metal';
		$GenreLookup[139]  = 'Crossover';
		$GenreLookup[140]  = 'Contemporary Christian';
		$GenreLookup[141]  = 'Christian Rock';
		$GenreLookup[142]  = 'Merengue';
		$GenreLookup[143]  = 'Salsa';
		$GenreLookup[144]  = 'Trash Metal';
		$GenreLookup[145]  = 'Anime';
		$GenreLookup[146]  = 'Jpop';
		$GenreLookup[147]  = 'Synthpop';
		$GenreLookup[255]  = 'Unknown';
		
		$GenreLookup['CR'] = 'Cover';
		$GenreLookup['RX'] = 'Remix';
	}
	return $GenreLookup;
}

function LookupGenre($genreid, $returnkey=FALSE) {
	if (($genreid != 'RX') && ($genreid === 'CR')) {
		$genreid = (int) $genreid; // to handle 3 or '3' or '03'
	}
	$GenreLookup = ArrayOfGenres();
	if ($returnkey) {
		if (($arrayindex = array_search($genreid, $GenreLookup)) !== FALSE) {
			return $arrayindex;
		} else {
			return '';
		}
	} else {
		return (isset($GenreLookup["$genreid"]) ? $GenreLookup["$genreid"] : '');
	}
}

function LookupCurrency($currencyid, $item) {
	$CurrencyLookup['AED']['country'] = 'United Arab Emirates';
	$CurrencyLookup['AFA']['country'] = 'Afghanistan';
	$CurrencyLookup['ALL']['country'] = 'Albania';
	$CurrencyLookup['AMD']['country'] = 'Armenia';
	$CurrencyLookup['ANG']['country'] = 'Netherlands Antilles';
	$CurrencyLookup['AOA']['country'] = 'Angola';
	$CurrencyLookup['ARS']['country'] = 'Argentina';
	$CurrencyLookup['ATS']['country'] = 'Austria';
	$CurrencyLookup['AUD']['country'] = 'Australia';
	$CurrencyLookup['AWG']['country'] = 'Aruba';
	$CurrencyLookup['AZM']['country'] = 'Azerbaijan';
	$CurrencyLookup['BAM']['country'] = 'Bosnia and Herzegovina';
	$CurrencyLookup['BBD']['country'] = 'Barbados';
	$CurrencyLookup['BDT']['country'] = 'Bangladesh';
	$CurrencyLookup['BEF']['country'] = 'Belgium';
	$CurrencyLookup['BGL']['country'] = 'Bulgaria';
	$CurrencyLookup['BHD']['country'] = 'Bahrain';
	$CurrencyLookup['BIF']['country'] = 'Burundi';
	$CurrencyLookup['BMD']['country'] = 'Bermuda';
	$CurrencyLookup['BND']['country'] = 'Brunei Darussalam';
	$CurrencyLookup['BOB']['country'] = 'Bolivia';
	$CurrencyLookup['BRL']['country'] = 'Brazil';
	$CurrencyLookup['BSD']['country'] = 'Bahamas';
	$CurrencyLookup['BTN']['country'] = 'Bhutan';
	$CurrencyLookup['BWP']['country'] = 'Botswana';
	$CurrencyLookup['BYR']['country'] = 'Belarus';
	$CurrencyLookup['BZD']['country'] = 'Belize';
	$CurrencyLookup['CAD']['country'] = 'Canada';
	$CurrencyLookup['CDF']['country'] = 'Congo/Kinshasa';
	$CurrencyLookup['CHF']['country'] = 'Switzerland';
	$CurrencyLookup['CLP']['country'] = 'Chile';
	$CurrencyLookup['CNY']['country'] = 'China';
	$CurrencyLookup['COP']['country'] = 'Colombia';
	$CurrencyLookup['CRC']['country'] = 'Costa Rica';
	$CurrencyLookup['CUP']['country'] = 'Cuba';
	$CurrencyLookup['CVE']['country'] = 'Cape Verde';
	$CurrencyLookup['CYP']['country'] = 'Cyprus';
	$CurrencyLookup['CZK']['country'] = 'Czech Republic';
	$CurrencyLookup['DEM']['country'] = 'Germany';
	$CurrencyLookup['DJF']['country'] = 'Djibouti';
	$CurrencyLookup['DKK']['country'] = 'Denmark';
	$CurrencyLookup['DOP']['country'] = 'Dominican Republic';
	$CurrencyLookup['DZD']['country'] = 'Algeria';
	$CurrencyLookup['EEK']['country'] = 'Estonia';
	$CurrencyLookup['EGP']['country'] = 'Egypt';
	$CurrencyLookup['ERN']['country'] = 'Eritrea';
	$CurrencyLookup['ESP']['country'] = 'Spain';
	$CurrencyLookup['ETB']['country'] = 'Ethiopia';
	$CurrencyLookup['EUR']['country'] = 'Euro Member Countries';
	$CurrencyLookup['FIM']['country'] = 'Finland';
	$CurrencyLookup['FJD']['country'] = 'Fiji';
	$CurrencyLookup['FKP']['country'] = 'Falkland Islands (Malvinas)';
	$CurrencyLookup['FRF']['country'] = 'France';
	$CurrencyLookup['GBP']['country'] = 'United Kingdom';
	$CurrencyLookup['GEL']['country'] = 'Georgia';
	$CurrencyLookup['GGP']['country'] = 'Guernsey';
	$CurrencyLookup['GHC']['country'] = 'Ghana';
	$CurrencyLookup['GIP']['country'] = 'Gibraltar';
	$CurrencyLookup['GMD']['country'] = 'Gambia';
	$CurrencyLookup['GNF']['country'] = 'Guinea';
	$CurrencyLookup['GRD']['country'] = 'Greece';
	$CurrencyLookup['GTQ']['country'] = 'Guatemala';
	$CurrencyLookup['GYD']['country'] = 'Guyana';
	$CurrencyLookup['HKD']['country'] = 'Hong Kong';
	$CurrencyLookup['HNL']['country'] = 'Honduras';
	$CurrencyLookup['HRK']['country'] = 'Croatia';
	$CurrencyLookup['HTG']['country'] = 'Haiti';
	$CurrencyLookup['HUF']['country'] = 'Hungary';
	$CurrencyLookup['IDR']['country'] = 'Indonesia';
	$CurrencyLookup['IEP']['country'] = 'Ireland (Eire)';
	$CurrencyLookup['ILS']['country'] = 'Israel';
	$CurrencyLookup['IMP']['country'] = 'Isle of Man';
	$CurrencyLookup['INR']['country'] = 'India';
	$CurrencyLookup['IQD']['country'] = 'Iraq';
	$CurrencyLookup['IRR']['country'] = 'Iran';
	$CurrencyLookup['ISK']['country'] = 'Iceland';
	$CurrencyLookup['ITL']['country'] = 'Italy';
	$CurrencyLookup['JEP']['country'] = 'Jersey';
	$CurrencyLookup['JMD']['country'] = 'Jamaica';
	$CurrencyLookup['JOD']['country'] = 'Jordan';
	$CurrencyLookup['JPY']['country'] = 'Japan';
	$CurrencyLookup['KES']['country'] = 'Kenya';
	$CurrencyLookup['KGS']['country'] = 'Kyrgyzstan';
	$CurrencyLookup['KHR']['country'] = 'Cambodia';
	$CurrencyLookup['KMF']['country'] = 'Comoros';
	$CurrencyLookup['KPW']['country'] = 'Korea';
	$CurrencyLookup['KWD']['country'] = 'Kuwait';
	$CurrencyLookup['KYD']['country'] = 'Cayman Islands';
	$CurrencyLookup['KZT']['country'] = 'Kazakstan';
	$CurrencyLookup['LAK']['country'] = 'Laos';
	$CurrencyLookup['LBP']['country'] = 'Lebanon';
	$CurrencyLookup['LKR']['country'] = 'Sri Lanka';
	$CurrencyLookup['LRD']['country'] = 'Liberia';
	$CurrencyLookup['LSL']['country'] = 'Lesotho';
	$CurrencyLookup['LTL']['country'] = 'Lithuania';
	$CurrencyLookup['LUF']['country'] = 'Luxembourg';
	$CurrencyLookup['LVL']['country'] = 'Latvia';
	$CurrencyLookup['LYD']['country'] = 'Libya';
	$CurrencyLookup['MAD']['country'] = 'Morocco';
	$CurrencyLookup['MDL']['country'] = 'Moldova';
	$CurrencyLookup['MGF']['country'] = 'Madagascar';
	$CurrencyLookup['MKD']['country'] = 'Macedonia';
	$CurrencyLookup['MMK']['country'] = 'Myanmar (Burma)';
	$CurrencyLookup['MNT']['country'] = 'Mongolia';
	$CurrencyLookup['MOP']['country'] = 'Macau';
	$CurrencyLookup['MRO']['country'] = 'Mauritania';
	$CurrencyLookup['MTL']['country'] = 'Malta';
	$CurrencyLookup['MUR']['country'] = 'Mauritius';
	$CurrencyLookup['MVR']['country'] = 'Maldives (Maldive Islands)';
	$CurrencyLookup['MWK']['country'] = 'Malawi';
	$CurrencyLookup['MXN']['country'] = 'Mexico';
	$CurrencyLookup['MYR']['country'] = 'Malaysia';
	$CurrencyLookup['MZM']['country'] = 'Mozambique';
	$CurrencyLookup['NAD']['country'] = 'Namibia';
	$CurrencyLookup['NGN']['country'] = 'Nigeria';
	$CurrencyLookup['NIO']['country'] = 'Nicaragua';
	$CurrencyLookup['NLG']['country'] = 'Netherlands (Holland)';
	$CurrencyLookup['NOK']['country'] = 'Norway';
	$CurrencyLookup['NPR']['country'] = 'Nepal';
	$CurrencyLookup['NZD']['country'] = 'New Zealand';
	$CurrencyLookup['OMR']['country'] = 'Oman';
	$CurrencyLookup['PAB']['country'] = 'Panama';
	$CurrencyLookup['PEN']['country'] = 'Peru';
	$CurrencyLookup['PGK']['country'] = 'Papua New Guinea';
	$CurrencyLookup['PHP']['country'] = 'Philippines';
	$CurrencyLookup['PKR']['country'] = 'Pakistan';
	$CurrencyLookup['PLN']['country'] = 'Poland';
	$CurrencyLookup['PTE']['country'] = 'Portugal';
	$CurrencyLookup['PYG']['country'] = 'Paraguay';
	$CurrencyLookup['QAR']['country'] = 'Qatar';
	$CurrencyLookup['ROL']['country'] = 'Romania';
	$CurrencyLookup['RUR']['country'] = 'Russia';
	$CurrencyLookup['RWF']['country'] = 'Rwanda';
	$CurrencyLookup['SAR']['country'] = 'Saudi Arabia';
	$CurrencyLookup['SBD']['country'] = 'Solomon Islands';
	$CurrencyLookup['SCR']['country'] = 'Seychelles';
	$CurrencyLookup['SDD']['country'] = 'Sudan';
	$CurrencyLookup['SEK']['country'] = 'Sweden';
	$CurrencyLookup['SGD']['country'] = 'Singapore';
	$CurrencyLookup['SHP']['country'] = 'Saint Helena';
	$CurrencyLookup['SIT']['country'] = 'Slovenia';
	$CurrencyLookup['SKK']['country'] = 'Slovakia';
	$CurrencyLookup['SLL']['country'] = 'Sierra Leone';
	$CurrencyLookup['SOS']['country'] = 'Somalia';
	$CurrencyLookup['SPL']['country'] = 'Seborga';
	$CurrencyLookup['SRG']['country'] = 'Suriname';
	$CurrencyLookup['STD']['country'] = 'São Tome and Principe';
	$CurrencyLookup['SVC']['country'] = 'El Salvador';
	$CurrencyLookup['SYP']['country'] = 'Syria';
	$CurrencyLookup['SZL']['country'] = 'Swaziland';
	$CurrencyLookup['THB']['country'] = 'Thailand';
	$CurrencyLookup['TJR']['country'] = 'Tajikistan';
	$CurrencyLookup['TMM']['country'] = 'Turkmenistan';
	$CurrencyLookup['TND']['country'] = 'Tunisia';
	$CurrencyLookup['TOP']['country'] = 'Tonga';
	$CurrencyLookup['TRL']['country'] = 'Turkey';
	$CurrencyLookup['TTD']['country'] = 'Trinidad and Tobago';
	$CurrencyLookup['TVD']['country'] = 'Tuvalu';
	$CurrencyLookup['TWD']['country'] = 'Taiwan';
	$CurrencyLookup['TZS']['country'] = 'Tanzania';
	$CurrencyLookup['UAH']['country'] = 'Ukraine';
	$CurrencyLookup['UGX']['country'] = 'Uganda';
	$CurrencyLookup['USD']['country'] = 'United States of America';
	$CurrencyLookup['UYU']['country'] = 'Uruguay';
	$CurrencyLookup['UZS']['country'] = 'Uzbekistan';
	$CurrencyLookup['VAL']['country'] = 'Vatican City';
	$CurrencyLookup['VEB']['country'] = 'Venezuela';
	$CurrencyLookup['VND']['country'] = 'Viet Nam';
	$CurrencyLookup['VUV']['country'] = 'Vanuatu';
	$CurrencyLookup['WST']['country'] = 'Samoa';
	$CurrencyLookup['XAF']['country'] = 'Communauté Financière Africaine';
	$CurrencyLookup['XAG']['country'] = 'Silver';
	$CurrencyLookup['XAU']['country'] = 'Gold';
	$CurrencyLookup['XCD']['country'] = 'East Caribbean';
	$CurrencyLookup['XDR']['country'] = 'International Monetary Fund';
	$CurrencyLookup['XPD']['country'] = 'Palladium';
	$CurrencyLookup['XPF']['country'] = 'Comptoirs Français du Pacifique';
	$CurrencyLookup['XPT']['country'] = 'Platinum';
	$CurrencyLookup['YER']['country'] = 'Yemen';
	$CurrencyLookup['YUM']['country'] = 'Yugoslavia';
	$CurrencyLookup['ZAR']['country'] = 'South Africa';
	$CurrencyLookup['ZMK']['country'] = 'Zambia';
	$CurrencyLookup['ZWD']['country'] = 'Zimbabwe';
	$CurrencyLookup['AED']['units']   = 'Dirhams';
	$CurrencyLookup['AFA']['units']   = 'Afghanis';
	$CurrencyLookup['ALL']['units']   = 'Leke';
	$CurrencyLookup['AMD']['units']   = 'Drams';
	$CurrencyLookup['ANG']['units']   = 'Guilders';
	$CurrencyLookup['AOA']['units']   = 'Kwanza';
	$CurrencyLookup['ARS']['units']   = 'Pesos';
	$CurrencyLookup['ATS']['units']   = 'Schillings';
	$CurrencyLookup['AUD']['units']   = 'Dollars';
	$CurrencyLookup['AWG']['units']   = 'Guilders';
	$CurrencyLookup['AZM']['units']   = 'Manats';
	$CurrencyLookup['BAM']['units']   = 'Convertible Marka';
	$CurrencyLookup['BBD']['units']   = 'Dollars';
	$CurrencyLookup['BDT']['units']   = 'Taka';
	$CurrencyLookup['BEF']['units']   = 'Francs';
	$CurrencyLookup['BGL']['units']   = 'Leva';
	$CurrencyLookup['BHD']['units']   = 'Dinars';
	$CurrencyLookup['BIF']['units']   = 'Francs';
	$CurrencyLookup['BMD']['units']   = 'Dollars';
	$CurrencyLookup['BND']['units']   = 'Dollars';
	$CurrencyLookup['BOB']['units']   = 'Bolivianos';
	$CurrencyLookup['BRL']['units']   = 'Brazil Real';
	$CurrencyLookup['BSD']['units']   = 'Dollars';
	$CurrencyLookup['BTN']['units']   = 'Ngultrum';
	$CurrencyLookup['BWP']['units']   = 'Pulas';
	$CurrencyLookup['BYR']['units']   = 'Rubles';
	$CurrencyLookup['BZD']['units']   = 'Dollars';
	$CurrencyLookup['CAD']['units']   = 'Dollars';
	$CurrencyLookup['CDF']['units']   = 'Congolese Francs';
	$CurrencyLookup['CHF']['units']   = 'Francs';
	$CurrencyLookup['CLP']['units']   = 'Pesos';
	$CurrencyLookup['CNY']['units']   = 'Yuan Renminbi';
	$CurrencyLookup['COP']['units']   = 'Pesos';
	$CurrencyLookup['CRC']['units']   = 'Colones';
	$CurrencyLookup['CUP']['units']   = 'Pesos';
	$CurrencyLookup['CVE']['units']   = 'Escudos';
	$CurrencyLookup['CYP']['units']   = 'Pounds';
	$CurrencyLookup['CZK']['units']   = 'Koruny';
	$CurrencyLookup['DEM']['units']   = 'Deutsche Marks';
	$CurrencyLookup['DJF']['units']   = 'Francs';
	$CurrencyLookup['DKK']['units']   = 'Kroner';
	$CurrencyLookup['DOP']['units']   = 'Pesos';
	$CurrencyLookup['DZD']['units']   = 'Algeria Dinars';
	$CurrencyLookup['EEK']['units']   = 'Krooni';
	$CurrencyLookup['EGP']['units']   = 'Pounds';
	$CurrencyLookup['ERN']['units']   = 'Nakfa';
	$CurrencyLookup['ESP']['units']   = 'Pesetas';
	$CurrencyLookup['ETB']['units']   = 'Birr';
	$CurrencyLookup['EUR']['units']   = 'Euro';
	$CurrencyLookup['FIM']['units']   = 'Markkaa';
	$CurrencyLookup['FJD']['units']   = 'Dollars';
	$CurrencyLookup['FKP']['units']   = 'Pounds';
	$CurrencyLookup['FRF']['units']   = 'Francs';
	$CurrencyLookup['GBP']['units']   = 'Pounds';
	$CurrencyLookup['GEL']['units']   = 'Lari';
	$CurrencyLookup['GGP']['units']   = 'Pounds';
	$CurrencyLookup['GHC']['units']   = 'Cedis';
	$CurrencyLookup['GIP']['units']   = 'Pounds';
	$CurrencyLookup['GMD']['units']   = 'Dalasi';
	$CurrencyLookup['GNF']['units']   = 'Francs';
	$CurrencyLookup['GRD']['units']   = 'Drachmae';
	$CurrencyLookup['GTQ']['units']   = 'Quetzales';
	$CurrencyLookup['GYD']['units']   = 'Dollars';
	$CurrencyLookup['HKD']['units']   = 'Dollars';
	$CurrencyLookup['HNL']['units']   = 'Lempiras';
	$CurrencyLookup['HRK']['units']   = 'Kuna';
	$CurrencyLookup['HTG']['units']   = 'Gourdes';
	$CurrencyLookup['HUF']['units']   = 'Forints';
	$CurrencyLookup['IDR']['units']   = 'Rupiahs';
	$CurrencyLookup['IEP']['units']   = 'Pounds';
	$CurrencyLookup['ILS']['units']   = 'New Shekels';
	$CurrencyLookup['IMP']['units']   = 'Pounds';
	$CurrencyLookup['INR']['units']   = 'Rupees';
	$CurrencyLookup['IQD']['units']   = 'Dinars';
	$CurrencyLookup['IRR']['units']   = 'Rials';
	$CurrencyLookup['ISK']['units']   = 'Kronur';
	$CurrencyLookup['ITL']['units']   = 'Lire';
	$CurrencyLookup['JEP']['units']   = 'Pounds';
	$CurrencyLookup['JMD']['units']   = 'Dollars';
	$CurrencyLookup['JOD']['units']   = 'Dinars';
	$CurrencyLookup['JPY']['units']   = 'Yen';
	$CurrencyLookup['KES']['units']   = 'Shillings';
	$CurrencyLookup['KGS']['units']   = 'Soms';
	$CurrencyLookup['KHR']['units']   = 'Riels';
	$CurrencyLookup['KMF']['units']   = 'Francs';
	$CurrencyLookup['KPW']['units']   = 'Won';
	$CurrencyLookup['KWD']['units']   = 'Dinars';
	$CurrencyLookup['KYD']['units']   = 'Dollars';
	$CurrencyLookup['KZT']['units']   = 'Tenge';
	$CurrencyLookup['LAK']['units']   = 'Kips';
	$CurrencyLookup['LBP']['units']   = 'Pounds';
	$CurrencyLookup['LKR']['units']   = 'Rupees';
	$CurrencyLookup['LRD']['units']   = 'Dollars';
	$CurrencyLookup['LSL']['units']   = 'Maloti';
	$CurrencyLookup['LTL']['units']   = 'Litai';
	$CurrencyLookup['LUF']['units']   = 'Francs';
	$CurrencyLookup['LVL']['units']   = 'Lati';
	$CurrencyLookup['LYD']['units']   = 'Dinars';
	$CurrencyLookup['MAD']['units']   = 'Dirhams';
	$CurrencyLookup['MDL']['units']   = 'Lei';
	$CurrencyLookup['MGF']['units']   = 'Malagasy Francs';
	$CurrencyLookup['MKD']['units']   = 'Denars';
	$CurrencyLookup['MMK']['units']   = 'Kyats';
	$CurrencyLookup['MNT']['units']   = 'Tugriks';
	$CurrencyLookup['MOP']['units']   = 'Patacas';
	$CurrencyLookup['MRO']['units']   = 'Ouguiyas';
	$CurrencyLookup['MTL']['units']   = 'Liri';
	$CurrencyLookup['MUR']['units']   = 'Rupees';
	$CurrencyLookup['MVR']['units']   = 'Rufiyaa';
	$CurrencyLookup['MWK']['units']   = 'Kwachas';
	$CurrencyLookup['MXN']['units']   = 'Pesos';
	$CurrencyLookup['MYR']['units']   = 'Ringgits';
	$CurrencyLookup['MZM']['units']   = 'Meticais';
	$CurrencyLookup['NAD']['units']   = 'Dollars';
	$CurrencyLookup['NGN']['units']   = 'Nairas';
	$CurrencyLookup['NIO']['units']   = 'Gold Cordobas';
	$CurrencyLookup['NLG']['units']   = 'Guilders';
	$CurrencyLookup['NOK']['units']   = 'Krone';
	$CurrencyLookup['NPR']['units']   = 'Nepal Rupees';
	$CurrencyLookup['NZD']['units']   = 'Dollars';
	$CurrencyLookup['OMR']['units']   = 'Rials';
	$CurrencyLookup['PAB']['units']   = 'Balboa';
	$CurrencyLookup['PEN']['units']   = 'Nuevos Soles';
	$CurrencyLookup['PGK']['units']   = 'Kina';
	$CurrencyLookup['PHP']['units']   = 'Pesos';
	$CurrencyLookup['PKR']['units']   = 'Rupees';
	$CurrencyLookup['PLN']['units']   = 'Zlotych';
	$CurrencyLookup['PTE']['units']   = 'Escudos';
	$CurrencyLookup['PYG']['units']   = 'Guarani';
	$CurrencyLookup['QAR']['units']   = 'Rials';
	$CurrencyLookup['ROL']['units']   = 'Lei';
	$CurrencyLookup['RUR']['units']   = 'Rubles';
	$CurrencyLookup['RWF']['units']   = 'Rwanda Francs';
	$CurrencyLookup['SAR']['units']   = 'Riyals';
	$CurrencyLookup['SBD']['units']   = 'Dollars';
	$CurrencyLookup['SCR']['units']   = 'Rupees';
	$CurrencyLookup['SDD']['units']   = 'Dinars';
	$CurrencyLookup['SEK']['units']   = 'Kronor';
	$CurrencyLookup['SGD']['units']   = 'Dollars';
	$CurrencyLookup['SHP']['units']   = 'Pounds';
	$CurrencyLookup['SIT']['units']   = 'Tolars';
	$CurrencyLookup['SKK']['units']   = 'Koruny';
	$CurrencyLookup['SLL']['units']   = 'Leones';
	$CurrencyLookup['SOS']['units']   = 'Shillings';
	$CurrencyLookup['SPL']['units']   = 'Luigini';
	$CurrencyLookup['SRG']['units']   = 'Guilders';
	$CurrencyLookup['STD']['units']   = 'Dobras';
	$CurrencyLookup['SVC']['units']   = 'Colones';
	$CurrencyLookup['SYP']['units']   = 'Pounds';
	$CurrencyLookup['SZL']['units']   = 'Emalangeni';
	$CurrencyLookup['THB']['units']   = 'Baht';
	$CurrencyLookup['TJR']['units']   = 'Rubles';
	$CurrencyLookup['TMM']['units']   = 'Manats';
	$CurrencyLookup['TND']['units']   = 'Dinars';
	$CurrencyLookup['TOP']['units']   = 'Pa\'anga';
	$CurrencyLookup['TRL']['units']   = 'Liras';
	$CurrencyLookup['TTD']['units']   = 'Dollars';
	$CurrencyLookup['TVD']['units']   = 'Tuvalu Dollars';
	$CurrencyLookup['TWD']['units']   = 'New Dollars';
	$CurrencyLookup['TZS']['units']   = 'Shillings';
	$CurrencyLookup['UAH']['units']   = 'Hryvnia';
	$CurrencyLookup['UGX']['units']   = 'Shillings';
	$CurrencyLookup['USD']['units']   = 'Dollars';
	$CurrencyLookup['UYU']['units']   = 'Pesos';
	$CurrencyLookup['UZS']['units']   = 'Sums';
	$CurrencyLookup['VAL']['units']   = 'Lire';
	$CurrencyLookup['VEB']['units']   = 'Bolivares';
	$CurrencyLookup['VND']['units']   = 'Dong';
	$CurrencyLookup['VUV']['units']   = 'Vatu';
	$CurrencyLookup['WST']['units']   = 'Tala';
	$CurrencyLookup['XAF']['units']   = 'Francs';
	$CurrencyLookup['XAG']['units']   = 'Ounces';
	$CurrencyLookup['XAU']['units']   = 'Ounces';
	$CurrencyLookup['XCD']['units']   = 'Dollars';
	$CurrencyLookup['XDR']['units']   = 'Special Drawing Rights';
	$CurrencyLookup['XPD']['units']   = 'Ounces';
	$CurrencyLookup['XPF']['units']   = 'Francs';
	$CurrencyLookup['XPT']['units']   = 'Ounces';
	$CurrencyLookup['YER']['units']   = 'Rials';
	$CurrencyLookup['YUM']['units']   = 'New Dinars';
	$CurrencyLookup['ZAR']['units']   = 'Rand';
	$CurrencyLookup['ZMK']['units']   = 'Kwacha';
	$CurrencyLookup['ZWD']['units']   = 'Zimbabwe Dollars';

	return (isset($CurrencyLookup["$currencyid"]["$item"]) ? $CurrencyLookup["$currencyid"]["$item"] : '');
}

function LanguageLookup($languagecode, $casesensitive=FALSE) {
	// ISO 639-2 - http://www.id3.org/iso639-2.html
	if ($languagecode == 'XXX') {
		return 'unknown';
	}
	if (!$casesensitive) {
		$languagecode = strtolower($languagecode);
	}

	$LanguageLookup['aar'] = 'Afar';
	$LanguageLookup['abk'] = 'Abkhazian';
	$LanguageLookup['ace'] = 'Achinese';
	$LanguageLookup['ach'] = 'Acoli';
	$LanguageLookup['ada'] = 'Adangme';
	$LanguageLookup['afa'] = 'Afro-Asiatic (Other)';
	$LanguageLookup['afh'] = 'Afrihili';
	$LanguageLookup['afr'] = 'Afrikaans';
	$LanguageLookup['aka'] = 'Akan';
	$LanguageLookup['akk'] = 'Akkadian';
	$LanguageLookup['alb'] = 'Albanian';
	$LanguageLookup['ale'] = 'Aleut';
	$LanguageLookup['alg'] = 'Algonquian Languages';
	$LanguageLookup['amh'] = 'Amharic';
	$LanguageLookup['ang'] = 'English, Old (ca. 450-1100)';
	$LanguageLookup['apa'] = 'Apache Languages';
	$LanguageLookup['ara'] = 'Arabic';
	$LanguageLookup['arc'] = 'Aramaic';
	$LanguageLookup['arm'] = 'Armenian';
	$LanguageLookup['arn'] = 'Araucanian';
	$LanguageLookup['arp'] = 'Arapaho';
	$LanguageLookup['art'] = 'Artificial (Other)';
	$LanguageLookup['arw'] = 'Arawak';
	$LanguageLookup['asm'] = 'Assamese';
	$LanguageLookup['ath'] = 'Athapascan Languages';
	$LanguageLookup['ava'] = 'Avaric';
	$LanguageLookup['ave'] = 'Avestan';
	$LanguageLookup['awa'] = 'Awadhi';
	$LanguageLookup['aym'] = 'Aymara';
	$LanguageLookup['aze'] = 'Azerbaijani';
	$LanguageLookup['bad'] = 'Banda';
	$LanguageLookup['bai'] = 'Bamileke Languages';
	$LanguageLookup['bak'] = 'Bashkir';
	$LanguageLookup['bal'] = 'Baluchi';
	$LanguageLookup['bam'] = 'Bambara';
	$LanguageLookup['ban'] = 'Balinese';
	$LanguageLookup['baq'] = 'Basque';
	$LanguageLookup['bas'] = 'Basa';
	$LanguageLookup['bat'] = 'Baltic (Other)';
	$LanguageLookup['bej'] = 'Beja';
	$LanguageLookup['bel'] = 'Byelorussian';
	$LanguageLookup['bem'] = 'Bemba';
	$LanguageLookup['ben'] = 'Bengali';
	$LanguageLookup['ber'] = 'Berber (Other)';
	$LanguageLookup['bho'] = 'Bhojpuri';
	$LanguageLookup['bih'] = 'Bihari';
	$LanguageLookup['bik'] = 'Bikol';
	$LanguageLookup['bin'] = 'Bini';
	$LanguageLookup['bis'] = 'Bislama';
	$LanguageLookup['bla'] = 'Siksika';
	$LanguageLookup['bnt'] = 'Bantu (Other)';
	$LanguageLookup['bod'] = 'Tibetan';
	$LanguageLookup['bra'] = 'Braj';
	$LanguageLookup['bre'] = 'Breton';
	$LanguageLookup['bua'] = 'Buriat';
	$LanguageLookup['bug'] = 'Buginese';
	$LanguageLookup['bul'] = 'Bulgarian';
	$LanguageLookup['bur'] = 'Burmese';
	$LanguageLookup['cad'] = 'Caddo';
	$LanguageLookup['cai'] = 'Central American Indian (Other)';
	$LanguageLookup['car'] = 'Carib';
	$LanguageLookup['cat'] = 'Catalan';
	$LanguageLookup['cau'] = 'Caucasian (Other)';
	$LanguageLookup['ceb'] = 'Cebuano';
	$LanguageLookup['cel'] = 'Celtic (Other)';
	$LanguageLookup['ces'] = 'Czech';
	$LanguageLookup['cha'] = 'Chamorro';
	$LanguageLookup['chb'] = 'Chibcha';
	$LanguageLookup['che'] = 'Chechen';
	$LanguageLookup['chg'] = 'Chagatai';
	$LanguageLookup['chi'] = 'Chinese';
	$LanguageLookup['chm'] = 'Mari';
	$LanguageLookup['chn'] = 'Chinook jargon';
	$LanguageLookup['cho'] = 'Choctaw';
	$LanguageLookup['chr'] = 'Cherokee';
	$LanguageLookup['chu'] = 'Church Slavic';
	$LanguageLookup['chv'] = 'Chuvash';
	$LanguageLookup['chy'] = 'Cheyenne';
	$LanguageLookup['cop'] = 'Coptic';
	$LanguageLookup['cor'] = 'Cornish';
	$LanguageLookup['cos'] = 'Corsican';
	$LanguageLookup['cpe'] = 'Creoles and Pidgins, English-based (Other)';
	$LanguageLookup['cpf'] = 'Creoles and Pidgins, French-based (Other)';
	$LanguageLookup['cpp'] = 'Creoles and Pidgins, Portuguese-based (Other)';
	$LanguageLookup['cre'] = 'Cree';
	$LanguageLookup['crp'] = 'Creoles and Pidgins (Other)';
	$LanguageLookup['cus'] = 'Cushitic (Other)';
	$LanguageLookup['cym'] = 'Welsh';
	$LanguageLookup['cze'] = 'Czech';
	$LanguageLookup['dak'] = 'Dakota';
	$LanguageLookup['dan'] = 'Danish';
	$LanguageLookup['del'] = 'Delaware';
	$LanguageLookup['deu'] = 'German';
	$LanguageLookup['din'] = 'Dinka';
	$LanguageLookup['div'] = 'Divehi';
	$LanguageLookup['doi'] = 'Dogri';
	$LanguageLookup['dra'] = 'Dravidian (Other)';
	$LanguageLookup['dua'] = 'Duala';
	$LanguageLookup['dum'] = 'Dutch, Middle (ca. 1050-1350)';
	$LanguageLookup['dut'] = 'Dutch';
	$LanguageLookup['dyu'] = 'Dyula';
	$LanguageLookup['dzo'] = 'Dzongkha';
	$LanguageLookup['efi'] = 'Efik';
	$LanguageLookup['egy'] = 'Egyptian (Ancient)';
	$LanguageLookup['eka'] = 'Ekajuk';
	$LanguageLookup['ell'] = 'Greek, Modern (1453-)';
	$LanguageLookup['elx'] = 'Elamite';
	$LanguageLookup['eng'] = 'English';
	$LanguageLookup['enm'] = 'English, Middle (ca. 1100-1500)';
	$LanguageLookup['epo'] = 'Esperanto';
	$LanguageLookup['esk'] = 'Eskimo (Other)';
	$LanguageLookup['esl'] = 'Spanish';
	$LanguageLookup['est'] = 'Estonian';
	$LanguageLookup['eus'] = 'Basque';
	$LanguageLookup['ewe'] = 'Ewe';
	$LanguageLookup['ewo'] = 'Ewondo';
	$LanguageLookup['fan'] = 'Fang';
	$LanguageLookup['fao'] = 'Faroese';
	$LanguageLookup['fas'] = 'Persian';
	$LanguageLookup['fat'] = 'Fanti';
	$LanguageLookup['fij'] = 'Fijian';
	$LanguageLookup['fin'] = 'Finnish';
	$LanguageLookup['fiu'] = 'Finno-Ugrian (Other)';
	$LanguageLookup['fon'] = 'Fon';
	$LanguageLookup['fra'] = 'French';
	$LanguageLookup['fre'] = 'French';
	$LanguageLookup['frm'] = 'French, Middle (ca. 1400-1600)';
	$LanguageLookup['fro'] = 'French, Old (842- ca. 1400)';
	$LanguageLookup['fry'] = 'Frisian';
	$LanguageLookup['ful'] = 'Fulah';
	$LanguageLookup['gaa'] = 'Ga';
	$LanguageLookup['gae'] = 'Gaelic (Scots)';
	$LanguageLookup['gai'] = 'Irish';
	$LanguageLookup['gay'] = 'Gayo';
	$LanguageLookup['gdh'] = 'Gaelic (Scots)';
	$LanguageLookup['gem'] = 'Germanic (Other)';
	$LanguageLookup['geo'] = 'Georgian';
	$LanguageLookup['ger'] = 'German';
	$LanguageLookup['gez'] = 'Geez';
	$LanguageLookup['gil'] = 'Gilbertese';
	$LanguageLookup['glg'] = 'Gallegan';
	$LanguageLookup['gmh'] = 'German, Middle High (ca. 1050-1500)';
	$LanguageLookup['goh'] = 'German, Old High (ca. 750-1050)';
	$LanguageLookup['gon'] = 'Gondi';
	$LanguageLookup['got'] = 'Gothic';
	$LanguageLookup['grb'] = 'Grebo';
	$LanguageLookup['grc'] = 'Greek, Ancient (to 1453)';
	$LanguageLookup['gre'] = 'Greek, Modern (1453-)';
	$LanguageLookup['grn'] = 'Guarani';
	$LanguageLookup['guj'] = 'Gujarati';
	$LanguageLookup['hai'] = 'Haida';
	$LanguageLookup['hau'] = 'Hausa';
	$LanguageLookup['haw'] = 'Hawaiian';
	$LanguageLookup['heb'] = 'Hebrew';
	$LanguageLookup['her'] = 'Herero';
	$LanguageLookup['hil'] = 'Hiligaynon';
	$LanguageLookup['him'] = 'Himachali';
	$LanguageLookup['hin'] = 'Hindi';
	$LanguageLookup['hmo'] = 'Hiri Motu';
	$LanguageLookup['hun'] = 'Hungarian';
	$LanguageLookup['hup'] = 'Hupa';
	$LanguageLookup['hye'] = 'Armenian';
	$LanguageLookup['iba'] = 'Iban';
	$LanguageLookup['ibo'] = 'Igbo';
	$LanguageLookup['ice'] = 'Icelandic';
	$LanguageLookup['ijo'] = 'Ijo';
	$LanguageLookup['iku'] = 'Inuktitut';
	$LanguageLookup['ilo'] = 'Iloko';
	$LanguageLookup['ina'] = 'Interlingua (International Auxiliary language Association)';
	$LanguageLookup['inc'] = 'Indic (Other)';
	$LanguageLookup['ind'] = 'Indonesian';
	$LanguageLookup['ine'] = 'Indo-European (Other)';
	$LanguageLookup['ine'] = 'Interlingue';
	$LanguageLookup['ipk'] = 'Inupiak';
	$LanguageLookup['ira'] = 'Iranian (Other)';
	$LanguageLookup['iri'] = 'Irish';
	$LanguageLookup['iro'] = 'Iroquoian uages';
	$LanguageLookup['isl'] = 'Icelandic';
	$LanguageLookup['ita'] = 'Italian';
	$LanguageLookup['jav'] = 'Javanese';
	$LanguageLookup['jaw'] = 'Javanese';
	$LanguageLookup['jpn'] = 'Japanese';
	$LanguageLookup['jpr'] = 'Judeo-Persian';
	$LanguageLookup['jrb'] = 'Judeo-Arabic';
	$LanguageLookup['kaa'] = 'Kara-Kalpak';
	$LanguageLookup['kab'] = 'Kabyle';
	$LanguageLookup['kac'] = 'Kachin';
	$LanguageLookup['kal'] = 'Greenlandic';
	$LanguageLookup['kam'] = 'Kamba';
	$LanguageLookup['kan'] = 'Kannada';
	$LanguageLookup['kar'] = 'Karen';
	$LanguageLookup['kas'] = 'Kashmiri';
	$LanguageLookup['kat'] = 'Georgian';
	$LanguageLookup['kau'] = 'Kanuri';
	$LanguageLookup['kaw'] = 'Kawi';
	$LanguageLookup['kaz'] = 'Kazakh';
	$LanguageLookup['kha'] = 'Khasi';
	$LanguageLookup['khi'] = 'Khoisan (Other)';
	$LanguageLookup['khm'] = 'Khmer';
	$LanguageLookup['kho'] = 'Khotanese';
	$LanguageLookup['kik'] = 'Kikuyu';
	$LanguageLookup['kin'] = 'Kinyarwanda';
	$LanguageLookup['kir'] = 'Kirghiz';
	$LanguageLookup['kok'] = 'Konkani';
	$LanguageLookup['kom'] = 'Komi';
	$LanguageLookup['kon'] = 'Kongo';
	$LanguageLookup['kor'] = 'Korean';
	$LanguageLookup['kpe'] = 'Kpelle';
	$LanguageLookup['kro'] = 'Kru';
	$LanguageLookup['kru'] = 'Kurukh';
	$LanguageLookup['kua'] = 'Kuanyama';
	$LanguageLookup['kum'] = 'Kumyk';
	$LanguageLookup['kur'] = 'Kurdish';
	$LanguageLookup['kus'] = 'Kusaie';
	$LanguageLookup['kut'] = 'Kutenai';
	$LanguageLookup['lad'] = 'Ladino';
	$LanguageLookup['lah'] = 'Lahnda';
	$LanguageLookup['lam'] = 'Lamba';
	$LanguageLookup['lao'] = 'Lao';
	$LanguageLookup['lat'] = 'Latin';
	$LanguageLookup['lav'] = 'Latvian';
	$LanguageLookup['lez'] = 'Lezghian';
	$LanguageLookup['lin'] = 'Lingala';
	$LanguageLookup['lit'] = 'Lithuanian';
	$LanguageLookup['lol'] = 'Mongo';
	$LanguageLookup['loz'] = 'Lozi';
	$LanguageLookup['ltz'] = 'Letzeburgesch';
	$LanguageLookup['lub'] = 'Luba-Katanga';
	$LanguageLookup['lug'] = 'Ganda';
	$LanguageLookup['lui'] = 'Luiseno';
	$LanguageLookup['lun'] = 'Lunda';
	$LanguageLookup['luo'] = 'Luo (Kenya and Tanzania)';
	$LanguageLookup['mac'] = 'Macedonian';
	$LanguageLookup['mad'] = 'Madurese';
	$LanguageLookup['mag'] = 'Magahi';
	$LanguageLookup['mah'] = 'Marshall';
	$LanguageLookup['mai'] = 'Maithili';
	$LanguageLookup['mak'] = 'Macedonian';
	$LanguageLookup['mak'] = 'Makasar';
	$LanguageLookup['mal'] = 'Malayalam';
	$LanguageLookup['man'] = 'Mandingo';
	$LanguageLookup['mao'] = 'Maori';
	$LanguageLookup['map'] = 'Austronesian (Other)';
	$LanguageLookup['mar'] = 'Marathi';
	$LanguageLookup['mas'] = 'Masai';
	$LanguageLookup['max'] = 'Manx';
	$LanguageLookup['may'] = 'Malay';
	$LanguageLookup['men'] = 'Mende';
	$LanguageLookup['mga'] = 'Irish, Middle (900 - 1200)';
	$LanguageLookup['mic'] = 'Micmac';
	$LanguageLookup['min'] = 'Minangkabau';
	$LanguageLookup['mis'] = 'Miscellaneous (Other)';
	$LanguageLookup['mkh'] = 'Mon-Kmer (Other)';
	$LanguageLookup['mlg'] = 'Malagasy';
	$LanguageLookup['mlt'] = 'Maltese';
	$LanguageLookup['mni'] = 'Manipuri';
	$LanguageLookup['mno'] = 'Manobo Languages';
	$LanguageLookup['moh'] = 'Mohawk';
	$LanguageLookup['mol'] = 'Moldavian';
	$LanguageLookup['mon'] = 'Mongolian';
	$LanguageLookup['mos'] = 'Mossi';
	$LanguageLookup['mri'] = 'Maori';
	$LanguageLookup['msa'] = 'Malay';
	$LanguageLookup['mul'] = 'Multiple Languages';
	$LanguageLookup['mun'] = 'Munda Languages';
	$LanguageLookup['mus'] = 'Creek';
	$LanguageLookup['mwr'] = 'Marwari';
	$LanguageLookup['mya'] = 'Burmese';
	$LanguageLookup['myn'] = 'Mayan Languages';
	$LanguageLookup['nah'] = 'Aztec';
	$LanguageLookup['nai'] = 'North American Indian (Other)';
	$LanguageLookup['nau'] = 'Nauru';
	$LanguageLookup['nav'] = 'Navajo';
	$LanguageLookup['nbl'] = 'Ndebele, South';
	$LanguageLookup['nde'] = 'Ndebele, North';
	$LanguageLookup['ndo'] = 'Ndongo';
	$LanguageLookup['nep'] = 'Nepali';
	$LanguageLookup['new'] = 'Newari';
	$LanguageLookup['nic'] = 'Niger-Kordofanian (Other)';
	$LanguageLookup['niu'] = 'Niuean';
	$LanguageLookup['nla'] = 'Dutch';
	$LanguageLookup['nno'] = 'Norwegian (Nynorsk)';
	$LanguageLookup['non'] = 'Norse, Old';
	$LanguageLookup['nor'] = 'Norwegian';
	$LanguageLookup['nso'] = 'Sotho, Northern';
	$LanguageLookup['nub'] = 'Nubian Languages';
	$LanguageLookup['nya'] = 'Nyanja';
	$LanguageLookup['nym'] = 'Nyamwezi';
	$LanguageLookup['nyn'] = 'Nyankole';
	$LanguageLookup['nyo'] = 'Nyoro';
	$LanguageLookup['nzi'] = 'Nzima';
	$LanguageLookup['oci'] = 'Langue d\'Oc (post 1500)';
	$LanguageLookup['oji'] = 'Ojibwa';
	$LanguageLookup['ori'] = 'Oriya';
	$LanguageLookup['orm'] = 'Oromo';
	$LanguageLookup['osa'] = 'Osage';
	$LanguageLookup['oss'] = 'Ossetic';
	$LanguageLookup['ota'] = 'Turkish, Ottoman (1500 - 1928)';
	$LanguageLookup['oto'] = 'Otomian Languages';
	$LanguageLookup['paa'] = 'Papuan-Australian (Other)';
	$LanguageLookup['pag'] = 'Pangasinan';
	$LanguageLookup['pal'] = 'Pahlavi';
	$LanguageLookup['pam'] = 'Pampanga';
	$LanguageLookup['pan'] = 'Panjabi';
	$LanguageLookup['pap'] = 'Papiamento';
	$LanguageLookup['pau'] = 'Palauan';
	$LanguageLookup['peo'] = 'Persian, Old (ca 600 - 400 B.C.)';
	$LanguageLookup['per'] = 'Persian';
	$LanguageLookup['phn'] = 'Phoenician';
	$LanguageLookup['pli'] = 'Pali';
	$LanguageLookup['pol'] = 'Polish';
	$LanguageLookup['pon'] = 'Ponape';
	$LanguageLookup['por'] = 'Portuguese';
	$LanguageLookup['pra'] = 'Prakrit uages';
	$LanguageLookup['pro'] = 'Provencal, Old (to 1500)';
	$LanguageLookup['pus'] = 'Pushto';
	$LanguageLookup['que'] = 'Quechua';
	$LanguageLookup['raj'] = 'Rajasthani';
	$LanguageLookup['rar'] = 'Rarotongan';
	$LanguageLookup['roa'] = 'Romance (Other)';
	$LanguageLookup['roh'] = 'Rhaeto-Romance';
	$LanguageLookup['rom'] = 'Romany';
	$LanguageLookup['ron'] = 'Romanian';
	$LanguageLookup['rum'] = 'Romanian';
	$LanguageLookup['run'] = 'Rundi';
	$LanguageLookup['rus'] = 'Russian';
	$LanguageLookup['sad'] = 'Sandawe';
	$LanguageLookup['sag'] = 'Sango';
	$LanguageLookup['sah'] = 'Yakut';
	$LanguageLookup['sai'] = 'South American Indian (Other)';
	$LanguageLookup['sal'] = 'Salishan Languages';
	$LanguageLookup['sam'] = 'Samaritan Aramaic';
	$LanguageLookup['san'] = 'Sanskrit';
	$LanguageLookup['sco'] = 'Scots';
	$LanguageLookup['scr'] = 'Serbo-Croatian';
	$LanguageLookup['sel'] = 'Selkup';
	$LanguageLookup['sem'] = 'Semitic (Other)';
	$LanguageLookup['sga'] = 'Irish, Old (to 900)';
	$LanguageLookup['shn'] = 'Shan';
	$LanguageLookup['sid'] = 'Sidamo';
	$LanguageLookup['sin'] = 'Singhalese';
	$LanguageLookup['sio'] = 'Siouan Languages';
	$LanguageLookup['sit'] = 'Sino-Tibetan (Other)';
	$LanguageLookup['sla'] = 'Slavic (Other)';
	$LanguageLookup['slk'] = 'Slovak';
	$LanguageLookup['slo'] = 'Slovak';
	$LanguageLookup['slv'] = 'Slovenian';
	$LanguageLookup['smi'] = 'Sami Languages';
	$LanguageLookup['smo'] = 'Samoan';
	$LanguageLookup['sna'] = 'Shona';
	$LanguageLookup['snd'] = 'Sindhi';
	$LanguageLookup['sog'] = 'Sogdian';
	$LanguageLookup['som'] = 'Somali';
	$LanguageLookup['son'] = 'Songhai';
	$LanguageLookup['sot'] = 'Sotho, Southern';
	$LanguageLookup['spa'] = 'Spanish';
	$LanguageLookup['sqi'] = 'Albanian';
	$LanguageLookup['srd'] = 'Sardinian';
	$LanguageLookup['srr'] = 'Serer';
	$LanguageLookup['ssa'] = 'Nilo-Saharan (Other)';
	$LanguageLookup['ssw'] = 'Siswant';
	$LanguageLookup['ssw'] = 'Swazi';
	$LanguageLookup['suk'] = 'Sukuma';
	$LanguageLookup['sun'] = 'Sudanese';
	$LanguageLookup['sus'] = 'Susu';
	$LanguageLookup['sux'] = 'Sumerian';
	$LanguageLookup['sve'] = 'Swedish';
	$LanguageLookup['swa'] = 'Swahili';
	$LanguageLookup['swe'] = 'Swedish';
	$LanguageLookup['syr'] = 'Syriac';
	$LanguageLookup['tah'] = 'Tahitian';
	$LanguageLookup['tam'] = 'Tamil';
	$LanguageLookup['tat'] = 'Tatar';
	$LanguageLookup['tel'] = 'Telugu';
	$LanguageLookup['tem'] = 'Timne';
	$LanguageLookup['ter'] = 'Tereno';
	$LanguageLookup['tgk'] = 'Tajik';
	$LanguageLookup['tgl'] = 'Tagalog';
	$LanguageLookup['tha'] = 'Thai';
	$LanguageLookup['tib'] = 'Tibetan';
	$LanguageLookup['tig'] = 'Tigre';
	$LanguageLookup['tir'] = 'Tigrinya';
	$LanguageLookup['tiv'] = 'Tivi';
	$LanguageLookup['tli'] = 'Tlingit';
	$LanguageLookup['tmh'] = 'Tamashek';
	$LanguageLookup['tog'] = 'Tonga (Nyasa)';
	$LanguageLookup['ton'] = 'Tonga (Tonga Islands)';
	$LanguageLookup['tru'] = 'Truk';
	$LanguageLookup['tsi'] = 'Tsimshian';
	$LanguageLookup['tsn'] = 'Tswana';
	$LanguageLookup['tso'] = 'Tsonga';
	$LanguageLookup['tuk'] = 'Turkmen';
	$LanguageLookup['tum'] = 'Tumbuka';
	$LanguageLookup['tur'] = 'Turkish';
	$LanguageLookup['tut'] = 'Altaic (Other)';
	$LanguageLookup['twi'] = 'Twi';
	$LanguageLookup['tyv'] = 'Tuvinian';
	$LanguageLookup['uga'] = 'Ugaritic';
	$LanguageLookup['uig'] = 'Uighur';
	$LanguageLookup['ukr'] = 'Ukrainian';
	$LanguageLookup['umb'] = 'Umbundu';
	$LanguageLookup['und'] = 'Undetermined';
	$LanguageLookup['urd'] = 'Urdu';
	$LanguageLookup['uzb'] = 'Uzbek';
	$LanguageLookup['vai'] = 'Vai';
	$LanguageLookup['ven'] = 'Venda';
	$LanguageLookup['vie'] = 'Vietnamese';
	$LanguageLookup['vol'] = 'Volapük';
	$LanguageLookup['vot'] = 'Votic';
	$LanguageLookup['wak'] = 'Wakashan Languages';
	$LanguageLookup['wal'] = 'Walamo';
	$LanguageLookup['war'] = 'Waray';
	$LanguageLookup['was'] = 'Washo';
	$LanguageLookup['wel'] = 'Welsh';
	$LanguageLookup['wen'] = 'Sorbian Languages';
	$LanguageLookup['wol'] = 'Wolof';
	$LanguageLookup['xho'] = 'Xhosa';
	$LanguageLookup['yao'] = 'Yao';
	$LanguageLookup['yap'] = 'Yap';
	$LanguageLookup['yid'] = 'Yiddish';
	$LanguageLookup['yor'] = 'Yoruba';
	$LanguageLookup['zap'] = 'Zapotec';
	$LanguageLookup['zen'] = 'Zenaga';
	$LanguageLookup['zha'] = 'Zhuang';
	$LanguageLookup['zho'] = 'Chinese';
	$LanguageLookup['zul'] = 'Zulu';
	$LanguageLookup['zun'] = 'Zuni';

	return (isset($LanguageLookup["$languagecode"]) ? $LanguageLookup["$languagecode"] : '');
}

function ETCOEventLookup($index) {
	$EventLookup[0x00] = 'padding (has no meaning)';
	$EventLookup[0x01] = 'end of initial silence';
	$EventLookup[0x02] = 'intro start';
	$EventLookup[0x03] = 'main part start';
	$EventLookup[0x04] = 'outro start';
	$EventLookup[0x05] = 'outro end';
	$EventLookup[0x06] = 'verse start';
	$EventLookup[0x07] = 'refrain start';
	$EventLookup[0x08] = 'interlude start';
	$EventLookup[0x09] = 'theme start';
	$EventLookup[0x0A] = 'variation start';
	$EventLookup[0x0B] = 'key change';
	$EventLookup[0x0C] = 'time change';
	$EventLookup[0x0D] = 'momentary unwanted noise (Snap, Crackle & Pop)';
	$EventLookup[0x0E] = 'sustained noise';
	$EventLookup[0x0F] = 'sustained noise end';
	$EventLookup[0x10] = 'intro end';
	$EventLookup[0x11] = 'main part end';
	$EventLookup[0x12] = 'verse end';
	$EventLookup[0x13] = 'refrain end';
	$EventLookup[0x14] = 'theme end';
	$EventLookup[0x15] = 'profanity';
	$EventLookup[0x16] = 'profanity end';
	for ($i=0x17;$i<=0xDF;$i++) {
		$EventLookup["$i"] = 'reserved for future use';
	}
	for ($i=0xE0;$i<=0xEF;$i++) {
		$EventLookup["$i"] = 'not predefined synch 0-F';
	}
	for ($i=0xF0;$i<=0xFC;$i++) {
		$EventLookup["$i"] = 'reserved for future use';
	}
	$EventLookup[0xFD] = 'audio end (start of silence)';
	$EventLookup[0xFE] = 'audio file ends';
	$EventLookup[0xFF] = 'one more byte of events follows';

	return (isset($EventLookup["$index"]) ? $EventLookup["$index"] : '');
}

function SYTLContentTypeLookup($index) {
	$SYTLContentTypeLookup[0x00] = 'other';
	$SYTLContentTypeLookup[0x01] = 'lyrics';
	$SYTLContentTypeLookup[0x02] = 'text transcription';
	$SYTLContentTypeLookup[0x03] = 'movement/part name';          // (e.g. 'Adagio')
	$SYTLContentTypeLookup[0x04] = 'events';                      // (e.g. 'Don Quijote enters the stage')
	$SYTLContentTypeLookup[0x05] = 'chord';                       // (e.g. 'Bb F Fsus')
	$SYTLContentTypeLookup[0x06] = 'trivia/\'pop up\' information';
	$SYTLContentTypeLookup[0x07] = 'URLs to webpages';
	$SYTLContentTypeLookup[0x08] = 'URLs to images';

	return (isset($SYTLContentTypeLookup["$index"]) ? $SYTLContentTypeLookup["$index"] : '');
}

function APICPictureTypeLookup($index) {
	$APICPictureTypeLookup[0x00] = 'Other';
	$APICPictureTypeLookup[0x01] = '32x32 pixels \'file icon\' (PNG only)';
	$APICPictureTypeLookup[0x02] = 'Other file icon';
	$APICPictureTypeLookup[0x03] = 'Cover (front)';
	$APICPictureTypeLookup[0x04] = 'Cover (back)';
	$APICPictureTypeLookup[0x05] = 'Leaflet page';
	$APICPictureTypeLookup[0x06] = 'Media (e.g. label side of CD)';
	$APICPictureTypeLookup[0x07] = 'Lead artist/lead performer/soloist';
	$APICPictureTypeLookup[0x08] = 'Artist/performer';
	$APICPictureTypeLookup[0x09] = 'Conductor';
	$APICPictureTypeLookup[0x0A] = 'Band/Orchestra';
	$APICPictureTypeLookup[0x0B] = 'Composer';
	$APICPictureTypeLookup[0x0C] = 'Lyricist/text writer';
	$APICPictureTypeLookup[0x0D] = 'Recording Location';
	$APICPictureTypeLookup[0x0E] = 'During recording';
	$APICPictureTypeLookup[0x0F] = 'During performance';
	$APICPictureTypeLookup[0x10] = 'Movie/video screen capture';
	$APICPictureTypeLookup[0x11] = 'A bright coloured fish';
	$APICPictureTypeLookup[0x12] = 'Illustration';
	$APICPictureTypeLookup[0x13] = 'Band/artist logotype';
	$APICPictureTypeLookup[0x14] = 'Publisher/Studio logotype';

	return (isset($APICPictureTypeLookup["$index"]) ? $APICPictureTypeLookup["$index"] : '');
}

function COMRReceivedAsLookup($index) {
	$COMRReceivedAsLookup[0x00] = 'Other';
	$COMRReceivedAsLookup[0x01] = 'Standard CD album with other songs';
	$COMRReceivedAsLookup[0x02] = 'Compressed audio on CD';
	$COMRReceivedAsLookup[0x03] = 'File over the Internet';
	$COMRReceivedAsLookup[0x04] = 'Stream over the Internet';
	$COMRReceivedAsLookup[0x05] = 'As note sheets';
	$COMRReceivedAsLookup[0x06] = 'As note sheets in a book with other sheets';
	$COMRReceivedAsLookup[0x07] = 'Music on other media';
	$COMRReceivedAsLookup[0x08] = 'Non-musical merchandise';

	return (isset($COMRReceivedAsLookup["$index"]) ? $COMRReceivedAsLookup["$index"] : '');
}

function RVA2ChannelTypeLookup($index) {
	$RVA2ChannelTypeLookup[0x00] = 'Other';
	$RVA2ChannelTypeLookup[0x01] = 'Master volume';
	$RVA2ChannelTypeLookup[0x02] = 'Front right';
	$RVA2ChannelTypeLookup[0x03] = 'Front left';
	$RVA2ChannelTypeLookup[0x04] = 'Back right';
	$RVA2ChannelTypeLookup[0x05] = 'Back left';
	$RVA2ChannelTypeLookup[0x06] = 'Front centre';
	$RVA2ChannelTypeLookup[0x07] = 'Back centre';
	$RVA2ChannelTypeLookup[0x08] = 'Subwoofer';

	return (isset($RVA2ChannelTypeLookup["$index"]) ? $RVA2ChannelTypeLookup["$index"] : '');
}

function FrameNameLongLookup($framename) {
	$FrameNameLongLookup['AENC'] = 'Audio encryption';
	$FrameNameLongLookup['APIC'] = 'Attached picture';
	$FrameNameLongLookup['ASPI'] = 'Audio seek point index';
	$FrameNameLongLookup['BUF']  = 'Recommended buffer size';
	$FrameNameLongLookup['CNT']  = 'Play counter';
	$FrameNameLongLookup['COM']  = 'Comments';
	$FrameNameLongLookup['COMM'] = 'Comments';
	$FrameNameLongLookup['COMR'] = 'Commercial frame';
	$FrameNameLongLookup['CRA']  = 'Audio encryption';
	$FrameNameLongLookup['CRM']  = 'Encrypted meta frame';
	$FrameNameLongLookup['ENCR'] = 'Encryption method registration';
	$FrameNameLongLookup['EQU']  = 'Equalization';
	$FrameNameLongLookup['EQU2'] = 'Equalisation (2)';
	$FrameNameLongLookup['EQUA'] = 'Equalization';
	$FrameNameLongLookup['ETC']  = 'Event timing codes';
	$FrameNameLongLookup['ETCO'] = 'Event timing codes';
	$FrameNameLongLookup['GEO']  = 'General encapsulated object';
	$FrameNameLongLookup['GEOB'] = 'General encapsulated object';
	$FrameNameLongLookup['GRID'] = 'Group identification registration';
	$FrameNameLongLookup['IPL']  = 'Involved people list';
	$FrameNameLongLookup['IPLS'] = 'Involved people list';
	$FrameNameLongLookup['LINK'] = 'Linked information';
	$FrameNameLongLookup['LNK']  = 'Linked information';
	$FrameNameLongLookup['MCDI'] = 'Music CD identifier';
	$FrameNameLongLookup['MCI']  = 'Music CD Identifier';
	$FrameNameLongLookup['MLL']  = 'MPEG location lookup table';
	$FrameNameLongLookup['MLLT'] = 'MPEG location lookup table';
	$FrameNameLongLookup['OWNE'] = 'Ownership frame';
	$FrameNameLongLookup['PCNT'] = 'Play counter';
	$FrameNameLongLookup['PIC']  = 'Attached picture';
	$FrameNameLongLookup['POP']  = 'Popularimeter';
	$FrameNameLongLookup['POPM'] = 'Popularimeter';
	$FrameNameLongLookup['POSS'] = 'Position synchronisation frame';
	$FrameNameLongLookup['PRIV'] = 'Private frame';
	$FrameNameLongLookup['RBUF'] = 'Recommended buffer size';
	$FrameNameLongLookup['REV']  = 'Reverb';
	$FrameNameLongLookup['RVA']  = 'Relative volume adjustment';
	$FrameNameLongLookup['RVA2'] = 'Relative volume adjustment (2)';
	$FrameNameLongLookup['RVAD'] = 'Relative volume adjustment';
	$FrameNameLongLookup['RVRB'] = 'Reverb';
	$FrameNameLongLookup['SEEK'] = 'Seek frame';
	$FrameNameLongLookup['SIGN'] = 'Signature frame';
	$FrameNameLongLookup['SLT']  = 'Synchronized lyric/text';
	$FrameNameLongLookup['STC']  = 'Synced tempo codes';
	$FrameNameLongLookup['SYLT'] = 'Synchronised lyric/text';
	$FrameNameLongLookup['SYTC'] = 'Synchronised tempo codes';
	$FrameNameLongLookup['TAL']  = 'Album/Movie/Show title';
	$FrameNameLongLookup['TALB'] = 'Album/Movie/Show title';
	$FrameNameLongLookup['TBP']  = 'BPM (Beats Per Minute)';
	$FrameNameLongLookup['TBPM'] = 'BPM (beats per minute)';
	$FrameNameLongLookup['TCM']  = 'Composer';
	$FrameNameLongLookup['TCO']  = 'Content type';
	$FrameNameLongLookup['TCOM'] = 'Composer';
	$FrameNameLongLookup['TCON'] = 'Content type';
	$FrameNameLongLookup['TCOP'] = 'Copyright message';
	$FrameNameLongLookup['TCR']  = 'Copyright message';
	$FrameNameLongLookup['TDA']  = 'Date';
	$FrameNameLongLookup['TDAT'] = 'Date';
	$FrameNameLongLookup['TDEN'] = 'Encoding time';
	$FrameNameLongLookup['TDLY'] = 'Playlist delay';
	$FrameNameLongLookup['TDOR'] = 'Original release time';
	$FrameNameLongLookup['TDRC'] = 'Recording time';
	$FrameNameLongLookup['TDRL'] = 'Release time';
	$FrameNameLongLookup['TDTG'] = 'Tagging time';
	$FrameNameLongLookup['TDY']  = 'Playlist delay';
	$FrameNameLongLookup['TEN']  = 'Encoded by';
	$FrameNameLongLookup['TENC'] = 'Encoded by';
	$FrameNameLongLookup['TEXT'] = 'Lyricist/Text writer';
	$FrameNameLongLookup['TFLT'] = 'File type';
	$FrameNameLongLookup['TFT']  = 'File type';
	$FrameNameLongLookup['TIM']  = 'Time';
	$FrameNameLongLookup['TIME'] = 'Time';
	$FrameNameLongLookup['TIPL'] = 'Involved people list';
	$FrameNameLongLookup['TIT1'] = 'Content group description';
	$FrameNameLongLookup['TIT2'] = 'Title/songname/content description';
	$FrameNameLongLookup['TIT3'] = 'Subtitle/Description refinement';
	$FrameNameLongLookup['TKE']  = 'Initial key';
	$FrameNameLongLookup['TKEY'] = 'Initial key';
	$FrameNameLongLookup['TLA']  = 'Language(s)';
	$FrameNameLongLookup['TLAN'] = 'Language(s)';
	$FrameNameLongLookup['TLE']  = 'Length';
	$FrameNameLongLookup['TLEN'] = 'Length';
	$FrameNameLongLookup['TMCL'] = 'Musician credits list';
	$FrameNameLongLookup['TMED'] = 'Media type';
	$FrameNameLongLookup['TMOO'] = 'Mood';
	$FrameNameLongLookup['TMT']  = 'Media type';
	$FrameNameLongLookup['TOA']  = 'Original artist(s)/performer(s)';
	$FrameNameLongLookup['TOAL'] = 'Original album/movie/show title';
	$FrameNameLongLookup['TOF']  = 'Original filename';
	$FrameNameLongLookup['TOFN'] = 'Original filename';
	$FrameNameLongLookup['TOL']  = 'Original Lyricist(s)/text writer(s)';
	$FrameNameLongLookup['TOLY'] = 'Original lyricist(s)/text writer(s)';
	$FrameNameLongLookup['TOPE'] = 'Original artist(s)/performer(s)';
	$FrameNameLongLookup['TOR']  = 'Original release year';
	$FrameNameLongLookup['TORY'] = 'Original release year';
	$FrameNameLongLookup['TOT']  = 'Original album/Movie/Show title';
	$FrameNameLongLookup['TOWN'] = 'File owner/licensee';
	$FrameNameLongLookup['TP1']  = 'Lead artist(s)/Lead performer(s)/Soloist(s)/Performing group';
	$FrameNameLongLookup['TP2']  = 'Band/Orchestra/Accompaniment';
	$FrameNameLongLookup['TP3']  = 'Conductor/Performer refinement';
	$FrameNameLongLookup['TP4']  = 'Interpreted, remixed, or otherwise modified by';
	$FrameNameLongLookup['TPA']  = 'Part of a set';
	$FrameNameLongLookup['TPB']  = 'Publisher';
	$FrameNameLongLookup['TPE1'] = 'Lead performer(s)/Soloist(s)';
	$FrameNameLongLookup['TPE2'] = 'Band/orchestra/accompaniment';
	$FrameNameLongLookup['TPE3'] = 'Conductor/performer refinement';
	$FrameNameLongLookup['TPE4'] = 'Interpreted, remixed, or otherwise modified by';
	$FrameNameLongLookup['TPOS'] = 'Part of a set';
	$FrameNameLongLookup['TPRO'] = 'Produced notice';
	$FrameNameLongLookup['TPUB'] = 'Publisher';
	$FrameNameLongLookup['TRC']  = 'ISRC (International Standard Recording Code)';
	$FrameNameLongLookup['TRCK'] = 'Track number/Position in set';
	$FrameNameLongLookup['TRD']  = 'Recording dates';
	$FrameNameLongLookup['TRDA'] = 'Recording dates';
	$FrameNameLongLookup['TRK']  = 'Track number/Position in set';
	$FrameNameLongLookup['TRSN'] = 'Internet radio station name';
	$FrameNameLongLookup['TRSO'] = 'Internet radio station owner';
	$FrameNameLongLookup['TSI']  = 'Size';
	$FrameNameLongLookup['TSIZ'] = 'Size';
	$FrameNameLongLookup['TSOA'] = 'Album sort order';
	$FrameNameLongLookup['TSOP'] = 'Performer sort order';
	$FrameNameLongLookup['TSOT'] = 'Title sort order';
	$FrameNameLongLookup['TSRC'] = 'ISRC (international standard recording code)';
	$FrameNameLongLookup['TSS']  = 'Software/hardware and settings used for encoding';
	$FrameNameLongLookup['TSSE'] = 'Software/Hardware and settings used for encoding';
	$FrameNameLongLookup['TSST'] = 'Set subtitle';
	$FrameNameLongLookup['TT1']  = 'Content group description';
	$FrameNameLongLookup['TT2']  = 'Title/Songname/Content description';
	$FrameNameLongLookup['TT3']  = 'Subtitle/Description refinement';
	$FrameNameLongLookup['TXT']  = 'Lyricist/text writer';
	$FrameNameLongLookup['TXX']  = 'User defined text information frame';
	$FrameNameLongLookup['TXXX'] = 'User defined text information frame';
	$FrameNameLongLookup['TYE']  = 'Year';
	$FrameNameLongLookup['TYER'] = 'Year';
	$FrameNameLongLookup['UFI']  = 'Unique file identifier';
	$FrameNameLongLookup['UFID'] = 'Unique file identifier';
	$FrameNameLongLookup['ULT']  = 'Unsychronized lyric/text transcription';
	$FrameNameLongLookup['USER'] = 'Terms of use';
	$FrameNameLongLookup['USLT'] = 'Unsynchronised lyric/text transcription';
	$FrameNameLongLookup['WAF']  = 'Official audio file webpage';
	$FrameNameLongLookup['WAR']  = 'Official artist/performer webpage';
	$FrameNameLongLookup['WAS']  = 'Official audio source webpage';
	$FrameNameLongLookup['WCM']  = 'Commercial information';
	$FrameNameLongLookup['WCOM'] = 'Commercial information';
	$FrameNameLongLookup['WCOP'] = 'Copyright/Legal information';
	$FrameNameLongLookup['WCP']  = 'Copyright/Legal information';
	$FrameNameLongLookup['WOAF'] = 'Official audio file webpage';
	$FrameNameLongLookup['WOAR'] = 'Official artist/performer webpage';
	$FrameNameLongLookup['WOAS'] = 'Official audio source webpage';
	$FrameNameLongLookup['WORS'] = 'Official Internet radio station homepage';
	$FrameNameLongLookup['WPAY'] = 'Payment';
	$FrameNameLongLookup['WPB']  = 'Publishers official webpage';
	$FrameNameLongLookup['WPUB'] = 'Publishers official webpage';
	$FrameNameLongLookup['WXX']  = 'User defined URL link frame';
	$FrameNameLongLookup['WXXX'] = 'User defined URL link frame';

	$FrameNameLongLookup['TFEA'] = 'Featured Artist';        // from Helium2 [www.helium2.com]
	$FrameNameLongLookup['TSTU'] = 'Recording Studio';       // from Helium2 [www.helium2.com]
	$FrameNameLongLookup['rgad'] = 'Replay Gain Adjustment'; // from http://privatewww.essex.ac.uk/~djmrob/replaygain/file_format_id3v2.html

	return (isset($FrameNameLongLookup["$framename"]) ? $FrameNameLongLookup["$framename"] : '');
}

function RGADnameLookup($namecode) {
	$RGADname[bindec('000')] = 'not set';
	$RGADname[bindec('001')] = 'Radio Gain Adjustment';
	$RGADname[bindec('010')] = 'Audiophile Gain Adjustment';
	
	return (isset($RGADname["$namecode"]) ? $RGADname["$namecode"] : '');
}

function RGADoriginatorLookup($originatorcode) {
	$RGADoriginator[bindec('000')] = 'unspecified';
	$RGADoriginator[bindec('001')] = 'pre-set by artist/producer/mastering engineer';
	$RGADoriginator[bindec('010')] = 'set by user';
	$RGADoriginator[bindec('011')] = 'determined automatically';
	
	return (isset($RGADoriginator["$originatorcode"]) ? $RGADoriginator["$originatorcode"] : '');
}

function RGADadjustmentLookup($rawadjustment, $signbit) {
	$adjustment = $rawadjustment / 10;
	if ($signbit == 1) {
		$adjustment *= -1;
	}
	return (float) $adjustment;
}

function RGADgainString($namecode, $originatorcode, $replaygain) {
	if ($replaygain < 0) {
		$signbit = '1';
	} else {
		$signbit = '0';
	}
	$storedreplaygain = round($replaygain * 10);
	$gainstring  = str_pad(decbin($namecode), 3, '0', STR_PAD_LEFT);
	$gainstring .= str_pad(decbin($originatorcode), 3, '0', STR_PAD_LEFT);
	$gainstring .= $signbit;
	$gainstring .= str_pad(decbin(round($replaygain * 10)), 9, '0', STR_PAD_LEFT);
	
	return $gainstring;
}

function RIFFwFormatTagLookup($wFormatTag) {
	$RIFFwFormatTagLookup[0x0000] = 'Unknown';
	$RIFFwFormatTagLookup[0x0001] = 'Microsoft Pulse Code Modulation (PCM)';
	$RIFFwFormatTagLookup[0x0002] = 'Microsoft ADPCM';
	$RIFFwFormatTagLookup[0x0005] = 'IBM CVSD';
	$RIFFwFormatTagLookup[0x0006] = 'Microsoft A-Law';
	$RIFFwFormatTagLookup[0x0007] = 'Microsoft mu-Law';
	$RIFFwFormatTagLookup[0x0010] = 'OKI ADPCM';
	$RIFFwFormatTagLookup[0x0011] = 'Intel DVI/IMA ADPCM';
	$RIFFwFormatTagLookup[0x0012] = 'Videologic Mediaspace ADPCM';
	$RIFFwFormatTagLookup[0x0013] = 'Sierra Semiconductor ADPCM';
	$RIFFwFormatTagLookup[0x0014] = 'Antex Electronics G723 ADPCM';
	$RIFFwFormatTagLookup[0x0015] = 'DSP Solutions DigiSTD';
	$RIFFwFormatTagLookup[0x0016] = 'DSP Solutions DigiFIX';
	$RIFFwFormatTagLookup[0x0017] = 'Dialogic OKI ADPCM';
	$RIFFwFormatTagLookup[0x0020] = 'Yamaha ADPCM';
	$RIFFwFormatTagLookup[0x0021] = 'Speech Compression Sonarc';
	$RIFFwFormatTagLookup[0x0022] = 'DSP Group Truespeech';
	$RIFFwFormatTagLookup[0x0023] = 'Echo Speech EchoSC1';
	$RIFFwFormatTagLookup[0x0024] = 'Audiofile AF36';
	$RIFFwFormatTagLookup[0x0025] = 'Audio Processing Technology APTX';
	$RIFFwFormatTagLookup[0x0026] = 'Audiofile AF10';
	$RIFFwFormatTagLookup[0x0030] = 'Dolby AC2';
	$RIFFwFormatTagLookup[0x0031] = 'Microsoft GSM 6.10';
	$RIFFwFormatTagLookup[0x0033] = 'Antex Electronics ADPCME';
	$RIFFwFormatTagLookup[0x0034] = 'Control Resources VQLPC';
	$RIFFwFormatTagLookup[0x0035] = 'DSP Solutions DigiREAL';
	$RIFFwFormatTagLookup[0x0036] = 'DSP Solutions DigiADPCM';
	$RIFFwFormatTagLookup[0x0037] = 'Control Resources CR10';
	$RIFFwFormatTagLookup[0x0038] = 'Natural MicroSystems VBXADPCM';
	$RIFFwFormatTagLookup[0x0039] = 'Crystal Semiconductor IMA ADPCM';
	$RIFFwFormatTagLookup[0x0040] = 'Antex Electronics GS721 ADPCM';
	$RIFFwFormatTagLookup[0x0050] = 'Microsoft MPEG';
	$RIFFwFormatTagLookup[0x0055] = 'Microsoft ACM: LAME MP3 encoder (ACM)';
	$RIFFwFormatTagLookup[0x0101] = 'IBM mu-law';
	$RIFFwFormatTagLookup[0x0102] = 'IBM A-law';
	$RIFFwFormatTagLookup[0x0103] = 'IBM AVC Adaptive Differential Pulse Code Modulation (ADPCM)';
	$RIFFwFormatTagLookup[0x0161] = 'Microsoft ACM: DivX ;-) Audio';
	$RIFFwFormatTagLookup[0x0200] = 'Creative Labs ADPCM';
	$RIFFwFormatTagLookup[0x0202] = 'Creative Labs Fastspeech8';
	$RIFFwFormatTagLookup[0x0203] = 'Creative Labs Fastspeech10';
	$RIFFwFormatTagLookup[0x0300] = 'Fujitsu FM Towns Snd';
	$RIFFwFormatTagLookup[0x1000] = 'Olivetti GSM';
	$RIFFwFormatTagLookup[0x1001] = 'Olivetti ADPCM';
	$RIFFwFormatTagLookup[0x1002] = 'Olivetti CELP';
	$RIFFwFormatTagLookup[0x1003] = 'Olivetti SBC';
	$RIFFwFormatTagLookup[0x1004] = 'Olivetti OPR';
	$RIFFwFormatTagLookup[0xFFFF] = 'development';

	return (isset($RIFFwFormatTagLookup["$wFormatTag"]) ? $RIFFwFormatTagLookup["$wFormatTag"] : '');
}

function RIFFfourccLookup($fourcc) {
	$RIFFfourccLookup['3IV1'] = '3ivx v1';
	$RIFFfourccLookup['3IV2'] = '3ivx v2';
	$RIFFfourccLookup['AASC'] = 'Autodesk Animator';
	$RIFFfourccLookup['ABYR'] = 'Kensington ?ABYR?';
	$RIFFfourccLookup['AEMI'] = 'Array VideoONE MPEG1-I Capture';
	$RIFFfourccLookup['AFLC'] = 'Autodesk Animator FLC';
	$RIFFfourccLookup['AFLI'] = 'Autodesk Animator FLI';
	$RIFFfourccLookup['AMPG'] = 'Array VideoONE MPEG';
	$RIFFfourccLookup['ANIM'] = 'Intel RDX (ANIM)';
	$RIFFfourccLookup['AP41'] = 'AngelPotion Definitive';
	$RIFFfourccLookup['ASV1'] = 'Asus Video v1';
	$RIFFfourccLookup['ASV2'] = 'Asus Video v2';
	$RIFFfourccLookup['ASVX'] = 'Asus Video 2.0 (audio)';
	$RIFFfourccLookup['AUR2'] = 'Aura 2 Codec - YUV 4:2:2';
	$RIFFfourccLookup['AURA'] = 'Aura 1 Codec - YUV 4:1:1';
	$RIFFfourccLookup['BINK'] = 'RAD Game Tools Bink Video';
	$RIFFfourccLookup['BT20'] = 'Conexant Prosumer Video';
	$RIFFfourccLookup['BTCV'] = 'Conexant Composite Video Codec';
	$RIFFfourccLookup['BW10'] = 'Data Translation Broadway MPEG Capture';
	$RIFFfourccLookup['CC12'] = 'Intel YUV12';
	$RIFFfourccLookup['CDVC'] = 'Canopus DV';
	$RIFFfourccLookup['CFCC'] = 'Digital Processing Systems DPS Perception';
	$RIFFfourccLookup['CGDI'] = 'Microsoft Office 97 Camcorder Video';
	$RIFFfourccLookup['CHAM'] = 'Winnov Caviara Champagne';
	$RIFFfourccLookup['CJPG'] = 'Creative WebCam JPEG';
	$RIFFfourccLookup['CPLA'] = 'Weitek YUV 4:2:0';
	$RIFFfourccLookup['CRAM'] = 'Microsoft Video 1 (CRAM)';
	$RIFFfourccLookup['CVID'] = 'Radius Cinepak';
	$RIFFfourccLookup['CWLT'] = '?CWLT?';
	$RIFFfourccLookup['CYUV'] = 'Creative YUV';
	$RIFFfourccLookup['CYUY'] = 'ATI YUV';
	$RIFFfourccLookup['DIV3'] = 'DivX v3 MPEG-4 Low-Motion';
	$RIFFfourccLookup['DIV4'] = 'DivX v3 MPEG-4 Fast-Motion';
	$RIFFfourccLookup['DIV5'] = '?DIV5?';
	$RIFFfourccLookup['DIVX'] = 'DivX v4.0+';
	$RIFFfourccLookup['divx'] = 'DivX';
	$RIFFfourccLookup['DMB1'] = 'Matrox Rainbow Runner hardware MJPEG';
	$RIFFfourccLookup['DMB2'] = 'Paradigm MJPEG';
	$RIFFfourccLookup['DSVD'] = '?DSVD?';
	$RIFFfourccLookup['DUCK'] = 'Duck TrueMotion S';
	$RIFFfourccLookup['DVAN'] = '?DVAN?';
	$RIFFfourccLookup['DVE2'] = 'InSoft DVE-2 Videoconferencing';
	$RIFFfourccLookup['DVSD'] = 'miroVideo DV300 software DV';
	$RIFFfourccLookup['dvsd'] = 'Pinnacle miroVideo DV300 software DV';
	$RIFFfourccLookup['DVX1'] = 'DVX1000SP Video Decoder';
	$RIFFfourccLookup['DVX2'] = 'DVX2000S Video Decoder';
	$RIFFfourccLookup['DVX3'] = 'DVX3000S Video Decoder';
	$RIFFfourccLookup['DXT1'] = 'Microsoft DirectX Compressed Texture (DXT1)';
	$RIFFfourccLookup['DXT2'] = 'Microsoft DirectX Compressed Texture (DXT2)';
	$RIFFfourccLookup['DXT3'] = 'Microsoft DirectX Compressed Texture (DXT3)';
	$RIFFfourccLookup['DXT4'] = 'Microsoft DirectX Compressed Texture (DXT4)';
	$RIFFfourccLookup['DXT5'] = 'Microsoft DirectX Compressed Texture (DXT5)';
	$RIFFfourccLookup['DXTC'] = 'Microsoft DirectX Compressed Texture (DXTC)';
	$RIFFfourccLookup['EKQ0'] = 'Elsa ?EKQ0?';
	$RIFFfourccLookup['ELK0'] = 'Elsa ?ELK0?';
	$RIFFfourccLookup['ESCP'] = 'Eidos Escape';
	$RIFFfourccLookup['ETV1'] = 'eTreppid Video ETV1';
	$RIFFfourccLookup['ETV2'] = 'eTreppid Video ETV2';
	$RIFFfourccLookup['ETVC'] = 'eTreppid Video ETVC';
	$RIFFfourccLookup['FLJP'] = 'D-Vision Field Encoded Motion JPEG';
	$RIFFfourccLookup['FRWA'] = 'SoftLab-Nsk Forward Motion JPEG w/ alpha channel';
	$RIFFfourccLookup['FRWD'] = 'SoftLab-Nsk Forward Motion JPEG';
	$RIFFfourccLookup['GLZW'] = 'Motion LZW (gabest@freemail.hu)';
	$RIFFfourccLookup['GPEG'] = 'Motion JPEG (gabest@freemail.hu)';
	$RIFFfourccLookup['GWLT'] = 'Microsoft ?GWLT?';
	$RIFFfourccLookup['H260'] = 'Intel ITU H.260 Videoconferencing';
	$RIFFfourccLookup['H261'] = 'Intel ITU H.261 Videoconferencing';
	$RIFFfourccLookup['H262'] = 'Intel ITU H.262 Videoconferencing';
	$RIFFfourccLookup['H263'] = 'Intel ITU H.263 Videoconferencing';
	$RIFFfourccLookup['H264'] = 'Intel ITU H.264 Videoconferencing';
	$RIFFfourccLookup['H265'] = 'Intel ITU H.265 Videoconferencing';
	$RIFFfourccLookup['H266'] = 'Intel ITU H.266 Videoconferencing';
	$RIFFfourccLookup['H267'] = 'Intel ITU H.267 Videoconferencing';
	$RIFFfourccLookup['H268'] = 'Intel ITU H.268 Videoconferencing';
	$RIFFfourccLookup['H269'] = 'Intel ITU H.269 Videoconferencing';
	$RIFFfourccLookup['HFYU'] = 'Huffman Lossless Codec';
	$RIFFfourccLookup['HMCR'] = 'Rendition Motion Compensation Format (HMCR)';
	$RIFFfourccLookup['HMRR'] = 'Rendition Motion Compensation Format (HMRR)';
	$RIFFfourccLookup['i263'] = 'Intel ITU H.263 Videoconferencing (i263)';
	$RIFFfourccLookup['IAN '] = 'Intel Indeo 4 Codec';
	$RIFFfourccLookup['ICLB'] = 'InSoft CellB Videoconferencing';
	$RIFFfourccLookup['IGOR'] = 'Power DVD';
	$RIFFfourccLookup['IJPG'] = 'Intergraph JPEG';
	$RIFFfourccLookup['ILVC'] = 'Intel Layered Video';
	$RIFFfourccLookup['ILVR'] = 'ITU H.263+ Codec';
	$RIFFfourccLookup['IPDV'] = 'I-O Data Device Giga AVI DV Codec';
	$RIFFfourccLookup['IR21'] = 'Intel Indeo 2.1';
	$RIFFfourccLookup['IV30'] = 'Ligos Indeo 3.0';
	$RIFFfourccLookup['IV31'] = 'Ligos Indeo 3.1';
	$RIFFfourccLookup['IV32'] = 'Ligos Indeo 3.2';
	$RIFFfourccLookup['IV33'] = 'Ligos Indeo 3.3';
	$RIFFfourccLookup['IV34'] = 'Ligos Indeo 3.4';
	$RIFFfourccLookup['IV35'] = 'Ligos Indeo 3.5';
	$RIFFfourccLookup['IV36'] = 'Ligos Indeo 3.6';
	$RIFFfourccLookup['IV37'] = 'Ligos Indeo 3.7';
	$RIFFfourccLookup['IV38'] = 'Ligos Indeo 3.8';
	$RIFFfourccLookup['IV39'] = 'Ligos Indeo 3.9';
	$RIFFfourccLookup['IV40'] = 'Ligos Indeo Interactive 4.0';
	$RIFFfourccLookup['IV41'] = 'Ligos Indeo Interactive 4.1';
	$RIFFfourccLookup['IV42'] = 'Ligos Indeo Interactive 4.2';
	$RIFFfourccLookup['IV43'] = 'Ligos Indeo Interactive 4.3';
	$RIFFfourccLookup['IV44'] = 'Ligos Indeo Interactive 4.4';
	$RIFFfourccLookup['IV45'] = 'Ligos Indeo Interactive 4.5';
	$RIFFfourccLookup['IV46'] = 'Ligos Indeo Interactive 4.6';
	$RIFFfourccLookup['IV47'] = 'Ligos Indeo Interactive 4.7';
	$RIFFfourccLookup['IV48'] = 'Ligos Indeo Interactive 4.8';
	$RIFFfourccLookup['IV49'] = 'Ligos Indeo Interactive 4.9';
	$RIFFfourccLookup['IV50'] = 'Ligos Indeo Interactive 5.0';
	$RIFFfourccLookup['JBYR'] = 'Kensington ?JBYR?';
	$RIFFfourccLookup['JPGL'] = 'Webcam JPEG Light?';
	$RIFFfourccLookup['KMVC'] = 'Karl Morton\'s Video Codec (presumably)';
	$RIFFfourccLookup['LEAD'] = 'LEAD Video Codec';
	$RIFFfourccLookup['Ljpg'] = 'LEAD MJPEG Codec';
	$RIFFfourccLookup['M261'] = 'Microsoft H.261';
	$RIFFfourccLookup['M263'] = 'Microsoft H.263';
	$RIFFfourccLookup['MC12'] = 'ATI Motion Compensation Format';
	$RIFFfourccLookup['MCAM'] = 'ATI Motion Compensation Format';
	$RIFFfourccLookup['mJPG'] = 'IBM Motion JPEG w/ Huffman Tables';
	$RIFFfourccLookup['MJPG'] = 'Motion JPEG';
	$RIFFfourccLookup['MP42'] = 'Microsoft MPEG-4 (low-motion)';
	$RIFFfourccLookup['MP43'] = 'Microsoft MPEG-4 (fast-motion)';
	$RIFFfourccLookup['MP4S'] = 'Microsoft MPEG-4 (MP4S)';
	$RIFFfourccLookup['MPEG'] = 'MPEG-1';
	$RIFFfourccLookup['MPG4'] = 'Microsoft MPEG-4 Video High Speed Compressor';
	$RIFFfourccLookup['MPGI'] = 'Sigma Designs MPEG';
	$RIFFfourccLookup['MRCA'] = 'FAST Multimedia Mrcodec';
	$RIFFfourccLookup['MRLE'] = 'Microsoft RLE';
	$RIFFfourccLookup['MSVC'] = 'Microsoft Video 1 (MSVC)';
	$RIFFfourccLookup['MTX1'] = 'Matrox ?MTX1?';
	$RIFFfourccLookup['MTX2'] = 'Matrox ?MTX2?';
	$RIFFfourccLookup['MTX3'] = 'Matrox ?MTX3?';
	$RIFFfourccLookup['MTX4'] = 'Matrox ?MTX4?';
	$RIFFfourccLookup['MTX5'] = 'Matrox ?MTX5?';
	$RIFFfourccLookup['MTX6'] = 'Matrox ?MTX6?';
	$RIFFfourccLookup['MTX7'] = 'Matrox ?MTX7?';
	$RIFFfourccLookup['MTX8'] = 'Matrox ?MTX8?';
	$RIFFfourccLookup['MTX9'] = 'Matrox ?MTX9?';
	$RIFFfourccLookup['MV12'] = '?MV12?';
	$RIFFfourccLookup['MWV1'] = 'Aware Motion Wavelets';
	$RIFFfourccLookup['nAVI'] = '?nAVI?';
	$RIFFfourccLookup['NTN1'] = 'Nogatech Video Compression 1';
	$RIFFfourccLookup['NVS0'] = 'nVidia GeForce Texture (NVS0)';
	$RIFFfourccLookup['NVS1'] = 'nVidia GeForce Texture (NVS1)';
	$RIFFfourccLookup['NVS2'] = 'nVidia GeForce Texture (NVS2)';
	$RIFFfourccLookup['NVS3'] = 'nVidia GeForce Texture (NVS3)';
	$RIFFfourccLookup['NVS4'] = 'nVidia GeForce Texture (NVS4)';
	$RIFFfourccLookup['NVS5'] = 'nVidia GeForce Texture (NVS5)';
	$RIFFfourccLookup['NVT0'] = 'nVidia GeForce Texture (NVT0)';
	$RIFFfourccLookup['NVT1'] = 'nVidia GeForce Texture (NVT1)';
	$RIFFfourccLookup['NVT2'] = 'nVidia GeForce Texture (NVT2)';
	$RIFFfourccLookup['NVT3'] = 'nVidia GeForce Texture (NVT3)';
	$RIFFfourccLookup['NVT4'] = 'nVidia GeForce Texture (NVT4)';
	$RIFFfourccLookup['NVT5'] = 'nVidia GeForce Texture (NVT5)';
	$RIFFfourccLookup['PDVC'] = 'I-O Data Device Digital Video Capture DV codec';
	$RIFFfourccLookup['PGVV'] = 'Radius Video Vision';
	$RIFFfourccLookup['PIM1'] = 'Pegasus Imaging ?PIM1?';
	$RIFFfourccLookup['PIM2'] = 'Pegasus Imaging ?PIM2?';
	$RIFFfourccLookup['PIMJ'] = 'Pegasus Imaging Lossless JPEG';
	$RIFFfourccLookup['PVEZ'] = 'Horizons Technology PowerEZ';
	$RIFFfourccLookup['PVMM'] = 'PacketVideo Corporation MPEG-4';
	$RIFFfourccLookup['PVW2'] = 'Pegasus Imaging Wavelet Compression';
	$RIFFfourccLookup['QPEG'] = 'Q-Team QPEG 1.0';
	$RIFFfourccLookup['qpeq'] = 'Q-Team QPEG 1.1';
	$RIFFfourccLookup['RGBT'] = 'Computer Concepts 32-bit support';
	$RIFFfourccLookup['RLE '] = 'Microsoft Run Length Encoder';
	$RIFFfourccLookup['RT21'] = 'Intel Real Time Video 2.1';
	$RIFFfourccLookup['rv20'] = 'RealVideo G2';
	$RIFFfourccLookup['rv30'] = 'RealVideo 8';
	$RIFFfourccLookup['RVX '] = 'Intel RDX (RVX )';
	$RIFFfourccLookup['s422'] = 'Tekram VideoCap C210 YUV 4:2:2';
	$RIFFfourccLookup['SDCC'] = 'Sun Communication Digital Camera Codec';
	$RIFFfourccLookup['SFMC'] = 'CrystalNet Surface Fitting Method';
	$RIFFfourccLookup['SMSC'] = 'Radius ?SMSC?';
	$RIFFfourccLookup['SMSD'] = 'Radius ?SMSD?';
	$RIFFfourccLookup['smsv'] = 'WorldConnect Wavelet Video';
	$RIFFfourccLookup['SPIG'] = 'Radius Spigot';
	$RIFFfourccLookup['SQZ2'] = 'Microsoft VXTreme Video Codec V2';
	$RIFFfourccLookup['STVA'] = 'ST CMOS Imager Data (Bayer)';
	$RIFFfourccLookup['STVB'] = 'ST CMOS Imager Data (Nudged Bayer)';
	$RIFFfourccLookup['STVC'] = 'ST CMOS Imager Data (Bunched)';
	$RIFFfourccLookup['STVX'] = 'ST CMOS Imager Data (Extended CODEC Data Format)';
	$RIFFfourccLookup['STVY'] = 'ST CMOS Imager Data (Extended CODEC Data Format with Correction Data)';
	$RIFFfourccLookup['SV10'] = 'Sorenson Video R1';
	$RIFFfourccLookup['SVQ1'] = 'Sorenson Video';
	$RIFFfourccLookup['TLMS'] = 'TeraLogic Motion Intraframe Codec (TLMS)';
	$RIFFfourccLookup['TLST'] = 'TeraLogic Motion Intraframe Codec (TLST)';
	$RIFFfourccLookup['TM20'] = 'Duck TrueMotion 2.0';
	$RIFFfourccLookup['TM2X'] = 'Duck TrueMotion 2X';
	$RIFFfourccLookup['TMIC'] = 'TeraLogic Motion Intraframe Codec (TMIC)';
	$RIFFfourccLookup['TMOT'] = 'Horizons Technology TrueMotion S';
	$RIFFfourccLookup['TR20'] = 'Duck TrueMotion RealTime 2.0';
	$RIFFfourccLookup['TSCC'] = 'TechSmith Screen Capture Codec';
	$RIFFfourccLookup['TV10'] = 'Tecomac Low-Bit Rate Codec';
	$RIFFfourccLookup['TY0N'] = 'Trident ?TY0N?';
	$RIFFfourccLookup['TY2C'] = 'Trident ?TY2C?';
	$RIFFfourccLookup['TY2N'] = 'Trident ?TY2N?';
	$RIFFfourccLookup['UCOD'] = 'eMajix.com ClearVideo';
	$RIFFfourccLookup['ULTI'] = 'IBM Ultimotion';
	$RIFFfourccLookup['V261'] = 'Lucent VX2000S';
	$RIFFfourccLookup['VCR1'] = 'ATI Video Codec 1';
	$RIFFfourccLookup['VCR2'] = 'ATI Video Codec 2';
	$RIFFfourccLookup['VDOM'] = 'VDOnet VDOWave';
	$RIFFfourccLookup['VDOW'] = 'VDOnet VDOLive (H.263)';
	$RIFFfourccLookup['VDTZ'] = 'Darim Vison VideoTizer YUV';
	$RIFFfourccLookup['VGPX'] = 'VGPixel Codec';
	$RIFFfourccLookup['VIDS'] = 'Vitec Multimedia YUV 4:2:2 CCIR 601 for V422';
	$RIFFfourccLookup['VIFP'] = '?VIFP?';
	$RIFFfourccLookup['VIVO'] = 'Vivo H.263 v2.00';
	$RIFFfourccLookup['VIXL'] = 'Miro Video XL';
	$RIFFfourccLookup['VLV1'] = 'VideoLogic ?VLV1?';
	$RIFFfourccLookup['VP30'] = 'On2 VP3.0';
	$RIFFfourccLookup['VP31'] = 'On2 VP3.1';
	$RIFFfourccLookup['VX1K'] = 'VX1000S Video Codec';
	$RIFFfourccLookup['VX2K'] = 'VX2000S Video Codec';
	$RIFFfourccLookup['VXSP'] = 'VX1000SP Video Codec';
	$RIFFfourccLookup['WBVC'] = 'Winbond W9960';
	$RIFFfourccLookup['WHAM'] = 'Microsoft Video 1 (WHAM)';
	$RIFFfourccLookup['WINX'] = 'Winnov Software Compression';
	$RIFFfourccLookup['WJPG'] = 'AverMedia Winbond JPEG';
	$RIFFfourccLookup['WNV1'] = 'Winnov Hardware Compression';
	$RIFFfourccLookup['x263'] = 'Xirlink H.263';
	$RIFFfourccLookup['XLV0'] = 'NetXL Video Decoder';
	$RIFFfourccLookup['XMPG'] = 'Xing MPEG (I-Frame only)';
	$RIFFfourccLookup['XXAN'] = '?XXAN?';
	$RIFFfourccLookup['Y41P'] = 'Brooktree YUV 4:1:1';
	$RIFFfourccLookup['Y8  '] = 'Grayscale video';
	$RIFFfourccLookup['YC12'] = 'Intel YUV 12 codec';
	$RIFFfourccLookup['YUV8'] = 'Winnov Caviar YUV8';
	$RIFFfourccLookup['YUY2'] = 'Uncompressed YUV 4:2:2';
	$RIFFfourccLookup['YUYV'] = 'Canopus YUV';
	$RIFFfourccLookup['ZLIB'] = '?ZLIB?';
	$RIFFfourccLookup['ZPEG'] = 'Metheus Video Zipper';
	
	return (isset($RIFFfourccLookup["$fourcc"]) ? $RIFFfourccLookup["$fourcc"] : '');
}

function MPEGvideoFramerateLookup($rawframerate) {
	$MPEGvideoFramerateLookup = array(0, 23.976, 24, 25, 29.97, 30, 50, 59.94, 60);
	return (isset($MPEGvideoFramerateLookup["$rawframerate"]) ? (float) $MPEGvideoFramerateLookup["$rawframerate"] : (float) 0);
}

function MPEGvideoAspectRatioLookup($rawaspectratio) {
	$MPEGvideoAspectRatioLookup = array(0, 1, 0.6735, 0.7031, 0.7615, 0.8055, 0.8437, 0.8935, 0.9157, 0.9815, 1.0255, 1.0695, 1.0950, 1.1575, 1.2015, 0);
	return (isset($MPEGvideoAspectRatioLookup["$rawaspectratio"]) ? (float) $MPEGvideoAspectRatioLookup["$rawaspectratio"] : (float) 0);
}

function MPEGvideoAspectRatioTextLookup($rawaspectratio) {
	$MPEGvideoAspectRatioTextLookup = array('forbidden', 'square pixels', '0.6735', '16:9, 625 line, PAL', '0.7615', '0.8055', '16:9, 525 line, NTSC', '0.8935', '4:3, 625 line, PAL, CCIR601', '0.9815', '1.0255', '1.0695', '4:3, 525 line, NTSC, CCIR601', '1.1575', '1.2015', 'reserved');
	return (isset($MPEGvideoAspectRatioTextLookup["$rawaspectratio"]) ? $MPEGvideoAspectRatioTextLookup["$rawaspectratio"] : '');
}

function ID3v2FrameFlagsLookupTagAlter($framename, $majorversion) {
	// unfinished
	switch ($framename) {
		case 'RGAD':
			$allow = TRUE;
		default:
			$allow = FALSE;
			break;
	}
	return $allow;
}

function ID3v2FrameFlagsLookupFileAlter($framename, $majorversion) {
	// unfinished
	switch ($framename) {
		case 'RGAD':
			$allow = FALSE;
		default:
			$allow = FALSE;
			break;
	}
	return $allow;
}

function IsValidETCOevent($eventid, $majorversion) {
	if (($eventid < 0) || ($eventid > 0xFF)) {
		// outside range of 1 byte
		return FALSE;
	} else if (($eventid >= 0xF0) && ($eventid <= 0xFC)) {
		// reserved for future use
		return FALSE;
	} else if (($eventid >= 0x17) && ($eventid <= 0xDF)) {
		// reserved for future use
		return FALSE;
	} else if (($eventid >= 0x0E) && ($eventid <= 0x16) && ($majorversion == 2)) {
		// not defined in ID3v2.2
		return FALSE;
	} else if (($eventid >= 0x15) && ($eventid <= 0x16) && ($majorversion == 3)) {
		// not defined in ID3v2.3
		return FALSE;
	} else {
		return TRUE;
	}
}

function IsValidSYLTtype($contenttype, $majorversion) {
	if (($contenttype >= 0) && ($contenttype <= 8) && ($majorversion == 4)) {
		return TRUE;
	} else if (($contenttype >= 0) && ($contenttype <= 6) && ($majorversion == 3)) {
		return TRUE;
	} else {
		return FALSE;
	}
}

function IsValidRVA2channeltype($channeltype, $majorversion) {
	if (($channeltype >= 0) && ($channeltype <= 8) && ($majorversion == 4)) {
		return TRUE;
	} else {
		return FALSE;
	}
}

function IsValidAPICpicturetype($picturetype, $majorversion) {
	if (($picturetype >= 0) && ($picturetype <= 0x14) && ($majorversion >= 2) && ($majorversion <= 4)) {
		return TRUE;
	} else {
		return FALSE;
	}
}

function IsValidAPICimageformat($imageformat, $majorversion) {
	if ($imageformat == '-->') {
		return TRUE;
	} else if ($majorversion == 2) {
		if ((strlen($imageformat) == 3) && ($imageformat == strtoupper($imageformat))) {
			return TRUE;
		}
	} else if (($majorversion == 3) || ($majorversion == 4)) {
		if (IsValidMIMEstring($imageformat)) {
			return TRUE;
		}
	}
	return FALSE;
}

function IsValidCOMRreceivedas($receivedas, $majorversion) {
	if (($majorversion >= 3) && ($receivedas >= 0) && ($receivedas <= 8)) {
		return TRUE;
	}
	return FALSE;
}

function IsValidRGADname($RGADname, $majorversion) {
	if (($RGADname >= bindec('000')) && ($RGADname <= bindec('010'))) {
		return TRUE;
	} else {
		return FALSE;
	}
}

function IsValidRGADoriginator($RGADoriginator, $majorversion) {
	if (($RGADoriginator >= bindec('000')) && ($RGADoriginator <= bindec('011'))) {
		return TRUE;
	} else {
		return FALSE;
	}
}

function IsValidMIMEstring($mimestring) {
	if ((strlen($mimestring) >= 3) && (strpos($mimestring, '/') > 0) && (strpos($mimestring, '/') < (strlen($mimestring) - 1))) {
		return TRUE;
	}
	return FALSE;
}

function IsWithinBitRange($number, $maxbits, $signed=FALSE) {
	if ($signed) {
		if (($number > (0 - pow(2, $maxbits - 1))) && ($number <= pow(2, $maxbits - 1))) {
			return TRUE;
		}
	} else {
		if (($number >= 0) && ($number <= pow(2, $maxbits))) {
			return TRUE;
		}
	}
	return FALSE;
}

function IsValidTextEncoding($textencodingbyte, $majorversion) {
	if ((chr($frame_data['encodingid']) >= 0) && (chr($frame_data['encodingid']) <= 3) && ($majorversion == 4)) {
		return TRUE;
	} else if ((chr($frame_data['encodingid']) >= 0) && (chr($frame_data['encodingid']) <= 1) && ($majorversion == 3)) {
		return TRUE;
	} else if ((chr($frame_data['encodingid']) >= 0) && (chr($frame_data['encodingid']) <= 1) && ($majorversion == 2)) {
		return TRUE;
	}
	return FALSE;
}

function TextEncodingLookup($type, $encoding) {
	// http://www.id3.org/id3v2.4.0-structure.txt
	// Frames that allow different types of text encoding contains a text encoding description byte. Possible encodings:
	// $00  ISO-8859-1. Terminated with $00.
	// $01  UTF-16 encoded Unicode with BOM. All strings in the same frame SHALL have the same byteorder. Terminated with $00 00.
	// $02  UTF-16BE encoded Unicode without BOM. Terminated with $00 00.
	// $03  UTF-8 encoded Unicode. Terminated with $00.

	$TextEncodingLookup['encoding']   = array('ISO-8859-1', 'UTF-16', 'UTF-16BE', 'UTF-8');
	$TextEncodingLookup['terminator'] = array(chr(0), chr(0).chr(0), chr(0).chr(0), chr(0));

	return (isset($TextEncodingLookup["$type"]["$encoding"]) ? $TextEncodingLookup["$type"]["$encoding"] : '');
}

function IsValidID3v2FrameName($framename, $id3v2majorversion) {
	// determines if first character falls within 'A-Z'
	if ((ord(substr($framename, 0, 1)) > ord('Z')) || (ord(substr($framename, 0, 1)) < ord('A'))) {
    	return FALSE;
	}
	// determines if subsequent characters fall within 'A-Z' or '0-9'
	for ($i=1;$i<strlen($framename);$i++) {
		if ((ord(substr($framename, $i, 1)) > ord('Z')) ||
		    (ord(substr($framename, $i, 1)) < ord('0')) ||
		    ((ord(substr($framename, $i, 1)) > ord('9')) && (ord(substr($framename, $i, 1)) < ord('A')))) {
		    	return FALSE;
		}
	}
	if (($id3v2majorversion == 2) && (strlen($framename) == 3)) {
		return TRUE;
	} else if (($id3v2majorversion >= 3) && (strlen($framename) == 4)) {
		return TRUE;
	} else {
		return FALSE;
	}
}

function IsValidEmail($email) {
	$valid = TRUE;
	$atpos  = strrpos($email, '@');
	$dotpos = strrpos($email, '.');
	if (strlen($email) < 5) { // a@b.c
		$valid = FALSE;
	} else if (!$atpos) { // no '@' or '@' at beginning of string
		$valid = FALSE;
	} else if (!$dotpos) { // no '.' or '.' at beginning of string
		$valid = FALSE;
	} else if (!($atpos+1 < $dotpos)) { // something between '@' and '.', and '.' after '@'
		$valid = FALSE;
	} else if (substr($email, strlen($email), 1) == '.') { // something after '.'
		$valid = FALSE;
	}
	return $valid;
}

function IsValidURL($url, $allowrelative=FALSE, $allowmailto=FALSE) {
	// by pv@gnw.de - 08-Aug-2000
	// modified by getid3@silisoftware.com - 06-Mar-2002
	if ($allowrelative) {
		// pretty much anything goes - how do you validate this?
	} else if ($parts = parse_url(strtolower($url))) {
		// $validschemes = array('http', 'https', 'ftp', 'gopher');
		$validschemes = array('http', 'https', 'ftp');
		if ($allowmailto && ($parts['scheme'] == 'mailto') && IsValidEmail($parts['path'])) {
			return TRUE;
		} else if (!in_array($parts['scheme'], $validschemes)) {
			return FALSE;
		} else if (!eregi("^[0-9a-z]([-.]?[0-9a-z])*\.[a-z]{2,3}$", $parts['host'], $regs)) {
			return FALSE;
		} else if (!eregi("^([0-9a-z-]|[\_])*$", $parts['user'], $regs)) {
			return FALSE;
		} else if (!eregi("^([0-9a-z-]|[\_])*$", $parts['pass'], $regs)) {
			return FALSE;
		} else if (!eregi("^[0-9a-z/_\.@~]*$", $parts['path'], $regs)) {
			return FALSE;
		} else if  (!eregi("^[0-9a-z?&=#\,]*$", $parts['query'], $regs)) {
			return FALSE;
		}
	} else {
		return FALSE;
	}
	return TRUE;
}

function IsANumber($numberstring, $allowdecimal=FALSE) {
	for ($i=0;$i<strlen($numberstring);$i++) {
		if ((chr(substr($numberstring, $i, 1)) < chr('0')) || (chr(substr($numberstring, $i, 1)) > chr('9'))) {
			if ($allowdecimal && (substr($numberstring, $i, 1) == '.')) {
				// allow
			} else {
				return FALSE;
			}
		}
	}
	return TRUE;
}

function IsValidDateStampString($datestamp) {
	if (strlen($datestamp) != 8) {
		return FALSE;
	}
	if (!IsANumber($datestamp, FALSE)) {
		return FALSE;
	}
	$year  = substr($datestamp, 0, 4);
	$month = substr($datestamp, 4, 2);
	$day   = substr($datestamp, 6, 2);
	if (($year == 0) || ($month == 0) || ($day == 0)) {
		return FALSE;
	}
	if ($month > 12) {
		return FALSE;
	}
	if ($day > 31) {
		return FALSE;
	}
	if (($day > 30) && (($month == 4) || ($month == 6) || ($month == 9) || ($month == 11))) {
		return FALSE;
	}
	if (($day > 29) && ($month == 2)) {
		return FALSE;
	}
	return TRUE;
}

function IsValidPriceString($pricestring) {
	if (LanguageLookup(substr($pricestring, 0, 3), TRUE) == '') {
		return FALSE;
	} else if (!IsANumber(substr($pricestring, 3), TRUE)) {
		return FALSE;
	}
	return TRUE;
}

function ID3v2HeaderLength($majorversion) {
	if ($majorversion == 2) {
		return 6;
	} else {
		return 10;
	}
}

function GeneralMIDIinstrumentLookup($instrumentid) {
	$GeneralMIDIinstrumentLookup[0]   = 'Acoustic Grand';
	$GeneralMIDIinstrumentLookup[1]   = 'Bright Acoustic';
	$GeneralMIDIinstrumentLookup[2]   = 'Electric Grand';
	$GeneralMIDIinstrumentLookup[3]   = 'Honky-Tonk';
	$GeneralMIDIinstrumentLookup[4]   = 'Electric Piano 1';
	$GeneralMIDIinstrumentLookup[5]   = 'Electric Piano 2';
	$GeneralMIDIinstrumentLookup[6]   = 'Harpsichord';
	$GeneralMIDIinstrumentLookup[7]   = 'Clav';
	$GeneralMIDIinstrumentLookup[8]   = 'Celesta';
	$GeneralMIDIinstrumentLookup[9]   = 'Glockenspiel';
	$GeneralMIDIinstrumentLookup[10]  = 'Music Box';
	$GeneralMIDIinstrumentLookup[11]  = 'Vibraphone';
	$GeneralMIDIinstrumentLookup[12]  = 'Marimba';
	$GeneralMIDIinstrumentLookup[13]  = 'Xylophone';
	$GeneralMIDIinstrumentLookup[14]  = 'Tubular Bells';
	$GeneralMIDIinstrumentLookup[15]  = 'Dulcimer';
	$GeneralMIDIinstrumentLookup[16]  = 'Drawbar Organ';
	$GeneralMIDIinstrumentLookup[17]  = 'Percussive Organ';
	$GeneralMIDIinstrumentLookup[18]  = 'Rock Organ';
	$GeneralMIDIinstrumentLookup[19]  = 'Church Organ';
	$GeneralMIDIinstrumentLookup[20]  = 'Reed Organ';
	$GeneralMIDIinstrumentLookup[21]  = 'Accordian';
	$GeneralMIDIinstrumentLookup[22]  = 'Harmonica';
	$GeneralMIDIinstrumentLookup[23]  = 'Tango Accordian';
	$GeneralMIDIinstrumentLookup[24]  = 'Acoustic Guitar (nylon)';
	$GeneralMIDIinstrumentLookup[25]  = 'Acoustic Guitar (steel)';
	$GeneralMIDIinstrumentLookup[26]  = 'Electric Guitar (jazz)';
	$GeneralMIDIinstrumentLookup[27]  = 'Electric Guitar (clean)';
	$GeneralMIDIinstrumentLookup[28]  = 'Electric Guitar (muted)';
	$GeneralMIDIinstrumentLookup[29]  = 'Overdriven Guitar';
	$GeneralMIDIinstrumentLookup[30]  = 'Distortion Guitar';
	$GeneralMIDIinstrumentLookup[31]  = 'Guitar Harmonics';
	$GeneralMIDIinstrumentLookup[32]  = 'Acoustic Bass';
	$GeneralMIDIinstrumentLookup[33]  = 'Electric Bass (finger)';
	$GeneralMIDIinstrumentLookup[34]  = 'Electric Bass (pick)';
	$GeneralMIDIinstrumentLookup[35]  = 'Fretless Bass';
	$GeneralMIDIinstrumentLookup[36]  = 'Slap Bass 1';
	$GeneralMIDIinstrumentLookup[37]  = 'Slap Bass 2';
	$GeneralMIDIinstrumentLookup[38]  = 'Synth Bass 1';
	$GeneralMIDIinstrumentLookup[39]  = 'Synth Bass 2';
	$GeneralMIDIinstrumentLookup[40]  = 'Violin';
	$GeneralMIDIinstrumentLookup[41]  = 'Viola';
	$GeneralMIDIinstrumentLookup[42]  = 'Cello';
	$GeneralMIDIinstrumentLookup[43]  = 'Contrabass';
	$GeneralMIDIinstrumentLookup[44]  = 'Tremolo Strings';
	$GeneralMIDIinstrumentLookup[45]  = 'Pizzicato Strings';
	$GeneralMIDIinstrumentLookup[46]  = 'Orchestral Strings';
	$GeneralMIDIinstrumentLookup[47]  = 'Timpani';
	$GeneralMIDIinstrumentLookup[48]  = 'String Ensemble 1';
	$GeneralMIDIinstrumentLookup[49]  = 'String Ensemble 2';
	$GeneralMIDIinstrumentLookup[50]  = 'SynthStrings 1';
	$GeneralMIDIinstrumentLookup[51]  = 'SynthStrings 2';
	$GeneralMIDIinstrumentLookup[52]  = 'Choir Aahs';
	$GeneralMIDIinstrumentLookup[53]  = 'Voice Oohs';
	$GeneralMIDIinstrumentLookup[54]  = 'Synth Voice';
	$GeneralMIDIinstrumentLookup[55]  = 'Orchestra Hit';
	$GeneralMIDIinstrumentLookup[56]  = 'Trumpet';
	$GeneralMIDIinstrumentLookup[57]  = 'Trombone';
	$GeneralMIDIinstrumentLookup[58]  = 'Tuba';
	$GeneralMIDIinstrumentLookup[59]  = 'Muted Trumpet';
	$GeneralMIDIinstrumentLookup[60]  = 'French Horn';
	$GeneralMIDIinstrumentLookup[61]  = 'Brass Section';
	$GeneralMIDIinstrumentLookup[62]  = 'SynthBrass 1';
	$GeneralMIDIinstrumentLookup[63]  = 'SynthBrass 2';
	$GeneralMIDIinstrumentLookup[64]  = 'Soprano Sax';
	$GeneralMIDIinstrumentLookup[65]  = 'Alto Sax';
	$GeneralMIDIinstrumentLookup[66]  = 'Tenor Sax';
	$GeneralMIDIinstrumentLookup[67]  = 'Baritone Sax';
	$GeneralMIDIinstrumentLookup[68]  = 'Oboe';
	$GeneralMIDIinstrumentLookup[69]  = 'English Horn';
	$GeneralMIDIinstrumentLookup[70]  = 'Bassoon';
	$GeneralMIDIinstrumentLookup[71]  = 'Clarinet';
	$GeneralMIDIinstrumentLookup[72]  = 'Piccolo';
	$GeneralMIDIinstrumentLookup[73]  = 'Flute';
	$GeneralMIDIinstrumentLookup[74]  = 'Recorder';
	$GeneralMIDIinstrumentLookup[75]  = 'Pan Flute';
	$GeneralMIDIinstrumentLookup[76]  = 'Blown Bottle';
	$GeneralMIDIinstrumentLookup[77]  = 'Shakuhachi';
	$GeneralMIDIinstrumentLookup[78]  = 'Whistle';
	$GeneralMIDIinstrumentLookup[79]  = 'Ocarina';
	$GeneralMIDIinstrumentLookup[80]  = 'Lead 1 (square)';
	$GeneralMIDIinstrumentLookup[81]  = 'Lead 2 (sawtooth)';
	$GeneralMIDIinstrumentLookup[82]  = 'Lead 3 (calliope)';
	$GeneralMIDIinstrumentLookup[83]  = 'Lead 4 (chiff)';
	$GeneralMIDIinstrumentLookup[84]  = 'Lead 5 (charang)';
	$GeneralMIDIinstrumentLookup[85]  = 'Lead 6 (voice)';
	$GeneralMIDIinstrumentLookup[86]  = 'Lead 7 (fifths)';
	$GeneralMIDIinstrumentLookup[87]  = 'Lead 8 (bass + lead)';
	$GeneralMIDIinstrumentLookup[88]  = 'Pad 1 (new age)';
	$GeneralMIDIinstrumentLookup[89]  = 'Pad 2 (warm)';
	$GeneralMIDIinstrumentLookup[90]  = 'Pad 3 (polysynth)';
	$GeneralMIDIinstrumentLookup[91]  = 'Pad 4 (choir)';
	$GeneralMIDIinstrumentLookup[92]  = 'Pad 5 (bowed)';
	$GeneralMIDIinstrumentLookup[93]  = 'Pad 6 (metallic)';
	$GeneralMIDIinstrumentLookup[94]  = 'Pad 7 (halo)';
	$GeneralMIDIinstrumentLookup[95]  = 'Pad 8 (sweep)';
	$GeneralMIDIinstrumentLookup[96]  = 'FX 1 (rain)';
	$GeneralMIDIinstrumentLookup[97]  = 'FX 2 (soundtrack)';
	$GeneralMIDIinstrumentLookup[98]  = 'FX 3 (crystal)';
	$GeneralMIDIinstrumentLookup[99]  = 'FX 4 (atmosphere)';
	$GeneralMIDIinstrumentLookup[100] = 'FX 5 (brightness)';
	$GeneralMIDIinstrumentLookup[101] = 'FX 6 (goblins)';
	$GeneralMIDIinstrumentLookup[102] = 'FX 7 (echoes)';
	$GeneralMIDIinstrumentLookup[103] = 'FX 8 (sci-fi)';
	$GeneralMIDIinstrumentLookup[104] = 'Sitar';
	$GeneralMIDIinstrumentLookup[105] = 'Banjo';
	$GeneralMIDIinstrumentLookup[106] = 'Shamisen';
	$GeneralMIDIinstrumentLookup[107] = 'Koto';
	$GeneralMIDIinstrumentLookup[108] = 'Kalimba';
	$GeneralMIDIinstrumentLookup[109] = 'Bagpipe';
	$GeneralMIDIinstrumentLookup[110] = 'Fiddle';
	$GeneralMIDIinstrumentLookup[111] = 'Shanai';
	$GeneralMIDIinstrumentLookup[112] = 'Tinkle Bell';
	$GeneralMIDIinstrumentLookup[113] = 'Agogo';
	$GeneralMIDIinstrumentLookup[114] = 'Steel Drums';
	$GeneralMIDIinstrumentLookup[115] = 'Woodblock';
	$GeneralMIDIinstrumentLookup[116] = 'Taiko Drum';
	$GeneralMIDIinstrumentLookup[117] = 'Melodic Tom';
	$GeneralMIDIinstrumentLookup[118] = 'Synth Drum';
	$GeneralMIDIinstrumentLookup[119] = 'Reverse Cymbal';
	$GeneralMIDIinstrumentLookup[120] = 'Guitar Fret Noise';
	$GeneralMIDIinstrumentLookup[121] = 'Breath Noise';
	$GeneralMIDIinstrumentLookup[122] = 'Seashore';
	$GeneralMIDIinstrumentLookup[123] = 'Bird Tweet';
	$GeneralMIDIinstrumentLookup[124] = 'Telephone Ring';
	$GeneralMIDIinstrumentLookup[125] = 'Helicopter';
	$GeneralMIDIinstrumentLookup[126] = 'Applause';
	$GeneralMIDIinstrumentLookup[127] = 'Gunshot';

	return (isset($GeneralMIDIinstrumentLookup["$instrumentid"]) ? $GeneralMIDIinstrumentLookup["$instrumentid"] : '');
}

function GeneralMIDIpercussionLookup($instrumentid) {
	$GeneralMIDIpercussionLookup[35] = 'Acoustic Bass Drum';
	$GeneralMIDIpercussionLookup[36] = 'Bass Drum 1';
	$GeneralMIDIpercussionLookup[37] = 'Side Stick';
	$GeneralMIDIpercussionLookup[38] = 'Acoustic Snare';
	$GeneralMIDIpercussionLookup[39] = 'Hand Clap';
	$GeneralMIDIpercussionLookup[40] = 'Electric Snare';
	$GeneralMIDIpercussionLookup[41] = 'Low Floor Tom';
	$GeneralMIDIpercussionLookup[42] = 'Closed Hi-Hat';
	$GeneralMIDIpercussionLookup[43] = 'High Floor Tom';
	$GeneralMIDIpercussionLookup[44] = 'Pedal Hi-Hat';
	$GeneralMIDIpercussionLookup[45] = 'Low Tom';
	$GeneralMIDIpercussionLookup[46] = 'Open Hi-Hat';
	$GeneralMIDIpercussionLookup[47] = 'Low-Mid Tom';
	$GeneralMIDIpercussionLookup[48] = 'Hi-Mid Tom';
	$GeneralMIDIpercussionLookup[49] = 'Crash Cymbal 1';
	$GeneralMIDIpercussionLookup[50] = 'High Tom';
	$GeneralMIDIpercussionLookup[51] = 'Ride Cymbal 1';
	$GeneralMIDIpercussionLookup[52] = 'Chinese Cymbal';
	$GeneralMIDIpercussionLookup[53] = 'Ride Bell';
	$GeneralMIDIpercussionLookup[54] = 'Tambourine';
	$GeneralMIDIpercussionLookup[55] = 'Splash Cymbal';
	$GeneralMIDIpercussionLookup[56] = 'Cowbell';
	$GeneralMIDIpercussionLookup[57] = 'Crash Cymbal 2';
	$GeneralMIDIpercussionLookup[59] = 'Ride Cymbal 2';
	$GeneralMIDIpercussionLookup[60] = 'Hi Bongo';
	$GeneralMIDIpercussionLookup[61] = 'Low Bongo';
	$GeneralMIDIpercussionLookup[62] = 'Mute Hi Conga';
	$GeneralMIDIpercussionLookup[63] = 'Open Hi Conga';
	$GeneralMIDIpercussionLookup[64] = 'Low Conga';
	$GeneralMIDIpercussionLookup[65] = 'High Timbale';
	$GeneralMIDIpercussionLookup[66] = 'Low Timbale';
	$GeneralMIDIpercussionLookup[67] = 'High Agogo';
	$GeneralMIDIpercussionLookup[68] = 'Low Agogo';
	$GeneralMIDIpercussionLookup[69] = 'Cabasa';
	$GeneralMIDIpercussionLookup[70] = 'Maracas';
	$GeneralMIDIpercussionLookup[71] = 'Short Whistle';
	$GeneralMIDIpercussionLookup[72] = 'Long Whistle';
	$GeneralMIDIpercussionLookup[73] = 'Short Guiro';
	$GeneralMIDIpercussionLookup[74] = 'Long Guiro';
	$GeneralMIDIpercussionLookup[75] = 'Claves';
	$GeneralMIDIpercussionLookup[76] = 'Hi Wood Block';
	$GeneralMIDIpercussionLookup[77] = 'Low Wood Block';
	$GeneralMIDIpercussionLookup[78] = 'Mute Cuica';
	$GeneralMIDIpercussionLookup[79] = 'Open Cuica';
	$GeneralMIDIpercussionLookup[80] = 'Mute Triangle';
	$GeneralMIDIpercussionLookup[81] = 'Open Triangle';

	return (isset($GeneralMIDIpercussionLookup["$instrumentid"]) ? $GeneralMIDIpercussionLookup["$instrumentid"] : '');
}

?>
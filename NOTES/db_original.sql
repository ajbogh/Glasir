CREATE TABLE `Songs` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Title` varchar(128) DEFAULT NULL,
  `Artist` varchar(128) DEFAULT NULL,
  `Album` varchar(128) DEFAULT NULL,
  `Year` varchar(128) DEFAULT NULL,
  `Comment` varchar(128) DEFAULT NULL,
  `Track` int(11) DEFAULT '0',
  `Genre` varchar(64) DEFAULT NULL,
  `Filename` varchar(1024) NOT NULL,
  `Bitrate` int(11) DEFAULT NULL,
  `Filesize` double DEFAULT NULL,
  `Duration` float DEFAULT NULL,
  `BPM` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `filename_index` (`Filename`(333))
) ENGINE=MyISAM AUTO_INCREMENT=106 DEFAULT CHARSET=utf8

CREATE TABLE `Users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(64) NOT NULL,
  `Password` varchar(256) NOT NULL,
  `Email` varchar(128) NOT NULL,
  `PasswordResetCode` varchar(256) DEFAULT NULL,
  `PasswordResetTime` datetime DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1

CREATE TABLE `Users_Songs` (
  `SongID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `TimesPlayed` int(11) NOT NULL DEFAULT '0',
  `Liked` tinyint(1) DEFAULT NULL,
  `Disliked` tinyint(1) DEFAULT NULL,
  `InPlaylist` tinyint(1) DEFAULT NULL,
  `Order` int(11) DEFAULT '0',
  PRIMARY KEY (`SongID`,`UserID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Songs that users have played or liked/disliked'

CREATE TABLE `Playlists` (
	`SongID` int(11) NOT NULL,
	`UserID` int(11) NOT NULL,
	`PlayOrder` int(11) DEFAULT '0',
	PRIMARY KEY (`SongID`,`UserID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1

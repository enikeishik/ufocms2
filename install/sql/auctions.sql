CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */auctions_settings` (
  `Id` int(11) NOT NULL auto_increment,
  `SectionId` int(11) NOT NULL default '0',
  `BodyHead` text NOT NULL,
  `BodyFoot` text NOT NULL,
  `PageLength` int(11) NOT NULL default '10',
  `UpdateTimeout` int(11) NOT NULL default '10',
  `UpdateType` tinyint(1) NOT NULL default '0', /* 0 - iframe, 1 - meta refresh, 2 - AJAX */
  PRIMARY KEY  (`Id`),
  KEY `SectionId` (`SectionId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */auctions` (
  `Id` int(11) NOT NULL auto_increment,
  `UserId` int(11) NOT NULL default '0',
  `SectionId` int(11) NOT NULL default '0',
  `IsDisabled` tinyint(1) NOT NULL default '0',
  `IsClosed` tinyint(1) NOT NULL default '0',
  `DateCreate` datetime NOT NULL default '0000-00-00 00:00:00',
  `DatePublicate` datetime NOT NULL default '0000-00-00 00:00:00',
  `DateStart` datetime NOT NULL default '0000-00-00 00:00:00',
  `DateStop` datetime NOT NULL default '0000-00-00 00:00:00',
  `DateView` datetime NOT NULL default '0000-00-00 00:00:00',
  `Step` int(11) NOT NULL default '-100',
  `StepTime` int(11) NOT NULL default '600',
  `PriceStart` int(11) NOT NULL default '1000',
  `PriceStop` int(11) NOT NULL default '100',
  `PriceCurrent` int(11) NOT NULL default '1000',
  `ViewedCnt` int(11) NOT NULL default '0',
  `Title` varchar(255) NOT NULL default '',
  `Thumbnail` varchar(255) NOT NULL default '',
  `Image` varchar(255) NOT NULL default '',
  `ShortDesc` text NOT NULL,
  `FullDesc` text NOT NULL,
  PRIMARY KEY  (`Id`),
  KEY `SDPS` (`SectionId`, `IsDisabled`, `DatePublicate`, `DateStart`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */auctions_log` (
  `Id` int(11) NOT NULL auto_increment,
  `UserId` int(11) NOT NULL default '0',
  `SectionId` int(11) NOT NULL default '0',
  `AuctionId` int(11) NOT NULL default '0',
  `DateCreate` datetime NOT NULL default '0000-00-00 00:00:00',
  `PriceNew` int(11) NOT NULL default '0',
  `IsSuccess` tinyint(1) NOT NULL default '0',
  `Info` text NOT NULL,
  PRIMARY KEY  (`Id`),
  KEY `SectionId` (`SectionId`),
  KEY `AuctionId` (`AuctionId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


INSERT INTO `/* TABLE_PREFIX */modules` (`id`, `muid`, `mname`, `mfile`, `mfileins`, `mfileinc`, `mfilexsm`, `mtable`, `mtableitems`, `madmin`, `issingle`, `isinsertion`, `isinclusion`, `isenabled`) 
VALUES (26, 26, 'Аукционы', 'mod_auctions.php', '', '', '', 'auctions_settings', 'auctions,auctions_log', 'mod_auctions', 0, 0, 0, 1);

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */oldurls` (
  `Id` int(11) NOT NULL auto_increment,
  `UserId` int(11) NOT NULL default '0',
  `SectionId` int(11) NOT NULL default '0',
  `OrderNumber` int(11) NOT NULL default '0',
  `DateCreate` datetime NOT NULL default '0000-00-00 00:00:00',
  `DateView` datetime NOT NULL default '0000-00-00 00:00:00',
  `Url` varchar(255) NOT NULL default '',
  `Target` varchar(255) NOT NULL default '',
  `Title` varchar(255) NOT NULL default '',
  `MetaKeys` varchar(255) NOT NULL default '',
  `MetaDesc` varchar(255) NOT NULL default '',
  `Body` text NOT NULL,
  `ViewedCnt` int(11) NOT NULL default '0',
  `IsHidden` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`Id`),
  KEY `SHU` (`SectionId`, `IsHidden`, `Url`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */oldurls_sections` (
  `Id` int(11) NOT NULL auto_increment,
  `SectionId` int(11) NOT NULL default '0',
  `BodyHead` text NOT NULL,
  `BodyFoot` text NOT NULL,
  `PageLength` int(11) NOT NULL default '10',
  `Orderby` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


INSERT INTO `/* TABLE_PREFIX */modules` (`id`, `muid`, `mname`, `mfile`, `mfileins`, `mfileinc`, `mfilexsm`, `mtable`, `mtableitems`, `madmin`, `issingle`, `isinsertion`, `isinclusion`, `isenabled`) 
VALUES (22, 22, 'Архив старых страниц', 'mod_oldurls.php', '0', '', 'xsm_oldurls.php', 'oldurls_sections', 'oldurls', 'mod_oldurls', 0, 0, 0, 1);


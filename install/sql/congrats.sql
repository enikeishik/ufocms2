CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */congrats_settings` (
  `Id` int(11) NOT NULL auto_increment,
  `SectionId` int(11) NOT NULL default '0',
  `BodyHead` text NOT NULL,
  `BodyFoot` text NOT NULL,
  `ShowDays` int(11) NOT NULL default '30',
  `PageLength` int(11) NOT NULL default '10',
  `Orderby` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`Id`),
  KEY `SectionId` (`SectionId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */congrats_items` (
  `Id` int(11) NOT NULL auto_increment,
  `UserId` int(11) NOT NULL default '0',
  `SectionId` int(11) NOT NULL default '0',
  `DateCreate` datetime NOT NULL default '0000-00-00 00:00:00',
  `DateStart` datetime NOT NULL default '0000-00-00 00:00:00',
  `DateStop` datetime NOT NULL default '0000-00-00 00:00:00',
  `DateView` datetime NOT NULL default '0000-00-00 00:00:00',
  `IsDisabled` tinyint(1) NOT NULL default '0',
  `IsEternal` tinyint(1) NOT NULL default '0',
  `IsPinned` tinyint(1) NOT NULL default '0',
  `IsHighlighted` tinyint(1) NOT NULL default '0',
  `ViewedCnt` int(11) NOT NULL default '0',
  `Thumbnail` varchar(255) NOT NULL default '',
  `Image` varchar(255) NOT NULL default '',
  `ShortDesc` text NOT NULL,
  `FullDesc` text NOT NULL,
  PRIMARY KEY  (`Id`),
  KEY `SDPSS` (`SectionId`, `IsDisabled`, `IsPinned`, `DateStart`, `DateStop`),
  KEY `ViewedCnt` (`ViewedCnt`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `/* TABLE_PREFIX */modules` (`id`, `muid`, `mname`, `mfile`, `mfileins`, `mfileinc`, `mfilexsm`, `mtable`, `mtableitems`, `madmin`, `issingle`, `isinsertion`, `isinclusion`, `isenabled`) 
VALUES (25, 25, 'Поздравления', 'mod_congrats.php', '', '', 'xsm_congrats.php', 'congrats_settings', 'congrats_items', 'mod_congrats', 0, 0, 0, 1);

INSERT INTO `/* TABLE_PREFIX */widgets_types`
(ModuleId, Name, Title, Description) VALUES
(25, '', 'Поздравления', 'Вывод элементов из раздела типа Поздравления');

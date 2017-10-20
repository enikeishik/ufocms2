CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */tales` (
  `Id` int(11) NOT NULL auto_increment,
  `UserId` int(11) NOT NULL default '0',
  `SectionId` int(11) NOT NULL default '0',
  `OrderNumber` int(11) NOT NULL default '0',
  `DateCreate` datetime NOT NULL default '0000-00-00 00:00:00',
  `DateView` datetime NOT NULL default '0000-00-00 00:00:00',
  `Url` varchar(255) NOT NULL default '',
  `Title` varchar(255) NOT NULL default '',
  `MetaKeys` varchar(255) NOT NULL default '',
  `MetaDesc` varchar(255) NOT NULL default '',
  `Author` varchar(255) NOT NULL default '',
  `Icon` varchar(255) NOT NULL default '',
  `Announce` text NOT NULL,
  `Body` text NOT NULL,
  `ViewedCnt` int(11) NOT NULL default '0',
  `IsHidden` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`Id`),
  KEY `SHOCV` (`SectionId`, `IsHidden`, `OrderNumber`, `DateCreate`, `ViewedCnt`),
  KEY `SHU` (`SectionId`, `IsHidden`, `Url`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */tales_sections` (
  `Id` int(11) NOT NULL auto_increment,
  `SectionId` int(11) NOT NULL default '0',
  `BodyHead` text NOT NULL,
  `BodyFoot` text NOT NULL,
  `IconAttributes` varchar(255) NOT NULL default '',
  `AnnounceLength` int(11) NOT NULL default '255',
  `PageLength` int(11) NOT NULL default '10',
  `RssOutCount` int(11) NOT NULL default '100',
  `Orderby` tinyint(1) NOT NULL default '0',
  `InheritMeta` tinyint(1) NOT NULL default '0', /* использовать информацию в тэгах TITLE и META из родительского раздела и всего сайта */
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


INSERT INTO `/* TABLE_PREFIX */modules` (`id`, `muid`, `mname`, `mfile`, `mfileins`, `mfileinc`, `mfilexsm`, `mtable`, `mtableitems`, `madmin`, `issingle`, `isinsertion`, `isinclusion`, `isenabled`) 
VALUES (16, 16, 'Лента документов', 'mod_tales.php', '', '', 'xsm_tales.php', 'tales_sections', 'tales', 'mod_tales', 0, 0, 0, 1);

INSERT INTO `/* TABLE_PREFIX */widgets_types`
(ModuleId, Name, Title, Description) VALUES
(16, '', 'Статьи', 'Вывод элементов из раздела типа Лента документов');

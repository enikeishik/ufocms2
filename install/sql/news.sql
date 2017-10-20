CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */news` (
  `Id` int(11) NOT NULL auto_increment,
  `SectionId` int(11) NOT NULL default '0',
  `DateCreate` datetime NOT NULL default '0000-00-00 00:00:00',
  `Title` varchar(255) NOT NULL default '',
  `Author` varchar(255) NOT NULL default '',
  `Icon` varchar(255) NOT NULL default '',
  `Announce` text NOT NULL,
  `Body` text NOT NULL,
  `ViewedCnt` int(11) NOT NULL default '0',
  `IsHidden` tinyint(1) NOT NULL default '0',
  `IsRss` tinyint(1) NOT NULL default '1',
  `IsTimered` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`Id`),
  KEY `SHD` (`SectionId`, `IsHidden`, `DateCreate`),
  KEY `ViewedCnt` (`ViewedCnt`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */news_sections` (
  `Id` int(11) NOT NULL auto_increment,
  `SectionId` int(11) NOT NULL default '0',
  `BodyHead` text NOT NULL,
  `BodyFoot` text NOT NULL,
  `IconAttributes` varchar(255) NOT NULL default '',
  `PageLength` int(11) NOT NULL default '10',
  `AnnounceLength` int(11) NOT NULL default '255',
  `RssExpireOffset` int(11) NOT NULL default '7200',
  `TimerOffset` int(11) NOT NULL default '0',
  `IsArchive` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`Id`),
  KEY `SectionId` (`SectionId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `/* TABLE_PREFIX */modules` (`id`, `muid`, `mname`, `mfile`, `mfileins`, `mfileinc`, `mfilexsm`, `mtable`, `mtableitems`, `madmin`, `issingle`, `isinsertion`, `isinclusion`, `isenabled`) 
VALUES (2, 2, 'Лента новостей', 'mod_news.php', 'ins_news.php', 'inc_news.php', 'xsm_news.php', 'news_sections', 'news', 'mod_news', 0, 1, 1, 1);

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */news_import` (
  `Id` int(11) NOT NULL auto_increment,
  `SectionId` int(11) NOT NULL default '0',
  `ItemsShow` int(11) NOT NULL default '20',
  `Title` varchar(255) NOT NULL default '',
  `Url` varchar(255) NOT NULL default '',
  `LastGuid` varchar(255) NOT NULL default '',
  `ItemAuthor` varchar(255) NOT NULL default '',
  `ItemIcon` varchar(255) NOT NULL default '',
  `ItemTitleSearch` varchar(255) NOT NULL default '',
  `ItemTitleReplace` varchar(255) NOT NULL default '',
  `ItemAnnounceSearch` varchar(255) NOT NULL default '',
  `ItemAnnounceReplace` varchar(255) NOT NULL default '',
  `ItemBodySearch` varchar(255) NOT NULL default '',
  `ItemBodyReplace` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `/* TABLE_PREFIX */widgets_types`
(ModuleId, Name, Title, Description) VALUES
(2, '', 'Новости', 'Вывод элементов новостных разделов'),
(2, 'Calendar', 'Архив новостей (календарь)', 'Вывод ссылок на архив новостей в виде календаря'),
(2, 'Authors', 'Авторы новостей', 'Вывод ссылок на новости конкретных авторов');

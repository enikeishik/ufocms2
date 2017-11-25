CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */news2_sections` (
  `Id` int(11) NOT NULL auto_increment,
  `SectionId` int(11) NOT NULL default '0',
  `BodyHead` text NOT NULL,
  `BodyFoot` text NOT NULL,
  `Orderby` varchar(255) NOT NULL default '',
  `IconAttributes` varchar(255) NOT NULL default '',
  `InsIconAttributes` varchar(255) NOT NULL default '',
  `AnnounceLength` int(11) NOT NULL default '255',
  `PageLength` int(11) NOT NULL default '10',
  `RssOutCount` int(11) NOT NULL default '100',
  `RssExpireOffset` int(11) NOT NULL default '7200', /* minutes */
  `TimerOffset` int(11) NOT NULL default '0',
  `IsPublic` tinyint(1) NOT NULL default '0', /* в разделе разрешена публикация новостей зарегистрированным пользователям сайта */
  `ThumbnailWidth` int(11) NOT NULL default '100',
  `ThumbnailHeight` int(11) NOT NULL default '75',
  `ImageMaxWidth` int(11) NOT NULL default '800',
  `ImageMaxHeight` int(11) NOT NULL default '600',
  `ImageMaxSize` int(11) NOT NULL default '102400',
  `ImageTypes` varchar(255) NOT NULL default 'jpg,jpeg,gif,png',
  `UploadDir` varchar(255) NOT NULL default '/upload/news',
  `IsModerated` tinyint(1) NOT NULL default '0', /* публикуемые пользователями новости будут скрытыми до проверки модератором */
  `IsGlobalAE` tinyint(1) NOT NULL default '1', /* использовать глобальный email администратора сайта для уведомлений */
  `AlertEmail` varchar(255) NOT NULL default '',
  `AlertEmailSubj` varchar(255) NOT NULL default 'News added',
  `AlertEmailBody` text NOT NULL,
  PRIMARY KEY  (`Id`),
  KEY `SectionId` (`SectionId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
/*
Orderby fields:
DateCreate DESC/ASC, 
DateComment DESC/ASC, 
DateRate DESC/ASC, 
DateView DESC/ASC, 
Rating DESC/ASC, 
RatesCnt DESC/ASC, 
CommentsCnt DESC/ASC, 
ViewedCnt DESC/ASC, 
Title DESC/ASC, 
*/

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */news2` (
  `Id` int(11) NOT NULL auto_increment,
  `UserId` int(11) NOT NULL default '0',
  `SectionId` int(11) NOT NULL default '0',
  `DateCreate` datetime NOT NULL default '0000-00-00 00:00:00',
  `DateView` datetime NOT NULL default '0000-00-00 00:00:00',
  `Title` varchar(255) NOT NULL default '',
  `Author` varchar(255) NOT NULL default '',
  `Icon` varchar(255) NOT NULL default '',
  `InsIcon` varchar(255) NOT NULL default '',
  `Announce` text NOT NULL,
  `Body` text NOT NULL,
  `ViewedCnt` int(11) NOT NULL default '0',
  `IsHidden` tinyint(1) NOT NULL default '0',
  `IsRss` tinyint(1) NOT NULL default '1',
  `IsTimered` tinyint(1) NOT NULL default '0',
  `IsDisabled` tinyint(1) NOT NULL default '0', /* запись не доступна для действий пользователю */
  PRIMARY KEY  (`Id`),
  KEY `DateCreate` (`DateCreate`),
  KEY `ViewedCnt` (`ViewedCnt`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */news2_ns` (
  `Id` int(11) NOT NULL auto_increment,
  `SectionId` int(11) NOT NULL default '0',
  `ItemId` int(11) NOT NULL default '0',
  `AnotherSectionId` int(11) NOT NULL default '0',
  PRIMARY KEY  (`Id`),
  KEY `ItemId` (`ItemId`),
  KEY `AnotherSectionId` (`AnotherSectionId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */news2_tags` (
  `Id` int(11) NOT NULL auto_increment,
  `Tag` varchar(255) NOT NULL default '',
  `IsDisabled` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`Id`),
  UNIQUE KEY `Tag` (`Tag`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */news2_nt` (
  `Id` int(11) NOT NULL auto_increment,
  `ItemId` int(11) NOT NULL default '0',
  `TagId` int(11) NOT NULL default '0',
  PRIMARY KEY  (`Id`),
  KEY `ItemId` (`ItemId`),
  KEY `TagId` (`TagId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `/* TABLE_PREFIX */modules` (`id`, `muid`, `mname`, `mfile`, `mfileins`, `mfileinc`, `mfilexsm`, `mtable`, `mtableitems`, `madmin`, `issingle`, `isinsertion`, `isinclusion`, `isenabled`) 
VALUES (17, 17, 'Лента новостей 2', 'mod_news2.php', 'ins_news2.php', 'inc_news2.php', 'xsm_news2.php', 'news2_sections', 'news2,news2_ns,news2_tags,news2_nt', 'mod_news2', 0, 1, 1, 1);

INSERT INTO `/* TABLE_PREFIX */widgets_types`
(ModuleId, Name, Title, Description) VALUES
(17, '', 'Новости 2', 'Вывод элементов разделов типа Новости 2');

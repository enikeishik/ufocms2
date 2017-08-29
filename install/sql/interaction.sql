CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */interaction_comments` (
  `Id` int(11) NOT NULL auto_increment,
  `UserId` int(11) NOT NULL default '0',
  `SectionId` int(11) NOT NULL default '0',
  `ItemId` int(11) NOT NULL default '0',
  `TopId` int(11) NOT NULL default '0',
  `ParentId` int(11) NOT NULL default '0',
  `OrderId` int(11) NOT NULL default '0',
  `LevelId` int(11) NOT NULL default '0',
  `Mask` varchar(255) NOT NULL default '',
  `DateCreate` datetime NOT NULL default '0000-00-00 00:00:00',
  `IP` varchar(15) NOT NULL default '',
  `Info` text NOT NULL,
  `CommentText` text NOT NULL,
  `CommentAuthor` varchar(255) NOT NULL default '',
  `CommentEmail` varchar(255) NOT NULL default '',
  `CommentUrl` varchar(255) NOT NULL default '',
  `CommentStatus` tinyint(1) NOT NULL default '0', /* 0 - neutral, 1 - good, -1 - bad */
  `AnswerText` text NOT NULL,
  `AnswerAuthor` varchar(255) NOT NULL default '',
  `AnswerEmail` varchar(255) NOT NULL default '',
  `AnswerUrl` varchar(255) NOT NULL default '',
  `IsDisabled` tinyint(1) NOT NULL default '0',
  `RatesCnt` int(11) NOT NULL default '0',
  `Rating` int(11) NOT NULL default '0',
  PRIMARY KEY  (`Id`),
  KEY `UserId` (`UserId`),
  KEY `SectionItem` (`SectionId`,`ItemId`),
  KEY `Mask` (`Mask`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */interaction_comments_rates` (
  `Id` int(11) NOT NULL auto_increment,
  `UserId` int(11) NOT NULL default '0',
  `SectionId` int(11) NOT NULL default '0', /* необходимо только для массового удаления при удалении раздела */
  `CommentId` int(11) NOT NULL default '0',
  `DateCreate` datetime NOT NULL default '0000-00-00 00:00:00',
  `IP` varchar(15) NOT NULL default '',
  `Info` text NOT NULL,
  `Rate` tinyint(1) NOT NULL default '0', /* 1, -1 */
  `IsDisabled` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`Id`),
  KEY `UserId` (`UserId`),
  KEY `CommentId` (`CommentId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */interaction_rates` (
  `Id` int(11) NOT NULL auto_increment,
  `UserId` int(11) NOT NULL default '0',
  `SectionId` int(11) NOT NULL default '0',
  `ItemId` int(11) NOT NULL default '0',
  `DateCreate` datetime NOT NULL default '0000-00-00 00:00:00',
  `IP` varchar(15) NOT NULL default '',
  `Info` text NOT NULL,
  `Rate` int(11) NOT NULL default '0',
  `IsDisabled` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`Id`),
  KEY `UserId` (`UserId`),
  KEY `SectionItem` (`SectionId`,`ItemId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */interaction_stat_periods_units` (
  `Id` int(11) NOT NULL auto_increment,
  `Code` varchar(255) NOT NULL default '', /* MySQL INTERVAL unit codename */
  `Title` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `/* TABLE_PREFIX */interaction_stat_periods_units` (`Id`, `Code`, `Title`) VALUES
(1, 'SECOND', 'секунды'),
(2, 'MINUTE', 'минуты'),
(3, 'HOUR', 'часы'),
(4, 'DAY', 'дни'),
(5, 'WEEK', 'недели'),
(6, 'MONTH', 'месяцы'),
(7, 'QUARTER', 'кварталы'),
(8, 'YEAR', 'года');

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */interaction_stat_periods` (
  `Id` int(11) NOT NULL auto_increment,
  `UnitId` int(11) NOT NULL default '0', /* единицы измерения периода */
  `Period` int(11) NOT NULL default '0', /* период статистики в единицах Unit */
  `Title` varchar(255) NOT NULL default '',
  `IsDisabled` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `/* TABLE_PREFIX */interaction_stat_periods` (`Id`, `UnitId`, `Period`, `Title`, `IsDisabled`) VALUES
(1, 4, 1, 'день', 1),
(2, 5, 1, 'неделя', 1),
(3, 6, 1, 'месяц', 1),
(4, 8, 1, 'год', 1);

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */interaction_stat_items` (
  `Id` int(11) NOT NULL auto_increment,
  `SectionId` int(11) NOT NULL default '0',
  `ItemId` int(11) NOT NULL default '0',
  `PeriodId` int(11) NOT NULL default '0',
  
  `DateComment` datetime NOT NULL default '0000-00-00 00:00:00',
  `DateCommentUpdate` datetime NOT NULL default '0000-00-00 00:00:00', /* дата последнего обновления этой статистики */
  `CommentsCnt` int(11) NOT NULL default '0',
  `CommentsStatusAvg` float(11,4) NOT NULL default '0', /* среднее значение статуса комментариев оставленных пользователем */
  
  `DateRate` datetime NOT NULL default '0000-00-00 00:00:00',
  `DateRateUpdate` datetime NOT NULL default '0000-00-00 00:00:00', /* дата последнего обновления этой статистики */
  `RatesCnt` int(11) NOT NULL default '0',
  `Rating` float(11,4) NOT NULL default '0',
  
  PRIMARY KEY  (`Id`),
  KEY `SectionItem` (`SectionId`,`ItemId`),
  KEY `PeriodId` (`PeriodId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */interaction_stat_users` (
  `Id` int(11) NOT NULL auto_increment,
  `UserId` int(11) NOT NULL default '0',
  `PeriodId` int(11) NOT NULL default '0',
  
  `DateComment` datetime NOT NULL default '0000-00-00 00:00:00', /* дата последнего комментария оставленного пользователем */
  `DateCommentUpdate` datetime NOT NULL default '0000-00-00 00:00:00', /* дата последнего обновления этой статистики */
  `CommentsCnt` int(11) NOT NULL default '0', /* количество комментариев оставленных пользователем */
  `CommentsStatusAvg` float(11,4) NOT NULL default '0', /* среднее значение статуса комментариев оставленных пользователем */
  
  `DateCommentRate` datetime NOT NULL default '0000-00-00 00:00:00', /* дата последней отметки проставленной другими пользователями на комментарии пользователя */
  `DateCommentRateUpdate` datetime NOT NULL default '0000-00-00 00:00:00', /* дата последнего обновления этой статистики */
  `CommentsRatesCnt` int(11) NOT NULL default '0', /* количество отметок проставленных другими пользователями на комментарии пользователя */
  `CommentsRateAvg` float(11,4) NOT NULL default '0', /* средняя отметка проставляемая другими пользователями на комментарии пользователя */
  `CommentsRating` float(11,4) NOT NULL default '0', /* общий рейтинг всех комментариев пользователя по отметкам других пользователей */
  
  `DateRateComment` datetime NOT NULL default '0000-00-00 00:00:00', /* дата последней отметки проставленно пользователем другим комментариям */
  `DateRateCommentUpdate` datetime NOT NULL default '0000-00-00 00:00:00', /* дата последнего обновления этой статистики */
  `RatesCommentsCnt` int(11) NOT NULL default '0', /* количество отметок проставленных пользователем другим комментариям */
  `RateCommentsAvg` float(11,4) NOT NULL default '0', /* средняя отметка проставляемая пользователем другим комментариям */
  
  `DateRate` datetime NOT NULL default '0000-00-00 00:00:00', /* дата последнего голоса */
  `DateRateUpdate` datetime NOT NULL default '0000-00-00 00:00:00', /* дата последнего обновления этой статистики */
  `RatesCnt` int(11) NOT NULL default '0', /* количество голосов сделанных пользователем */
  `RateAvg` float(11,4) NOT NULL default '0', /* среднее значение голосов пользователя */
  
  PRIMARY KEY  (`Id`),
  KEY `UserId` (`UserId`),
  KEY `PeriodId` (`PeriodId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */interaction_poststack` (
  `Id` int(11) NOT NULL auto_increment,
  `UserId` int(11) NOT NULL default '0',
  `SectionId` int(11) NOT NULL default '0',
  `ItemId` int(11) NOT NULL default '0',
  `CommentId` int(11) NOT NULL default '0',
  `PostType` tinyint(1) NOT NULL default '0', /* 0 - comment, 1 - rate, 2 - comment rate */
  `IP` varchar(15) NOT NULL default '',
  `DatePost` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`Id`),
  KEY `SectionItem` (`SectionId`,`ItemId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */interaction_blacklist` (
  `Id` int(11) NOT NULL auto_increment,
  `DateCreate` datetime NOT NULL default '0000-00-00 00:00:00',
  `IP` varchar(15) NOT NULL default '',
  PRIMARY KEY  (`Id`),
  KEY `IP` (`IP`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `interaction_images` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `SectionId` int(11) NOT NULL DEFAULT '0',
  `ItemId` int(11) NOT NULL DEFAULT '0',
  `CommentId` int(11) NOT NULL DEFAULT '0',
  `Thumbnail` varchar(255) NOT NULL DEFAULT '',
  `Image` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`Id`),
  KEY `ItemId` (`ItemId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

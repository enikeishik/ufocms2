CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */board_sections` (
  `Id` int(11) NOT NULL auto_increment,
  `SectionId` int(11) NOT NULL default '0',
  `BodyHead` text NOT NULL,
  `BodyFoot` text NOT NULL,
  `PageLength` int(11) NOT NULL default '10',
  `Orderby` tinyint(1) NOT NULL default '0',
  `IsModerated` tinyint(1) NOT NULL default '0',
  `IsReferer` tinyint(1) NOT NULL default '0',
  `IsCaptcha` tinyint(1) NOT NULL default '0',
  `MessageMaxLen` int(11) NOT NULL default '1048576',
  `AutoClear` int(11) NOT NULL default '30',
  `AlertEmail` varchar(255) NOT NULL default '',
  `AlertEmailSubj` varchar(255) NOT NULL default '',
  `AlertEmailBody` text NOT NULL,
  `PostMessage` text NOT NULL,
  `PostMessageErr` text NOT NULL,
  `PostMessageBad` text NOT NULL,
  PRIMARY KEY  (`Id`),
  KEY `SectionId` (`SectionId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */board` (
  `Id` int(11) NOT NULL auto_increment,
  `UserId` int(11) NOT NULL default '0',
  `SectionId` int(11) NOT NULL default '0',
  `DateCreate` datetime NOT NULL default '0000-00-00 00:00:00',
  `IsHidden` tinyint(1) NOT NULL default '0',
  `ViewedCnt` int(11) NOT NULL default '0',
  `IP` varchar(15) NOT NULL default '',
  `Title` varchar(255) NOT NULL default '',
  `Message` text NOT NULL,
  `Contacts` text NOT NULL,
  PRIMARY KEY  (`Id`),
  KEY `SHD` (`SectionId`, `IsHidden`, `DateCreate`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `/* TABLE_PREFIX */modules` (`id`, `muid`, `mname`, `mfile`, `mfileins`, `mfileinc`, `mfilexsm`, `mtable`, `mtableitems`, `madmin`, `issingle`, `isinsertion`, `isinclusion`, `isenabled`) 
VALUES (4, 4, 'Доска объявлений', 'mod_board.php', 'ins_board.php', 'inc_board.php', 'xsm_board.php', 'board_sections', 'board', 'mod_board', 0, 1, 1, 1);

INSERT INTO `/* TABLE_PREFIX */widgets_types`
(ModuleId, Name, Title, Description) VALUES
(4, '', 'Объявления', 'Вывод элементов из разделов типа Доска объявлений'),
(4, 'Form', 'Форма подачи объявления', 'Вывод формы для подачи объявления в разделы типа Доска объявлений');

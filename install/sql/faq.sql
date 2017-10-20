CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */faq_sections` (
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
  `AlertEmail` varchar(255) NOT NULL default '',
  `AlertEmailSubj` varchar(255) NOT NULL default '',
  `AlertEmailBody` text NOT NULL,
  `PostMessage` text NOT NULL,
  `PostMessageErr` text NOT NULL,
  `PostMessageBad` text NOT NULL,
  PRIMARY KEY  (`Id`),
  KEY `SectionId` (`SectionId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */faq` (
  `Id` int(11) NOT NULL auto_increment,
  `UserId` int(11) NOT NULL default '0',
  `SectionId` int(11) NOT NULL default '0',
  `DateCreate` datetime NOT NULL default '0000-00-00 00:00:00',
  `DateAnswer` datetime NOT NULL default '0000-00-00 00:00:00',
  `IsHidden` tinyint(1) NOT NULL default '0',
  `UIP` varchar(15) NOT NULL default '',
  `USign` varchar(255) NOT NULL default '',
  `UEmail` varchar(255) NOT NULL default '',
  `UUrl` varchar(255) NOT NULL default '',
  `UMessage` text NOT NULL,
  `AIP` varchar(15) NOT NULL default '',
  `ASign` varchar(255) NOT NULL default '',
  `AEmail` varchar(255) NOT NULL default '',
  `AUrl` varchar(255) NOT NULL default '',
  `AMessage` text NOT NULL,
  PRIMARY KEY  (`Id`),
  KEY `SHD` (`SectionId`, `IsHidden`, `DateCreate`),
  KEY `SHA` (`SectionId`, `IsHidden`, `DateAnswer`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `/* TABLE_PREFIX */modules` (`id`, `muid`, `mname`, `mfile`, `mfileins`, `mfileinc`, `mtable`, `mtableitems`, `madmin`, `issingle`, `isinsertion`, `isinclusion`, `isenabled`) 
VALUES (6, 6, 'Вопрос-ответ', 'mod_faq.php', 'ins_faq.php', 'inc_faq.php', 'faq_sections', 'faq', 'mod_faq', 0, 1, 1, 1);

INSERT INTO `/* TABLE_PREFIX */widgets_types`
(ModuleId, Name, Title, Description) VALUES
(6, '', 'Вопрос-ответ', 'Вывод элементов из разделов типа Вопрос-ответ'),
(6, 'Form', 'Форма подачи вопроса', 'Вывод формы для подачи вопроса в разделы типа Вопрос-ответ');

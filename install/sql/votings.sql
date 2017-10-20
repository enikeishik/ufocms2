CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */votings_settings` (
  `Id` int(11) NOT NULL auto_increment,
  `SectionId` int(11) NOT NULL default '0',
  `BodyHead` text NOT NULL,
  `BodyFoot` text NOT NULL,
  `PageLength` int(11) NOT NULL default '10',
  `Orderby` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`Id`),
  KEY `SectionId` (`SectionId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */votings` (
  `Id` int(11) NOT NULL auto_increment,
  `SectionId` int(11) NOT NULL default '0',
  `DateCreate` datetime NOT NULL default '0000-00-00 00:00:00',
  `DateStart` datetime NOT NULL default '0000-00-00 00:00:00',
  `DateStop` datetime NOT NULL default '0000-00-00 00:00:00',
  `IsDisabled` tinyint(1) NOT NULL default '0',
  `IsClosed` tinyint(1) NOT NULL default '0',
  `IsMultianswer` tinyint(1) NOT NULL default '0', /* мультиответ (более одного ответа) */
  `AnswersLimit` tinyint(1) NOT NULL default '3', /* макс. количество ответов при мультиответе */
  `AnswersSeparate` tinyint(1) NOT NULL default '0', /* ответы стоят отдельно, каждый со своей кнопкой "проголосовать" */
  `CheckReferer` tinyint(1) NOT NULL default '1',
  `CheckTicket` tinyint(1) NOT NULL default '1',
  `CheckCookie` tinyint(1) NOT NULL default '1',
  `CheckIP` tinyint(1) NOT NULL default '0',
  `CheckUser` tinyint(1) NOT NULL default '0', /* проверка зарегистрированных пользователей, голосовать могут только зарегистрированные пользователи */
  `CheckCaptcha` tinyint(1) NOT NULL default '0',
  `ResultsDisplay` tinyint(1) NOT NULL default '0', /* -1 - before vote, 0 - after vote, 1 - after voting close */
  `VotesCnt` int(11) NOT NULL default '0',
  `Title` varchar(255) NOT NULL default '',
  `Image` varchar(255) NOT NULL default '',
  `Description` text NOT NULL,
 PRIMARY KEY  (`Id`),
 KEY `SDCSS` (`SectionId`, `IsDisabled`, `IsClosed`, `DateStart`, `DateStop`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */votings_answers` (
  `Id` int(11) NOT NULL auto_increment,
  `SectionId` int(11) NOT NULL default '0',
  `VotingId` int(11) NOT NULL default '0',
  `OrderNumber` int(11) NOT NULL default '0',
  `VotesCnt` int(11) NOT NULL default '0',
  `Title` varchar(255) NOT NULL default '',
  `Image` varchar(255) NOT NULL default '',
  `Description` text NOT NULL,
 PRIMARY KEY  (`Id`),
 KEY `VO` (`VotingId`, `OrderNumber`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */votings_log` (
  `Id` int(11) NOT NULL auto_increment,
  `UserId` int(11) NOT NULL default '0',
  `SectionId` int(11) NOT NULL default '0',
  `VotingId` int(11) NOT NULL default '0',
  `AnswerId` int(11) NOT NULL default '0',
  `DateCreate` datetime NOT NULL default '0000-00-00 00:00:00',
  `IP` int(10) unsigned NOT NULL default '0',
  `UA` varchar(255) NOT NULL default '',
 PRIMARY KEY  (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


INSERT INTO `/* TABLE_PREFIX */modules` (`id`, `muid`, `mname`, `mfile`, `mfileins`, `mfileinc`, `mtable`, `mtableitems`, `madmin`, `issingle`, `isinsertion`, `isinclusion`, `isenabled`) 
VALUES (5, 5, 'Голосования', 'mod_votings.php', '', '', 'votings_settings', 'votings,votings_questions,votings_answers', 'mod_votings', 0, 0, 0, 1);

INSERT INTO `/* TABLE_PREFIX */widgets_types`
(ModuleId, Name, Title, Description) VALUES
(5, '', 'Голосования', 'Вывод формы или результатов голосования');

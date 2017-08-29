CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */sendforms` (
  `Id` int(11) NOT NULL auto_increment,
  `DateCreate` datetime NOT NULL default '0000-00-00 00:00:00',
  `Status` tinyint(1) NOT NULL default '0',
  `Url` varchar(255) NOT NULL default '',
  `IP` varchar(15) NOT NULL default '',
  `Form` text NOT NULL,
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

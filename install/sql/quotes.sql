CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */quotes_groups` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */quotes` (
  `id` int(11) NOT NULL auto_increment,
  `groupid` int(11) NOT NULL default '0',
  `quote` text NOT NULL,
  `disabled` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `gd` (`groupid`,`disabled`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

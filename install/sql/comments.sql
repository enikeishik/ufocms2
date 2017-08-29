CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */comments` (
  `id` int(11) NOT NULL auto_increment,
  `userid` int(11) NOT NULL default '0',
  `url` varchar(255) NOT NULL default '',
  `dtm` datetime NOT NULL default '0000-00-00 00:00:00',
  `ip` varchar(15) NOT NULL default '',
  `info` text NOT NULL,
  `comment` text NOT NULL,
  `comment_sign` varchar(255) NOT NULL default '',
  `comment_email` varchar(255) NOT NULL default '',
  `comment_url` varchar(255) NOT NULL default '',
  `answer` text NOT NULL,
  `answer_sign` varchar(255) NOT NULL default '',
  `answer_email` varchar(255) NOT NULL default '',
  `answer_url` varchar(255) NOT NULL default '',
  `rate` int(11) NOT NULL default '0',
  `disabled` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `url` (`url`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */comments_rating` (
  `id` int(11) NOT NULL auto_increment,
  `url` varchar(255) NOT NULL default '',
  `val` float(11,6) NOT NULL default '0',
  `cnt` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `url` (`url`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */comments_blacklist` (
  `id` int(11) NOT NULL auto_increment,
  `url` varchar(255) NOT NULL default '',
  `ip` varchar(15) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `urlip` (`url`,`ip`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

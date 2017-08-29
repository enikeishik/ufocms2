CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */mainpage` (
  `id` int(11) NOT NULL auto_increment,
  `body` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `/* TABLE_PREFIX */modules` (`id`, `muid`, `mname`, `mfile`, `mfileins`, `mfileinc`, `mtable`, `mtableitems`, `madmin`, `issingle`, `isinsertion`, `isinclusion`, `isenabled`) 
VALUES (-1, -1, 'Главная страница', 'mod_mainpage.php', '', '', 'mainpage', '', 'mod_mainpage', 1, 0, 0, 1);

INSERT INTO `/* TABLE_PREFIX */sections` (`id`, `topid`, `parentid`, `orderid`, `levelid`, `isparent`, `moduleid`, `designid`, `mask`, `path`, `image`, `timage`, `indic`, `title`, `metadesc`, `metakeys`, `isenabled`, `insearch`, `inmenu`, `inlinks`, `inmap`, `shtitle`, `shmenu`, `shlinks`, `flsearch`, `flcache`) VALUES
(-1, -1, -1, -1, -1, 1, -1, -1, '', '/', '', '', 'Главная', 'Главная страница', '', '', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);

INSERT INTO `/* TABLE_PREFIX */mainpage` (`id`, `body`) VALUES (1, '<p>Главная страница.</p>');

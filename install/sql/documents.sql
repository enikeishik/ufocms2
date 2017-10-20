CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */documents` (
  `Id` int(11) NOT NULL auto_increment,
  `SectionId` int(11) NOT NULL default '0',
  `PageId` int(11) NOT NULL default '1',
  `Body` text NOT NULL,
  PRIMARY KEY  (`Id`),
  KEY `SectionId` (`SectionId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `/* TABLE_PREFIX */modules` (`id`, `muid`, `mname`, `mfile`, `mfileins`, `mfileinc`, `mfilexsm`, `mtable`, `mtableitems`, `madmin`, `issingle`, `isinsertion`, `isinclusion`, `isenabled`) 
VALUES (1, 1, 'Документы', 'mod_documents.php', 'ins_documents.php', '', 'xsm_documents.php', 'documents', 'documents', 'mod_documents', 0, 1, 0, 1);

INSERT INTO `/* TABLE_PREFIX */widgets_types`
(ModuleId, Name, Title, Description) VALUES
(1, '', 'Документы', 'Вывод части содержимого текстовых разделов');

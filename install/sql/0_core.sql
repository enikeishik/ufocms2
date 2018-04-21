CREATE TABLE `/* TABLE_PREFIX */inclusions` (
  `Id` int(11) NOT NULL auto_increment,
  `TargetId` int(11) NOT NULL default '0',
  `PlaceId` int(11) NOT NULL default '0',
  `OrderId` int(11) NOT NULL default '0',
  `SourceId` int(11) NOT NULL default '0',
  `Title` varchar(255) NOT NULL default '',
  `Options` text NOT NULL,
  PRIMARY KEY  (`Id`),
  KEY `TargetPlace` (`TargetId`,`PlaceId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `/* TABLE_PREFIX */insertions` (
  `Id` int(11) NOT NULL auto_increment,
  `TargetId` int(11) NOT NULL default '0',
  `PlaceId` int(11) NOT NULL default '0',
  `OrderId` int(11) NOT NULL default '0',
  `SourceId` int(11) NOT NULL default '0',
  `SourcesIds` varchar(255) NOT NULL default '',
  `Title` varchar(255) NOT NULL default '',
  `ItemsIds` varchar(255) NOT NULL default '',
  `ItemsStart` int(11) NOT NULL default '0',
  `ItemsCount` int(11) NOT NULL default '0',
  `ItemsLength` int(11) NOT NULL default '0',
  `ItemsStartMark` varchar(255) NOT NULL default '',
  `ItemsStopMark` varchar(255) NOT NULL default '',
  `ItemsOptions` text NOT NULL,
  PRIMARY KEY  (`Id`),
  KEY `TargetPlace` (`TargetId`,`PlaceId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `/* TABLE_PREFIX */metatags` (
  `id` int(11) NOT NULL auto_increment,
  `sectionid` int(11) NOT NULL default '0',
  `itemid` int(11) NOT NULL default '0',
  `metaname` varchar(255) NOT NULL default '',
  `metavalue` varchar(255) NOT NULL default '',
  `ishttp` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `/* TABLE_PREFIX */modules` (
  `id` int(11) NOT NULL auto_increment,
  `muid` int(11) NOT NULL default '0',
  `mname` varchar(255) NOT NULL default '',
  `mfile` varchar(255) NOT NULL default '',
  `mfileins` varchar(255) NOT NULL default '',
  `mfileinc` varchar(255) NOT NULL default '',
  `mfilexsm` varchar(255) NOT NULL default '',
  `mtable` varchar(255) NOT NULL default '',
  `mtableitems` varchar(255) NOT NULL default '',
  `madmin` varchar(255) NOT NULL default '',
  `issingle` tinyint(1) NOT NULL default '0',
  `isinsertion` tinyint(1) NOT NULL default '0',
  `isinclusion` tinyint(1) NOT NULL default '0',
  `isenabled` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `muid` (`muid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `/* TABLE_PREFIX */sections` (
  `id` int(11) NOT NULL auto_increment,
  `topid` int(11) NOT NULL default '0',
  `parentid` int(11) NOT NULL default '0',
  `orderid` int(11) NOT NULL default '0',
  `levelid` int(11) NOT NULL default '0',
  `isparent` tinyint(1) NOT NULL default '0',
  `moduleid` int(11) NOT NULL default '0',
  `designid` int(11) NOT NULL default '0',
  `mask` varchar(255) NOT NULL default '',
  `path` varchar(255) NOT NULL default '',
  `image` varchar(255) NOT NULL default '',
  `timage` varchar(255) NOT NULL default '',
  `indic` varchar(255) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `metadesc` varchar(255) NOT NULL default '',
  `metakeys` varchar(255) NOT NULL default '',
  `isenabled` tinyint(1) NOT NULL default '0',
  `insearch` tinyint(1) NOT NULL default '0',
  `inmenu` tinyint(1) NOT NULL default '0',
  `inlinks` tinyint(1) NOT NULL default '0',
  `inmap` tinyint(1) NOT NULL default '0',
  `shtitle` tinyint(1) NOT NULL default '0',
  `shmenu` tinyint(1) NOT NULL default '0',
  `shlinks` tinyint(1) NOT NULL default '0',
  `shcomments` tinyint(1) NOT NULL default '0',
  `shrating` tinyint(1) NOT NULL default '0',
  `flsearch` int(11) NOT NULL default '0',
  `flcache` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `mask` (`mask`),
  KEY `path` (`path`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `/* TABLE_PREFIX */siteparams` (
  `Id` int(11) NOT NULL auto_increment,
  `POrder` int(11) NOT NULL default '0',
  `PType` int(11) NOT NULL default '0',
  `PGroup` varchar(255) NOT NULL default '',
  `PName` varchar(255) NOT NULL default '',
  `PValue` text NOT NULL,
  `PDefault` text NOT NULL,
  `PDescription` text NOT NULL,
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `/* TABLE_PREFIX */siteparams` (`Id`, `POrder`, `PType`, `PGroup`, `PName`, `PValue`, `PDefault`, `PDescription`) VALUES
(1, 1, 202, 'Общие параметры', 'SiteUrl', '', '', 'Адрес сайта'),
(2, 2, 202, 'Общие параметры', 'SiteEMail', '', '', 'Общий электронный почтовый ящик'),
(3, 3, 202, 'Общие параметры', 'SiteTitle', '', '', 'Общий заголовок сайта'),
(4, 4, 202, 'Общие параметры', 'SiteMetaDescription', '', '', 'Общее мета описание сайта'),
(5, 5, 202, 'Общие параметры', 'SiteMetaKeywords', '', '', 'Общие мета ключевые слова сайта'),
(6, 6, 202, 'Общие параметры', 'SiteEMailFrom', '', '', 'Электронный почтовый адрес, указываемый в поле From автоматических рассылок'),
(7, 7, 202, 'Общие параметры', 'SiteCopyright', '', '', 'Общий копирайт сайта'),
(8, 1, 202, 'Sendform', 'SendformSubj', 'Отправлена форма со страницы {REFERER}', 'Form was sended', 'Тема письма-уведомления'),
(9, 2, 202, 'Sendform', 'SendformBody', 'Отправлена форма со страницы {REFERER}<br>Адрес отправителя {IP}<hr>{FORM}', 'Form was sended', 'Сообщение в письме-уведомлении'),
(10, 3, 202, 'Sendform', 'SendformEMail1', '', '', 'Дополнительный адрес отправки форм');

CREATE TABLE `/* TABLE_PREFIX */users_params` (
  `Id` int(11) NOT NULL auto_increment,
  `BodyHead` text NOT NULL,
  `BodyFoot` text NOT NULL,
  `PageLength` int(11) NOT NULL default '10',
  `Orderby` tinyint(1) NOT NULL default '0',
  `IsModerated` tinyint(1) NOT NULL default '0',
  `IsGlobalAE` tinyint(1) NOT NULL default '1',
  `IsGlobalAEF` tinyint(1) NOT NULL default '1',
  `AdminEmail` varchar(255) NOT NULL default '',
  `AdminEmailFrom` varchar(255) NOT NULL default '',
  `RecoverySubject` varchar(255) NOT NULL default 'Your password on site {SITE} is {PASSWORD}',
  `RecoveryMessage` text NOT NULL,
  `ImageWidth` int(11) NOT NULL default '100',
  `ImageHeight` int(11) NOT NULL default '100',
  `ImageMaxSize` int(11) NOT NULL default '102400',
  `ImageTypes` varchar(255) NOT NULL default 'jpg,jpeg,gif,png',
  `UploadDir` varchar(255) NOT NULL default '/upload/users',
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;
/* AUTO_INCREMENT=2 because we already have Id=-1 as template values and Id=1 as real values */

INSERT INTO `/* TABLE_PREFIX */users_params` (`Id`, `BodyHead`, `BodyFoot`, `PageLength`, `Orderby`, `IsModerated`, `IsGlobalAE`, `IsGlobalAEF`, `AdminEmail`, `AdminEmailFrom`, `RecoverySubject`, `RecoveryMessage`) VALUES
(-1, '', '', 10, 0, 0, 1, 1, '', '', 'Password recovery on site {SITE}', 'Your password on site {SITE} is {PASSWORD}'),
(1, '', '', 10, 0, 0, 1, 1, '', '', 'Password recovery on site {SITE}', 'Your password on site {SITE} is {PASSWORD}');

CREATE TABLE `/* TABLE_PREFIX */users` (
  `Id` int(11) NOT NULL auto_increment,
  `DateCreate` datetime NOT NULL default '0000-00-00 00:00:00',
  `DateLogin` datetime NOT NULL default '0000-00-00 00:00:00',
  `EntryCounter` int(11) NOT NULL default '0',
  `IsDisabled` tinyint(1) NOT NULL default '0',
  `IsHidden` tinyint(1) NOT NULL default '0',
  `Ticket` varchar(32) NOT NULL default '', /* WARNING: length 32 is according to md5 hash, change this if hashing will changed */
  `ExtUID` varchar(255) NOT NULL default '',
  `Login` varchar(255) NOT NULL default '',
  `Password` varchar(255) NOT NULL default '',
  `Title` varchar(255) NOT NULL default '',
  `Image` varchar(255) NOT NULL default '',
  `Email` varchar(255) NOT NULL default '',
  `Description` text NOT NULL,
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `/* TABLE_PREFIX */users` (`Id`, `DateCreate`, `DateLogin`, `EntryCounter`, `IsDisabled`, `IsHidden`, `Ticket`, `ExtUID`, `Login`, `Password`, `Title`, `Image`, `Email`, `Description`) VALUES
(-1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0, 0, '', '', 'admin', 'admin', '', '', '', 'Default administrator');

CREATE TABLE `/* TABLE_PREFIX */users_attributes` (
  `Id` int(11) NOT NULL auto_increment,
  `OrderId` int(11) NOT NULL default '0',
  `Title` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `/* TABLE_PREFIX */users_attributes_values` (
  `Id` int(11) NOT NULL auto_increment,
  `UserId` int(1) NOT NULL default '0',
  `AttributeId` int(1) NOT NULL default '0',
  `StringValue` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `/* TABLE_PREFIX */users_groups` (
  `Id` int(11) NOT NULL auto_increment,
  `OrderId` int(11) NOT NULL default '0',
  `Title` varchar(255) NOT NULL default '',
  `Description` text NOT NULL,
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `/* TABLE_PREFIX */users_groups_relations` (
  `Id` int(11) NOT NULL auto_increment,
  `UserId` int(11) NOT NULL default '0',
  `GroupId` int(11) NOT NULL default '0',
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `/* TABLE_PREFIX */widgets` (
  `Id` int(11) NOT NULL auto_increment,
  `TypeId` int(11) NOT NULL default '0',
  `PlaceId` int(11) NOT NULL default '0',
  `OrderId` int(11) NOT NULL default '0',
  `IsDisabled` tinyint(1) NOT NULL default '0',
  `ShowTitle` tinyint(1) NOT NULL default '0',
  `SrcSections` varchar(255) NOT NULL default '', /* идентификаторы разделов-источников, can contain only 41 elements with 5 digits for id */
  `SrcItems` varchar(255) NOT NULL default '', /* идентификаторы конкретных элементов-источников разделов-источников */
  `Title` varchar(255) NOT NULL default '',
  `Description` varchar(255) NOT NULL default '',
  `Content` text NOT NULL,
  `Params` text NOT NULL,
  `ContentExpiry` int(11) NOT NULL default '0',
  `ContentCreated` datetime NOT NULL default '0000-00-00 00:00:00',
  `ContentHash` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`Id`),
  KEY `TypeId` (`TypeId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `/* TABLE_PREFIX */widgets_targets` (
  `Id` int(11) NOT NULL auto_increment,
  `WidgetId` int(11) NOT NULL default '0',
  `SectionId` int(11) NOT NULL default '0',
  `ItemId` int(11) NOT NULL default '0', /* 0 - all section pages, -1 - main section page only, N - some one section page */
  PRIMARY KEY  (`Id`),
  KEY `WidgetId` (`WidgetId`),
  KEY `SectionId` (`SectionId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `/* TABLE_PREFIX */widgets_types` (
  `Id` int(11) NOT NULL auto_increment,
  `ModuleId` int(11) NOT NULL default '0',
  `Name` varchar(255) NOT NULL default '', /* name of class */
  `Title` varchar(255) NOT NULL default '',
  `Description` text NOT NULL,
  PRIMARY KEY  (`Id`),
  KEY `ModuleId` (`ModuleId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `/* TABLE_PREFIX */widgets_types`
(ModuleId, Name, Title, Description) VALUES
(0, 'Text', 'Текст (HTML)', 'Самостоятельный текстовый (HTML) блок без привязки к разделу'),
(0, 'Slideshow', 'Показ слайдов', 'Блок показа слайдов из папки с изображениями, без привязки к разделу'),
(0, 'WeatherGismeteo', 'Погода от Gismeteo.ru', 'Блок показа информера погоды от Gismeteo.ru'),
(0, 'WeatherYandex', 'Погода от Яндекса', 'Блок показа информера погоды от Яндекса'),
(0, 'TrafficYandex', 'Пробки от Яндекса', 'Блок показа информера пробок от Яндекса'),
(0, 'CurrencyCbrf', 'Курсы валют от Банка России', 'Блок показа курсов валют от Банка России'),
(0, 'DayEvents', 'События дня', 'Блок показа событий вчерашнего, текущего, завтрашнего дня'),
(0, 'Coinmarketcap', 'Курсы криптовалют', 'Курсы основных криптовалют и токенов от coinmarketcap.com');


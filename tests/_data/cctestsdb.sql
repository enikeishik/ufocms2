CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */inclusions` (
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
TRUNCATE TABLE `/* TABLE_PREFIX */inclusions`;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */insertions` (
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
TRUNCATE TABLE `/* TABLE_PREFIX */insertions`;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */metatags` (
  `id` int(11) NOT NULL auto_increment,
  `sectionid` int(11) NOT NULL default '0',
  `itemid` int(11) NOT NULL default '0',
  `metaname` varchar(255) NOT NULL default '',
  `metavalue` varchar(255) NOT NULL default '',
  `ishttp` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
TRUNCATE TABLE `/* TABLE_PREFIX */metatags`;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */modules` (
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
TRUNCATE TABLE `/* TABLE_PREFIX */modules`;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */sections` (
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
TRUNCATE TABLE `/* TABLE_PREFIX */sections`;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */siteparams` (
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
TRUNCATE TABLE `/* TABLE_PREFIX */siteparams`;

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

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */users_params` (
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
TRUNCATE TABLE `/* TABLE_PREFIX */users_params`;

INSERT INTO `/* TABLE_PREFIX */users_params` (`Id`, `BodyHead`, `BodyFoot`, `PageLength`, `Orderby`, `IsModerated`, `IsGlobalAE`, `IsGlobalAEF`, `AdminEmail`, `AdminEmailFrom`, `RecoverySubject`, `RecoveryMessage`) VALUES
(-1, '', '', 10, 0, 0, 1, 1, '', '', 'Password recovery on site {SITE}', 'Your password on site {SITE} is {PASSWORD}'),
(1, '', '', 10, 0, 0, 1, 1, '', '', 'Password recovery on site {SITE}', 'Your password on site {SITE} is {PASSWORD}');

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */users` (
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
TRUNCATE TABLE `/* TABLE_PREFIX */users`;

INSERT INTO `/* TABLE_PREFIX */users` (`Id`, `DateCreate`, `DateLogin`, `EntryCounter`, `IsDisabled`, `IsHidden`, `Ticket`, `ExtUID`, `Login`, `Password`, `Title`, `Image`, `Email`, `Description`) VALUES
(-1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0, 0, '', '', 'admin', 'admin', '', '', '', 'Default administrator');

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */users_attributes` (
  `Id` int(11) NOT NULL auto_increment,
  `OrderId` int(11) NOT NULL default '0',
  `Title` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
TRUNCATE TABLE `/* TABLE_PREFIX */users_attributes`;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */users_attributes_values` (
  `Id` int(11) NOT NULL auto_increment,
  `UserId` int(1) NOT NULL default '0',
  `AttributeId` int(1) NOT NULL default '0',
  `StringValue` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
TRUNCATE TABLE `/* TABLE_PREFIX */users_attributes_values`;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */users_groups` (
  `Id` int(11) NOT NULL auto_increment,
  `OrderId` int(11) NOT NULL default '0',
  `Title` varchar(255) NOT NULL default '',
  `Description` text NOT NULL,
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
TRUNCATE TABLE `/* TABLE_PREFIX */users_groups`;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */users_groups_relations` (
  `Id` int(11) NOT NULL auto_increment,
  `UserId` int(11) NOT NULL default '0',
  `GroupId` int(11) NOT NULL default '0',
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
TRUNCATE TABLE `/* TABLE_PREFIX */users_groups_relations`;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */widgets` (
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
TRUNCATE TABLE `/* TABLE_PREFIX */widgets`;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */widgets_targets` (
  `Id` int(11) NOT NULL auto_increment,
  `WidgetId` int(11) NOT NULL default '0',
  `SectionId` int(11) NOT NULL default '0',
  `ItemId` int(11) NOT NULL default '0', /* 0 - all section pages, -1 - main section page only, N - some one section page */
  PRIMARY KEY  (`Id`),
  KEY `WidgetId` (`WidgetId`),
  KEY `SectionId` (`SectionId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
TRUNCATE TABLE `/* TABLE_PREFIX */widgets_targets`;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */widgets_types` (
  `Id` int(11) NOT NULL auto_increment,
  `ModuleId` int(11) NOT NULL default '0',
  `Name` varchar(255) NOT NULL default '', /* name of class */
  `Title` varchar(255) NOT NULL default '',
  `Description` text NOT NULL,
  PRIMARY KEY  (`Id`),
  KEY `ModuleId` (`ModuleId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
TRUNCATE TABLE `/* TABLE_PREFIX */widgets_types`;

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

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */users_roles` (
  `Id` int(11) NOT NULL auto_increment,
  `IsSystem` tinyint(1) NOT NULL default '0',
  `Title` varchar(255) NOT NULL default '',
  `Description` text NOT NULL,
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ; /* starts from 10, 1-9 reserved */
TRUNCATE TABLE `/* TABLE_PREFIX */users_roles`;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */users_roles_relations` (
  `Id` int(11) NOT NULL auto_increment,
  `UserId` int(11) NOT NULL default '0',
  `RoleId` int(11) NOT NULL default '0',
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
TRUNCATE TABLE `/* TABLE_PREFIX */users_roles_relations`;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */users_roles_perms_mods` (
  `Id` int(11) NOT NULL auto_increment,
  `RoleId` int(11) NOT NULL default '0',
  `ModuleId` int(11) NOT NULL default '0',
  `Permissions` text NOT NULL,
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
TRUNCATE TABLE `/* TABLE_PREFIX */users_roles_perms_mods`;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */users_roles_perms_core` (
  `Id` int(11) NOT NULL auto_increment,
  `RoleId` int(11) NOT NULL default '0',
  `CoreModule` varchar(255) NOT NULL default '',
  `Permissions` text NOT NULL,
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
TRUNCATE TABLE `/* TABLE_PREFIX */users_roles_perms_core`;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */users_roles_restrictions` (
  `Id` int(11) NOT NULL auto_increment,
  `RoleId` int(11) NOT NULL default '0',
  `CoreModules` text NOT NULL,
  `Modules` text NOT NULL,
  `Sections` text NOT NULL,
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
TRUNCATE TABLE `/* TABLE_PREFIX */users_roles_restrictions`;

INSERT INTO `/* TABLE_PREFIX */users_roles`
(`Id`, `IsSystem`, `Title`, `Description`) VALUES
(1, 1, 'Администратор', 'Полные права'),
(2, 1, 'Редактор', 'Права на изменение, отключение/включение и удаление записей'),
(3, 1, 'Модератор', 'Права на отключение/включение записей'),
(4, 1, 'Журналист', 'Права на создание и редактирование записей, без возможности отключения/включения'),
(5, 1, 'Писатель', 'Права на создание и редактирование записей, с возможностью отключения/включения');

INSERT INTO `/* TABLE_PREFIX */users_roles_perms_mods`
(`RoleId`, `ModuleId`, `Permissions`) VALUES
(2, 0, '{"create":true,"edit":true,"disable":true,"enable":true,"delete":true}'),
(3, 0, '{"create":false,"edit":false,"disable":true,"enable":true,"delete":false}'),
(4, 0, '{"create":true,"edit":true,"disable":false,"enable":false,"delete":false}'),
(5, 0, '{"create":true,"edit":true,"disable":true,"enable":true,"delete":false}');

INSERT INTO `/* TABLE_PREFIX */users_roles_perms_core`
(`RoleId`, `CoreModule`, `Permissions`) VALUES
(2, '', '{"create":true,"edit":true,"disable":true,"enable":true,"delete":true}'),
(3, '', '{"create":false,"edit":false,"disable":true,"enable":true,"delete":false}'),
(4, '', '{"create":true,"edit":true,"disable":false,"enable":false,"delete":false}'),
(5, '', '{"create":true,"edit":true,"disable":true,"enable":true,"delete":false}');

INSERT INTO `/* TABLE_PREFIX */users_roles_restrictions`
(`RoleId`, `CoreModules`, `Modules`) VALUES
(2, 'widgets,quotes,interaction,comments,sendform,filemanager,xmlsitemap', ''),
(3, 'interaction,comments', '3,4,6,10,17'), /* гостевая, объявления, вопрос-ответ, объявления2, новости2 */
(4, 'none', '2,6,16,17,25'), /* новости, вопрос-ответ, лента документов, новости2, поздравления */
(5, 'none', '2,6,16,17,25'); /* новости, вопрос-ответ, лента документов, новости2, поздравления */

INSERT INTO `/* TABLE_PREFIX */users_roles_relations` (`Id`, `UserId`, `RoleId`) VALUES
(1, -1, 1);

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */auctions_settings` (
  `Id` int(11) NOT NULL auto_increment,
  `SectionId` int(11) NOT NULL default '0',
  `BodyHead` text NOT NULL,
  `BodyFoot` text NOT NULL,
  `PageLength` int(11) NOT NULL default '10',
  `UpdateTimeout` int(11) NOT NULL default '10',
  `UpdateType` tinyint(1) NOT NULL default '0', /* 0 - iframe, 1 - meta refresh, 2 - AJAX */
  PRIMARY KEY  (`Id`),
  KEY `SectionId` (`SectionId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
TRUNCATE TABLE `/* TABLE_PREFIX */auctions_settings`;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */auctions` (
  `Id` int(11) NOT NULL auto_increment,
  `UserId` int(11) NOT NULL default '0',
  `SectionId` int(11) NOT NULL default '0',
  `IsDisabled` tinyint(1) NOT NULL default '0',
  `IsClosed` tinyint(1) NOT NULL default '0',
  `DateCreate` datetime NOT NULL default '0000-00-00 00:00:00',
  `DatePublicate` datetime NOT NULL default '0000-00-00 00:00:00',
  `DateStart` datetime NOT NULL default '0000-00-00 00:00:00',
  `DateStop` datetime NOT NULL default '0000-00-00 00:00:00',
  `DateView` datetime NOT NULL default '0000-00-00 00:00:00',
  `Step` int(11) NOT NULL default '-100',
  `StepTime` int(11) NOT NULL default '600',
  `PriceStart` int(11) NOT NULL default '1000',
  `PriceStop` int(11) NOT NULL default '100',
  `PriceCurrent` int(11) NOT NULL default '1000',
  `ViewedCnt` int(11) NOT NULL default '0',
  `Title` varchar(255) NOT NULL default '',
  `Thumbnail` varchar(255) NOT NULL default '',
  `Image` varchar(255) NOT NULL default '',
  `ShortDesc` text NOT NULL,
  `FullDesc` text NOT NULL,
  PRIMARY KEY  (`Id`),
  KEY `SDPS` (`SectionId`, `IsDisabled`, `DatePublicate`, `DateStart`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
TRUNCATE TABLE `/* TABLE_PREFIX */auctions`;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */auctions_log` (
  `Id` int(11) NOT NULL auto_increment,
  `UserId` int(11) NOT NULL default '0',
  `SectionId` int(11) NOT NULL default '0',
  `AuctionId` int(11) NOT NULL default '0',
  `DateCreate` datetime NOT NULL default '0000-00-00 00:00:00',
  `PriceNew` int(11) NOT NULL default '0',
  `IsSuccess` tinyint(1) NOT NULL default '0',
  `Info` text NOT NULL,
  PRIMARY KEY  (`Id`),
  KEY `SectionId` (`SectionId`),
  KEY `AuctionId` (`AuctionId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
TRUNCATE TABLE `/* TABLE_PREFIX */auctions_log`;

INSERT INTO `/* TABLE_PREFIX */modules` (`id`, `muid`, `mname`, `mfile`, `mfileins`, `mfileinc`, `mfilexsm`, `mtable`, `mtableitems`, `madmin`, `issingle`, `isinsertion`, `isinclusion`, `isenabled`) 
VALUES (26, 26, 'Аукционы', 'mod_auctions.php', '', '', '', 'auctions_settings', 'auctions,auctions_log', 'mod_auctions', 0, 0, 0, 1);

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
TRUNCATE TABLE `/* TABLE_PREFIX */board_sections`;

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
TRUNCATE TABLE `/* TABLE_PREFIX */board`;

INSERT INTO `/* TABLE_PREFIX */modules` (`id`, `muid`, `mname`, `mfile`, `mfileins`, `mfileinc`, `mfilexsm`, `mtable`, `mtableitems`, `madmin`, `issingle`, `isinsertion`, `isinclusion`, `isenabled`) 
VALUES (4, 4, 'Доска объявлений', 'mod_board.php', 'ins_board.php', 'inc_board.php', 'xsm_board.php', 'board_sections', 'board', 'mod_board', 0, 1, 1, 1);

INSERT INTO `/* TABLE_PREFIX */widgets_types`
(ModuleId, Name, Title, Description) VALUES
(4, '', 'Объявления', 'Вывод элементов из разделов типа Доска объявлений'),
(4, 'Form', 'Форма подачи объявления', 'Вывод формы для подачи объявления в разделы типа Доска объявлений');

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
TRUNCATE TABLE `/* TABLE_PREFIX */comments`;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */comments_rating` (
  `id` int(11) NOT NULL auto_increment,
  `url` varchar(255) NOT NULL default '',
  `val` float(11,6) NOT NULL default '0',
  `cnt` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `url` (`url`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
TRUNCATE TABLE `/* TABLE_PREFIX */comments_rating`;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */comments_blacklist` (
  `id` int(11) NOT NULL auto_increment,
  `url` varchar(255) NOT NULL default '',
  `ip` varchar(15) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `urlip` (`url`,`ip`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
TRUNCATE TABLE `/* TABLE_PREFIX */comments_blacklist`;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */congrats_settings` (
  `Id` int(11) NOT NULL auto_increment,
  `SectionId` int(11) NOT NULL default '0',
  `BodyHead` text NOT NULL,
  `BodyFoot` text NOT NULL,
  `ShowDays` int(11) NOT NULL default '30',
  `PageLength` int(11) NOT NULL default '10',
  `Orderby` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`Id`),
  KEY `SectionId` (`SectionId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
TRUNCATE TABLE `/* TABLE_PREFIX */congrats_settings`;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */congrats_items` (
  `Id` int(11) NOT NULL auto_increment,
  `UserId` int(11) NOT NULL default '0',
  `SectionId` int(11) NOT NULL default '0',
  `DateCreate` datetime NOT NULL default '0000-00-00 00:00:00',
  `DateStart` datetime NOT NULL default '0000-00-00 00:00:00',
  `DateStop` datetime NOT NULL default '0000-00-00 00:00:00',
  `DateView` datetime NOT NULL default '0000-00-00 00:00:00',
  `IsDisabled` tinyint(1) NOT NULL default '0',
  `IsEternal` tinyint(1) NOT NULL default '0',
  `IsPinned` tinyint(1) NOT NULL default '0',
  `IsHighlighted` tinyint(1) NOT NULL default '0',
  `ViewedCnt` int(11) NOT NULL default '0',
  `Thumbnail` varchar(255) NOT NULL default '',
  `Image` varchar(255) NOT NULL default '',
  `ShortDesc` text NOT NULL,
  `FullDesc` text NOT NULL,
  PRIMARY KEY  (`Id`),
  KEY `SDPSS` (`SectionId`, `IsDisabled`, `IsPinned`, `DateStart`, `DateStop`),
  KEY `ViewedCnt` (`ViewedCnt`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
TRUNCATE TABLE `/* TABLE_PREFIX */congrats_items`;

INSERT INTO `/* TABLE_PREFIX */modules` (`id`, `muid`, `mname`, `mfile`, `mfileins`, `mfileinc`, `mfilexsm`, `mtable`, `mtableitems`, `madmin`, `issingle`, `isinsertion`, `isinclusion`, `isenabled`) 
VALUES (25, 25, 'Поздравления', 'mod_congrats.php', '', '', 'xsm_congrats.php', 'congrats_settings', 'congrats_items', 'mod_congrats', 0, 0, 0, 1);

INSERT INTO `/* TABLE_PREFIX */widgets_types`
(ModuleId, Name, Title, Description) VALUES
(25, '', 'Поздравления', 'Вывод элементов из раздела типа Поздравления');

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */documents` (
  `Id` int(11) NOT NULL auto_increment,
  `SectionId` int(11) NOT NULL default '0',
  `PageId` int(11) NOT NULL default '1',
  `Body` text NOT NULL,
  PRIMARY KEY  (`Id`),
  KEY `SectionId` (`SectionId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
TRUNCATE TABLE `/* TABLE_PREFIX */documents`;

INSERT INTO `/* TABLE_PREFIX */modules` (`id`, `muid`, `mname`, `mfile`, `mfileins`, `mfileinc`, `mfilexsm`, `mtable`, `mtableitems`, `madmin`, `issingle`, `isinsertion`, `isinclusion`, `isenabled`) 
VALUES (1, 1, 'Документы', 'mod_documents.php', 'ins_documents.php', '', 'xsm_documents.php', 'documents', 'documents', 'mod_documents', 0, 1, 0, 1);

INSERT INTO `/* TABLE_PREFIX */widgets_types`
(ModuleId, Name, Title, Description) VALUES
(1, '', 'Документы', 'Вывод части содержимого текстовых разделов');

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
TRUNCATE TABLE `/* TABLE_PREFIX */faq_sections`;

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
TRUNCATE TABLE `/* TABLE_PREFIX */faq`;

INSERT INTO `/* TABLE_PREFIX */modules` (`id`, `muid`, `mname`, `mfile`, `mfileins`, `mfileinc`, `mtable`, `mtableitems`, `madmin`, `issingle`, `isinsertion`, `isinclusion`, `isenabled`) 
VALUES (6, 6, 'Вопрос-ответ', 'mod_faq.php', 'ins_faq.php', 'inc_faq.php', 'faq_sections', 'faq', 'mod_faq', 0, 1, 1, 1);

INSERT INTO `/* TABLE_PREFIX */widgets_types`
(ModuleId, Name, Title, Description) VALUES
(6, '', 'Вопрос-ответ', 'Вывод элементов из разделов типа Вопрос-ответ'),
(6, 'Form', 'Форма подачи вопроса', 'Вывод формы для подачи вопроса в разделы типа Вопрос-ответ');

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
TRUNCATE TABLE `/* TABLE_PREFIX */interaction_comments`;

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
TRUNCATE TABLE `/* TABLE_PREFIX */interaction_comments_rates`;

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
TRUNCATE TABLE `/* TABLE_PREFIX */interaction_rates`;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */interaction_stat_periods_units` (
  `Id` int(11) NOT NULL auto_increment,
  `Code` varchar(255) NOT NULL default '', /* MySQL INTERVAL unit codename */
  `Title` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
TRUNCATE TABLE `/* TABLE_PREFIX */interaction_stat_periods_units`;

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
TRUNCATE TABLE `/* TABLE_PREFIX */interaction_stat_periods`;

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
TRUNCATE TABLE `/* TABLE_PREFIX */interaction_stat_items`;

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
TRUNCATE TABLE `/* TABLE_PREFIX */interaction_stat_users`;

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
TRUNCATE TABLE `/* TABLE_PREFIX */interaction_poststack`;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */interaction_blacklist` (
  `Id` int(11) NOT NULL auto_increment,
  `DateCreate` datetime NOT NULL default '0000-00-00 00:00:00',
  `IP` varchar(15) NOT NULL default '',
  PRIMARY KEY  (`Id`),
  KEY `IP` (`IP`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
TRUNCATE TABLE `/* TABLE_PREFIX */interaction_blacklist`;

CREATE TABLE IF NOT EXISTS `interaction_images` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `SectionId` int(11) NOT NULL DEFAULT '0',
  `ItemId` int(11) NOT NULL DEFAULT '0',
  `CommentId` int(11) NOT NULL DEFAULT '0',
  `Thumbnail` varchar(255) NOT NULL DEFAULT '',
  `Image` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`Id`),
  KEY `ItemId` (`ItemId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
TRUNCATE TABLE `/* TABLE_PREFIX */interaction_images`;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */mainpage` (
  `id` int(11) NOT NULL auto_increment,
  `body` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
TRUNCATE TABLE `/* TABLE_PREFIX */mainpage`;

INSERT INTO `/* TABLE_PREFIX */modules` (`id`, `muid`, `mname`, `mfile`, `mfileins`, `mfileinc`, `mtable`, `mtableitems`, `madmin`, `issingle`, `isinsertion`, `isinclusion`, `isenabled`) 
VALUES (-1, -1, 'Главная страница', 'mod_mainpage.php', '', '', 'mainpage', '', 'mod_mainpage', 1, 0, 0, 1);

INSERT INTO `/* TABLE_PREFIX */sections` (`id`, `topid`, `parentid`, `orderid`, `levelid`, `isparent`, `moduleid`, `designid`, `mask`, `path`, `image`, `timage`, `indic`, `title`, `metadesc`, `metakeys`, `isenabled`, `insearch`, `inmenu`, `inlinks`, `inmap`, `shtitle`, `shmenu`, `shlinks`, `flsearch`, `flcache`) VALUES
(-1, -1, -1, -1, -1, 1, -1, -1, '', '/', '', '', 'Главная', 'Главная страница', '', '', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);

INSERT INTO `/* TABLE_PREFIX */mainpage` (`id`, `body`) VALUES (1, '<p>Главная страница.</p>');

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */news` (
  `Id` int(11) NOT NULL auto_increment,
  `SectionId` int(11) NOT NULL default '0',
  `DateCreate` datetime NOT NULL default '0000-00-00 00:00:00',
  `Title` varchar(255) NOT NULL default '',
  `Author` varchar(255) NOT NULL default '',
  `Icon` varchar(255) NOT NULL default '',
  `Announce` text NOT NULL,
  `Body` text NOT NULL,
  `ViewedCnt` int(11) NOT NULL default '0',
  `IsHidden` tinyint(1) NOT NULL default '0',
  `IsRss` tinyint(1) NOT NULL default '1',
  `IsTimered` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`Id`),
  KEY `SHD` (`SectionId`, `IsHidden`, `DateCreate`),
  KEY `ViewedCnt` (`ViewedCnt`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
TRUNCATE TABLE `/* TABLE_PREFIX */news`;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */news_sections` (
  `Id` int(11) NOT NULL auto_increment,
  `SectionId` int(11) NOT NULL default '0',
  `BodyHead` text NOT NULL,
  `BodyFoot` text NOT NULL,
  `IconAttributes` varchar(255) NOT NULL default '',
  `PageLength` int(11) NOT NULL default '10',
  `AnnounceLength` int(11) NOT NULL default '255',
  `RssExpireOffset` int(11) NOT NULL default '7200',
  `TimerOffset` int(11) NOT NULL default '0',
  `IsArchive` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`Id`),
  KEY `SectionId` (`SectionId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
TRUNCATE TABLE `/* TABLE_PREFIX */news_sections`;

INSERT INTO `/* TABLE_PREFIX */modules` (`id`, `muid`, `mname`, `mfile`, `mfileins`, `mfileinc`, `mfilexsm`, `mtable`, `mtableitems`, `madmin`, `issingle`, `isinsertion`, `isinclusion`, `isenabled`) 
VALUES (2, 2, 'Лента новостей', 'mod_news.php', 'ins_news.php', 'inc_news.php', 'xsm_news.php', 'news_sections', 'news', 'mod_news', 0, 1, 1, 1);

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */news_import` (
  `Id` int(11) NOT NULL auto_increment,
  `SectionId` int(11) NOT NULL default '0',
  `ItemsShow` int(11) NOT NULL default '20',
  `Title` varchar(255) NOT NULL default '',
  `Url` varchar(255) NOT NULL default '',
  `LastGuid` varchar(255) NOT NULL default '',
  `ItemAuthor` varchar(255) NOT NULL default '',
  `ItemIcon` varchar(255) NOT NULL default '',
  `ItemTitleSearch` varchar(255) NOT NULL default '',
  `ItemTitleReplace` varchar(255) NOT NULL default '',
  `ItemAnnounceSearch` varchar(255) NOT NULL default '',
  `ItemAnnounceReplace` varchar(255) NOT NULL default '',
  `ItemBodySearch` varchar(255) NOT NULL default '',
  `ItemBodyReplace` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
TRUNCATE TABLE `/* TABLE_PREFIX */news_import`;

INSERT INTO `/* TABLE_PREFIX */widgets_types`
(ModuleId, Name, Title, Description) VALUES
(2, '', 'Новости', 'Вывод элементов новостных разделов'),
(2, 'Calendar', 'Архив новостей (календарь)', 'Вывод ссылок на архив новостей в виде календаря'),
(2, 'Authors', 'Авторы новостей', 'Вывод ссылок на новости конкретных авторов');

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */news2_sections` (
  `Id` int(11) NOT NULL auto_increment,
  `SectionId` int(11) NOT NULL default '0',
  `BodyHead` text NOT NULL,
  `BodyFoot` text NOT NULL,
  `Orderby` varchar(255) NOT NULL default '',
  `IconAttributes` varchar(255) NOT NULL default '',
  `InsIconAttributes` varchar(255) NOT NULL default '',
  `AnnounceLength` int(11) NOT NULL default '255',
  `PageLength` int(11) NOT NULL default '10',
  `RssOutCount` int(11) NOT NULL default '100',
  `RssExpireOffset` int(11) NOT NULL default '7200', /* minutes */
  `TimerOffset` int(11) NOT NULL default '0',
  `IsPublic` tinyint(1) NOT NULL default '0', /* в разделе разрешена публикация новостей зарегистрированным пользователям сайта */
  `ThumbnailWidth` int(11) NOT NULL default '100',
  `ThumbnailHeight` int(11) NOT NULL default '75',
  `ImageMaxWidth` int(11) NOT NULL default '800',
  `ImageMaxHeight` int(11) NOT NULL default '600',
  `ImageMaxSize` int(11) NOT NULL default '102400',
  `ImageTypes` varchar(255) NOT NULL default 'jpg,jpeg,gif,png',
  `UploadDir` varchar(255) NOT NULL default '/upload/news',
  `IsModerated` tinyint(1) NOT NULL default '0', /* публикуемые пользователями новости будут скрытыми до проверки модератором */
  `IsGlobalAE` tinyint(1) NOT NULL default '1', /* использовать глобальный email администратора сайта для уведомлений */
  `AlertEmail` varchar(255) NOT NULL default '',
  `AlertEmailSubj` varchar(255) NOT NULL default 'News added',
  `AlertEmailBody` text NOT NULL,
  PRIMARY KEY  (`Id`),
  KEY `SectionId` (`SectionId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
TRUNCATE TABLE `/* TABLE_PREFIX */news2_sections`;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */news2` (
  `Id` int(11) NOT NULL auto_increment,
  `UserId` int(11) NOT NULL default '0',
  `SectionId` int(11) NOT NULL default '0',
  `DateCreate` datetime NOT NULL default '0000-00-00 00:00:00',
  `DateView` datetime NOT NULL default '0000-00-00 00:00:00',
  `Title` varchar(255) NOT NULL default '',
  `Author` varchar(255) NOT NULL default '',
  `Icon` varchar(255) NOT NULL default '',
  `InsIcon` varchar(255) NOT NULL default '',
  `Announce` text NOT NULL,
  `Body` text NOT NULL,
  `ViewedCnt` int(11) NOT NULL default '0',
  `IsHidden` tinyint(1) NOT NULL default '0',
  `IsRss` tinyint(1) NOT NULL default '1',
  `IsTimered` tinyint(1) NOT NULL default '0',
  `IsDisabled` tinyint(1) NOT NULL default '0', /* запись не доступна для действий пользователю */
  PRIMARY KEY  (`Id`),
  KEY `DateCreate` (`DateCreate`),
  KEY `ViewedCnt` (`ViewedCnt`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
TRUNCATE TABLE `/* TABLE_PREFIX */news2`;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */news2_ns` (
  `Id` int(11) NOT NULL auto_increment,
  `SectionId` int(11) NOT NULL default '0',
  `ItemId` int(11) NOT NULL default '0',
  `AnotherSectionId` int(11) NOT NULL default '0',
  PRIMARY KEY  (`Id`),
  KEY `ItemId` (`ItemId`),
  KEY `AnotherSectionId` (`AnotherSectionId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
TRUNCATE TABLE `/* TABLE_PREFIX */news2_ns`;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */news2_tags` (
  `Id` int(11) NOT NULL auto_increment,
  `Tag` varchar(255) NOT NULL default '',
  `IsDisabled` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`Id`),
  UNIQUE KEY `Tag` (`Tag`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
TRUNCATE TABLE `/* TABLE_PREFIX */news2_tags`;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */news2_nt` (
  `Id` int(11) NOT NULL auto_increment,
  `ItemId` int(11) NOT NULL default '0',
  `TagId` int(11) NOT NULL default '0',
  PRIMARY KEY  (`Id`),
  KEY `ItemId` (`ItemId`),
  KEY `TagId` (`TagId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
TRUNCATE TABLE `/* TABLE_PREFIX */news2_nt`;

INSERT INTO `/* TABLE_PREFIX */modules` (`id`, `muid`, `mname`, `mfile`, `mfileins`, `mfileinc`, `mfilexsm`, `mtable`, `mtableitems`, `madmin`, `issingle`, `isinsertion`, `isinclusion`, `isenabled`) 
VALUES (17, 17, 'Лента новостей 2', 'mod_news2.php', 'ins_news2.php', 'inc_news2.php', 'xsm_news2.php', 'news2_sections', 'news2,news2_ns,news2_tags,news2_nt', 'mod_news2', 0, 1, 1, 1);

INSERT INTO `/* TABLE_PREFIX */widgets_types`
(ModuleId, Name, Title, Description) VALUES
(17, '', 'Новости 2', 'Вывод элементов разделов типа Новости 2');

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */oldurls` (
  `Id` int(11) NOT NULL auto_increment,
  `UserId` int(11) NOT NULL default '0',
  `SectionId` int(11) NOT NULL default '0',
  `OrderNumber` int(11) NOT NULL default '0',
  `DateCreate` datetime NOT NULL default '0000-00-00 00:00:00',
  `DateView` datetime NOT NULL default '0000-00-00 00:00:00',
  `Url` varchar(255) NOT NULL default '',
  `Target` varchar(255) NOT NULL default '',
  `Title` varchar(255) NOT NULL default '',
  `MetaKeys` varchar(255) NOT NULL default '',
  `MetaDesc` varchar(255) NOT NULL default '',
  `Body` text NOT NULL,
  `ViewedCnt` int(11) NOT NULL default '0',
  `IsHidden` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`Id`),
  KEY `SHU` (`SectionId`, `IsHidden`, `Url`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
TRUNCATE TABLE `/* TABLE_PREFIX */oldurls`;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */oldurls_sections` (
  `Id` int(11) NOT NULL auto_increment,
  `SectionId` int(11) NOT NULL default '0',
  `BodyHead` text NOT NULL,
  `BodyFoot` text NOT NULL,
  `PageLength` int(11) NOT NULL default '10',
  `Orderby` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
TRUNCATE TABLE `/* TABLE_PREFIX */oldurls_sections`;

INSERT INTO `/* TABLE_PREFIX */modules` (`id`, `muid`, `mname`, `mfile`, `mfileins`, `mfileinc`, `mfilexsm`, `mtable`, `mtableitems`, `madmin`, `issingle`, `isinsertion`, `isinclusion`, `isenabled`) 
VALUES (22, 22, 'Архив старых страниц', 'mod_oldurls.php', '0', '', 'xsm_oldurls.php', 'oldurls_sections', 'oldurls', 'mod_oldurls', 0, 0, 0, 1);

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */quotes_groups` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
TRUNCATE TABLE `/* TABLE_PREFIX */quotes_groups`;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */quotes` (
  `id` int(11) NOT NULL auto_increment,
  `groupid` int(11) NOT NULL default '0',
  `quote` text NOT NULL,
  `disabled` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `gd` (`groupid`,`disabled`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
TRUNCATE TABLE `/* TABLE_PREFIX */quotes`;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */sendforms` (
  `Id` int(11) NOT NULL auto_increment,
  `DateCreate` datetime NOT NULL default '0000-00-00 00:00:00',
  `Status` tinyint(1) NOT NULL default '0',
  `Url` varchar(255) NOT NULL default '',
  `IP` varchar(15) NOT NULL default '',
  `Form` text NOT NULL,
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
TRUNCATE TABLE `/* TABLE_PREFIX */sendforms`;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */shop_categories` (
  `Id` int(11) NOT NULL auto_increment,
  `SectionId` int(11) NOT NULL DEFAULT '0',
  `TopId` int(11) NOT NULL DEFAULT '0',
  `ParentId` int(11) NOT NULL DEFAULT '0',
  `OrderId` int(11) NOT NULL DEFAULT '0',
  `LevelId` int(11) NOT NULL DEFAULT '0',
  `TemplateId` int(11) NOT NULL DEFAULT '0',
  `IsParent` tinyint(1) NOT NULL DEFAULT '0',
  `IsHidden` tinyint(1) NOT NULL DEFAULT '0',
  `Mask` varchar(11) NOT NULL DEFAULT '',
  `Alias` varchar(255) NOT NULL DEFAULT '',
  `Caption` varchar(255) NOT NULL DEFAULT '',
  `Title` varchar(255) NOT NULL DEFAULT '',
  `MetaKeys` varchar(255) NOT NULL DEFAULT '',
  `MetaDesc` varchar(255) NOT NULL DEFAULT '',
  `Thumbnail` varchar(255) NOT NULL DEFAULT '',
  `Image` varchar(255) NOT NULL DEFAULT '',
  `Info` text NOT NULL,
  `SelfItemsCount` int(11) NOT NULL DEFAULT '0',
  `TotalItemsCount` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`Id`),
  KEY `SectionId` (`SectionId`),
  KEY `Mask` (`Mask`),
  KEY `Alias` (`Alias`),
  KEY `ParentId` (`ParentId`),
  KEY `OrderId` (`OrderId`),
  KEY `IsHidden` (`IsHidden`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
TRUNCATE TABLE `/* TABLE_PREFIX */shop_categories`;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */shop_items` (
  `Id` int(11) NOT NULL auto_increment,
  `SectionId` int(11) NOT NULL DEFAULT '0',
  `CategoryId` int(11) NOT NULL DEFAULT '0',
  `RelatedInfoId` int(11) NOT NULL DEFAULT '0',
  `OrderNumber` int(11) NOT NULL DEFAULT '0',
  `DateCreate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `DateView` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `Alias` varchar(255) NOT NULL DEFAULT '',
  `Caption` varchar(255) NOT NULL DEFAULT '',
  `Title` varchar(255) NOT NULL DEFAULT '',
  `MetaKeys` varchar(255) NOT NULL DEFAULT '',
  `MetaDesc` varchar(255) NOT NULL DEFAULT '',
  `Thumbnail` varchar(255) NOT NULL DEFAULT '',
  `Image` varchar(255) NOT NULL DEFAULT '',
  `ShortDesc` text NOT NULL,
  `FullDesc` text NOT NULL,
  `Price` float(13,2) NOT NULL DEFAULT '0.00',
  `ViewedCnt` int(11) NOT NULL DEFAULT '0',
  `IsHidden` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`Id`),
  KEY `SectionId` (`SectionId`),
  KEY `OrderNumber` (`OrderNumber`),
  KEY `Alias` (`Alias`),
  KEY `CategoryId` (`CategoryId`),
  KEY `ViewedCnt` (`ViewedCnt`),
  KEY `IsHidden` (`IsHidden`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
TRUNCATE TABLE `/* TABLE_PREFIX */shop_items`;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */shop_items_images` (
  `Id` int(11) NOT NULL auto_increment,
  `SectionId` int(11) NOT NULL DEFAULT '0',
  `ItemId` int(11) NOT NULL DEFAULT '0',
  `Thumbnail` varchar(255) NOT NULL DEFAULT '',
  `Image` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY  (`Id`),
  KEY `SectionId` (`SectionId`),
  KEY `ItemId` (`ItemId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
TRUNCATE TABLE `/* TABLE_PREFIX */shop_items_images`;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */shop_orders` (
  `Id` int(11) NOT NULL auto_increment,
  `SectionId` int(11) NOT NULL DEFAULT '0',
  `UserId` int(11) NOT NULL DEFAULT '0',
  `UserToken` varchar(40) NOT NULL DEFAULT '',
  `Status` tinyint(1) NOT NULL DEFAULT '0',
  `DateInit` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `DateCreate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `DatePaid` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `DateEquip` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `DateSend` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `DateClosed` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `Address` varchar(255) NOT NULL DEFAULT '',
  `Email` varchar(255) NOT NULL DEFAULT '',
  `Phone` varchar(255) NOT NULL DEFAULT '',
  `Comment` text NOT NULL,
  `Report` text NOT NULL,
  `Notes` text NOT NULL,
  PRIMARY KEY  (`Id`),
  KEY `SectionId` (`SectionId`),
  KEY `UserId` (`UserId`),
  KEY `UserToken` (`UserToken`),
  KEY `Status` (`Status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
TRUNCATE TABLE `/* TABLE_PREFIX */shop_orders`;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */shop_orders_items` (
  `Id` int(11) NOT NULL auto_increment,
  `SectionId` int(11) NOT NULL DEFAULT '0',
  `OrderId` int(11) NOT NULL DEFAULT '0',
  `ItemId` int(11) NOT NULL DEFAULT '0',
  `ItemsCount` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`Id`),
  KEY `SectionId` (`SectionId`),
  KEY `OrderId` (`OrderId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
TRUNCATE TABLE `/* TABLE_PREFIX */shop_orders_items`;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */shop_relatedinfo` (
  `Id` int(11) NOT NULL auto_increment,
  `SectionId` int(11) NOT NULL DEFAULT '0',
  `Caption` varchar(255) NOT NULL DEFAULT '',
  `Info` text NOT NULL,
  PRIMARY KEY  (`Id`),
  KEY `SectionId` (`SectionId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
TRUNCATE TABLE `/* TABLE_PREFIX */shop_relatedinfo`;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */shop_sections` (
  `Id` int(11) NOT NULL auto_increment,
  `SectionId` int(11) NOT NULL DEFAULT '0',
  `BodyHead` text NOT NULL,
  `BodyFoot` text NOT NULL,
  `PageLength` int(11) NOT NULL DEFAULT '10',
  `Orderby` tinyint(1) NOT NULL DEFAULT '0',
  `InheritMeta` tinyint(1) NOT NULL DEFAULT '0',
  `ThumbnailAttributes` varchar(255) NOT NULL DEFAULT '',
  `InsThumbnailAttributes` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY  (`Id`),
  KEY `SectionId` (`SectionId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
TRUNCATE TABLE `/* TABLE_PREFIX */shop_sections`;

INSERT INTO `/* TABLE_PREFIX */modules` (`id`, `muid`, `mname`, `mfile`, `mfileins`, `mfileinc`, `mfilexsm`, `mtable`, `mtableitems`, `madmin`, `issingle`, `isinsertion`, `isinclusion`, `isenabled`) 
VALUES (24, 24, 'Магазин', 'mod_shop.php', 'ins_shop.php', 'inc_shop.php', 'xsm_shop.php', 'shop_sections', 'shop_items,shop_items_images,shop_categories,shop_orders,shop_orders_items,shop_relatedinfo', 'mod_shop', 0, 1, 1, 1);

INSERT INTO `/* TABLE_PREFIX */widgets_types`
(ModuleId, Name, Title, Description) VALUES
(24, '', 'Магазин', 'Вывод элементов из раздела типа Магазин');

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */tales` (
  `Id` int(11) NOT NULL auto_increment,
  `UserId` int(11) NOT NULL default '0',
  `SectionId` int(11) NOT NULL default '0',
  `OrderNumber` int(11) NOT NULL default '0',
  `DateCreate` datetime NOT NULL default '0000-00-00 00:00:00',
  `DateView` datetime NOT NULL default '0000-00-00 00:00:00',
  `Url` varchar(255) NOT NULL default '',
  `Title` varchar(255) NOT NULL default '',
  `MetaKeys` varchar(255) NOT NULL default '',
  `MetaDesc` varchar(255) NOT NULL default '',
  `Author` varchar(255) NOT NULL default '',
  `Icon` varchar(255) NOT NULL default '',
  `Announce` text NOT NULL,
  `Body` text NOT NULL,
  `ViewedCnt` int(11) NOT NULL default '0',
  `IsHidden` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`Id`),
  KEY `SHOCV` (`SectionId`, `IsHidden`, `OrderNumber`, `DateCreate`, `ViewedCnt`),
  KEY `SHU` (`SectionId`, `IsHidden`, `Url`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
TRUNCATE TABLE `/* TABLE_PREFIX */tales`;

CREATE TABLE IF NOT EXISTS `/* TABLE_PREFIX */tales_sections` (
  `Id` int(11) NOT NULL auto_increment,
  `SectionId` int(11) NOT NULL default '0',
  `BodyHead` text NOT NULL,
  `BodyFoot` text NOT NULL,
  `IconAttributes` varchar(255) NOT NULL default '',
  `AnnounceLength` int(11) NOT NULL default '255',
  `PageLength` int(11) NOT NULL default '10',
  `RssOutCount` int(11) NOT NULL default '100',
  `Orderby` tinyint(1) NOT NULL default '0',
  `InheritMeta` tinyint(1) NOT NULL default '0', /* использовать информацию в тэгах TITLE и META из родительского раздела и всего сайта */
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
TRUNCATE TABLE `/* TABLE_PREFIX */tales_sections`;

INSERT INTO `/* TABLE_PREFIX */modules` (`id`, `muid`, `mname`, `mfile`, `mfileins`, `mfileinc`, `mfilexsm`, `mtable`, `mtableitems`, `madmin`, `issingle`, `isinsertion`, `isinclusion`, `isenabled`) 
VALUES (16, 16, 'Лента документов', 'mod_tales.php', '', '', 'xsm_tales.php', 'tales_sections', 'tales', 'mod_tales', 0, 0, 0, 1);

INSERT INTO `/* TABLE_PREFIX */widgets_types`
(ModuleId, Name, Title, Description) VALUES
(16, '', 'Статьи', 'Вывод элементов из раздела типа Лента документов');

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
TRUNCATE TABLE `/* TABLE_PREFIX */votings_settings`;

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
TRUNCATE TABLE `/* TABLE_PREFIX */votings`;

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
TRUNCATE TABLE `/* TABLE_PREFIX */votings_answers`;

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
TRUNCATE TABLE `/* TABLE_PREFIX */votings_log`;

INSERT INTO `/* TABLE_PREFIX */modules` (`id`, `muid`, `mname`, `mfile`, `mfileins`, `mfileinc`, `mtable`, `mtableitems`, `madmin`, `issingle`, `isinsertion`, `isinclusion`, `isenabled`) 
VALUES (5, 5, 'Голосования', 'mod_votings.php', '', '', 'votings_settings', 'votings,votings_questions,votings_answers', 'mod_votings', 0, 0, 0, 1);

INSERT INTO `/* TABLE_PREFIX */widgets_types`
(ModuleId, Name, Title, Description) VALUES
(5, '', 'Голосования', 'Вывод формы или результатов голосования');

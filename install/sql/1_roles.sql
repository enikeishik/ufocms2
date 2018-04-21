CREATE TABLE `/* TABLE_PREFIX */users_roles` (
  `Id` int(11) NOT NULL auto_increment,
  `IsSystem` tinyint(1) NOT NULL default '0',
  `Title` varchar(255) NOT NULL default '',
  `Description` text NOT NULL,
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ; /* starts from 10, 1-9 reserved */

CREATE TABLE `/* TABLE_PREFIX */users_roles_relations` (
  `Id` int(11) NOT NULL auto_increment,
  `UserId` int(11) NOT NULL default '0',
  `RoleId` int(11) NOT NULL default '0',
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `/* TABLE_PREFIX */users_roles_perms_mods` (
  `Id` int(11) NOT NULL auto_increment,
  `RoleId` int(11) NOT NULL default '0',
  `ModuleId` int(11) NOT NULL default '0',
  `Permissions` text NOT NULL,
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `/* TABLE_PREFIX */users_roles_perms_core` (
  `Id` int(11) NOT NULL auto_increment,
  `RoleId` int(11) NOT NULL default '0',
  `CoreModule` varchar(255) NOT NULL default '',
  `Permissions` text NOT NULL,
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `/* TABLE_PREFIX */users_roles_restrictions` (
  `Id` int(11) NOT NULL auto_increment,
  `RoleId` int(11) NOT NULL default '0',
  `CoreModules` text NOT NULL,
  `Modules` text NOT NULL,
  `Sections` text NOT NULL,
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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

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
(1, '', 'Документы', 'Вывод части содержимого текстовых разделов'),
(2, '', 'Новости', 'Вывод элементов новостных разделов'),
(2, 'Calendar', 'Архив новостей (календарь)', 'Вывод ссылок на архив новостей в виде календаря'),
(2, 'Authors', 'Авторы новостей', 'Вывод ссылок на новости конкретных авторов'),
(0, 'Slideshow', 'Показ слайдов', 'Блок показа слайдов из папки с изображениями, без привязки к разделу'),
(0, 'WeatherGismeteo', 'Погода от Gismeteo.ru', 'Блок показа информера погоды от Gismeteo.ru'),
(0, 'WeatherYandex', 'Погода от Яндекса', 'Блок показа информера погоды от Яндекса'),
(0, 'TrafficYandex', 'Пробки от Яндекса', 'Блок показа информера пробок от Яндекса'),
(0, 'CurrencyCbrf', 'Курсы валют от Банка России', 'Блок показа курсов валют от Банка России'),
(0, 'DayEvents', 'События дня', 'Блок показа событий вчерашнего, текущего, завтрашнего дня'),
(4, '', 'Объявления', 'Вывод элементов из разделов типа Доска объявлений'),
(6, '', 'Вопрос-ответ', 'Вывод элементов из разделов типа Вопрос-ответ'),
(4, 'Form', 'Форма подачи объявления', 'Вывод формы для подачи объявления в разделы типа Доска объявлений'),
(6, 'Form', 'Форма подачи вопроса', 'Вывод формы для подачи вопроса в разделы типа Вопрос-ответ'),
(24, '', 'Магазин', 'Вывод элементов из раздела типа Магазин'),
(25, '', 'Поздравления', 'Вывод элементов из раздела типа Поздравления'),
(16, '', 'Статьи', 'Вывод элементов из раздела типа Лента документов');

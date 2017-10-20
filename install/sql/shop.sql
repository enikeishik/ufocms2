CREATE TABLE `/* TABLE_PREFIX */shop_categories` (
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

CREATE TABLE `/* TABLE_PREFIX */shop_items` (
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

CREATE TABLE `/* TABLE_PREFIX */shop_items_images` (
  `Id` int(11) NOT NULL auto_increment,
  `SectionId` int(11) NOT NULL DEFAULT '0',
  `ItemId` int(11) NOT NULL DEFAULT '0',
  `Thumbnail` varchar(255) NOT NULL DEFAULT '',
  `Image` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY  (`Id`),
  KEY `SectionId` (`SectionId`),
  KEY `ItemId` (`ItemId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `/* TABLE_PREFIX */shop_orders` (
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

CREATE TABLE `/* TABLE_PREFIX */shop_orders_items` (
  `Id` int(11) NOT NULL auto_increment,
  `SectionId` int(11) NOT NULL DEFAULT '0',
  `OrderId` int(11) NOT NULL DEFAULT '0',
  `ItemId` int(11) NOT NULL DEFAULT '0',
  `ItemsCount` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`Id`),
  KEY `SectionId` (`SectionId`),
  KEY `OrderId` (`OrderId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `/* TABLE_PREFIX */shop_relatedinfo` (
  `Id` int(11) NOT NULL auto_increment,
  `SectionId` int(11) NOT NULL DEFAULT '0',
  `Caption` varchar(255) NOT NULL DEFAULT '',
  `Info` text NOT NULL,
  PRIMARY KEY  (`Id`),
  KEY `SectionId` (`SectionId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `/* TABLE_PREFIX */shop_sections` (
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

INSERT INTO `/* TABLE_PREFIX */modules` (`id`, `muid`, `mname`, `mfile`, `mfileins`, `mfileinc`, `mfilexsm`, `mtable`, `mtableitems`, `madmin`, `issingle`, `isinsertion`, `isinclusion`, `isenabled`) 
VALUES (24, 24, 'Магазин', 'mod_shop.php', 'ins_shop.php', 'inc_shop.php', 'xsm_shop.php', 'shop_sections', 'shop_items,shop_items_images,shop_categories,shop_orders,shop_orders_items,shop_relatedinfo', 'mod_shop', 0, 1, 1, 1);

INSERT INTO `/* TABLE_PREFIX */widgets_types`
(ModuleId, Name, Title, Description) VALUES
(24, '', 'Магазин', 'Вывод элементов из раздела типа Магазин');

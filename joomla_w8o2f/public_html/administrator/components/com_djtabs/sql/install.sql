CREATE TABLE IF NOT EXISTS `#__djtabs_groups` (
   `id` int(10) unsigned not null auto_increment,
   `title` varchar(255) not null default '',
   `published` tinyint(1) not null default '0',
   `ordering` int(11) not null default '0',
   `params` text,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;

CREATE TABLE IF NOT EXISTS `#__djtabs_themes` (
   `id` int(11) not null auto_increment,
   `title` varchar(255) not null,
   `custom` tinyint(1) not null default '1',
   `random` tinyint(1) not null default '1',
   `published` tinyint(1) not null default '0',
   `ordering` int(11) not null default '0',
   `params` text,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;

CREATE TABLE IF NOT EXISTS `#__djtabs_items` (
   `id` int(11) not null auto_increment,
   `name` varchar(255) not null,
   `type` tinyint(4) not null,
   `group_id` int(11) not null,
   `ordering` int(11) not null,
   `params` text,
   `published` int(11) not null,
   `access` int(11) not null default 1,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;

INSERT INTO `#__djtabs_groups` (`id`, `title`, `published`, `ordering`) VALUES ('1','Tabs1','1','1');

INSERT INTO `#__djtabs_themes` (`id`, `title`, `custom`, `random`, `published`, `ordering`, `params`) VALUES
(12, 'Arc light blue', 1, 1, 1, 12, '{\"tb-hght\":47,\"tb-wdth\":\"134\",\"tb-dir\":\"left\",\"tb-brdr-rds\":10,\"tb-brdr-rds-bttm\":0,\"tb-pddng-lft-rght\":5,\"tb-spc\":2,\"tb-brdr-stl\":\"dotted\",\"tb-brdr-clr\":\"#585452\",\"tb-txt-trnsfrm\":\"uppercase\",\"tb-fnt-sz\":14,\"tb-fnt-fml\":\"arial, sans-serif\",\"tb-ctv-bck-clr\":\"#26ADE4\",\"tb-ctv-ttl-clr\":\"#F7F2EB\",\"tb-nctv-bck-clr\":\"#302C2C\",\"tb-nctv-ttl-clr\":\"#F7F2EB\",\"tb-ln-width\":2,\"tb-ln-clr\":\"#26ADE4\",\"cntnt-fnt-sz\":13,\"cntnt-fnt-fml\":\"arial, sans-serif\",\"cntnt-dt-fnt-sz\":12,\"cntnt-clr\":\"#666666\",\"mg-brdr-rds\":10,\"cntnt-fll-brdr\":\"1\",\"pnl-brdr-rds\":10,\"pnl-fnt-sz\":14,\"pnl-fnt-fml\":\"arial, sans-serif\",\"pnl-dt-clr\":\"#26ADE4\",\"pnl-ctv-clr\":\"#302C2C\",\"pnl-ctv-ttl-clr\":\"#F5F5F5\",\"pnl-ctv-brdrs-stl\":\"solid\",\"pnl-ctv-brdrs-clr\":\"#302C2C\",\"pnl-nctv-clr\":\"#FFFFFF\",\"pnl-nctv-ttl-clr\":\"#302C2C\",\"pnl-nctv-brdrs-stl\":\"solid\",\"pnl-nctv-brdrs-clr\":\"#E2E2E2\",\"tgglr-nctv-bck-clr\":\"#E2E2E2\",\"tgglr-ctv-bck-clr\":\"#26ADE4\",\"tgglr-brdr-rds\":10}'),
(11, 'Green and Black', 1, 1, 1, 11, '{\"tb-hght\":67,\"tb-wdth\":\"120\",\"tb-dir\":\"left\",\"tb-brdr-rds\":5,\"tb-brdr-rds-bttm\":0,\"tb-pddng-lft-rght\":10,\"tb-spc\":1,\"tb-brdr-stl\":\"none\",\"tb-brdr-clr\":\"#585452\",\"tb-txt-trnsfrm\":\"uppercase\",\"tb-fnt-sz\":16,\"tb-fnt-fml\":\"arial, sans-serif\",\"tb-ctv-bck-clr\":\"#59A80F\",\"tb-ctv-ttl-clr\":\"#F7F2EB\",\"tb-nctv-bck-clr\":\"#302C2C\",\"tb-nctv-ttl-clr\":\"#F7F2EB\",\"tb-ln-width\":1,\"tb-ln-clr\":\"#59A80F\",\"cntnt-fnt-sz\":16,\"cntnt-fnt-fml\":\"arial, sans-serif\",\"cntnt-dt-fnt-sz\":12,\"cntnt-clr\":\"#454545\",\"mg-brdr-rds\":3,\"cntnt-fll-brdr\":\"1\",\"pnl-brdr-rds\":5,\"pnl-fnt-sz\":18,\"pnl-fnt-fml\":\"arial, sans-serif\",\"pnl-dt-clr\":\"#59A80F\",\"pnl-ctv-clr\":\"#302C2C\",\"pnl-ctv-ttl-clr\":\"#F5F5F5\",\"pnl-ctv-brdrs-stl\":\"dashed\",\"pnl-ctv-brdrs-clr\":\"#302C2C\",\"pnl-nctv-clr\":\"#FFFFFF\",\"pnl-nctv-ttl-clr\":\"#302C2C\",\"pnl-nctv-brdrs-stl\":\"solid\",\"pnl-nctv-brdrs-clr\":\"#E2E2E2\",\"tgglr-nctv-bck-clr\":\"#E2E2E2\",\"tgglr-ctv-bck-clr\":\"#59A80F\",\"tgglr-brdr-rds\":25}'),
(10, 'Arc Orange', 1, 1, 1, 10, '{\"tb-hght\":47,\"tb-wdth\":\"auto\",\"tb-dir\":\"left\",\"tb-brdr-rds\":20,\"tb-brdr-rds-bttm\":0,\"tb-pddng-lft-rght\":25,\"tb-spc\":1,\"tb-brdr-stl\":\"dashed\",\"tb-brdr-clr\":\"#585452\",\"tb-txt-trnsfrm\":\"uppercase\",\"tb-fnt-sz\":12,\"tb-fnt-fml\":\"arial, sans-serif\",\"tb-ctv-bck-clr\":\"#F54828\",\"tb-ctv-ttl-clr\":\"#F7F2EB\",\"tb-nctv-bck-clr\":\"#302C2C\",\"tb-nctv-ttl-clr\":\"#F7F2EB\",\"tb-ln-width\":4,\"tb-ln-clr\":\"#F54828\",\"cntnt-fnt-sz\":17,\"cntnt-fnt-fml\":\"inherit\",\"cntnt-dt-fnt-sz\":12,\"cntnt-clr\":\"#666666\",\"mg-brdr-rds\":20,\"cntnt-fll-brdr\":\"1\",\"pnl-brdr-rds\":20,\"pnl-fnt-sz\":16,\"pnl-fnt-fml\":\"arial, sans-serif\",\"pnl-dt-clr\":\"#F54828\",\"pnl-ctv-clr\":\"#302C2C\",\"pnl-ctv-ttl-clr\":\"#F5F5F5\",\"pnl-ctv-brdrs-stl\":\"solid\",\"pnl-ctv-brdrs-clr\":\"#302C2C\",\"pnl-nctv-clr\":\"#FFFFFF\",\"pnl-nctv-ttl-clr\":\"#302C2C\",\"pnl-nctv-brdrs-stl\":\"solid\",\"pnl-nctv-brdrs-clr\":\"#E2E2E2\",\"tgglr-nctv-bck-clr\":\"#E2E2E2\",\"tgglr-ctv-bck-clr\":\"#F54828\",\"tgglr-brdr-rds\":20}'),
(9, 'Rounded Black', 1, 1, 1, 9, '{\"tb-hght\":47,\"tb-wdth\":\"auto\",\"tb-dir\":\"left\",\"tb-brdr-rds\":5,\"tb-brdr-rds-bttm\":0,\"tb-pddng-lft-rght\":10,\"tb-spc\":2,\"tb-brdr-stl\":\"none\",\"tb-brdr-clr\":\"#585452\",\"tb-txt-trnsfrm\":\"uppercase\",\"tb-fnt-sz\":15,\"tb-fnt-fml\":\"arial, sans-serif\",\"tb-ctv-bck-clr\":\"#524C4C\",\"tb-ctv-ttl-clr\":\"#F7F2EB\",\"tb-nctv-bck-clr\":\"#302C2C\",\"tb-nctv-ttl-clr\":\"#F7F2EB\",\"tb-ln-width\":2,\"tb-ln-clr\":\"#524C4C\",\"cntnt-fnt-sz\":16,\"cntnt-fnt-fml\":\"inherit\",\"cntnt-dt-fnt-sz\":12,\"cntnt-clr\":\"#666666\",\"mg-brdr-rds\":5,\"cntnt-fll-brdr\":\"1\",\"pnl-brdr-rds\":25,\"pnl-fnt-sz\":20,\"pnl-fnt-fml\":\"arial, sans-serif\",\"pnl-dt-clr\":\"#524C4C\",\"pnl-ctv-clr\":\"#302C2C\",\"pnl-ctv-ttl-clr\":\"#F5F5F5\",\"pnl-ctv-brdrs-stl\":\"solid\",\"pnl-ctv-brdrs-clr\":\"#302C2C\",\"pnl-nctv-clr\":\"#FFFFFF\",\"pnl-nctv-ttl-clr\":\"#302C2C\",\"pnl-nctv-brdrs-stl\":\"solid\",\"pnl-nctv-brdrs-clr\":\"#E2E2E2\",\"tgglr-nctv-bck-clr\":\"#E2E2E2\",\"tgglr-ctv-bck-clr\":\"#524C4C\",\"tgglr-brdr-rds\":25}'),
(8, 'Bold Blue', 1, 1, 1, 8, '{\"tb-hght\":67,\"tb-wdth\":\"auto\",\"tb-dir\":\"left\",\"tb-brdr-rds\":2,\"tb-brdr-rds-bttm\":2,\"tb-pddng-lft-rght\":15,\"tb-spc\":2,\"tb-brdr-stl\":\"none\",\"tb-brdr-clr\":\"#585452\",\"tb-txt-trnsfrm\":\"capitalize\",\"tb-fnt-sz\":14,\"tb-fnt-fml\":\"arial, sans-serif\",\"tb-ctv-bck-clr\":\"#26ADE4\",\"tb-ctv-ttl-clr\":\"#F7F2EB\",\"tb-nctv-bck-clr\":\"#302C2C\",\"tb-nctv-ttl-clr\":\"#F7F2EB\",\"tb-ln-width\":1,\"tb-ln-clr\":\"#26ADE4\",\"cntnt-fnt-sz\":18,\"cntnt-fnt-fml\":\"inherit\",\"cntnt-dt-fnt-sz\":17,\"cntnt-clr\":\"#454545\",\"mg-brdr-rds\":2,\"cntnt-fll-brdr\":\"1\",\"pnl-brdr-rds\":0,\"pnl-fnt-sz\":15,\"pnl-fnt-fml\":\"inherit\",\"pnl-dt-clr\":\"#26ADE4\",\"pnl-ctv-clr\":\"#FFFFFF\",\"pnl-ctv-ttl-clr\":\"#302C2C\",\"pnl-ctv-brdrs-stl\":\"solid\",\"pnl-ctv-brdrs-clr\":\"#E2E2E2\",\"pnl-nctv-clr\":\"#FFFFFF\",\"pnl-nctv-ttl-clr\":\"#302C2C\",\"pnl-nctv-brdrs-stl\":\"solid\",\"pnl-nctv-brdrs-clr\":\"#E2E2E2\",\"tgglr-nctv-bck-clr\":\"#E2E2E2\",\"tgglr-ctv-bck-clr\":\"#26ADE4\",\"tgglr-brdr-rds\":0}'),
(7, 'Greenpoint', 1, 1, 1, 7, '{\"tb-hght\":47,\"tb-wdth\":\"auto\",\"tb-dir\":\"left\",\"tb-brdr-rds\":6,\"tb-brdr-rds-bttm\":0,\"tb-pddng-lft-rght\":10,\"tb-spc\":3,\"tb-brdr-stl\":\"dashed\",\"tb-brdr-clr\":\"#585452\",\"tb-txt-trnsfrm\":\"capitalize\",\"tb-fnt-sz\":15,\"tb-fnt-fml\":\"Inherit\",\"tb-ctv-bck-clr\":\"#59A80F\",\"tb-ctv-ttl-clr\":\"#F7F2EB\",\"tb-nctv-bck-clr\":\"#302C2C\",\"tb-nctv-ttl-clr\":\"#F7F2EB\",\"tb-ln-width\":2,\"tb-ln-clr\":\"#59A80F\",\"cntnt-fnt-sz\":18,\"cntnt-fnt-fml\":\"arial, sans-serif\",\"cntnt-dt-fnt-sz\":12,\"cntnt-clr\":\"#000000\",\"mg-brdr-rds\":0,\"cntnt-fll-brdr\":\"1\",\"pnl-brdr-rds\":3,\"pnl-fnt-sz\":19,\"pnl-fnt-fml\":\"inherit\",\"pnl-dt-clr\":\"#59A80F\",\"pnl-ctv-clr\":\"#FFFFFF\",\"pnl-ctv-ttl-clr\":\"#302C2C\",\"pnl-ctv-brdrs-stl\":\"dotted\",\"pnl-ctv-brdrs-clr\":\"#E2E2E2\",\"pnl-nctv-clr\":\"#FFFFFF\",\"pnl-nctv-ttl-clr\":\"#302C2C\",\"pnl-nctv-brdrs-stl\":\"groove\",\"pnl-nctv-brdrs-clr\":\"#E2E2E2\",\"tgglr-nctv-bck-clr\":\"#E2E2E2\",\"tgglr-ctv-bck-clr\":\"#59A80F\",\"tgglr-brdr-rds\":0}'),
(6, 'Happy Orange', 1, 1, 1, 6, '{\"tb-hght\":57,\"tb-wdth\":\"auto\",\"tb-dir\":\"left\",\"tb-brdr-rds\":5,\"tb-brdr-rds-bttm\":0,\"tb-pddng-lft-rght\":15,\"tb-spc\":10,\"tb-brdr-stl\":\"dotted\",\"tb-brdr-clr\":\"#585452\",\"tb-txt-trnsfrm\":\"capitalize\",\"tb-fnt-sz\":18,\"tb-fnt-fml\":\"arial, sans-serif\",\"tb-ctv-bck-clr\":\"#F54828\",\"tb-ctv-ttl-clr\":\"#F7F2EB\",\"tb-nctv-bck-clr\":\"#302C2C\",\"tb-nctv-ttl-clr\":\"#F7F2EB\",\"tb-ln-width\":4,\"tb-ln-clr\":\"#F54828\",\"cntnt-fnt-sz\":18,\"cntnt-fnt-fml\":\"inherit\",\"cntnt-dt-fnt-sz\":12,\"cntnt-clr\":\"#666666\",\"mg-brdr-rds\":3,\"cntnt-fll-brdr\":\"1\",\"pnl-brdr-rds\":0,\"pnl-fnt-sz\":16,\"pnl-fnt-fml\":\"inherit\",\"pnl-dt-clr\":\"#F54828\",\"pnl-ctv-clr\":\"#FFFFFF\",\"pnl-ctv-ttl-clr\":\"#302C2C\",\"pnl-ctv-brdrs-stl\":\"solid\",\"pnl-ctv-brdrs-clr\":\"#E2E2E2\",\"pnl-nctv-clr\":\"#FFFFFF\",\"pnl-nctv-ttl-clr\":\"#302C2C\",\"pnl-nctv-brdrs-stl\":\"solid\",\"pnl-nctv-brdrs-clr\":\"#E2E2E2\",\"tgglr-nctv-bck-clr\":\"#E2E2E2\",\"tgglr-ctv-bck-clr\":\"#F54828\",\"tgglr-brdr-rds\":0}'),
(1, 'dj-black', 0, 1, 1, 1, '{\"tb-ctv-bck-clr\":\"#353535\"}'),
(2, 'dj-orange', 0, 1, 1, 2, '{\"tb-ctv-bck-clr\":\"#DD4125\"}'),
(3, 'dj-green', 0, 1, 1, 3, '{\"tb-ctv-bck-clr\":\"#51980E\"}'),
(4, 'dj-blue', 0, 1, 1, 4, '{\"tb-ctv-bck-clr\":\"#239CCD\"}'),
(5, 'Deep Black', 1, 1, 1, 5, '{\"tb-hght\":47,\"tb-wdth\":\"auto\",\"tb-dir\":\"left\",\"tb-brdr-rds\":2,\"tb-brdr-rds-bttm\":0,\"tb-pddng-lft-rght\":15,\"tb-spc\":2,\"tb-brdr-stl\":\"dotted\",\"tb-brdr-clr\":\"#585452\",\"tb-txt-trnsfrm\":\"uppercase\",\"tb-fnt-sz\":12,\"tb-fnt-fml\":\"arial, sans-serif\",\"tb-ctv-bck-clr\":\"#524C4C\",\"tb-ctv-ttl-clr\":\"#F7F2EB\",\"tb-nctv-bck-clr\":\"#302C2C\",\"tb-nctv-ttl-clr\":\"#F7F2EB\",\"tb-ln-width\":4,\"tb-ln-clr\":\"#524C4C\",\"cntnt-fnt-sz\":17,\"cntnt-fnt-fml\":\"inherit\",\"cntnt-dt-fnt-sz\":12,\"cntnt-clr\":\"#666666\",\"mg-brdr-rds\":0,\"cntnt-fll-brdr\":\"1\",\"pnl-brdr-rds\":0,\"pnl-fnt-sz\":16,\"pnl-fnt-fml\":\"arial, sans-serif\",\"pnl-dt-clr\":\"#524C4C\",\"pnl-ctv-clr\":\"#FFFFFF\",\"pnl-ctv-ttl-clr\":\"#302C2C\",\"pnl-ctv-brdrs-stl\":\"solid\",\"pnl-ctv-brdrs-clr\":\"#E2E2E2\",\"pnl-nctv-clr\":\"#FFFFFF\",\"pnl-nctv-ttl-clr\":\"#302C2C\",\"pnl-nctv-brdrs-stl\":\"solid\",\"pnl-nctv-brdrs-clr\":\"#E2E2E2\",\"tgglr-nctv-bck-clr\":\"#E2E2E2\",\"tgglr-ctv-bck-clr\":\"#524C4C\",\"tgglr-brdr-rds\":0}'),
(13, 'Lavender blue light', 1, 1, 1, 13, '{\"tb-hght\":50,\"tb-wdth\":\"auto\",\"tb-dir\":\"left\",\"tb-brdr-rds\":0,\"tb-brdr-rds-bttm\":0,\"tb-pddng-lft-rght\":25,\"tb-spc\":3,\"tb-brdr-stl\":\"none\",\"tb-brdr-clr\":\"#DFE7FF\",\"tb-txt-trnsfrm\":\"none\",\"tb-fnt-sz\":15,\"tb-fnt-fml\":\"Georgia\",\"tb-ctv-bck-clr\":\"#EEF2FF\",\"tb-ctv-ttl-clr\":\"#726CEB\",\"tb-nctv-bck-clr\":\"#F8FAFF\",\"tb-nctv-ttl-clr\":\"#636A79\",\"tb-ln-width\":2,\"tb-ln-clr\":\"#EEF2FF\",\"cntnt-fnt-sz\":16,\"cntnt-fnt-fml\":\"inherit\",\"cntnt-dt-fnt-sz\":12,\"cntnt-clr\":\"#4D4D4D\",\"mg-brdr-rds\":2,\"cntnt-fll-brdr\":\"1\",\"pnl-brdr-rds\":0,\"pnl-fnt-sz\":14,\"pnl-fnt-fml\":\"arial, sans-serif\",\"pnl-dt-clr\":\"#726CEB\",\"pnl-ctv-clr\":\"#FFFFFF\",\"pnl-ctv-ttl-clr\":\"#302C2C\",\"pnl-ctv-brdrs-stl\":\"solid\",\"pnl-ctv-brdrs-clr\":\"#FFFFFF\",\"pnl-nctv-clr\":\"#FFFFFF\",\"pnl-nctv-ttl-clr\":\"#302C2C\",\"pnl-nctv-brdrs-stl\":\"solid\",\"pnl-nctv-brdrs-clr\":\"#FFFFFF\",\"tgglr-nctv-bck-clr\":\"#FFFFFF\",\"tgglr-ctv-bck-clr\":\"#726CEB\",\"tgglr-brdr-rds\":3}'),
(14, 'Light blue rounded', 1, 1, 1, 14, '{\"tb-hght\":50,\"tb-wdth\":\"auto\",\"tb-dir\":\"left\",\"tb-brdr-rds\":30,\"tb-brdr-rds-bttm\":30,\"tb-pddng-lft-rght\":25,\"tb-spc\":5,\"tb-brdr-stl\":\"dotted\",\"tb-brdr-clr\":\"#FFFFFF\",\"tb-txt-trnsfrm\":\"none\",\"tb-fnt-sz\":18,\"tb-fnt-fml\":\"arial, sans-serif\",\"tb-ctv-bck-clr\":\"#6F94FC\",\"tb-ctv-ttl-clr\":\"#FFFFFF\",\"tb-nctv-bck-clr\":\"#EDEDED\",\"tb-nctv-ttl-clr\":\"#2D3849\",\"tb-ln-width\":0,\"tb-ln-clr\":\"#D6D6D6\",\"cntnt-fnt-sz\":18,\"cntnt-fnt-fml\":\"inherit\",\"cntnt-dt-fnt-sz\":12,\"cntnt-clr\":\"#2D3849\",\"mg-brdr-rds\":20,\"cntnt-fll-brdr\":\"0\",\"pnl-brdr-rds\":0,\"pnl-fnt-sz\":14,\"pnl-fnt-fml\":\"arial, sans-serif\",\"pnl-dt-clr\":\"#6F94FC\",\"pnl-ctv-clr\":\"#FFFFFF\",\"pnl-ctv-ttl-clr\":\"#2D3849\",\"pnl-ctv-brdrs-stl\":\"solid\",\"pnl-ctv-brdrs-clr\":\"#E2E2E2\",\"pnl-nctv-clr\":\"#FFFFFF\",\"pnl-nctv-ttl-clr\":\"#2D3849\",\"pnl-nctv-brdrs-stl\":\"solid\",\"pnl-nctv-brdrs-clr\":\"#E2E2E2\",\"tgglr-nctv-bck-clr\":\"#E2E2E2\",\"tgglr-ctv-bck-clr\":\"#6F94FC\",\"tgglr-brdr-rds\":0}'),
(15, 'Simple', 1, 1, 1, 15, '{\"tb-hght\":47,\"tb-wdth\":\"auto\",\"tb-dir\":\"left\",\"tb-brdr-rds\":5,\"tb-brdr-rds-bttm\":0,\"tb-pddng-lft-rght\":15,\"tb-spc\":3,\"tb-brdr-stl\":\"none\",\"tb-brdr-clr\":\"#BFBFBF\",\"tb-txt-trnsfrm\":\"none\",\"tb-fnt-sz\":16,\"tb-fnt-fml\":\"arial, sans-serif\",\"tb-ctv-bck-clr\":\"#017EBA\",\"tb-ctv-ttl-clr\":\"#FFFFFF\",\"tb-nctv-bck-clr\":\"#E8E8E8\",\"tb-nctv-ttl-clr\":\"#000000\",\"tb-ln-width\":1,\"tb-ln-clr\":\"#017EBA\",\"cntnt-fnt-sz\":16,\"cntnt-fnt-fml\":\"arial, sans-serif\",\"cntnt-dt-fnt-sz\":12,\"cntnt-clr\":\"#666666\",\"mg-brdr-rds\":0,\"cntnt-fll-brdr\":\"1\",\"pnl-brdr-rds\":0,\"pnl-fnt-sz\":14,\"pnl-fnt-fml\":\"arial, sans-serif\",\"pnl-dt-clr\":\"#302C2C\",\"pnl-ctv-clr\":\"#FFFFFF\",\"pnl-ctv-ttl-clr\":\"#302C2C\",\"pnl-ctv-brdrs-stl\":\"solid\",\"pnl-ctv-brdrs-clr\":\"#E2E2E2\",\"pnl-nctv-clr\":\"#FFFFFF\",\"pnl-nctv-ttl-clr\":\"#302C2C\",\"pnl-nctv-brdrs-stl\":\"solid\",\"pnl-nctv-brdrs-clr\":\"#E2E2E2\",\"tgglr-nctv-bck-clr\":\"#E2E2E2\",\"tgglr-ctv-bck-clr\":\"#017EBA\",\"tgglr-brdr-rds\":0}'),
(16, 'Simple RTL', 1, 1, 1, 16, '{\"tb-hght\":47,\"tb-wdth\":\"auto\",\"tb-dir\":\"right\",\"tb-brdr-rds\":5,\"tb-brdr-rds-bttm\":0,\"tb-pddng-lft-rght\":15,\"tb-spc\":3,\"tb-brdr-stl\":\"none\",\"tb-brdr-clr\":\"#BFBFBF\",\"tb-txt-trnsfrm\":\"none\",\"tb-fnt-sz\":16,\"tb-fnt-fml\":\"arial, sans-serif\",\"tb-ctv-bck-clr\":\"#017EBA\",\"tb-ctv-ttl-clr\":\"#FFFFFF\",\"tb-nctv-bck-clr\":\"#E8E8E8\",\"tb-nctv-ttl-clr\":\"#000000\",\"tb-ln-width\":1,\"tb-ln-clr\":\"#017EBA\",\"cntnt-fnt-sz\":16,\"cntnt-fnt-fml\":\"arial, sans-serif\",\"cntnt-dt-fnt-sz\":12,\"cntnt-clr\":\"#666666\",\"mg-brdr-rds\":0,\"cntnt-fll-brdr\":\"1\",\"pnl-brdr-rds\":0,\"pnl-fnt-sz\":14,\"pnl-fnt-fml\":\"arial, sans-serif\",\"pnl-dt-clr\":\"#308806\",\"pnl-ctv-clr\":\"#FFFFFF\",\"pnl-ctv-ttl-clr\":\"#302C2C\",\"pnl-ctv-brdrs-stl\":\"solid\",\"pnl-ctv-brdrs-clr\":\"#E2E2E2\",\"pnl-nctv-clr\":\"#FFFFFF\",\"pnl-nctv-ttl-clr\":\"#302C2C\",\"pnl-nctv-brdrs-stl\":\"solid\",\"pnl-nctv-brdrs-clr\":\"#E2E2E2\",\"tgglr-nctv-bck-clr\":\"#E2E2E2\",\"tgglr-ctv-bck-clr\":\"#017EBA\",\"tgglr-brdr-rds\":0}');
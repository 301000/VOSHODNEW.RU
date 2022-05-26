ALTER TABLE `#__djtabs_items` ADD `access` int(11) NOT NULL default 1;

INSERT INTO `#__djtabs_themes` (`title`, `custom`, `random`, `published`, `ordering`, `params`) VALUES 
('Lavender blue light', 1, 1, 1, 13, '{\"tb-hght\":50,\"tb-wdth\":\"auto\",\"tb-dir\":\"left\",\"tb-brdr-rds\":0,\"tb-brdr-rds-bttm\":0,\"tb-pddng-lft-rght\":25,\"tb-spc\":3,\"tb-brdr-stl\":\"none\",\"tb-brdr-clr\":\"#DFE7FF\",\"tb-txt-trnsfrm\":\"none\",\"tb-fnt-sz\":15,\"tb-fnt-fml\":\"Georgia\",\"tb-ctv-bck-clr\":\"#EEF2FF\",\"tb-ctv-ttl-clr\":\"#726CEB\",\"tb-nctv-bck-clr\":\"#F8FAFF\",\"tb-nctv-ttl-clr\":\"#636A79\",\"tb-ln-width\":2,\"tb-ln-clr\":\"#EEF2FF\",\"cntnt-fnt-sz\":16,\"cntnt-fnt-fml\":\"inherit\",\"cntnt-dt-fnt-sz\":12,\"cntnt-clr\":\"#4D4D4D\",\"mg-brdr-rds\":2,\"cntnt-fll-brdr\":\"1\",\"pnl-brdr-rds\":0,\"pnl-fnt-sz\":14,\"pnl-fnt-fml\":\"arial, sans-serif\",\"pnl-dt-clr\":\"#726CEB\",\"pnl-ctv-clr\":\"#FFFFFF\",\"pnl-ctv-ttl-clr\":\"#302C2C\",\"pnl-ctv-brdrs-stl\":\"solid\",\"pnl-ctv-brdrs-clr\":\"#FFFFFF\",\"pnl-nctv-clr\":\"#FFFFFF\",\"pnl-nctv-ttl-clr\":\"#302C2C\",\"pnl-nctv-brdrs-stl\":\"solid\",\"pnl-nctv-brdrs-clr\":\"#FFFFFF\",\"tgglr-nctv-bck-clr\":\"#FFFFFF\",\"tgglr-ctv-bck-clr\":\"#726CEB\",\"tgglr-brdr-rds\":3}'),
('Light blue rounded', 1, 1, 1, 14, '{\"tb-hght\":50,\"tb-wdth\":\"auto\",\"tb-dir\":\"left\",\"tb-brdr-rds\":30,\"tb-brdr-rds-bttm\":30,\"tb-pddng-lft-rght\":25,\"tb-spc\":5,\"tb-brdr-stl\":\"dotted\",\"tb-brdr-clr\":\"#FFFFFF\",\"tb-txt-trnsfrm\":\"none\",\"tb-fnt-sz\":18,\"tb-fnt-fml\":\"arial, sans-serif\",\"tb-ctv-bck-clr\":\"#6F94FC\",\"tb-ctv-ttl-clr\":\"#FFFFFF\",\"tb-nctv-bck-clr\":\"#EDEDED\",\"tb-nctv-ttl-clr\":\"#2D3849\",\"tb-ln-width\":0,\"tb-ln-clr\":\"#D6D6D6\",\"cntnt-fnt-sz\":18,\"cntnt-fnt-fml\":\"inherit\",\"cntnt-dt-fnt-sz\":12,\"cntnt-clr\":\"#2D3849\",\"mg-brdr-rds\":20,\"cntnt-fll-brdr\":\"0\",\"pnl-brdr-rds\":0,\"pnl-fnt-sz\":14,\"pnl-fnt-fml\":\"arial, sans-serif\",\"pnl-dt-clr\":\"#6F94FC\",\"pnl-ctv-clr\":\"#FFFFFF\",\"pnl-ctv-ttl-clr\":\"#2D3849\",\"pnl-ctv-brdrs-stl\":\"solid\",\"pnl-ctv-brdrs-clr\":\"#E2E2E2\",\"pnl-nctv-clr\":\"#FFFFFF\",\"pnl-nctv-ttl-clr\":\"#2D3849\",\"pnl-nctv-brdrs-stl\":\"solid\",\"pnl-nctv-brdrs-clr\":\"#E2E2E2\",\"tgglr-nctv-bck-clr\":\"#E2E2E2\",\"tgglr-ctv-bck-clr\":\"#6F94FC\",\"tgglr-brdr-rds\":0}'),
('Simple', 1, 1, 1, 15, '{\"tb-hght\":47,\"tb-wdth\":\"auto\",\"tb-dir\":\"left\",\"tb-brdr-rds\":5,\"tb-brdr-rds-bttm\":0,\"tb-pddng-lft-rght\":15,\"tb-spc\":3,\"tb-brdr-stl\":\"none\",\"tb-brdr-clr\":\"#BFBFBF\",\"tb-txt-trnsfrm\":\"none\",\"tb-fnt-sz\":16,\"tb-fnt-fml\":\"arial, sans-serif\",\"tb-ctv-bck-clr\":\"#017EBA\",\"tb-ctv-ttl-clr\":\"#FFFFFF\",\"tb-nctv-bck-clr\":\"#E8E8E8\",\"tb-nctv-ttl-clr\":\"#000000\",\"tb-ln-width\":1,\"tb-ln-clr\":\"#017EBA\",\"cntnt-fnt-sz\":16,\"cntnt-fnt-fml\":\"arial, sans-serif\",\"cntnt-dt-fnt-sz\":12,\"cntnt-clr\":\"#666666\",\"mg-brdr-rds\":0,\"cntnt-fll-brdr\":\"1\",\"pnl-brdr-rds\":0,\"pnl-fnt-sz\":14,\"pnl-fnt-fml\":\"arial, sans-serif\",\"pnl-dt-clr\":\"#302C2C\",\"pnl-ctv-clr\":\"#FFFFFF\",\"pnl-ctv-ttl-clr\":\"#302C2C\",\"pnl-ctv-brdrs-stl\":\"solid\",\"pnl-ctv-brdrs-clr\":\"#E2E2E2\",\"pnl-nctv-clr\":\"#FFFFFF\",\"pnl-nctv-ttl-clr\":\"#302C2C\",\"pnl-nctv-brdrs-stl\":\"solid\",\"pnl-nctv-brdrs-clr\":\"#E2E2E2\",\"tgglr-nctv-bck-clr\":\"#E2E2E2\",\"tgglr-ctv-bck-clr\":\"#017EBA\",\"tgglr-brdr-rds\":0}'),
('Simple RTL', 1, 1, 1, 16, '{\"tb-hght\":47,\"tb-wdth\":\"auto\",\"tb-dir\":\"right\",\"tb-brdr-rds\":5,\"tb-brdr-rds-bttm\":0,\"tb-pddng-lft-rght\":15,\"tb-spc\":3,\"tb-brdr-stl\":\"none\",\"tb-brdr-clr\":\"#BFBFBF\",\"tb-txt-trnsfrm\":\"none\",\"tb-fnt-sz\":16,\"tb-fnt-fml\":\"arial, sans-serif\",\"tb-ctv-bck-clr\":\"#017EBA\",\"tb-ctv-ttl-clr\":\"#FFFFFF\",\"tb-nctv-bck-clr\":\"#E8E8E8\",\"tb-nctv-ttl-clr\":\"#000000\",\"tb-ln-width\":1,\"tb-ln-clr\":\"#017EBA\",\"cntnt-fnt-sz\":16,\"cntnt-fnt-fml\":\"arial, sans-serif\",\"cntnt-dt-fnt-sz\":12,\"cntnt-clr\":\"#666666\",\"mg-brdr-rds\":0,\"cntnt-fll-brdr\":\"1\",\"pnl-brdr-rds\":0,\"pnl-fnt-sz\":14,\"pnl-fnt-fml\":\"arial, sans-serif\",\"pnl-dt-clr\":\"#308806\",\"pnl-ctv-clr\":\"#FFFFFF\",\"pnl-ctv-ttl-clr\":\"#302C2C\",\"pnl-ctv-brdrs-stl\":\"solid\",\"pnl-ctv-brdrs-clr\":\"#E2E2E2\",\"pnl-nctv-clr\":\"#FFFFFF\",\"pnl-nctv-ttl-clr\":\"#302C2C\",\"pnl-nctv-brdrs-stl\":\"solid\",\"pnl-nctv-brdrs-clr\":\"#E2E2E2\",\"tgglr-nctv-bck-clr\":\"#E2E2E2\",\"tgglr-ctv-bck-clr\":\"#017EBA\",\"tgglr-brdr-rds\":0}');
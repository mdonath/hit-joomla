ALTER TABLE `#__kampinfo_hitcamp`
	ADD COLUMN `published` SMALLINT(3) NOT NULL default '0',
	ADD COLUMN `publish_up` DATETIME NOT NULL default '0000-00-00 00:00:00',
	ADD COLUMN `publish_down` DATETIME NOT NULL default '0000-00-00 00:00:00'
;
	

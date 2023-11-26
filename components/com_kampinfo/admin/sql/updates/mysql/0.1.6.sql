ALTER TABLE `#__kampinfo_hitcamp`
	ADD COLUMN `helpdeskContactpersoon` varchar(50) NULL AFTER `helpdeskTeOudMagAantal`;
ALTER TABLE `#__kampinfo_hitcamp`
	ADD COLUMN `helpdeskContactEmailadres` varchar(50) NULL AFTER `helpdeskContactpersoon`;
ALTER TABLE `#__kampinfo_hitcamp`
	ADD COLUMN `helpdeskContactTelefoonnummer` varchar(50) NULL AFTER `helpdeskContactEmailadres`;

ALTER TABLE `#__kampinfo_hitproject`
	ADD COLUMN `shantiEvenementId` INT(5) NULL AFTER `jaar`;

ALTER TABLE `#__kampinfo_hitcamp`
	ADD COLUMN `minimumAantalSubgroepjes` SMALLINT(3) NULL default 0 AFTER `aantalSubgroepen`;

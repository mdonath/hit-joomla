ALTER TABLE `#__kampinfo_hitsite` ADD COLUMN `afkorting` VARCHAR(3) NULL AFTER `bijdragePlaats`;
ALTER TABLE `#__kampinfo_hitsite` ADD COLUMN `budgetnummer` SMALLINT(4) NULL AFTER `afkorting`;

ALTER TABLE `#__kampinfo_hitcamp` ADD COLUMN `afkorting` VARCHAR(3) NULL AFTER `aantalDagenVoorAfdracht`;
ALTER TABLE `#__kampinfo_hitcamp` ADD COLUMN `budgetnummer` SMALLINT(4) NULL AFTER `afkorting`;
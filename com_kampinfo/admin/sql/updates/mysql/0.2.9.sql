ALTER TABLE `#__kampinfo_hitcamp` ADD COLUMN `maximumAantalDeelnemersOrigineel` SMALLINT(3) NULL AFTER `maximumAantalDeelnemers`;
UPDATE `#__kampinfo_hitcamp` SET `maximumAantalDeelnemersOrigineel` = `maximumAantalDeelnemers`;

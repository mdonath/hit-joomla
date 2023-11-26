ALTER TABLE `#__kampinfo_hitcamp` ADD COLUMN `minimumLeeftijdOuder` TINYINT NULL AFTER `maximumLeeftijd`;
ALTER TABLE `#__kampinfo_hitcamp` ADD COLUMN `maximumLeeftijdOuder` TINYINT NULL AFTER `minimumLeeftijdOuder`;

UPDATE `#__kampinfo_hitcamp` SET minimumLeeftijdOuder = 21, maximumLeeftijdOuder = 88 WHERE isouderkind = 1;

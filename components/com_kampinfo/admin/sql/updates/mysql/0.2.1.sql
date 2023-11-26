ALTER TABLE `#__kampinfo_hitcamp`
	ADD COLUMN `startElders` INT(5) NULL AFTER `start_elders`;

ALTER TABLE `#__kampinfo_hitcamp`
	ADD COLUMN `sublocatie` VARCHAR(255) NULL AFTER `startElders`;

ALTER TABLE `#__kampinfo_hitcamp`
	DROP COLUMN `start_elders`;

ALTER TABLE `#__kampinfo_hitcamp`
	ADD COLUMN `youtube` VARCHAR(11) NULL AFTER `webadresFoto3`;
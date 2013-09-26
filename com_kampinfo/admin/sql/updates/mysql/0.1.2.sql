ALTER TABLE `#__kampinfo_hitsite`
	ADD COLUMN `asset_id` INT(10) NOT NULL DEFAULT '0' AFTER `id`;

ALTER TABLE `#__kampinfo_hitcamp`
	ADD COLUMN `asset_id` INT(10) NOT NULL DEFAULT '0' AFTER `id`;

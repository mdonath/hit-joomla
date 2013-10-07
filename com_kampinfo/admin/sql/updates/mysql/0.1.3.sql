ALTER TABLE `#__kampinfo_hitsite`
	ADD COLUMN `hitproject_id` INT(11) NOT NULL DEFAULT '0' AFTER `asset_id`;

ALTER TABLE `#__kampinfo_hitcamp`
	ADD COLUMN `hitsite_id` INT(11) NOT NULL DEFAULT '0' AFTER `asset_id`;

UPDATE `#__kampinfo_hitsite` s
	SET s.hitproject_id = (SELECT id FROM `#__kampinfo_hitproject` p WHERE s.jaar = p.jaar);

UPDATE `#__kampinfo_hitcamp` c
	SET c.hitsite_id = (SELECT id FROM `#__kampinfo_hitsite` s WHERE s.code = c.hitsite);

alter table `#__kampinfo_hitsite`
	add constraint `#__kampinfo_hitsite_project_fk` foreign key (hitproject_id) references `#__kampinfo_hitproject` (id);

alter table `#__kampinfo_hitcamp`
	add constraint `#__kampinfo_hitcamp_site_fk` foreign key (hitsite_id) references `#__kampinfo_hitsite` (id);

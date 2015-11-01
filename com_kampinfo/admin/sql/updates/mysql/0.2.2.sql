ALTER TABLE `#__kampinfo_hitcamp`
	ADD COLUMN `aantalMedewerkers`			SMALLINT(3) NULL					AFTER `helpdeskContactpersoon`;

ALTER TABLE `#__kampinfo_hitcamp`
	ADD COLUMN `aantalDagenVoorAfdracht`	SMALLINT(1) NULL	DEFAULT 2		AFTER `aantalMedewerkers`;


ALTER TABLE `#__kampinfo_hitsite`
	ADD COLUMN `aantalMedewerkers`			SMALLINT(3) NULL					AFTER `akkoordHitPlaats`;

ALTER TABLE `#__kampinfo_hitsite`
	ADD COLUMN `medewerkersBijdrage`		DECIMAL(10,2) NULL					AFTER `aantalMedewerkers`;

ALTER TABLE `#__kampinfo_hitsite`
	ADD COLUMN `bijdragePlaats`				DECIMAL(10,2) NULL					AFTER `medewerkersBijdrage`;

	
ALTER TABLE `#__kampinfo_hitproject`
	ADD COLUMN `bijdrageProjectteam`		DECIMAL(10,2)		DEFAULT 2.00	AFTER `maandag`;
	
ALTER TABLE `#__kampinfo_hitproject`
	ADD COLUMN `bijdrageCalamiteitenfonds`	DECIMAL(10,2)		DEFAULT 1.50	AFTER `bijdrageProjectteam`;
	
ALTER TABLE `#__kampinfo_hitproject`
	ADD COLUMN `bijdrageLSC`				DECIMAL(10,2)		DEFAULT 1.00	AFTER `bijdrageCalamiteitenfonds`;

ALTER TABLE `#__kampinfo_hitproject`
	ADD COLUMN 	`percentageReservering`		SMALLINT(2)			DEFAULT 10		AFTER `bijdrageLSC`;


UPDATE `#__kampinfo_hitcamp`
SET aantalDagenVoorAfdracht = 2
WHERE hitsite_id IN (
	SELECT s.id FROM `#__kampinfo_hitsite` s JOIN `#__kampinfo_hitproject` p ON (p.id = s.hitproject_id AND p.jaar = 2016)
)
;

UPDATE `#__kampinfo_hitproject`
SET	bijdrageProjectteam = 2.0
,	bijdrageCalamiteitenfonds = 1.5
,	bijdrageLSC = 1.0
,	percentageReservering = 10
WHERE jaar = 2016
;
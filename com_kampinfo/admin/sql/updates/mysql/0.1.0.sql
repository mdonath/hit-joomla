ALTER TABLE `#__kampinfo_hitsite` ADD COLUMN `akkoordHitPlaats` BOOLEAN;


ALTER TABLE `#__kampinfo_hitcamp` ADD COLUMN `doelstelling` TEXT;
ALTER TABLE `#__kampinfo_hitcamp` ADD COLUMN `hitCourantTekst` TEXT;
ALTER TABLE `#__kampinfo_hitcamp` ADD COLUMN `helpdeskOpmerkingen` TEXT;

ALTER TABLE `#__kampinfo_hitcamp` ADD COLUMN `helpdeskOverschrijdingAantal` SMALLINT(3);
ALTER TABLE `#__kampinfo_hitcamp` ADD COLUMN `helpdeskOverschrijdingLeeftijd` BOOLEAN;
ALTER TABLE `#__kampinfo_hitcamp` ADD COLUMN `helpdeskTeJongMagAantal` SMALLINT(3);
ALTER TABLE `#__kampinfo_hitcamp` ADD COLUMN `helpdeskTeOudMagAantal` SMALLINT(3);

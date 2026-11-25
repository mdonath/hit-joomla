ALTER TABLE `#__kampinfo_hitproject`
    ADD COLUMN `zichtbaarVanaf`     DATETIME     NULL DEFAULT NULL
,   ADD COLUMN `courantOnlineVanaf` DATETIME     NULL DEFAULT NULL
,   ADD COLUMN `courantLink`        VARCHAR(255) NULL DEFAULT NULL
,   ADD COLUMN `courantVoorkant`    VARCHAR(255) NULL DEFAULT NULL
,   ADD COLUMN `tweedeRondeTekst`   VARCHAR(255) NULL DEFAULT NULL
;

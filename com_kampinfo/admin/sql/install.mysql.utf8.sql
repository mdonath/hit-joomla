DROP TABLE IF EXISTS `#__kampinfo_hitproject`;
DROP TABLE IF EXISTS `#__kampinfo_hitsite`;
DROP TABLE IF EXISTS `#__kampinfo_hitcamp`;


CREATE TABLE `#__kampinfo_hitproject` (
	`id` int(11) NOT NULL AUTO_INCREMENT
,	`jaar` YEAR(4) NOT NULL
,	`inschrijvingStartdatum` DATE
,	`inschrijvingEinddatum` DATE
,	`inschrijvingWijzigenTotDatum` DATE
,	`inschrijvingKosteloosAnnulerenDatum` DATE
,	`inschrijvingGeenRestitutieDatum` DATE
,	`inningsdatum` DATE
,	PRIMARY KEY (`id`)
);

CREATE TABLE `#__kampinfo_hitsite` (
	`id` int(11) NOT NULL AUTO_INCREMENT
,	`code` VARCHAR(20) NOT NULL
,	`naam` VARCHAR(50) NOT NULL
,	`jaar` YEAR(4) NOT NULL
,	`deelnemersnummer` INT
,	`hitCourantTekst` TEXT
,	`contactPersoonNaam` VARCHAR(50)
,	`contactPersoonEmail` VARCHAR(50)
,	`contactPersoonTelefoon` VARCHAR(50)
,	PRIMARY KEY (`id`)
);

CREATE TABLE `#__kampinfo_hitcamp` (
	`id` int(11) NOT NULL AUTO_INCREMENT
,	`naam` VARCHAR(50) NOT NULL
,	`hitsite` varchar(20) NOT NULL
,	PRIMARY KEY (`id`)
);

-- TEST DATA
INSERT INTO `#__kampinfo_hitproject` (
	`jaar`
,	`inschrijvingStartdatum`
,	`inschrijvingEinddatum`
,	`inschrijvingWijzigenTotDatum`
,	`inschrijvingKosteloosAnnulerenDatum`
,	`inschrijvingGeenRestitutieDatum`
,	`inningsdatum`
) VALUES
	(2012, '2012-01-02', '2012-03-09', '2012-03-09', '2012-03-09', '2012-03-10', '2012-03-19')
,	(2013, '2013-01-02', '2013-03-09', '2013-03-09', '2013-03-09', '2013-03-10', '2013-03-19')
;

INSERT INTO `#__kampinfo_hitsite` (
	`naam`
,	`code`
,	`jaar`
,	`contactPersoonEmail`
) VALUES
	('Alphen', 'alphen-2012', 2012, 'voorzitter@hitalphen.scouting.nl')
,	('Dwingeloo', 'dwingeloo-2012', 2012, 'voorzitter@hitdwingeloo.scouting.nl')
,	('Harderwijk', 'harderwijk-2012', 2012, 'voorzitter@hitharderwijk.scouting.nl')
,	('Hilversum', 'hilversum-2012', 2012, 'voorzitter@hithilversum.scouting.nl')
,	('Mook', 'mook-2012', 2012, 'voorzitter@hitmook.scouting.nl')
,	('Zeeland', 'zeeland-2012', 2012, 'voorzitter@hitzeeland.scouting.nl')

,	('Alphen', 'alphen-2013', 2013, 'voorzitter@hitalphen.scouting.nl')
,	('Baarn', 'baarn-2013', 2013, 'voorzitter@hithilversum.scouting.nl')
,	('Dwingeloo', 'dwingeloo-2013', 2013, 'voorzitter@hitdwingeloo.scouting.nl')
,	('Harderwijk', 'harderwijk-2013', 2013, 'voorzitter@hitharderwijk.scouting.nl')
,	('Mook', 'mook-2013', 2013, 'voorzitter@hitmook.scouting.nl')
,	('Zeeland', 'zeeland-2013', 2013, 'voorzitter@hitzeeland.scouting.nl')
;

INSERT INTO `#__kampinfo_hitcamp` (
	`naam`
,	`hitsite`
) VALUES
	('Stookkamp', 'mook-2012')
,	('Kokkamp eXtreme', 'harderwijk-2012')
,	('Kokkamp eXtreme', 'mook-2013')
;
DROP TABLE IF EXISTS `#__kampinfo_hitproject`;
DROP TABLE IF EXISTS `#__kampinfo_hitsite`;
DROP TABLE IF EXISTS `#__kampinfo_hitcamp`;


CREATE TABLE `#__kampinfo_hitproject` (
	`id`									INT(11)		NOT NULL	AUTO_INCREMENT
,	`jaar`									YEAR(4)		NOT NULL
,	`inschrijvingStartdatum`				DATE
,	`inschrijvingEinddatum`					DATE
,	`inschrijvingWijzigenTotDatum`			DATE
,	`inschrijvingKosteloosAnnulerenDatum`	DATE
,	`inschrijvingGeenRestitutieDatum`		DATE
,	`inningsdatum`							DATE
,	`vrijdag`								DATE
,	`maandag`								DATE
,	PRIMARY KEY (`id`)
);

CREATE TABLE `#__kampinfo_hitsite` (
	`id`									INT(11)		NOT NULL	AUTO_INCREMENT
,	`code`									VARCHAR(20)	NOT NULL
,	`naam`									VARCHAR(50)	NOT NULL
,	`jaar`									YEAR(4)		NOT NULL
,	`deelnemersnummer`						INT
,	`hitCourantTekst`						TEXT
,	`contactPersoonNaam`					VARCHAR(50)
,	`contactPersoonEmail`					VARCHAR(50)
,	`contactPersoonTelefoon`				VARCHAR(50)
,	PRIMARY KEY (`id`)
);

CREATE TABLE `#__kampinfo_hitcamp` (
	`id`									INT(11)		NOT NULL	AUTO_INCREMENT
,	`naam`									VARCHAR(50)	NOT NULL
,	`hitsite`								VARCHAR(20)	NOT NULL
,	`deelnemersnummer`						INT(10)
,	`minimumLeeftijd`						TINYINT
,	`maximumLeeftijd`						TINYINT
,	`deelnamekosten`						SMALLINT(3)
,	`groep`									VARCHAR(20)
,	`websiteTekst`							TEXT
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
,	`minimumLeeftijd`
,	`maximumLeeftijd`
,	`deelnamekosten`
,	`websiteTekst`
) VALUES
	('Stookkamp', 'mook-2012', 8, 14, 50, 'Fikkiestoken Fikkiestoken Fikkiestoken Fikkiestoken Fikkiestoken Fikkiestoken!')
,	('Stookkamp', 'mook-2013', 8, 14, 50, 'Fikkiestoken Fikkiestoken Fikkiestoken Fikkiestoken Fikkiestoken Fikkiestoken!')
,	('Kokkamp eXtreme', 'harderwijk-2012', 10, 15, 90, 'Lekker eten! Lekker eten! Lekker eten! Lekker eten! Lekker eten! Lekker eten! Lekker eten! Lekker eten! Lekker eten! Lekker eten! Lekker eten! Lekker eten! Lekker eten! Lekker eten! Lekker eten! Lekker eten! Lekker eten! Lekker eten! Lekker eten! Lekker eten! ')
,	('Kokkamp eXtreme', 'mook-2013', 14, 18, 100, 'Explosief bakken!!! Explosief bakken!!! Explosief bakken!!! Explosief bakken!!! Explosief bakken!!! Explosief bakken!!! Explosief bakken!!! Explosief bakken!!! Explosief bakken!!! Explosief bakken!!! Explosief bakken!!! Explosief bakken!!! Explosief bakken!!! Explosief bakken!!! Explosief bakken!!! Explosief bakken!!! Explosief bakken!!! Explosief bakken!!! Explosief bakken!!! Explosief bakken!!! Explosief bakken!!! Explosief bakken!!! ')
,	('Punniken voor gevorderden', 'alphen-2013', 18, 32, 40, 'Punnik punnik punnik punnik punnik punnik punnik punnik punnik punnik punnik punnik punnik punnik punnik punnik punnik ')
,	('Jongerenhike in Dwingeloo', 'dwingeloo-2013', 18, 32, 40, 'Zuip zuip zuip zuip zuip zuip zuip zuip zuip zuip zuip zuip zuip zuip zuip zuip zuip zuip zuip zuip zuip: verzuipen in de regen natuurlijk.')
,	('MPSE', 'baarn-2013', 18, 32, 90, 'Chop chop chop chop chop chop chop chop chop chop chop chop chop chop chop chop chop chop chop chop chop chop ')
,	('LAN-HIT', 'harderwijk-2013', 14, 20, 90, 'Typ typ typ typ typ typ typ typ typ typ typ typ typ typ typ typ typ typ typ typ typ typ ')
,	('Bourgondische Bier Brouwers', 'zeeland-2013', 18, 40, 90, 'Brouw eet drink rij repeat eet drink rij repeat eet drink rij repeat eet drink rij repeat eet drink rij repeat eet drink rij repeat ')
;
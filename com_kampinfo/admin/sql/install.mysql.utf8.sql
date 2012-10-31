DROP TABLE IF EXISTS `#__kampinfo_hitproject`;
DROP TABLE IF EXISTS `#__kampinfo_hitsite`;
DROP TABLE IF EXISTS `#__kampinfo_hitcamp`;
DROP TABLE IF EXISTS `#__kampinfo_hiticon`;


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

CREATE TABLE `#__kampinfo_hiticon` (
	`id`									INT(11)			NOT NULL	AUTO_INCREMENT
,	`volgorde`								INT(10)			NOT NULL
,	`bestandsnaam`							VARCHAR(20)		NOT NULL
,	`tekst`									VARCHAR(255)	NOT NULL
,	`soort`									CHAR(1)
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

INSERT INTO `#__kampinfo_hiticon` (
	`volgorde`
,	`bestandsnaam`
,	`tekst`
,	`soort`
) VALUES
	(1, 'staand', 'Staand kamp', 'B')
,	(2, 'fiets', 'Trekken per fiets', 'B')
,	(3, 'hike', 'Trekken met rugzak', 'B')
,	(4, 'kano', 'Trekken per kano', 'B')
,	(5, 'zeilboot', 'Trekkend per boot', 'B')
,	(6, 'geenrugz', 'Lopen zonder rugzak', 'B')
,	(7, 'hikevr', 'Lopen met een ander voorwerp', 'B')
,	(8, 'auto', 'Trekkend per auto', 'B')

,	(9, '0pers', 'Inschrijven per persoon', 'I')
,	(10, 'groepje', 'Inschrijven per groep', 'I')

,	(11, 'tent', 'Overnachten in een zelfmeegenomen tent', 'O')
,	(12, 'friet', 'Overnachten in een frietbuil', 'O')
,	(13, 'nacht', 'Overnachten zonder tent', 'O')
,	(14, 'tent_opgezet', 'Overnachten in tenten verzorgd door staf', 'O')
,	(15, 'gebouw', 'Overnachten in gebouw', 'O')
,	(16, 'bootslaap', 'Overnachten op een boot', 'O')

,	(17, '5km', 'Totale afstand is 5 km', 'A')
,	(18, '15km', 'Totale afstand is 15 km', 'A')
,	(19, '20km', 'Totale afstand is 20 km', 'A')
,	(20, '25km', 'Totale afstand is 25 km', 'A')
,	(21, '30km', 'Totale afstand is 30 km', 'A')
,	(22, '35km', 'Totale afstand is 35 km', 'A')
,	(23, '40km', 'Totale afstand is 40 km', 'A')
,	(24, '45km', 'Totale afstand is 45 km', 'A')
,	(25, '50km', 'Totale afstand is 50 km', 'A')
,	(26, '55km', 'Totale afstand is 55 km', 'A')
,	(27, '60km', 'Totale afstand is 60 km', 'A')
,	(28, '80km', 'Totale afstand is 80 km', 'A')
,	(29, '100km', 'Totale afstand is 100 km', 'A')
,	(30, '120km', 'Totale afstand is 120 km', 'A')

,	(31, 'vuur', 'Koken op houtvuur zonder pannen', 'K')
,	(32, 'opvuur', 'Koken op houtvuur met pannen', 'K')
,	(33, 'gas', 'Koken op gas met pannen', 'K')
,	(34, 'stafkookt', 'Gekookt door de staf', 'K')

,	(35, 'k_ks', 'Kennis van kaart en kompas op eenvoudig niveau', '?')
,	(36, 'k_kv', 'Kennis van kaart en kompas op gevorderd niveau', '?')
,	(37, 'k_kgv', 'Kennis van kaart en kompas op specialistisch niveau', '?')
,	(38, 'insigne', 'Activiteit waarmee een insigne kan worden behaald', '?')

,	(39, 'zwem', 'Zwemdiploma verplicht', '?')
,	(40, 'mobieltje', 'Mobieltje meenemen', '?')
,	(41, 'geenmobieltje', 'Mobieltjes zijn verboden', '?')
,	(42, 'rolstoel', 'Geschikt voor minder validen (rolstoel)', '?')
,	(43, 'vraagt', 'Vraagteken Mysterie elementen', '?')
,	(44, 'buitenland', 'Buitenland - ID kaart of paspoort verplicht', '?')
;
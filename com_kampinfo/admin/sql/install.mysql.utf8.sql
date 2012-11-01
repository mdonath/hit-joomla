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
,	`deelnemersnummer`						INT(10)		NOT NULL
,	`jaar`									YEAR(4)		NOT NULL
,	`code`									VARCHAR(20)	NOT NULL
,	`naam`									VARCHAR(50)	NOT NULL
,	`hitCourantTekst`						TEXT
,	`contactPersoonNaam`					VARCHAR(50)
,	`contactPersoonEmail`					VARCHAR(50)
,	`contactPersoonTelefoon`				VARCHAR(50)
,	PRIMARY KEY (`id`)
);

CREATE TABLE `#__kampinfo_hitcamp` (
	`id`									INT(11)		NOT NULL	AUTO_INCREMENT
,	`deelnemersnummer`						INT(10)		NOT NULL
,	`hitsite`								VARCHAR(20)	NOT NULL
,	`naam`									VARCHAR(50)	NOT NULL
,	`minimumLeeftijd`						TINYINT
,	`maximumLeeftijd`						TINYINT
,	`deelnamekosten`						SMALLINT(3)
,	`groep`									VARCHAR(20)
,	`websiteTekst`							TEXT
,	`icoontjes`								TEXT
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
,	`deelnemersnummer`
,	`code`
,	`jaar`
,	`contactPersoonEmail`
) VALUES
	('Alphen', 121000, 'alphen-2012', 2012, 'voorzitter@hitalphen.scouting.nl')
,	('Dwingeloo', 122000, 'dwingeloo-2012', 2012, 'voorzitter@hitdwingeloo.scouting.nl')
,	('Harderwijk', 123000, 'harderwijk-2012', 2012, 'voorzitter@hitharderwijk.scouting.nl')
,	('Hilversum', 124000, 'hilversum-2012', 2012, 'voorzitter@hithilversum.scouting.nl')
,	('Mook', 125000, 'mook-2012', 2012, 'voorzitter@hitmook.scouting.nl')
,	('Zeeland', 126000, 'zeeland-2012', 2012, 'voorzitter@hitzeeland.scouting.nl')

,	('Alphen', 131000, 'alphen-2013', 2013, 'voorzitter@hitalphen.scouting.nl')
,	('Baarn', 132000, 'baarn-2013', 2013, 'voorzitter@hithilversum.scouting.nl')
,	('Dwingeloo', 13300, 'dwingeloo-2013', 2013, 'voorzitter@hitdwingeloo.scouting.nl')
,	('Harderwijk', 134000, 'harderwijk-2013', 2013, 'voorzitter@hitharderwijk.scouting.nl')
,	('Mook', 135000, 'mook-2013', 2013, 'voorzitter@hitmook.scouting.nl')
,	('Zeeland', 136000, 'zeeland-2013', 2013, 'voorzitter@hitzeeland.scouting.nl')
;





INSERT INTO `#__kampinfo_hitcamp` (
	`deelnemersnummer`
,	`naam`
,	`hitsite`
,	`minimumLeeftijd`
,	`maximumLeeftijd`
,	`deelnamekosten`
,	`websiteTekst`
) VALUES
	(125001, 'Stookkamp', 'mook-2012', 8, 14, 50, 'Fikkiestoken Fikkiestoken Fikkiestoken Fikkiestoken Fikkiestoken Fikkiestoken!')
,	(135001, 'Stookkamp', 'mook-2013', 8, 14, 50, 'Fikkiestoken Fikkiestoken Fikkiestoken Fikkiestoken Fikkiestoken Fikkiestoken!')
,	(123001, 'Kokkamp eXtreme', 'harderwijk-2012', 10, 15, 90, 'Lekker eten! Lekker eten! Lekker eten! Lekker eten! Lekker eten! Lekker eten! Lekker eten! Lekker eten! Lekker eten! Lekker eten! Lekker eten! Lekker eten! Lekker eten! Lekker eten! Lekker eten! Lekker eten! Lekker eten! Lekker eten! Lekker eten! Lekker eten! ')
,	(135002, 'Kokkamp eXtreme', 'mook-2013', 14, 18, 100, 'Explosief bakken!!! Explosief bakken!!! Explosief bakken!!! Explosief bakken!!! Explosief bakken!!! Explosief bakken!!! Explosief bakken!!! Explosief bakken!!! Explosief bakken!!! Explosief bakken!!! Explosief bakken!!! Explosief bakken!!! Explosief bakken!!! Explosief bakken!!! Explosief bakken!!! Explosief bakken!!! Explosief bakken!!! Explosief bakken!!! Explosief bakken!!! Explosief bakken!!! Explosief bakken!!! Explosief bakken!!! ')
,	(131001, 'Punniken voor gevorderden', 'alphen-2013', 18, 32, 40, 'Punnik punnik punnik punnik punnik punnik punnik punnik punnik punnik punnik punnik punnik punnik punnik punnik punnik ')
,	(133001, 'Jongerenhike in Dwingeloo', 'dwingeloo-2013', 18, 32, 40, 'Zuip zuip zuip zuip zuip zuip zuip zuip zuip zuip zuip zuip zuip zuip zuip zuip zuip zuip zuip zuip zuip: verzuipen in de regen natuurlijk.')
,	(132001, 'MPSE', 'baarn-2013', 18, 32, 90, 'Chop chop chop chop chop chop chop chop chop chop chop chop chop chop chop chop chop chop chop chop chop chop ')
,	(134001, 'LAN-HIT', 'harderwijk-2013', 14, 20, 90, 'Typ typ typ typ typ typ typ typ typ typ typ typ typ typ typ typ typ typ typ typ typ typ ')
,	(136001, 'Bourgondische Bier Brouwers', 'zeeland-2013', 18, 40, 90, 'Brouw eet drink rij repeat eet drink rij repeat eet drink rij repeat eet drink rij repeat eet drink rij repeat eet drink rij repeat ')
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

,	(17, '0km', 'Totale afstand is 0 km', 'A')
,	(18, '5km', 'Totale afstand is 5 km', 'A')
,	(19, '15km', 'Totale afstand is 15 km', 'A')
,	(20, '20km', 'Totale afstand is 20 km', 'A')
,	(21, '25km', 'Totale afstand is 25 km', 'A')
,	(22, '30km', 'Totale afstand is 30 km', 'A')
,	(23, '35km', 'Totale afstand is 35 km', 'A')
,	(24, '40km', 'Totale afstand is 40 km', 'A')
,	(25, '45km', 'Totale afstand is 45 km', 'A')
,	(26, '50km', 'Totale afstand is 50 km', 'A')
,	(27, '55km', 'Totale afstand is 55 km', 'A')
,	(28, '60km', 'Totale afstand is 60 km', 'A')
,	(29, '65km', 'Totale afstand is 65 km', 'A')
,	(30, '70km', 'Totale afstand is 70 km', 'A')
,	(31, '75km', 'Totale afstand is 75 km', 'A')
,	(32, '80km', 'Totale afstand is 80 km', 'A')
,	(33, '85km', 'Totale afstand is 85 km', 'A')
,	(34, '90km', 'Totale afstand is 90 km', 'A')
,	(35, '100km', 'Totale afstand is 100 km', 'A')
,	(36, '120km', 'Totale afstand is 120 km', 'A')
,	(37, '150km', 'Totale afstand is 150 km', 'A')

,	(38, 'vuur', 'Koken op houtvuur zonder pannen', 'K')
,	(39, 'opvuur', 'Koken op houtvuur met pannen', 'K')
,	(40, 'gas', 'Koken op gas met pannen', 'K')
,	(41, 'stafkookt', 'Gekookt door de staf', 'K')

,	(42, 'k_ks', 'Kennis van kaart en kompas op eenvoudig niveau', '?')
,	(43, 'k_kv', 'Kennis van kaart en kompas op gevorderd niveau', '?')
,	(44, 'k_kgv', 'Kennis van kaart en kompas op specialistisch niveau', '?')
,	(45, 'insigne', 'Activiteit waarmee een insigne kan worden behaald', '?')

,	(46, 'zwem', 'Zwemdiploma verplicht', '?')
,	(47, 'mobieltje', 'Mobieltje meenemen', '?')
,	(48, 'geenmobieltje', 'Mobieltjes zijn verboden', '?')
,	(49, 'rolstoel', 'Geschikt voor minder validen (rolstoel)', '?')
,	(50, 'vraagt', 'Vraagteken Mysterie elementen', '?')
,	(51, 'buitenland', 'Buitenland - ID kaart of paspoort verplicht', '?')
;

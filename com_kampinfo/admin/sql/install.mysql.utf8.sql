DROP TABLE IF EXISTS `#__kampinfo_hitproject`;
DROP TABLE IF EXISTS `#__kampinfo_hitsite`;
DROP TABLE IF EXISTS `#__kampinfo_hitcamp`;
DROP TABLE IF EXISTS `#__kampinfo_hiticon`;
DROP TABLE IF EXISTS `#__kampinfo_downloads`;
DROP TABLE IF EXISTS `#__kampinfo_deelnemers`;

CREATE TABLE `#__kampinfo_hitproject` (
	`id`									INT(11)		NOT NULL	AUTO_INCREMENT
,	`jaar`									YEAR(4)		NOT NULL
,	`shantiEvenementId`						INT(5)
,	`inschrijvingStartdatum`				DATETIME
,	`inschrijvingEinddatum`					DATETIME
,	`inschrijvingWijzigenTotDatum`			DATETIME
,	`inschrijvingKosteloosAnnulerenDatum`	DATETIME
,	`inschrijvingGeenRestitutieDatum`		DATETIME
,	`inningsdatum`							DATETIME
,	`vrijdag`								DATETIME
,	`maandag`								DATETIME
,	`thema`									VARCHAR(255)
,	`ouderkind`								TEXT

,	`bijdrageProjectteam`					DECIMAL(10,2)			DEFAULT 2.00
,	`bijdrageCalamiteitenfonds`				DECIMAL(10,2)			DEFAULT 1.50
,	`bijdrageLSC`							DECIMAL(10,2)			DEFAULT 1.00
,	`percentageReservering`					SMALLINT(2)				DEFAULT 10

,	PRIMARY KEY (`id`)
);

CREATE TABLE `#__kampinfo_hitsite` (
	`id`									INT(11)		NOT NULL	AUTO_INCREMENT
,	`asset_id`								INT(10)		NOT NULL	DEFAULT '0'
,	`hitproject_id`							INT(11)		NOT NULL
,	`naam`									VARCHAR(50)	NOT NULL
,	`projectcode`							VARCHAR(50)
,	`hitCourantTekst`						TEXT
,	`contactPersoonNaam`					VARCHAR(50)
,	`contactPersoonEmail`					VARCHAR(50)
,	`contactPersoonTelefoon`				VARCHAR(50)
,	`akkoordHitPlaats`						BOOLEAN

,	`aantalMedewerkers`						SMALLINT(3)
,	`medewerkersBijdrage`					DECIMAL(10,2)
,	`bijdragePlaats`						DECIMAL(10,2)
,	`afkorting`								VARCHAR(3)
,	`budgetnummer`							SMALLINT(4)							

,	`published`								SMALLINT(3) NOT NULL	DEFAULT '0'
,	PRIMARY KEY (`id`)
);

alter table `#__kampinfo_hitsite`
	add constraint `#__kampinfo_hitsite_project_fk` foreign key (hitproject_id) references `#__kampinfo_hitproject` (id);


CREATE TABLE `#__kampinfo_hitcamp` (
	`id`									INT(11)			NOT NULL	AUTO_INCREMENT
,	`asset_id`								INT(10)			NOT NULL	DEFAULT '0'
,	`hitsite_id`							INT(11)			NOT NULL
-- ,	`deelnemersnummer`						INT(10)			NOT NULL
,	`shantiFormuliernummer`					INT(10)
,	`ouderShantiFormuliernummer`			INT(10)
,	`extraShantiFormuliernummer`			INT(10)
-- ,	`hitsite`								VARCHAR(20)		NOT NULL
,	`naam`									VARCHAR(255)	NOT NULL
,	`isouderkind`							BOOLEAN
,	`optieAlleenOuderLid`					BOOLEAN				NULL DEFAULT 0
,	`startElders`							BOOLEAN
,	`sublocatie`							VARCHAR(255)
,	`activiteitengebieden`					TEXT
,	`titeltekst`							VARCHAR(255)
,	`startDatumTijd`						DATETIME
,	`eindDatumTijd`							DATETIME
,	`deelnamekosten`						SMALLINT(3)
,	`minimumLeeftijd`						TINYINT
,	`maximumLeeftijd`						TINYINT
,	`minimumLeeftijdOuder`					TINYINT						DEFAULT 21
,	`maximumLeeftijdOuder`					TINYINT						DEFAULT 88
,	`subgroepsamenstellingMinimum`			SMALLINT(2)
,	`subgroepsamenstellingMaximum`			SMALLINT(2)
,	`subgroepsamenstellingExtra`			SMALLINT(1)
,	`icoontjes`								TEXT
,	`websiteAdres`							VARCHAR(255)
,	`websiteTekst`							TEXT
,	`webadresFoto1`							VARCHAR(255)
,	`webadresFoto2`							VARCHAR(255)
,	`webadresFoto3`							VARCHAR(255)
,	`youtube`								VARCHAR(11)
,	`websiteContactTelefoonnummer`			VARCHAR(255)
,	`websiteContactEmailadres`				VARCHAR(255)
,	`websiteContactpersoon`					VARCHAR(255)
,	`minimumAantalDeelnemers`				SMALLINT(3)
,	`aantalDeelnemers`						SMALLINT(3)
,	`gereserveerd`							SMALLINT(3)
,	`maximumAantalDeelnemers`				SMALLINT(3)
,	`maximumAantalDeelnemersOrigineel`		SMALLINT(3)
,	`minimumAantalSubgroepjes`				SMALLINT(3)
,	`aantalSubgroepen`						SMALLINT(2)
,	`maximumAantalSubgroepjes`				INT(6)
,	`maximumAantalUitEenGroep`				SMALLINT(3)
,	`margeAantalDagenTeJong`				SMALLINT(3)
,	`margeAantalDagenTeOud`					SMALLINT(3)
,	`redenAfwijkingMarge`					VARCHAR(255)
,	`doelgroepen`							TEXT
,	`doelstelling`							TEXT
,	`hitCourantTekst`						TEXT
,	`helpdeskOpmerkingen`					TEXT
,	`helpdeskOverschrijdingAantal`			SMALLINT(3)
,	`helpdeskOverschrijdingLeeftijd` 		BOOLEAN
,	`helpdeskTeJongMagAantal`				SMALLINT(3)
,	`helpdeskTeOudMagAantal`				SMALLINT(3)
,	`helpdeskContactEmailadres`				VARCHAR(50)
,	`helpdeskContactTelefoonnummer`			VARCHAR(50)
,	`helpdeskContactpersoon`				VARCHAR(50)

,	`aantalMedewerkers`						SMALLINT(3)
,	`aantalDagenVoorAfdracht`				SMALLINT(1)					DEFAULT 2
,	`afkorting`								VARCHAR(4)
,	`budgetnummer`							SMALLINT(4)


,	`akkoordHitKamp`						BOOLEAN
,	`akkoordHitPlaats`						BOOLEAN
,	`published`								SMALLINT(3)		NOT NULL	DEFAULT '0'
,	`publish_up`							DATETIME		NOT NULL	DEFAULT '0000-00-00 00:00:00'
,	`publish_down`							DATETIME		NOT NULL	DEFAULT '0000-00-00 00:00:00'
,	PRIMARY KEY (`id`)
);


alter table `#__kampinfo_hitcamp`
	add constraint `#__kampinfo_hitcamp_site_fk` foreign key (hitsite_id) references `#__kampinfo_hitsite` (id);

CREATE TABLE `#__kampinfo_hiticon` (
	`id`									INT(11)			NOT NULL	AUTO_INCREMENT
,	`volgorde`								INT(10)			NOT NULL
,	`bestandsnaam`							VARCHAR(20)		NOT NULL
,	`tekst`									VARCHAR(255)	NOT NULL
,	`uitleg`								TEXT
,	`soort`									CHAR(1)
,	PRIMARY KEY (`id`)
);

CREATE TABLE `#__kampinfo_downloads` (
	`id`									INT(11)		NOT NULL	AUTO_INCREMENT
,	`jaar`									YEAR(4)		NOT NULL
,	`soort`									VARCHAR(4)	NOT NULL
,	`bijgewerktOp`							TIMESTAMP	NOT NULL	DEFAULT CURRENT_TIMESTAMP
,	`melding`								TEXT
,	PRIMARY KEY (`id`)
);

CREATE TABLE `#__kampinfo_deelnemers` (
	`id`									INT(11)			NOT NULL	AUTO_INCREMENT
,	`jaar`									YEAR(4)			NOT NULL
,	`dlnnr`									INT(10)			NOT NULL
,	`herkomst`								VARCHAR(60)		NOT NULL
,	`leeftijd`								TINYINT			NOT NULL
,	`geslacht`								CHAR(1)			NOT NULL
,	`datumInschrijving`						DATE			NOT NULL
,	`hitsite`								VARCHAR(20)		NOT NULL
,	`hitcamp`								VARCHAR(255)	NOT NULL
,	`hitcampId`								INT(11)			NULL
,	PRIMARY KEY (`id`)
);


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
,	(38, 'kano10', 'Afstand per kano is 10 km', 'A')
,	(39, 'fiets60', 'Afstand per fiets is 60 km', 'A')

,	(40, 'vuur', 'Koken op houtvuur zonder pannen', 'K')
,	(41, 'opvuur', 'Koken op houtvuur met pannen', 'K')
,	(42, 'gas', 'Koken op gas met pannen', 'K')
,	(43, 'stafkookt', 'Gekookt door de staf', 'K')

,	(45, 'k_ks', 'Kennis van kaart en kompas op eenvoudig niveau', '?')
,	(46, 'k_kv', 'Kennis van kaart en kompas op gevorderd niveau', '?')
,	(47, 'k_kgv', 'Kennis van kaart en kompas op specialistisch niveau', '?')
,	(49, 'insigne', 'Activiteit waarmee een insigne kan worden behaald', '?')

,	(50, 'zwem', 'Zwemdiploma verplicht', '?')
,	(51, 'mobieltje', 'Mobieltje meenemen', '?')
,	(52, 'geenmobieltje', 'Mobieltjes zijn verboden', '?')
,	(53, 'rolstoel', 'Geschikt voor minder validen (rolstoel)', '?')
,	(54, 'vraagt', 'Vraagteken Mysterie elementen', '?')
,	(55, 'buitenland', 'Buitenland - ID kaart of paspoort verplicht', '?')
;

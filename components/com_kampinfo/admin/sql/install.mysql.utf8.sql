DROP TABLE IF EXISTS `#__kampinfo_hitproject`;
DROP TABLE IF EXISTS `#__kampinfo_hitsite`;
DROP TABLE IF EXISTS `#__kampinfo_hitcamp`;
DROP TABLE IF EXISTS `#__kampinfo_hiticon`;
DROP TABLE IF EXISTS `#__kampinfo_downloads`;

CREATE TABLE `#__kampinfo_hitproject` (
    `id`                                    INT(11)         NOT NULL    AUTO_INCREMENT
,   `jaar`                                  YEAR(4)         NOT NULL
,   `shantiEvenementId`                     INT(5)
,   `loterijStartdatum`                     DATETIME
,   `loterijEinddatum`                      DATETIME
,   `inschrijvingStartdatum`                DATETIME
,   `inschrijvingEinddatum`                 DATETIME
,   `inschrijvingWijzigenTotDatum`          DATETIME
,   `inschrijvingKosteloosAnnulerenDatum`   DATETIME
,   `inschrijvingGeenRestitutieDatum`       DATETIME
,   `inningsdatum`                          DATETIME
,   `vrijdag`                               DATETIME
,   `maandag`                               DATETIME
,   `thema`                                 VARCHAR(255)
,   `ouderkind`                             TEXT

,    PRIMARY KEY (`id`)
);

CREATE TABLE `#__kampinfo_hitsite` (
    `id`                                    INT(11)         NOT NULL    AUTO_INCREMENT
,   `asset_id`                              INT(10)         NOT NULL    DEFAULT '0'
,   `hitproject_id`                         INT(11)         NOT NULL
,   `naam`                                  VARCHAR(50)     NOT NULL
,   `projectcode`                           VARCHAR(50)
,   `hitCourantTekst`                       TEXT
,   `contactPersoonNaam`                    VARCHAR(50)
,   `contactPersoonEmail`                   VARCHAR(50)
,   `contactPersoonTelefoon`                VARCHAR(50)
,   `socialmediaFacebook`                   VARCHAR(100)
,   `socialmediaInstagram`                  VARCHAR(100)
,   `akkoordHitPlaats`                      BOOLEAN
,   `published`                             SMALLINT(3)     NOT NULL    DEFAULT '0'
,    PRIMARY KEY (`id`)
);

alter table `#__kampinfo_hitsite`
    add constraint `#__kampinfo_hitsite_project_fk` foreign key (hitproject_id) references `#__kampinfo_hitproject` (id);


CREATE TABLE `#__kampinfo_hitcamp` (
    `id`                                    INT(11)         NOT NULL    AUTO_INCREMENT
,   `asset_id`                              INT(10)         NOT NULL    DEFAULT '0'
,   `hitsite_id`                            INT(11)         NOT NULL
,   `shantiFormuliernummer`                 INT(10)
,   `ouderShantiFormuliernummer`            INT(10)
,   `extraShantiFormuliernummer`            INT(10)
,   `naam`                                  VARCHAR(255)    NOT NULL
,   `geannuleerd`                           BOOLEAN                     DEFAULT 0
,   `isouderkind`                           BOOLEAN
,   `optieAlleenOuderLid`                   BOOLEAN         NULL        DEFAULT 0
,   `startElders`                           BOOLEAN
,   `sublocatie`                            VARCHAR(255)
,   `activiteitengebieden`                  TEXT
,   `titeltekst`                            VARCHAR(255)
,   `startDatumTijd`                        DATETIME
,   `eindDatumTijd`                         DATETIME
,   `deelnamekosten`                        SMALLINT(3)
,   `minimumLeeftijd`                       TINYINT
,   `maximumLeeftijd`                       TINYINT
,   `minimumLeeftijdOuder`                  TINYINT                        DEFAULT 21
,   `maximumLeeftijdOuder`                  TINYINT                        DEFAULT 88
,   `subgroepsamenstellingMinimum`          SMALLINT(2)
,   `subgroepsamenstellingMaximum`          SMALLINT(2)
,   `subgroepsamenstellingExtra`            SMALLINT(1)
,   `icoontjes`                             TEXT
,   `websiteAdres`                          VARCHAR(255)
,   `websiteTekst`                          TEXT
,   `webadresFoto1`                         VARCHAR(255)
,   `webadresFoto2`                         VARCHAR(255)
,   `webadresFoto3`                         VARCHAR(255)
,   `youtube`                               VARCHAR(11)
,   `websiteContactTelefoonnummer`          VARCHAR(255)
,   `websiteContactEmailadres`              VARCHAR(255)
,   `websiteContactpersoon`                 VARCHAR(255)
,   `minimumAantalDeelnemers`               SMALLINT(3)
,   `aantalDeelnemers`                      SMALLINT(3)
,   `gereserveerd`                          SMALLINT(3)
,   `maximumAantalDeelnemers`               SMALLINT(3)
,   `maximumAantalDeelnemersOrigineel`      SMALLINT(3)
,   `minimumAantalSubgroepjes`              SMALLINT(3)
,   `aantalSubgroepen`                      SMALLINT(2)
,   `maximumAantalSubgroepjes`              INT(6)
,   `maximumAantalUitEenGroep`              SMALLINT(3)
,   `margeAantalDagenTeJong`                SMALLINT(3)
,   `margeAantalDagenTeOud`                 SMALLINT(3)
,   `redenAfwijkingMarge`                   VARCHAR(255)
,   `doelstelling`                          TEXT
,   `hitCourantTekst`                       TEXT
,   `helpdeskOpmerkingen`                   TEXT
,   `helpdeskOverschrijdingAantal`          SMALLINT(3)
,   `helpdeskOverschrijdingLeeftijd`        BOOLEAN
,   `helpdeskTeJongMagAantal`               SMALLINT(3)
,   `helpdeskTeOudMagAantal`                SMALLINT(3)
,   `helpdeskContactEmailadres`             VARCHAR(50)
,   `helpdeskContactTelefoonnummer`         VARCHAR(50)
,   `helpdeskContactpersoon`                VARCHAR(50)

,   `akkoordHitKamp`                        BOOLEAN
,   `akkoordHitPlaats`                      BOOLEAN
,   `published`                             SMALLINT(3)     NOT NULL    DEFAULT '0'
,   `publish_up`                            DATETIME        NOT NULL    DEFAULT '0000-00-00 00:00:00'
,   `publish_down`                          DATETIME        NOT NULL    DEFAULT '0000-00-00 00:00:00'
,   PRIMARY KEY (`id`)
);


alter table `#__kampinfo_hitcamp`
    add constraint `#__kampinfo_hitcamp_site_fk` foreign key (hitsite_id) references `#__kampinfo_hitsite` (id);

CREATE TABLE `#__kampinfo_hiticon` (
    `id`                                    INT(11)         NOT NULL    AUTO_INCREMENT
,   `volgorde`                              INT(10)         NOT NULL
,   `bestandsnaam`                          VARCHAR(20)     NOT NULL
,   `tekst`                                 VARCHAR(255)    NOT NULL
,   `uitleg`                                TEXT
,   `soort`                                 CHAR(1)
,   PRIMARY KEY (`id`)
);

INSERT INTO `#__kampinfo_hiticon` (
-- INSERT INTO `joom_kampinfo_hiticon` (
    `volgorde`
,    `bestandsnaam`
,    `tekst`
,    `soort`
) VALUES
     (10, 'aantalnacht1', 'Je overnacht 1 keer', 'S')
,    (10, 'aantalnacht2', 'Je overnacht 2 keer', 'S')
,    (10, 'aantalnacht3', 'Je overnacht 3 keer', 'S')
,    (10, 'aantalnacht4', 'Je overnacht 4 keer', 'S')
,    (20, 'ouderkind', 'Dit is een ouder-kind kamp', 'S')
,    (0, 'vol', 'Het kamp is vol!', 'S')
,    (0, 'loterij', 'Het kamp is vol en er moet geloot worden!', 'S')

,    (101, 'staand', 'Staand kamp', 'B')
,    (102, 'fiets', 'Trekken per fiets', 'B')
,    (103, 'hike', 'Trekken met rugzak', 'B')
,    (104, 'kano', 'Trekken per kano', 'B')
,    (105, 'zeilboot', 'Trekkend per boot', 'B')
,    (106, 'geenrugz', 'Lopen zonder rugzak', 'B')
,    (107, 'hikevr', 'Lopen met een ander voorwerp', 'B')
,    (108, 'auto', 'Trekkend per auto', 'B')
,    (110, 'motor', 'Trekken per motor', 'B')
,    (111, 'trein', 'Trekken per trein', 'B')
,    (111, 'lucht', 'Je gaat vliegen', 'B')

,    (209, '0pers', 'Inschrijven per persoon', 'I')
,    (210, 'groepje', 'Inschrijven per groep', 'I')
,    (211, 'meiden', 'Inschrijven voor alleen meiden', 'I')

,    (311, 'tent', 'Overnachten in een zelfmeegenomen tent', 'O')
,    (312, 'friet', 'Overnachten in een frietbuil', 'O')
,    (313, 'nacht', 'Overnachten zonder tent', 'O')
,    (314, 'tent_opgezet', 'Overnachten in tenten verzorgd door staf', 'O')
,    (315, 'gebouw', 'Overnachten in gebouw', 'O')
,    (316, 'bootslaap', 'Overnachten op een boot', 'O')
,    (317, 'vlotslaap', 'Overnachten op een vlot of drijvend voorwerp ', 'O')

,    (417, '0km', 'Totale afstand is 0 km', 'A')
,    (418, '5km', 'Totale afstand is 5 km', 'A')
,    (419, '10km', 'Totale afstand is 10 km', 'A')
,    (419, '15km', 'Totale afstand is 15 km', 'A')
,    (420, '20km', 'Totale afstand is 20 km', 'A')
,    (421, '25km', 'Totale afstand is 25 km', 'A')
,    (422, '30km', 'Totale afstand is 30 km', 'A')
,    (423, '35km', 'Totale afstand is 35 km', 'A')
,    (424, '40km', 'Totale afstand is 40 km', 'A')
,    (425, '45km', 'Totale afstand is 45 km', 'A')
,    (426, '50km', 'Totale afstand is 50 km', 'A')
,    (427, '55km', 'Totale afstand is 55 km', 'A')
,    (428, '60km', 'Totale afstand is 60 km', 'A')
,    (429, '65km', 'Totale afstand is 65 km', 'A')
,    (430, '70km', 'Totale afstand is 70 km', 'A')
,    (431, '75km', 'Totale afstand is 75 km', 'A')
,    (432, '80km', 'Totale afstand is 80 km', 'A')
,    (433, '85km', 'Totale afstand is 85 km', 'A')
,    (434, '90km', 'Totale afstand is 90 km', 'A')
,    (435, '100km', 'Totale afstand is 100 km', 'A')
,    (436, '120km', 'Totale afstand is 120 km', 'A')
,    (437, '150km', 'Totale afstand is 150 km', 'A')
,    (440, 'kano10', 'Afstand per kano is 10 km', 'A')
,    (450, 'fiets45', 'Afstand per fiets is 45 km', 'A')
,    (451, 'fiets60', 'Afstand per fiets is 60 km', 'A')
,    (460, 'kano25', 'Afstand per kano is 25 km', 'A')
,    (461, 'kano50', 'Afstand per kano is 50 km', 'A')

,    (540, 'vuur', 'Koken op houtvuur zonder pannen', 'K')
,    (541, 'opvuur', 'Koken op houtvuur met pannen', 'K')
,    (542, 'gas', 'Koken op gas met pannen', 'K')
,    (543, 'stafkookt', 'Gekookt door de staf', 'K')

,    (640, 'pios', 'Kennis van pionieren en knopen op eenvoudig niveau ', '?')
,    (641, 'piov', 'Kennis van pionieren en knopen op gevorderd niveau ', '?')
,    (642, 'piogv', 'Kennis van pionieren en knopen op specialistisch niveau ', '?')

,    (645, 'k_ks', 'Kennis van kaart en kompas op eenvoudig niveau', '?')
,    (646, 'k_kv', 'Kennis van kaart en kompas op gevorderd niveau', '?')
,    (647, 'k_kgv', 'Kennis van kaart en kompas op specialistisch niveau', '?')
,    (649, 'insigne', 'Activiteit waarmee een insigne kan worden behaald', '?')

,    (650, 'zwem', 'Zwemdiploma verplicht', '?')
,    (651, 'mobieltje', 'Mobieltje meenemen', '?')
,    (652, 'geenmobieltje', 'Mobieltjes zijn verboden', '?')
,    (653, 'rolstoel', 'Geschikt voor minder validen (rolstoel)', '?')
,    (654, 'vraagt', 'Vraagteken Mysterie elementen', '?')
,    (655, 'buitenland', 'Buitenland - ID kaart of paspoort verplicht', '?')
,    (656, 'idee', 'Hier doe je nieuwe ideeën op en verbeter je je Scoutingtechniek ', '?')


,    (700, 'geenalcohol', 'Er mag geen alcohol worden gedronken', '?')
,    (701, 'nietroken', 'Rookvrije HIT ', '?')
;

CREATE TABLE `#__kampinfo_downloads` (
    `id`                                    INT(11)     NOT NULL    AUTO_INCREMENT
,   `jaar`                                  YEAR(4)     NOT NULL
,   `soort`                                 VARCHAR(4)  NOT NULL
,   `bijgewerktOp`                          TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP
,   `melding`                               TEXT
,	PRIMARY KEY (`id`)
);

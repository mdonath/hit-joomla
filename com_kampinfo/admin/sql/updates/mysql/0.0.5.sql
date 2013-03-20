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
,	PRIMARY KEY (`id`)
);

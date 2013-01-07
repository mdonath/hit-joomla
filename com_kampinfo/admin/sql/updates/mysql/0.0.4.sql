CREATE TABLE `#__kampinfo_downloads` (
	`id`									INT(11)		NOT NULL	AUTO_INCREMENT
,	`jaar`									YEAR(4)		NOT NULL
,	`soort`									VARCHAR(4)	NOT NULL
,	`bijgewerktOp`							TIMESTAMP	NOT NULL	DEFAULT CURRENT_TIMESTAMP
,	`melding`								TEXT
,	PRIMARY KEY (`id`)
);

CREATE INDEX idx_jaar on `#__kampinfo_deelnemers` (jaar);

UPDATE `#__kampinfo_hitcamp`
SET
	startdatumtijd = convert_tz(startdatumtijd, 'Europe/Amsterdam', 'GMT')
,	einddatumtijd = convert_tz(einddatumtijd, 'Europe/Amsterdam', 'GMT')
;

-- aantal inschrijvingen per jaar, verdeling man/vrouw
select
	jaar
,	count(*) as aantal
,	sum(if(geslacht='M',1,0))/count(*)*100 as "man (%)"
,	sum(if(geslacht='V',1,0))/count(*)*100 as "vrouw (%)"
from kuw4c_kampinfo_deelnemers
group by jaar;


-- aantal inschrijvingen per inschrijfdag per jaar
select
  1 + datediff(datumInschrijving, ed.eerste_dag) as Inschrijfdag
, sum(d.jaar=2006) as "2006"
, sum(d.jaar=2007) as "2007"
, sum(d.jaar=2008) as "2008"
, sum(d.jaar=2009) as "2009"
, sum(d.jaar=2010) as "2010"
, sum(d.jaar=2011) as "2011"
, sum(d.jaar=2012) as "2012"
, sum(d.jaar=2013) as "2013"
, sum(d.jaar=2014) as "2014"
, sum(d.jaar=2015) as "2015"
from
  kuw4c_kampinfo_deelnemers d
join
  (select
     jaar
   , min(datumInschrijving) as eerste_dag
   from kuw4c_kampinfo_deelnemers
   group by jaar
  ) ed on (ed.jaar = d.jaar)
group by inschrijfdag
;


select
	1 + datediff(datumInschrijving, (select min(datumInschrijving) from kuw4c_kampinfo_deelnemers d2 where d2.jaar=d.jaar)) as Inschrijfdag
,	sum(if(d.jaar=2008,1,0)) as "2008"
,	sum(if(d.jaar=2009,1,0)) as "2009"
,	sum(if(d.jaar=2010,1,0)) as "2010"
,	sum(if(d.jaar=2011,1,0)) as "2011"
,	sum(if(d.jaar=2012,1,0)) as "2012"
,	sum(if(d.jaar=2013,1,0)) as "2013"
from kuw4c_kampinfo_deelnemers d
group by inschrijfdag

INTO OUTFILE '/tmp/inschrijvingen-per-jaar.csv'
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
;


select
	leeftijd
,	sum(if(d.jaar=2008,1,0)) as "2008"
,	sum(if(d.jaar=2009,1,0)) as "2009"
,	sum(if(d.jaar=2010,1,0)) as "2010"
,	sum(if(d.jaar=2011,1,0)) as "2011"
,	sum(if(d.jaar=2012,1,0)) as "2012"
,	sum(if(d.jaar=2013,1,0)) as "2013"
from kuw4c_kampinfo_deelnemers d
group by leeftijd
;


-- aantal inschrijvingen per jaar, per week, per leeftijdsgroep
select
	jaar
,	concat(jaar, ' wk', lpad(week(datumInschrijving), 2, '0'))  as week
,	sum(if(leeftijd<8,1,0)) as "6-7"
,	sum(if(leeftijd>=8 and leeftijd<=10,1,0)) as "8-10"
,	sum(if(leeftijd>10 and leeftijd<=15,1,0)) as "11-15"
,	sum(if(leeftijd>15 and leeftijd < 18, 1, 0)) as "16-17"
,	sum(if(leeftijd>=18 and leeftijd <= 23, 1, 0)) as "18-23"
,	sum(if(leeftijd > 23,1,0)) as "24+"
,	count(*) as aantal
from kuw4c_kampinfo_deelnemers
group by jaar, week(datumInschrijving)
order by jaar, concat(year(datumInschrijving),'-',lpad(week(datumInschrijving), 2, '0'))

INTO OUTFILE '/tmp/inschrijvingen-per-leeftijd.csv'
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
;

select
	jaar
,	concat(jaar, ' wk', lpad(week(datumInschrijving), 2, '0'))  as week
,	sum(if(leeftijd=6 ,1,0)) as "6"
,	sum(if(leeftijd=7 ,1,0)) as "7"
,	sum(if(leeftijd=8 ,1,0)) as "8"
,	sum(if(leeftijd=9 ,1,0)) as "9"
,	sum(if(leeftijd=10,1,0)) as "10"
,	sum(if(leeftijd=11,1,0)) as "11"
,	sum(if(leeftijd=12,1,0)) as "12"
,	sum(if(leeftijd=13,1,0)) as "13"
,	sum(if(leeftijd=14,1,0)) as "14"
,	sum(if(leeftijd=15,1,0)) as "15"
,	sum(if(leeftijd=16,1,0)) as "16"
,	sum(if(leeftijd=17,1,0)) as "17"
,	sum(if(leeftijd=18,1,0)) as "18"
,	sum(if(leeftijd=19,1,0)) as "19"
,	sum(if(leeftijd=20,1,0)) as "20"
,	sum(if(leeftijd=21,1,0)) as "21"
,	sum(if(leeftijd=22,1,0)) as "22"
,	sum(if(leeftijd=23,1,0)) as "23"
,	sum(if(leeftijd=24,1,0)) as "24"
,	sum(if(leeftijd > 24,1,0)) as "25+"
,	count(*) as aantal
from kuw4c_kampinfo_deelnemers
group by jaar, week(datumInschrijving)
order by jaar, concat(year(datumInschrijving),'-',lpad(week(datumInschrijving), 2, '0'))

INTO OUTFILE '/tmp/inschrijvingen-per-leeftijdsjaar.csv'
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
;

-- Hoeveel kampen zijn bereikbaar voor bepaalde leeftijd
select
	jaar
,	sum(if(minimumLeeftijd<=6 and maximumLeeftijd >=6 ,1,0)) as "6"
,	sum(if(minimumLeeftijd<=7 and maximumLeeftijd >=7 ,1,0)) as "7"
,	sum(if(minimumLeeftijd<=8 and maximumLeeftijd >=8 ,1,0)) as "8"
,	sum(if(minimumLeeftijd<=9 and maximumLeeftijd >=9 ,1,0)) as "9"
,	sum(if(minimumLeeftijd<=10 and maximumLeeftijd >=10 ,1,0)) as "10"
,	sum(if(minimumLeeftijd<=11 and maximumLeeftijd >=11 ,1,0)) as "11"
,	sum(if(minimumLeeftijd<=12 and maximumLeeftijd >=12 ,1,0)) as "12"
,	sum(if(minimumLeeftijd<=13 and maximumLeeftijd >=13 ,1,0)) as "13"
,	sum(if(minimumLeeftijd<=14 and maximumLeeftijd >=14 ,1,0)) as "14"
,	sum(if(minimumLeeftijd<=15 and maximumLeeftijd >=15 ,1,0)) as "15"
,	sum(if(minimumLeeftijd<=16 and maximumLeeftijd >=16 ,1,0)) as "16"
,	sum(if(minimumLeeftijd<=17 and maximumLeeftijd >=17 ,1,0)) as "17"
,	sum(if(minimumLeeftijd<=18 and maximumLeeftijd >=18 ,1,0)) as "18"
,	sum(if(minimumLeeftijd<=19 and maximumLeeftijd >=19 ,1,0)) as "19"
,	sum(if(minimumLeeftijd<=20 and maximumLeeftijd >=20 ,1,0)) as "20"
,	sum(if(minimumLeeftijd<=21 and maximumLeeftijd >=21 ,1,0)) as "21"
,	sum(if(minimumLeeftijd<=22 and maximumLeeftijd >=22 ,1,0)) as "22"
,	sum(if(minimumLeeftijd<=23 and maximumLeeftijd >=23 ,1,0)) as "23"
,	sum(if(minimumLeeftijd<=24 and maximumLeeftijd >=24 ,1,0)) as "24"
,	sum(if(minimumLeeftijd<25,1,0) and maximumLeeftijd > 25) as "25+"
from kuw4c_kampinfo_hitcamp c
left join kuw4c_kampinfo_hitsite s on (s.code = c.hitsite)
group by jaar
order by jaar;

INTO OUTFILE '/tmp/aantal-kampen-per-leeftijdsjaar.csv'
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
;

-- Hoeveel deelnameplekken is er voor elk leeftijdsjaar beschikbaar?
select
	jaar
,	sum(if(minimumLeeftijd<=6 and maximumLeeftijd >=6 ,maximumAantalDeelnemers,0)) as "6"
,	sum(if(minimumLeeftijd<=7 and maximumLeeftijd >=7 ,maximumAantalDeelnemers,0)) as "7"
,	sum(if(minimumLeeftijd<=8 and maximumLeeftijd >=8 ,maximumAantalDeelnemers,0)) as "8"
,	sum(if(minimumLeeftijd<=9 and maximumLeeftijd >=9 ,maximumAantalDeelnemers,0)) as "9"
,	sum(if(minimumLeeftijd<=10 and maximumLeeftijd >=10 ,maximumAantalDeelnemers,0)) as "10"
,	sum(if(minimumLeeftijd<=11 and maximumLeeftijd >=11 ,maximumAantalDeelnemers,0)) as "11"
,	sum(if(minimumLeeftijd<=12 and maximumLeeftijd >=12 ,maximumAantalDeelnemers,0)) as "12"
,	sum(if(minimumLeeftijd<=13 and maximumLeeftijd >=13 ,maximumAantalDeelnemers,0)) as "13"
,	sum(if(minimumLeeftijd<=14 and maximumLeeftijd >=14 ,maximumAantalDeelnemers,0)) as "14"
,	sum(if(minimumLeeftijd<=15 and maximumLeeftijd >=15 ,maximumAantalDeelnemers,0)) as "15"
,	sum(if(minimumLeeftijd<=16 and maximumLeeftijd >=16 ,maximumAantalDeelnemers,0)) as "16"
,	sum(if(minimumLeeftijd<=17 and maximumLeeftijd >=17 ,maximumAantalDeelnemers,0)) as "17"
,	sum(if(minimumLeeftijd<=18 and maximumLeeftijd >=18 ,maximumAantalDeelnemers,0)) as "18"
,	sum(if(minimumLeeftijd<=19 and maximumLeeftijd >=19 ,maximumAantalDeelnemers,0)) as "19"
,	sum(if(minimumLeeftijd<=20 and maximumLeeftijd >=20 ,maximumAantalDeelnemers,0)) as "20"
,	sum(if(minimumLeeftijd<=21 and maximumLeeftijd >=21 ,maximumAantalDeelnemers,0)) as "21"
,	sum(if(minimumLeeftijd<=22 and maximumLeeftijd >=22 ,maximumAantalDeelnemers,0)) as "22"
,	sum(if(minimumLeeftijd<=23 and maximumLeeftijd >=23 ,maximumAantalDeelnemers,0)) as "23"
,	sum(if(minimumLeeftijd<=24 and maximumLeeftijd >=24 ,maximumAantalDeelnemers,0)) as "24"
,	sum(if(minimumLeeftijd<=25 and maximumLeeftijd >=25,maximumAantalDeelnemers,0)) as "25"
,	sum(if(minimumLeeftijd<=26 and maximumLeeftijd >=26,maximumAantalDeelnemers,0)) as "26"
,	sum(if(minimumLeeftijd<=27 and maximumLeeftijd >=27,maximumAantalDeelnemers,0)) as "27"
,	sum(if(minimumLeeftijd<=28 and maximumLeeftijd >=28,maximumAantalDeelnemers,0)) as "28"
,	sum(if(minimumLeeftijd<=29 and maximumLeeftijd >=29,maximumAantalDeelnemers,0)) as "29"
,	sum(if(minimumLeeftijd<=30 and maximumLeeftijd >=30,maximumAantalDeelnemers,0)) as "30"
,	sum(if(minimumLeeftijd<=31 and maximumLeeftijd >=31,maximumAantalDeelnemers,0)) as "31"
,	sum(if(minimumLeeftijd<=32 and maximumLeeftijd >=32,maximumAantalDeelnemers,0)) as "32"
,	sum(if(minimumLeeftijd<=33 and maximumLeeftijd >=33,maximumAantalDeelnemers,0)) as "33"
from kuw4c_kampinfo_hitcamp c
left join kuw4c_kampinfo_hitsite s on (s.code = c.hitsite)
group by jaar
order by jaar;




-- Hoeveel deelnameplekken is er voor elk leeftijdsjaar beschikbaar? KLOPT NIETS VAN!!!
select
	jaar
,	sum(if(minimumLeeftijd<=  6 and maximumLeeftijd >= 10 ,maximumAantalDeelnemers,0)) as "6-10"
,	sum(if(minimumLeeftijd<= 11 and maximumLeeftijd >= 15 ,maximumAantalDeelnemers,0)) as "11-15"
,	sum(if(minimumLeeftijd<= 16 and maximumLeeftijd >= 18 ,maximumAantalDeelnemers,0)) as "16-18"
,	sum(if(minimumLeeftijd<= 19 and maximumLeeftijd >= 23 ,maximumAantalDeelnemers,0)) as "19-23"
,	sum(if(minimumLeeftijd<= 24 and maximumLeeftijd >= 88, maximumAantalDeelnemers,0)) as "24+"
from kuw4c_kampinfo_hitcamp c
left join kuw4c_kampinfo_hitsite s on (s.code = c.hitsite)
group by jaar
order by jaar;





-- inschrijvingen per jaar per plaats per dag
select
	d.jaar as Jaar
,	1 + datediff(datumInschrijving, (select min(datumInschrijving) from kuw4c_kampinfo_deelnemers d2 where d2.jaar=d.jaar)) as Inschrijfdag
,	sum(if(s.naam='Alphen',1,0)) as Alphen
,	sum(if(s.naam='Hilversum' or s.naam='Baarn',1,0)) as "H'sum/Baarn"
,	sum(if(s.naam='Dwingeloo',1,0)) as Dwingeloo
,	sum(if(s.naam='Harderwijk',1,0)) as Harderwijk
,	sum(if(s.naam='Mook',1,0)) as Mook
,	sum(if(s.naam='Zeeland',1,0)) as Zeeland
from kuw4c_kampinfo_deelnemers d
left join kuw4c_kampinfo_hitsite s on d.hitsite = s.code
group by jaar, datumInschrijving

INTO OUTFILE '/tmp/inschrijvingen-per-dag-per-plaats.csv'
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
;

-- deelnemers demografie
select leeftijd
, count(1) as totaal
, sum(jaar=2008) as "2008"
, sum(jaar=2009) as "2009"
, sum(jaar=2010) as "2010"
, sum(jaar=2011) as "2011"
, sum(jaar=2012) as "2012"
, sum(jaar=2013) as "2013"
from kuw4c_kampinfo_deelnemers
group by leeftijd

INTO OUTFILE '/tmp/deelnemers-demografie.csv'
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'

;

-- alleen 2013, voor updates
select leeftijd, sum(jaar=2013) as "2013" from kuw4c_kampinfo_deelnemers group by leeftijd
INTO OUTFILE '/tmp/deelnemers-demografie-2013.csv'
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
;


-- per jaar: aantal, man, vrouw, perc_man, perc_vrw, gem_lft, gem_man, gem_vrw
select
  jaar
, count(*) as aantal
, sum(geslacht='M') as man
, sum(geslacht='V') as vrouw
, round(100 * sum(geslacht='M') /  count(*), 1) as perc_man
, round(100 * sum(geslacht='V') / count(*), 1) as perc_vrw
, round(avg(leeftijd), 1) as gem_lft
, round((select avg(leeftijd) from kuw4c_kampinfo_deelnemers d2 where d2.jaar = d1.jaar and geslacht='M'), 1) as gem_lft_man
, round((select avg(leeftijd) from kuw4c_kampinfo_deelnemers d2 where d2.jaar = d1.jaar and geslacht='V'), 1) as gem_lft_vrw
from kuw4c_kampinfo_deelnemers d1 
group by jaar
;


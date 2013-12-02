-- Corrigeer kampprijzen van 2014 door met 2 euro te verhogen

update `#__kampinfo_hitcamp`		c
	join `#__kampinfo_hitsite`		s on (c.hitsite_id = s.id)
	join `#__kampinfo_hitproject`	p on (s.hitproject_id = p.id)
set		c.deelnamekosten = c.deelnamekosten + 2
where	p.jaar = 2014;

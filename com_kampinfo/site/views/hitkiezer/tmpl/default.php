<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

$kampen = $this->project;
?>
<h1>HIT Kiezer in <?php echo $this->jaar; ?></h1>
<p>Met de HIT-kiezer kun je kijken welke HIT er bij jou past, op basis van je leeftijd
	   tijdens de HIT, je budget, en de dingen die je absoluut wel of absoluut niet leuk vindt.
	   Kampen die al vol zijn, zie je niet meer in de lijst.
	   <span>Je gebruikt hem zo:</span>
</p>


	<form id="filter" name="filter">
		<p><strong>Stap 1:</strong> Vul eerst je geboortedatum in. Je ziet nu alle HITs voor jouw leeftijd.</p>
		<p>
			<select name="geboortedag" id="geboortedag" onchange="updateGeboorteDatumEvent();" class="cookiestore">
				<option value=""></option>
			<option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select>
				<select name="geboortemaand" id="geboortemaand" onchange="updateGeboorteDatumEvent();" class="cookiestore">
				<option value=""></option>
			<option value="1">januari</option><option value="2">februari</option><option value="3">maart</option><option value="4">april</option><option value="5">mei</option><option value="6">juni</option><option value="7">juli</option><option value="8">augustus</option><option value="9">september</option><option value="10">oktober</option><option value="11">november</option><option value="12">december</option></select>
				<select name="geboortejaar" id="geboortejaar" onchange="updateGeboorteDatumEvent();" class="cookiestore">
				<option value=""></option>
			<option value="2005">2005</option><option value="2004">2004</option><option value="2003">2003</option><option value="2002">2002</option><option value="2001">2001</option><option value="2000">2000</option><option value="1999">1999</option><option value="1998">1998</option><option value="1997">1997</option><option value="1996">1996</option><option value="1995">1995</option><option value="1994">1994</option><option value="1993">1993</option><option value="1992">1992</option><option value="1991">1991</option><option value="1990">1990</option><option value="1989">1989</option><option value="1988">1988</option><option value="1987">1987</option><option value="1986">1986</option><option value="1985">1985</option><option value="1984">1984</option><option value="1983">1983</option><option value="1982">1982</option><option value="1981">1981</option><option value="1980">1980</option><option value="1979">1979</option><option value="1978">1978</option><option value="1977">1977</option><option value="1976">1976</option><option value="1975">1975</option><option value="1974">1974</option><option value="1973">1973</option><option value="1972">1972</option><option value="1971">1971</option><option value="1970">1970</option><option value="1969">1969</option><option value="1968">1968</option><option value="1967">1967</option><option value="1966">1966</option><option value="1965">1965</option><option value="1964">1964</option><option value="1963">1963</option><option value="1962">1962</option><option value="1961">1961</option><option value="1960">1960</option><option value="1959">1959</option><option value="1958">1958</option><option value="1957">1957</option><option value="1956">1956</option><option value="1955">1955</option><option value="1954">1954</option><option value="1953">1953</option><option value="1952">1952</option><option value="1951">1951</option><option value="1950">1950</option><option value="1949">1949</option><option value="1948">1948</option><option value="1947">1947</option><option value="1946">1946</option><option value="1945">1945</option><option value="1944">1944</option><option value="1943">1943</option><option value="1942">1942</option><option value="1941">1941</option><option value="1940">1940</option><option value="1939">1939</option><option value="1938">1938</option><option value="1937">1937</option><option value="1936">1936</option><option value="1935">1935</option><option value="1934">1934</option><option value="1933">1933</option><option value="1932">1932</option><option value="1931">1931</option><option value="1930">1930</option><option value="1929">1929</option><option value="1928">1928</option><option value="1927">1927</option><option value="1926">1926</option><option value="1925">1925</option><option value="1924">1924</option></select>
			<span id="leeftijd">, dan is je leeftijd tijdens de HIT 22 jaar.</span>
		</p>
	
		<p><strong>Stap 2:</strong> Geef het bedrag aan dat je maximaal voor de HIT wilt betalen.</p>
		<label for="budget">Maximaal €&nbsp;</label>
		<select name="budget" id="budget" onchange="updateBudgetEvent();" class="cookiestore">
			<option value="-1"></option>
		<option value="35">35</option><option value="40">40</option><option value="45">45</option><option value="50">50</option><option value="55">55</option><option value="60">60</option><option value="65">65</option><option value="70">70</option></select>
			
		<p><strong>Stap 3:</strong> Klik op de icoontjes die passen bij wat je graag wilt bij een HIT, of juist niet.</p>
		<ul>
		    <li>Geen rand: Dit maakt je niet uit</li>
		    <li>Groene rand: Dit wil je graag (1x klikken)</li>
		    <li>Rode rand: Dit wil je liever niet (2x klikken)</li>
		</ul>
		<!-- Placeholder voor de pictogrammen / icoontjes -->
		<div id="pictos"><img style="border-color: black" title="Trekken met rugzak" alt="Trekken met rugzak" src="https://hit.scouting.nl/images/iconen40pix/hike.gif" onclick="selectIcoonEvent('hike')" id="hike" border="3"><img style="border-color: black" title="Trekken per kano" alt="Trekken per kano" src="https://hit.scouting.nl/images/iconen40pix/kano.gif" onclick="selectIcoonEvent('kano')" id="kano" border="3"><img style="border-color: black" title="Trekkend per boot" alt="Trekkend per boot" src="https://hit.scouting.nl/images/iconen40pix/zeilboot.gif" onclick="selectIcoonEvent('zeilboot')" id="zeilboot" border="3"><img style="border-color: black" title="Lopen zonder rugzak" alt="Lopen zonder rugzak" src="https://hit.scouting.nl/images/iconen40pix/geenrugz.gif" onclick="selectIcoonEvent('geenrugz')" id="geenrugz" border="3"><img style="border-color: black" title="Inschrijven per persoon" alt="Inschrijven per persoon" src="https://hit.scouting.nl/images/iconen40pix/0pers.gif" onclick="selectIcoonEvent('0pers')" id="0pers" border="3"><img style="border-color: black" title="Inschrijven per groep" alt="Inschrijven per groep" src="https://hit.scouting.nl/images/iconen40pix/groepje.gif" onclick="selectIcoonEvent('groepje')" id="groepje" border="3"><img style="border-color: black" title="Overnachten in een zelfmeegenomen tent" alt="Overnachten in een zelfmeegenomen tent" src="https://hit.scouting.nl/images/iconen40pix/tent.gif" onclick="selectIcoonEvent('tent')" id="tent" border="3"><img style="border-color: black" title="Overnachten zonder tent" alt="Overnachten zonder tent" src="https://hit.scouting.nl/images/iconen40pix/nacht.gif" onclick="selectIcoonEvent('nacht')" id="nacht" border="3"><img style="border-color: black" title="Totale afstand is 50 km" alt="Totale afstand is 50 km" src="https://hit.scouting.nl/images/iconen40pix/50km.gif" onclick="selectIcoonEvent('50km')" id="50km" border="3"><img style="border-color: black" title="Totale afstand is 120 km" alt="Totale afstand is 120 km" src="https://hit.scouting.nl/images/iconen40pix/120km.gif" onclick="selectIcoonEvent('120km')" id="120km" border="3"><img style="border-color: black" title="Koken op houtvuur zonder pannen" alt="Koken op houtvuur zonder pannen" src="https://hit.scouting.nl/images/iconen40pix/vuur.gif" onclick="selectIcoonEvent('vuur')" id="vuur" border="3"><img style="border-color: black" title="Koken op houtvuur met pannen" alt="Koken op houtvuur met pannen" src="https://hit.scouting.nl/images/iconen40pix/opvuur.gif" onclick="selectIcoonEvent('opvuur')" id="opvuur" border="3"><img style="border-color: black" title="Kennis van kaart en kompas op gevorderd niveau" alt="Kennis van kaart en kompas op gevorderd niveau" src="https://hit.scouting.nl/images/iconen40pix/k_kv.gif" onclick="selectIcoonEvent('k_kv')" id="k_kv" border="3"><img style="border-color: black" title="Zwemdiploma verplicht" alt="Zwemdiploma verplicht" src="https://hit.scouting.nl/images/iconen40pix/zwem.gif" onclick="selectIcoonEvent('zwem')" id="zwem" border="3"><img style="border-color: black" title="Mobieltjes zijn verboden" alt="Mobieltjes zijn verboden" src="https://hit.scouting.nl/images/iconen40pix/geenmobieltje.gif" onclick="selectIcoonEvent('geenmobieltje')" id="geenmobieltje" border="3"></div>
		
		<br>
		<fieldset>
			<!-- Placeholder voor het aantal vind-resultaten. -->
			<legend>Gevonden kampen: <span id="count"><?php echo(count($kampen)); ?></span></legend>
		    <p>Je ziet nu hier de HITs waarvan de HIT-kiezer denkt dat die het beste bij je passen.
	   		   Succes met je keuze!</p>
	
			<!-- Placeholder voor de lijst met kamponderdelen. -->
			<div id="kampen">
			<ul id="kampList">
			
				<?php foreach($kampen as $kamp) { ?>
				<li><a href="https://hit.scouting.nl/hits-in-<?php echo($kamp->plaats);?>/<?php echo($kamp->naam);?>" title="leeftijd: 18-40, prijs € 40. Nog voldoende plaatsen beschikbaar."><?php echo($kamp->naam);?></a><span class="score" title="score">[100%]</span></li>
				<?php }?>
			</ul>
			</div>
		</fieldset>
	</form>

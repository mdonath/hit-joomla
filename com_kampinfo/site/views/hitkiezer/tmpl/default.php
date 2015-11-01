<?php defined('_JEXEC') or die('Restricted access');?>

<article class="itempage" itemtype="http://schema.org/Article" itemscope="">
	<hgroup>
		<h1>HIT Kiezer</h1>
	</hgroup>

	<div itemprop="articleBody">
	<p>	Met de HIT-kiezer kun je kijken welke HIT er bij jou past, op basis van je leeftijd
		tijdens de HIT, je budget, en de dingen die je absoluut wel of absoluut niet leuk vindt.
		Kampen die al vol zijn, zie je niet meer in de lijst.
		<span class="ifJavascriptAvailable">Je gebruikt hem zo:</span>
	</p>
	<div class="ifNoJavascriptAvailable">De HIT-kiezer heeft Javascript ondersteuning nodig, anders werkt het niet.</div>
	<div class="ifJavascriptAvailable">
		<form id="filter" name="filter">
			<p><strong>Stap 1:</strong> Vul eerst je geboortedatum in. Je ziet nu alle HITs voor jouw leeftijd.</p>
			<p>
				<select name="geboortedag" id="geboortedag" onchange="updateGeboorteDatumEvent();" class="cookiestore kiezerinput">
					<option value=""></option>
				</select>
					<select name="geboortemaand" id="geboortemaand" onchange="updateGeboorteDatumEvent();" class="cookiestore kiezerinput">
					<option value=""></option>
				</select>
					<select name="geboortejaar" id="geboortejaar" onchange="updateGeboorteDatumEvent();" class="cookiestore kiezerinput">
					<option value=""></option>
				</select>
				<span id="leeftijd"></span>
			</p>
		
			<p><strong>Stap 2:</strong> Geef het bedrag aan dat je maximaal voor de HIT wilt betalen.</p>
			<p>Maximaal €&nbsp;
			<select name="budget" id="budget" onchange="updateBudgetEvent();" class="cookiestore kiezerinput">
				<option value="-1"></option>
			</select></p>
				
			<p><strong>Stap 3:</strong> Klik op de icoontjes die passen bij wat je graag wilt bij een HIT, of juist niet.</p>
			<ul>
			    <li>Geen rand: Dit maakt je niet uit</li>
			    <li>Groene rand: Dit wil je graag (1x klikken)</li>
			    <li>Rode rand: Dit wil je liever niet (2x klikken)</li>
			</ul>
	
			<!-- Placeholder voor de pictogrammen / icoontjes -->
			<div id="pictos"></div>
	
			<p><strong>Stap 4:</strong> Kies eventueel een plaats uit:
				<select name="plaats" id="plaats" onchange="updatePlaatsEvent();" class="cookiestore kiezerinput">
					<option value="-1"></option>
				</select>
			</p>
			
			<fieldset>
				<!-- Placeholder voor het aantal vind-resultaten. -->
				<legend>Gevonden kampen: <span id="count"></span></legend>
			    <p>Je ziet nu hier de HITs waarvan de HIT-kiezer denkt dat die het beste bij je passen.
		   		   Succes met je keuze!</p>
		
				<!-- Placeholder voor de lijst met kamponderdelen. -->
				<div id="kampen"></div>
			</fieldset>
		</form>
	</div>
</div>

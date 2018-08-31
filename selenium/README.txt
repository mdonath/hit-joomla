De suite 'suite-kampen' maakt gebruik van een extra Selenium plugin genaamd "SelBlocks 2.0" voor 'forJson' en voor 'if'-then-else constructies.

De plugin is te downloaden via: https://addons.mozilla.org/en-US/firefox/addon/selenium-ide-sel-blocks/versions/

Ik maak gebruik van versie 2.0 (28-10-2013), deze bevat ondersteuning voor het lezen van data uit JSON bestanden.



================================

Handleiding aanmaken formulieren

================================

Voorwaarden:
------------
- Firefox met de mogelijkheid om Selenium IDE te draaien. Sinds eind 2017 is Firefox gewijzigd 
(Quantum) en werkt Selenium IDE alleen nog op een oude versie.
- Selenium IDE 2.9.1 (de laatste op dit moment)
- Extra Selenium plugin "SelBlocks 2.0"


Stappen:
--------

VERWIJDEREN OUDE KAMPEN:
 1. Kopieer activiteit "HIT 201(n)" naar "HIT 201(n+1)"
 2. Noteer het id van het evenement: ____
 3. Open Firefox, Selenium IDE en open de testcase "verwijderen-alle-kampen".
 4. Pas de parameter evt_id aan en vul hier het id van stap 2 in.
 5. Controleer de Base URL: dit moet "https://sol.scouting.nl" zijn.
 6. Stap de eerste keer handmatig door de commands en controleer of inderdaad het eerste kamp wordt
   verwijderd (altijd vanaf de derde regel, de eerste twee zijn formulieren).
 7. Start het script en laat het draaien tot het stukloopt omdat er geen kampen meer zijn.
 8. Alle kampen van vorig jaar zijn nu verwijderd en alleen de twee basisformulieren zijn nog over.

ALGMENE DEEL:
 9. Vul in KampInfo het evenement id (stap 2) in bij "HIT Project 201(n)" in het veld "Shanti Eventid"
10. Controleer gelijk nog even de verschillende datums in dat scherm.
    Controleer ook of bij elke plaats de kostenplaats / projectcode is ingevuld.
11. Pas in SOL van beide formulieren (Basis en Basis-OK-kamp) de tabbladen 'financiën' en 'samenstellen
    aan.
    - Tot en met deze datum kosteloos annuleren: Datum start inschrijving
    - Datum waarna annuleren met volledige kosten geldt: Datum einde inschrijving
12. Maak via KampInfo een Shanti dump (menu Overzichten) en sla deze ergens op je computer op als
    "data.js".

AANMAKEN KAMPONDERDELEN:
13. Open in Selenium IDE de TestCase "toevoegen-alle-kampen" en laat deze de data in 'data.js'
    verwerken. Alle kampen worden nu aangemaakt.
14. 


EINDCONTROLE:
- Controleren of de inschrijfformulieren voor de BeverHIT's op de juiste momenten ingeschakeld zijn.
  - Alphen wil beide BeverHITs tegelijk laten inschrijven.
  - Mook wil de kampen één voor één laten inschrijven.
- 
BIJWERKEN KAMPONDERDELEN.


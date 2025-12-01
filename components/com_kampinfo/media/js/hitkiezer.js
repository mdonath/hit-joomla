// Copyright (c) 2012, HIT Scouting Nederland
"use strict";

$(init);

let hitkiezer;

function init() {
    extend();
    hitkiezer = new HitKiezer(hit);

    $('#filter_geboortedag').change(() => hitkiezer.updateGeboorteDatumEvent());
    $('#filter_geboortemaand').change(() => hitkiezer.updateGeboorteDatumEvent());
    $('#filter_geboortejaar').change(() => hitkiezer.updateGeboorteDatumEvent());
    $('#filter_budget').change(({currentTarget}) => hitkiezer.updateBudgetEvent($(currentTarget).val()));
    $('#filter_plaats').change(({currentTarget}) => hitkiezer.updatePlaatsEvent($(currentTarget).val()));
    $('#filter_ouderkind').change(({currentTarget}) => hitkiezer.updateOuderKindEvent($(currentTarget).val()));
}

/**
 * Filtert/scoort met behulp van pictogrammen.
 * 
 * @param color
 * @returns {IconFilter}
 */
class IconFilter {

    #color;
    #list = [];

    constructor(color) {
        this.#color = color;
        this.load();
    }

    contains(id) {
        return (this.#list.indexOf(id) != -1);
    }

    score(icoon_naam, extra) {
        let score = 0.0;
        this.#list.forEach(item => {
            if (icoon_naam === item) {
                score += extra;
            }
        });
        return score;
    }

    add(id) {
        this.#list.push(id);
        this.save();
    }

    remove(id) {
        if (this.contains(id)) {
            this.#list.splice(this.#list.indexOf(id), 1);
            this.save();
        }
    }

    load() {
        const unsplit = jaaulde.utils.cookies.get(this.#color);
        if (unsplit == null) {
            this.#list = [];
        } else {
            this.#list = unsplit.split('|');
        }
    }

    save() {
        jaaulde.utils.cookies.set(this.#color, this.#list.join('|'));
    }

    clear() {
        this.#list.length = 0;
        this.save();
    }

    isEmpty() {
        return this.#list.length === 0;
    }
}

class Filter {
    // Leeftijd tijdens de HIT
    #peildatum;
    #geboortedatum = null;

    // Deelnamekosten
    #budget = -1;

    // Icoontjes die positief meetellen
    #groen;
    // Icoontjes die negatief meetellen
    #rood;

    // HIT plaats
    #plaats = -1;

    // Selectie ouder-kind kampen
    #ouderkind = -1;

    constructor(hit) {
        this.#groen = new IconFilter('groen');
        this.#rood = new IconFilter('rood');
        this.#peildatum = parseDate(hit.vrijdag);
    }

    get hitjaar() {
        return this.#peildatum.getFullYear();
    }

    loadFilters() {
        this.#groen.load();
        this.#rood.load();
    }

    clearFilters() {
        this.#groen.clear();
        this.#rood.clear();
    }

    isGroen(id) {
        return this.#groen.contains(id);
    }

    isRood(id) {
        return this.#rood.contains(id);
    }

    naarRood(id) {
        this.#groen.remove(id);
        this.#rood.add(id);
    }

    naarGroen(id) {
        this.#groen.add(id);
        this.#rood.remove(id);
    }

    naarZwart(id) {
        this.#groen.remove(id);
        this.#rood.remove(id);
    }

    bepaalScore(kamp) {
        let score = 0.0;
        const filter_leeftijd = this.#filterLeeftijd(kamp);
        const filter_budget = this.#filterBudget(kamp);
        const filter_vol = this.#filterVol(kamp);
        const filter_plaats = this.#filterPlaats(kamp);
        const filter_ouderkind = this.#filterOuderKind(kamp);
        if (filter_leeftijd && filter_budget && filter_vol && filter_plaats && filter_ouderkind) {
            if (this.#budget != -1) {
                const afstandTotMaxBudget = this.#budget - kamp.deelnamekosten;
                const factor = afstandTotMaxBudget / this.#budget;
                const invFactor = 1.0 - factor;
                score += invFactor * 2;
            }
            
            kamp.iconen.forEach(icoon => {
                 // elke groene icoon levert 2 punten op
                score += this.#groen.score(icoon.bestandsnaam, 2.0);
                // elke rode icoon kost 2 punten
                score -= this.#rood.score(icoon.bestandsnaam, 2.0);
                 // maar nooit minder dan 0
                if (score < 0) {
                    score = 0;
                }
            });
        } else {
            score = -1;
        }
        return score;
    }

    #leeftijdOpPeildatum() {
        let result = -1;
        if (this.#geboortedatum != null) {
            result = this.hitjaar - this.#geboortedatum.getFullYear();
            const verjaardagInHitJaar = createDate(
                    this.hitjaar,
                    this.#geboortedatum.getMonth() + 1,
                    this.#geboortedatum.getDate());
            if (verjaardagInHitJaar > this.#peildatum) {
                result--;
            }
        }
        return result;
    }

    #relativeDate(datumTijd, jaarOffset, dagOffset) {
        return createDate(
            datumTijd.getFullYear() - jaarOffset,
            datumTijd.getMonth() + 1,
            datumTijd.getDate() - dagOffset
        );
    }

    #isOuderLeeftijdInRange(kamp) {
        if (this.#ouderkind != null && this.#ouderkind != -1 && kamp.isouderkind === 1) {
            const geborenNaOuder = this.#relativeDate(
                kamp.eindDatumTijd,
                kamp.maximumLeeftijdOuder + 1,
                -1
            );
            const geborenVoorOuder = this.#relativeDate(
                kamp.startDatumTijd,
                kamp.minimumLeeftijdOuder,
                0
            );
            return (this.#geboortedatum >= geborenNaOuder && this.#geboortedatum <= geborenVoorOuder);
        }
        return false;
    }

    #isKindLeeftijdInRange(kamp) {
        const geborenNa = this.#relativeDate(
            kamp.eindDatumTijd,
            kamp.maximumLeeftijd + 1, // +1; want hele jaar telt mee 
            kamp.margeAantalDagenTeOud - 1 // -1; bij marge=0 mag je op einddatum nog niet maxlft+1 zijn
        );
        const geborenVoor = this.#relativeDate(
            kamp.startDatumTijd,
            kamp.minimumLeeftijd,
            kamp.margeAantalDagenTeJong
        );
        return (this.#geboortedatum >= geborenNa && this.#geboortedatum <= geborenVoor);
    }

    #filterLeeftijd(kamp) {
        return this.#geboortedatum == null || this.#isKindLeeftijdInRange(kamp) || this.#isOuderLeeftijdInRange(kamp);
    }

    #filterBudget(kamp) {
        return this.#budget === -1 || ( (kamp.deelnamekosten <= this.#budget + 10) && (kamp.deelnamekosten >= this.#budget - 10) );
    }

    #filterVol(kamp) { // het is ok als...
        return kamp.gereserveerd < kamp.maximumAantalDeelnemers;
    }

    #filterPlaats(kamp) {
        return this.#plaats == null || this.#plaats == -1 || kamp.plaats.toLowerCase() === this.#plaats.toLowerCase();
    }

    #filterOuderKind(kamp) {
        return this.#ouderkind == null || this.#ouderkind === -1 || (this.#ouderkind === kamp.isouderkind);
    }

    updateGeboorteDatum(jaar, maand, dag) {
        if (this.#validateGeboortedatumForm(jaar, maand, dag)) {
            this.#geboortedatum = createDate(jaar, maand, dag);
            const leeftijd = this.#leeftijdOpPeildatum();
            this.clearFilters();
            return leeftijd;
        } else {
            this.#geboortedatum = null;
            this.clearFilters();
            return '';
        }
    }

    #validateGeboortedatumForm(jaar, maand, dag) {
        return !(jaar === '' || maand === '' || dag === '');
    }

    updateBudget(value) {
        this.#budget = parseInt(value);
        this.clearFilters();
    }

    updatePlaats(value) {
        this.#plaats = value;
        if (this.#plaats === -1) {
            this.#plaats = null;
        }
        this.clearFilters();
    }

    updateOuderKind(value) {
        this.#ouderkind = value;

        if (this.#ouderkind != null) {
            this.#ouderkind = parseInt(this.#ouderkind); 
        }
        this.clearFilters();
    }
}

class HitKiezer {

    #filter;
    #hit;

    constructor(hit) {
        this.#hit = hit;
        this.#filter = new Filter(hit);

        this.#initVelden();
        this.#repaint();
    }

    get filter() {
        return this.#filter
    }

    #initVelden() {
        this.#initVeldenGeboorteDatum();
        this.#initVeldenPrijzen();
        this.#initVeldenPlaatsen();
        this.#initVeldenOuderKind();

        $('.cookiestore').cookieBind();

        this.updateGeboorteDatumEvent();
        this.#filter.updateBudget($('#filter_budget').val());
        this.#filter.updatePlaats($('#filter_plaats').val() || $.getUrlVar('filter_plaats'));
        this.#filter.updateOuderKind($('#filter_ouderkind').val());

        this.#filter.loadFilters();
        this.#toonKampenMetFilter();
    }

    #initVeldenGeboorteDatum() {
        // Geboortedag
        for (let i = 1; i < 32; i++) {
            $("<option>")
                .attr("value", i)
                .text(i)
                .appendTo("#filter_geboortedag");
        }

        // Geboortemaand
        [
            'januari',
            'februari',
            'maart',
            'april',
            'mei',
            'juni',
            'juli',
            'augustus',
            'september',
            'oktober',
            'november',
            'december'
        ].forEach((maand, index) => 
            $("<option>")
                .attr("value", index + 1)
                .text(maand)
                .appendTo("#filter_geboortemaand")
        );

        // Geboortejaar; afhankelijk van minimum- en maximumleeftijd.
        const {min, max} = this.#minMaxJaar();
        for (let i = min; i >= max; i--) {
            $("<option>")
                .attr("value", i)
                .text(i)
                .appendTo("#filter_geboortejaar");
        }
    }

    #minMaxJaar() {
        let min = 100;
        let max = 0;

        this.#hit.hitPlaatsen.forEach(plaats =>
            plaats.kampen.forEach(kamp => {
                min = Math.min(min, kamp.minimumLeeftijd);
                max = Math.max(max, kamp.maximumLeeftijd);
            })
        );

        const hitjaar = this.#filter.hitjaar;
        return {
            min: hitjaar - min,
            max: hitjaar - max
        };
    }

    #initVeldenPrijzen() {
        const prijzen = [];
        this.#hit.hitPlaatsen.forEach(plaats =>
            plaats.kampen.forEach(kamp => {
                let found = false;
                prijzen.forEach(prijs =>
                    found = found || (prijs === kamp.deelnamekosten)
                );
                if (!found) {
                    prijzen.push(kamp.deelnamekosten);
                }
                kamp.score = 0;
                kamp.plaats = plaats.naam;
                kamp.startDatumTijd = parseDate(kamp.startDatumTijd);
                kamp.eindDatumTijd = parseDate(kamp.eindDatumTijd);
            })
        );

        // prijzen
        prijzen.sort((a, b) => a - b); // sorteer numeriek
        const lowest = (Math.round(prijzen[0] / 10) * 10) + 10;
        const highest = (Math.round(prijzen[prijzen.length - 1] / 10) * 10) + 10;
        for (let prijs = lowest; prijs < highest; prijs += 10) {
            $("<option>")
                .attr("value", prijs)
                .text((prijs - 10) + " tot " + (prijs + 10))
                .appendTo("#filter_budget");
        };
    }

    #initVeldenPlaatsen() {
        this.#hit.hitPlaatsen.forEach(plaats =>
            $("<option>")
                .attr("value", plaats.naam)
                .text("HIT " + plaats.naam)
                .appendTo("#filter_plaats")
        );
    }

    #initVeldenOuderKind() {
        [
            {value: '1', text: 'Ja'},
            {value: '0', text: 'Nee'},
        ]
        .forEach(({value, text}) =>
            $("<option>")
                .attr("value", value)
                .text(text)
                .appendTo("#filter_ouderkind")
        );
    }

    #repaint() {
        this.#toonKampenMetFilter();
        this.#filterPictogrammen();
    }

    #toonKampenMetFilter() {
        // kieper huidige lijst leeg
        $('#kampen').empty();

        // verzamel kampen met score >= 0
        const kampen = [];
        this.#hit.hitPlaatsen.forEach(plaats =>
            plaats.kampen.forEach(kamp => {
                kamp.score = this.#filter.bepaalScore(kamp);
                if (kamp.score >= 0) {
                    kampen.push(kamp);
                }
            })
        );

        $("#count").text(kampen.length);

        if (kampen.length > 0) {
            $("<ul>").attr("id", "kampList").appendTo("#kampen");

            // sorteren op score
            kampen.sort((a, b) => b.score - a.score);
            
            const volPatt = /vol:/i;
            // overgebleven kampen tonen
            kampen.forEach(kamp => {
                const li = $("<li>");
                const fuzzy = fuzzyIndicatieVol(kamp);
                const fuzzyInNaam = volPatt.test(fuzzy);
                $("<a>")
                    .text(kamp.naam + " in " + kamp.plaats + (fuzzyInNaam ? " ("+fuzzy+")" : ""))
                    .attr({
                        title: "leeftijd: " + kamp.minimumLeeftijd + "-" + kamp.maximumLeeftijd 
                                + ", prijs € " + kamp.deelnamekosten
                                + ". " + fuzzy
                                ,
                        href: "../hits-in-" + kamp.plaats.toLowerCase() + "-" + this.#hit.jaar + "/" + urlified(kamp.naam) })
                    .appendTo(li);
                $("<span>")
                    .text("[" + (Math.round(10 * kamp.score) / 10) + "]")
                    .attr({title: "score", 'class': "score"})
                    .appendTo(li);
                li.appendTo("#kampList");
            });
        } else {
            $("<p>").text("Helaas, geen activiteiten gevonden!").appendTo("#kampen");
        }
    }

    /**
     * Teken de pictogrammen opnieuw en filter op basis van getoonde kampen.
     */
    #filterPictogrammen() {
        // Maak pictogrammen op scherm leeg.
        $("#filter_pictos").empty();

        // Verzamel de gewenste set iconen.
        const gebruikteIconen = []
        hit.hitPlaatsen.forEach(plaats => 
            plaats.kampen.forEach(kamp => {
                if (kamp.score >= 0) {
                    // Kijk voor elk kamp met voldoende score of zijn icoontjes al in de gewenste set zit
                    kamp.iconen.forEach(kampIcoon => {
                        let found = false;
                        gebruikteIconen.forEach(verzameldIcoon => 
                            found = found || (kampIcoon.bestandsnaam === verzameldIcoon.bestandsnaam)
                        );
                        if (!found) {
                            gebruikteIconen.push(kampIcoon);
                        }
                    });
                }
            })
        );

        // Sorteer op basis van de vaste icoon-volgorde.
        gebruikteIconen.sort((a, b) => a.volgorde - b.volgorde);

        // Druk iconen af.
        gebruikteIconen.forEach(icoon => {
            const borderColor = this.#filter.isGroen(icoon.bestandsnaam) ? "green"
                    : this.#filter.isRood(icoon.bestandsnaam) ? "red"
                    : "black";
            $("<img>")
                .on('click', ({currentTarget}) => this.selectIcoonEvent(icoon.bestandsnaam, currentTarget))
                .attr({
                    id: icoon.bestandsnaam,
                    src: kampinfoConfig.iconFolderLarge + '/' + icoon.bestandsnaam + kampinfoConfig.iconExtension,
                    border: 3,
                    alt: icoon.tekst,
                    title: icoon.tekst,
                    style: "border-color: " + borderColor 
                })
                .appendTo("#filter_pictos");
        });
    }

    /**
     * Als de geboortedatum aangepast wordt.
     */
    updateGeboorteDatumEvent() {
        const leeftijd = this.#filter.updateGeboorteDatum(
            $('#filter_geboortejaar').val(),
            $('#filter_geboortemaand').val(),
            $('#filter_geboortedag').val()
        );
        if (leeftijd === '') {
            $("#leeftijd").text('');
        } else {
            $("#leeftijd").text(", dan is je leeftijd tijdens de HIT " + leeftijd + " jaar.");
        }
        this.#repaint();
    }

    /**
     * Als het budget aangepast wordt.
     */
    updateBudgetEvent(value) {
        this.#filter.updateBudget(value);
        this.#repaint();
    }

    /**
     * Als er op een icoontje geklikt wordt.
     */
    selectIcoonEvent(cellId, element) {
        const style = element.style;
        if (style.borderColor === 'green') {
            // groen -- rood
            style.borderColor = 'red';
            this.#filter.naarRood(cellId);
        } else if (style.borderColor === 'red') {
            // rood -- zwart
            style.borderColor = 'black';
            this.#filter.naarZwart(cellId);
        } else {
            // zwart -- groen
            style.borderColor = 'green';
            this.#filter.naarGroen(cellId);
        }
        this.#toonKampenMetFilter();
    }

    /**
     * Als de plaats aangepast wordt.
     */
    updatePlaatsEvent(value) {
        this.#filter.updatePlaats(value);
        this.#repaint();
    }

    /** Als de indicatie Ouder-Kind kamp aangepast wordt. */
    updateOuderKindEvent(value) {
        this.#filter.updateOuderKind(value);
        this.#repaint();
    }

}

import { fuzzyIndicatieVol } from './util.js';
import { parseDate } from "./date_util.js";
import { BudgetFilter } from './filters/BudgetFilter.js';
import { IcoonFilter } from './filters/IcoonFilter.js';
import { LeeftijdFilter } from './filters/LeeftijdFilter.js';   
import { OuderKindFilter } from './filters/OuderKindFilter.js';
import { PlaatsFilter } from './filters/PlaatsFilter.js';
import { VolFilter } from './filters/VolFilter.js';

/**
 * De hitkiezer zelf.
 */
export default class Kiezer {

    #hit;

    #volFilter;
    #leeftijdFilter;
    #budgetFilter;
    #icoonFilter;
    #plaatsFilter;
    #ouderkindFilter;

    constructor(hit) {
        // Bewaar de data over de kamponderdelen
        this.#hit = hit;
        this.preprocessData();
        
        // Maak de filters aan
        this.#volFilter = new VolFilter(this);
        this.#leeftijdFilter = new LeeftijdFilter(this);
        this.#budgetFilter = new BudgetFilter(this);
        this.#icoonFilter = new IcoonFilter(this);
        this.#plaatsFilter = new PlaatsFilter(this);
        this.#ouderkindFilter = new OuderKindFilter(this);

        // Koppel cookie opslag aan de velden
        $('.cookiestore').cookieBind();
        // Trigger een eerste update zodat het filter wordt toegepast met de cookie waarden
        this.updateAll();

        // Initialiseer de weergave
        this.updateEvent();
    }

    preprocessData() {
        this.hit.hitPlaatsen.forEach(plaats =>
            plaats.kampen.forEach(kamp => {
                kamp.score = 0;
                kamp.plaats = plaats.naam;
                kamp.startDatumTijd = parseDate(kamp.startDatumTijd);
                kamp.eindDatumTijd = parseDate(kamp.eindDatumTijd);
            })
        );
    }

    get hit() {
        return this.#hit;
    }

    updateAll() {
        this.#leeftijdFilter.update();
        this.#budgetFilter.update();
        this.#plaatsFilter.update();
        this.#ouderkindFilter.update();
        this.#icoonFilter.loadIconFiltersFromCookie();
    }

    updateEvent(clearIconFilters = true) {
        if (clearIconFilters) {
            this.#icoonFilter.clearIconFilters();
        }
        this.#toonGefilterdeKampen();
        this.#icoonFilter.draw();
    }

    #toonGefilterdeKampen() {
        // kieper huidige lijst leeg
        $('#kampen').empty();

        // verzamel kampen met score >= 0
        const kampen = [];
        this.hit.hitPlaatsen.forEach(plaats =>
            plaats.kampen.forEach(kamp => {
                kamp.score = this.score(kamp);
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
                    .text(this.#kampNaam(kamp, fuzzyInNaam, fuzzy))
                    .attr({
                        title: this.#kampTitle(kamp, fuzzy),
                        href: this.#kampUrl(kamp)
                    })
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

    #kampNaam(kamp, fuzzyInNaam, fuzzy) {
        return kamp.naam + " in " + kamp.plaats + (fuzzyInNaam ? " (" + fuzzy + ")" : "");
    }

    #kampTitle(kamp, fuzzy) {
        return "leeftijd: " + kamp.minimumLeeftijd + "-" + kamp.maximumLeeftijd 
            + ", prijs € " + kamp.deelnamekosten
            + ". " + fuzzy;
    }

    #kampUrl(kamp) {
        return "../hits-in-" + kamp.plaats.toLowerCase() + "-" + this.#hit.jaar + "/" + kamp.alias;
    }

    score(kamp) {
        const filter_leeftijd = this.#leeftijdFilter.filter(kamp, this.#ouderkindFilter.isFilterActief(kamp));
        const filter_budget = this.#budgetFilter.filter(kamp)
        const filter_vol = this.#volFilter.filter(kamp);
        const filter_plaats = this.#plaatsFilter.filter(kamp);
        const filter_ouderkind = this.#ouderkindFilter.filter(kamp);

        if (filter_leeftijd && filter_budget && filter_vol && filter_plaats && filter_ouderkind) {
            let score = 0.0;
            score = this.#budgetFilter.score(kamp, score);
            score = this.#icoonFilter.score(kamp, score);
            return score;
        }

        // Geen score
        return -1;
    }
}

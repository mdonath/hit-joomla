import { parseDate } from "../date_util.js";
import { Filter } from "./Filter.js";

const SELECTOR = '#filter_budget';

/**
 * Filter voor te betalen deelnemerbijdrage.
 */
export class BudgetFilter extends Filter {

    #value = -1;

    constructor(hitkiezer) {
        super(hitkiezer);
        this.#initVelden();
    }

    #initVelden() {
        const prijzen = [];
        this.hit.hitPlaatsen.forEach(plaats =>
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
                .appendTo(SELECTOR);
        };

        $(SELECTOR).change(() => this.update());
    }

    update() {
        const value = $(SELECTOR).val();
        this.#value = parseInt(value);
        this.updateEvent();
    }

    score(kamp, baseScore) {
        let score = baseScore;
        if (this.#value != -1) {
            const afstandTotMaxBudget = this.#value - kamp.deelnamekosten;
            const factor = afstandTotMaxBudget / this.#value;
            const invFactor = 1.0 - factor;
            score = invFactor * 2;
        }
        return score;
    }

    filter(kamp) {
        return this.#value === -1 || ( (kamp.deelnamekosten <= this.#value + 10) && (kamp.deelnamekosten >= this.#value - 10) );
    }

}

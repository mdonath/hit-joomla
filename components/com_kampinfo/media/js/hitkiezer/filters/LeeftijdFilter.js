import { createDate, parseDate } from "../date_util.js";
import { Filter } from "./Filter.js";

export const SELECTOR_DAG = "#filter_geboortedag";
export const SELECTOR_MAAND = "#filter_geboortemaand";
export const SELECTOR_JAAR = "#filter_geboortejaar";
export const SELECTOR_LEEFTIJD = "#leeftijd";

/**
 * Filter voor leeftijd.
 */
export class LeeftijdFilter extends Filter {
    #peildatum;
    #geboortedatum;

    constructor(hitkiezer) {
        super(hitkiezer);
        this.#peildatum = parseDate(this.hit.vrijdag);
        this.#initVelden();
    }

    #initVelden() {
        // Geboortedag
        for (let i = 1; i < 32; i++) {
            $("<option>").attr("value", i).text(i).appendTo(SELECTOR_DAG);
        }
        $(SELECTOR_DAG).change(() => this.update());

        // Geboortemaand
        [
            "januari",
            "februari",
            "maart",
            "april",
            "mei",
            "juni",
            "juli",
            "augustus",
            "september",
            "oktober",
            "november",
            "december",
        ].forEach((maand, index) =>
            $("<option>")
                .attr("value", index + 1)
                .text(maand)
                .appendTo(SELECTOR_MAAND),
        );
        $(SELECTOR_MAAND).change(() => this.update());

        // Geboortejaar; afhankelijk van minimum- en maximumleeftijd.
        const { min, max } = this.#minMaxJaar();
        for (let i = min; i >= max; i--) {
            $("<option>").attr("value", i).text(i).appendTo(SELECTOR_JAAR);
        }
        $(SELECTOR_JAAR).change(() => this.update());
    }

    #minMaxJaar() {
        let min = 100;
        let max = 0;

        this.hit.hitPlaatsen.forEach((plaats) =>
            plaats.kampen.forEach((kamp) => {
                min = Math.min(min, kamp.minimumLeeftijd);
                max = Math.max(max, kamp.maximumLeeftijd);
            }),
        );

        const hitjaar = this.hitjaar;
        return {
            min: hitjaar - min,
            max: hitjaar - max,
        };
    }

    get hitjaar() {
        return this.#peildatum.getFullYear();
    }

    /**
     * Geeft aan of het kamp door het filter komt.
     *
     * @param {*} kamp
     * @param {*} ouderkindFilterActief
     * @returns true als het kamp door het filter komt, anders false.
     */
    filter(kamp, ouderkindFilterActief) {
        return (
            this.#isFilterLeeg() ||
            this.#isKindLeeftijdInRange(kamp) ||
            this.#isOuderLeeftijdInRange(kamp, ouderkindFilterActief)
        );
    }

    #isFilterLeeg() {
        return this.#geboortedatum == null;
    }

    #isKindLeeftijdInRange(kamp) {
        const minimum = this.#relativeDate(
            kamp.eindDatumTijd,
            -(kamp.maximumLeeftijd + 1),
            -kamp.margeAantalDagenTeOud,
        );
        const maximum = this.#relativeDate(
            kamp.startDatumTijd,
            -kamp.minimumLeeftijd,
            +kamp.margeAantalDagenTeJong,
        );

        return this.#inRange(minimum, this.#geboortedatum, maximum);
    }

    #isOuderLeeftijdInRange(kamp, ouderkindFilterActief) {
        if (ouderkindFilterActief) {
            const minimum = this.#relativeDate(
                kamp.eindDatumTijd,
                -(kamp.maximumLeeftijdOuder + 1),
                0,
            );
            const maximum = this.#relativeDate(
                kamp.startDatumTijd,
                -kamp.minimumLeeftijdOuder,
                0,
            );
            return this.#inRange(minimum, this.#geboortedatum, maximum);
        }
        return false;
    }

    #relativeDate(datumTijd, jaarOffset, dagOffset) {
        return createDate(
            datumTijd.getFullYear() + jaarOffset,
            datumTijd.getMonth() + 1,
            datumTijd.getDate() + dagOffset,
        );
    }

    #inRange(min, date, max) {
        return min <= date && date <= max;
    }

    /**
     * Als de geboortedatum aangepast wordt.
     */
    update(init) {
        const jaar = $(SELECTOR_JAAR).val();
        const maand = $(SELECTOR_MAAND).val();
        const dag = $(SELECTOR_DAG).val();

        if (this.#validateGeboortedatumForm(jaar, maand, dag)) {
            this.#geboortedatum = createDate(jaar, maand, dag);
            const leeftijd = this.#leeftijdOpPeildatum();
            $(SELECTOR_LEEFTIJD).text(
                ", dan is je leeftijd tijdens de HIT " + leeftijd + " jaar.",
            );
        } else {
            this.#geboortedatum = null;
            $(SELECTOR_LEEFTIJD).text("");
        }

        this.updateEvent(init);
    }

    #validateGeboortedatumForm(jaar, maand, dag) {
        return !(jaar === "" || maand === "" || dag === "");
    }

    #leeftijdOpPeildatum() {
        let result = this.hitjaar - this.#geboortedatum.getFullYear();
        const verjaardagInHitJaar = createDate(
            this.hitjaar,
            this.#geboortedatum.getMonth() + 1,
            this.#geboortedatum.getDate(),
        );
        if (verjaardagInHitJaar > this.#peildatum) {
            result--;
        }
        return result;
    }
}

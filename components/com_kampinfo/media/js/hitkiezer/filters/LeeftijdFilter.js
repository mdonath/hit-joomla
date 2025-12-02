import { createDate, parseDate } from '../date_util.js';
import { Filter } from './Filter.js';

const SELECTOR_DAG = '#filter_geboortedag';
const SELECTOR_MAAND = '#filter_geboortemaand';
const SELECTOR_JAAR = '#filter_geboortejaar';

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
            $("<option>")
                .attr("value", i)
                .text(i)
                .appendTo(SELECTOR_DAG);
        }
        $(SELECTOR_DAG).change(() => this.update());

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
                .appendTo(SELECTOR_MAAND)
        );
        $(SELECTOR_MAAND).change(() => this.update());

        // Geboortejaar; afhankelijk van minimum- en maximumleeftijd.
        const {min, max} = this.#minMaxJaar();
        for (let i = min; i >= max; i--) {
            $("<option>")
                .attr("value", i)
                .text(i)
                .appendTo(SELECTOR_JAAR);
        }
        $(SELECTOR_JAAR).change(() => this.update());
    }

    #minMaxJaar() {
        let min = 100;
        let max = 0;

        this.hit.hitPlaatsen.forEach(plaats =>
            plaats.kampen.forEach(kamp => {
                min = Math.min(min, kamp.minimumLeeftijd);
                max = Math.max(max, kamp.maximumLeeftijd);
            })
        );

        const hitjaar = this.hitjaar;
        return {
            min: hitjaar - min,
            max: hitjaar - max
        };
    }

    get hitjaar() {
        return this.#peildatum.getFullYear();
    }

    filter(kamp, ouderkindFilterActief) {
        return this.#geboortedatum == null || this.#isKindLeeftijdInRange(kamp) || this.#isOuderLeeftijdInRange(kamp, ouderkindFilterActief);
    }

    #isOuderLeeftijdInRange(kamp, ouderkindFilterActief) {
        if (ouderkindFilterActief) {
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

    #relativeDate(datumTijd, jaarOffset, dagOffset) {
        return createDate(
            datumTijd.getFullYear() - jaarOffset,
            datumTijd.getMonth() + 1,
            datumTijd.getDate() - dagOffset
        );
    }

    /**
     * Als de geboortedatum aangepast wordt.
     */
    update() {
        const jaar = $('#filter_geboortejaar').val();
        const maand = $('#filter_geboortemaand').val();
        const dag = $('#filter_geboortedag').val();

        if (this.#validateGeboortedatumForm(jaar, maand, dag)) {
            this.#geboortedatum = createDate(jaar, maand, dag);
            const leeftijd = this.#leeftijdOpPeildatum();
            $("#leeftijd").text(", dan is je leeftijd tijdens de HIT " + leeftijd + " jaar.");
        } else {
            this.#geboortedatum = null;
            $("#leeftijd").text('');
        }

        this.updateEvent();
    }

    #validateGeboortedatumForm(jaar, maand, dag) {
        return !(jaar === '' || maand === '' || dag === '');
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

}

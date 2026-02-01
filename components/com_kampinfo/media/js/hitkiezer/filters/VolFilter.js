import { Filter } from "./Filter.js";

/**
 * Filter die kampen doorlaat die nog niet vol zijn wat betreft deelnemers en subgroepjes.
 */
export class VolFilter extends Filter {
    constructor(hitkiezer) {
        super(hitkiezer);
    }

    filter(kamp) {
        return (
            this.#isErNogRuimteQuaDeelnemers(kamp) &&
            this.#isErNogRuimteVoorGroepjes(kamp)
        );
    }

    #isErNogRuimteQuaDeelnemers(kamp) {
        return kamp.gereserveerd < kamp.maximumAantalDeelnemers;
    }

    #isErNogRuimteVoorGroepjes(kamp) {
        return (
            kamp.maximumAantalSubgroepjes === 0 ||
            kamp.aantalSubgroepen < kamp.maximumAantalSubgroepjes
        );
    }
}

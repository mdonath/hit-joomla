import { Filter } from './Filter.js';

/**
 * Filter die kampen door laat die nog niet vol zijn.
 */
export class VolFilter extends Filter {

    constructor(hitkiezer) {
        super(hitkiezer);
    }

    filter(kamp) {
        return kamp.gereserveerd < kamp.maximumAantalDeelnemers;
    }

}

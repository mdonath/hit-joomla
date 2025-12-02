import { Filter } from "./Filter.js";

const SELECTOR = '#filter_plaats';

/**
 * Filter om kampen uit een plaats door te laten.
 */
export class PlaatsFilter extends Filter {

    #value = null;

    constructor(hitkiezer) {
        super(hitkiezer);
        this.#initVelden();
    }
    
    #initVelden() {
        this.hit.hitPlaatsen.forEach(plaats =>
            $("<option>")
                .attr("value", plaats.naam)
                .text("HIT " + plaats.naam)
                .appendTo(SELECTOR)
        );
        $(SELECTOR).change(() => this.update());
    }

    update() {
        let value = $(SELECTOR).val();
        if (value == -1) {
            value = null;
        }
        this.#value = value;
        this.updateEvent()
    }

    filter(kamp) {
        return this.#value == null || this.#value == -1 || (this.#value.toLowerCase() === kamp.plaats.toLowerCase());
    }

}

import { Filter } from "./Filter.js";

export const SELECTOR = "#filter_locatie";

/**
 * Filter om kampen binnen een HIT plaats door te laten.
 */
export class LocatieFilter extends Filter {
    #value = null;

    constructor(hitkiezer) {
        super(hitkiezer);
        this.#initVelden();
    }

    #initVelden() {
        this.hit.hitPlaatsen.forEach((plaats) =>
            $("<option>")
                .attr("value", plaats.naam)
                .text("HIT " + plaats.naam)
                .appendTo(SELECTOR),
        );
        $(SELECTOR).change(() => this.update());
    }

    update(init) {
        let value = $(SELECTOR).val();
        if (value == -1) {
            value = null;
        }
        this.#value = value;
        this.updateEvent(init);
    }

    filter(kamp) {
        return (
            this.#value == null ||
            this.#value == -1 ||
            this.#value.toLowerCase() === kamp.plaats.toLowerCase()
        );
    }
}

import { Filter }  from './Filter.js';

const SELECTOR = '#filter_ouderkind';

/**
 * Filter om ouder-kind kampen door te laten.
 */
export class OuderKindFilter extends Filter {

    #value = -1;

    constructor(hitkiezer) {
        super(hitkiezer);
        this.#initVelden();
    }

    #initVelden() {
        [
            {value: '1', text: 'Ja'},
            {value: '0', text: 'Nee'},
        ]
        .forEach(({value, text}) =>
            $("<option>")
                .attr("value", value)
                .text(text)
                .appendTo(SELECTOR)
        );
        $(SELECTOR).change(() => this.update());
    }

    update() {
        let value = $(SELECTOR).val();
        if (value != null) {
            value = parseInt(value);
        }
        this.#value = value;
        this.updateEvent();
    }

    filter(kamp) {
        return this.#value == null || this.#value === -1 || (this.#value === kamp.isouderkind);
    }

    isFilterActief(kamp) {
        return this.#value != null && this.#value != -1 && kamp.isouderkind === 1;
    }
}

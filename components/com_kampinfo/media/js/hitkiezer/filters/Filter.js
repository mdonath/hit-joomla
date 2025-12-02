/**
 * Parent class voor filters.
 */
export class Filter {

    #hitkiezer;

    constructor(hitkiezer) {
        this.#hitkiezer = hitkiezer;
    }

    get hitkiezer() {
        return this.#hitkiezer;
    }

    get hit() {
        return this.#hitkiezer.hit;
    }

    updateEvent() {
        this.#hitkiezer.updateEvent();
    }
}

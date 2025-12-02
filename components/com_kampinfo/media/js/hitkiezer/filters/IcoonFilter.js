import { kampinfoConfig } from "../util.js";
import { Filter } from "./Filter.js";

const SELECTOR = '#filter_pictos';

/**
 * Filter voor icoontjes.
 */
export class IcoonFilter extends Filter {

    // Icoontjes die positief meetellen
    #groen;
    // Icoontjes die negatief meetellen
    #rood;

    constructor(hitkiezer) {
        super(hitkiezer);
        this.#groen = new IconColorListFilter('groen');
        this.#rood = new IconColorListFilter('rood');
    }

    score(kamp, baseScore) {
        let score = baseScore
        kamp.iconen.forEach(icoon => {
            // elke groene icoon levert 2 punten op
            score += this.#groen.score(icoon.bestandsnaam, 2.0);
            // elke rode icoon kost 2 punten
            score -= this.#rood.score(icoon.bestandsnaam, 2.0);
            // maar nooit minder dan 0
            if (score < 0) {
                score = 0;
            }
        });
        return score;
    }

    loadIconFiltersFromCookie() {
        this.#groen.loadFromCookie();
        this.#rood.loadFromCookie();
    }

    clearIconFilters() {
        this.#groen.clear();
        this.#rood.clear();
    }

    #randKleur(id) {
        if (this.#isGroen(id)) {
            return 'green';
        }
        if (this.#isRood(id)) {
            return 'red';
        }
        return 'black';
    }

    #isGroen(id) {
        return this.#groen.contains(id);
    }

    #isRood(id) {
        return this.#rood.contains(id);
    }

    #naarRood(id) {
        this.#groen.remove(id);
        this.#rood.add(id);
    }

    #naarGroen(id) {
        this.#groen.add(id);
        this.#rood.remove(id);
    }

    #naarZwart(id) {
        this.#groen.remove(id);
        this.#rood.remove(id);
    }

    draw() {
        // Maak pictogrammen op scherm leeg.
        $(SELECTOR).empty();

        // Verzamel de gewenste set iconen.
        const gebruikteIconen = []
        this.hit.hitPlaatsen.forEach(plaats => 
            plaats.kampen.forEach(kamp => {
                if (kamp.score >= 0) {
                    // Kijk voor elk kamp met voldoende score of zijn icoontjes al in de gewenste set zit
                    kamp.iconen.forEach(kampIcoon => {
                        let found = false;
                        gebruikteIconen.forEach(verzameldIcoon => 
                            found = found || (kampIcoon.bestandsnaam === verzameldIcoon.bestandsnaam)
                        );
                        if (!found) {
                            gebruikteIconen.push(kampIcoon);
                        }
                    });
                }
            })
        );

        // Sorteer op basis van de vaste icoon-volgorde.
        gebruikteIconen.sort((a, b) => a.volgorde - b.volgorde);

        // Druk iconen af.
        gebruikteIconen.forEach(icoon => {
            $("<img>")
                .on('click', ({currentTarget}) => this.#selectIcoonEvent(icoon.bestandsnaam, currentTarget))
                .attr({
                    id: icoon.bestandsnaam,
                    src: kampinfoConfig.iconFolderLarge + '/' + icoon.bestandsnaam + kampinfoConfig.iconExtension,
                    border: 3,
                    alt: icoon.tekst,
                    title: icoon.tekst,
                    style: "border-color: " + this.#randKleur(icoon.bestandsnaam) 
                })
                .appendTo(SELECTOR);
        });
    }

    #selectIcoonEvent(cellId, element) {
        if (this.#isGroen(cellId)) {
            this.#naarRood(cellId);
        } else if (this.#isRood(cellId)) {
            this.#naarZwart(cellId);
        } else {
            this.#naarGroen(cellId);
        }
        this.hitkiezer.updateEvent(false)
    }

}

class IconColorListFilter {

    #color;
    #list = [];

    constructor(color) {
        this.#color = color;
        this.loadFromCookie();
    }

    contains(id) {
        return (this.#list.indexOf(id) != -1);
    }

    score(icoon_naam, extra) {
        let score = 0.0;
        this.#list.forEach(item => {
            if (icoon_naam === item) {
                score += extra;
            }
        });
        return score;
    }

    add(id) {
        this.#list.push(id);
        this.saveToCookie();
    }

    remove(id) {
        if (this.contains(id)) {
            this.#list.splice(this.#list.indexOf(id), 1);
            this.saveToCookie();
        }
    }

    loadFromCookie() {
        const unsplit = jaaulde.utils.cookies.get(this.#color);
        if (unsplit == null) {
            this.#list = [];
        } else {
            this.#list = unsplit.split('|');
        }
    }

    saveToCookie() {
        jaaulde.utils.cookies.set(this.#color, this.#list.join('|'));
    }

    clear() {
        this.#list.length = 0;
        this.saveToCookie();
    }

    isEmpty() {
        return this.#list.length === 0;
    }

}

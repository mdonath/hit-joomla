import { beforeEach, jest } from "@jest/globals";
import {
    LeeftijdFilter,
    SELECTOR_DAG,
    SELECTOR_MAAND,
    SELECTOR_JAAR,
    SELECTOR_LEEFTIJD,
} from "./LeeftijdFilter";
import { $ } from "jquery";

describe("LeeftijdFilter", () => {
    let filter;

    const hitkiezer = {
        hit: {
            vrijdag: "2026-06-01",
            hitPlaatsen: [
                {
                    kampen: [
                        {
                            minimumLeeftijd: 10,
                            maximumLeeftijd: 15,
                        },
                    ],
                },
            ],
        },
        updateEvent: jest.fn(() => {}),
    };

    beforeEach(() => {
        // leak jquery
        window.$ = $;
        // setup document body
        document.body.innerHTML = `
        <select id="filter_geboortedag"><option value=""></option></select>
        <select id="filter_geboortemaand"><option value=""></option></select>
        <select id="filter_geboortejaar"><option value=""></option></select>
        <span id="leeftijd"></span>
        `;
    });

    describe("vullen van de dropdowns", () => {
        let filter;

        beforeEach(() => {
            filter = new LeeftijdFilter(hitkiezer);
        });

        it("vult de dropdown voor geboortedag met 1-31", () => {
            expect($(SELECTOR_DAG).children().length).toBe(32);
        });

        it("vult de dropdown voor geboortemaand met de namen van de maanden", () => {
            const texts = $(SELECTOR_MAAND)
                .children()
                .map((_, el) => el.text)
                .toArray();

            expect(texts).toEqual([
                "",
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
            ]);
        });

        it("vult de dropdown voor geboortejaar met jaren passend bij de leeftijdsgrenzen van de kampen", () => {
            const values = $(SELECTOR_JAAR)
                .children()
                .map((_, el) => el.value)
                .toArray();

            expect(values).toEqual([
                "",
                "2016",
                "2015",
                "2014",
                "2013",
                "2012",
                "2011",
            ]);
        });
    });

    it("geeft het juiste hitjaar terug op basis van de peildatum", () => {
        hitkiezer.hit.vrijdag = "2026-04-05";

        filter = new LeeftijdFilter(hitkiezer);

        expect(filter.hitjaar).toBe(2026);
    });

    it("roept updateEvent aan bij update", () => {
        filter = new LeeftijdFilter(hitkiezer);
        filter.update();
        expect(hitkiezer.updateEvent).toHaveBeenCalled();
    });

    describe("filter", () => {
        beforeEach(() => {
            hitkiezer.hit.vrijdag = "2026-06-01";
            hitkiezer.hit.hitPlaatsen[0].kampen.push({
                // forceer een grote reeks geboortejaren, zodat we de leeftijdsgrenzen goed kunnen testen
                minimumLeeftijd: 0,
                maximumLeeftijd: 100,
            });
            filter = new LeeftijdFilter(hitkiezer);
        });

        it("geeft true terug als gefilterd wordt zonder geboortedatum", () => {
            filter.update();

            const result = filter.filter(null, false);

            expect(result).toBe(true);
        });

        it.each`
            geboortedatum   | margeJong | minLeeftijd | maxLeeftijd | margeOud | expLeeftijd  | expected
            ${[1, 6, 2009]} | ${0}      | ${10}       | ${15}       | ${376}   | ${"17 jaar"} | ${true}
            ${[1, 6, 2010]} | ${0}      | ${10}       | ${15}       | ${10}    | ${"16 jaar"} | ${true}
            ${[1, 6, 2010]} | ${0}      | ${10}       | ${15}       | ${0}     | ${"16 jaar"} | ${false}
            ${[1, 6, 2011]} | ${0}      | ${10}       | ${15}       | ${0}     | ${"15 jaar"} | ${true}
            ${[1, 1, 2016]} | ${0}      | ${10}       | ${15}       | ${0}     | ${"10 jaar"} | ${true}
            ${[1, 6, 2016]} | ${0}      | ${10}       | ${15}       | ${0}     | ${"10 jaar"} | ${true}
            ${[2, 6, 2016]} | ${0}      | ${10}       | ${15}       | ${0}     | ${"9 jaar"}  | ${false}
            ${[3, 6, 2016]} | ${2}      | ${10}       | ${15}       | ${0}     | ${"9 jaar"}  | ${true}
        `(
            `geeft $expected terug en leeftijd is $expLeeftijd met leeftijdsgrenzen [$minLeeftijd(-$margeJong) - $maxLeeftijd(+$margeOud)] en geboortedatum $geboortedatum`,
            ({
                geboortedatum,
                margeJong,
                minLeeftijd,
                maxLeeftijd,
                margeOud,
                expLeeftijd,
                expected,
            }) => {
                const [gebDag, gebMaand, gebJaar] = geboortedatum;
                setGeboortedatum(gebDag, gebMaand, gebJaar);
                filter.update();

                const kamp = {
                    startDatumTijd: new Date("2026-06-01T00:00:00+02:00"),
                    eindDatumTijd: new Date("2026-06-10T00:00:00+02:00"),
                    margeAantalDagenTeJong: margeJong,
                    minimumLeeftijd: minLeeftijd,
                    maximumLeeftijd: maxLeeftijd,
                    margeAantalDagenTeOud: margeOud,
                };

                const result = filter.filter(kamp, false);

                expect(result).toBe(expected);
                expect($(SELECTOR_LEEFTIJD).text()).toEqual(
                    `, dan is je leeftijd tijdens de HIT ${expLeeftijd}.`,
                );
            },
        );

        it.each`
            geboortedatum    | minLeeftijdOuder | maxLeeftijdOuder | expLeeftijdOuder | expected
            ${[1, 6, 1975]}  | ${30}            | ${50}            | ${"51 jaar"}     | ${false}
            ${[9, 6, 1975]}  | ${30}            | ${50}            | ${"50 jaar"}     | ${false}
            ${[10, 6, 1975]} | ${30}            | ${50}            | ${"50 jaar"}     | ${true}
            ${[1, 6, 1996]}  | ${30}            | ${50}            | ${"30 jaar"}     | ${true}
            ${[1, 6, 1997]}  | ${30}            | ${50}            | ${"29 jaar"}     | ${false}
        `(
            `geeft $expected terug en leeftijd is $expLeeftijdOuder met leeftijdsgrenzen [$minLeeftijdOuder - $maxLeeftijdOuder] en geboortedatum $geboortedatum`,
            ({
                geboortedatum,
                minLeeftijdOuder,
                maxLeeftijdOuder,
                expLeeftijdOuder,
                expected,
            }) => {
                const [gebDag, gebMaand, gebJaar] = geboortedatum;
                setGeboortedatum(gebDag, gebMaand, gebJaar);
                filter.update();

                const kamp = {
                    startDatumTijd: new Date("2026-06-01T00:00:00+02:00"),
                    eindDatumTijd: new Date("2026-06-10T00:00:00+02:00"),
                    minimumLeeftijd: 5,
                    maximumLeeftijd: 10,
                    minimumLeeftijdOuder: minLeeftijdOuder,
                    maximumLeeftijdOuder: maxLeeftijdOuder,
                };

                const result = filter.filter(kamp, true);

                expect(result).toBe(expected);
                expect($(SELECTOR_LEEFTIJD).text()).toEqual(
                    `, dan is je leeftijd tijdens de HIT ${expLeeftijdOuder}.`,
                );
            },
        );
    });

    describe("update", () => {
        it("maakt alles leeg als er iets niet is ingevuld", () => {
            const filter = new LeeftijdFilter(hitkiezer);

            filter.update();
            expect($(SELECTOR_LEEFTIJD).text()).toBe("");

            setGeboortedatum(1, 1, "");
            filter.update();
            expect($(SELECTOR_LEEFTIJD).text()).toBe("");

            setGeboortedatum(1, "", 2010);
            filter.update();
            expect($(SELECTOR_LEEFTIJD).text()).toBe("");

            setGeboortedatum("", 1, 2010);
            filter.update();
            expect($(SELECTOR_LEEFTIJD).text()).toBe("");

            setGeboortedatum(1, 1, 2010);
            filter.update();
            expect($(SELECTOR_LEEFTIJD).text()).toBe(
                ", dan is je leeftijd tijdens de HIT 16 jaar.",
            );
        });
    });

    const setGeboortedatum = (dag, maand, jaar) => {
        $(SELECTOR_DAG).val(dag);
        $(SELECTOR_MAAND).val(maand);
        $(SELECTOR_JAAR).val(jaar);
    };
});

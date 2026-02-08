import { beforeEach, describe, expect, jest } from "@jest/globals";
import { mockFn } from "jest-mock-extended";
import { SELECTOR, IcoonFilter } from "./IcoonFilter";
import { CookieWrapper } from "./CookieWrapper";
import { kampinfoConfig } from "../util";
import { $ } from "jquery";

describe("IcoonFilter", () => {
    beforeEach(() => {
        // leak jquery
        window.$ = $;
        // setup document body
        document.body.innerHTML = `<div id="filter_pictos"></div>`;

        kampinfoConfig.iconFolderLarge = "/media/com_kampinfo/icons/large";
        kampinfoConfig.iconExtension = ".png";
    });

    describe("filter", () => {
        let filter;
        let hit;

        let cookieGetterFn;
        let cookieSetterFn;

        beforeEach(() => {
            hit = {
                hitPlaatsen: [
                    {
                        naam: "Alphen",
                        kampen: [
                            {
                                score: 1,
                                naam: "Kamp A",
                                iconen: [
                                    {
                                        bestandsnaam: "icon1",
                                        tekst: "Icon 1",
                                        volgorde: 1,
                                    },
                                    {
                                        bestandsnaam: "icon3",
                                        tekst: "Icon 3",
                                        volgorde: 3,
                                    },
                                ],
                            },
                        ],
                    },
                    {
                        naam: "Zeeland",
                        kampen: [
                            {
                                score: 1,
                                naam: "Kamp Z",
                                iconen: [
                                    {
                                        bestandsnaam: "icon2",
                                        tekst: "Icon 2",
                                        volgorde: 2,
                                    },
                                    {
                                        bestandsnaam: "icon4",
                                        tekst: "Icon 4",
                                        volgorde: 4,
                                    },
                                ],
                            },
                        ],
                    },
                ],
            };
            const hitkiezer = {
                hit,
                updateEvent: jest.fn(),
            };

            cookieGetterFn = mockFn();
            cookieSetterFn = mockFn();
            const cookieWrapperMock = new CookieWrapper(
                cookieGetterFn,
                cookieSetterFn,
            );

            filter = new IcoonFilter(hitkiezer, cookieWrapperMock);
        });

        describe("iconen", () => {
            it("toont alle in de kampen aanwezige iconen in een vaste volgorde", () => {
                filter.draw();

                $(SELECTOR)
                    .find("img")
                    .each((index, element) => {
                        expect($(element).attr("src")).toContain(
                            `icon${index + 1}`,
                        );
                    });
            });

            it("toont initieel alle iconen met border=black", () => {
                filter.draw();

                verifyBorderColors(["black", "black", "black", "black"]);
            });

            it("toont iconen met border conform de cookies", () => {
                mockCookieForColor("groen", ["icon1", "icon3"]);
                mockCookieForColor("rood", ["icon2"]);
                filter.loadIconFiltersFromCookie();

                filter.draw();

                verifyBorderColors(["green", "red", "green", "black"]);
            });

            it("roept clear aan op zowel groene als rode filters", () => {
                mockCookieForColor("groen", ["icon1", "icon2"]);
                filter.loadIconFiltersFromCookie();
                filter.draw();
                verifyBorderColors(["green", "green", "black", "black"]);

                filter.clearIconFilters();
                filter.draw();
                verifyBorderColors(["black", "black", "black", "black"]);
            });

            it("togglet de kleur van zwart->groen->rood->zwart als er op geklikt wordt", () => {
                filter.draw();
                verifyBorderColors(["black", "black", "black", "black"]);

                clickOnIcon($(SELECTOR).find("img").first());
                verifyBorderColors(["green", "black", "black", "black"]);

                clickOnIcon($(SELECTOR).find("img").first());
                verifyBorderColors(["red", "black", "black", "black"]);

                clickOnIcon($(SELECTOR).find("img").first());
                verifyBorderColors(["black", "black", "black", "black"]);
            });

            const clickOnIcon = (iconElement) => {
                iconElement.trigger("click", iconElement);
                filter.draw();
            };

            const mockCookieForColor = (color, iconNames) => {
                cookieGetterFn
                    .calledWith(color)
                    .mockReturnValue(iconNames.join("|"));
            };

            const verifyBorderColors = (expectedIconColors) => {
                $(SELECTOR)
                    .find("img")
                    .each((index, element) => {
                        const borderColor = $(element).css("border-color");
                        expect(borderColor).toBe(expectedIconColors[index]);
                    });
            };
        });

        describe("score", () => {
            it("geeft geen negatieve score terug", () => {
                // Dit kamp heeft icon1 en icon3, die beide rood zijn.
                cookieGetterFn
                    .calledWith("rood")
                    .mockReturnValue("icon1|icon3");
                filter.loadIconFiltersFromCookie();

                filter.draw();

                const score = filter.score(hit.hitPlaatsen[0].kampen[0], 0.0);

                expect(score).toBeGreaterThanOrEqual(0.0);
            });

            it("geeft de juiste score terug voor een kamp met groene en rode iconen", () => {
                cookieGetterFn
                    .calledWith("groen")
                    .mockReturnValue("icon1|icon3");
                cookieGetterFn
                    .calledWith("rood")
                    .mockReturnValue("icon2|icon4");
                filter.loadIconFiltersFromCookie();

                filter.draw();

                const score = filter.score(hit.hitPlaatsen[0].kampen[0], 0.0);
                expect(score).toBe(4.0);
            });
        });
    });
});

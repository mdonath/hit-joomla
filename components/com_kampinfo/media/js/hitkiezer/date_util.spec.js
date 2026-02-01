import { describe, it } from "@jest/globals";
import {
    toDateTime,
    parseDate,
    parseDateTime,
    createDate,
    createDateTime,
} from "./date_util.js";

describe("date_util", () => {
    describe("toDateTime", () => {
        it("should convert a Date object to a date time string", () => {
            // 1 Feb 2026 02:04:05 UTC -> 1-2-2026 03:04:05
            const date = new Date(Date.UTC(2026, 1, 1, 2, 4, 5));
            const result = toDateTime(date);
            expect(result).toBe("1-2-2026 03:04:05");
        });
    });

    describe("parseDate", () => {
        it("should create a Date object from a yyyy-mm-dd string", () => {
            const date = parseDate("2026-02-13");
            expect(date.getFullYear()).toBe(2026);
            expect(date.getMonth()).toBe(1); // Months are zero-based
            expect(date.getDate()).toBe(13);

            expect(date.getHours()).toBe(0);
            expect(date.getMinutes()).toBe(0);
            expect(date.getSeconds()).toBe(0);
        });
    });

    describe("parseDateTime", () => {
        it("should create a Date object from a yyyy-mm-ddThh:mm:ss string", () => {
            const date = parseDateTime("2026-02-01T03:04:05");
            expect(date.getFullYear()).toBe(2026);
            expect(date.getMonth()).toBe(1); // Months are zero-based
            expect(date.getDate()).toBe(1);

            expect(date.getHours()).toBe(3);
            expect(date.getMinutes()).toBe(4);
            expect(date.getSeconds()).toBe(5);
        });

        it("should return empty string for invalid date string", () => {
            const date = parseDateTime("invalid-date-string");
            expect(date).toBe("");
        });
    });

    describe("createDate", () => {
        it("should create a Date object with the specified parameters and time 0,0,0", () => {
            const date = createDate(2026, 2, 1);
            expect(date.getFullYear()).toBe(2026);
            expect(date.getMonth()).toBe(1); // Months are zero-based
            expect(date.getDate()).toBe(1);

            expect(date.getHours()).toBe(0);
            expect(date.getMinutes()).toBe(0);
            expect(date.getSeconds()).toBe(0);
        });
    });

    describe("createDateTime", () => {
        it("should create a Date object with the specified parameters", () => {
            const date = createDateTime(2026, 2, 1, 3, 4, 5);
            expect(date.getFullYear()).toBe(2026);
            expect(date.getMonth()).toBe(1); // Months are zero-based
            expect(date.getDate()).toBe(1);

            expect(date.getHours()).toBe(3);
            expect(date.getMinutes()).toBe(4);
            expect(date.getSeconds()).toBe(5);
        });

        it("should give a date 10 days before given date when subtracting 10 days", () => {
            const date = createDateTime(2026, 1, 1 - 10, 0, 0, 0);
            expect(date.getFullYear()).toBe(2025);
            expect(date.getMonth()).toBe(11); // Months are zero-based
            expect(date.getDate()).toBe(22);

            expect(date.getHours()).toBe(0);
            expect(date.getMinutes()).toBe(0);
            expect(date.getSeconds()).toBe(0);
        });
    });
});

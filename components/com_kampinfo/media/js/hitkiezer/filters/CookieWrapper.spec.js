import { beforeEach, describe, expect, it, jest } from "@jest/globals";
import { CookieWrapper } from "./CookieWrapper";

describe("CookieWrapper", () => {
    const cookieGetterFn = jest.fn();
    const cookieSetterFn = jest.fn();
    const cookieBindFn = jest.fn();

    let cookieWrapper;

    beforeEach(() => {
        cookieWrapper = new CookieWrapper(
            cookieGetterFn,
            cookieSetterFn,
            cookieBindFn,
        );
    });

    describe("getCookie", () => {
        it("haalt de cookie op via de cookieGetter", () => {
            cookieGetterFn.mockReturnValue("cookieValue");

            const result = cookieWrapper.getCookie("testCookie");

            expect(cookieGetterFn).toHaveBeenCalledWith("testCookie");
            expect(result).toBe("cookieValue");
        });
    });

    describe("setCookie", () => {
        it("stelt de cookie in via de cookieSetter", () => {
            cookieWrapper.setCookie("testCookie", "cookieValue", { path: "/" });

            expect(cookieSetterFn).toHaveBeenCalledWith(
                "testCookie",
                "cookieValue",
                { path: "/" },
            );
        });
    });

    describe("cookieBind", () => {
        it("One cookie to bind them all", () => {
            cookieWrapper.cookieBind();

            expect(cookieBindFn).toHaveBeenCalled();
        });
    });
});

export class CookieWrapper {
    #cookieGetterFn;
    #cookieSetterFn;
    #cookieBindFn;

    constructor(cookieGetterFn, cookieSetterFn, cookieBindFn) {
        this.#cookieGetterFn = cookieGetterFn;
        this.#cookieSetterFn = cookieSetterFn;
        this.#cookieBindFn = cookieBindFn;
    }

    getCookie(name) {
        return this.#cookieGetterFn(name);
    }

    setCookie(name, value, options) {
        this.#cookieSetterFn(name, value, options);
    }

    cookieBind() {
        this.#cookieBindFn();
    }
}

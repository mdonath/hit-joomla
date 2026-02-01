import { kampinfoConfig } from "./hitkiezer/util.js";
import Kiezer from "./hitkiezer/Kiezer.js";
import { CookieWrapper } from "./hitkiezer/filters/CookieWrapper.js";

if (!window.Joomla) {
    throw new Error("Joomla API was not properly initialised");
}

const options = Joomla.getOptions("com_kampinfo-hitkiezer.vars");
kampinfoConfig.iconFolderLarge = options["iconFolderLarge"];
kampinfoConfig.iconExtension = options["iconExtension"];

const cookieWrapper = new CookieWrapper(
    jaaulde.utils.cookies.get,
    jaaulde.utils.cookies.set,
    () => $(".cookiestore").cookieBind(),
);

new Kiezer(options["hit"], cookieWrapper);

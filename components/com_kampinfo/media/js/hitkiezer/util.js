import { parseDateTime, toDateTime } from './date_util.js';

export const kampinfoConfig = {
    "iconFolderLarge" : null,
    "iconExtension" : null
};

/**
 * Geeft een tekst terug waaraan af te lezen is wanneer de gegevens voor het laatst zijn bijgewerkt.
 * @returns {String}
 */
export function laatstBijgewerktOp() {
    if (window.inschrijvingen) {
        return "Laatst bijgewerkt op: " + toDateTime(parseDateTime(inschrijvingen.timestamp));
    }
    return "";
}

/**
 * Hoe vol is een kamp?
 * Er zijn 4 mogelijkheden: voldoende plaatsen, bijna vol, alleen nog gereserveerde plaatsen, proppievol.
 * @param kamp
 * @return {String} Met een mooie tekst over hoe vol het kamp is.
 */
export function fuzzyIndicatieVol(kamp) {
    if (isInschrijvingActief(kamp) && !isLoterijActief(kamp)) {
        return kamp.fuzzyIndicatieVol;
    }
    return '';
}

export function isInschrijvingActief(activiteit) {
    const nu = (new Date()).getTime();
    const startMoment = parseDateTime(activiteit.startInschrijving).getTime();
    const stopMoment = parseDateTime(activiteit.eindInschrijving).getTime();
    return (nu >= startMoment) && (nu <= stopMoment);
}

export function isLoterijActief(activiteit) {
    const nu = (new Date()).getTime();
    const startMoment = parseDateTime(activiteit.startLoterij).getTime();
    const stopMoment = parseDateTime(activiteit.eindLoterij).getTime();
    return (nu >= startMoment) && (nu <= stopMoment);
}

export function meervoudPlaats(eenAantal) {
    return "plaats" + ((eenAantal != 1) ? "en" : "");
}

/**
 * Is een kamp vol?
 * 
 * @param kamp Het kamp.
 * @returns {Boolean} Of een kamp al volgereserveerd is.
 */
export function isVol(kamp) {
    return kamp.gereserveerd >= kamp.maximumAantalDeelnemers || isVolQuaGroepjes(kamp); 
}

export function isVolQuaGroepjes(kamp) {
    return kamp.maximumAantalSubgroepjes > 0 && kamp.aantalSubgroepen >= kamp.maximumAantalSubgroepjes;
}

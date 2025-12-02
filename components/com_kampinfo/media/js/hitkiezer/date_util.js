/**
 * Geeft een datum met z'n tijd terug in leesbaar formaat.
 * 
 * @param datum De datum.
 * @returns {String}
 */
export function toDateTime(datum) {
    return datum.toLocaleDateString() + " " + datum.toLocaleTimeString();
}

/** 
 * Maakt een Date van een string in het formaat: "yyyy-mm-dd".
 * 
 * @param s De datum.
 * @returns {Date}
 */
export function parseDate(s) {
    return createDate(
        s.substring(0,  4),
        s.substring(5,  7),
        s.substring(8, 10)
    );
}

/**
 * Maakt een Date van een string.
 * 
 * @param s Een string met een datumtijd in het formaat "yyyy-mm-ddThh:mm:ss";
 * @returns {Date}
 */
export function parseDateTime(s) {
    const regex = /([0-9]{4})-([0-9]{2})-([0-9]{2})[T\s]?([0-9]+):([0-9]+):([0-9]+)/;
    const match = regex.exec(s);
    if (!match) {
        return '';
    }

    return createDateTime(
            match[1], match[2], match[3],
            match[4], match[5], match[6]
    );
}

/**
 * Maakt een Date met tijd 0,0,0 van opgegeven parameters.
 * 
 * @param year
 * @param month
 * @param day
 * @returns {Date}
 */
export function createDate(year, month, day) {
    return createDateTime(year, month, day, 0,0,0);
}

/**
 * Maakt een Date van opgegeven parameters.
 * 
 * @param year
 * @param month
 * @param day
 * @param hour
 * @param min
 * @param sec
 * @returns {Date}
 */
export function createDateTime(year, month, day, hour, min, sec) {
    const result = new Date();
    result.setYear(year);
    result.setMonth(month - 1);
    result.setDate(day);
    result.setHours(hour, min, sec, 0);
    return result;
}

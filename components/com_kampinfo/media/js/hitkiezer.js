import { kampinfoConfig } from './hitkiezer/util.js';
import Kiezer from './hitkiezer/Kiezer.js';

if (!window.Joomla) {
    throw new Error('Joomla API was not properly initialised');
}

const options = Joomla.getOptions('com_kampinfo-hitkiezer.vars');
kampinfoConfig.iconFolderLarge = options['iconFolderLarge'];
kampinfoConfig.iconExtension = options['iconExtension'];

new Kiezer(options['hit']);

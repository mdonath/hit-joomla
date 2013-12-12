// Copyright (c) 2012, HIT Scouting Nederland

var $j = jQuery.noConflict();

$j(init);

/**
 * Filtert/scoort met behulp van pictogrammen. 
 * @param color
 * @returns {IconFilter}
 */
function IconFilter(color) {
	this.color = color;
	this.list = new Array();
	this.contains = function (id) {
		return (this.list.indexOf(id) != -1);
	};
	this.add = function (id) {
		this.list.push(id);
		this.save();
	};
	this.remove = function (id) {
		if (this.contains(id)) {
			this.list.splice(this.list.indexOf(id), 1);
			this.save();
		}
	};
	this.load = function() {
		var unsplit = jaaulde.utils.cookies.get(this.color);
		if (unsplit == null) {
			this.list = new Array();
		} else {
			this.list = unsplit.split('|');
		}
		
	};
	this.save = function() {
		jaaulde.utils.cookies.set(this.color, this.list.join('|'));
	},
	this.clear = function() {
		this.list.length = 0;
		this.save();
	};
	this.isEmpty = function() {
		return this.list.length == 0;
	};
}


var filter = {
		// Leeftijd tijdens de HIT
		"peildatum" : null,
		"geboortedatum": null,
		// Deelnamekosten
		"budget": -1,
		// Icoontjes die positief meetellen
		"groen": new IconFilter('groen'),
		// Icoontjes die negatief meetellen
		"rood": new IconFilter('rood'),
		// HIT plaats
		"plaats": null,

		loadFilters: function() {
			this.groen.load();
			this.rood.load();
		},
		clearFilters: function() {
			this.groen.clear();
			this.rood.clear();
		},
		isGroen: function(id) {
			return this.groen.contains(id);
		},
		isRood: function(id) {
			return this.rood.contains(id);
		},
		naarRood: function(id) {
			this.groen.remove(id);
			this.rood.add(id);
		},
		naarGroen: function(id) {
			this.groen.add(id);
			this.rood.remove(id);
		},
		naarZwart: function(id) {
			this.groen.remove(id);
			this.rood.remove(id);
		},

		bepaalScore: function (kamp) {
			var score = 0.0;
			if (this.filterLeeftijd(kamp) && this.filterBudget(kamp) && this.filterVol(kamp) && this.filterPlaats(kamp)) {
				if (this.budget != -1) {
					var afstandTotMaxBudget = this.budget - kamp.deelnamekosten;
					var factor = afstandTotMaxBudget / this.budget;
					var invFactor = 1.0 - factor;
					score += invFactor * 2;
				}
				
				for (var idx = 0; idx < kamp.iconen.length; idx++) {
						if (!this.groen.isEmpty()) {
						$j.each(this.groen.list, function(j, gfl) {
							if (kamp.iconen[idx].bestandsnaam == gfl) {
								score += 2.0; // elke groene icoon levert 2 punten op
							}
						});
					}
					if (!this.rood.isEmpty()) {
						$j.each(this.rood.list, function(j, rfl) {
							if (kamp.iconen[idx].bestandsnaam == rfl) {
								score -= 2.0; // elke rode icoon kost 2 punten
								if (score < 0) {
									score = 0; // maar nooit minder dan 0
								}
							}
						});
					}
				}
			} else {
				score = -1;
			}
			return score;
		},
		leeftijdOpPeildatum: function() {
			var leeftijdInJaren = -1;
			if (this.geboortedatum != null) {
				var leeftijdInJaren = this.peildatum.getFullYear() - this.geboortedatum.getFullYear();
				var verjaardagInHitJaar = createDate(
						this.peildatum.getFullYear(),
						this.geboortedatum.getMonth() + 1,
						this.geboortedatum.getDate());
				if (verjaardagInHitJaar > this.peildatum) {
					leeftijdInJaren--;
				}
			}
			return leeftijdInJaren;
		},
		filterLeeftijd: function (kamp) {
			var geborenNa = createDate(
					kamp.eindDatumTijd.getFullYear() - kamp.maximumLeeftijd - 1, // -1; want hele jaar telt mee 
					kamp.eindDatumTijd.getMonth() + 1, // 0-based
					kamp.eindDatumTijd.getDate() - kamp.margeAantalDagenTeOud + 1); // +1; bij marge=0 mag je op einddatum nog niet maxlft+1 zijn
			var geborenVoor = createDate(
					kamp.startDatumTijd.getFullYear() - kamp.minimumLeeftijd, 
					kamp.startDatumTijd.getMonth() + 1, // 0-based 
					kamp.startDatumTijd.getDate() + kamp.margeAantalDagenTeJong);
			return this.geboortedatum == null || this.geboortedatum >= geborenNa && this.geboortedatum <= geborenVoor;
		},
		filterBudget: function (kamp) {
			return this.budget == -1 || kamp.deelnamekosten <= this.budget;
		},
		filterVol: function (kamp) { // het is ok als...
			return kamp.gereserveerd < kamp.maximumAantalDeelnemers;
		},
		filterPlaats: function (kamp) {
			return this.plaats == null || kamp.plaats.toLowerCase() == this.plaats.toLowerCase();
		}
};

function init() {
	fixStupidBrowsers();
	extend();
	initVelden();
	repaint();
}

function initVelden() {

	// Geboortedag
	for (var i = 1; i < 32; i++) {
		$j("<option>")
			.attr("value", i)
			.text(i)
			.appendTo("#geboortedag");
	}
	
	// Geboortemaand
	$j.each(['januari', 'februari', 'maart', 'april', 'mei', 'juni', 'juli', 'augustus', 'september', 'oktober', 'november', 'december']
			, function(i, maand) {
				$j("<option>")
					.attr("value", i+1)
					.text(maand)
					.appendTo("#geboortemaand");
 			}
	);
 	// Geboortejaar; afhankelijk van minimum- en maximumleeftijd.
	var minMax = minMaxJaar();
 	for (var i = minMax.min; i >= minMax.max; i--) {
		$j("<option>")
			.attr("value", i)
			.text(i)
			.appendTo("#geboortejaar");
	}
 	

 	// prijzen, score, plaatsnaam en start/einddatumtijd
 	var prijzen = new Array();
 	$j.each(hit.hitPlaatsen, function(i, plaats) {
 		$j.each(plaats.kampen, function(j, kamp) {
	 		var found = false;
 		 	$j.each(prijzen, function(k, prijs) {
	 			found = found || (prijs == kamp.deelnamekosten);
	 		});
	 		if (!found){
	 			prijzen.push(kamp.deelnamekosten);
	 		}
			kamp.score = 0;
			kamp.plaats = plaats.naam;
			kamp.startDatumTijd = parseDate(kamp.startDatumTijd);
			kamp.eindDatumTijd = parseDate(kamp.eindDatumTijd);
		});
    });
	prijzen.sort(function(a,b){return a - b;}); // sorteer numeriek	 	
 	$j.each(prijzen, function(i, prijs) {
 		$j("<option>")
			.attr("value", prijs)
			.text(prijs)
			.appendTo("#budget");
	});
 	// plaatsen
 	var plaatsen = new Array();
 	$j.each(hit.hitPlaatsen, function(i, plaats) {
 		$j("<option>")
 			.attr("value", plaats.naam)
 			.text("HIT " + plaats.naam)
 			.appendTo("#plaats");
 	});

 	
 	$j('.cookiestore').cookieBind();
	filter.peildatum = parseDate(hit.vrijdag);
	filter.plaats = $j.getUrlVar('plaats');

	updateGeboorteDatum();
	updateBudget();
	filter.loadFilters();
	toonKampenMetFilter();
}


function repaint() {
	toonKampenMetFilter();
	filterPictogrammen();
}

function toonKampenMetFilter() {
	// kieper huidige lijst leeg
	$j('#kampen').empty();

	// verzamel kampen met score >= 0
	var kampen = new Array();
    $j.each(hit.hitPlaatsen, function(i, plaats) {
		 	$j.each(plaats.kampen, function(j, kamp) {
		 		kamp.score = filter.bepaalScore(kamp);
		 		if (kamp.score >= 0) {
					kampen.push(kamp);
				}
		});
    });

	$j("#count").text(kampen.length);
	
	if (kampen.length > 0) {
		$j("<ul>").attr("id", "kampList").appendTo("#kampen");

		// sorteren op score		
	    kampen.sort(function(a, b) { return b.score - a.score; });
		// overgebleven kampen tonen
	    $j.each(kampen, function(i, kamp) {
			var li = $j("<li>");
			$j("<a>")
			  	.text(kamp.naam + " in " + kamp.plaats)
			  	.attr({
			  		title: "leeftijd: " + kamp.minimumLeeftijd + "-" + kamp.maximumLeeftijd 
			  			 + ", prijs € " + kamp.deelnamekosten
			  			 + ". " + fuzzyIndicatieVol(kamp)
			  			 ,
			  		href: "../hits-in-" + kamp.plaats.toLowerCase() + "-" + hit.jaar + "/" + urlified(kamp.naam) })
			  	.appendTo(li);
			$j("<span>")
				.text("[" + (Math.round(10*kamp.score)/10) + "]")
				.attr({title: "score", 'class': "score"})
				.appendTo(li);
			li.appendTo("#kampList");
	    });
	} else {
		$j("<p>").text("Helaas, geen activiteiten gevonden!").appendTo("#kampen");
	}
}

/**
 * Teken de pictogrammen opnieuw en filter op basis van getoonde kampen.
 */
function filterPictogrammen() {
	// Maak pictogrammen op scherm leeg.
	$j("#pictos").empty();
	
	// Verzamel de gewenste set iconen.
	var gebruikteIconen = new Array();
	$j.each(hit.hitPlaatsen, function(i, plaats) {
		$j.each(plaats.kampen, function(j, kamp) {
			if (kamp.score >= 0) {
				// Kijk voor elk kamp met voldoende score of zijn icoontjes al in de gewenste set zit
				$j.each(kamp.iconen, function(k, kampIcoon) {
					var found = false;
					$j.each(gebruikteIconen, function(l, verzameldIcoon) {
						found = found || (kampIcoon.bestandsnaam == verzameldIcoon.bestandsnaam);
					});						
					if (!found) {
						gebruikteIconen.push(kampIcoon);
					}
				});
			}
		});
	});
	
	// Sorteer op basis van de vaste icoon-volgorde.
	gebruikteIconen.sort(function (a,b) { return a.volgorde - b.volgorde; });
	
	// Druk iconen af.
	$j.each(gebruikteIconen, function(i, icoon) {
		var borderColor = filter.isGroen(icoon.bestandsnaam) ? "green"
				: filter.isRood(icoon.bestandsnaam)	? "red"
				: "black";
	 	$j("<img>")
			.attr({
				id: icoon.bestandsnaam,
				onclick: "selectIcoonEvent('" + icoon.bestandsnaam + "')",
				src: kampinfoConfig.iconFolderLarge + '/' + icoon.bestandsnaam + kampinfoConfig.iconExtension,
				border: 3,
				alt: icoon.tekst,
				title: icoon.tekst,
				style: "border-color: " + borderColor 
			})
			.appendTo("#pictos");
	});
}


/**
 * Als de geboortedatum aangepast wordt.
 */
function updateGeboorteDatumEvent() {
	updateGeboorteDatum();
	filter.clearFilters();
	repaint();
}

function updateGeboorteDatum() {
	if (validateGeboortedatumForm()) {
		filter.geboortedatum = createDate($j('#geboortejaar').val(), $j('#geboortemaand').val(), $j('#geboortedag').val());
		var leeftijd = filter.leeftijdOpPeildatum();

		$j("#leeftijd").text(", dan is je leeftijd tijdens de HIT " + leeftijd + " jaar.");
	} else {
		$j("#leeftijd").text('');
		filter.geboortedatum = null;
	}
}

function validateGeboortedatumForm() {
	return !($j('#geboortedag').val() == '' || $j('#geboortemaand').val() == '' || $j('#geboortejaar').val() == '');
}

/**
 * Als het budget aangepast wordt.
 */
function updateBudgetEvent() {
	updateBudget();
	filter.clearFilters();
	repaint();
}

function updateBudget() {
	filter.budget = $j('#budget').val();
}

/**
 * Als de plaats aangepast wordt.
 */
function updatePlaatsEvent() {
	updatePlaats();
	filter.clearFilters();
	repaint();
}

function updatePlaats() {
	filter.plaats = $j('#plaats').val();
	if (filter.plaats == -1) {
		filter.plaats = null;
	}
}

/**
 * Als er op een icoontje geklikt wordt.
 */
function selectIcoonEvent(cellId) {
	var cell = document.getElementById(cellId);
	var style = cell.style;
	if (style.borderColor == 'green') {
		// groen -- rood
		style.borderColor = 'red';
		filter.naarRood(cellId);
	} else if (style.borderColor == 'red') {
		// rood -- zwart
		style.borderColor = 'black';
		filter.naarZwart(cellId);
	} else {
		// zwart -- groen
		style.borderColor = 'green';
		filter.naarGroen(cellId);
	}
	toonKampenMetFilter();
}

function minMaxJaar() {
	var min = 100;
	var max = 0;

  	$j.each(hit.hitPlaatsen, function(i, plaats) {
	 	$j.each(plaats.kampen, function(j, kamp) {
	 		min = Math.min(min, kamp.minimumLeeftijd);
	 		max = Math.max(max, kamp.maximumLeeftijd);
		 });
	});

 	var hitjaar = parseDate(hit.vrijdag).getFullYear();
	return {min: hitjaar - min, max: hitjaar - max};
}

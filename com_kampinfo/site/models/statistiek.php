<?php defined('_JEXEC') or die('Restricted access');

// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
include_once dirname(__FILE__) . '/kampinfomodelparent.php';

/**
 * KampInfo Deelnemers Model
 */
class KampInfoModelStatistiek extends KampInfoModelParent {

	public function getJaar() {
		$jaar = JRequest :: getInt('jaar');
		if (empty($jaar)) {
			$jaar = date("Y");
		}
		return $jaar;
	}

	public function getSoort() {
		$soort =  JRequest :: getString('soort');
		if (empty($soort)) {
			$soort = 'Standaard';
		}
		return $soort;
	}

	public function getPlaats() {
		$plaats = JRequest :: getString('plaats');
		return $plaats;
	}
	
	/**
	 * Hoofdmethode.
	 * @return unknown
	 */
	public function getStatistiek() {
		$soort = $this->getSoort() . 'Statistiek';
		return new $soort($this->getJaar(), $this->getPlaats());
	}
}

abstract class AbstractStatistiek {
	private $packages;
	private $title;
	private $width;
	private $height;

	public function __construct($packages, $title, $width=600, $height=400) {
		$this->packages = $packages;
		$this->title = $title;
		$this->width = $width;
		$this->height = $height;
	}

	public function setDrawVisualization($drawVisualization) {
		$this->drawVisualization = $drawVisualization;
	}
	public function getPackages() {
		return $this->packages;
	}
	public function getTitle() {
		return $this->title;
	}
	public function getWidth() {
		return $this->width;
	}
	public function getHeight() {
		return $this->height;
	}
	public abstract function getDrawVisualization();

	protected function getBeschikbareJaren() {
		$db = JFactory :: getDBO();

		$query = $db->getQuery(true);
		$query->select('jaar');
		$query->from('#__kampinfo_deelnemers d');
		$query->group('jaar');

		$db->setQuery($query);
		$data = $db->loadObjectList();

		if ($db->getErrorNum()) {
			JError :: raiseWarning(500, $db->getErrorMsg());
		}

		$jaren = array();
		foreach ($data as $row) {
			$jaren[] = $row->jaar;
		}
		return $jaren;
	}
	
	protected function getPlaatsenInJaar($jaar) {
		$db = JFactory :: getDBO();
	
		$query = $db->getQuery(true);
		$query->select('naam');
		$query->from('#__kampinfo_hitsite s');
		$query->join('LEFT', '#__kampinfo_hitproject p on (p.id = s.hitproject_id)');
		$query->where('p.jaar = ' . (int) ($db->getEscaped($jaar)));
		$query->order('s.naam');
	
		$db->setQuery($query);
		$data = $db->loadObjectList();
	
		if ($db->getErrorNum()) {
			JError :: raiseWarning(500, $db->getErrorMsg());
		}
	
		$plaatsNamen = array();
		foreach ($data as $row) {
			$plaatsNamen[] = $row->naam;
		}
		return $plaatsNamen;
	}
}

/**
 * Standaard statistiek als het soort niet is opgegeven.
 */
class StandaardStatistiek extends TotaalInschrijvingenPerJaarStatistiek {
	
}

/**
 * Toont "Totaal aantal inschrijvingen per jaar".
 */
class TotaalInschrijvingenPerJaarStatistiek extends AbstractStatistiek {

	public function __construct($jaar = null) {
		parent::__construct("corechart", "Totaal aantal inschrijvingen per jaar", 600, 400);
	}

	public function getDrawVisualization() {
		$result = "
				function drawVisualization() {
				var data = google.visualization.arrayToDataTable([
				['Jaar', 'Aantal'],
				";
		$data = $this->getData();
		foreach ($data as $row) {
			$result .= "['".$row->jaar . "', " . $row->aantal . "],\n";
		}
		$result .=
		"	]);
			new google.visualization.ColumnChart(document.getElementById('visualization')).
			draw(data, {
				title: '". $this->getTitle()  ."',
				width : ". $this->getWidth()  .",
				height: ". $this->getHeight() .",
				hAxis: {title: \"Jaar\"}
			});
		}";
		return $result;
	}
	
	private function getData() {
		$db = JFactory :: getDBO();
	
		$query = $db
		-> getQuery(true)
		-> select('jaar			AS jaar')
		-> select('count(jaar)	AS aantal')
		-> from('#__kampinfo_deelnemers d')
		-> group('jaar');
	
		$db->setQuery($query);
	
		$data = $db->loadObjectList();
		if ($db->getErrorNum()) {
			JError :: raiseWarning(500, $db->getErrorMsg());
		}
		return $data;
	}
}


/**
 * Toont "Inschrijvingen per dag per jaar".
 */
class InschrijvingenPerDagPerJaarStatistiek extends AbstractStatistiek {

	public function __construct($jaar = null) {
		parent::__construct("corechart", "Inschrijvingen per dag per jaar", 600, 400);
	}

	public function getDrawVisualization() {
		$result = "function drawVisualization() {
				var data = google.visualization.arrayToDataTable([";
		
		$jaren = $this->getBeschikbareJaren();
		$cumulatief = array();
		$header = "['Dag'";
		foreach ($jaren as $jaar) {
			$cumulatief[$jaar] = 0;
			$header .= ", '$jaar'";
		}
		$header .= "],";

		
		$result .= $header;
		$data = $this->getData($jaren);
		foreach ($data as $row) {
			$result .= '['. $row->inschrijfdag;
			foreach ($jaren as $jaar) {
				$column = "y$jaar"; // kolomnaam begint met 'y'
				$cumulatief[$jaar] += $row->$column;
				$result .= ','.$cumulatief[$jaar];
			}
			$result .= '],';
			if ($row->inschrijfdag > 80) { // na dag 80 is het niet meer interessant
				break;
			}
		}

		$result .= "
			]);
			new google.visualization.LineChart(document.getElementById('visualization')).
			draw(data, {
				title: '". $this->getTitle()  ."',
				width : ". $this->getWidth()  .",
				height: ". $this->getHeight() .",
				vAxis: {title: \"Cumulatieve inschrijvingen\"},
				hAxis: {title: \"Inschrijfdag\"},
				curveType: \"function\",
			});
		}";

		return $result;
	}

	private function getData($jaren) {
		$db = JFactory :: getDBO();
		$query = $db->getQuery(true);

		$query->select('1 + datediff(datumInschrijving, (select min(datumInschrijving) from kuw4c_kampinfo_deelnemers d2 where d2.jaar=d.jaar)) as inschrijfdag');
		foreach ($jaren as $jaar) {
			$query->select("sum(if(d.jaar=$jaar,1,0)) as \"y$jaar\"");
		}
		$query->from('#__kampinfo_deelnemers d');
		$query->group('inschrijfdag');
		$db->setQuery($query);

		$data = $db->loadObjectList();
		if ($db->getErrorNum()) {
			JError :: raiseWarning(500, $db->getErrorMsg());
		}
		return $data;
	}
}

/**
 * Het verloop van de inschrijvingen per plaats in één specifiek jaar.
 */
class InschrijvingenPerPlaatsInSpecifiekJaarStatistiek extends AbstractStatistiek {
	private $jaar;
	
	public function __construct($jaar = null) {
		parent::__construct("corechart", "Inschrijvingen per plaats in ". $jaar, 600, 400);
		$this->jaar = $jaar;
	}
	
	public function getDrawVisualization() {
		$result = "function drawVisualization() {
				var data = google.visualization.arrayToDataTable([";
		$result .= "['Datum'";

		$cumulatief = array();
		$plaatsNamen = $this->getPlaatsenInJaar($this->jaar);
		foreach ($plaatsNamen as $plaatsNaam) {
			$cumulatief[$plaatsNaam] = 0;
			$result .= ", '$plaatsNaam'";
		}
		$result .= '],';
		
		$data = $this->getData($plaatsNamen);
		
		foreach ($data as $row) {
			$result .= "['". $row->datumInschrijving . "'";
			foreach ($plaatsNamen as $plaatsNaam) {
				$cumulatief[$plaatsNaam] += $row->$plaatsNaam;
				$result .= ','. $cumulatief[$plaatsNaam];
			}
			$result .= '],';
		}
		
		$result .= "
			]);
			new google.visualization.LineChart(document.getElementById('visualization')).
			draw(data, {
				title: '". $this->getTitle()  ."',
				width : ". $this->getWidth()  .",
				height: ". $this->getHeight() .",
				vAxis: {title: \"Cumulatieve inschrijvingen\"},
				hAxis: {title: \"Inschrijfdatum\"},
				curveType: \"function\",
			});
		}";
		
		return $result;
	}
	
	private function getData($plaatsNamen) {
		$db = JFactory :: getDBO();
		$query = $db->getQuery(true);
		
		$query->select("d.jaar");
		$query->select("d.datumInschrijving");
		foreach ($plaatsNamen as $plaatsNaam) {
			$query->select("sum(if(s.naam='$plaatsNaam' and d.jaar=p.jaar,1,0)) as $plaatsNaam");
		}
		$query->from('#__kampinfo_deelnemers d');
		$query->innerJoin('#__kampinfo_hitsite s on (d.hitsite = s.naam)');
		$query->innerJoin('#__kampinfo_hitproject p on (s.hitproject_id = p.id and p.jaar = d.jaar)');
		$query->where('(d.jaar = ' . (int) ($db->getEscaped($this->jaar)) . ')');
		$query->group("jaar, datumInschrijving");
		
		$db->setQuery($query);
		
		$data = $db->loadObjectList();
		if ($db->getErrorNum()) {
			JError :: raiseWarning(500, $db->getErrorMsg());
		}
		return $data;
	}
}

class HerkomstDeelnemersInJaarStatistiek extends AbstractStatistiek {
	private $jaar;
	
	public function __construct($jaar = null) {
		parent::__construct("geochart", "Herkomst deelnemers in ". $jaar, 600, 400);
		$this->jaar = $jaar;
	}
	
	public function getDrawVisualization() {
		$result = "function drawVisualization() {
				var data = google.visualization.arrayToDataTable([";
		$result .= "['Plaats', 'Aantal'],";
	
		$data = $this->getData();
	
		foreach ($data as $row) {
			$result .= "['". $row->plaats . "', " . $row->aantal ."],\n";
		}
	
		$result .= "
			]);
			new google.visualization.GeoChart(document.getElementById('visualization')).
			draw(data, {
				title: '". $this->getTitle()  ."',
				width : ". $this->getWidth()  .",
				height: ". $this->getHeight() .",
				region: 'NL',
				displayMode: 'markers',
				resolution: 'provinces'
			});
		}";
		return $result;
	}
	
	private function getData() {
		$db = JFactory :: getDBO();
		$query = $db->getQuery(true);
	
		$query->select("d.herkomst as plaats");
		$query->select("count(*) as aantal");
		$query->from('#__kampinfo_deelnemers d');
		$query->where('(d.jaar = ' . (int) ($db->getEscaped($this->jaar)) . ')');
		$query->group("herkomst");
		$query->order("aantal desc");
	
		$db->setQuery($query);
	
		$data = $db->loadObjectList();
		if ($db->getErrorNum()) {
			JError :: raiseWarning(500, $db->getErrorMsg());
		}
		return $data;
	}
}


class VerloopInschrijvingenInPlaatsStatistiek extends AbstractStatistiek {
	private $jaar;
	private $plaats;
	
	public function __construct($jaar = null, $plaats = null) {
		parent::__construct("corechart", "Verloop inschrijvingen van HIT ". $plaats . " over alle jaren heen", 600, 400);
		$this->jaar = $jaar;
		$this->plaats = $plaats;
	}
	
	public function getDrawVisualization() {
		$result = "function drawVisualization() {
				var data = google.visualization.arrayToDataTable([";
		
		$jaren = $this->getBeschikbareJaren();
		$cumulatief = array();
		$header = "['Dag'";
		foreach ($jaren as $jaar) {
			$cumulatief[$jaar] = 0;
			$header .= ", '$jaar'";
		}
		$header .= "],";

		
		$result .= $header;
		$data = $this->getData($jaren);
		foreach ($data as $row) {
			$result .= '['. $row->inschrijfdag;
			foreach ($jaren as $jaar) {
				$column = "y$jaar"; // kolomnaam begint met 'y'
				$cumulatief[$jaar] += $row->$column;
				$result .= ','.$cumulatief[$jaar];
			}
			$result .= '],';
			if ($row->inschrijfdag > 80) { // na dag 80 is het niet meer interessant
				break;
			}
		}

		$result .= "
			]);
			new google.visualization.LineChart(document.getElementById('visualization')).
			draw(data, {
				title: '". $this->getTitle()  ."',
				width : ". $this->getWidth()  .",
				height: ". $this->getHeight() .",
				vAxis: {title: \"Cumulatieve inschrijvingen\"},
				hAxis: {title: \"Inschrijfdag\"},
				curveType: \"function\",
			});
		}";

		return $result;
	}

	private function getData($jaren) {
		$db = JFactory :: getDBO();
		$query = $db->getQuery(true);

		$query->select('1 + datediff(datumInschrijving, (select min(datumInschrijving) from kuw4c_kampinfo_deelnemers d2 where d2.jaar=d.jaar)) as inschrijfdag');
		foreach ($jaren as $jaar) {
			$query->select("sum(if(d.jaar=$jaar and lower(d.hitsite) = lower(s.naam),1,0)) as \"y$jaar\"");
		}
		$query->from('#__kampinfo_deelnemers d');
		$query->innerJoin('#__kampinfo_hitsite s on (lower(d.hitsite) = lower(s.naam))');
		$query->innerJoin('#__kampinfo_hitproject p on (s.hitproject_id = p.id and d.jaar = p.jaar)');
		$query->where("s.naam = ". $db->quote($db->getEscaped($this->plaats)));
		$query->group('inschrijfdag');
		$db->setQuery($query);

		$data = $db->loadObjectList();
		if ($db->getErrorNum()) {
			JError :: raiseWarning(500, $db->getErrorMsg());
		}
		return $data;
	}
}


class OpbouwLeeftijdPerJaarStatistiek extends AbstractStatistiek {
	private $jaar;
	private $plaats;

	public function __construct($jaar = null, $plaats = null) {
		parent::__construct("controls", "Opbouw leeftijd over alle jaren heen", 600, 400);
		$this->jaar = $jaar;
		$this->plaats = $plaats;
	}

	public function getDrawVisualization() {
		$result = "function drawVisualization() {
				var data = google.visualization.arrayToDataTable([";

		$jaren = $this->getBeschikbareJaren();
		$header = "['Leeftijd'";
		foreach ($jaren as $jaar) {
			$header .= ", '$jaar'";
		}
		$header .= "],";

		$result .= $header;
		$data = $this->getData($jaren);
		foreach ($data as $row) {
			$result .= '['. $row->leeftijd;
			foreach ($jaren as $jaar) {
				$column = "y$jaar"; // kolomnaam begint met 'y'
				$result .= ','.$row->$column;
			}
			$result .= '],';
		}

		$result .= "
			]);
			var lftSlider = new google.visualization.ControlWrapper({
	          'controlType': 'NumberRangeFilter',
	          'containerId': 'control',
	          'options': {
	            'filterColumnLabel': 'Leeftijd',
	            'minValue': 5,
	            'maxValue': 70
	          }
	        });
			 var chart = new google.visualization.ChartWrapper({
	          'chartType': 'LineChart',
	          'containerId': 'visualization',
	          'options': {
	            title: '". $this->getTitle()  ."',
				width : ". $this->getWidth()  .",
				height: ". $this->getHeight() .",
				hAxis: {title: \"Leeftijd\"},
				vAxis: {title: \"Aantal deelnemers\"},
				curveType: \"function\",
	          }
	        });
			var dashboard = new google.visualization.Dashboard(document.getElementById('dashboard'))
						.bind(lftSlider, chart)
						.draw(data);
		}";

		return $result;
	}

	private function getData($jaren) {
		$db = JFactory :: getDBO();
		$query = $db->getQuery(true);

		$query->select('leeftijd');
		foreach ($jaren as $jaar) {
			$query->select("sum(if(d.jaar=$jaar,1,0)) as \"y$jaar\"");
		}
		$query->from('#__kampinfo_deelnemers d');
		$query->group('leeftijd');
		$db->setQuery($query);

		$data = $db->loadObjectList();
		if ($db->getErrorNum()) {
			JError :: raiseWarning(500, $db->getErrorMsg());
		}
		return $data;
	}
}

class AantalKampenVoorLeeftijdInJaarStatistiek extends AbstractStatistiek {
	private $jaar;

	public function __construct($jaar = null) {
		parent::__construct("corechart", "Aantal kampen per leeftijdsjaar in ".$jaar, 600, 400);
		$this->jaar = $jaar;
	}

	public function getDrawVisualization() {
		// Bepaal minimumLeeftijd en maximumLeeftijd
		// select min(c.minimumLeeftijd), max(c.maximumLeeftijd) from kuw4c_kampinfo_hitcamp c join kuw4c_kampinfo_hitsite s on (c.hitsite_id = s.id) join kuw4c_kampinfo_hitproject p on (p.id = s.hitproject_id and p.jaar = 2014) order by c.minimumLeeftijd
		$min = 7;
		$max = 88;

		$leeftijdenKolommen = '';
		for ($age = $min; $age <= $max; $age++) {
			$leeftijdenKolommen .= "'$age',";
		}
		$result = "
				function drawVisualization() {
				var data = google.visualization.arrayToDataTable([
				[ $leeftijdenKolommen ],
				";
		$data = $this->getData($min, $max)[0];
		
		$result .= "[";
		for ($age = $min; $age <= $max; $age++) {
			$field = 'l'.$age;
			$result .=  $data->$field. ",";
		}
		$result .= "],\n";
		
		$result .=
		"	]);
			new google.visualization.ColumnChart(document.getElementById('visualization')).
			draw(data, {
				title: '". $this->getTitle()  ."',
				width : ". $this->getWidth()  .",
				height: ". $this->getHeight() .",
				hAxis: {title: \"Leeftijd\"}
			});
		}";
		return $result;
	}
	
	private function getData($min, $max) {
		$db = JFactory :: getDBO();
		
		// Query
		// select sum(if(7 between data.mini and data.maxi,1,0)) as l7, sum(if(8 between data.mini and data.maxi,1,0)) as l8 from ;
		$query = $db-> getQuery(true);
		for ($age = $min; $age <= $max; $age++) {
			$query->select("sum(if($age between data.mini and data.maxi,1,0)) as l$age");
		}
		$query->from("(select c.minimumLeeftijd  as mini, c.maximumLeeftijd as maxi from #__kampinfo_hitcamp c join #__kampinfo_hitsite s on (c.hitsite_id = s.id) join #__kampinfo_hitproject p on (p.id = s.hitproject_id and p.jaar = $this->jaar) order by c.minimumLeeftijd) data");
	
		$db->setQuery($query);
	
		$data = $db->loadObjectList();
		if ($db->getErrorNum()) {
			JError :: raiseWarning(500, $db->getErrorMsg());
		}
		return $data;
	}
}

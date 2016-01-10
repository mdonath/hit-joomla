<?php defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR .'/../com_kampinfo/helpers/kampinfo.php';
require_once JPATH_COMPONENT_ADMINISTRATOR .'/../com_kampinfo/helpers/kampinfourl.php';

// config
$params = JComponentHelper::getParams('com_kampinfo');

// model
$statistiek = $this->statistiek;
?>
<article class="itempage" itemtype="http://schema.org/Article" itemscope="">
	<hgroup>
		<h1><?php echo ($statistiek->getTitle());?></h1>
	</hgroup>

	<div itemprop="articleBody">

<!-- Start content -->
		<div id="dashboard">
			<div id="visualization" style="width: <?php echo ($statistiek->getWidth());?>px; height: <?php echo ($statistiek->getHeight());?>px;"></div>
		</div>
		<div id="control"></div>
<!-- Eind content -->
	</div>

</article>						
<?php defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR .'/../com_kampinfo/helpers/kampinfo.php';
require_once JPATH_COMPONENT_ADMINISTRATOR .'/../com_kampinfo/helpers/kampinfourl.php';

// config
$params =JComponentHelper::getParams('com_kampinfo');

// model
$statistiek = $this->statistiek;

?>
<div class="rt-article">
	<div class="item-page">
		<div class="module-content-pagetitle">
			<div class="module-l">
				<div class="module-r">
					<div class="rt-headline">
						<div class="module-title">
							<div class="module-title2">
								<h1 class="title rt-pagetitle">HIT Statistieken</h1>
							</div>
						</div>
					</div>
					<div class="clear"></div>
				</div>
			</div>
		</div>

		<div class="module-content">
			<div class="module-l">
				<div class="module-r">
					<div class="module-inner">
						<div class="module-inner2">
<!-- Start content -->
						<h2><?php echo ($statistiek->getTitle());?></h2>
						<div id="dashboard">
							<div id="visualization" style="width: <?php echo ($statistiek->getWidth());?>px; height: <?php echo ($statistiek->getHeight());?>px;"></div>
							<div id="control"></div>
						</div>
<!-- Eind content -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
						
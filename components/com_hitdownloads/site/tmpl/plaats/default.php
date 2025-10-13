<?php 

\defined('_JEXEC') or die('Restricted access');

$jaar = $this->jaar;
$plaats = $this->plaats;
$files = $this->files;

?> 
<div class="rt-article">
    <div class="item-page">
        <div class="module-content-pagetitle">
            <div class="module-l">
                <div class="module-r">
                    <div class="rt-headline">
                        <div class="module-title">
                            <div class="module-title2">
                                <h1 class="title rt-pagetitle">De Downloads van HIT <?php echo $plaats; ?> in <?php echo $jaar; ?></h1>
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


<p>Overzicht van alle downloads van HIT <?=$plaats?> in  <?=$jaar?></p>
<ul>
<?php foreach($files as $file): ?>
    <li><a href="<?=$file['location']?>" target="_blank"><?=$file['naam']?> (<?=$file['type']?>)</a></li>
<?php endforeach; ?>
</ul>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

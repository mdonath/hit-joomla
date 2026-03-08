<?php

// No direct access
defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
?>

<?php if ($result != null && ($this->isGevuld($result->facebook) || $this->isGevuld($result->instagram))) : ?>

    <p>
        <?php Text::printf("PLG_CONTENT_SOCIALMEDIA_FOLLOW_US", $result->plaats) ?>
    </p>

    <p>
        <?php if ($this->isGevuld($result->facebook)) : ?>
            <a title='<?php Text::printf("PLG_CONTENT_SOCIALMEDIA_FACEBOOK", $result->plaats) ?>' href='<?= $result->facebook ?>' target='_blank' rel='noopener'>
                <img src='/media/plg_content_socialmedia/images/facebook.png' alt='Facebook' width='64' height='64'>
            </a>
        <?php endif; ?>

        <?php if ($this->isGevuld($result->instagram)) : ?>
            <a title='<?php Text::printf("PLG_CONTENT_SOCIALMEDIA_INSTAGRAM", $result->plaats) ?>' href='<?= $result->instagram ?>' target='_blank' rel='noopener'>
                <img src='/media/plg_content_socialmedia/images/instagram.jpg' alt='Instagram' width='64' height='64'>
            </a>
        <?php endif; ?>
    </p>

    <?php endif; ?>

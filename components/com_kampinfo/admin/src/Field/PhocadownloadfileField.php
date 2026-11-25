<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Field;

\defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
use Joomla\Database\DatabaseInterface;

class PhocadownloadfileField extends ListField {

    /**
     * The form field type.
     * 
     * @var    string
     */
    protected $type = 'Phocadownloadfile';

    protected function getOptions(): array {
        return array_merge(
            parent::getOptions(),
            $this->getOptionsFromDatabase()
        );
    }

    private function getOptionsFromDatabase(): array {
        $db     = $this->getDatabase();
        $query  = $db->getQuery(true)
            ->select([
                $db->quoteName('a.id'),
                $db->quoteName('a.title'),
                $db->quoteName('a.alias'),
                $db->quoteName('a.catid'),
                $db->quoteName('c.alias', 'category_alias'),
            ])
            ->from($db->quoteName('#__phocadownload', 'a'))
            ->join('LEFT',
                $db->quoteName('#__phocadownload_categories', 'c')
                . ' ON ' . $db->quoteName('c.id') . ' = ' . $db->quoteName('a.catid')
            )
            ->where($db->quoteName('a.published') . ' = 1')
            ->order($db->quoteName('a.title') . ' ASC');

        $db->setQuery($query);
        $items = $db->loadObjectList();

        $options = [];
        if ($items) {
            foreach ($items as $item) {
                $itemId = (int) $item->id;
                $catId = (int) $item->catid;
                $options[] = HTMLHelper::_(
                    'select.option',
                    "index.php?option=com_phocadownload&view=file&id={$itemId}:{$item->alias}&catid={$catId}:{$item->category_alias}",
                    "{$item->title} (ID: {$itemId})"
                );
            }
        }
        return $options;
    }

    protected function getInput(): string {
        $html = parent::getInput();

        if (!empty($this->value)) {
            $editUrl = Route::_('index.php?option=com_phocadownload&task=phocadownloadfile.edit&id=' . (int) $this->value);
            $html .= '<div class="mt-2">'
                   . '<a href="' . $editUrl . '" target="_blank" class="btn btn-sm btn-secondary">'
                   . '<span class="icon-edit" aria-hidden="true"></span> Edit File in Phoca Download'
                   . '</a></div>';
        }

        return $html;
    }
}
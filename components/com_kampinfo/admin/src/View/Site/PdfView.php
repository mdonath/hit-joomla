<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\View\Site;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\MVC\View\AbstractView;

use HITScoutingNL\Component\KampInfo\Administrator\Helper\PDF\PlaatsPdf;
use HITScoutingNL\Component\KampInfo\Administrator\Model\SiteModel;

class PdfView extends AbstractView {

    public function display($tpl = null): void {
        /** @var SiteModel $model */
        $model      = $this->getModel();
        $plaats     = $model->getItem();
        $kampen     = $model->getKampen($plaats);

        $pdf = new PlaatsPdf($plaats, $kampen);
        $pdf->printPlaats();
        $pdf->Output($pdf->getPdfOutputName(), 'D');
    }

}

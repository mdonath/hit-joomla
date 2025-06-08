<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Helper\PDF;

\defined('_JEXEC') or die('Restricted Access');

require_once dirname(__FILE__).'/../../../libraries/fpdf/fpdf.php';


/**
 * Schrijft een fragment HTML naar de PDF.
 */
class PdfHtml extends \FPDF {

    private $skipFirstP;
    private $B;
    private $I;
    private $U;
    private $HREF;
    private $fontlist;
    private $issetfont;
    private $issetcolor;

    public function __construct($orientation = 'P', $unit = 'mm', $format = 'A4') {
        parent::__construct($orientation, $unit, $format);

        $this->B = 0;
        $this->I = 0;
        $this->U = 0;
        $this->HREF = '';
        $this->fontlist = ['arial', 'times', 'courier', 'helvetica', 'symbol'];
        $this->issetfont = false;
        $this->issetcolor = false;
    }

    protected function WriteHTML($html) {
        $this->skipFirstP = true;
        // HTML parser
        $html = strip_tags($html, '<b><u><i><a><img><p><br><strong><em><font><tr><blockquote>');
        $html = str_replace('\n', ' ', $html);
        $tags = preg_split('/<(.*)>/U', $html, -1, PREG_SPLIT_DELIM_CAPTURE);

        foreach ($tags as $index => $tag) {
            if ($index % 2 == 0) {
                // Text
                if ($this->HREF) {
                    $this->PutLink($tag, $this->HREF);
                } else {
                    $this->Write(5, stripslashes(self::txtentities($tag)));
                }
            } else {
                // Tag
                if ($tag[0] == '/') {
                    $this->CloseTag(strtoupper(substr($tag, 1)));
                } else {
                    // Extract attributes
                    $attributes = explode(' ', $tag);
                    $tagName = strtoupper(array_shift($attributes));
                    $attr = [];
                    foreach ($attributes as $v) {
                        if (preg_match('/([^=]*)=["\']?([^"\']*)/', $v, $matches)) {
                            $attr[strtoupper($matches[1])] = $matches[2];
                        }
                    }
                    $this->OpenTag($tagName, $attr);
                }
            }
        }
    }

    /**
     * Opening tag
     */
    private function OpenTag($tag, $attr) {
        switch($tag) {
            case 'STRONG':
                $this->SetStyle('B', true);
                break;
            case 'EM':
                $this->SetStyle('I', true);
                break;
            case 'B':
            case 'I':
            case 'U':
                $this->SetStyle($tag, true);
                break;
            case 'A':
                $this->HREF = $attr['HREF'];
                break;
            case 'IMG':
                if (isset($attr['SRC']) && (isset($attr['WIDTH']) || isset($attr['HEIGHT']))) {
                    if (!isset($attr['WIDTH'])) {
                        $attr['WIDTH'] = 0;
                    }
                    if (!isset($attr['HEIGHT'])) {
                        $attr['HEIGHT'] = 0;
                    }
                    $this->Image($attr['SRC'], $this->GetX(), $this->GetY(), self::px2mm($attr['WIDTH']), self::px2mm($attr['HEIGHT']));
                }
                break;
            case 'TR':
            case 'BLOCKQUOTE':
            case 'BR':
                $this->Ln(5);
                break;
            case 'P':
                if ($this->skipFirstP) {
                    $this->skipFirstP = false;
                } else {
                    $this->Ln(10);
                }
                break;
            case 'FONT':
                if (isset($attr['COLOR']) && $attr['COLOR'] != '') {
                    $color = self::hex2dec($attr['COLOR']);
                    $this->SetTextColor($color['R'], $color['G'], $color['B']);
                    $this->issetcolor = true;
                }
                if (isset($attr['FACE']) && in_array(strtolower($attr['FACE']), $this->fontlist)) {
                    $this->SetFont(strtolower($attr['FACE']));
                    $this->issetfont = true;
                }
                break;
        }
    }

    /**
     * Closing tag.
     */
    private function CloseTag($tag) {
        if ($tag == 'STRONG') {
            $tag = 'B';
        }
        if ($tag == 'EM') {
            $tag = 'I';
        }
        if ($tag == 'B' || $tag == 'I' || $tag == 'U') {
            $this->SetStyle($tag, false);
        }
        if ($tag == 'A') {
            $this->HREF = '';
        }
        if ($tag == 'FONT') {
            if ($this->issetcolor == true) {
                $this->SetTextColor(0);
            }
            if ($this->issetfont) {
                $this->SetFont('arial');
                $this->issetfont = false;
            }
        }
    }

    /**
     * Modify style and select corresponding font.
     */
    private function SetStyle($tag, $enable) {
        $this->$tag += ($enable ? 1 : -1);
        $style = '';
        foreach (['B', 'I', 'U'] as $s) {
            if ($this->$s > 0) {
                $style .= $s;
            }
        }
        $this->SetFont('', $style);
    }

    /**
     * Put a hyperlink.
     */
    private function PutLink(string $txt, string $link) {
        $this->SetTextColor(0, 0, 255);
        $this->SetStyle('U', true);
        $this->Write(5, $txt, $link);
        $this->SetStyle('U', false);
        $this->SetTextColor(0);
    }

    /**
     * Returns an associative array (keys: R,G,B) from
     * a hex html code (e.g. #3FE5AA)
     */
    private static function hex2dec(string $color = '#000000') {
        return [
            'R' => hexdec(substr($color, 1, 2)),
            'G' => hexdec(substr($color, 3, 2)),
            'B' => hexdec(substr($color, 5, 2)),
        ];
    }

    /**
     * Converteert van pixel -> millimeter @ 72 dpi.
     */
    private static function px2mm($px){
        return $px * 25.4 / 72;
    }

    /**
     * Converteert HTML-entities weer terug naar normale karakters.
     */
    private static function txtentities(string $html){
        $trans = get_html_translation_table(HTML_ENTITIES);
        $trans = array_flip($trans);
        return strtr($html, $trans);
    }

}

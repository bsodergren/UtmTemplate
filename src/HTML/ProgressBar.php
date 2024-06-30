<?php

namespace UTMTemplate\HTML;

use UTMTemplate\Render;

class ProgressBar
{
    public static $progressDir = 'elements/html/ProgressBar';

    public $percentDone = 0;
    public $pbid;
    public $pbarid;
    public $tbarid;
    public $textid;
    public $decimals = 1;
    public $width = '200px';
    public $height = '1.3em';
    public $textColor;
    public $bgBefore = '#43b6df';
    public $bgAfter = '#FEFEFE';
    public $direction = 'up';
    public $rounded = true;

    public function __construct($percentDone = 0)
    {
        $this->pbid = 'pb';
        $this->pbarid = 'progress-bar';
        $this->tbarid = 'transparent-bar';
        $this->textid = 'pb_text';
        $this->percentDone = $percentDone;
    }

    public function setStyle($options = [])
    {
        if (\count($options) > 0) {
            $vars = get_class_vars(__CLASS__);

            foreach ($options as $key => $value) {
                if (\array_key_exists($key, $vars)) {
                    $this->{$key} = $value;
                }
            }
        }
    }

    public function render()
    {
        // print ($GLOBALS['CONTENT']);
        // $GLOBALS['CONTENT'] = '';
        echo '<div style="width: '.$this->width.';"></div>';
        echo $this->getContent();
        $this->flush();
        $this->setProgressBarProgress(0);
    }

    public function getContent()
    {
        $this->percentDone = (float) $this->percentDone;
        $percentDone = number_format($this->percentDone, $this->decimals, '.', '').'%';

        $params['textid'] = $this->textid;
        $params['pbid'] = $this->pbid;
        $params['pbarid'] = $this->pbarid;
        $params['tbarid'] = $this->tbarid;
        $params['percentDone'] = $percentDone;
        $params['style'] = $this->style();

        return Render::return(self::$progressDir.'/progressbar', $params);
    }

    public function setProgressBarProgress($percentDone, $text = '')
    {
        $this->percentDone = $percentDone;
        $text = $text ?: number_format($this->percentDone, $this->decimals, '.', '').'%';

        $params['pbarid'] = $this->pbarid;
        $params['pbid'] = $this->pbid;
        $params['percentDone'] = $percentDone;
        $params['pbDone'] = 'block';
        $params['tbarid'] = $this->tbarid;
        $params['textId'] = $this->textid;

        if (100 == $percentDone) {
            $params['pbDone'] = 'none';
        } else {
            $params['percentLeft'] = (100 - $percentDone);
        }
        if ($text) {
            $params['pbText'] = htmlspecialchars($text);
        }
        echo Render::return(self::$progressDir.'/progressjs', $params);
        $this->flush();
    }

    public function flush()
    {
        echo str_pad('', (int) \ini_get('output_buffering'))."\n";
        ob_end_flush();
        flush();
    }

    public function style()
    {
        $params =
              [
                  'width' => $this->width,
                  'height' => $this->height,
                  'bgBefore' => $this->bgBefore,
                  'bgAfter' => $this->bgAfter,
              ];

        if (true === $this->rounded) {
            $barParams['barRadius'] = '10px';
            $params['bar'] = Render::return(self::$progressDir.'/Bar/bar', $barParams);
            $params['bar_before'] = Render::return(self::$progressDir.'/Bar/before', $barParams);
            $params['bar_after'] = Render::return(self::$progressDir.'/Bar/after', $barParams);
        }

        return Render::return(self::$progressDir.'/progressStyle', $params);
    }
}

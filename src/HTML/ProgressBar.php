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
    public $params = [];
    public $decimals = 1;
    public $width = '200px';

    public $height = '1.3em';

    public $borderRadius = '5px';
    public $textColor;
    public $bgBefore = '#43b6df';

    public $bgAfter = '#FEFEFE';
    public $direction = 'up';
    public $rounded = true;

    public function __construct($barId = 'progress-bar', $percentDone = 0)
    {
        $this->pbid =  $barId.'_pb';
        $this->pbarid = $barId;
        $this->tbarid =  $barId.'_transparent-bar';
        $this->textid =  $barId.'_text';

        $this->params['textid'] = $this->textid;
        $this->params['pbid'] = $this->pbid;
        $this->params['pbarid'] = $this->pbarid;
        $this->params['tbarid'] = $this->tbarid;

        $this->percentDone = $percentDone;
    }

    public function setStyle($options = [])
    {
        if (\count($options) > 0) {
            $vars = get_class_vars(__CLASS__);

            foreach ($options as $key => $value) {
                if (\array_key_exists($key, $vars)) {
                    $this->{$key} = $value;
                    $this->params[$key] = $value;
                }
            }
        }
    }

    public function render()
    {
        // print ($GLOBALS['CONTENT']);
        // $GLOBALS['CONTENT'] = '';
        // echo '<div style="width: '.$this->width.';"></div>';
        echo $this->getContent();
        $this->flush();
        $this->setProgressBarProgress(0);
    }

    public function getContent()
    {
        $this->percentDone = (float) $this->percentDone;
        $percentDone = number_format($this->percentDone, $this->decimals, '.', '').'%';

        $params = $this->params;
        $params['percentDone'] = $percentDone;
        $params['style'] = $this->style();

        return Render::return(self::$progressDir.'/progressbar', $params);
    }

    public function setProgressBarProgress($percentDone, $text = '')
    {
        $this->percentDone = $percentDone;
        $text = $text ?: number_format($this->percentDone, $this->decimals, '.', '').'%';

        $params = $this->params;
        $params['percentDone'] = $percentDone;
        $params['pbDone'] = 'block';

        if (100 == $percentDone) {
            $params['pbDone'] = 'none';
        } else {
            $params['percentLeft'] = (100 - $percentDone);
        }
        // if ($text) {
        $params['pbText'] = htmlspecialchars($text);
        // }
        echo Render::return(self::$progressDir.'/progressjs', $params);
        $this->flush();
    }

    public function flush()
    {
        echo str_pad('', (int) \ini_get('output_buffering'))."\n";
        @ob_end_flush();
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
            $barParams['barRadius'] = $this->borderRadius;
            $params['bar'] = Render::return(self::$progressDir.'/Bar/bar', $barParams);
            $params['bar_before'] = Render::return(self::$progressDir.'/Bar/before', $barParams);
            $params['bar_after'] = Render::return(self::$progressDir.'/Bar/after', $barParams);
        }

        return Render::return(self::$progressDir.'/progressStyle', $params);
    }
}

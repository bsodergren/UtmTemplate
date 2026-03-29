<?php

namespace UTMTemplate\Bundle\GridView\Buttons;

use UTMTemplate\Bundle\GridView\Buttons\Button;

/**
 * create a button that will have Edit as a label and will have a link to the
 * given url.
 */
class EditButton extends Button
{
    public $label = 'Edit';
    public $css = 'btn btn-success btn-xs';

    public function __construct($url, $config = [])
    {
        $this->url = $url;
        parent::__construct($config);
    }
}

<?php

use UTMTemplate\Render;
use UTMTemplate\Template;

require '.config.php';

// (new Template())->getRegisteredCallbacks();
Template::getRegisteredCallbacks();
$text = 'Fasdfasdfasdfasd';

$html = Render::html('base/header', []);
$html .= Render::html('pages/Home/main', ['BODY' => $text]);
$html .= Render::html('base/footer', []);

echo $html;

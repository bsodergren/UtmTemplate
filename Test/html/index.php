<?php
use UTM\Utm;
use UTMTemplate\Render;
use UTMTemplate\Template;
use UTMTemplate\UtmDevice;
use UTMTemplate\HTML\Elements;

require '.config.php';

$text = "Fasdfasdfasdfasd";
echo Render::html('base/header', []);

echo Elements::javaRefresh(__URL_HOME__, 3,"Redirecting to " . __URL_HOME__);

echo Render::html('base/footer', []);

<?php

use UTMTemplate\Bundle\GridView\Table;
use UTMTemplate\Render;
use UTMTemplate\Template;

require '.config.php';

// (new Template())->getRegisteredCallbacks();
Template::getRegisteredCallbacks();
$text = 'Fasdfasdfasdfasd';

$dataSource = array();
for($i=0; $i<10; $i++) {
	$dataSource[] = array(
	            	'uniqid'=>uniqid(), 
	            	'loop_iterator'=>$i.' times',
	            	'date'=>date('Y-m-d'),
	            	'total'=>rand(1,25)
	           	);		        
}

$table = new Table($dataSource);



$html = Render::html('base/header', []);
$html .= Render::html('pages/Home/main', ['BODY' => $table]);
$html .= Render::html('base/footer', []);

echo $html;

<?php

use UTMTemplate\Bundle\GridView\Buttons\DeleteButton;
use UTMTemplate\Bundle\GridView\Buttons\EditButton;
use UTMTemplate\Bundle\GridView\Buttons\ViewButton;
use UTMTemplate\Bundle\GridView\Columns\ButtonColumn;
use UTMTemplate\Bundle\GridView\Columns\DateTimeColumn;
// use UTMTemplate\Bundle\GridView\Columns\LinkColumn;
use UTMTemplate\Bundle\GridView\Columns\CheckBoxColumn;
use UTMTemplate\Bundle\GridView\Table;

use UTMTemplate\Render;
use UTMTemplate\Template;

require '.config.php';

// (new Template())->getRegisteredCallbacks();
Template::getRegisteredCallbacks();
$text = 'Fasdfasdfasdfasd';
$dataSource = [];
for ($i = 0; $i < 10; ++$i) {
    $dataSource[] = [
        'uniqid' => uniqid(),
        'loop_iterator' => $i.' times',
        'date' => date('Y-m-d'),
        'total' => rand(1, 25),
    ];
}

$table = new Table($dataSource);
$table->addColumn(
        new CheckBoxColumn([
            'name' => 'checkbox',
            'checked' => function ($data) {
                return $data['loop_iterator'] % 2 === 0;
            },
        ])
    )
    ->addColumn(
        new DateTimeColumn([
            'name' => 'date',
            'visible' => (date('j') % 2),
        ])
    )
;

// or via array access
// $table['loop_iterator'] = array('name'=>'loop_iterator');

$table[] = new ButtonColumn(
    [
        'buttons' => [
            new ViewButton('/view'),
            new EditButton('/edit'),
            new DeleteButton('/delete'),
        ],
    ]);

// echo (string) $table; // renders table
$html = Render::html('base/header', []);
$html .= Render::html('pages/Home/main', ['BODY' => $table]);
$html .= Render::html('base/footer', []);

echo $html;

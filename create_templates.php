<?php
require __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

@mkdir(__DIR__ . '/storage/app/templates', 0777, true);

$templates = [
    'ACTA_INICIO_BASICA.docx' => 'Plantilla ACTA INICIO BASICA - sustituye por la real',
    'ACTA_INICIO_ESPECIALIZADA.docx' => 'Plantilla ACTA INICIO ESPECIALIZADA - sustituye por la real',
];

foreach ($templates as $name => $text) {
    $phpWord = new PhpWord();
    $section = $phpWord->addSection();
    $section->addText($text);
    $writer = IOFactory::createWriter($phpWord, 'Word2007');
    $writer->save(__DIR__ . '/storage/app/templates/' . $name);
    echo "Created: storage/app/templates/{$name}\n";
}

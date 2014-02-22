<?php

require_once "svglib/svglib.php";
$svg = SVGDocument::getInstance( ); #start a svgDocument using default (minimal) svg document
$svg->setTitle("Simple example"); #define the title

$style = new SVGStyle();
$style->setFill( '#f2f2f2' );
$style->setStroke( '#e1a100' );
$style->setStrokeWidth( 2 );

$width = 1000;
$lines = 8;

for ($i = 0; $i < $lines; $i++)
{
	$line = SVGLine::getInstance( 5 + ($i * 20), 5, 200 + ($i * 20), 200, 'line' . $i, $style );
	$svg->addShape( $line );
}

$svg->output(); #output to browser, with header

?>
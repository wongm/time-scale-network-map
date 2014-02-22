<?php

require_once "svglib/svglib.php";
$svg = SVGDocument::getInstance( ); #start a svgDocument using default (minimal) svg document
$svg->setTitle("Simple example"); #define the title

$style = new SVGStyle();
$style->setFill( '#f2f2f2' );
$style->setStroke( '#e1a100' );
$style->setStrokeWidth( 2 );

$line = SVGLine::getInstance( 5, 5, 200, 200, 'line1', $style );
$svg->addShape( $line );
$svg->output(); #output to browser, with header

?>
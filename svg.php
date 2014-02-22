<?php

$radius = 500;
$totalLines = 5;

$degreesDifference = (360 / $totalLines);
$origin = $radius + 10;
$width = ($radius * 2) + 10;

require_once "svglib/svglib.php";
$svg = SVGDocument::getInstance( ); #start a svgDocument using default (minimal) svg document
$svg->setTitle("Simple example"); #define the title
$svg->setWidth( $width . "px" );
$svg->setHeight( $width . "px" );

$style = new SVGStyle();
$style->setFill( '#f2f2f2' );
$style->setStroke( '#e1a100' );

for ($currentLine = 0; $currentLine < $totalLines; $currentLine++)
{	
	$radians = deg2rad(($currentLine * $degreesDifference) - 90);
	$xOuter = ($radius * cos($radians)) + $origin;
	$yOuter = ($radius * sin($radians)) + $origin;
	$style->setStrokeWidth( $currentLine * 3 );
	
	$line = SVGLine::getInstance( $origin, $origin, $xOuter, $yOuter, 'line' . $currentLine, $style );
	$svg->addShape( $line );
}

$svg->output(); #output to browser, with header

?>
<?php

$radius = 500;

require_once('data.php');

$totalLines = sizeof($lines);

$degreesDifference = (360 / $totalLines);
$origin = $radius + 10;
$width = ($radius * 2) + 20;

require_once "svglib/svglib.php";
$svg = SVGDocument::getInstance( ); #start a svgDocument using default (minimal) svg document
$svg->setTitle("Simple example"); #define the title
$svg->setWidth( $width . "px" );
$svg->setHeight( $width . "px" );

$style = new SVGStyle();
$style->setFill( '#f2f2f2' );
$style->setStroke( '#e1a100' );

// convert arrival times into minutes since first station
foreach (array_keys($lines) as $lineName)
{
	$currentLine = $lines[$lineName];	
	$stationIndex = 0;
	
	foreach(array_keys($currentLine) as $stationName)
	{
		// convert time to minutes
		$stationMinutes = (strtotime($currentLine[$stationName]) / 60);
		
		// need to know time at first station
		if ($stationIndex == 0)
		{
			$lineStartMinutes = $stationMinutes;
		}
		
		// calculate minutes since first station
		$lines[$lineName][$stationName] = ($stationMinutes - $lineStartMinutes);
		$stationIndex++;
	}
}

$maximumTime = 0;

// find line with the longest trravel time
foreach ($lines as $line)
{
	if (max($line) > $maximumTime)
	{
		$maximumTime = max($line);
	}
}

$currentIndex = 0;

//draw everything
foreach ($lines as $line)
{
	// determine how long the line should be
	$endTime = max($line);
	$scalingFactor = ($endTime / $maximumTime);
	$lineLength = ($radius * $scalingFactor);
	
	$radians = deg2rad(($currentIndex * $degreesDifference) - 90);
	$xOuter = ($lineLength * cos($radians)) + $origin;
	$yOuter = ($lineLength * sin($radians)) + $origin;
	$style->setStrokeWidth( $currentIndex * 1 );
	
	$line = SVGLine::getInstance( $origin, $origin, $xOuter, $yOuter, 'line' . $currentIndex, $style );
	$svg->addShape( $line );
	$currentIndex++;
}

$svg->output(); #output to browser, with header

?>
<?php

$radius = 1500;
$degreesOffset = 45;
$stationRadius = 3;

require_once('data.php');

$totalLines = sizeof($lines);

$degreesDifference = (360 / $totalLines);
$origin = $radius + 10;
$canvas = ($radius * 2) + 20;

require_once "svglib/svglib.php";
$svg = SVGDocument::getInstance( ); #start a svgDocument using default (minimal) svg document
$svg->setTitle("Simple example"); #define the title
$svg->setWidth( $canvas . "px" );
$svg->setHeight( $canvas . "px" );

$style = new SVGStyle();
$style->setFill( '#f2f2f2' );
$style->setStroke( '#e1a100' );

// convert arrival times into minutes since first station
foreach (array_keys($lines) as $lineName)
{
	$line = $lines[$lineName];
	$stationIndex = 0;
	
	foreach(array_keys($line) as $stationName)
	{
		// convert time to minutes
		$stationMinutes = (strtotime($line[$stationName]) / 60);
		
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

// draw all the lines
foreach ($lines as $line)
{
	// determine scaling and direction of this line
	$lineEndTime = max($line);
	$lineScaling = ($lineEndTime / $maximumTime);
	$lineLength = ($radius * $lineScaling);
	$radians = deg2rad(($currentIndex * $degreesDifference) - $degreesOffset);
	
	// determine end of the line
	$xOuter = ($lineLength * cos($radians)) + $origin;
	$yOuter = ($lineLength * sin($radians)) + $origin;
	$style->setStrokeWidth( 1 );
	
	$lineShape = SVGLine::getInstance( $origin, $origin, $xOuter, $yOuter, 'line' . $currentIndex, $style );
	$svg->addShape( $lineShape );
	
	
	$stationIndex = 0;
	
	// add each station along the line
	foreach(array_keys($line) as $stationName)
	{
		$stationScaling = ($line[$stationName] / $lineEndTime);
		$stationDistance = ($lineLength * $stationScaling);
		
		$xStation = ($stationDistance * cos($radians)) + $origin;
		$yStation = ($stationDistance * sin($radians)) + $origin;
		$stationShape = SVGCircle::getInstance( $xStation, $yStation, $stationRadius, 'station' . $currentIndex . $stationIndex, $style );
		$svg->addShape( $stationShape );
		
		$stationLabelText = "$stationName ($line[$stationName])";
		
		$stationLabel = SVGText::getInstance( $xStation, $yStation, 'stationLabel' . $currentIndex . $stationIndex, $stationLabelText, $style );
		$svg->addShape( $stationLabel );
		
		$stationIndex++;
	}
	
	$currentIndex++;
}

$radiusMinutes = 10;
$radiusStyle = new SVGStyle();
$radiusStyle->setStroke( 'grey' );
$radiusStyle->setFill( 'none' );
$radiusStyle->setStrokeWidth( 1 );

// finally, add some circles
while ($radiusMinutes < $maximumTime)
{
	$radiusScaling = ($radiusMinutes / $maximumTime);
	$radiusDistance = ($radius * $radiusScaling);
	
	$radiusCircle = SVGCircle::getInstance( $origin, $origin, $radiusDistance, 'radius' . $currentRadius, $radiusStyle );
	$svg->addShape( $radiusCircle );
	$radiusMinutes += 10;
}

$svg->output(); #output to browser, with header

?>
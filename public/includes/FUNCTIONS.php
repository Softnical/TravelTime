<?php

// calculates distance between two latlng
function distanceLatLng( $latlng1, $latlng2)
{
	// "haversine" formula

	$lat1 = deg2rad( $latlng1[0] );
	$lng1 = deg2rad( $latlng1[1] );
	
	$lat2 = deg2rad( $latlng2[0] );
	$lng2 = deg2rad( $latlng2[1] );
 
	$distance = 2*6371000*asin(sqrt(pow(sin(($lat2-$lat1)/2),2)+cos($lat1)*cos($lat2)*pow(sin(($lng2-$lng1)/2),2)));
	
	// return distance in meter
	return $distance;
}
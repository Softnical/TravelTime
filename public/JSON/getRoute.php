<?php

require_once dirname(__FILE__).'/../includes/DB.php';

require_once dirname(__FILE__).'/../includes/GET_POST.php';

require_once dirname(__FILE__).'/../includes/FUNCTIONS.php';

$result = $RESULT;

// expected the user location and destination
if( vGET(["userlat", "userlng", "userdest"]) )
{
	// assign
	$userDest = (int)$_GET['userdest'];
	$userLat = (float)$_GET['userlat'];
	$userLng = (float)$_GET['userlng'];

	if( !empty($userLat) && !empty($userLng) && !empty($userDest) )
	{
		// new DB obj
		$connection = new DB;

		// routes with user destination 
		$userRoutes = $connection->query("SELECT route FROM {$TABLE['route']} WHERE route LIKE CONCAT('%',:dest,'%')", 
			[
				":dest" => $userDest
			]);

		// data is $userRoutes['data']
		$routes = $userRoutes['data'];

		// stations from routes
		foreach ( $routes as $key => $value) 
		{
			$stationsArray[] = explode('-', $value['route'] );
		}

		// stations to single array
		foreach ( $stationsArray as $index => $stationArray ) 
		{
			foreach ( $stationArray as $id => $value ) 
			{
				$stationFromRoutes[] = $value;
			}
			
		}

		// remove duplicate stations
		$stationFromRoutes = array_unique($stationFromRoutes);

		// array sort
		if( asort($stationFromRoutes) )
			$stationQuery = "SELECT id, lat, lng FROM {$TABLE['station']} WHERE id BETWEEN :first AND :last";
		else
			$stationQuery = "SELECT id, lat, lng FROM {$TABLE['station']}";

		// all stations with lat lng
		$stationsData =  $connection->query( $stationQuery, 
			[ 
				":first" => reset($stationFromRoutes),
				":last" => end($stationFromRoutes)
			]);

		$allStations = $stationsData['data'];

		foreach ($allStations as $key => $value) 
		{
			$stationLatLng[ $value['id'] ] = [ $value['lat'], $value['lng'] ];
		}

		// max possible distance
		$minDistance = 10000000;

		// iteration to find nearest station
		foreach ( $stationFromRoutes as $key => $station ) 
		{
			$distance = distanceLatLng( [ $userLat, $userLng ], $stationLatLng[$station] );

			if( $minDistance > $distance )
			{
				$nearestStation = $station;
				$minDistance = $distance;
			}
		}

		$DATA = 
		[
			"station" => $nearestStation,
			"distance" => $minDistance
		];

		$result = ["status" => "OK", "message" => "no error occured.", "data" => $DATA ];
	}
}

// JSON output
echo json_encode($result);

?>
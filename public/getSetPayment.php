<?php

require_once dirname(__FILE__).'/includes/DB.php';

require_once dirname(__FILE__).'/includes/GET_POST.php';

require_once dirname(__FILE__).'/includes/FUNCTIONS.php';

$result = $RESULT;

if( vGET( ["cid", "busid"] ) )
{

	$custId = (int)$_GET['cid'];
	$busId = (int)$_GET['busid'];

	$connection = new DB;

	// finding bus current location
	$locationData = $connection->query("SELECT lat, lng FROM {$TABLE['bus']} WHERE id = :busid", 
		[
			":busid" => $busId
		]);

	$currentLocation = $locationData['data'][0];

	// check user is already in vehicle
	$userData = $connection->query("SELECT slat, slng FROM {$TABLE['record']} WHERE fare = 0 AND busid = :busid AND cid = :cid", 
		[
			":busid" => $busId,
			":cid" => $custId
		]);

	// starting lat lng
	if( !isset( $userData['data'][0] ) )
	{
		$sLatLng = false;
	}
	else
	{
		$sLatLng = $userData['data'][0];
	}

	
	// if yes
	if( !$sLatLng )
	{
		$insertQuery = $connection->query("INSERT INTO {$TABLE['record']}(slat, slng, busid, cid) VALUES( :slat, :slng, :busid, :cid )",
			[
				":slat" => (float)$currentLocation['lat'],
				":slng" => (float)$currentLocation['lng'],
				":busid" => $busId,
				":cid" => $custId
			]);

		if( $insertQuery['status'] == "OK" )
		{
			$result =
			[
				"status" => "UPDATE",
				"message" => "no error occured.",
				"data" => []
			];
		}
	}
	else
	{
		// find distance form GMAP api

		$urlJSON = "https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial";
		$urlJSON .= "&origins={$sLatLng['slat']},{$sLatLng['slng']}";
		$urlJSON .= "&destinations={$currentLocation['lat']},{$currentLocation['lng']}";
		$urlJSON .= "&key={$KEY['MatrixAPI']}";

		$loadJSON = file_get_contents( $urlJSON );
		$JSONresult = json_decode( $loadJSON, true );

		$distance = 0;
		
		if( $JSONresult['status'] == 'OK')
		{
			$distance = (float)($JSONresult['rows'][0]['elements'][0]['distance']['value']);
			$duration = (float)$JSONresult['rows'][0]['elements'][0]['duration']['value'];
		}


		// distance calculation using haversine
		if( !$distance )
		{
			$distance = distanceLatLng(
				[ (float)$sLatLng['slat'], (float)$sLatLng['slng'] ],
				[ (float)$currentLocation['lat'], (float)$currentLocation['lng'] ]);
			$duration = $distance/6600;
		}

		// fare calculation , 5 /KM
		$fare = $distance/1000 * 5;

		if( $fare < 10 )
			$fare = 10;

		// update DB
		$updateQuery = $connection->query("UPDATE {$TABLE['record']} SET elat = :elat, elng = :elng, fare = :fare WHERE busid = :busid AND cid = :cid",
			[
				":elat" => $currentLocation['lat'],
				":elng" => $currentLocation['lng'],
				":fare" => $fare,
				":busid" => $busId,
				":cid" =>  $custId
			]);

		if( $updateQuery['status'] == "OK" )
		{
			$result =
			[
				"status" => "OK",
				"message" => "no error occured.",
				"data" => [
					"fare" => $fare,
					"distance" => $distance,
					"duration" => $duration
				]
			];
		}
	}
}
echo json_encode($result);

?>
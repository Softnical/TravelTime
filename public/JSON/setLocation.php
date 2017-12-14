<?php

require_once dirname(__FILE__).'/../includes/DB.php';

require_once dirname(__FILE__).'/../includes/GET_POST.php';

// expected params lat, lng, busid and service key

// default result
$result = $RESULT;

// checking if required params available
if ( vGET(["busid", "buslat", "buslng"]) )
{
	// assigning them to variables
	$busId = (int)$_GET['busid'];
	$busLat = (float)$_GET['buslat'];
	$busLng = (float)$_GET['buslng'];
		
	// new DB obj
	$location=new DB;

	// update query
	$result = $location->query("UPDATE {$TABLE['bus']} SET lat = :lat , lng = :lng WHERE id = :id",
		[
			":lat" => $busLat,
			":lng" => $busLng,
			":id" => $busId
		]);
}

// json output
echo json_encode($result);	

?>
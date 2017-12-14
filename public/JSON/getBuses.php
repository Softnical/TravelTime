<?php

require_once dirname(__FILE__).'/../includes/DB.php';

require_once dirname(__FILE__).'/../includes/GET_POST.php';

$result = $RESULT;

$connection = new DB;

if( vGET( ["all"] ) )
{
	$result = $connection->query("SELECT busno, drivername, lat, lng FROM {$TABLE['bus']}");
}
else if( vGET( ["oid"], false) )
{
	$result = $connection->query("SELECT busno, drivername, lat, lng FROM {$TABLE['bus']} WHERE ownerId = :oid",
		[
			":oid" => (int)$_GET['oid']
		]);
}

echo json_encode($result);

?>

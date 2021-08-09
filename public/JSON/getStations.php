<?php

require_once dirname(__FILE__).'../includes/DB.php';

require_once dirname(__FILE__).'../includes/GET_POST.php';

// default result
$result = $RESULT;

if(  vGET(["skey"]) )
{
	// new DB obj
	$connction = new DB;
	
	// DB query
	$result = $connction->query("SELECT id, name, lat, lng FROM {$TABLE['station']}");
}

// json output
echo json_encode($result);

$connction = null;

?>
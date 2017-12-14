<?php

require_once dirname(__FILE__).'/../../CONFIG.php';

// modify as your need
$keyParam = "skey";

// to validate GET/POST contains non empty required param
function vGET( $getArray, $keyRequire = true )
{
	global $keyParam, $KEY;

	foreach ( $getArray as $getKey => $getValue ) 
	{
		if( !isset($_GET[$getValue]) && empty($_GET[$getValue]) )
			return false;
	}

	if ( !$keyRequire )
	{
		return true;
	}
	elseif (isset($_GET[$keyParam]) && $_GET[$keyParam] === $KEY['service'] ) 
	{
		return true;
	}
	return false;
}

function vPOST( $postArray, $keyRequire = true )
{
	global $keyParam, $KEY;

	foreach ( $postArray as $postKey => $postValue ) 
	{
		if( !isset($_POST[$postValue]) && empty($_POST[$postValue]) )
			return false;
	}

	if ( !$keyRequire )
	{
		return true;
	}
	elseif (isset($_POST[$keyParam]) && $_POST[$keyParam] === $KEY['service'] ) 
	{
		return true;
	}
	return false;
}

?>
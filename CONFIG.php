<?php

// CONFIG.php, feel free to modify

// site details
$SITE=
[
	"name"=>"TravelTime",
	"url"=>"https://tt.softnical.com",
	"address"=>"Kaski, Pokhara",
	"email"=>"contact@softnical.com",
	"year"=>"2017"
]; 

// mysql database connction details
$SQLDB=
[
	"host" => "localhost",
	"user" => "root",
	"password" => "",
	"name" => ""
];

/* your key list.
 *
 * google map key is visible to html page
 *
 * app key determines valid request
 * such as update bus location
 * it is private
*/
$KEY=
[
	"service" => "YOUR_PRIVATE_SERVICE_KEY",
	"MatrixAPI" => "GMAP_DISTANCE_MATRIX_KEY"
];

// various table names

$TABLE=
[
	"bus"=>"buses",
	"owner"=>"owners",
	"station"=>"stations",
	"record"=>"records",
	"route"=>"routes"
];

$RESULT=
[
	"status" => "ERROR",
	"message" => "can not perform given action."
];

?>
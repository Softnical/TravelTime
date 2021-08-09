<?php

require_once dirname(__FILE__).'/includes/DB.php';

require_once dirname(__FILE__).'/includes/GET_POST.php';

if( vGET(["busid"], false) )
{
	$bID = (int)$_GET['busid'];

	// define
	$Year = $YMonth = $YMDay = false;

	if( vGET(["yy"], false) )
	{
		$Year = true;
		$yy = (int)$_GET['yy'];

		if( vGET(["mm"], false) )
		{
			$YMonth = true;
			$mm = (int)$_GET['mm'];

			if( vGET(["dd"], false) )
			{
				$YMDay = true;
				$dd = (int)$_GET['dd'];
			}
		}
	}

	// database OBJ
	$connection = new DB;

	$recordsBind = [];

	// if no date given, fetch all records
	$recordsQuery = "SELECT id, fare FROM {$TABLE['record']} WHERE busid = :id";

	$recordsBind[":id"] = $bID;

	// if specified date given
	if( $Year )
	{
		$recordsQuery .= " AND YEAR(time) = :year";
		$recordsBind[":year"] = $yy;
	}
	if( $YMonth )
	{
		$recordsQuery .= " AND MONTH(time) = :month";
		$recordsBind[":month"] = $mm;
	}
	if( $YMDay )
	{
		$recordsQuery .= " AND DAY(time) = :day";
		$recordsBind[":day"] = $dd;
	}


	$recordsResult = $connection->query( $recordsQuery, $recordsBind );


	// useful data
	$records = $recordsResult['data'];

	// display this records
	echo displayRecords( $records );

}

function displayRecords( $records )
{
	$loop = 1;
	$totalFare = 0;

	$data = "<table id='fareTable'> <tr> <th>SN.</th> <th>Id</th> <th>Fare (Rs)</th> </tr>";

	foreach ( $records as $key => $value )
	{
		$data .= "<tr> <td>{$loop}</td> <td>{$value['id']}</td> <td>{$value['fare']}</td> </tr>";

		$totalFare += (float)$value['fare'];
		$loop++;
	}

	$data .= "<tr> <td></td> <td>Total:</td> <td>$totalFare</td> </tr> </table>";
	return $data;
}




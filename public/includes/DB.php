<?php

require_once dirname(__FILE__).'/../../CONFIG.php';


// refer CONFIG.PHP for $SQLDB

define("DBUSER", $SQLDB['user']);
define("DBPASS", $SQLDB['password']);
define("DBDSN","mysql:host={$SQLDB['host']};dbname={$SQLDB['name']}");

class DB
{
	protected $dbConn;

	public function __construct()
	{
		try
		{
			$this->dbConn = new PDO(DBDSN, DBUSER, DBPASS);
		}
		catch(PDOException $e)
		{
			die( $e->getMessage() );
		}
	}
	public function pdoOBJ()
	{
		return $this->dbConn;
	}

	public function query( $query, $bindValues=[] )
	{

		if ( $stmt = $this->dbConn->prepare($query) )
		{
			foreach ( $bindValues as $key => $value )
			{
				if( !$stmt->bindValue($key, $value) )
				{
					// bind error
					return [ "status" => "ERROR", "message" => "bind error occured." ];
				}
			}

			if ( $stmt->execute() )
			{
				if(( $result = $stmt->fetchAll(PDO::FETCH_ASSOC)) !== FALSE )
					{
						return [ 
							"status" => "OK", 
							"message" => "no errors occured.", 
							"data" => $result
						];

					}
			}
		}

		// if query fails, send error message : execute or fetch error
		return [ "status" => "ERROR", "message" => "some errors occured." ];
	}
}

?>

<?php

class DatabaseIO {

	var $connection = NULL;
    var $db = NULL;
    var $collection = NULL;

	// ---------------------------------------------------------------------------------------
	// Initializes the model, connects to DB
		
    function __construct()
    {
		// Setup
		require "../../secure/iobridge.php";
		
		$connectionString = "mongodb://$setServer";
		
		$this->connection = new Mongo($connectionString);
		$this->db = $this->connection->$setDB;
		$this->collection = $this->db->$setCollection;
    }
	
	// ---------------------------------------------------------------------------------------
	// Formats and inserts data

	function formatAndInsert($data)
	{
		if (!isset($data['module']['status']))
		{
			return date("Ymd His") . " \n" . "Empty data received; not inserted.";
		}
		else
		{
	
			if (isset($_GET['method']))
			{
				$method = $_GET['method'];
			}
			else
			{
				$method = "na";
			}

			// Module
			$data['module']['_method'] = $method; 
			$data['module']['_unixtime'] = time(); 

			// Channel 1
			$data['module']['channels'][0]['_function'] = "temperature"; 
			$data['module']['channels'][0]['_unit'] = "C"; 

			// Channel 2
			$data['module']['channels'][1]['_function'] = "humidity"; 
			$data['module']['channels'][1]['_unit'] = "percent"; 

			// Channel 3
			$data['module']['channels'][2]['_function'] = "light"; 
			$data['module']['channels'][2]['_unit'] = "RAW"; 

			// Channel 4
			unset($data['module']['channels'][3]); // Channel not in use
			
			// Insert data
			$ret = $this->insertDocument($data);
			$message = date("Ymd His") . " \n" . print_r ($ret, TRUE);
		
			return $message;
		}
	}
	
	// ---------------------------------------------------------------------------------------
	// Inserts a document
	// Tämän kutsuminen saman connecytionin aikana samalla data-arraylla aiheuttaa virheen: mongodb luulee että yrität tallentaa samaa tiedostoa uudelleen

	function insertDocument($document, $id = FALSE)
	{
		// Jos ID annettu, käytetään sitä. Muuten MongoDB luo ID:n automaattisesti.
		if ($id)
		{
			$document['_id'] = $id;
		}
	
		$options['safe'] = TRUE;
		
		try {
			$ret = "Database insert succeeded. \n " . print_r ($this->collection->insert($document, $options), TRUE);
		}
		catch(MongoCursorException $e) {
			$ret = "Database insert failed. \n " . $e;
		}
		
		return $ret;
	}

	// ---------------------------------------------------------------------------------------
	// Returns all data

	function returnArray()
	{
		$ret = NULL;

		$cursor = $this->collection->find(); // Mongo cursor object
		
		$ret = iterator_to_array($cursor);
			
		return $ret;
	}

	// ---------------------------------------------------------------------------------------
	// Returns N most recent, sorted

	function returnSelected($n = 100, $method = "cron10", $sort = "DESC", $skip = 0)
	{
		$ret = NULL;
		
		// LIMIT
		$defaultLimit = 800;
		if ($n > $defaultLimit || $n < 1 || !is_numeric($n))
		{
			$n = $defaultLimit;
		}
		
		// SORT
		if ("DESC" == $sort)
		{
			$sort = -1;
		}
		else
		{
			$sort = 1;
		}
		
		// WHERE
		$js = "function() {
		  return this.module._method == '$method';
		}";
//		echo $js; exit(); // DEBUG

		$cursor = $this->collection->find(array('$where' => $js))->skip($skip)->limit($n); // Mongo cursor object
		
		// ASC/1 returns ascending array, but starts from the first matching data
		$cursor = $cursor->sort(array('module._unixtime' => $sort));
		
		$ret = iterator_to_array($cursor);
			
		return $ret;
	}

	// ---------------------------------------------------------------------------------------
	// test
	function test()
	{
		echo "<p>Now testing... done.</p>";
	}
	
	// ---------------------------------------------------------------------------------------

}
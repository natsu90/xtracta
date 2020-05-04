<?php

namespace Xtracta;

use Ahc\Cli\Input\Command;
use Ahc\Cli\IO\Interactor;
use SleekDB\SleekDB as DB;

class SeedCommand extends Command {

	public function __construct()
    {
    	parent::__construct('seed', 'Importing invoice data');

		$this->invoice = DB::store('invoice', __DIR__ . '/../db');
    }

    public function interact(Interactor $io)
    {

    }

    public function execute()
    {
    	$this->invoice->delete();

    	//Open file
		$handler = fopen ("invoice.txt", "r");

		//Read file, line to line
		while (!feof ($handler)) {
				//Read one line
				$line = fgets($handler, 4096);

				// fix json data with double quote instead of single qoute
				$line = str_replace("'", "\"", $line);
				// insert data
				$this->invoice->insert(json_decode($line, true));
		}
		//Close handler
		fclose ($handler);

		echo "Imported invoice data!\n";
    }
}
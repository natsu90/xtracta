<?php

namespace Xtracta;

use Ahc\Cli\Input\Command;
use Ahc\Cli\IO\Interactor;
use SleekDB\SleekDB as DB;

class ImportCommand extends Command {

	public function __construct()
    {
    	parent::__construct('import', 'Import supplier data');

        $this->supplier = DB::store('supplier', __DIR__ . '/../db');
    }

    public function interact(Interactor $io)
    {
    	
    }

    public function execute()
    {
    	$this->supplier->delete();

    	//Open file
		$handler = fopen ("suppliernames.txt", "r");

		//Read file, line to line
		while (!feof ($handler)) {
				//Read one line
				$line = fgets($handler, 4096);
				// assuming it is a valid csv
                $supplier_data = explode(',', $line);
                $supplier_id = trim($supplier_data[0]);
                $supplier_name = trim($supplier_data[1]);
                // insert data to db
                $this->supplier->insert(compact('supplier_id', 'supplier_name'));
		}
		//Close handler
		fclose ($handler);

        echo "Suppliers imported!\n";
    }
}
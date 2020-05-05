<?php

namespace Xtracta;

use Ahc\Cli\Input\Command;
use Ahc\Cli\IO\Interactor;
use SleekDB\SleekDB as DB;
use Faker\Factory;
use SQLite3;

class ImportCommand extends Command {

	public function __construct()
    {
    	parent::__construct('import', 'Import supplier data');

        $this->db = new SQLite3(__DIR__ . '/../db.sqlite');

        $this->argument('[total]', 'Total dummy data to test');
    }

    public function interact(Interactor $io)
    {
    	if (!$this->total)
            $this->set('total', 0);
    }

    public function execute($total)
    {
        error_reporting(E_ALL ^ E_WARNING);
        // truncate data
        $this->db->exec('DROP TABLE supplier');
        $this->db->exec('CREATE TABLE IF NOT EXISTS supplier (id INTEGER PRIMARY KEY AUTOINCREMENT, supplier_id INTEGER, supplier_name VARCHAR(255))');

        //Open file
        $handler = fopen ("suppliernames.txt", "r");
		//Read file, line to line
        $start_read = false; $insert_query = "BEGIN TRANSACTION;";
		while (!feof ($handler)) {
				//Read one line
				$line = fgets($handler, 4096);
                // skip first line
                if (!$start_read) {
                    $start_read = true;
                    continue;
                }
				// assuming it is a valid csv
                $supplier_data = explode(',', $line);
                $supplier_id = trim($supplier_data[0]);
                $supplier_name = trim($supplier_data[1]);
                // prepare data to insert
                $insert_query .= 'INSERT INTO supplier (supplier_id, supplier_name) VALUES ('.$supplier_id.', "'.$supplier_name.'");';
		}
		//Close handler
		fclose ($handler);
        $insert_query .= "COMMIT;";
        // insert data to db
        $this->db->exec($insert_query);

        echo "Suppliers imported!\n";

        // skip if argument not specified
        if ($total == 0 ) return;
        echo "This will take awhile..\n";
        // set memory limit otherwise will get fatal error memory exhausted when data is million
        ini_set('memory_limit', '1024M'); 
        // prepare fake data to insert
        $faker = Factory::create(); $insert_query = "BEGIN TRANSACTION;";
        for($i = 0; $i < $total; $i++) {

            $supplier_id = $faker->unique()->randomNumber(8);
            $supplier_name = $faker->unique()->company;
            $insert_query .= 'INSERT INTO supplier (supplier_id, supplier_name) VALUES ('.$supplier_id.', "'.$supplier_name.'");';
        }
        // insert data
        $insert_query .= "COMMIT;";
        $this->db->exec($insert_query);

        echo "More suppliers imported!\n";
    }

    public function __destruct()
    {
        // close the connection
        $this->db->close();
    }
}
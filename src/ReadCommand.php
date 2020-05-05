<?php

namespace Xtracta;

use Ahc\Cli\Input\Command;
use Ahc\Cli\IO\Interactor;
use SleekDB\SleekDB as DB;
use Ahc\Cli\Output\Writer;
use Ahc\Cli\Output\Color;
use SQLite3;

class ReadCommand extends Command {

	public function __construct()
    {
    	parent::__construct('read', 'Reading invoice data');

		$this->invoice = DB::store('invoice', __DIR__ . '/../db');

		$this->db = new SQLite3(__DIR__ . '/../db.sqlite');
    }

    public function interact(Interactor $io)
    {

    }

    public function execute()
    {
    	error_reporting(E_ALL ^ E_WARNING);

    	$color = new Color;
    	// check if invoice not empty
    	if (!$this->invoice->fetch()) {
    		echo $color->error("No invoice data!\n"); exit;
    	}
    	// check if supplier not empty
    	$rows = $this->db->query("SELECT COUNT(*) as count FROM supplier");
    	if (!$rows) {
    		echo $color->error("No supplier data!\n"); exit;
    	}

		$row = $rows->fetchArray();
		echo "Total suppliers: ". $row['count'] ."\n";
    	// fetch data
    	$invoice_data = $this->invoice
    		->orderBy('asc', 'line_id')
    		->fetch();

    	// group by page_id & line_id & cspan_id & rspan_id
    	// order by pos_id asc
    	$temp_data = [];
    	foreach($invoice_data as $data) {

    		$key = 'page-'. $data['page_id'] .'-line-'. $data['line_id'] .'-cspan-'. $data['cspan_id'] .'-rspan-'. $data['rspan_id'];

    		if (!isset($temp_data[$key])) $temp_data[$key] = [];
			
			$temp_data[$key][] = $data;
			// sort asc pos_id
			usort($temp_data[$key], function($a, $b) {
			    return $a['pos_id'] - $b['pos_id'];
			});
    	}
    	// feed data to display in table
    	// prepare array data for where_in query
    	$table_data = []; $words_data = [];
    	foreach ($temp_data as $data) {
    		// merge words
    		$words = implode(' ', array_column($data, 'word'));

    		$table_data[] = ['words' => $words];

    		$words_data[] = $words;
    	}

    	// fetch query where_in
    	$res = $this->db->query('SELECT * FROM supplier WHERE supplier_name IN ("'.implode('","', $words_data).'")');
    	// prepare supplier data to display in table
    	$suppliers_data = [];
    	while($data = $res->fetchArray()) {

		    $suppliers_data[] = ['suppliers' => $data['supplier_name']];
		}

    	$writer = new Writer;

    	$writer->table($table_data);
    	$writer->table($suppliers_data);

    	echo $color->info("Reading completed!\n");
    }

    public function __destruct()
    {
        // close the connection
        $this->db->close();
    }
}
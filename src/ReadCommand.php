<?php

namespace Xtracta;

use Ahc\Cli\Input\Command;
use Ahc\Cli\IO\Interactor;
use SleekDB\SleekDB as DB;
use Ahc\Cli\Output\Writer;
use Ahc\Cli\Output\Color;

class ReadCommand extends Command {

	public function __construct()
    {
    	parent::__construct('read', 'Reading invoice data');

		$this->invoice = DB::store('invoice', __DIR__ . '/../db');

		$this->supplier = DB::store('supplier', __DIR__ . '/../db');
    }

    public function interact(Interactor $io)
    {

    }

    public function execute()
    {
    	$color = new Color;
    	// check if invoice not empty
    	if (!$this->invoice->fetch()) {
    		echo $color->error("No invoice data!\n"); exit;
    	}
    	// check if supplier not empty
    	if (!$this->supplier->fetch()) {
    		echo $color->error("No supplier data!\n"); exit;
    	}
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
    	$suppliers = $this->supplier->in('supplier_name', $words_data)->fetch();
    	// prepare supplier data to display in table
    	$suppliers_data = [];
    	foreach($suppliers as $data) {
    		$suppliers_data[] = ['suppliers' => $data['supplier_name']];
    	}

    	$writer = new Writer;

    	$writer->table($table_data);
    	$writer->table($suppliers_data);

    	echo $color->info("Reading completed!\n");
    }
}
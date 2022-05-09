<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\Datatable;
class DatatableController extends Controller
{

	public function get_orders(Request $request){

		$table = 'datatables_demo';
 
		// Table's primary key
		$primaryKey = 'id';
		 
		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes
		$columns = array(
		    array( 'db' => 'first_name', 'dt' => 0 ),
		    array( 'db' => 'last_name',  'dt' => 1 ),
		    array( 'db' => 'position',   'dt' => 2 ),
		    array( 'db' => 'office',     'dt' => 3 ),
		    array(
		        'db'        => 'start_date',
		        'dt'        => 4,
		        'formatter' => function( $d, $row ) {
		            return date( 'jS M y', strtotime($d));
		        }
		    ),
		    array(
		        'db'        => 'salary',
		        'dt'        => 5,
		        'formatter' => function( $d, $row ) {
		            return '$'.number_format($d);
		        }
		    )
		);
		 
		// SQL server connection information
		$sql_details = array(
		    'user' => '',
		    'pass' => '',
		    'db'   => '',
		    'host' => ''
		);
		
		$data['data'] = Datatable::simple( $request->all(), $sql_details, $table, $primaryKey, $columns );
		$data['draw'] = $request->draw;
		$data['recordsFiltered'] = 57;
		$data['recordsTotal'] = 57;
		$data['postData'] = $request->all();
		return json_encode($data);
	}

}
<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;

class MasterController extends Controller

{

	public function quickLinks(){

		return view('dashboard.master.quick-links');

	} 

	public function roles(){

		$data['roles'] = \App\Role::where('is_active',1)->get();

		return view('dashboard.master.roles',$data);

	} 



	public function addRole(Request $request){

		$response = array();

		$validator = \Validator::make($request->all(),

			array(

				'role' =>'required|unique:roles,role',

			)

		);



		if($validator->fails())

		{

			$response['flag'] = false;

			$response['errors'] = $validator->getMessageBag();

		}else{

			$role =  new \App\Role();

			$role->role = $request->role;

			if($role->save()){

				$response['flag'] = true;

				$response['message'] = "Role Added Successfully";

			}else{

				$response['flag'] = false;

				$response['error'] = "Something Went Wrong";

			}

		}

		return response()->json($response);

	}

	public function getEditRole($id){

		$data['role'] = \App\Role::where('id',$id)->where('is_active',1)->first();

		return view('dashboard.master.edit-role',$data);

	} 

	public function updateRole(Request $request){

		$response = array();

		$validator = \Validator::make($request->all(),

			array(

				'role' =>'required|unique:roles,role,'.$request->id,

			)

		);

		if($validator->fails())

		{

			$response['flag'] = false;

			$response['errors'] = $validator->getMessageBag();

		}else{

			$role =  \App\Role::where('id',$request->id)->where('is_active',1)->first();

			if(is_null($role)){

				$response['flag'] = false;

				$response['error'] = "Role Not found";

			}else{

				$role->role = $request->role;

				if($role->save()){

					$response['flag'] = true;

					$response['message'] = "Role Updated Successfully";

				}else{

					$response['flag'] = false;

					$response['error'] = "Something Went Wrong";

				}

			}

		}

		return response()->json($response);

	}



	public function deleteRole($id){

		$response = array();

		$role = \App\Role::where('id',$id)->where('is_active',1)->first();

		if(is_null($role)){

			$response['flag'] = false;

			$response['message'] = "Role Not Found";

		}else{

			$role->is_active = 0;

			if($role->save()){

				$response['flag'] = true;

				$response['message'] = "Role Deleted";

			}else{

				$response['flag'] = false;

				$response['message'] = "Failed to delete";

			}

		}

		return response()->json($response);

	}









	public function getRolePermissions($id){

		$data = array();

		$role = \App\Role::where('is_active',1)->where('id',$id)->first();

		if(is_null($role)){

			return redirect('/user/roles')->with('error','Role Not Found');

		}else{

			$data['role'] = $role;

			$data['modules'] = \App\Module::where('is_active',1)->with('sub_modules')->get();

			return view('dashboard.master.assign-permission',$data);

		}

	} 



	public function updateRolePermissions($role_id,$module_id,$sub_module_id){

		$response = array();

		$permission = \App\RoleModuleAssociation::where('role_id',$role_id)->where('module_id',$module_id)->where('sub_module_id',$sub_module_id)->first();

		if(is_null($permission)){

			$permission = new \App\RoleModuleAssociation();

			$permission->role_id = $role_id;

			$permission->module_id = $module_id;

			$permission->sub_module_id = $sub_module_id;

			if($permission->save()){

				$response['flag'] = true;

			}else{

				$response['flag'] = true;

				$response['message'] = "Something Went Wrong";

			}



		}else{

			if($permission->delete()){

				$response['flag'] = true;

			}else{

				$response['flag'] = true;

				$response['message'] = "Something Went Wrong";

			}

		}

		return response()->json($response);

	} 







	public function sessions(){

		$data['sessions'] = \App\Session::where('is_active',1)->get();

		return view('dashboard.master.sessions',$data);

	} 



	public function addSession(Request $request){

		$response = array();

		$validator = \Validator::make($request->all(),

			array(

				'session' =>'required|unique:sessions,session',

			)

		);



		if($validator->fails())

		{

			$response['flag'] = false;

			$response['errors'] = $validator->getMessageBag();

		}else{

			$session =  new \App\Session();

			$session->session = $request->session;

			if($session->save()){

				$response['flag'] = true;

				$response['message'] = "Session Added Successfully";

			}else{

				$response['flag'] = false;

				$response['error'] = "Something Went Wrong";

			}

		}

		return response()->json($response);

	}

	public function getEditSession($id){

		$data['session'] = \App\Session::where('id',$id)->where('is_active',1)->first();

		return view('dashboard.master.edit-session',$data);

	} 

	public function updateSession(Request $request){

		$response = array();

		$validator = \Validator::make($request->all(),

			array(

				'session' =>'required|unique:sessions,session,'.$request->id,

			)

		);

		if($validator->fails())

		{

			$response['flag'] = false;

			$response['errors'] = $validator->getMessageBag();

		}else{

			$session =  \App\Session::where('id',$request->id)->where('is_active',1)->first();

			if(is_null($session)){

				$response['flag'] = false;

				$response['error'] = "Session Not found";

			}else{

				$session->session = $request->session;

				if($session->save()){

					$response['flag'] = true;

					$response['message'] = "Session Updated Successfully";

				}else{

					$response['flag'] = false;

					$response['error'] = "Something Went Wrong";

				}

			}

		}

		return response()->json($response);

	}



	public function deleteSession($id){

		$response = array();

		$session = \App\Session::where('id',$id)->where('is_active',1)->first();

		if(is_null($session)){

			$response['flag'] = false;

			$response['message'] = "Session Not Found";

		}else{

			$session->is_active = 0;

			if($session->save()){

				$response['flag'] = true;

				$response['message'] = "session Deleted";

			}else{

				$response['flag'] = false;

				$response['message'] = "Failed to delete";

			}

		}

		return response()->json($response);

	}





	public function banks(){

		$data['banks'] = \App\Bank::where('is_active',1)->get();

		return view('dashboard.master.banks',$data);

	} 



	public function addBank(Request $request){

		$response = array();

		$validator = \Validator::make($request->all(),

			array(

				'name' =>'required|unique:banks,name',

			)

		);



		if($validator->fails())

		{

			$response['flag'] = false;

			$response['errors'] = $validator->getMessageBag();

		}else{

			$bank =  new \App\Bank();

			$bank->name = $request->name;

			if($bank->save()){

				$response['flag'] = true;

				$response['message'] = "Bank Added Successfully";

			}else{

				$response['flag'] = false;

				$response['error'] = "Something Went Wrong";

			}

		}

		return response()->json($response);

	}

	public function getEditBank($id){

		$data['bank'] = \App\Bank::where('id',$id)->where('is_active',1)->first();

		return view('dashboard.master.edit-bank',$data);

	} 

	public function updateBank(Request $request){

		$response = array();

		$validator = \Validator::make($request->all(),

			array(

				'name' =>'required|unique:banks,name,'.$request->id,

			)

		);

		if($validator->fails())

		{

			$response['flag'] = false;

			$response['errors'] = $validator->getMessageBag();

		}else{

			$bank =  \App\Bank::where('id',$request->id)->where('is_active',1)->first();

			if(is_null($bank)){

				$response['flag'] = false;

				$response['error'] = "Bank Not found";

			}else{

				$bank->name = $request->name;

				if($bank->save()){

					$response['flag'] = true;

					$response['message'] = "Bank Updated Successfully";

				}else{

					$response['flag'] = false;

					$response['error'] = "Something Went Wrong";

				}

			}

		}

		return response()->json($response);

	}



	public function deleteBank($id){

		$response = array();

		$bank = \App\Bank::where('id',$id)->where('is_active',1)->first();

		if(is_null($bank)){

			$response['flag'] = false;

			$response['message'] = "Bank Not Found";

		}else{

			$bank->is_active = 0;

			if($bank->save()){

				$response['flag'] = true;

				$response['message'] = "Bank Deleted";

			}else{

				$response['flag'] = false;

				$response['message'] = "Failed to delete";

			}

		}

		return response()->json($response);

	}









	public function bank_accounts(){

		$data = array();

		$data['banks'] = \App\Bank::where('is_active',1)->get();

		$data['bank_accounts'] = \App\BankAccount::where('is_active',1)->get();

		return view('dashboard.master.bank_accounts',$data);

	} 



	public function addBankAccount(Request $request){

		$response = array();

		$validator = \Validator::make($request->all(),

			array(

				'bank_id' =>'required',

				'account_number' =>'required|unique:bank_accounts,account_number',

			)

		);



		if($validator->fails())

		{

			$response['flag'] = false;

			$response['errors'] = $validator->getMessageBag();

		}else{

			$bank_account =  new \App\BankAccount();

			$bank_account->bank_id = $request->bank_id;

			$bank_account->bank_branch = $request->bank_branch;

			$bank_account->account_number = $request->account_number;

			$bank_account->ifsc_code = $request->ifsc_code;

			$bank_account->account_holder_name = $request->account_holder_name;

			if($bank_account->save()){

				$response['flag'] = true;

				$response['message'] = "Bank Account Added Successfully";

			}else{

				$response['flag'] = false;

				$response['error'] = "Something Went Wrong";

			}

		}

		return response()->json($response);

	}

	public function getEditBankAccount($id){

		$data = array();

		$data['banks'] = \App\Bank::where('is_active',1)->get();

		$data['bank_account'] = \App\BankAccount::where('id',$id)->where('is_active',1)->first();

		return view('dashboard.master.edit-bank-account',$data);

	} 

	public function updateBankAccount(Request $request){

		$response = array();

		$validator = \Validator::make($request->all(),

			array(

				'bank_id' =>'required',

				'account_number' =>'required|unique:bank_accounts,account_number,'.$request->id,

			)

		);

		if($validator->fails())

		{

			$response['flag'] = false;

			$response['errors'] = $validator->getMessageBag();

		}else{

			$bank_account =  \App\BankAccount::where('id',$request->id)->where('is_active',1)->first();

			if(is_null($bank_account)){

				$response['flag'] = false;

				$response['error'] = "Bank Account Not found";

			}else{

				$bank_account->bank_id = $request->bank_id;

				$bank_account->bank_branch = $request->bank_branch;

				$bank_account->account_number = $request->account_number;

				$bank_account->ifsc_code = $request->ifsc_code;

				$bank_account->account_holder_name = $request->account_holder_name;

				if($bank_account->save()){

					$response['flag'] = true;

					$response['message'] = "Bank Account Updated Successfully";

				}else{

					$response['flag'] = false;

					$response['error'] = "Something Went Wrong";

				}

			}

		}

		return response()->json($response);

	}



	public function deleteBankAccount($id){

		$response = array();

		$bank_account = \App\BankAccount::where('id',$id)->where('is_active',1)->first();

		if(is_null($bank_account)){

			$response['flag'] = false;

			$response['message'] = "Bank Account Not Found";

		}else{

			$bank_account->is_active = 0;

			if($bank_account->save()){

				$response['flag'] = true;

				$response['message'] = "Bank Account Deleted";

			}else{

				$response['flag'] = false;

				$response['message'] = "Failed to delete";

			}

		}

		return response()->json($response);

	}









	public function accounts(){

		$data = array();

		$data['accounts'] = \App\Account::where('is_active',1)->get();

		return view('dashboard.master.accounts',$data);

	} 



	public function addAccount(Request $request){

		$response = array();

		$validator = \Validator::make($request->all(),

			array(

				'name' =>'required|unique:accounts,name',

				'phone' =>'required|unique:accounts,phone',

				'email' =>'required|unique:accounts,email',

				'address' =>'required',

				'gst_no' =>'required|unique:accounts,gst_no',



			)

		);



		if($validator->fails())

		{

			$response['flag'] = false;

			$response['errors'] = $validator->getMessageBag();

		}else{

			$account =  new \App\Account();

			$account->name = $request->name;

			$account->phone = $request->phone;

			$account->email = $request->email;

			$account->address = $request->address;

			$account->gst_no = $request->gst_no;

			if($account->save()){

				$account->unique_id = 'ACNT'.$account->id;

				$account->save();

				$response['flag'] = true;

				$response['message'] = "Account Added Successfully";

			}else{

				$response['flag'] = false;

				$response['error'] = "Something Went Wrong";

			}

		}

		return response()->json($response);

	}

	public function getEditAccount($id){

		$data['account'] = \App\Account::where('id',$id)->where('is_active',1)->first();

		return view('dashboard.master.edit-account',$data);

	} 

	public function updateAccount(Request $request){

		$response = array();

		$validator = \Validator::make($request->all(),

			array(

				'name' =>'required|unique:accounts,name,'.$request->id,

				'phone' =>'required|unique:accounts,phone,'.$request->id,

				'email' =>'required|unique:accounts,email,'.$request->id,

				'address' =>'required',

				'gst_no' =>'required|unique:accounts,gst_no,'.$request->id,

			)

		);

		if($validator->fails())

		{

			$response['flag'] = false;

			$response['errors'] = $validator->getMessageBag();

		}else{

			$account =  \App\Account::where('id',$request->id)->where('is_active',1)->first();

			if(is_null($account)){

				$response['flag'] = false;

				$response['error'] = "Account Not found";

			}else{

				$account->name = $request->name;

				$account->phone = $request->phone;

				$account->email = $request->email;

				$account->address = $request->address;

				$account->gst_no = $request->gst_no;

				if($account->save()){

					$response['flag'] = true;

					$response['message'] = "Account Updated Successfully";

				}else{

					$response['flag'] = false;

					$response['error'] = "Something Went Wrong";

				}

			}

		}

		return response()->json($response);

	}



	public function deleteAccount($id){

		$response = array();

		$account = \App\Account::where('id',$id)->where('is_active',1)->first();

		if(is_null($account)){

			$response['flag'] = false;

			$response['message'] = "Account Not Found";

		}else{

			$account->is_active = 0;

			if($account->save()){

				$response['flag'] = true;

				$response['message'] = "Account Deleted";

			}else{

				$response['flag'] = false;

				$response['message'] = "Failed to delete";

			}

		}

		return response()->json($response);

	}





	public function transporters(){

		$data['transporters'] = \App\Transporter::where('is_active',1)->get();

		return view('dashboard.master.transporters',$data);

	} 



	public function addTransporter(Request $request){

		// dd($request->all());

		$response = array();

		$validator = \Validator::make($request->all(),

			array(

				'name' =>'required|unique:accounts,name',

				'phone' =>'unique:accounts,phone',

				'email' =>'unique:accounts,email',

				// 'address' =>'required',

				// 'gst_no' =>'required|unique:accounts,gst_no',



			)

		);



		if($validator->fails())

		{

			$response['flag'] = false;

			$response['errors'] = $validator->getMessageBag();

		}else{

			$transporter =  new \App\Transporter();

			$transporter->name = $request->name;

			if($request->hindi_name){

				$transporter->hindi_name = $request->hindi_name;

			}else{

				$transporter->name = $request->name;



			}

			$transporter->phone = $request->phone;

			$transporter->email = $request->email;

			$destination_rates = array();

			$i = 0;

			foreach ($request->destination as $key => $value) {

				$arr = array();

				$arr['destination'] = $request->destination[$i];

				$arr['rate'] = $request->rate[$i];

				array_push($destination_rates,$arr);

				$i++;

			}

			$transporter->destination_rates = json_encode($destination_rates);

			if($transporter->save()){

				$response['flag'] = true;

				$response['message'] = "Transporter Added Successfully";

			}else{

				$response['flag'] = false;

				$response['error'] = "Something Went Wrong";

			}

		}

		return response()->json($response);

	}

	public function getEditTransporter($id){

		$data['transporter'] = \App\Transporter::where('id',$id)->where('is_active',1)->first();

		return view('dashboard.master.edit-transporter',$data);

	} 

	public function updateTransporter(Request $request){

		$response = array();

		$validator = \Validator::make($request->all(),

			array(

				'name' =>'required|unique:accounts,name,'.$request->id,

				'phone' =>'unique:accounts,phone,'.$request->id,

				'email' =>'unique:accounts,email,'.$request->id,

			)

		);

		if($validator->fails())

		{

			$response['flag'] = false;

			$response['errors'] = $validator->getMessageBag();

		}else{

			$transporter =  \App\Transporter::where('id',$request->id)->where('is_active',1)->first();

			if(is_null($transporter)){

				$response['flag'] = false;

				$response['error'] = "Transporter Not found";

			}else{

				$transporter->name = $request->name;

				if($request->hindi_name){

					$transporter->hindi_name = $request->hindi_name;

				}else{

					$transporter->hindi_name = $request->name;



				}



				$transporter->phone = $request->phone;

				$transporter->email = $request->email;

				if($request->destination){



					$destination_rate = array();

					$i = 0;

					foreach ($request->destination as $key => $value) {

						$arr = array();

						$arr['destination'] = $request->destination[$i];

						$arr['rate'] = $request->rate[$i];

						array_push($destination_rate,$arr);

						$i++;

					}

					$transporter->destination_rates = json_encode($destination_rate);

				}else{

					$transporter->destination_rates = null;

				}

				if($transporter->save()){

					$response['flag'] = true;

					$response['message'] = "Transporter Updated Successfully";

				}else{

					$response['flag'] = false;

					$response['error'] = "Something Went Wrong";

				}

			}

		}

		return response()->json($response);

	}



	public function deleteTransporter($id){

		$response = array();

		$transporter = \App\Transporter::where('id',$id)->where('is_active',1)->first();

		if(is_null($transporter)){

			$response['flag'] = false;

			$response['message'] = "Transporter Not Found";

		}else{

			$transporter->is_active = 0;

			if($transporter->save()){

				$response['flag'] = true;

				$response['message'] = "Transporter Deleted";

			}else{

				$response['flag'] = false;

				$response['message'] = "Failed to delete";

			}

		}

		return response()->json($response);

	}



/*

 * Function to get Freight List

 */

public function freightList()

{

	$data = array();

	$data['freightList'] = \App\FreightList::all();

	// $data['users'] = \App\User::where('role_id',3)->get();

	return view('dashboard.master.freight-list',$data);

}

/*

 * Function to add Freight

 */

public function addFreight(Request $request)

{

	$response = array();

	$validator = \Validator::make($request->all(),

		array(

			'destination' =>'required',

			'freight' =>'required',

		)

	);



	if($validator->fails())

	{

		$response['flag'] = false;

		$response['errors'] = $validator->getMessageBag();

	}else{

		$freight =  new \App\FreightList();

		$freight->destination = $request->destination;

		$freight->district = $request->district;

		$freight->distance = $request->distance;

		$freight->freight = $request->freight;

		if($freight->save()){

			$response['flag'] = true;

			$response['message'] = "Freight Added Successfully";

		}else{

			$response['flag'] = false;

			$response['error'] = "Something Went Wrong";

		}

	}

	return response()->json($response);

}





public function getEditFreight($id){

	$data = array();

	$data['freight'] = \App\FreightList::where('id',$id)->first();

	return view('dashboard.master.edit-freight',$data);

} 

public function updateFreight(Request $request){

	$response = array();

	$validator = \Validator::make($request->all(),

		array(

			'destination' =>'required',

			'freight' =>'required',

		)

	);

	if($validator->fails())

	{

		$response['flag'] = false;

		$response['errors'] = $validator->getMessageBag();

	}else{

		$freight =  \App\FreightList::where('id',$request->id)->first();

		if(is_null($freight)){

			$response['flag'] = false;

			$response['error'] = "Freight Not found";

		}else{

			$freight->destination = $request->destination;

			$freight->district = $request->district;

			$freight->distance = $request->distance;

			$freight->freight = $request->freight;

			if($freight->save()){

				$response['flag'] = true;

				$response['message'] = "Freight Updated Successfully";

			}else{

				$response['flag'] = false;

				$response['error'] = "Something Went Wrong";

			}

		}

	}

	return response()->json($response);

}



public function deleteFreight($id){

	$response = array();

	$freight = \App\FreightList::where('id',$id)->where('is_active',1)->first();

	if(is_null($freight)){

		$response['flag'] = false;

		$response['message'] = "Freight Not Found";

	}else{

		$freight->is_active = 0;

		if($freight->save()){

			$response['flag'] = true;

			$response['message'] = "Freight Deleted";

		}else{

			$response['flag'] = false;

			$response['message'] = "Failed to delete";

		}

	}

	return response()->json($response);

}



public function warehouses(){

	$data = array();

	$data['warehouses'] = \App\Warehouse::where('is_active',1)->get();

	$data['users'] = \App\User::where('role_id',12)->get();

	return view('dashboard.master.warehouses',$data);

} 





public function addWarehouse(Request $request){

	$response = array();



	$validator = \Validator::make($request->all(),

		array(

			'name' =>'required|unique:warehouses,name',

			'user_id' =>'required',

			'location' =>'required',

		)

	);



	if($validator->fails())

	{

		$response['flag'] = false;

		$response['errors'] = $validator->getMessageBag();

	}else{

		$warehouse =  new \App\Warehouse();

		$warehouse->location = $request->location;

		$warehouse->user_id = $request->user_id;

		$warehouse->name = $request->name;

		if($request->hindi_name){

			$warehouse->hindi_name = $request->hindi_name;

		}

		else{

			$warehouse->hindi_name = $request->name;

		}

		$warehouse->lat = $request->lat;

		$warehouse->lng = $request->lng;

		if($warehouse->save()){

			$warehouse->unique_id = 'WH00'.$warehouse->id;

			$warehouse->save();

			$response['flag'] = true;

			$response['message'] = "Warehouse Added Successfully";

		}else{

			$response['flag'] = false;

			$response['error'] = "Something Went Wrong";

		}

	}

	return response()->json($response);

}

public function getEditWarehouse($id){

	$data = array();

	$data['warehouse'] = \App\Warehouse::where('id',$id)->where('is_active',1)->first();

	$data['users'] = \App\User::where('role_id',3)->get();

	return view('dashboard.master.edit-warehouse',$data);

} 

public function updateWarehouse(Request $request){

	$response = array();

	$validator = \Validator::make($request->all(),

		array(

			'name' =>'required|unique:warehouses,name,'.$request->id,

			'user_id' =>'required',

			'location' =>'required',



		)

	);

	if($validator->fails())

	{

		$response['flag'] = false;

		$response['errors'] = $validator->getMessageBag();

	}else{

		$warehouse =  \App\Warehouse::where('id',$request->id)->where('is_active',1)->first();

		if(is_null($warehouse)){

			$response['flag'] = false;

			$response['error'] = "Warehouse Not found";

		}else{

			$warehouse->name = $request->name;

			$warehouse->user_id = $request->user_id;

			if($request->hindi_name){

				$warehouse->hindi_name = $request->hindi_name;

			}

			else{

				$warehouse->hindi_name = $request->name;

			}

			$warehouse->location = $request->location;

			$warehouse->lat = $request->lat;

			$warehouse->lng = $request->lng;

			if($warehouse->save()){

				$response['flag'] = true;

				$response['message'] = "Warehouse Updated Successfully";

			}else{

				$response['flag'] = false;

				$response['error'] = "Something Went Wrong";

			}

		}

	}

	return response()->json($response);

}



public function deleteWarehouse($id){

	$response = array();

	$warehouse = \App\Warehouse::where('id',$id)->where('is_active',1)->first();

	if(is_null($warehouse)){

		$response['flag'] = false;

		$response['message'] = "Warehouse Not Found";

	}else{

		$warehouse->is_active = 0;

		if($warehouse->save()){

			$response['flag'] = true;

			$response['message'] = "Warehouse Deleted";

		}else{

			$response['flag'] = false;

			$response['message'] = "Failed to delete";

		}

	}

	return response()->json($response);

}







public function companies(){

	$data = array();

	$data['companies'] = \App\Company::where('is_active',1)->get();

	return view('dashboard.master.companies',$data);

}



public function addCompany(Request $request){

	$response = array();

	$validator = \Validator::make($request->all(),

		array(

			'name' =>'required|unique:companies,name',

			'address1' =>'required',

			'city' =>'required',

			'phone' =>'required',

			'gst_no' =>'required|unique:companies,gst_no',

			'pan_number' =>'required|unique:companies,pan_number',

			'bank_name' =>'required',

			'bank_branch_name' =>'required',

			'acc_number' =>'required',

			'bank_ifs_code' =>'required',

			'token_abbreviation' =>'required',

		)

	);



	if($validator->fails())

	{

		$response['flag'] = false;

		$response['errors'] = $validator->getMessageBag();

	}else{

		$company =  new \App\Company();

		$company->name = $request->name;

		$company->token_abbreviation = $request->token_abbreviation;

		$company->address1 = $request->address1;

		if($request->address2){

			$company->address2 = $request->address2;

		}

		$company->city = $request->city;

		$company->phone = $request->phone;

		if($request->email){

			$company->email = $request->email;

		}

		$company->gst_no = $request->gst_no;

		$company->pan_number = $request->pan_number;

		$company->bank_name = $request->bank_name;

		$company->bank_branch_name = $request->bank_branch_name;

		$company->bank_account_number = $request->acc_number;

		$company->bank_ifsc_code = $request->bank_ifs_code;

		$company->is_rate_mandatory = $request->is_rate_mandatory == "on" ? 1 : 0;

		$company->for_invoice = $request->for_invoice == "on" ? 1 : 0;

		if($company->save()){

			$response['flag'] = true;

			$response['message'] = "Company Added Successfully";

		}else{

			$response['flag'] = false;

			$response['error'] = "Something Went Wrong";

		}

	}

	return response()->json($response);

}

public function getEditCompany($id){

	$data = array();

	$data['company'] = \App\Company::where('id',$id)->where('is_active',1)->first();

	return view('dashboard.master.edit-company',$data);

} 

public function updateCompany(Request $request){

	$response = array();

	$validator = \Validator::make($request->all(),

		array(

			'name' =>'required|unique:companies,name,'.$request->id,

			'address1' =>'required',

			'city' =>'required',

			'phone' =>'required',

			'gst_no' =>'required|unique:companies,gst_no,'.$request->id,

			'pan_number' =>'required|unique:companies,pan_number,'.$request->id,

			'bank_name' =>'required',

			'bank_branch_name' =>'required',

			'acc_number' =>'required',

			'bank_ifs_code' =>'required',

			'token_abbreviation' =>'required',

		)

	);

	if($validator->fails())

	{

		$response['flag'] = false;

		$response['errors'] = $validator->getMessageBag();

	}else{

		$company =  \App\Company::where('id',$request->id)->where('is_active',1)->first();

		if(is_null($company)){

			$response['flag'] = false;

			$response['error'] = "Company Not found";

		}else{

			$company->name = $request->name;

			$company->token_abbreviation = $request->token_abbreviation;

			$company->address1 = $request->address1;

			if($request->address1){

				$company->address2 = $request->address2;

			}

			$company->city = $request->city;

			$company->phone = $request->phone;

			if ($request->email) {

				$company->email = $request->email;

			}

			$company->gst_no = $request->gst_no;

			$company->pan_number = $request->pan_number;

			$company->bank_name = $request->bank_name;

			$company->bank_branch_name = $request->bank_branch_name;

			$company->bank_account_number = $request->acc_number;

			$company->bank_ifsc_code = $request->bank_ifs_code;

			$company->is_rate_mandatory = $request->is_rate_mandatory == "on" ? 1 : 0;

			$company->for_invoice = $request->for_invoice == "on" ? 1 : 0;



			if($company->save()){

				$response['flag'] = true;

				$response['message'] = "Company Updated Successfully";

			}else{

				$response['flag'] = false;

				$response['error'] = "Something Went Wrong";

			}

		}

	}

	return response()->json($response);

}



public function deleteCompany($id){

	$response = array();

	$company = \App\Company::where('id',$id)->where('is_active',1)->first();

	if(is_null($company)){

		$response['flag'] = false;

		$response['message'] = "Company Not Found";

	}else{

		$company->is_active = 0;

		if($company->save()){

			$response['flag'] = true;

			$response['message'] = "Company Deleted";

		}else{

			$response['flag'] = false;

			$response['message'] = "Failed to delete";

		}

	}

	return response()->json($response);

} 





public function rake_points(){

	$data = array();

	$data['rake_points'] = \App\RakePoint::where('is_active',1)->get();

	return view('dashboard.master.rake_points',$data);

} 



public function addRakePoint(Request $request){

	$response = array();

	$validator = \Validator::make($request->all(),

		array(

			'rake_point' =>'required|unique:rake_points,rake_point',

		)

	);



	if($validator->fails())

	{

		$response['flag'] = false;

		$response['errors'] = $validator->getMessageBag();

	}else{

		$rake_point =  new \App\RakePoint();

		$rake_point->rake_point = $request->rake_point;

		if($rake_point->save()){

			$response['flag'] = true;

			$response['message'] = "Rake Point Added Successfully";

		}else{

			$response['flag'] = false;

			$response['error'] = "Something Went Wrong";

		}

	}

	return response()->json($response);

}

public function getEditRakePoint($id){

	$data = array();

	$data['rake_point'] = \App\RakePoint::where('id',$id)->where('is_active',1)->first();

	return view('dashboard.master.edit-rake-point',$data);

} 

public function updateRakePoint(Request $request){

	$response = array();

	$validator = \Validator::make($request->all(),

		array(

			'rake_point' =>'required|unique:rake_points,rake_point,'.$request->id,

		)

	);

	if($validator->fails())

	{

		$response['flag'] = false;

		$response['errors'] = $validator->getMessageBag();

	}else{

		$rake_point =  \App\RakePoint::where('id',$request->id)->where('is_active',1)->first();

		if(is_null($rake_point)){

			$response['flag'] = false;

			$response['error'] = "Rake Point Not found";

		}else{

			$rake_point->rake_point = $request->rake_point;

			if($rake_point->save()){

				$response['flag'] = true;

				$response['message'] = "Rake Point Updated Successfully";

			}else{

				$response['flag'] = false;

				$response['error'] = "Something Went Wrong";

			}

		}

	}

	return response()->json($response);

}



public function deleteRakePoint($id){

	$response = array();

	$rake_point = \App\RakePoint::where('id',$id)->where('is_active',1)->first();

	if(is_null($rake_point)){

		$response['flag'] = false;

		$response['message'] = "Rake Point Not Found";

	}else{

		$rake_point->is_active = 0;

		if($rake_point->save()){

			$response['flag'] = true;

			$response['message'] = "Rake Point Deleted";

		}else{

			$response['flag'] = false;

			$response['message'] = "Failed to delete";

		}

	}

	return response()->json($response);

}









public function masterRakes(){

	$data['master_rakes'] = \App\MasterRake::where('is_active',1)->get();

	$data['sessions'] = \App\Session::where('is_active',1)->get();

	$data['product_companies'] = \App\ProductCompany::where('is_active',1)->get();

	$data['rake_points'] = \App\RakePoint::where('is_active',1)->get();

	$data['products'] = \App\Product::where('is_active',1)->get();

	return view('dashboard.master.master-rakes',$data);

} 



public function lockMasterRake($id){

	$response = array();

	$master_rake = \App\MasterRake::where('id',$id)->first();

	if(!is_null($master_rake)){

		$master_rake->is_closed = 1;

		if($master_rake->save()){

			$response['flag'] = true;

			$response['message'] = "Rake Closed Successfully";

		}else{

			$response['flag'] = false;

			$response['message'] = "Failed To save.";

		}

	}else{

		$response['flag'] = false;

		$response['message'] = "Rake Not Found";



	}

	return response()->json($response);

}  



public function addMasterRake(Request $request){

	// dd($request->all());

	$response = array();

	$validator = \Validator::make($request->all(),

		array(

			'session_id' =>'required',

			'product_company_id' =>'required',

			'loading_time' =>'required',

			'rake_point' =>'required',

			'date' =>'required',

		)

	);



	if($validator->fails())

	{

		$response['flag'] = false;

		$response['errors'] = $validator->getMessageBag();

	}else{

		$master_rake =  new \App\MasterRake();

		$master_rake->session_id = $request->session_id;

		$master_rake->rake_point_id = $request->rake_point;

		$master_rake->product_company_id = $request->product_company_id;

		$master_rake->loading_time = $request->loading_time;

		$master_rake->unloading_time = $request->unloading_time;

		$master_rake->demurrage = $request->demurrage;

		$master_rake->wharfage = $request->wharfage;

		$master_rake->cheque_number = $request->cheque_number;

		//$master_rake->payment_date = date('Y-m-d',strtotime($request->payment_date));

		$master_rake->payment_date = '';

		if($request->hasFile('rr_document')) {

			$filenameWithExt = $request->file('rr_document')->getClientOriginalName();

			$filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);            

			$extension = $request->file('rr_document')->getClientOriginalExtension();

			$fileNameToStore = $filename.'_'.time().'.'.$extension;   

			$destinationPath = public_path().'/rr_document';

			$path = $request->file('rr_document')->move($destinationPath, $fileNameToStore);

			$master_rake->rr_document = str_replace(public_path(), '', $path);

		} 



		$master_rake->date = date('Y-m-d',strtotime($request->date));

		if($master_rake->save()){

			$total = 0;

			$i = 0;

			$product_id = explode(',', $request->product_id);

			$quantity = explode(',', $request->quantity);

			foreach ($product_id as $key => $value) {

				$total = $total + $quantity[$i];

				$master_rake_product = \App\MasterRakeProduct::where('master_rake_id',$master_rake->id)->where('product_id',$product_id[$i])->first();

				if(!is_null($master_rake_product)){

					$master_rake_product->quantity 	 	 = $quantity[$i]+$master_rake_product->quantity ;

					$master_rake_product->remaining_quantity 	 	 = $quantity[$i]+$master_rake_product->remaining_quantity;

				}else{



					$master_rake_product = new \App\MasterRakeProduct();

					$master_rake_product->master_rake_id = $master_rake->id;

					$master_rake_product->product_id 	 = $product_id[$i];

					$master_rake_product->quantity 	 	 = $quantity[$i];

					$master_rake_product->remaining_quantity =  $quantity[$i];

				}

				$master_rake_product->save();



				/*----------update inventory-------------------*/

				$buffer_inventory = \App\Inventory::where('product_company_id',$request->product_company_id)->where('warehouse_id',24)->where('rake_point_id', $request->rake_point)->where('product_id',$product_id[$i])->where('product_brand_id',$request->product_company_id)->first();



				if(!is_null($buffer_inventory)){

					$buffer_inventory->quantity = $buffer_inventory->quantity + $quantity[$i];					

					$buffer_inventory->save();

				}else{

					$buffer_inventory = new  \App\Inventory();

					$buffer_inventory->product_company_id 			= $request->product_company_id;

					$buffer_inventory->rake_point_id			    = $request->rake_point;

					$buffer_inventory->product_brand_id			    = $request->product_company_id;

					$buffer_inventory->warehouse_id 				= 24;

					$buffer_inventory->product_id 					= $product_id[$i];

					$buffer_inventory->quantity 					= $quantity[$i];

					$buffer_inventory->unit_id 					    = 1;

					$buffer_inventory->save();

				}

				/*----------update inventory-------------------*/





				$i++;

			}

			$product_company = getModelById('ProductCompany',$request->product_company_id);

			$session = getModelById('Session',$request->session_id);

			$master_rake->name = $product_company->brand_name.'/'.date('d/m/Y',strtotime($request->date)).'/'.$session->session.'/'.$master_rake->id;

			$master_rake->quantity_alloted = $total;

			$master_rake->save();

			$response['flag'] = true;

			$response['message'] = "Master Rake Added Successfully";

		}else{

			$response['flag'] = false;

			$response['error'] = "Something Went Wrong";

		}

	}

	return response()->json($response);

}

public function getEditMasterRake($id){

	$data['master_rake'] = \App\MasterRake::where('id',$id)->where('is_active',1)->with('master_rake_products')->first();

	$data['sessions'] = \App\Session::where('is_active',1)->get();

	$data['product_companies'] = \App\ProductCompany::where('is_active',1)->get();

	$data['products'] = \App\Product::where('is_active',1)->get();

	$data['rake_points'] = \App\RakePoint::where('is_active',1)->get();

	return view('dashboard.master.edit-master-rake',$data);

} 

public function updateMasterRake(Request $request){



	// dd($request->all());

	$response = array();

	$validator = \Validator::make($request->all(),

		array(

			'session_id' =>'required',

			'product_company_id' =>'required',

			'loading_time' =>'required',

			'date' =>'required',

			'rake_point' =>'required',

		)

	);

	if($validator->fails())

	{

		$response['flag'] = false;

		$response['errors'] = $validator->getMessageBag();

	}else{

		$master_rake =  \App\MasterRake::where('id',$request->id)->where('is_active',1)->first();

		if(is_null($master_rake)){

			$response['flag'] = false;

			$response['error'] = "Master Rake Not found";

		}else{

			$master_rake->session_id = $request->session_id;

			$master_rake->rake_point_id = $request->rake_point;

			$master_rake->product_company_id = $request->product_company_id;

			$master_rake->loading_time = $request->loading_time;

			$master_rake->unloading_time = $request->unloading_time;

			$master_rake->date = date('Y-m-d',strtotime($request->date));

			$master_rake->demurrage = $request->demurrage;

			$master_rake->wharfage = $request->wharfage;

			$master_rake->cheque_number = $request->cheque_number;

			$master_rake->payment_date = date('Y-m-d',strtotime($request->payment_date));





			if($request->hasFile('rr_document')) {

				$filenameWithExt = $request->file('rr_document')->getClientOriginalName();

				$filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);            

				$extension = $request->file('rr_document')->getClientOriginalExtension();

				$fileNameToStore = $filename.'_'.time().'.'.$extension;   

				$destinationPath = public_path().'/rr_document';

				$path = $request->file('rr_document')->move($destinationPath, $fileNameToStore);

				$master_rake->rr_document = str_replace(public_path(), '', $path);

			} 



			if($master_rake->save()){

				$product_id = explode(',', $request->product_id);

				$quantity = explode(',', $request->quantity);

				$excess_quantity = explode(',', $request->excess_quantity);

				$shortage_from_company = explode(',', $request->shortage_from_company);





				\App\MasterRakeProduct::where('master_rake_id', $master_rake->id)->delete();

				$i = 0;

				foreach ($product_id as $key => $value) {

					$master_rake_product = \App\MasterRakeProduct::where('master_rake_id',$master_rake->id)->where('product_id',$product_id[$i])->first();

					if(!is_null($master_rake_product)){

						$master_rake_product->quantity 	 	 = $quantity[$i]+$master_rake_product->quantity ;

						$master_rake_product->remaining_quantity 	 	 = $quantity[$i]+$master_rake_product->remaining_quantity;

					}else{

						$master_rake_product = new \App\MasterRakeProduct();

						$master_rake_product->master_rake_id = $master_rake->id;

						$master_rake_product->product_id 	 = $product_id[$i];

						$master_rake_product->quantity 	 	 = $quantity[$i];

						$master_rake_product->remaining_quantity 	 	 = $quantity[$i];

						$master_rake_product->excess_quantity 	 	 = $excess_quantity[$i];

						$master_rake_product->shortage_from_company 	 	 = $shortage_from_company[$i];

					}

					$master_rake_product->save();



					$rake_excess_additions =  \App\RakeExcessAddition::where('master_rake_id',$master_rake->id)->where('product_id',$product_id[$i])->first();

					if($excess_quantity > 0 && is_null($rake_excess_additions)){

						$rake_excess_additions = new \App\RakeExcessAddition();

						$rake_excess_additions->master_rake_id = $master_rake->id;

						$rake_excess_additions->product_id 	 = $product_id[$i];

						$rake_excess_additions->quantity 	 = $excess_quantity[$i];

						$rake_excess_additions->save();

					}

					$i++;

				}

				$response['flag'] = true;

				$response['message'] = "Master Rake Updated Successfully";

			}else{

				$response['flag'] = false;

				$response['error'] = "Something Went Wrong";

			}

		}

	}

	return response()->json($response);

}



public function deleteMasterRake($id){

	$response = array();

	$master_rake = \App\MasterRake::where('id',$id)->where('is_active',1)->first();

	if(is_null($master_rake)){

		$response['flag'] = false;

		$response['message'] = "Master Rake Not Found";

	}else{

		$master_rake->is_active = 0;

		if($master_rake->save()){

			$response['flag'] = true;

			$response['message'] = "Master Rake Deleted";

		}else{

			$response['flag'] = false;

			$response['message'] = "Failed to delete";

		}

	}

	return response()->json($response);

} 



public function masterRakeDetails($id){

	$response = array();

	$master_rake = \App\MasterRake::where('id',$id)->where('is_active',1)->with('session','product_company')->first();

	if(is_null($master_rake)){

		$response['flag'] = false;

		$response['message'] = "Master Rake Not Found";

	}else{

		$response['flag'] = true;

		$response['master_rake'] = $master_rake;

	}

	return response()->json($response);

} 







public function rakes(){

	$data['rakes'] = \App\Rake::where('is_active',1)->with('master_rake')->get();

	$data['master_rakes'] = \App\MasterRake::where('is_active',1)->get();

	$data['product_companies'] = \App\ProductCompany::where('is_active',1)->get();

	$data['products'] = \App\Product::where('is_active',1)->get();

	return view('dashboard.master.rakes',$data);

} 



public function addRake(Request $request){

	$response = array();

	$validator = \Validator::make($request->all(),

		array(

			'master_rake_id' =>'required',

			'quantity' =>'required|integer',

			'product_id' =>'required',

		)

	);



	if($validator->fails())

	{

		$response['flag'] = false;

		$response['errors'] = $validator->getMessageBag();

	}else{

		$rake =  new \App\Rake();

		$rake->master_rake_id = $request->master_rake_id;

		$rake->quantity = $request->quantity;

		$rake->product_id = $request->product_id;

		if($rake->save()){

			$master_rake = getModelById('MasterRake',$request->master_rake_id);

			$product = getModelById('Product',$request->product_id);

			$rake->name = $master_rake->name.'/'.$product->name;

			$rake->rake_no = 'RK'.$rake->id;

			$rake->save();

			$response['flag'] = true;

			$response['message'] = "Rake Added Successfully";

		}else{

			$response['flag'] = false;

			$response['error'] = "Something Went Wrong";

		}

	}

	return response()->json($response);

}

public function getEditRake($id){

	$data['rake'] = \App\Rake::where('id',$id)->where('is_active',1)->with('master_rake')->first();

	$data['master_rakes'] = \App\MasterRake::where('is_active',1)->get();

	$data['product_companies'] = \App\ProductCompany::where('is_active',1)->get();

	$data['products'] = \App\Product::where('is_active',1)->get();

	return view('dashboard.master.edit-rake',$data);

} 

public function updateRake(Request $request){

	$response = array();

	$validator = \Validator::make($request->all(),

		array(

			'master_rake_id' =>'required',

			'quantity' =>'required|integer',

			'product_id' =>'required',

		)

	);

	if($validator->fails())

	{

		$response['flag'] = false;

		$response['errors'] = $validator->getMessageBag();

	}else{

		$rake =  \App\Rake::where('id',$request->id)->where('is_active',1)->first();

		if(is_null($rake)){

			$response['flag'] = false;

			$response['error'] = "Rake Not found";

		}else{

			$rake->master_rake_id = $request->master_rake_id;

			$rake->quantity = $request->quantity;

			$rake->product_id = $request->product_id;

			if($rake->save()){

				$response['flag'] = true;

				$response['message'] = "Rake Updated Successfully";

			}else{

				$response['flag'] = false;

				$response['error'] = "Something Went Wrong";

			}

		}

	}

	return response()->json($response);

}



public function deleteRake($id){

	$response = array();

	$rake = \App\Rake::where('id',$id)->where('is_active',1)->first();

	if(is_null($rake)){

		$response['flag'] = false;

		$response['message'] = "Rake Not Found";

	}else{

		$rake->is_active = 0;

		if($rake->save()){

			$response['flag'] = true;

			$response['message'] = "Rake Deleted";

		}else{

			$response['flag'] = false;

			$response['message'] = "Failed to delete";

		}

	}

	return response()->json($response);

} 



public function dealers(){

	$data = array();

	$data['dealers'] = \App\Dealer::where('is_active',1)->get();

	$data['locations'] = \App\Location::where('is_active',1)->get();



	return view('dashboard.master.dealers',$data);

} 



public function addDealer(Request $request){

	$response = array();

	$validator = \Validator::make($request->all(),

		array(

				// 'name' =>'required|unique:dealers,name',

			'name' =>'required',

			'unique_id' =>'required|unique:dealers,unique_id',

			'location'=>'required'

				//'phone' =>'required',

			// 'address1' =>'required',

		)

	);



	if($validator->fails())

	{

		$response['flag'] = false;

		$response['errors'] = $validator->getMessageBag();

	}else{

		$dealer =  new \App\Dealer();

		$dealer->unique_id = $request->unique_id;

		$dealer->name = $request->name;

		$dealer->phone = $request->phone;

		$dealer->destination_code = $request->location;

		if($request->hindi_name){

			$dealer->hindi_name = $request->hindi_name;

		}

		else{

			$dealer->hindi_name = $request->name;

		}

		$dealer->name = $request->name;

		$dealer->phone = $request->phone;

		$dealer->address1 = $request->address1;

		if($request->hindi_address1){

			$dealer->hindi_address1 = $request->hindi_address1;

		}

		else{

			$dealer->hindi_address1 = $request->address1;

		}

		$dealer->address2 = $request->address2;

		if($request->hindi_address2){

			$dealer->hindi_address2 = $request->hindi_address2;

		}

		else{

			$dealer->hindi_address2 = $request->address2;

		}

		$dealer->district = $request->district;

		$dealer->pin_code = $request->pin_code;

		$dealer->owner_name = $request->owner_name;

		$dealer->mobile_number = $request->mobile_number;

		$dealer->email = $request->email_address;

		$dealer->ifms_code = $request->ifms_code;

		$dealer->gst_number = $request->gst_number;

		$dealer->show_separate_report = $request->show_separate_report == "on" ? 1 : 0;

		if($dealer->save()){

			// $dealer->unique_id = 'PRT00'.$dealer->id;

			$dealer->save();

			$response['flag'] = true;

			$response['message'] = "Dealer Added Successfully";

		}else{

			$response['flag'] = false;

			$response['error'] = "Something Went Wrong";

		}

	}

	return response()->json($response);

}

public function getEditDealer($id){

	$data = array();

	$data['dealer'] = \App\Dealer::where('id',$id)->where('is_active',1)->first();

	$data['locations'] = \App\Location::where('is_active',1)->get();

	return view('dashboard.master.edit-dealer',$data);

} 

public function updateDealer(Request $request){

	$response = array();

	$validator = \Validator::make($request->all(),

		array(

			'name' =>'required',

			'unique_id' =>'required',

			'location'=>'required'

		)

	);

	if($validator->fails())

	{

		$response['flag'] = false;

		$response['errors'] = $validator->getMessageBag();

	}else{

		$dealer =  \App\Dealer::where('id',$request->id)->where('is_active',1)->first();

		if(is_null($dealer)){

			$response['flag'] = false;

			$response['error'] = "Dealer Not found";

		}else{

			$dealer->unique_id = $request->unique_id;

		$dealer->name = $request->name;

		$dealer->phone = $request->phone;

		$dealer->destination_code = $request->location;

			if($request->hindi_name){

				$dealer->hindi_name = $request->hindi_name;

			}

			else{

				$dealer->hindi_name = $request->name;

			}

			$dealer->phone = $request->phone;

			$dealer->address1 = $request->address1;

			if($request->hindi_address1){

				$dealer->hindi_address1 = $request->hindi_address1;

			}

			else{

				$dealer->hindi_address1 = $request->address1;

			}

			$dealer->address2 = $request->address2;

			if($request->hindi_address2){

				$dealer->hindi_address2 = $request->hindi_address2;

			}

			else{

				$dealer->hindi_address2 = $request->address2;

			}

			$dealer->owner_name = $request->owner_name;

			$dealer->district = $request->district;

			$dealer->pin_code = $request->pin_code;

			$dealer->mobile_number = $request->mobile_number;

			$dealer->email = $request->email_address;

			$dealer->ifms_code = $request->ifms_code;

			$dealer->gst_number = $request->gst_number;

			$dealer->show_separate_report = $request->show_separate_report == "on" ? 1 : 0;

			if($dealer->save()){

				$response['flag'] = true;

				$response['message'] = "Dealer Updated Successfully";

			}else{

				$response['flag'] = false;

				$response['error'] = "Something Went Wrong";

			}

		}

	}

	return response()->json($response);

}



public function deleteDealer($id){

	$response = array();

	$dealer = \App\Dealer::where('id',$id)->where('is_active',1)->first();

	if(is_null($dealer)){

		$response['flag'] = false;

		$response['message'] = "Dealer Not Found";

	}else{

		$dealer->is_active = 0;

		if($dealer->save()){

			$response['flag'] = true;

			$response['message'] = "Dealer Deleted";

		}else{

			$response['flag'] = false;

			$response['message'] = "Failed to delete";

		}

	}

	return response()->json($response);

}



public function exportDealer(Request $request){



	$dealerExcel = \App\Dealer::select('*')->where('is_active',1)->get();

	\Excel::create('Dealer Lists', function($excel) use($dealerExcel) {

		$excel->sheet('Dealers', function($sheet) use($dealerExcel) {

			$sheet->fromArray($dealerExcel);

		});

	})->export('xls');

}

public function importDealer(Request $request){

	if($request->hasFile('dealer_file'))

	{

		$extension = \File::extension($request->file('dealer_file')->getClientOriginalName());

		if ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {

			\Excel::load($request->file('dealer_file'), function($reader) {

					// echo "<pre>";

				foreach ($reader->toArray() as $sheet) {

					if(count($sheet) > 0){

						$dealer = \App\Dealer::where('id',$sheet['id'])->first();

						if(!is_null($dealer)){

							$dealer->name = $sheet['name'];

							$dealer->hindi_name = $sheet['hindi_name'];

							$dealer->address1 = $sheet['address1'];

							$dealer->hindi_address1 = $sheet['hindi_address1'];

							$dealer->address2 = $sheet['address2'];

							$dealer->hindi_address2 = $sheet['hindi_address2'];

							$dealer->owner_name = $sheet['owner_name'];

							$dealer->district = $sheet['district'];

							$dealer->pin_code = $sheet['pin_code'];

							$dealer->mobile_number = $sheet['mobile_number'];

							$dealer->email = $sheet['email'];

							$dealer->ifms_code = $sheet['ifms_code'];

							$dealer->gst_number = $sheet['gst_number'];

							$dealer->save();



						}else{

							$dealer = new \App\Dealer();

							$dealer->name = $sheet['name'];

							$dealer->hindi_name = $sheet['hindi_name'];

							$dealer->address1 = $sheet['address1'];

							$dealer->hindi_address1 = $sheet['address1'];

							$dealer->address2 = $sheet['address2'];

							$dealer->hindi_address2 = $sheet['address2'];

							$dealer->owner_name = $sheet['owner_name'];

							$dealer->district = $sheet['district'];

							$dealer->pin_code = $sheet['pin_code'];

							$dealer->mobile_number = $sheet['mobile_number'];

							$dealer->email = $sheet['email'];

							$dealer->ifms_code = $sheet['ifms_code'];

							$dealer->gst_number = $sheet['gst_number'];

							$dealer->save();

							$dealer->unique_id = 'PRT00'.$dealer->id;

							$dealer->save();

						}

					}

				}

			});

			return redirect('user/dealers')->with('save','Imported Successfully');

		}else{

			return redirect('user/dealers')->with('error','Invalid File type');

		}

	}else{

		return redirect('user/dealers')->with('error','File Required');

	}

}





public function product_categories(){

	$data['product_categories'] = \App\ProductCategory::where('is_active',1)->get();

	return view('dashboard.master.product-categories',$data);

} 



public function addProductCategory(Request $request){

	$response = array();

	$validator = \Validator::make($request->all(),

		array(

			'category' =>'required|unique:product_categories,category',

		)

	);



	if($validator->fails())

	{

		$response['flag'] = false;

		$response['errors'] = $validator->getMessageBag();

	}else{

		$category =  new \App\ProductCategory();

		$category->category = $request->category;

		if($category->save()){

			$response['flag'] = true;

			$response['message'] = "Category Added Successfully";

		}else{

			$response['flag'] = false;

			$response['error'] = "Something Went Wrong";

		}

	}

	return response()->json($response);

}

public function getEditProductCategory($id){

	$data['category'] = \App\ProductCategory::where('id',$id)->where('is_active',1)->first();

	return view('dashboard.master.edit-product-category',$data);

} 

public function updateProductCategory(Request $request){

	$response = array();

	$validator = \Validator::make($request->all(),

		array(

			'category' =>'required|unique:product_categories,category,'.$request->id,

		)

	);

	if($validator->fails())

	{

		$response['flag'] = false;

		$response['errors'] = $validator->getMessageBag();

	}else{

		$category =  \App\ProductCategory::where('id',$request->id)->where('is_active',1)->first();

		if(is_null($category)){

			$response['flag'] = false;

			$response['error'] = "Category Not found";

		}else{

			$category->category = $request->category;

			if($category->save()){

				$response['flag'] = true;

				$response['message'] = "Category Updated Successfully";

			}else{

				$response['flag'] = false;

				$response['error'] = "Something Went Wrong";

			}

		}

	}

	return response()->json($response);

}



public function deleteProductCategory($id){

	$response = array();

	$category = \App\ProductCategory::where('id',$id)->where('is_active',1)->first();

	if(is_null($category)){

		$response['flag'] = false;

		$response['message'] = "Category Not Found";

	}else{

		$category->is_active = 0;

		if($category->save()){

			$response['flag'] = true;

			$response['message'] = "Category Deleted";

		}else{

			$response['flag'] = false;

			$response['message'] = "Failed to delete";

		}

	}

	return response()->json($response);

}









public function products(){

	$data = array();

	$data['invoice_types'] = \App\InvoiceType::where('is_active',1)->get();

	$data['products'] = \App\Product::where('is_active',1)->with('product_category')->get();

	$data['product_categories'] = \App\ProductCategory::where('is_active',1)->get();

	return view('dashboard.master.products',$data);

} 



public function addProduct(Request $request){

	$response = array();

	$validator = \Validator::make($request->all(),

		array(

			'name' =>'required|unique:products,name',

			'hsn_code' =>'required',

			'gst_slab' =>'required',

			'igst' =>'required',

			'cgst' =>'required',

			'sgst' =>'required|same:cgst',

			'invoice_type' =>'required',

			'product_category' =>'required',

			'weight_in_kg'=>'required'

		)

	);

	if($validator->fails())

	{

		$response['flag'] = false;

		$response['errors'] = $validator->getMessageBag();

	}else{

		$product =  new \App\Product();

		$product->name 		= $request->name;

		$product->hsn_code 	= $request->hsn_code;

		$product->gst_slab 	= $request->gst_slab;

		$product->igst 		= $request->igst;

		$product->cgst 		= $request->cgst;

		$product->sgst 		= $request->sgst;

		$product->invoice_type_id 		= $request->invoice_type;

		$product->product_category_id = $request->product_category;

		$product->weight_in_kg=$request->weight_in_kg;

		if($request->hindi_name){

			$product->hindi_name=$request->hindi_name;

		}else{

			$product->hindi_name=$request->name;

		}

		if($product->save()){

			$product->unique_id = 'PROD00'.$product->id;

			$product->save();

			$response['flag'] = true;

			$response['message'] = "Product Added Successfully";

		}else{

			$response['flag'] = false;

			$response['error'] = "Something Went Wrong";

		}

	}

	return response()->json($response);

}

public function getEditProduct($id){

	$data = array();

	$data['invoice_types'] = \App\InvoiceType::where('is_active',1)->get();

	$data['product'] = \App\Product::where('id',$id)->where('is_active',1)->first();

	$data['product_categories'] = \App\ProductCategory::where('is_active',1)->get();

	return view('dashboard.master.edit-product',$data);

} 

public function updateProduct(Request $request){

	$response = array();

	$validator = \Validator::make($request->all(),

		array(

			'name' =>'required|unique:products,name,'.$request->id,

			'hsn_code' =>'required',

			'gst_slab' =>'required',

			'igst' =>'required',

			'cgst' =>'required',

			'sgst' =>'required|same:cgst',

			'invoice_type' =>'required',

			'product_category' =>'required',

		)

	);

	if($validator->fails())

	{

		$response['flag'] = false;

		$response['errors'] = $validator->getMessageBag();

	}else{

		$product =  \App\Product::where('id',$request->id)->where('is_active',1)->first();

		if(is_null($product)){

			$response['flag'] = false;

			$response['error'] = "Product Not found";

		}else{

			$product->name 		= $request->name;

			$product->hsn_code 	= $request->hsn_code;

			$product->gst_slab 	= $request->gst_slab;

			$product->igst 		= $request->igst;

			$product->cgst 		= $request->cgst;

			$product->sgst 		= $request->sgst;

			$product->invoice_type_id 		= $request->invoice_type;

			$product->product_category_id = $request->product_category;

			$product->weight_in_kg=$request->weight_in_kg;

			if($request->hindi_name){

				$product->hindi_name=$request->hindi_name;

			}else{

				$product->hindi_name=$request->name;

			}

			if($product->save()){

				$response['flag'] = true;

				$response['message'] = "Product Updated Successfully";

			}else{

				$response['flag'] = false;

				$response['error'] = "Something Went Wrong";

			}

		}

	}

	return response()->json($response);

}



public function deleteProduct($id){

	$response = array();

	$product = \App\Product::where('id',$id)->where('is_active',1)->first();

	if(is_null($product)){

		$response['flag'] = false;

		$response['message'] = "Product Not Found";

	}else{

		$product->is_active = 0;

		if($product->save()){

			$response['flag'] = true;

			$response['message'] = "Product Deleted";

		}else{

			$response['flag'] = false;

			$response['message'] = "Failed to delete";

		}

	}

	return response()->json($response);

}









public function productCompanies(){

	$data = array();

	$data['product_companies'] = \App\ProductCompany::where('is_active',1)->get();

	return view('dashboard.master.product-companies',$data);

} 



public function addProductCompany(Request $request){

	$response = array();

	$validator = \Validator::make($request->all(),

		array(

			'name' =>'required|unique:product_companies,name',

			'abbreviation' =>'required|unique:product_companies,abbreviation',

			'brand_name' =>'required',

			'address' =>'required',

			'state' =>'required',

			'gst_no' =>'required|unique:accounts,gst_no',

		)

	);



	if($validator->fails())

	{

		$response['flag'] = false;

		$response['errors'] = $validator->getMessageBag();

	}else{

		$product_company =  new \App\ProductCompany();

		$product_company->name = $request->name;

		if($request->hindi_name){

			$product_company->hindi_name = $request->hindi_name;

		}

		else{

			$product_company->hindi_name = $request->name;

		}

		$product_company->abbreviation = $request->abbreviation;

		$product_company->brand_name = $request->brand_name;

		if($request->hindi_brand_name){

			$product_company->hindi_brand_name = $request->hindi_brand_name;

		}

		else{

			$product_company->hindi_brand_name = $request->brand_name;

		}

		$product_company->address = $request->address;

		if($request->hindi_address){

			$product_company->hindi_address = $request->hindi_address;

		}

		else{

			$product_company->hindi_address = $request->address;

		}

		$product_company->state = $request->state;

		$product_company->gst_no = $request->gst_no;

		if($product_company->save()){

			$product_company->unique_id = 'PRODCPNY00'.$product_company->id;

			$product_company->save();

			$response['flag'] = true;

			$response['message'] = "Product Company Added Successfully";

		}else{

			$response['flag'] = false;

			$response['error'] = "Something Went Wrong";

		}

	}

	return response()->json($response);

}

public function getEditProductCompany($id){

	$data = array();

	$data['company'] = \App\ProductCompany::where('id',$id)->where('is_active',1)->first();

	return view('dashboard.master.edit-product-company',$data);

} 

public function updateProductCompany(Request $request){

	$response = array();

	$validator = \Validator::make($request->all(),

		array(

			'name' =>'required|unique:product_companies,name,'.$request->id,

			'abbreviation' =>'required|unique:product_companies,abbreviation,'.$request->id,

			'brand_name' =>'required',

			'address' =>'required',

			'state' =>'required',

			'gst_no' =>'required|unique:accounts,gst_no,'.$request->id,

		)

	);

	if($validator->fails())

	{

		$response['flag'] = false;

		$response['errors'] = $validator->getMessageBag();

	}else{

		$product_company =  \App\ProductCompany::where('id',$request->id)->where('is_active',1)->first();

		if(is_null($product_company)){

			$response['flag'] = false;

			$response['error'] = "Product Company Not found";

		}else{

			$product_company->name = $request->name;

			if($request->hindi_name){

				$product_company->hindi_name = $request->hindi_name;

			}

			else{

				$product_company->hindi_name = $request->name;

			}

			$product_company->abbreviation = $request->abbreviation;

			$product_company->brand_name = $request->brand_name;

			if($request->hindi_brand_name){

				$product_company->hindi_brand_name = $request->hindi_brand_name;

			}

			else{

				$product_company->hindi_brand_name = $request->brand_name;

			}

			$product_company->address = $request->address;

			if($request->hindi_address){

				$product_company->hindi_address = $request->hindi_address;

			}

			else{

				$product_company->hindi_address = $request->address;

			}

			$product_company->state = $request->state;

			$product_company->gst_no = $request->gst_no;

			if($product_company->save()){

				$response['flag'] = true;

				$response['message'] = "Company Updated Successfully";

			}else{

				$response['flag'] = false;

				$response['error'] = "Something Went Wrong";

			}

		}

	}

	return response()->json($response);

}



public function deleteProductCompany($id){

	$response = array();

	$product_company = \App\ProductCompany::where('id',$id)->where('is_active',1)->first();

	if(is_null($product_company)){

		$response['flag'] = false;

		$response['message'] = "Product Company Not Found";

	}else{

		$product_company->is_active = 0;

		if($product_company->save()){

			$response['flag'] = true;

			$response['message'] = "Product Company Deleted";

		}else{

			$response['flag'] = false;

			$response['message'] = "Failed to delete";

		}

	}

	return response()->json($response);

}





public function module(){

	$data['modules'] = \App\Module::where('is_active',1)->get();

	return view('dashboard.master.module',$data);

} 



public function addModule(Request $request){

	$response = array();

	$validator = \Validator::make($request->all(),

		array(

			'module' =>'required|unique:modules,module',

			'icon' =>'required',

		)

	);



	if($validator->fails())

	{

		$response['flag'] = false;

		$response['errors'] = $validator->getMessageBag();

	}else{

		$module =  new \App\Module();

		$module->module = $request->module;

		if($request->link){

			$module->link = $request->link;

		}

		$module->icon = $request->icon;

		if($module->save()){

			$response['flag'] = true;

			$response['message'] = "Module Added Successfully";

		}else{

			$response['flag'] = false;

			$response['error'] = "Something Went Wrong";

		}

	}

	return response()->json($response);

}

public function getEditModule($id){

	$data['module'] = \App\Module::where('id',$id)->where('is_active',1)->first();

	return view('dashboard.master.edit-module',$data);

} 

public function updateModule(Request $request){

	$response = array();

	$validator = \Validator::make($request->all(),

		array(

			'module' =>'required|unique:modules,module,'.$request->id,

			'icon' =>'required',

		)

	);

	if($validator->fails())

	{

		$response['flag'] = false;

		$response['errors'] = $validator->getMessageBag();

	}else{

		$module =  \App\Module::where('id',$request->id)->where('is_active',1)->first();

		if(is_null($module)){

			$response['flag'] = false;

			$response['error'] = "Module Not found";

		}else{

			$module->module = $request->module;

			if($request->link){

				$module->link = $request->link;

			}

			$module->icon = $request->icon;

			if($module->save()){

				$response['flag'] = true;

				$response['message'] = "Module Updated Successfully";

			}else{

				$response['flag'] = false;

				$response['error'] = "Something Went Wrong";

			}

		}

	}

	return response()->json($response);

}



public function deleteModule($id){

	$response = array();

	$module = \App\Module::where('id',$id)->where('is_active',1)->first();

	if(is_null($module)){

		$response['flag'] = false;

		$response['message'] = "mMdule Not Found";

	}else{

		$module->is_active = 0;

		if($module->save()){

			$response['flag'] = true;

			$response['message'] = "Module Deleted";

		}else{

			$response['flag'] = false;

			$response['message'] = "Failed to delete";

		}

	}

	return response()->json($response);

}







public function sub_module(){

	$data['sub_modules'] = \App\SubModule::where('is_active',1)->get();

	$data['modules'] = \App\Module::where('is_active',1)->get();

	return view('dashboard.master.sub-module',$data);

} 



public function addSubModule(Request $request){

	$response = array();

	$validator = \Validator::make($request->all(),

		array(

			'module_id' =>'required',

			'sub_module' =>'required|unique:sub_modules,sub_module',

			'link' =>'required|unique:sub_modules,link',

		)

	);



	if($validator->fails())

	{

		$response['flag'] = false;

		$response['errors'] = $validator->getMessageBag();

	}else{

		$sub_module =  new \App\SubModule();

		$sub_module->module_id = $request->module_id;

		$sub_module->sub_module = $request->sub_module;

		$sub_module->link = $request->link;

		if($sub_module->save()){

			$response['flag'] = true;

			$response['message'] = "Sub Module Added Successfully";

		}else{

			$response['flag'] = false;

			$response['error'] = "Something Went Wrong";

		}

	}

	return response()->json($response);

}

public function getEditSubModule($id){

	$data = array();

	$data['sub_module'] = \App\SubModule::where('id',$id)->where('is_active',1)->first();

	$data['modules'] = \App\Module::where('is_active',1)->get();

	return view('dashboard.master.edit-sub-module',$data);

} 

public function updateSubModule(Request $request){

	$response = array();

	$validator = \Validator::make($request->all(),

		array(

			'module_id' =>'required',

			'sub_module' =>'required|unique:sub_modules,sub_module,'.$request->id,

			'link' =>'required|unique:sub_modules,link,'.$request->id,

		)

	);

	if($validator->fails())

	{

		$response['flag'] = false;

		$response['errors'] = $validator->getMessageBag();

	}else{

		$sub_module =  \App\SubModule::where('id',$request->id)->where('is_active',1)->first();

		if(is_null($sub_module)){

			$response['flag'] = false;

			$response['error'] = "Sub Module Not found";

		}else{

			$permission =  \App\RoleModuleAssociation::where('sub_module_id',$request->id)->first();

			if(!is_null($permission)){

				if($permission->module_id != $request->module_id){

					$permission->delete();

				}

			}

			$sub_module->module_id = $request->module_id;

			$sub_module->sub_module = $request->sub_module;

			$sub_module->link = $request->link;

			if($sub_module->save()){

				$response['flag'] = true;

				$response['message'] = "Sub Module Updated Successfully";

			}else{

				$response['flag'] = false;

				$response['error'] = "Something Went Wrong";

			}

		}

	}

	return response()->json($response);

}



public function deleteSubModule($id){

	$response = array();

	$sub_module = \App\SubModule::where('id',$id)->where('is_active',1)->first();

	if(is_null($sub_module)){

		$response['flag'] = false;

		$response['message'] = "Sub Module Not Found";

	}else{

		$sub_module->is_active = 0;

		if($sub_module->save()){

			$response['flag'] = true;

			$response['message'] = "Sub Module Deleted";

		}else{

			$response['flag'] = false;

			$response['message'] = "Failed to delete";

		}

	}

	return response()->json($response);

}







public function invoice_types(){

	$data = array();

	$data['invoice_types'] = \App\InvoiceType::where('is_active',1)->get();

	return view('dashboard.master.invoice-types',$data);

} 



public function addInvoiceType(Request $request){

	$response = array();

	$validator = \Validator::make($request->all(),

		array(

			'invoice_type' =>'required|unique:invoice_types,invoice_type',

		)

	);



	if($validator->fails())

	{

		$response['flag'] = false;

		$response['errors'] = $validator->getMessageBag();

	}else{

		$invoice_type =  new \App\InvoiceType();

		$invoice_type->invoice_type = $request->invoice_type;

		if($invoice_type->save()){

			$response['flag'] = true;

			$response['message'] = "Invoice Type Added Successfully";

		}else{

			$response['flag'] = false;

			$response['error'] = "Something Went Wrong";

		}

	}

	return response()->json($response);

}

public function getEditInvoiceType($id){

	$data['invoice_type'] = \App\InvoiceType::where('id',$id)->where('is_active',1)->first();

	return view('dashboard.master.edit-invoice-type',$data);

} 

public function updateInvoiceType(Request $request){

	$response = array();

	$validator = \Validator::make($request->all(),

		array(

			'invoice_type' =>'required|unique:invoice_types,invoice_type,'.$request->id,

		)

	);

	if($validator->fails())

	{

		$response['flag'] = false;

		$response['errors'] = $validator->getMessageBag();

	}else{

		$invoice_type =  \App\InvoiceType::where('id',$request->id)->where('is_active',1)->first();

		if(is_null($invoice_type)){

			$response['flag'] = false;

			$response['error'] = "Invoice Type Not found";

		}else{

			$invoice_type->invoice_type = $request->invoice_type;

			if($invoice_type->save()){

				$response['flag'] = true;

				$response['message'] = "Invoice Type Updated Successfully";

			}else{

				$response['flag'] = false;

				$response['error'] = "Something Went Wrong";

			}

		}

	}

	return response()->json($response);

}



public function deleteInvoiceType($id){

	$response = array();

	$invoice_type = \App\InvoiceType::where('id',$id)->where('is_active',1)->first();

	if(is_null($invoice_type)){

		$response['flag'] = false;

		$response['message'] = "Invoice Type Not Found";

	}else{

		$invoice_type->is_active = 0;

		if($invoice_type->save()){

			$response['flag'] = true;

			$response['message'] = "Invoice Type Deleted";

		}else{

			$response['flag'] = false;

			$response['message'] = "Failed to delete";

		}

	}

	return response()->json($response);

}







public function payment_modes(){

	$data = array();

	$data['payment_modes'] = \App\PaymentMode::where('is_active',1)->get();

	return view('dashboard.master.payment-modes',$data);

} 



public function addPaymentMode(Request $request){

	$response = array();

	$validator = \Validator::make($request->all(),

		array(

			'payment_mode' =>'required|unique:payment_modes,payment_mode',

		)

	);



	if($validator->fails())

	{

		$response['flag'] = false;

		$response['errors'] = $validator->getMessageBag();

	}else{

		$payment_mode =  new \App\PaymentMode();

		$payment_mode->payment_mode = $request->payment_mode;

		if($payment_mode->save()){

			$response['flag'] = true;

			$response['message'] = "Payment Mode Added Successfully";

		}else{

			$response['flag'] = false;

			$response['error'] = "Something Went Wrong";

		}

	}

	return response()->json($response);

}

public function getEditPaymentMode($id){

	$data['payment_mode'] = \App\PaymentMode::where('id',$id)->where('is_active',1)->first();

	return view('dashboard.master.edit-payment-mode',$data);

} 

public function updatePaymentMode(Request $request){

	$response = array();

	$validator = \Validator::make($request->all(),

		array(

			'payment_mode' =>'required|unique:payment_modes,payment_mode,'.$request->id,

		)

	);

	if($validator->fails())

	{

		$response['flag'] = false;

		$response['errors'] = $validator->getMessageBag();

	}else{

		$payment_mode =  \App\PaymentMode::where('id',$request->id)->where('is_active',1)->first();

		if(is_null($payment_mode)){

			$response['flag'] = false;

			$response['error'] = "Payment Mode Not found";

		}else{

			$payment_mode->payment_mode = $request->payment_mode;

			if($payment_mode->save()){

				$response['flag'] = true;

				$response['message'] = "Payment Mode Updated Successfully";

			}else{

				$response['flag'] = false;

				$response['error'] = "Something Went Wrong";

			}

		}

	}

	return response()->json($response);

}



public function deletePaymentMode($id){

	$response = array();

	$payment_mode = \App\PaymentMode::where('id',$id)->where('is_active',1)->first();

	if(is_null($payment_mode)){

		$response['flag'] = false;

		$response['message'] = "Payment Mode Not Found";

	}else{

		$payment_mode->is_active = 0;

		if($payment_mode->save()){

			$response['flag'] = true;

			$response['message'] = "Payment Mode Deleted";

		}else{

			$response['flag'] = false;

			$response['message'] = "Failed to delete";

		}

	}

	return response()->json($response);

}

public function locations(){

	$data = array();

	$data['locations'] = \App\Location::where('is_active',1)->get();

	return view('dashboard.master.locations',$data);

}

public function addLocation(Request $request){

	$response = array();

	$validator = \Validator::make($request->all(),

		array(

			'location_id' =>'required|unique:locations,location_id',

			'name' =>'required|unique:locations,name',

			'district' =>'required|unique:locations,district',

		)

	);



	if($validator->fails())

	{

		$response['flag'] = false;

		$response['errors'] = $validator->getMessageBag();

	}else{

		$location =  new \App\Location();

		$location->location_id = $request->location_id;

		$location->name = $request->name;

		$location->district = $request->district;

		if($location->save()){

			$response['flag'] = true;

			$response['message'] = "Location Added Successfully";

		}else{

			$response['flag'] = false;

			$response['error'] = "Something Went Wrong";

		}

		

	}

	return response()->json($response);

}

public function getEditLocation($id){

	$data = array();

	$data['location'] = \App\Location::where('id',$id)->where('is_active',1)->first();

	return view('dashboard.master.edit-location',$data);

} 

public function updateLocation(Request $request){

	$response = array();

	$validator = \Validator::make($request->all(),

		array(

			'location_id' =>'required|unique:locations,location_id,'.$request->id,

			'name' =>'required|unique:locations,name,'.$request->id,

			'district' =>'required|unique:locations,district,'.$request->id,

		)

	);

	if($validator->fails())

	{

		$response['flag'] = false;

		$response['errors'] = $validator->getMessageBag();

	}else{

		$location_details =  \App\Location::where('id',$request->id)->where('is_active',1)->first();

		if(is_null($location_details)){

			$response['flag'] = false;

			$response['error'] = "Location Not found";

		}else{

			$location_details->location_id = $request->location_id;

			$location_details->name = $request->name;

			$location_details->district = $request->district;

			if($location_details->save()){

				$response['flag'] = true;

				$response['message'] = "Location Updated Successfully";

			}else{

				$response['flag'] = false;

				$response['error'] = "Something Went Wrong";

			}

			

		}

	}

	return response()->json($response);

}

public function deleteLocation($id){

	$response = array();

	$location = \App\Location::where('id',$id)->where('is_active',1)->first();

	if(is_null($location)){

		$response['flag'] = false;

		$response['message'] = "Product Company Not Found";

	}else{

		$location->is_active = 0;

		if($location->save()){

			$response['flag'] = true;

			$response['message'] = "Location Deleted";

		}else{

			$response['flag'] = false;

			$response['message'] = "Failed to delete";

		}

	}

	return response()->json($response);

}





}


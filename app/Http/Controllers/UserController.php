<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;

class UserController extends Controller
{
	public function users(){
		$data = array();
		$data['users'] = \App\User::where('is_active',1)->where('id','<>',\Auth::user()->id)->with('role')->get();
		return view('dashboard.user.index',$data);
	} 
	public function getAddUser(){
		$data['roles'] = \App\Role::where('is_active',1)->get();
		return view('dashboard.user.create',$data);
	} 

	public function postAddUser(Request $request){
		$response = array();
		$validator = \Validator::make($request->all(),
			array(
				'role' =>'required',
				'name' =>'required',
				'email' =>'required|email|unique:users,email',
				'password' =>'required',
			)
		);

		if($validator->fails())
		{
			$response['flag'] = false;
			$response['errors'] = $validator->getMessageBag();
		}else{
			$user =  new \App\User();
			$user->role_id = $request->role;
			$user->name = $request->name;
			$user->email = $request->email;
			$user->password = \Hash::make($request->password);
			$user->plain_password = $request->password;
			if($user->save()){
				$response['flag'] = true;
				$response['user'] = $user;
				$response['message'] = "User Created Successfully";
			}else{
				$response['flag'] = false;
				$response['error'] = "Something Went Wrong";
			}
		}
		return response()->json($response);
	}
	
	public function getEditUser($id){
		$user = \App\User::where('id',$id)->where('is_active',1)->first();
		if(is_null($user)){
			return redirect('user/users')->with('error','User not found');
		}else{
			$data['user'] = $user;
			$data['roles'] = \App\Role::where('is_active',1)->get();
			return view('dashboard.user.edit',$data);
		}
	} 

	public function postEditUser(Request $request){
		$response = array();
		$validator = \Validator::make($request->all(),
			array(
				'role' =>'required',
				'name' =>'required',
				'email' =>'required|email|unique:users,email,'.$request->id,
				'password' =>'required',
			)
		);
		if($validator->fails())
		{
			$response['flag'] = false;
			$response['errors'] = $validator->getMessageBag();
		}else{
			$user =  \App\User::where('id',$request->id)->where('is_active',1)->first();
			if(is_null($user)){
				$response['flag'] = false;
				$response['error'] = "User Not found";
			}else{
				$user->role_id = $request->role;
				$user->name = $request->name;
				$user->email = $request->email;
				$user->password = \Hash::make($request->password);
				$user->plain_password = $request->password;
				if($user->save()){
					$response['flag'] = true;
					$response['message'] = "User Updated Successfully";
				}else{
					$response['flag'] = false;
					$response['error'] = "Something Went Wrong";
				}
			}
		}
		return response()->json($response);
	}
       public function retailers_filter(Request $request){
				$data = array();
					

						


				
					$retailers = \App\Retailer::where('is_active',1)->get();




			
			


				return Datatables::of($retailers)
				->addIndexColumn()
					->addColumn('action', function($retailers){
					
                                

							$btn ='<td>';
							
								$btn=$btn.'<div class="hidden-sm hidden-xs btn-group">
											<a class="btn btn-xs btn-info" onclick="getEdit('.$retailers->id.')" >
												<i class="ace-icon fa fa-pencil bigger-120"></i>
											</a>

											<button class="btn btn-xs btn-danger" onclick="deleteRetailer('.$retailers->id.')" >
												<i class="ace-icon fa fa-trash-o bigger-120"></i>
											</button>
										</div>';
						

						
												
						$btn=$btn.'</td>';
					

						return $btn;
				})

					->addColumn('dealer_name', function($retailers){
				
				
					$btn =' <td>';
					$btn=$btn.'<b>'.getdealer($retailers->dealer_id)->name.'</b> &nbsp; &nbsp ['.$retailers->dealer_id.']';
					
					$btn=$btn.'</td>';
				
				return $btn;
			})

			
				->rawColumns(['action','dealer_name'])
				
				
				->make(true);
	}	
	
	public function retailers(){
		$data = array();
		// $data['retailers'] = \App\Retailer::where('is_active',1)->get();
		$data['dealers'] = \App\Dealer::where('is_active',1)->get();
		$data['locations'] = \App\Location::where('is_active',1)->get();
		
		return view('dashboard.master.retailers',$data);
	} 

	public function addRetailers(Request $request){
		$response = array();
		$validator = \Validator::make($request->all(),
			array(
				// 'name' =>'required|unique:dealers,name',
				'name' =>'required',
				'dealer_id' =>'required',
				'unique_code'=>'required|unique:retailers,unique_code',
				'location'=>'required'
			)
		);

		if($validator->fails())
		{
			$response['flag'] = false;
			$response['errors'] = $validator->getMessageBag();
		}else{
			//dd($request->all());
			$retailer =  new \App\Retailer();

			$retailer->name = $request->name;
			if($request->hindi_name){
				$retailer->hindi_name = $request->hindi_name;
			}
			else{
				$retailer->hindi_name = $request->name;
			}
			$retailer->district = $request->district;
			$retailer->address = $request->address;
			$retailer->address2 = $request->address2;
			if($request->hindi_address){
				$retailer->hindi_address = $request->hindi_address;
			}
			else{
				$retailer->hindi_address = $request->name;
			}
			$retailer->mobile_number = $request->mobile_number;
			$retailer->destination_code = $request->location;
			$retailer->unique_code = $request->unique_code;
			$retailer->gst_number = $request->gst_number;
			$retailer->dealer_id = $request->dealer_id;
			if($retailer->save()){
				$response['flag'] = true;
				$response['message'] = "Retailer Added Successfully";
			}else{
				$response['flag'] = false;
				$response['error'] = "Something Went Wrong";
			}
		}
		return response()->json($response);
	}

	public function getEditRetailer($id){
		$data = array();
		$data['retailer'] = \App\Retailer::where('id',$id)->where('is_active',1)->first();
		$data['dealers'] = \App\Dealer::where('is_active',1)->get();
		$data['locations'] = \App\Location::where('is_active',1)->get();
		return view('dashboard.master.edit-retailer',$data);
	}

	public function updateRetailer(Request $request){
		$response = array();
		$validator = \Validator::make($request->all(),
			array(
				'name' =>'required',
				'dealer_id' =>'required',
				'unique_code'=>'required',
				'location'=>'required'
			)
		);
		if($validator->fails())
		{
			$response['flag'] = false;
			$response['errors'] = $validator->getMessageBag();
		}else{
			$retailer =  \App\Retailer::where('id',$request->id)->where('is_active',1)->first();


			if(is_null($retailer)){
				$response['flag'] = false;
				$response['error'] = "Retailer Not found";
			}else{
				$retailer->unique_code = $request->unique_code;
				$retailer->destination_code = $request->location;
				$retailer->name = $request->name;
				$retailer->hindi_name = $request->hindi_name;
				$retailer->district = $request->district;
				$retailer->address = $request->address;
				$retailer->address2 = $request->address2;
				if($request->hindi_address){
					$retailer->hindi_address = $request->hindi_address;
				}
				else{
					$retailer->hindi_address = $request->name;
				}
				$retailer->mobile_number = $request->mobile_number;
				$retailer->gst_number = $request->gst_number;
				$retailer->dealer_id = $request->dealer_id;
				if($retailer->save()){
					$response['flag'] = true;
					$response['message'] = "Retailer Updated Successfully";
					$response['response'] = $request->all();
				}else{
					$response['flag'] = false;
					$response['error'] = "Something Went Wrong";
				}
			}
		}
		return response()->json($response);
	} 

	public function deleteRetailer($id){
		$response = array();
		$retailer = \App\Retailer::where('id',$id)->where('is_active',1)->first();
		if(is_null($retailer)){
			$response['flag'] = false;
			$response['message'] = "retailer Not Found";
		}else{
			$retailer->is_active = 0;
			if($retailer->save()){
				$response['flag'] = true;
				$response['message'] = "Retailer Deleted";
			}else{
				$response['flag'] = false;
				$response['message'] = "Failed to delete";
			}
		}
		return response()->json($response);
	}



	public function exportRetailer(Request $request){
		$retailerExcel = \App\Retailer::select('*')->where('is_active',1)->get();

		\Excel::create('Retailer Lists', function($excel) use($retailerExcel){
			$excel->sheet('Retailers', function($sheet) use($retailerExcel) {
				$sheet->fromArray($retailerExcel);
			});
		})->export('xls');
	}
	public function importRetailer(Request $request){
		if($request->hasFile('retailer_file'))
		{
			$extension = \File::extension($request->file('retailer_file')->getClientOriginalName());
			if ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {
				\Excel::load($request->file('retailer_file'), function($reader) {
					foreach ($reader->toArray() as $sheets) {
						
						if(count($sheets) > 0){
							foreach ($sheets as $key => $sheet) {
								// echo "<pre>";
								// print_r($sheet['id']);
								// exit;
								$retailer = \App\Retailer::where('id',(int)$sheet['id'])->first();
								if(is_null($retailer)){
									$retailer = new \App\Retailer();				
									$retailer->name = addslashes($sheet['name']);
									$retailer->hindi_name = $sheet['hindi_name'];
									$retailer->address = $sheet['address'];
									$retailer->hindi_address = $sheet['hindi_address'];
									$retailer->mobile_number = $sheet['mobile_number'];
									$retailer->gst_number = $sheet['gst_number'];

								}else{
									$retailer->name = addslashes($sheet['name']);
									$retailer->hindi_name = $sheet['hindi_name'];
									$retailer->address = $sheet['address'];
									$retailer->hindi_address = $sheet['hindi_address'];
									$retailer->mobile_number = $sheet['mobile_number'];
									$retailer->gst_number = $sheet['gst_number'];
								}

								// $retailer->save();
							}
						}
					}
					exit;
				});
				return redirect('user/retailers')->with('save','Imported Successfully');
			}else{
				return redirect('user/retailers')->with('error','Invalid File type');
			}
		}else{
			return redirect('user/retailers')->with('error','File Required');
		}
	}


}

<?php 
function getUserById($user_id){
	$user = \App\User::find($user_id);
	return $user;
}

function getWarehouseDaywiseProducts($product_company_id,$warehouse_id,$product_id,$date){
	$inward_inventory = \App\ProductLoading::where('product_company_id',$product_company_id)
	->where('warehouse_id',$warehouse_id)
	->where('product_id',$product_id)
	->whereDate('created_at', '=', date('Y-m-d',strtotime($date)))
	->sum('quantity');

	$outward_inventory = \App\ProductLoading::where('product_company_id',$product_company_id)
	->where('from_warehouse_id',$warehouse_id)
	->where('product_id',$product_id)
	->whereDate('created_at', '=', date('Y-m-d',strtotime($date)))
	->sum('quantity');

	$actual_inventory = $inward_inventory - $outward_inventory;


	return $actual_inventory;
}

function totalTokenLoading($token_id){
	$total_loading = \App\ProductLoading::where('token_id',$token_id)
	->sum('quantity');
	return $total_loading;
}

function getInventory($product_brand_id,$warehouse_id,$product_id){
	$inventory = \App\Inventory::where('product_brand_id',$product_brand_id)
	->where('warehouse_id',$warehouse_id)
	->where('product_id',$product_id)
	->sum('quantity');
	return $inventory;
}

function getOpeningInventory($product_brand_id,$warehouse_id,$product_id){
	$inventory = \App\OpeningInventory::where('product_brand_id',$product_brand_id)
	->where('warehouse_id',$warehouse_id)
	->where('product_id',$product_id)
	->sum('quantity');
	return $inventory;
}
function getPartyOpeningInventoryByProductAndBrand($party_id,$product_id,$product_brand_id,$type){
	$inventory = \App\OpeningInventory::where($type,$party_id)
	->where('product_id',$product_id)
	->where('product_brand_id',$product_brand_id)
	->sum('quantity');
	return $inventory;
}

function getPartyInventoryByProductAndBrand($party_id,$product_id,$product_brand_id,$type){
	$inventory = \App\Inventory::where($type,$party_id)
	->where('product_id',$product_id)
	->where('product_brand_id',$product_brand_id)
	->sum('quantity');
	return $inventory;
}

function getOtherPartyInventoryByProductAndBrand($product_id,$product_brand_id){

	$inventory_unique_dealers = \App\Inventory::select('dealer_id')->distinct()->where('dealer_id','!=', null)->get();
	$total = 0;
	foreach ($inventory_unique_dealers as $inventory_unique_dealer) {
		$dealer = \App\Dealer::where('id',$inventory_unique_dealer->dealer_id)->first();
		
		if(!$dealer->show_separate_report){
			$inventory = \App\Inventory::where('product_id',$product_id)
			->where('product_brand_id',$product_brand_id)
			->where('dealer_id',$inventory_unique_dealer->dealer_id)
			->sum('quantity');
			$total = $total + $inventory;
		}
	}
	return $total;
}

function getOtherPartyOpeningInventoryByProductAndBrand($product_id,$product_brand_id){

	$inventory_unique_dealers = \App\OpeningInventory::select('dealer_id')->distinct()->where('dealer_id','!=', null)->get();
	$total = 0;
	foreach ($inventory_unique_dealers as $inventory_unique_dealer) {
		$dealer = \App\Dealer::where('id',$inventory_unique_dealer->dealer_id)->first();
		
		if(!$dealer->show_separate_report){
			$inventory = \App\OpeningInventory::where('product_id',$product_id)
			->where('product_brand_id',$product_brand_id)
			->where('dealer_id',$inventory_unique_dealer->dealer_id)
			->sum('quantity');
			$total = $total + $inventory;
		}
	}
	return $total;
}

function getInventoryProductQuantity($type,$party_id,$product_id,$product_brand_id){
	$inventory = \App\Inventory::where($type,$party_id)
	->where('product_id',$product_id)
	->where('product_brand_id',$product_brand_id)
	->sum('quantity');
	return $inventory;
}

function getBufferInventoryProductQuantity($type,$party_id,$product_id,$product_brand_id){
	$inventory = \App\Inventory::where($type,$party_id)
	->where('product_id',$product_id)
	->where('product_brand_id',$product_brand_id)
	->where('warehouse_id',24)
	->sum('quantity');
	return $inventory;
}
function getDealerTotalLoadingQuantity($dealer_id,$master_rake_id,$product_id){
	$product_loading = \App\ProductLoading::where('dealer_id',$dealer_id)->where('master_rake_id',$master_rake_id)->where('product_id',$product_id)->sum('quantity');
	if(is_null($product_loading)){
		return 0;
	}else{
		return $product_loading;
	}
}

function monthlyAverageLabourRate($month,$year,$type){
	
	if($type == "loading"){
		$result = \App\LabourPayments::where('rate','>',0)->whereMonth('created_at','=',$month)->whereYear('created_at','=',$year)->avg('rate');
	}else if($type == "unloading"){
		$result = \App\UnloadingLabourPayment::where('rate','>',0)->whereMonth('created_at','=',$month)->whereYear('created_at','=',$year)->avg('rate');
	}else{
		$result = \App\WagonUnloading::where('wagon_rate','>',0)->whereMonth('created_at','=',$month)->whereYear('created_at','=',$year)->avg('wagon_rate');
	}
	return $result;

}

function monthlyAverageFreight($month,$year){
	
	$result = \App\ProductLoading::whereNotNull('freight')->where('freight','>',0)->whereMonth('created_at','=',$month)->whereYear('created_at','=',$year)->avg('freight');
	return $result;

}
function monthlyAverageDemurrage($month,$year){
	
	$result = \App\MasterRake::whereNotNull('demurrage')->where('demurrage','>',0)->whereMonth('created_at','=',$month)->whereYear('created_at','=',$year)->avg('demurrage');
	return $result;

}
function monthlyAverageWharfage($month,$year){
	
	$result = \App\MasterRake::whereNotNull('wharfage')->where('wharfage','>',0)->whereMonth('created_at','=',$month)->whereYear('created_at','=',$year)->avg('wharfage');
	return $result;

}

function getModelById($modal,$id){
	$modal = "\App\\".$modal;
	$result = $modal::find($id);
	if($result!=null){
		return $result;
		}else{
			return new $modal;
		}
}



function getdealer($id){
	$result = \App\Dealer::where('unique_id',$id)->first();
	if($result == null){
		return new \App\Dealer();
	}else{
		return $result;
	}
	
}

function getretailer($id){
	$result = \App\Retailer::where('unique_code',$id)->first();
	if($result == null){
		return new \App\Retailer();
	}else{
        return $result;
	}
	
}

function getSubmoduleByModule($role_id,$module_id){
	$sub_menus = \App\RoleModuleAssociation::with('sub_module')
	->where('role_id',$role_id)
	->where('module_id',$module_id)
	->get();
	return $sub_menus;
}

function getIndianCurrency(float $number)
{
	$decimal = round($number - ($no = floor($number)), 2) * 100;
	$hundred = null;
	$digits_length = strlen($no);
	$i = 0;
	$str = array();
	$words = array(0 => '', 1 => 'one', 2 => 'two',
		3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
		7 => 'seven', 8 => 'eight', 9 => 'nine',
		10 => 'ten', 11 => 'eleven', 12 => 'twelve',
		13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
		16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
		19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
		40 => 'forty', 50 => 'fifty', 60 => 'sixty',
		70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
	$digits = array('', 'hundred','thousand','lakh', 'crore');
	while( $i < $digits_length ) {
		$divider = ($i == 2) ? 10 : 100;
		$number = floor($no % $divider);
		$no = floor($no / $divider);
		$i += $divider == 10 ? 1 : 2;
		if ($number) {
			$plural = (($counter = count($str)) && $number > 9) ? 's' : null;
			$hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
			$str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
		} else $str[] = null;
	}
	$Rupees = implode('', array_reverse($str));
	$paise = ($decimal) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
	return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise;
}

function calculateTimeSpan($date){
	$seconds  = strtotime(date('Y-m-d H:i:s')) - strtotime($date);

	$months = floor($seconds / (3600*24*30));
	$day = floor($seconds / (3600*24));
	$hours = floor($seconds / 3600);
	$mins = floor(($seconds - ($hours*3600)) / 60);
	$secs = floor($seconds % 60);

	if($seconds < 60){
		$time = $secs." sec ago";
	}
	else if($seconds < 60*60 ){
		$time = $mins." min ago";
	}
	else if($seconds < 24*60*60){
		$time = $hours." hrs ago";
	}
	else if($seconds < 24*60*60*30){
		$time = $day." day ago";
	}
	else{
		$time = $months." month ago";
	}
	return $time;
}

function keyFromSlug($slug,$delimiter){

	$tmp = explode($delimiter, $slug);
	return ucfirst(implode(' ', $tmp));
}

function setOpen($path)
{
	return Request::is($path) ? 'open' : '';
}
function setActive($path)
{
	return Request::is($path) ? 'active' : '';
}

function truckNoGet($modal,$order_id){
	$modal = "\App\\".$modal;
	$result = $modal::where('order_id',$order_id)->first();
	return $result;
}

?>
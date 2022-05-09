<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReturnedProduct extends Model
{
	public function retailer(){
		return $this->belongsTo('\App\Retailer');
	}
	public function dealer(){
		return $this->belongsTo('\App\Dealer');
	}
	public function product(){
		return $this->belongsTo('\App\Product');
	}
	
	public function product_company(){
		return $this->belongsTo('\App\ProductCompany');
	}
	public function product_brand(){
		return $this->belongsTo('\App\ProductCompany');
	}

	public function unit(){
		return $this->belongsTo('\App\Unit');
	}
	public function warehouse(){
		return $this->belongsTo('\App\Warehouse');
	}
	
	public function transporter(){
		return $this->belongsTo('\App\Transporter');
	}
	
	
}

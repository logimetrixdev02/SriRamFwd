<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
	public function company(){
		return $this->belongsTo('\App\Company');
	}
	public function from_warehouse(){
		return $this->belongsTo('\App\Warehouse');
	}
	public function master_rake(){
		return $this->belongsTo('\App\MasterRake')->with('product_company');
	}
	public function dealer(){
		return $this->belongsTo('\App\Dealer','account_from_id');
	}
	public function product(){
		return $this->belongsTo('\App\Product');
	}
	public function unit(){
		return $this->belongsTo('\App\Unit');
	}
	public function product_company(){
		return $this->belongsTo('\App\ProductCompany');
	}
	public function transporter(){
		return $this->belongsTo('\App\Transporter');
	}
	public function warehouse(){
		return $this->belongsTo('\App\Warehouse');
	}
	public function retailer(){
		return $this->belongsTo('\App\Retailer');
	}
	public function product_loadings(){
		return $this->hasMany('\App\ProductLoading');
	}
}

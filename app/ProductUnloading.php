<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductUnloading extends Model
{
	public function product(){
		return $this->belongsTo('\App\Product');
	}
	public function dealer(){
		return $this->belongsTo('\App\Dealer');
	}
	public function token(){
		return $this->belongsTo('\App\Token','token_id')->with('unit');
	}
	public function master_rake(){
		return $this->belongsTo('\App\MasterRake','master_rake_id');
	}
	public function unit(){
		return $this->belongsTo('\App\Unit');
	}
	public function warehouse(){
		return $this->belongsTo('\App\Warehouse');
	}
	public function product_loading(){
		return $this->belongsTo('\App\ProductLoading');
	}
	public function unloading_labour_payment(){
		return $this->hasOne('\App\UnloadingLabourPayment');
	}
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MasterRake extends Model
{
	public function session(){
		return $this->belongsTo('\App\Session');
	}
	public function product_company(){
		return $this->belongsTo('\App\ProductCompany');
	}
	public function tokens(){
		return $this->hasMany('\App\Token','master_rake_id')->with('product_loadings');
	}
	public function master_rake_products(){
		return $this->hasMany('\App\MasterRakeProduct','master_rake_id')->with('product');
	}
	public function rake_allotments(){
		return $this->hasMany('\App\RakeProductAllotment','master_rake_id')->with('dealer')->groupBy('dealer_id');
	}

	public function users(){
		return $this->belongsTo('\App\User');
	}
	
}

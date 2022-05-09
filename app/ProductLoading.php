<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductLoading extends Model
{
	public function product(){
		return $this->belongsTo('\App\Product');
	}
	public function from_warehouse(){
		return $this->belongsTo('\App\Warehouse');
	}
	public function retailer(){
		return $this->belongsTo('\App\Retailer');
	}
	public function token(){
		return $this->belongsTo('\App\Token','token_id')->with('unit');
	}
	public function dealer(){
		return $this->belongsTo('\App\Dealer');
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
	
	public function labour_payment(){
		return $this->hasOne('\App\LabourPayments');
	}
	public function loading_slip_invoice(){
		return $this->hasOne('\App\LoadingSlipInvoice','loading_slip_id');
	}
	public function transporter(){
		return $this->belongsTo('\App\Transporter');
	}
	public function product_company(){
		return $this->belongsTo('\App\ProductCompany');
	}

	public function userinfo(){
		return $this->belongsTo('\App\User','user_id');
	}
}

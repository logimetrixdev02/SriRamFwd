<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyDi extends Model
{
	public function master_rake(){
		return $this->belongsTo('\App\MasterRake');
	}public function product_company(){
		return $this->belongsTo('\App\ProductCompany');
	}public function dealer(){
		return $this->belongsTo('\App\Dealer');
	}
}

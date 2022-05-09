<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WarehouseDi extends Model
{
	public function product_company(){
		return $this->belongsTo('\App\ProductCompany');
	}public function dealer(){
		return $this->belongsTo('\App\Dealer');
	}
}

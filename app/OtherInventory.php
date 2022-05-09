<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OtherInventory extends Model
{
	public function dealer()
	{
		return $this->belongsTo('\App\Dealer');
	}
	public function product_brand()
	{
		return $this->belongsTo('\App\ProductCompany');
	}
	public function product_company()
	{
		return $this->belongsTo('\App\ProductCompany');
	}
	public function product()
	{
		return $this->belongsTo('\App\Product');
	}
	public function warehouse()
	{
		return $this->belongsTo('\App\Warehouse');
	}
	public function unit()
	{
		return $this->belongsTo('\App\Unit');
	}
	
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WarehouseTransferUnloading extends Model
{
	public function product(){
		return $this->belongsTo('\App\Product');
	}
	public function warehouse_transfer_loading(){
		return $this->belongsTo('\App\WarehouseTransferLoading');
	}
	public function unit(){
		return $this->belongsTo('\App\Unit');
	}
	public function from_warehouse(){
		return $this->belongsTo('\App\Warehouse');
	}
	public function to_warehouse(){
		return $this->belongsTo('\App\Warehouse');
	}
	public function transporter(){
		return $this->belongsTo('\App\Transporter');
	}
	public function product_brand(){
		return $this->belongsTo('\App\ProductCompany');
	}
}

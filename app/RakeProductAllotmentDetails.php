<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RakeProductAllotmentDetails extends Model
{
    public function rake_product_allotment(){
		return $this->belongsTo('\App\RakeProductAllotment','rake_product_allotment_id');
	}
	public function product(){
		return $this->belongsTo('\App\Product');
	}
	public function unit(){
		return $this->belongsTo('\App\Unit');
	}
}

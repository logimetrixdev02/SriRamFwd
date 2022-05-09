<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MasterRakeProduct extends Model
{
	public function product(){
		return $this->belongsTo('\App\Product');
	}
	public function master_rake(){
		return $this->belongsTo('\App\MasterRake');
	}
	
}

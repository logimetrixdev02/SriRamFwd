<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UnloadingLabourPayment extends Model
{
	public function product(){
		return $this->belongsTo('\App\Product');
	}
	public function token(){
		return $this->belongsTo('\App\Token','token_id')->with('unit');
	}
	public function master_rake(){
		return $this->belongsTo('\App\MasterRake','master_rake_id');
	}
}

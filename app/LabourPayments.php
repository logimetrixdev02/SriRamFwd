<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LabourPayments extends Model
{
	public function product(){
		return $this->belongsTo('\App\Product');
	}
	// public function hindi_product(){
	// 	return $this->belongsTo('\App\Product')->select('hindi_name');
	// }
	public function token(){
		return $this->belongsTo('\App\Token','token_id')->with('unit');
	}
	public function master_rake(){
		return $this->belongsTo('\App\MasterRake','master_rake_id');
	}
	
}

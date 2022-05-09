<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WagonUnloading extends Model
{
	public function master_rake(){
		return $this->belongsTo('\App\MasterRake');
	}
	public function product(){
		return $this->belongsTo('\App\Product');
	}public function user(){
		return $this->belongsTo('\App\User','unloaded_by');
	}
	
}

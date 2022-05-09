<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rake extends Model
{
	public function master_rake(){
		return $this->belongsTo('\App\MasterRake');
	}
}

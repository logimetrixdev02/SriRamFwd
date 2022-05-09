<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
class DirectLabourPayment extends Model
{
	public function master_rake(){
		return $this->belongsTo('\App\MasterRake');
	}
	public function warehouse(){
		return $this->belongsTo('\App\Warehouse');
	}
}
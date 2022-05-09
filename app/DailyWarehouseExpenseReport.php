<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DailyWarehouseExpenseReport extends Model
{
	public function user()
	{
		return $this->belongsTo('App\User','generated_by');
	}
}

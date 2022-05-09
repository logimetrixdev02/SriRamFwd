<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RakeExpenseReport extends Model
{
	public function user()
	{
		return $this->belongsTo('App\User','generated_by');
	}
}

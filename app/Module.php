<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
	public function sub_modules(){
		return $this->hasMany('\App\SubModule');
	}
}

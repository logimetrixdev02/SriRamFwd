<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
	public function company_sms_setting(){
		return $this->hasOne('\App\CompanySmsSetting');
	}
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyInvoicePayment extends Model
{
	public function bank_account(){
		return $this->belongsTo('\App\BankAccount')->with('bank');
	}
}

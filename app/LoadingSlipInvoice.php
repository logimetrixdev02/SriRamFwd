<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoadingSlipInvoice extends Model
{
	public function company(){
		return $this->belongsTo("\App\Company");
	}
	public function product_loading(){
		return $this->belongsTo("\App\ProductLoading",'loading_slip_id');
	}
	public function retailer(){
		return $this->belongsTo("\App\Retailer");
	}
	public function dealer(){
		return $this->belongsTo("\App\Dealer");
	}
	public function product(){
		return $this->belongsTo("\App\Product",'product_id');
	}
	public function product_details(){
		return $this->belongsTo("\App\Product",'product_id');
	}
	public function retailer_invoice_payments(){
		return $this->hasMany('\App\PartyInvoicePayment','invoice_id');
	}
}
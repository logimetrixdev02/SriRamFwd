<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
class RetailerSmsReport extends Model
{
    public function retailer(){
        return $this->belongsTo('\App\Retailer');
    }
}
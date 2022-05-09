<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
class RakeAllotmentHistory extends Model
{
    public function master_rake(){
        return $this->belongsTo('\App\MasterRake');
    }
    public function product(){
        return $this->belongsTo('\App\Product');
    }
    public function dealer(){
        return $this->belongsTo('\App\Dealer');
    }
    public function unit(){
        return $this->belongsTo('\App\Unit');
    }

}

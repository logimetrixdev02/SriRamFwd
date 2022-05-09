<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoleModuleAssociation extends Model
{
    
    public function module(){
    	return $this->belongsTo('\App\Module');
    }
    public function sub_module(){
    	return $this->belongsTo('\App\SubModule');
    }
}

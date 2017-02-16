<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaveReport extends Model
{
    //
    protected $fillable = [
	 'updated_at',
	 'name',
	 'ac_user_id',
	 'url'
    ];

   public function getNameAttribute($value) {
        return strtoupper($value);
    }

    public function getUrlAttribute($value){
    	$arr = json_decode($value, true);
    	$arrpp = "http://localhost:8888/report?".
                 "start=".$arr['ac_start'].
                 "&end=".$arr['ac_end'].
                 "&client_id=".$arr['c_id'].
                 "&project_id=".$arr['p_id']. 
                 "&user_id=".$arr['u_id'];
    	         return ($arrpp);
    }
}

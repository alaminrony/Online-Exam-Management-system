<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Grade extends Model {
	
        public $timestamps = true;
        
        public static function boot(){
            parent::boot();
            static::creating(function($post){
                $post->created_by = $post->updated_by = Auth::user()->id;
            });

            static::updating(function($post){
                $post->updated_by = Auth::user()->id;
            });
                       
        }
        
}
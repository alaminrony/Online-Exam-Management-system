<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Department extends Model {
    
    use SoftDeletes;
    
    protected $primaryKey = 'id';
    protected $table = 'department';
    public $timestamps = true;
    protected $dates = ['deleted_at'];

    public static function boot() {
        parent::boot();
        static::creating(function($post) {
            $post->created_by = Auth::user()->id;
            $post->updated_by = Auth::user()->id;
        });

        static::updating(function($post) {
            $post->updated_by = Auth::user()->id;
        });
        
    }
    
    public function scopeStatus($query, $status){
        return $query->where('status', $status);
    }

}

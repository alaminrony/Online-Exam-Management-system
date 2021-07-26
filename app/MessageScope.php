<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth; 

class MessageScope extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'message_scope';
    public $timestamps = false;

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
}

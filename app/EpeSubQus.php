<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class EpeSubQus extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'epe_sub_to_question';
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

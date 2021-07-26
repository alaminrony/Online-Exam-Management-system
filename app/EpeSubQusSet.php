<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class EpeSubQusSet extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'epe_sub_qus_set';
    public $timestamps = true;

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

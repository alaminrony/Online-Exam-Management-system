<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class SubjectToCi extends Model {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'subject_to_ci';
    public $timestamps = true;

    public static function boot() {
        parent::boot();
        static::creating(function($post) {
            $post->updated_by = Auth::user()->id;
        });

        static::updating(function($post) {
            $post->updated_by = Auth::user()->id;
        });
    }

}

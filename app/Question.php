<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Question extends Model {
    
    use SoftDeletes;

    protected $table = 'question';
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
    
    public function questionType() {
        return $this->belongsTo('App\QuestionType', 'type_id');
    }
    
    public function subject() {
        return $this->belongsTo('App\Subject', 'subject_id');
    }

}

<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Tae extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tae';
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

    public function course() {
        return $this->belongsTo('Course', 'course_id');
    }
    
    public function part() {
        return $this->belongsTo('Part', 'part_id');
    }
    
    public function subject() {
        return $this->belongsTo('Subject', 'subject_id');
    }
    
    public function phase() {
        return $this->belongsTo('Phase', 'phase_id');
    }

    public function taeDetail() {
        return $this->hasMany('TaeDetail', 'tae_id', 'id');
    }

    public function marksDistribution() {
        return $this->belongsTo('MarksDistribution', 'marks_distribution_id');
    }
    
}

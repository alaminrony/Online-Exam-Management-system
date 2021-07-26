<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Epe extends Model {
    
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'epe';
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

    public function course() {
        return $this->belongsTo('App\Course', 'course_id');
    }

    public function part() {
        return $this->belongsTo('App\Part', 'part_id');
    }

    public function subject() {
        return $this->belongsTo('App\Subject', 'subject_id');
    }

    public function phase() {
        return $this->belongsTo('App\Phase', 'phase_id');
    }

    public function epeDetail() {
        return $this->hasMany('App\EpeDetail', 'epe_id', 'id');
    }

    public function epeQusTypeDetails() {
        return $this->hasMany('App\EpeQusTypeDetails', 'epe_id', 'id');
    }

    public function marksDistribution() {
        return $this->belongsTo('App\MarksDistribution', 'marks_distribution_id');
    }

    public function syncOriginal() {
        $this->original = $this->attributes;
        return $this;
    }

}

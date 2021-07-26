<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Student extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'student_details';
        public $timestamps = true;
        
        public static function boot()
        {
            parent::boot();
            static::creating(function($post)
            {
                $post->created_by = Auth::user()->id;
                $post->updated_by = Auth::user()->id;
            });

            static::updating(function($post)
            {
                $post->updated_by = Auth::user()->id;
            });
        }
        
        public function user()
        {
            return $this->belongsTo('User', 'user_id', 'id');
        }
        
        public function course() {
            return $this->belongsTo('Course', 'course_id');
        }
        
        public function part() {
            return $this->belongsTo('Part', 'part_id');
        }

        
}
<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class EpeDetail extends Model {

    protected $table = 'epe_details';
    public $timestamps = false;
    
    
    public function course() {
        return $this->belongsTo('Course', 'course_id')->select('id', 'title', 'serial_no', 'course_session', 'from_date', 'to_date', 'weeks', 'status');
    }
    
    public function part() {
        return $this->belongsTo('Part', 'part_id')->select('id', 'title', 'details');
    }
    
    public function phase() {
        return $this->belongsTo('Phase', 'phase_id')->select('id', 'part_id', 'name', 'full_name');
    }
    
    public function branch() {
        return $this->belongsTo('Branch', 'branch_id')->select('id', 'name', 'short_name');
    }
}

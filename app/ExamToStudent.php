<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class ExamToStudent extends Model {

    protected $table = 'exam_to_student';
    public $timestamps = false;
}

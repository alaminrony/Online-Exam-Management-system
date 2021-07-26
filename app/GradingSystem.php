<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class GradingSystem extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'grading_system';
        public $timestamps =false;
        
}
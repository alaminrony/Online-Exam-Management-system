<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Status extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'status';
        public $timestamps = false;
        
}
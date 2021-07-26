<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Configuration extends Model {

	protected $table = 'configurations';
        public $timestamps = false;
        
}
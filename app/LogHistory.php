<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class LogHistory extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'history_logging';
    public $timestamps = false;

}

<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class EpeToQuestion extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'epe_to_question';
    public $timestamps = false;

}

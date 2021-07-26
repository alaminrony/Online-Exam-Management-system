<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class MockToQuestion extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'mock_to_question';
    public $timestamps = false;

}

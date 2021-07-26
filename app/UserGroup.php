<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;


class UserGroup extends Model {

    protected $primaryKey = 'id';
    protected $table = 'user_group';
    public $timestamps = false;

}

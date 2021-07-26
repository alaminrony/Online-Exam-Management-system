<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AclAccess extends Model {

    protected $primaryKey = 'id';
    protected $table = 'acl_access';
    public $timestamps = false;

    public static function boot() {
        parent::boot();
    }

}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AclModule extends Model {

    protected $primaryKey = 'id';
    protected $table = 'acl_module';
    public $timestamps = false;

    public static function boot() {
        parent::boot();
    }

}

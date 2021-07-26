<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AclModuleToAccess extends Model {

    protected $primaryKey = 'id';
    protected $table = 'acl_module_to_access';
    public $timestamps = false;

    public static function boot() {
        parent::boot();
    }

}

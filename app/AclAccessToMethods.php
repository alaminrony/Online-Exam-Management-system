<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AclAccessToMethods extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'acl_access_to_methods';
    public $timestamps = false;

    public static function boot() {
        parent::boot();
    }

}

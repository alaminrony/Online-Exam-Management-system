<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AclUserGroupToAccess extends Model {

    protected $primaryKey = 'id';
    protected $table = 'acl_user_group_to_access';
    public $timestamps = false;

    public static function boot() {
        parent::boot();
    }

}

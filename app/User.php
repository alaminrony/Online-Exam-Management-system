<?php

namespace App;

//use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Auth;

class User extends Authenticatable {

    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'password', 'remember_token', 'conf_password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    public $timestamps = true;
    protected $dates = ['deleted_at'];

    public static function boot() {
        parent::boot();
        static::creating(function($post) {
            $post->created_by = Auth::user()->id;
            $post->updated_by = Auth::user()->id;
        });

        static::updating(function($post) {
            $post->updated_by = Auth::user()->id;
        });
    }

    public function UserGroup() {
        return $this->belongsTo('App\UserGroup', 'group_id');
    }

    public function rank() {
        return $this->belongsTo('App\Rank', 'rank_id');
    }

    public function designation() {
        return $this->belongsTo('App\Designation', 'designation_id');
    }

    public function branch() {
        return $this->belongsTo('App\Branch', 'branch_id');
    }

    public function program() {
        return $this->belongsTo('App\Program', 'program_id');
    }

    //This function use for USERS/TAE/TAE_TO_STUSENT and other controller
    public function studentBasicInfo() {
        return $this->hasOne('App\Student', 'user_id');
    }
    
    public function department(){
        return $this->belongsTo('App\Department','department_id');
    }
}

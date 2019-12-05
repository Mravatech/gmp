<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use App\Models\UserProfile;
use App\Models\Education;
use App\Models\Experience;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'password', 'name', 'email', 'uuid', 'member_id', 'first_name', 'last_name', 'other_name', 'status'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'id'
    ];

    protected $with= ['']



    public function details()
    {
        return $this->hasOne(UserProfile::class, 'user_id', 'id');
    }


    public function education()
    {
        return $this->hasOne(Education::class, 'user_id', 'id');
    }


    public function experience()
    {
        return $this->hasOne(Experience::class, 'user_id', 'id');
    }
}

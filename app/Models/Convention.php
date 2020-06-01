<?php


namespace App\Models;


use App\Models\ConventionFees;
use App\Models\ConventionAccomodation;
use Illuminate\Database\Eloquent\Model;

class Convention extends Model
{

    protected $table = 'convention';
    protected $fillable = ['uuid', 'name', 'theme', 'year'];
    protected $hidden = ['id'];

    public function fees()
    {
        return $this->hasMany(ConventionFees::class, 'convention_id', 'id');
    }


    // public function accomodation()
    // {
    //     // return $this->hasOne(ConventionAccomodation::class, 'user_id', 'user_id');
    // }
}

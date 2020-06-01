<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ConventionAccomodation extends Model
{
    protected $table = 'convention_accomodation';
    protected $fillable = ['uuid', 'convention_id', 'name', 'price'];
}

<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ConventionFees extends Model
{
    protected $table = 'convention_fees';
    protected $fillable = ['uuid', 'convention_id', 'name', 'price'];
}

<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    protected $table = 'education';
    protected $fillable = [
        'uuid', 'user_id',  'institute',
        'qualification',
        'field_of_study',
        'year_of_graduation',
    ];
}

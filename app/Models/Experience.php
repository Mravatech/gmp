<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    protected $table = 'experience';
    protected $fillable = [
        'uuid',
        'user_id',
        'position_rank',
        'company',
        'location',
        'work_role',
        'start_date',
        'end_date',
        'description',
    ];
}

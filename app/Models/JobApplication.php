<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    protected $table = 'job_application';
    protected $fillable = [
        'uuid', 'user_id', 'job_id',
        'cv', 'cover', 'description'
    ];

}

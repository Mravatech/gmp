<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Jobs extends Model
{
    protected $table = 'jobs';
    protected $fillable = [
        'uuid', 'title',
        'description', 'company',
        'company_description', 'email',
        'deadline', 'status', 'gmp'
    ];

}

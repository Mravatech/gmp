<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'admin';
    protected $fillable = [
        'uuid',
        'email',
        'password',
        'first_name',
        'last_name',
        'other_name',
        'status'
    ];

    protected $hidden = ['id', 'password'];
}

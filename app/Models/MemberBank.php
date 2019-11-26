<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class MemberBank extends Model
{
    protected $table = 'members_bank';
    protected $fillable = ['uuid', 'member'];
}

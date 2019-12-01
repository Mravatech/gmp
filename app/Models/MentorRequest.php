<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class MentorRequest extends Model
{

    protected $table = 'mentor_request';
    protected $fillable = [
        'user_id', 'uuid', 'reason', 'preference', 'assigned_user',
        'status'
    ];
    protected $appends = ['assigned_user', 'user'];

    protected $hidden = ['user_id', 'assigned_user'];




    public function getAssignedMentorAttribute()
    {
        return User::where('id', $this->attributes['assigned_user'])->first();
    }

    public function getUserAttribute()
    {
        return User::where('id', $this->attributes['user_id'])->first();
    }
}

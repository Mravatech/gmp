<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{

    protected $table = 'users_profile';
    protected $hidden = ['id', 'user_id'];
    protected $fillable = [
        'uuid',
        'user_id',
        'member_id',
        'grade',
        'title',
        'marital_status',
        'dob',
        'gender',
        'phone_number',
        'address',
        'city',
        'state',
        'country',
        'postal_address',
        'chapter',
        'sector',
        'sub_sector',
        'occupation',
        'institute',
        'qualification',
        'field_of_study',
        'year_of_graduation',
        'position_rank',
        'company',
        'location',
        'work_role',
        'start_date',
        'end_date',
        'description'
    ];
}

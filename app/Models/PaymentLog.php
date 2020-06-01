<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class PaymentLog extends Model
{
    protected $table = 'payment_log';
    protected $fillable = ['uuid', 'user_id', 'type', 'amount', 'description', 'response_code', 'amount_paid', 'reference', 'status'];
}

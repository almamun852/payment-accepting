<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionHistory extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','transaction_id','payment_created_at','payment_updated_at','payment_date','amount','description','status'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id','id');
    }
}

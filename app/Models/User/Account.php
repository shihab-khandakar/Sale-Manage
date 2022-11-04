<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = ['user_id', 'account_number', 'bank_id', 'credit', 'debit', 'discount', 'note'];
}

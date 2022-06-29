<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'cust_email',
        'cust_name',
        'cust_phone',
        'cust_photo',
        'cust_address'
    ];
}

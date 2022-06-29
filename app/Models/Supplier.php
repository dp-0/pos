<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'supp_email',
        'supp_name',
        'supp_phone',
        'supp_photo',
        'supp_address'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier_Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'supp_trans_drcr',
        'supp_trans_amount',
        'supplier_id'
    ];

    public function supplier(){
        return $this->hasOne(Supplier::class,'id','supplier_id');
    }
}

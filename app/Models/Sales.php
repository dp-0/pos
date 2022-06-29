<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;
    protected $fillable = [
        'unrecust_name',
        'seles_price',
        'discount',
        'sales_stock',
        'remarks',
        'product_id',
        'customer_id',
        'drcr'
    ];

    public function product(){
        return $this->hasOne(Product::class,'id','product_id');
    }
    public function customer(){
        return $this->hasOne(Customer::class,'id','customer_id');
    }
}

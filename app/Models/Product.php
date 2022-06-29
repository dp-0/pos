<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'prod_name',
        'prod_barcode',
        'prod_selling_price',
        'prod_stock',
        'prod_purchase_stock',
        'supp_trans_id',
        'category_id',
        
    ];
    
    public function category(){
        return $this->hasOne(Category::class,'id','category_id');
    }
    public function supptrans(){
        return $this->hasOne(Supplier_Transaction::class,'id','supp_trans_id');
    }
}

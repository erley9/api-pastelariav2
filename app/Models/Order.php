<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'client_id',
        'product_id'
    ];

    public function client(){
        return $this->hasOne(Client::class,'id','client_id');
    }

    public function product(){
        return $this->hasOne(Product::class,'id','product_id');
    }
}

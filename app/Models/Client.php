<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Client extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phonenumber',
        'dateofbirth',
        'address',
        'complement',
        'neighborhood',
        'zipcode'
    ];

    public function products () {
        return $this->belongsToMany(
            Product::class,
            'orders',
            'client_id',
            'product_id'
        )
        ->withTimestamps()
        ->withPivot('created_at');
    }
}

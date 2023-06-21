<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'photo',
    ];

    public function clients () {
        return $this->belongsToMany(
            Client::class,
            'orders',
            'product_id',
            'client_id'
        )
        ->withTimestamps()
        ->withPivot('created_at');
    }
}

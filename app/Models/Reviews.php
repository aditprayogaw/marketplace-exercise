<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reviews extends Model
{
    use HasFactory;
    protected $guarded = [];

    /**
     * Relasi: Review dimiliki oleh satu Customer.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Relasi: Review merujuk ke satu Product.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
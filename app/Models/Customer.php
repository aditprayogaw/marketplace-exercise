<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relasi: Customer memiliki banyak Order.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Relasi Many-to-Many: Customer memiliki banyak Product di Wishlist (favorites).
     */
    public function favorites(): BelongsToMany
    {
        // Pivot table: 'favorites', foreign key lokal: 'customer_id', foreign key relasi: 'product_id'
        return $this->belongsToMany(Product::class, 'favorites', 'customer_id', 'product_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];
    // protected $fillable = ['name', 'description', 'price', 'stock', 'status', 'vendor_id', 'category_id'];

    // Relasi ke Vendor
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    // Relasi ke Kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi ke Review
    public function reviews()
    {
        return $this->hasMany(Reviews::class);
    }

    // Relasi Wishlist (Customer)
    public function favoritedByCustomers()
    {
        // Pivot table 'favorites', foreign key lokal 'product_id', foreign key relasi 'customer_id'
        return $this->belongsToMany(Customer::class, 'favorites', 'product_id', 'customer_id');
    }

    // --- Accessors & Scopes ---

    // ACCESSOR: Hitung rata-rata rating (dari spesifikasi)
    public function getAverageRatingAttribute()
    {
        // Menggunakan avg() pada collection yang sudah di-eager load atau menjalankan query baru
        return $this->reviews->avg('rating');
    }

    // SCOPE: Filter produk yang aktif
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}

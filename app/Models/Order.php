<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItems::class);
    }

    // SCOPE: Filter order yang pending
    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }
    
    // SCOPE: Filter order yang completed
    public function scopeCompleted($query)
    {
        return $query->where('status', 'Completed');
    }
}
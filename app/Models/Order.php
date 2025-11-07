<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

enum OrderStatus {
    case PENDING;
    case APPROVED;
}

enum OrderType {
    case NORMAL;
    case REGISTRATION;
    case MAINTENANCE;
    case UPGRADE;
}

class Order extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $fillable = [
        "amount",
        "distributor_id",
        "order_type",
        "stockist_id"
    ];

    public function distributor() {
        return $this->belongsTo(Distributor::class);
    }

    public function products() {
        return $this->belongsToMany(Product::class, "order_items", "order_id", "product_id")->withPivot(["quantity"]);
    }

    public function stockist() {
        return $this->belongsTo(Stockist::class);
    }
}

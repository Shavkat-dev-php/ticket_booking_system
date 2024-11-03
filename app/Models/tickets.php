<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'ticket_type',
        'ticket_price',
        'barcode',
        'ticket_quantity',
    ];

    public static function insert(array $tickets)
    {
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

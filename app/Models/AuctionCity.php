<?php

namespace App\Models;


use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AuctionCity extends Model
{
    use HasFactory;
    use HasSlug;

    protected $table = 'auction_cities';
    
    protected $fillable = [
        'state_id',
        'name',
        'pincode_id',
    ];
    protected $slugSource = 'name';
}

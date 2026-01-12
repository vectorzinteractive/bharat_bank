<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Auction extends Model
{
    use HasFactory;
    use HasSlug;
    protected $slugSource = 'description';
    protected $slugLength = 5;

    protected $table = 'auctions';

    protected $fillable = [
        'description',
        'slug',
        'state_id',
        'city_id',
        'price',
        'sq_ft'
    ];

    public function state()
    {
        return $this->belongsTo(AuctionState::class, 'state_id');
    }

    public function city()
    {
        return $this->belongsTo(AuctionCity::class, 'city_id');
    }
}

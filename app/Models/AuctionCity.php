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
        'slug',
        'pincode_id',
    ];
    protected $slugSource = 'name';

    public function pincode()
    {
        return $this->belongsTo(Pincode::class, 'pincode_id');
    }

    public function state()
    {
        return $this->belongsTo(AuctionState::class, 'state_id');
    }

    public function auctions()
    {
        return $this->hasMany(Auction::class, 'city_id');
    }
}

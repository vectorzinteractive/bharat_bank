<?php

namespace Modules\Location\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Location\Models\Town;
use Modules\Auction\Models\Auction;

class Pincode extends Model
{
    use HasFactory;

    protected $table = 'pincode';

     protected $fillable = [
        'town_id',
        'pincode',
    ];

    public function town()
    {
        return $this->belongsTo(Town::class);
    }

    public function auctions()
    {
        return $this->hasMany(Auction::class, 'pincode_id');
    }
}

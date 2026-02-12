<?php

namespace Modules\Auction\Models;

use App\Traits\HasSlug;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Location\Models\Pincode;
// use Modules\Auction\Database\Factories\AuctionFactory;

class Auction extends Model
{
    use HasFactory;
    use HasSlug;
    use Filterable;

    protected $slugSource = 'description';
    protected $slugLength = 5;

    protected $table = 'auctions';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'description',
        'slug',
        'pincode_id',
        'price',
        'sq_ft',
    ];

    public function pincode()
    {
        return $this->belongsTo(Pincode::class);
    }
}

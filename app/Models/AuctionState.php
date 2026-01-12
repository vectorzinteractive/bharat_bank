<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AuctionState extends Model
{

    use HasFactory;
    use HasSlug;
    protected $table = 'auction_states';
    protected $fillable = ['name', 'slug'];
    protected $slugSource = 'name';


    public function city()
    {
        return $this->belongsTo(AuctionCity::class, 'city_id');
    }


}

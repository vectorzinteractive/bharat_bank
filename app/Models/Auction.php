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
        'pincode_id',
        'price',
        'sq_ft',
    ];

    public function pincode()
    {
        return $this->belongsTo(Pincode::class);
    }
}

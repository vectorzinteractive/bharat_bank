<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Town extends Model
{
    use HasFactory;
    use HasSlug;
    protected $table = 'towns';
    protected $fillable = ['city_id', 'name', 'slug'];
    protected $slugSource = 'name';


    public function city()
    {
        return $this->belongsTo(City::class);
    }


    public function pincodes()
    {
        return $this->hasMany(Pincode::class, 'town_id');
    }
}

<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class City extends Model
{
    use HasFactory;
    use HasSlug;

    protected $table = 'cities';

    protected $fillable = [
        'state_id',
        'name',
        'slug',
    ];
    protected $slugSource = 'name';

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function towns()
    {
        return $this->hasMany(Town::class);
    }

}

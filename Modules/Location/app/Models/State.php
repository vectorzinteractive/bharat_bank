<?php

namespace Modules\Location\Models;
use App\Traits\HasSlug;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Location\Models\City;

class State extends Model
{
    use HasFactory;
    use HasSlug;

    protected $table = 'states';
    protected $fillable = ['name', 'slug'];
    protected $slugSource = 'name';

    public function city()
    {
        return $this->hasMany(City::class, 'state_id');
    }
}

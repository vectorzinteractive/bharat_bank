<?php

namespace Modules\UnclaimedDeposit\Models;

use App\Traits\HasSlug;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Location\Models\Pincode;

class UnclaimedDeposit extends Model
{
     use HasFactory;
    use HasSlug;
     use Filterable;
    protected $slugSource = 'name';
    protected $slugLength = 5;

    protected $table = 'unclaimed_deposits';
    protected $fillable = [
        'name',
        'slug',
        'udrn_id',
        'description',
        'pincode_id',
    ];

    public function pincode()
    {
        return $this->belongsTo(Pincode::class);
    }
}

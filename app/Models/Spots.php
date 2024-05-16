<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spots extends Model
{
    use HasFactory;

    protected $fillable = [
        'regional_id', 'name', 'address', 'serve', 'capacity'
    ];

    public function vaccines()
    {
        return $this->belongsToMany(Vaccines::class, 'spot_vaccines', 'spot_id', 'vaccine_id');
    }

    public function regional()
    {
        return $this->belongsTo(Regionals::class, 'regional_id');
    }
}

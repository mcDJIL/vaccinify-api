<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vaccinations extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'dose', 'date', 'society_id', 'spot_id', 'vaccine_id', 'doctor_id', 'officer_id'
    ];

    protected $hidden = [
        'id', 'society_id', 'spot_id', 'vaccine_id', 'doctor_id', 'officer_id'
    ];

    public function spot()
    {
        return $this->belongsTo(Spots::class, 'spot_id');
    }

    public function vaccine()
    {
        return $this->belongsTo(Vaccines::class, 'vaccine_id');
    }

    public function vaccinator()
    {
        return $this->belongsTo(Medicals::class, 'doctor_id');
    }
}

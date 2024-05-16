<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Societies extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'id_card_number', 'password', 'name', 'born_date', 'gender', 'address', 'regional_id', 'login_tokens'
    ];

    protected $hidden = [
        'id_card_number', 'password'
    ];

    public function regional()
    {
        return $this->belongsTo(Regionals::class, 'regional_id');
    }
}

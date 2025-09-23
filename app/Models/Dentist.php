<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dentist extends Model
{
    protected $fillable = [
        'nom_dentiste',
        'telephone'
    ];

    public function patients()
    {
        return $this->hasMany(Patient::class);
    }

    public function rendezVous()
    {
        return $this->hasMany(RendezVous::class);
    }
}

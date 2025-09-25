<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\RendezVous;
class Patient extends Model
{
    protected $fillable = [
        'nom_complet',
        'num_telephone',
        'age',
        'maladies',
        'notes',
    ];
    public function rendezvous ()
    {
        return $this->hasMany(RendezVous::class);
    }
    public function dentistes ()
    {
        return $this->belongsTo(Dentist::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Patient;

class RendezVous extends Model
{
    protected $fillable = [
        'dentiste_id',
        'date_heure',
        'motif',
        'patient_id',
    ];
    protected $casts = [
        'date_heure' => 'datetime',
    ];
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
    public function dentiste()
    {
        return $this->belongsTo(Dentist::class,'dentiste_id');
    }
}
